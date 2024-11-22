<x-admin title="Adjustment Create">
    <x-page-header head="Adjustment Create" />
    <div class="card p-3">
      <x-form method="post" action="{{ route('adjustment.update',$adjustment->id) }}">
      <div class="row">
        <div class="col-md-4">
          <x-input id="date" value="{{ $adjustment->created_at->format('Y-m-d') }}" type="date" required/>
        </div>
        <div class="col-md-12 mb-3">
          <label for="code">Product</label>
          <div class="input-group flex-nowrap">
            <span style="border-radius: 0;" class="input-group-text">
                <i class="fa fa-barcode"></i>
            </span>
            <input type="text" name="product" id="product_search"
              class="form-control" placeholder="Type here to search product">
          </div>
        </div>
        <div class="col-md-12 col-sm-12">
          <div class="card">
            <table class="table">
              <thead class="bg-gray">
                <tr>
                  <th>SL</th>
                  <th>Product</th>
                  <th>Type</th>
                  <th>Qty</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="find-product-table">
                @foreach ($adjustment->products as $key=>$item)
                    <tr>
                      <td>{{ ++$key }}</td>
                      <input type="hidden" name="product_id[]" value="{{ $item->product_id }}">
                      <td>{{ $item->product->name }} [{{ $item->product->code }}]</td>
                      <td>
                        <select name="type[]" id="type">
                          <option value="1" {{ $item->type == 1 ?'selected':'' }}>Addition</option>  
                          <option value="0" {{ $item->type == 0 ?'selected':'' }}>Subtraction</option>  
                        </select>
                      </td>
                      <td><input style="width:100px" type="number" min="1" name="qty[]" value="{{ $item->qty }}" id="quantity"></td>
                    </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="col-md-12 text-right">
          <x-button value="Submit" />
        </div>
        
      </div>
      </x-form>
    </div>
@push('js')
  <script>
    $(document).ready(function() {

      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
      
      $("#product_search").autocomplete({
        source: function(request, response) {
          $.ajax({
            url: "{{ route('purchase.getProduct') }}",
            type: 'post',
              dataType: "json",
              data: {
                _token: CSRF_TOKEN,
                search: request.term
              },
              success: function(data) {
                if (data.length === 1) {
                  // Automatically select the only result
                  var item = data[0];
                  addProductToTable(item);
                  $('#product_search').val('');  // Clear the input field
                  $('#product_search').autocomplete('close');  // Close the autocomplete suggestions
                } else {
                  response($.map(data, function(item) {
                    return {
                      label: item.value + ' [' + item.code + ']',
                      value: item.value,
                      id: item.id,
                      code: item.code,
                      cost: item.cost,
                      stock: item.stock,
                      price: item.price
                    };
                  }));
                }
              }
            });
          },
          select: function(event, ui) {
            addProductToTable(ui.item);
            $('#product_search').val('');  // Clear the input field
            return false;
          }
      });

      function addProductToTable(item) {
          var found = false;
          $('#find-product-table tr').each(function() {
              var row = $(this);
              var productText = row.find('td:eq(1)').text();
              if (productText.includes(item.code)) {
                  // Increment quantity if product is already in the table
                  var qtyInput = row.find('input[name="qty[]"]');
                  qtyInput.val(parseInt(qtyInput.val()) + 1);
                  found = true;
              }
          });

          if (!found) {
              var newRow = `
                  <tr>
                      <td>#</td>
                      <td>${item.value} [${item.code}]</td>
                      <input type="hidden" name="product_id[]" value="${item.id}">
                      <td>
                        <select name="type[]" id="type">
                          <option value="1">Addition</option>  
                          <option value="0">Subtraction</option>  
                        </select>
                        </td>
                      <td><input style="width:100px" type="number" min="1" name="qty[]" value="1" id="quantity"></td>
                      <td><button type="button" class="btn-danger" id="remove">X</button></td>
                  </tr>
              `;
              $('#find-product-table').append(newRow);
          }
      }
    
      
      $(document).on('click', '#remove', function() {
        $(this).closest('tr').remove();
        calculation();
      });

  });

  </script>
@endpush
</x-admin>


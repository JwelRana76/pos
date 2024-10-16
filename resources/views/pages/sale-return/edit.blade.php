<x-admin title="Sale Return Edit">
    <x-page-header head="Sale Return Edit" />
    <div class="card p-3">
      <x-form method="post" action="{{ route('sale.return.update',$sale_return->id) }}">
      <div class="row">
        <div class="col-md-4">
          <x-input id="date" value="{{ $sale_return->created_at->format('Y-m-d') }}" type="date" required/>
        </div>
        <x-input id="grand_total" type="hidden" required/>
        <div class="col-md-4">
          <x-select id="customer" selectedId="{{ $sale_return->customer_id }}" required :options="$customers" />
        </div>
        <div class="col-md-12 mb-3">
          <label for="code">Product</label>
          <div class="input-group flex-nowrap">
            <span style="border-radius: 0;" class="input-group-text" id="category_code_gen">
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
                  <th>Qty</th>
                  <th>Price</th>
                  <th>Sub Total</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="find-product-table">
                @foreach ($sale_return->sale_product_return as $item)
                    <tr>
                      <td>#</td>
                      <td>{{$item->product->name ?? null}} [{{$item->product->code ?? nill}}]</td>
                      <input type="hidden" name="product_id[]" value="{{ $item->product_id }}">
                      <td><input style="width:100px" type="number" min="1" name="qty[]" value="{{ $item->qty }}" id="quantity"></td>
                      <td><input style="width:100px" name="price[]" id="price" value="{{ $item->unit_price }}"></td>
                      <td class="sub_total">{{ $item->unit_price * $item->qty }}</td>
                      <td><button type="button" class="btn-danger" id="remove">X</button></td>
                    </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th>Total Price</th>
                  <th id="total_price_show"></th>
                  <th></th>
                </tr>
              </tfoot>
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
      calculation();
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
                  sutotal_increment(row);
              }
          });

          if (!found) {
              var newRow = `
                  <tr>
                      <td>#</td>
                      <td>${item.value} [${item.code}]</td>
                      <input type="hidden" name="product_id[]" value="${item.id}">
                      <td><input style="width:100px" type="number" min="1" name="qty[]" value="1" id="quantity"></td>
                      <td><input style="width:100px" name="price[]" id="price" value="${item.price}"></td>
                      <td class="sub_total">${item.price}</td>
                      <td><button type="button" class="btn-danger" id="remove">X</button></td>
                  </tr>
              `;
              $('#find-product-table').append(newRow);
          }
          calculation();
      }
    
      $(document).on('input', 'input[name="price[]"]', function() {
        var row = $(this).closest('tr');
        sutotal_increment(row);
      });
      $(document).on('input', 'input[name="qty[]"]', function() {
        var row = $(this).closest('tr');
        sutotal_increment(row);
      });
      function sutotal_increment(row){
        var qty = parseInt(row.find('#quantity').val());
        var price = parseInt(row.find('#price').val()); // Assuming there's a class named 'stock' for the stock cell
        var subtotal = qty * price;
        row.find('.sub_total').text(subtotal);
        calculation();
      }
      $(document).on('click', '#remove', function() {
        $(this).closest('tr').remove();
        calculation();
      });

      function calculation(){
        var total_price = 0;
        $('#find-product-table tr').each(function() {
          var row = $(this);
          var subtotal = parseFloat(row.find('.sub_total').text());
          total_price += subtotal;
        });
        $('#total_price_show').text(total_price.toFixed(2));
        $('#grand_total').val(total_price.toFixed(2));
        
      }
  });

  </script>
@endpush
</x-admin>


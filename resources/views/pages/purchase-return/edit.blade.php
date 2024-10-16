<x-admin title="Purchase Return Update">
    <x-page-header head="Purchase Return Update" />
    <div class="card p-3">
      <x-form method="post" action="{{ route('purchase.return.update',$purchase_return->id) }}">
      <div class="row">
        <div class="col-md-4">
          <x-input id="date" value="{{ $purchase_return->created_at->format('Y-m-d') }}" type="date" required/>
        </div>
        <x-input id="grand_total" type="hidden" required/>
        <div class="col-md-4">
          <x-input id="voucher_no" value="{{ $purchase_return->voucher_no }}" required/>
        </div>
        <div class="col-md-4">
          <x-select id="supplier" selectedId="{{ $purchase_return->supplier_id }}" required :options="$suppliers" />
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
                  <th>Cost</th>
                  <th>Sub Total</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="find-product-table">
                @foreach ($purchase_return->purchase_product_return as $item)
                  @php
                    $product = App\Models\Product::findOrFail($item->product_id);
                  @endphp
                    <tr>
                      <td>#</td>
                      <td>{{$item->product->name ?? null}} [{{$item->product->code ?? nill}}]</td>
                      <input type="hidden" name="product_id[]" value="{{ $item->product_id }}">
                      <input type="hidden" name="stock[]" id="stock" value="{{ $product->stock + $item->qty}}">
                      <td><input style="width:100px" type="number" min="1" name="qty[]" value="{{ $item->qty }}" id="quantity"></td>
                      <td><input style="width:100px" name="cost[]" id="cost" value="{{ $item->unit_cost }}"></td>
                      <td class="sub_total">{{ $item->unit_cost * $item->qty }}</td>
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
      calculation();
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
      
      $("#product_search").autocomplete({
        source: function(request, response) {
          $.ajax({
            url: "{{ route('sale.getProduct') }}",
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
                      price: item.price,
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
                  var stock = row.find('input[name="stock[]"]');
                  if(stock > qtyInput){
                    qtyInput.val(parseInt(qtyInput.val()) + 1);
                  }else{
                    alert('Out of Stock');
                  }
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
                      <input type="hidden" name="stock[]" id="stock" value="${item.stock}">
                      <td><input style="width:100px" type="number" min="1" name="qty[]" value="1" id="quantity"></td>
                      <td><input style="width:100px" name="cost[]" id="price" value="${item.cost}"></td>
                      <td class="sub_total">${item.cost}</td>
                      <td><button type="button" class="btn-danger" id="remove">X</button></td>
                  </tr>
              `;
              $('#find-product-table').append(newRow);
          }
          calculation();
      }
    
      $(document).on('input', 'input[name="cost[]"]', function() {
        var row = $(this).closest('tr');
        sutotal_increment(row);
      });
      $(document).on('input', 'input[name="qty[]"]', function() {
        var row = $(this).closest('tr');
        var stock = parseInt(row.find('#stock').val());
        if($(this).val() > stock){
          alert('Out of Stock')
          $(this).val(stock);
        }
        sutotal_increment(row);
      });
      function sutotal_increment(row){
        var qty = parseInt(row.find('#quantity').val());
        var cost = parseInt(row.find('#cost').val()); // Assuming there's a class named 'stock' for the stock cell
        var subtotal = qty * cost;
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


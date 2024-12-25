<x-admin title="Purchase Create">
    <x-page-header head="Purchase Create" />
    <div class="card p-3">
      <x-form method="post" id="purchaseForm" action="{{ route('purchase.store') }}">
      <div class="row">
        <div class="col-md-4">
          <x-input id="date" value="{{ date('Y-m-d') }}" type="date" required/>
        </div>
        <div class="col-md-4">
          <x-select id="supplier" required :options="$suppliers" has-modal modal-open-id="supplier_model"  />
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
        <div class="col-md-8 col-sm-12">
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

              </tbody>
            </table>
          </div>
        </div>
        <div class="col-md-4 col-sm-12">
          <x-small-card header="Calculation Part">
              <x-inline-input id="total_price" readonly />
              <div class="mb-3">
                  <div class="input-group">
                      <div class="input-group-prepend" style="width: 40%">
                          <div class="input-group-text w-100">Discount</div>
                      </div>
                      <input type="text" class="form-control" max="100" id="discount_percent" name="discount_percent" placeholder="%">
                      <input type="text" class="form-control" id="discount_amount" name="discount_amount"  placeholder="Amount">
                  </div>
              </div>
              <x-inline-input id="shipping_cost" />
              <hr>
              <x-inline-input id="grand_total" readonly />
              <x-inline-input id="previous_due" readonly />
              <hr>
              <x-inline-input id="total_payable" readonly />
              <x-inline-input id="paid_amount" />
              <x-inline-input id="due" readonly />
          </x-small-card>
        </div>
        <div class="col-md-12 text-right">
          <x-button value="Submit" />
        </div>
        
      </div>
      </x-form>
    </div>
    @php
        $districts = App\Models\District::get();
    @endphp
    <x-large-modal id="supplier_model">
      <form id="supplierForm">
        @csrf
        <div class="row">
        <div class="col-md-6">
          <x-input id="name" required/>
        </div>
        <div class="col-md-6">
          <x-select id="district" :options="$districts"/>
        </div>
        <div class="col-md-6">
          <x-input id="contact" required/>
        </div>
        <div class="col-md-6">
          <x-input id="email" type="email"/>
        </div>
          <x-input type="hidden" id="opening_due" value="0" />
        <div class="col-md-12">
          <x-input id="address" required/>
        </div>
        <div class="col-md-12">
          <x-button value="Save" />
        </div>
        
      </div>
      </form>
    </x-large-modal>
    @php
      $supplierss = App\Models\Supplier::get()->map(function($supplier) {
          return [
              'id' => $supplier->id,
              'due' => $supplier->due, // This will call the getDueAttribute method
          ];
      })->toArray();
    @endphp
@push('js')
  <script>
    $(document).ready(function() {
      $('#supplierForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission
        var formData = {
        _token: $('input[name="_token"]').val(),
        district: $('#district').val(),
        name: $('#name').val(),
        contact: $('#contact').val(),
        email: $('#email').val(),
        address: $('#address').val(),
        };

        // Use AJAX to send the data to the server
        $.ajax({
        type: 'POST',
        url: '{{ route("supplier.supplierstore") }}',
        data: formData,
        success: function(response) {
            // Handle the success response here
          console.log(response);
          $('#supplier_model').modal('hide');
          var selectfield = '#purchaseForm select[name="supplier"]';
          updateCategorySelect(response,selectfield);

          var lastCategoryId = response[response.length - 1].id;
          var selectElement = $('#purchaseForm select[name="supplier"]'); // Use the correct selector
          console.log(lastCategoryId);
          selectElement.selectpicker('val', lastCategoryId);
        },
        error: function(error) {
            // Handle any errors here
          console.error(error);
        }
        });
      });
      
    })
    function updateCategorySelect(categories, selectfiled) {
          // Assuming "categories" is an array of objects with "id" and "name" fields
          var $categorySelect = $(selectfiled);
          $categorySelect.empty(); // Clear the current options

          // Add the new options
          categories.forEach(function(category) {
              $categorySelect.append(
                  $('<option>', {
                      value: category.id,
                      text: category.name
                  })
              );
          });

          // Update the selectpicker
          $categorySelect.selectpicker('refresh');
      }
    $(document).ready(function() {

      var suppliers = @json($supplierss);
      $(document).on('change', '#supplier', function() {
          var selectedSupplierId = $(this).val();
          $.each(suppliers, function(index, item) {
              if (item.id == selectedSupplierId) {
                  $('#previous_due').val(item.due);
                  console.log('Supplier found:', item); // Debugging purpose
              }
          });
      });

      toggleDiscountAmount();
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
                      <td><input style="width:100px" name="cost[]" id="cost" value="${item.cost}"></td>
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
        sutotal_increment(row);
      });
      function sutotal_increment(row){
        var qty = parseFloat(row.find('#quantity').val());
        var cost = parseFloat(row.find('#cost').val()); // Assuming there's a class named 'stock' for the stock cell
        var subtotal = qty * cost;
        row.find('.sub_total').text(subtotal.toFixed(2));
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
        $('#total_price').val(total_price.toFixed(2));
        
        var previous_due = parseFloat($('#previous_due').val() || 0);
        var discount = parseFloat($('#discount_amount').val() || 0);
        var shipping_cost = parseFloat($('#shipping_cost').val() || 0);
        var paid = parseFloat($('#paid_amount').val() || 0);
        var grand_total = total_price - discount + shipping_cost;
        var payable = grand_total;
        var due = payable - paid;
        
        $('#grand_total').val(grand_total.toFixed(2));
        $('#total_payable').val(payable.toFixed(2));
        $('#due').val(due.toFixed(2));
        
        if(total_price < 1){
          $('#discount_amount').val(null);
          $('#discount_percent').val(null);
          $('#grand_total').val(null);
          $('#total_payable').val(null);
          $('#paid_amount').val(null);
          $('#shipping_cost').val(null);
          $('#due').val(null);
        }
        if(paid > payable){
          $('#paid_amount').val(payable);
          calculation();
        }
        
        toggleDiscountAmount();
      }

      function toggleDiscountAmount() {
        var totalPrice = parseFloat($('#total_price').val());
        if (totalPrice > 0) {
            $('#discount_amount').prop('disabled', false);
            $('#discount_percent').prop('disabled', false);
            $('#paid_amount').prop('disabled', false);
            $('#shipping_cost').prop('disabled', false);
        } else {
            $('#discount_amount').prop('disabled', true);
            $('#discount_percent').prop('disabled', true);
            $('#paid_amount').prop('disabled', true);
            $('#shipping_cost').prop('disabled', true);
        }
      }
      function discount_calculate(InputValue){
        var total_price = parseFloat($('#total_price').val());
        
        var amount = parseFloat($('#discount_amount').val() || 0);
        var percent = parseFloat($('#discount_percent').val() || 0);
        if(InputValue == amount){
          var parcentage = (InputValue / total_price) * 100;
          $('#discount_percent').val(parcentage.toFixed(1));
        }else{
          var amount = (total_price * InputValue) / 100;
          $('#discount_amount').val(amount);
        }
        if(total_price < amount || total_price < 1){
          $('#discount_amount').val(null);
          $('#discount_percent').val(null);
        }
        calculation();
      }
      $('input[name="discount_amount"],input[name="discount_percent"]').on('input',function(){
        discount_calculate(parseFloat($(this).val()));
      });
      $(document).on('input', '#paid_amount', function() {
        var payable = parseFloat($('#total_payable').val());
        var paid = parseFloat($(this).val());
        if(paid > payable){
          alert(`Can't pay more than payable amount`);
          $(this).val(payable);
        }
        calculation();
      })
      $(document).on('input', '#shipping_cost', function() {
        calculation();
      })
  });

  </script>
@endpush
</x-admin>


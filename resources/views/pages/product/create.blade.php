<x-admin title="Product Create">
    <x-page-header head="Product Create" />
    <div class="card p-3">
      <x-form method="post" action="{{ route('product.store') }}">
      <div class="row">
        <div class="col-md-4">
          <x-input id="name" required/>
        </div>
        <div class="col-md-4">
          <label for="code">Code</label>
          <div class="input-group flex-nowrap">
              <input type="text" required name="code" value="{{ old('code') }}" id="code"
                  class="form-control" placeholder="Product Code">
              <span style="cursor: pointer; border-radius: 0;" data-bs-toggle="modal"
                  data-bs-target="#addCustomerModal" class="input-group-text" id="product_code_gen">
                  <i class="fa fa-barcode"></i>
              </span>
          </div>
        </div>
        <div class="col-md-4">
          <x-select id="category" name="category_id" :options="$categories" has-modal modal-open-id="category_model" />
        </div>
        <div class="col-md-4">
          <x-select id="brand" name="brand_id" :options="$brands" has-modal modal-open-id="brand_model" />
        </div>
        <div class="col-md-4">
          <x-select id="size" name="size_id" :options="$sizes" has-modal modal-open-id="size_model" />
        </div>
        <div class="col-md-4">
          <x-select id="unit" name="unit_id" :options="$units" has-modal modal-open-id="unit_model" />
        </div>
        <div class="col-md-4">
          <x-input id="cost" required/>
        </div>
        <div class="col-md-4">
          <x-input id="price" required/>
        </div>
        <div class="col-md-4">
          <x-input id="alert_qty" required/>
        </div>
        <div class="col-md-4">
          <x-input id="image" accept="image/*" type="file" />
        </div>
        <div class="col-md-12">
          <x-button value="Save" />
        </div>
        
      </div>
      </x-form>
    </div>

    <x-modal id="category_model">
      <form id="categoryForm">
        @csrf
        <x-input id="category_name" label="name" />
        <label for="code">Code</label>
        <div class="input-group flex-nowrap">
          <input type="text" required name="code" id="category_code"
              class="form-control" placeholder="Category Code">
          <span style="cursor: pointer; border-radius: 0;" class="input-group-text" id="category_code_gen">
              <i class="fa fa-barcode"></i>
          </span>
        </div>
        <x-button value="Save" />
      </form>
    </x-modal>
    <x-modal id="brand_model">
      <form id="brandForm">
        @csrf
        <x-input id="brand_name" label="name" />
        <label for="code">Code</label>
        <div class="input-group flex-nowrap">
          <input type="text" required name="code" id="brand_code"
              class="form-control" placeholder="Category Code">
          <span style="cursor: pointer; border-radius: 0;" class="input-group-text" id="brand_code_gen">
              <i class="fa fa-barcode"></i>
          </span>
        </div>
        <x-button value="Save" />
      </form>
    </x-modal>
    <x-modal id="size_model">
      <form id="sizeForm">
        @csrf
        <x-input id="size_name" label="name" />
        <label for="code">Code</label>
        <div class="input-group flex-nowrap">
          <input type="text" required name="code" id="size_code"
              class="form-control" placeholder="Category Code">
          <span style="cursor: pointer; border-radius: 0;" class="input-group-text" id="size_code_gen">
              <i class="fa fa-barcode"></i>
          </span>
        </div>
        <x-button value="Save" />
      </form>
    </x-modal>
    <x-modal id="unit_model">
      <form id="unitForm">
        @csrf
        <x-input id="unit_name" label="name" />
        <label for="code">Code</label>
        <div class="input-group flex-nowrap">
          <input type="text" required name="code" id="unit_code"
              class="form-control" placeholder="Category Code">
          <span style="cursor: pointer; border-radius: 0;" class="input-group-text" id="unit_code_gen">
              <i class="fa fa-barcode"></i>
          </span>
        </div>
        <x-button value="Save" />
      </form>
    </x-modal>

@push('js')
  <script>
    $(document).ready(function() {
      $('#product_code_gen').on('click', function() {
        const inputEl = $(this).prev();
        const random = (Math.random() + 1).toString(10).substring(7);
        inputEl.val(random);
      });
      $('#category_code_gen').on('click', function() {
        const inputEl = $(this).prev();
        const random = (Math.random() + 1).toString(10).substring(14);
        inputEl.val(random);
      });
      $('#brand_code_gen').on('click', function() {
        const inputEl = $(this).prev();
        const random = (Math.random() + 1).toString(10).substring(14);
        inputEl.val(random);
      });
      $('#size_code_gen').on('click', function() {
        const inputEl = $(this).prev();
        const random = (Math.random() + 1).toString(10).substring(14);
        inputEl.val(random);
      });
      $('#unit_code_gen').on('click', function() {
        const inputEl = $(this).prev();
        const random = (Math.random() + 1).toString(10).substring(14);
        inputEl.val(random);
      });

      $('#categoryForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Collect the form data
        var formData = {
        _token: $('input[name="_token"]').val(),
        id: null,
        name: $('#category_name').val(),
        code: $('#category_code').val()
        };

        // Use AJAX to send the data to the server
        $.ajax({
        type: 'POST',
        url: '{{ route("category.categorystore") }}',
        data: formData,
        success: function(response) {
            // Handle the success response here
          console.log(response);
          $('#category_model').modal('hide');
          var selectfield = '#category';
          updateCategorySelect(response,selectfield);

          var lastCategoryId = response[response.length - 1].id;
          var selectElement = $('#category'); // Use the correct selector
          console.log(lastCategoryId);
          selectElement.selectpicker('val', lastCategoryId);
        },
        error: function(error) {
            // Handle any errors here
          console.error(error);
        }
        });
      });
      $('#brandForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Collect the form data
        var formData = {
        _token: $('input[name="_token"]').val(),
        id: null,
        name: $('#brand_name').val(),
        code: $('#brand_code').val()
        };

        // Use AJAX to send the data to the server
        $.ajax({
        type: 'POST',
        url: '{{ route("brand.brandstore") }}',
        data: formData,
        success: function(response) {
            // Handle the success response here
          console.log(response);
          $('#brand_model').modal('hide');
          var selectfield = '#brand';
          updateCategorySelect(response,selectfield);

          var lastCategoryId = response[response.length - 1].id;
          var selectElement = $('#brand'); // Use the correct selector
          console.log(lastCategoryId);
          selectElement.selectpicker('val', lastCategoryId);
        },
        error: function(error) {
            // Handle any errors here
          console.error(error);
        }
        });
      });
      $('#sizeForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Collect the form data
        var formData = {
        _token: $('input[name="_token"]').val(),
        id: null,
        name: $('#size_name').val(),
        code: $('#size_code').val()
        };

        // Use AJAX to send the data to the server
        $.ajax({
        type: 'POST',
        url: '{{ route("size.sizestore") }}',
        data: formData,
        success: function(response) {
            // Handle the success response here
          console.log(response);
          $('#size_model').modal('hide');
          var selectfield = '#size';
          updateCategorySelect(response,selectfield);

          var lastCategoryId = response[response.length - 1].id;
          var selectElement = $('#size'); // Use the correct selector
          console.log(lastCategoryId);
          selectElement.selectpicker('val', lastCategoryId);
        },
        error: function(error) {
            // Handle any errors here
          console.error(error);
        }
        });
      });
      $('#unitForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Collect the form data
        var formData = {
        _token: $('input[name="_token"]').val(),
        id: null,
        name: $('#unit_name').val(),
        code: $('#unit_code').val()
        };

        // Use AJAX to send the data to the server
        $.ajax({
        type: 'POST',
        url: '{{ route("unit.unitstore") }}',
        data: formData,
        success: function(response) {
            // Handle the success response here
          console.log(response);
          $('#unit_model').modal('hide');
          var selectfield = '#unit';
          updateCategorySelect(response,selectfield);

          var lastCategoryId = response[response.length - 1].id;
          var selectElement = $('#unit'); // Use the correct selector
          console.log(lastCategoryId);
          selectElement.selectpicker('val', lastCategoryId);
        },
        error: function(error) {
            // Handle any errors here
          console.error(error);
        }
        });
      });
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
    })
  </script>
@endpush
</x-admin>


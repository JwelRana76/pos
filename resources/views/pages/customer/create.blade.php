<x-admin title="Customer Create">
    <x-page-header head="Customer Create" />
    <div class="card p-3">
      <x-form method="post" action="{{ route('customer.store') }}">
      <div class="row">
        <div class="col-md-4">
          <x-input id="name" required/>
        </div>
        <div class="col-md-4">
          <x-select id="district" :options="$districts" has-modal modal-open-id="district_model" />
        </div>
        <div class="col-md-4">
          <x-input id="contact" required/>
        </div>
        <div class="col-md-4">
          <x-input id="email" type="email"/>
        </div>
        <div class="col-md-4">
          <x-input id="opening_due"/>
        </div>
        <div class="col-md-4">
          <x-input id="address" required/>
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

    <x-modal id="district_model">
      <form id="districtForm">
        @csrf
        <x-select id="division_id" :options="$divisions" />
        <x-input id="district_name" label="name" />
        <label for="code">Code</label>
        <div class="input-group flex-nowrap">
          <input type="text" required name="code" id="district_code"
              class="form-control" placeholder="district Code">
          <span style="cursor: pointer; border-radius: 0;" class="input-group-text" id="district_code_gen">
              <i class="fa fa-barcode"></i>
          </span>
        </div>
        <x-button value="Save" />
      </form>
    </x-modal>

@push('js')
  <script>
    $(document).ready(function() {
      $('#district_code_gen').on('click', function() {
        const inputEl = $(this).prev();
        const random = (Math.random() + 1).toString(10).substring(14);
        inputEl.val(random);
      });
      $('#districtForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Collect the form data
        var formData = {
        _token: $('input[name="_token"]').val(),
        id: null,
        division_id: $('#division_id').val(),
        name: $('#district_name').val(),
        code: $('#district_code').val()
        };

        // Use AJAX to send the data to the server
        $.ajax({
        type: 'POST',
        url: '{{ route("district.districtstore") }}',
        data: formData,
        success: function(response) {
            // Handle the success response here
          console.log(response);
          $('#district_model').modal('hide');
          var selectfield = '#district';
          updateCategorySelect(response,selectfield);

          var lastCategoryId = response[response.length - 1].id;
          var selectElement = $('#district'); // Use the correct selector
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


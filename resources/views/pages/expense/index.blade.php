<x-admin title="Expense">
    <x-page-header head="Expense" />
    <div class="row">
        <div class="col-md-4">
            <div class="card p-3">
                <x-form method="post" action="{{ route('expense.store') }}">
                    <x-input id="id" type="hidden" value="{{ $expense->id ?? null }}" />
                    <x-select id="category_id" label="Category" selectedId="{{ $expense->category_id ?? null }}" name="category_id" :options="$categories" has-modal modal-open-id="category" />
                    <x-input id="amount" label="amount" value="{{ $expense->amount ?? null }}" />
                    @if(count(accounts()) > 1)
                    <x-select id="account_id" label="Account" selectedId="{{ $expense->account_id ?? null }}" :options="accounts()" />
                    @endif
                    <x-text-area id='note' name="note" value="{{ $expense->note ?? null  }}" />
                    <x-button value="Save" />
                </x-form>
            </div>
        </div>
        <div class="col-md-8">
            <x-data-table dataUrl="/expense" id="expenses" :columns="$columns" />
        </div>
    </div>
    <x-modal id="category">
        <form id="categoryForm">
            @csrf
            <x-input id="id" type="hidden" />
            <x-input id="category_name" label="name" />
            <label for="code">Code</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="category_code" id="category_code" >
                <div class="input-group-append">
                    <input type="button" class="input-group-text" id="generate_code" value="Generat" >
                </div>
            </div>
            <x-button value="Save" />
        </form>
    </x-modal>
    @push('js')
        <script>
            $('#generate_code').on('click', function () {
                var min = 100;
                var max = 9999;
                var randomCode = Math.floor(Math.random() * (max - min + 1)) + min;
                $('input[name="category_code"]').val(randomCode);
            });

            $(document).ready(function() {
                // Add an event listener to the form's submit event
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
                    url: '{{ route("expense-category.categprystore") }}',
                    data: formData,
                    success: function(response) {
                        // Handle the success response here
                        console.log(response);
                        $('#category').modal('hide');
                        updateCategorySelect(response);

                        var lastCategoryId = response[response.length - 1].id;
                        var selectElement = $('#category_id'); // Use the correct selector
                        console.log(lastCategoryId);
                        selectElement.selectpicker('val', lastCategoryId);
                    },
                    error: function(error) {
                        // Handle any errors here
                        console.error(error);
                    }
                    });
                });
                
            });
            function updateCategorySelect(categories) {
                // Assuming "categories" is an array of objects with "id" and "name" fields
                var $categorySelect = $('#category_id');
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
        </script>
    @endpush
</x-admin>

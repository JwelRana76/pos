<x-admin title="Income">
    <x-page-header head="Income" />
    <div class="row">
        <div class="col-md-4">
            <div class="card p-3">
                <x-form method="post" action="{{ route('income.store') }}">
                    <x-input id="id" type="hidden" value="{{ $income->id ?? null }}" />
                    <x-select id="category_id" label="Category" selectedId="{{ $income->category_id ?? null }}" name="category_id" :options="$categories" has-modal modal-open-id="category" />
                    <x-input id="amount" label="amount" value="{{ $income->amount ?? null }}" />
                    @if(count(accounts()) > 1)
                    <x-select id="account_id" label="Account" selectedId="{{ $income->account_id ?? null }}" :options="accounts()" />
                    @endif
                    <x-text-area id='note' name="note" value="{{ $income->note ?? null  }}" />
                    <x-button value="Save" />
                </x-form>
            </div>
        </div>
        <div class="col-md-8">
            <x-data-table dataUrl="/income" id="incomes" :columns="$columns" />
        </div>
    </div>
    <x-modal id="category">
        <form id="categoryForm">
            @csrf
            <x-input id="id" type="hidden" />
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
    @push('js')
        <script>
            $('#category_code_gen').on('click', function () {
                const inputEl = $(this).prev();
                const random = (Math.random() + 1).toString(10).substring(14);
                inputEl.val(random);
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
                    url: '{{ route("income-category.categprystore") }}',
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

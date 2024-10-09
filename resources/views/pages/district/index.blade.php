<x-admin title="District">
    <x-page-header head="District" />
    <div class="row">
        <div class="col-md-4">
            <div class="card p-3">
                <x-form method="post" action="{{ route('district.store') }}">
                    <x-input id="id" type="hidden" value="{{ $district->id ?? null }}" />
                    <x-select id="division_id" label="Division" selectedId="{{ $district->division_id ?? null }}" name="division_id" :options="$divisions" has-modal modal-open-id="division" />
                    <x-input id="name" label="name" value="{{ $district->name ?? null }}" />
                    <label for="code">Code</label>
                    <div class="input-group flex-nowrap">
                        <input type="text" required name="code" id="district_code"
                            class="form-control" placeholder="Category Code">
                        <span style="cursor: pointer; border-radius: 0;" class="input-group-text" id="district_code_gen">
                            <i class="fa fa-barcode"></i>
                        </span>
                    </div>
                    <x-button value="Save" />
                </x-form>
            </div>
        </div>
        <div class="col-md-8">
            <x-data-table dataUrl="/expense-category" id="expenseCategories" :columns="$columns" />
        </div>
    </div>
    <x-modal id="division">
        <form id="divisionForm">
            @csrf
            <x-input id="id" type="hidden" />
            <x-input id="division_name" label="name" />
            <label for="code">Code</label>
            <div class="input-group flex-nowrap">
                <input type="text" required name="code" id="division_code"
                    class="form-control" placeholder="Category Code">
                <span style="cursor: pointer; border-radius: 0;" class="input-group-text" id="division_code_gen">
                    <i class="fa fa-barcode"></i>
                </span>
            </div>
            <x-button value="Save" />
        </form>
    </x-modal>
    @push('js')
        <script>
            $(document).ready(function() {
                $('#division_code_gen').on('click', function() {
                    const inputEl = $(this).prev();
                    const random = (Math.random() + 1).toString(10).substring(14);
                    inputEl.val(random);
                });
                $('#district_code_gen').on('click', function() {
                    const inputEl = $(this).prev();
                    const random = (Math.random() + 1).toString(10).substring(14);
                    inputEl.val(random);
                });

                $(document).ready(function() {
                    // Add an event listener to the form's submit event
                    $('#divisionForm').on('submit', function(e) {
                        e.preventDefault(); // Prevent the default form submission

                        // Collect the form data
                        var formData = {
                        _token: $('input[name="_token"]').val(),
                        id: null,
                        name: $('#division_name').val(),
                        code: $('#division_code').val()
                        };

                        // Use AJAX to send the data to the server
                        $.ajax({
                        type: 'POST',
                        url: '{{ route("division.divisionstore") }}',
                        data: formData,
                        success: function(response) {
                            // Handle the success response here
                            console.log(response);
                            $('#division').modal('hide');
                            updateDivisionSelect(response);

                            var lastDivisionId = response[response.length - 1].id;
                            var selectElement = $('#division_id'); // Use the correct selector
                            console.log(lastDivisionId);
                            selectElement.selectpicker('val', lastDivisionId);
                        },
                        error: function(error) {
                            // Handle any errors here
                            console.error(error);
                        }
                        });
                    });
                    
                });
                function updateDivisionSelect(categories) {
                    // Assuming "categories" is an array of objects with "id" and "name" fields
                    var $categorySelect = $('#division_id');
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
            
            });
        </script>
    @endpush
</x-admin>

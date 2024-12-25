<x-admin title="Bank">
    <x-page-header head="Bank" />
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3">
                <x-form method="post" action="{{ route('bank.store') }}">
                    <x-input id="id" type="hidden" value="{{ $bank->id ?? null }}" />
                    <x-input id="holder_name" value="{{ $bank->holder_name ?? null }}" />
                    <label for="account_no">Account No</label>
                    <div class="input-group flex-nowrap mb-3">
                        <input type="text" required name="account_no" id="account_no"
                        class="form-control" placeholder="Account No" value="{{ $bank->account_no ?? null }}">
                        <span style="cursor: pointer; border-radius: 0;" class="input-group-text" id="account_no_gen">
                            <i class="fa fa-barcode"></i>
                        </span>
                    </div>
                    <x-input id="bank_name" value="{{ $bank->bank_name ?? null }}" />
                    <x-input id="branch" value="{{ $bank->branch ?? null }}" />
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                            <input type="hidden" name="is_default" value="0">
                            <input type="checkbox" @isset($bank)
                                {{ $bank->is_default == true?'checked':'' }}
                            @endisset name="is_default" value="1" aria-label="Checkbox for following text input">
                            </div>
                        </div>
                        <label for="is_default">Is Default</label>
                    </div>
                    <x-button value="Save" />
                </x-form>
            </div>
        </div>
        <div class="col-md-9">
            <x-data-table dataUrl="/accounting/bank" id="banks" :columns="$columns" />
        </div>
    </div>
    @push('js')
        <script>
           $(document).ready(function () {
                // Re-initialize Bootstrap Toggle on table draw
                $('#banks').on('draw.dt', function () {
                    $('.toggle-switch').bootstrapToggle();
                });

                // Initialize Bootstrap Toggle for the first time
                $('.toggle-switch').bootstrapToggle();
            });
            $(document).on('change', '.toggle-switch', function () {
                let itemId = $(this).data('id');
                let status = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                    url: '/accounting/bank/update-status', // Your update route
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'), // Include CSRF token
                        id: itemId,
                        status: status,
                    },
                    success: function (response) {
                        alert(response.message); // Optional: Show a success message
                        location.reload();
                    },
                    error: function (error) {
                        console.error(error);
                        alert('An error occurred!'); // Optional: Show an error message
                    }
                });
            });
            $('#account_no_gen').on('click', function () {
                const inputEl = $(this).prev();
                const random = (Math.random() + 1).toString(10).substring(7);
                inputEl.val(random);
            });
        </script>
    @endpush
</x-admin>

<x-admin title="Account">
    <x-page-header head="Account" />
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3">
                <x-form method="post" action="{{ route('account.store') }}">
                    <x-input id="id" type="hidden" value="{{ $account->id ?? null }}" />
                    <x-input id="name" value="{{ $account->name ?? null }}" />
                    <label for="account_no">Account No</label>
                    <div class="input-group flex-nowrap mb-3">
                    <input type="text" required name="account_no" id="account_no"
                        class="form-control" placeholder="Account No" value="{{ $account->account_no ?? null }}">
                        <span style="cursor: pointer; border-radius: 0;" class="input-group-text" id="account_no_gen">
                            <i class="fa fa-barcode"></i>
                        </span>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                            <input type="hidden" name="is_default" value="0">
                            <input type="checkbox" @isset($account)
                                {{ $account->is_default == true?'checked':'' }}
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
            <x-data-table dataUrl="/accounting/account" id="accounts" :columns="$columns" />
        </div>
    </div>
    @push('js')
        <script>
            $('#account_no_gen').on('click', function () {
                const inputEl = $(this).prev();
                const random = (Math.random() + 1).toString(10).substring(7);
                inputEl.val(random);
            });
        </script>
    @endpush
</x-admin>
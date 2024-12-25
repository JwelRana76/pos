<x-admin title="Voucher Seeting">
    <x-page-header head="Voucher Setting" />
    <div class="row">
        <div class="col-md-6">
            <x-card header="Voucher Setting">
                <x-form method="post" action="{{ route('voucher_setting.update', $voucher->id) }}">
                    <div class="form-group form-check">
                        <input type="checkbox" {{ $voucher->expense == true ? 'checked':'' }} class="form-check-input" id="expense" name="expense">
                        <label class="form-check-label" for="expense">Expense</label>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" {{ $voucher->pos == true ? 'checked':'' }} class="form-check-input" id="pos" name="pos">
                        <label class="form-check-label" for="pos">POS</label>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" {{ $voucher->purchase == true ? 'checked':'' }} class="form-check-input" id="purchase" name="purchase">
                        <label class="form-check-label" for="purchase">Purchase</label>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" {{ $voucher->salary == true ? 'checked':'' }} class="form-check-input" id="salary" name="salary">
                        <label class="form-check-label" for="salary">Salary</label>
                    </div>
                    <x-button value="Save" />
                </x-form>
            </x-card>
        </div>
    </div>
</x-admin>

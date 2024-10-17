<x-admin title="Invest">
    <x-page-header head="Invest" />
    <div class="row">
        <div class="col-md-4">
            <div class="card p-3">
                <x-form method="post" action="{{ route('invest.store') }}">
                    <x-input id="id" type="hidden" value="{{ $invest->id ?? null }}" />
                    <x-input id="date" type="date" label="date" value="{{ isset($invest) ? $invest->created_at->format('Y-m-d') : date('Y-m-d') }}" />
                    <x-input id="amount" label="amount" value="{{ $invest->amount ?? null }}" />
                    <div class="form-group mb-2">
                        <label for="type">Type</label>
                        <select id="invest_type" class="form-control selectpicker" data-live-search="true" title="Select Type" name="type">
                            <option value="0" @if(isset($invest)){{ $invest->type == 0 ? 'selected':'' }} @endif> Cash</option>
                            <option value="1" @if(isset($invest)){{ $invest->type == 1 ? 'selected':'' }} @endif> Bank</option>
                        </select>
                    </div>
                    <div id="account_section" class="d-none">
                    <x-select id="account_id" label="Account" selectedId="{{ $invest->account_id ?? null }}" :options="accounts()" />
                    </div>
                    <div id="bank_section" class="d-none">
                    <x-select id="bank_id" label="Bank" key="bank_name" selectedId="{{ $invest->bank_id ?? null }}" :options="banks()" />
                    </div>
                    <x-text-area id='note' name="note" value="{{ $invest->note ?? null  }}" />
                    <x-button value="Save" />
                </x-form>
            </div>
        </div>
        <div class="col-md-8">
            <x-data-table dataUrl="/invest" id="invests" :columns="$columns" />
        </div>
    </div>
    @push('js')
        <script>
    $(document).ready(function () {
        $('#invest_type').change(function () { 
            var type = $(this).val();
            invest_type(type);
        });
        function invest_type(type) {
            if (type == 1) {
                $('#account_section').addClass('d-none');
                $('#bank_section').removeClass('d-none');
            } else {
                $('#account_section').removeClass('d-none');
                $('#bank_section').addClass('d-none');
            }
        }
    });
</script>
    @endpush
</x-admin>

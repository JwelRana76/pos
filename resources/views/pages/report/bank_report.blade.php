<x-admin title="Bank Report">
    <x-page-header head="Bank Report" />
    <div class="row">
        <div class="col-md-12">
            <div class="card card-body">
                <form action="{{ route('report.bank') }}" method="get">
                    <div class="row">
                        <table class="table">
                            @php
                                $banks = App\Models\Bank::select('id','bank_name','account_no')->get()
                            @endphp
                            <tr>
                                <td><input type="date" name="start_date" required value="{{ request()->start_date }}" class="form-control"></td>
                                <td>To</td>
                                <td><input type="date" name="end_date" required value="{{ request()->end_date }}" class="form-control"></td>
                                <td>
                                    <select name="bank" required class="form-control selectpicker" title="Select Bank" data-live-search="true">
                                        @foreach ($banks as $key=>$item)
                                            <option value="{{ $item->id }}" {{ request()->bank == $item->id ?'selected':'' }} >{{ $item->bank_name }} [{{ $item->account_no }}]</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td><button class="btn btn-success">Submit</button></td>
                            </tr>
                        </table>

                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-12">
            <x-data-table-two dataUrl="/report/supplier-ledger" id="supplierLedgerReports" :columns="$columns" />
        </div>
    </div>
</x-admin>

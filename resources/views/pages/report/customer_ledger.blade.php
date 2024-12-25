<x-admin title="Customer Ledger">
    <x-page-header head="Customer Ledger" />
    <div class="row">
        <div class="col-md-12">
            <div class="card card-body">
                <form action="{{ route('report.customerledger') }}" method="get">
                    <div class="row">
                        <table class="table">
                            @php
                                $customers = App\Models\Customer::select('id','name')->get()
                            @endphp
                            <tr>
                                <td><input type="date" name="start_date" required value="{{ request()->start_date }}" class="form-control"></td>
                                <td>To</td>
                                <td><input type="date" name="end_date" required value="{{ request()->end_date }}" class="form-control"></td>
                                <td>
                                    <select name="customer_id" required class="form-control selectpicker" title="Select Customer" data-live-search="true">
                                        @foreach ($customers as $key=>$item)
                                            <option value="{{ $item->id }}" {{ request()->customer_id == $item->id ?'selected':'' }} >{{ $item->name }}</option>
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
            <x-data-table-two dataUrl="/report/customer-ledger" id="customerLedgerReports" :columns="$columns" />
        </div>
    </div>
</x-admin>

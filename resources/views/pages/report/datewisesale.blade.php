<x-admin title="Date Wise Sale Report">
    <x-page-header head="Date Wise Sale Report" />
    <div class="row">
        <div class="col-md-12">
            <div class="card card-body">
                <form action="{{ route('report.datewisesale') }}" method="get">
                    <div class="row">
                        <table class="table">
                            <tr>
                                <td>Start Date</td>
                                <td><input type="date" name="start_date" required value="{{ request()->start_date }}" class="form-control"></td>
                                <td>End Date</td>
                                <td><input type="date" name="end_date" required value="{{ request()->end_date }}" class="form-control"></td>

                                <td><button class="btn btn-success">Submit</button></td>
                            </tr>
                        </table>

                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-12">
            <x-data-table dataUrl="/report/date-wise-sale" id="datewisesaleReports" :columns="$columns" />
        </div>
    </div>
</x-admin>

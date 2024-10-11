<x-admin title="Employee">
    <x-page-header head="Employee" />
    <a href="{{route('employee.create')}}" class="btn btn-sm btn-primary m-2">
        <i class="fas fa-fw fa-plus"></i> Add Employee
    </a>
    <div class="row">
        <div class="col-md-12">
            <x-data-table dataUrl="/employee" id="employees" :columns="$columns" />
        </div>
    </div>
</x-admin>

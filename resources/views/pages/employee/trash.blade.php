<x-admin title="Employee Trash List">
    <x-page-header head="Employee Trash List" />
    <div class="row">
        <div class="col-md-12">
            <x-data-table dataUrl="/employee" id="employees" :columns="$columns" />
        </div>
    </div>
</x-admin>

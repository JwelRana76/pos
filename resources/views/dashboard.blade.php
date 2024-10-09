<x-admin title="Dashboard">
    <h1>Users List</h1>
    <div class="col-md-12 px-0">
        <x-data-table dataUrl="/dashboard" id="dashboards" :columns="$columns" />
    </div>

</x-admin>

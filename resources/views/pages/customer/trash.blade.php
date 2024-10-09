<x-admin title="Customer Trash List">
    <x-page-header head="Customer Trash List" />
    <div class="row">
        <div class="col-md-12">
            <x-data-table dataUrl="/customer/trash" id="customerTrash" :columns="$columns" />
        </div>
    </div>
</x-admin>

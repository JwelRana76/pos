<x-admin title="Loan Trash List">
    <x-page-header head="Loan Trash List" />
    <div class="row">
        <div class="col-md-12">
            <x-data-table dataUrl="/customer/trash" id="customerTrash" :columns="$columns" />
        </div>
    </div>
</x-admin>

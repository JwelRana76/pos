<x-admin title="Expense Trash">
    <x-page-header head="Expense Trash" />
    <div class="row">
        <div class="col-md-12">
            <x-data-table dataUrl="/expense/trash" id="expensestrash" :columns="$columns" />
        </div>
    </div>
</x-admin>

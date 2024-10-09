<x-admin title="Expense Category Trasj">
    <x-page-header head="Expense Category Trasj" />
    <div class="row">
        <div class="col-md-12">
            <x-data-table dataUrl="/expense-category/trash" id="expenseCategoriesTrash" :columns="$columns" />
        </div>
    </div>
</x-admin>

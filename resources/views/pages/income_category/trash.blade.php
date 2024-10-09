<x-admin title="Income Category Trash">
    <x-page-header head="Income Category Trash" />
    <div class="row">
        <div class="col-md-12">
            <x-data-table dataUrl="/income-category" id="incomeCategories" :columns="$columns" />
        </div>
    </div>
</x-admin>

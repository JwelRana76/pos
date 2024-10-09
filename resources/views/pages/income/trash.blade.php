<x-admin title="Income Trash">
    <x-page-header head="Income Trash" />
    <div class="row">
        <div class="col-md-12">
            <x-data-table dataUrl="/income/trash" id="income_trash" :columns="$columns" />
        </div>
    </div>
</x-admin>

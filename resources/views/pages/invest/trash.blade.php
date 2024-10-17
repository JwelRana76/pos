<x-admin title="Invest Trash">
    <x-page-header head="Invest Trash" />
    <div class="row">
        <div class="col-md-12">
            <x-data-table dataUrl="/invest/trash" id="invest_trash" :columns="$columns" />
        </div>
    </div>
</x-admin>

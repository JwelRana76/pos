<div class="card ">
    <div class="card-body table_card_body">
        <table class="table table-striped w-100" id="{{ $id }}">
            <thead>
                <tr>
                    <th>
                        <label for="dtb_all_selector" class="itd-checkbox" style="margin-top: -10px;">
                            <input type="checkbox" id="dtb_all_selector" class="custom-checkbox" />
                            <span class="checkmark"></span>
                        </label>
                    </th>
                    @foreach ($columns as $column)
                        <th>{{ ucwords(implode(' ', explode('_', $column['name']))) }}</th>
                    @endforeach
                </tr>
            </thead>
        </table>
    </div>
</div>
<style>
    thead {
        background: var(--sidebar-color) !important;
        color: var(--light-color);
    }

    .table_card_body {
        overflow-x: auto;
        max-width: 100%;
    }

    .table_card_body table {
        width: 100%;
        table-layout: fixed;
    }
</style>
@push('js')
    <script>
        const selector = {!! json_encode($id) !!};
        const columns = {!! json_encode($columns) !!};
        const dataUrl = {!! json_encode($dataUrl) !!};

        const table = itd_makeDataTable2(
            '#' + selector,
            dataUrl,
            columns,
        );
    </script>
@endpush

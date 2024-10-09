<x-admin title="Role">
    <x-page-header head="Role" />
    <button type="button" class="btn btn-sm btn-primary my-2" data-toggle="modal" data-target="#add_role">
        <i class="fas fa-fw fa-plus"></i> Add Role
    </button>
    <x-data-table dataUrl="/setting/role" id="role" :columns="$columns" />

    <x-modal id="add_role">
        <x-form method="post" action="{{ route('role.store') }}">
            <x-input id="name" />
            <x-button value="Save" />
        </x-form>
    </x-modal>
    <x-modal id="update_role">
        <x-form method="post" action="{{ route('role.update',1) }}">
            <x-input id="name" />
            <x-input type="hidden" id="update_id" />
            <x-button value="Save" />
        </x-form>
    </x-modal>
    @push('js')
        <script>
            $('#update_role').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var id = button.data('id');
                $.get("/setting/role/edit/" + id, function (data) {
                    $('#update_role input[name="name"]').val(data.name);
                    $('#update_role input[name="update_id"]').val(data.id);
                });
            });

        </script>
    @endpush
</x-admin>
<x-admin title="User">
    <x-page-header head="User" />
    <button type="button" class="btn btn-sm btn-primary my-2" data-toggle="modal" data-target="#add_user">
        <i class="fas fa-fw fa-plus"></i> Add User
    </button>
    <div class="row">
        <div class="col-md-8">
            <x-data-table dataUrl="/setting/user" id="user" :columns="$columns" />
        </div>
        <div class="col-md-4">
            <x-card header="User Create">
                <x-form method="post" action="{{ route('user.store') }}">
                    <x-input id="name" />
                    <x-input id="username" />
                    <x-input type="password" id="password" />
                    <x-input type="password" id="conform_password" />
                    <x-select id="role_id" :options="$roles" />
                    <x-button value="Save" />
                </x-form>
            </x-card>
        </div>
    </div>

    <x-modal id="set_role">
        <x-form method="post" action="{{ route('user.role_assign_store',1) }}">
            <x-input type="hidden" id="user_id" />
            <x-select id="role_id" :options="$roles" />
            <x-button value="Save" />
        </x-form>
    </x-modal>
    @push('js')
        <script>
            $('#set_role').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var id = button.data('id');
                $.get("/setting/user/assign_role/" + id, function (data) {
                    console.log(data);
                    $('#set_role input[name="user_id"]').val(id);
                    var selectElement = $('#set_role #role_id'); // Use the correct selector
                    selectElement.val(data);
                    selectElement.trigger('change');
                });
            });
        </script>
    @endpush
</x-admin>
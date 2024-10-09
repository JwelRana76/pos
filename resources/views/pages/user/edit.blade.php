<x-admin title="User Update">
  <x-page-header head="User Update" />
  <div class="row">
    <div class="col-md-6">
        <x-card header="User Update">
            <x-form method="post" action="{{ route('user.update',$data->id) }}">
                <x-input id="name" value="{{ $data->name }}" />
                <x-input id="username" value="{{ $data->username }}" />
                <x-input type="password" id="password" />
                <x-input type="password" id="conform_password" />
                <x-select id="role_id" selectedId="{{ $data->role->role_id }}" :options="$roles" />
                <x-button value="Save" />
            </x-form>
        </x-card>
    </div>
</div>
</x-admin>
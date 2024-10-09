<x-admin title="Permission Set">
    <x-page-header head="Permission Set" second="role" />
    <h1 class="h3 m-3">
      User Role : <b>{{ $role->name }}</b> <span style="font-size: 17px; margin-left:30px"> <input type="checkbox" id="all_permission"> <label for="all_permission">All Permission</label> </span>
    </h1>
  <div class="row">
    <div class="col-md-12">
        <form action="{{ route('role.permission.store',$role->id) }}" method="post">
            @csrf
            <div class="responsive-table">
                <table class="table table-bordered">
                    <thead>
                        <th>Group</th>
                        <th>Permissions</th>
                    </thead>
                    <tbody>
                        @foreach ($groups as $key=>$group)
                            <tr>
                                <td><input type="checkbox" name="group_checkbox[]" value="{{ $group->id }}" class="group_checkbox mr-2" id="{{ $group->name }}"><label for="{{ $group->name }}">{{ $group->name }} </label></td>
                                
                                <td>
                                    @foreach ($group->permissions as $permission)
                                        <span class="mr-3 {{ $group->id }}">
                                            <input type="checkbox" name="permissions[]" {{ $permission->permissionCheck($role->id,$permission->id) ? 'checked' : '' }} value="{{ $permission->id }}" id="{{ $permission->name }}">
                                            <label for="{{ $permission->name }}">{{ explode('-',$permission->name)[1] }}</label>
                                        </span>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-center">
                <button class="btn btn-success mt-3">Assign</button>
            </div>
        </form>
    </div>
</div>
@push('js')
    <script>
        $('.group_checkbox').on('input',function(){
            $class = $(this).val();
            $('.'+$class).find('input[type=checkbox]').each(function () {
                this.checked = !this.checked ;
           });
        })

        $("#all_permission").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>
@endpush
</x-admin>
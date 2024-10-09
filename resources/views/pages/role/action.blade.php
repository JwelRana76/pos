<a href="{{ route('role.permission',$item->id) }}" class="btn btn-sm btn-primary mr-2">Permission</a>
<button type="button" class="btn btn-sm btn-primary mr-2" data-id="{{ $item->id }}" data-toggle="modal" data-target="#update_role">
    <i class="fas fa-fw fa-pen-square"></i>
</button>
<a href="{{ route('role.delete',$item->id) }}" onclick="return confirm('Are you sure to delete this record')" class="btn btn-sm btn-primary mr-2 bg-danger"><i class="fas fa-fw fa-trash"></i></a>

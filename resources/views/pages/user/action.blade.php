
<!-- Default dropleft button -->
<div class="btn-group dropleft">
    <button type="button" class="action-button dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-fw fa-ellipsis-v"></i>
    </button>
    <div class="dropdown-menu">
        <button type="button" class="dropdown-item" data-id="{{ $item->id }}" data-toggle="modal" data-target="#set_role">
            Role Set
        </button>
        <a class="dropdown-item" href="{{ route('user.edit',$item->id) }}"><i class="fa-regular text-primary fa-pen-to-square"></i> Edit</a>
        <a class="dropdown-item" href="{{ route('user.delete',$item->id) }}" onclick="return confirm('Are you sure to Delete this record..??')"><i class="fa-regular text-danger fa-trash-can"></i> Delete</a>
    </div>  
</div>

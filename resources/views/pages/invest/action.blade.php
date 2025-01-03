<div class="dropdown">
  <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
    Action
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item text-info" href="{{ route('invest.edit',$item->id) }}" ><i class="fas fa-fw fa-pen"></i> Edit</a>
    @if ($item->balance > 0)
    <a class="dropdown-item text-info" href="#" data-balance="{{ $item->balance }}" data-id="{{ $item->id }}" data-target="#investReturn" data-toggle="modal" ><i class="fa-solid mr-2 fa-rotate-left"></i> Return</a>
    @endif
    <a class="dropdown-item text-info" href="#" data-id="{{ $item->id }}" data-target="#investReturnList" data-toggle="modal" ><i class="fa-solid fa-list mr-2"></i> Return List</a>
    <a class="dropdown-item text-danger" href="{{ route('invest.delete',$item->id) }}"  onclick="return confirm('Are you sure to delete this record')"><i class="fas fa-fw fa-trash"></i> Delete</a>
    
  </div>
</div>
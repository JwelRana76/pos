<div class="dropdown">
  <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
    Action
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item text-warning" href="{{ route('purchase.restore',$item->id) }}"  onclick="return confirm('Are you sure to Restore this record')"><i class="fa-solid fa-reply mr-2"></i> Restore</a>
    <a class="dropdown-item text-danger" href="{{ route('purchase.pdelete',$item->id) }}"  onclick="return confirm('Are you sure to permanently delete this record')"><i class="fa-solid fa-trash mr-2"></i> Permanent Delete</a>
  </div>
</div>
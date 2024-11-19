<div class="dropdown">
  <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
    Action
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item text-info" href="{{ route('salary-submit.edit',$item->id) }}" ><i class="fas fa-fw fa-pen mr-2"></i> Edit</a>
    <a class="dropdown-item text-danger" href="{{ route('salary-submit.delete',$item->id) }}"  onclick="return confirm('Are you sure to delete this record')"><i class="fas fa-fw fa-trash mr-2"></i> Delete</a>
  </div>
</div>
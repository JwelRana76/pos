<div class="dropdown">
  <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
    Action
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item text-info" href="{{ route('expense.edit',$item->id) }}" ><i class="fas fa-fw fa-pen"></i> Edit</a>
    
    @if (voucher()->expense == true) {
    <a class="dropdown-item text-info" target="_blank" href="{{ route('expense.receipt',$item->id) }}" ><i class="fas fa-fw fa-file-pdf"></i> Receipt</a>
    @endif
    <a class="dropdown-item text-danger" href="{{ route('expense.delete',$item->id) }}"  onclick="return confirm('Are you sure to delete this record')"><i class="fas fa-fw fa-trash"></i> Delete</a>
    
  </div>
</div>
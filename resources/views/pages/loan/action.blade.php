<div class="dropdown">
  <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
    Action
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item text-info" href="{{ route('loan.edit',$item->id) }}" ><i class="fas fa-fw fa-pen mr-2"></i> Edit</a>
    
    <a href="#" data-id="{{ $item->id }}" data-balance="{{ $item->balance }}" class="dropdown-item text-info" data-target="#loanReturnModal" data-toggle="modal" >
      <i class="fa fa-rotate-left mr-2"></i>
      <span>Return</span>
    </a>
    <a href="#" data-id="{{ $item->id }}" data-balance="{{ $item->balance }}" 
      class="dropdown-item text-info" data-target="#LoanReturnList" data-toggle="modal" >
      <i class="fa fa-list mr-2"></i>
      <span>Return</span>
    </a>
    
    <a class="dropdown-item text-danger" href="{{ route('loan.delete',$item->id) }}"  onclick="return confirm('Are you sure to delete this record')"><i class="fas fa-fw fa-trash mr-2"></i> Delete</a>
    
  </div>
</div>
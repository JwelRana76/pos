<div class="dropdown">
  <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
    Action
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item text-info" href="{{ route('purchase.edit',$item->id) }}" ><i class="fas fa-fw fa-pen"></i> Edit</a>
    @if($item->due > 0)
    <a class="dropdown-item text-info" href="#" data-id="{{ $item->id }}" data-target="#paymentModal" data-toggle="modal"  ><i class="fas fa-fw fa-money-bill"></i> Add Payment</a>
    @endif
    @if (voucher()->purchase == true)
      <a class="dropdown-item text-info" target="_blank" href="{{ route('purchase.receipt',$item->id) }}" ><i class="fas fa-fw fa-file-pdf mr-2"></i> Receipt</a>
    @endif
    <a class="dropdown-item text-danger" href="{{ route('purchase.delete',$item->id) }}"  onclick="return confirm('Are you sure to delete this record')"><i class="fas fa-fw fa-trash"></i> Delete</a>
    
  </div>
</div>
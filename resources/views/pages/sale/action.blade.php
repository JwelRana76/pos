<div class="dropdown">
  <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
    Action
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item text-info" href="{{ route('sale.edit',$item->id) }}" ><i class="fas fa-fw fa-pen"></i> Edit</a>
    <a class="dropdown-item text-info" href="#" onclick="openPopup({{ $item->id }})"><i class="fas fa-fw fa-file-pdf"></i> Invoice</a>
      <a class="dropdown-item text-info" href="#" data-id="{{ $item->id }}" data-target="#paymentModal" data-toggle="modal"  ><i class="fas fa-fw fa-money-bill"></i> Add Payment</a>
    <a class="dropdown-item text-danger" href="{{ route('sale.delete',$item->id) }}"  onclick="return confirm('Are you sure to delete this record')"><i class="fas fa-fw fa-trash"></i> Delete</a>
    
  </div>
</div>
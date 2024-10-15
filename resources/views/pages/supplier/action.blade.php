<div class="dropdown">
  <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
    Action
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item text-info" href="{{ route('supplier.edit',$item->id) }}" ><i class="fas fa-fw fa-pen mr-2"></i> Edit</a>
    
    <a href="#" class="dropdown-item text-info" data-target="#supplierPaymentModal" data-toggle="modal" >
      <i class="fa fa-money-bill mr-2"></i>
      <span>Payment</span>
    </a>
    <a class="dropdown-item text-info" href="#" data-target="#payment_details" data-toggle="modal" onclick="paymentdetails({{ $item->id }})"><i class="fa fa-money-bill mr-2" ></i> Payment Details</a>
    <a class="dropdown-item text-danger" href="{{ route('supplier.delete',$item->id) }}"  onclick="return confirm('Are you sure to delete this record')"><i class="fas fa-fw fa-trash mr-2"></i> Delete</a>
  </div>
</div>
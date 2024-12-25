<div class="dropdown">
  <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
    Action
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item text-info" href="{{ route('salary-payment.edit',$item->id) }}" ><i class="fas fa-fw fa-pen mr-2"></i> Edit</a>
    @if (voucher()->salary == true)
      <a class="dropdown-item text-info" target="_blank" href="{{ route('salary-payment.receipt',$item->id) }}" ><i class="fas fa-fw fa-file-pdf mr-2"></i> Receipt</a>
    @endif
    <a class="dropdown-item text-danger" href="{{ route('salary-payment.delete',$item->id) }}"  onclick="return confirm('Are you sure to delete this record')"><i class="fas fa-fw fa-trash mr-2"></i> Delete</a>
  </div>
</div>
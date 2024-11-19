<x-admin title="Advance Salary List">
    <x-page-header head="Advance Salary List" />
    <a href="{{route('advance-salary.create')}}" class="btn btn-sm btn-primary m-2">
        <i class="fas fa-fw fa-plus"></i> New
    </a>
    <div class="row">
        <div class="col-md-12">
            <x-data-table dataUrl="/payrole/salary-submit" id="salary_submits" :columns="$columns" />
        </div>
    </div>
    <x-large-modal id="payment_details">
      <table class="table">
        <thead>
            <tr>
                <th>SL</th>
                <th>Date</th>
                <th>Voucher_no</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="payment_details_table">

        </tbody>
        <tfoot>
            <tr>
              <td colspan="3">Total</td>
              <td id="total_amount"></td>
            </tr>
          </tfoot>
      </table>
    </x-large-modal>
    @push('js')
        <script>
                
            function paymentdetails(Id){
                $.get('/supplier/payment/details/'+Id,
                    function (data) {
                    var key = 1;
                    var total = 0;
                    $('#payment_details_table').html(null);
                    data.map(function(item){
                        var url ='{{route("supplier.payment.delete", ":id")}}'
                        url = url.replace(':id', item.id);
                        total += item.amount;
                        $('#payment_details_table').append(`
                        <tr>
                            <td>${key++}</td>
                            <td>${item.created_at.split('T')[0]}</td>
                            <td>${item.voucher_no}</td>
                            <td>${item.amount}</td>
                            <td>
                            <div class="dropdown">
                                <button class="btn border dropdown-toggle bg-primary text-light" type="button" data-toggle="dropdown" aria-expanded="false">
                                Action
                                </button>
                                <div class="dropdown-menu">
                                <form action="${url}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button class="dropdown-item text-danger" onclick="return confirm('Are you sure to delete this record')" data-toggle="tooltip" title='Delete' ><i class="fas fa-fw fa-trash mr-2"></i>Delete</button>
                                </form>
                                </div>
                            </div>
                            </td>
                        </tr>
                        `);
                    })
                    $('#total_amount').html(total);
                });
            }
        </script>
    @endpush
</x-admin>

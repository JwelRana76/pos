<x-admin title="Loan">
    <x-page-header head="Loan" />
    <a href="{{route('loan.create')}}" class="btn btn-sm btn-primary m-2">
        <i class="fas fa-fw fa-plus"></i> New Loan
    </a>
    <div class="row">
        <div class="col-md-12">
            <x-data-table dataUrl="/customer" id="customers" :columns="$columns" />
        </div>
    </div>
    <x-modal id="loanReturnModal">
        <x-form method="post" action="{{ route('loan-return.store') }}">
                <x-input id="loan_id" type="hidden" />
                <x-input id="balance" type="hidden" />
                <x-input id="date" type="date" value="{{ date('Y-m-d') }}"  />
                <x-input id="amount"  />
                <x-text-area id='note' />
                <x-button value="Save" />
        </x-form>
    </x-modal>
    <x-modal id="LoanReturnEdit">
        <x-form method="post" action="{{ route('loan-return.update') }}">
                <x-input id="return_id" type="hidden" />
                <x-input id="balance" type="hidden" />
                <x-input id="date" type="date"  />
                <x-input id="amount"  />
                <x-text-area id='note' />
                <x-button value="Save" />
        </x-form>
    </x-modal>
    <x-large-modal id="LoanReturnList">
        <table class="table">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="investReturnBody">

            </tbody>
        </table>
    </x-large-modal>
    @push('js')
        <script>
            $('#loanReturnModal').on('shown.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var balance = button.data('balance'); 
                $('#loanReturnModal #loan_id').val(id);
                $('#loanReturnModal #balance').val(balance);
            })  
            
             $('#loanReturnModal #amount').on('input', function () {
                var balance = parseFloat($('#loanReturnModal #balance').val());
                var amount = parseFloat($(this).val());
                if(balance < amount){
                    alert('You can not return more than invested balance');
                    $(this).val(balance);
                }
            });
            $('#LoanReturnList').on('shown.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                $('#investReturnBody').html(null);
                $.get("/loan-return/view/"+id,
                    function (data) {
                        $.each(data.item, function (index,item) { 
                            $('#investReturnBody').append(`
                                <tr>
                                    <td>${index+1}</td>    
                                    <td>${moment(item?.created_at).format('LL')}</td>    
                                    <td>${item.amount}</td>    
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item text-info" href="#" data-note="${item.note}" data-date="${item.created_at}" data-amount="${item.amount}" data-balance="${data.balance}" data-id="${item.id}" data-target="#LoanReturnEdit" data-toggle="modal"><i class="fas fa-fw fa-pen"></i> Edit</a>
                                                <a class="dropdown-item text-danger" href="/loan-return/delete/${item.id}"  onclick="return confirm('Are you sure to delete this record')"><i class="fas fa-fw fa-trash"></i> Delete</a>
                                                
                                            </div>
                                        </div>    
                                    </td>    
                                </tr>
                            `);
                        });   
                    }
                );
            })
            $('#LoanReturnEdit').on('shown.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var balance = button.data('balance'); 
                var amount = button.data('amount'); 
                var date = button.data('date'); 
                var note = button.data('note'); 
                $('#LoanReturnList').modal('hide')
                $('#LoanReturnEdit #return_id').val(id);
                $('#LoanReturnEdit #balance').val(parseFloat(balance) + parseFloat(amount));
                $('#LoanReturnEdit #amount').val(amount);
                $('#LoanReturnEdit #note').val(note);
                $('#LoanReturnEdit #date').val(date.split('T')[0]);
            })
            $('#LoanReturnEdit #amount').on('input', function () {
                var balance = parseFloat($('#LoanReturnEdit #balance').val());
                var amount = parseFloat($(this).val());
                if(balance < amount){
                    alert('You can not return more than invested balance');
                    $(this).val(balance);
                }
            });
            
        </script>
    @endpush
</x-admin>

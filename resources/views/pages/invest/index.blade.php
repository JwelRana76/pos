<x-admin title="Invest">
    <x-page-header head="Invest" />
    <div class="row">
        <div class="col-md-4">
            <div class="card p-3">
                <x-form method="post" action="{{ route('invest.store') }}">
                    <x-input id="id" type="hidden" value="{{ $invest->id ?? null }}" />
                    <x-input id="date" type="date" label="date" value="{{ isset($invest) ? $invest->created_at->format('Y-m-d') : date('Y-m-d') }}" />
                    <x-input id="amount" label="amount" value="{{ $invest->amount ?? null }}" />
                    <div class="form-group mb-2">
                        <label for="type">Type</label>
                        <select id="invest_type" class="form-control selectpicker" data-live-search="true" title="Select Type" name="type">
                            <option value="0" @if(isset($invest)){{ $invest->type == 0 ? 'selected':'' }} @endif> Cash</option>
                            <option value="1" @if(isset($invest)){{ $invest->type == 1 ? 'selected':'' }} @endif> Bank</option>
                        </select>
                    </div>
                    <div id="account_section" class="d-none">
                    <x-select id="account_id" label="Account" selectedId="{{ $invest->account_id ?? null }}" :options="accounts()" />
                    </div>
                    <div id="bank_section" class="d-none">
                    <x-select id="bank_id" label="Bank" key="bank_name" selectedId="{{ $invest->bank_id ?? null }}" :options="banks()" />
                    </div>
                    <x-text-area id='note' name="note" value="{{ $invest->note ?? null  }}" />
                    <x-button value="Save" />
                </x-form>
            </div>
        </div>
        <div class="col-md-8">
            <x-data-table dataUrl="/invest" id="invests" :columns="$columns" />
        </div>
    </div>
    <x-modal id="investReturn">
        <x-form method="post" action="{{ route('invest_return.store') }}">
                <x-input id="invest_id" type="hidden" />
                <x-input id="balance" type="hidden" />
                <x-input id="date" type="date" value="{{ date('Y-m-d') }}"  />
                <x-input id="amount"  />
                <x-button value="Save" />
        </x-form>
    </x-modal>
    <x-modal id="investReturnEdit">
        <x-form method="post" action="{{ route('invest_return.update') }}">
                <x-input id="return_id" type="hidden" />
                <x-input id="balance" type="hidden" />
                <x-input id="date" type="date"  />
                <x-input id="amount"  />
                <x-button value="Save" />
        </x-form>
    </x-modal>
    <x-large-modal id="investReturnList">
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
    $(document).ready(function () {
        $('#invest_type').change(function () { 
            var type = $(this).val();
            invest_type(type);
        });
        function invest_type(type) {
            if (type == 1) {
                $('#account_section').addClass('d-none');
                $('#bank_section').removeClass('d-none');
            } else {
                $('#account_section').removeClass('d-none');
                $('#bank_section').addClass('d-none');
            }
        }
        $('#investReturn').on('shown.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var balance = button.data('balance'); 
            $('#investReturn #invest_id').val(id);
            $('#investReturn #balance').val(balance);
        })
         $('#investReturn #amount').on('input', function () {
            var balance = parseFloat($('#balance').val());
            var amount = parseFloat($(this).val());
            if(balance < amount){
                alert('You can not return more than invested balance');
                $(this).val(balance);
            }
        });
        $('#investReturnList').on('shown.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            $.get("/invest-return/view/"+id,
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
                                            <a class="dropdown-item text-info" href="#" data-date="${item.created_at}" data-amount="${item.amount}" data-balance="${data.balance}" data-id="${item.id}" data-target="#investReturnEdit" data-toggle="modal"><i class="fas fa-fw fa-pen"></i> Edit</a>
                                            <a class="dropdown-item text-danger" href="/invest-return/delete/${item.id}"  onclick="return confirm('Are you sure to delete this record')"><i class="fas fa-fw fa-trash"></i> Delete</a>
                                            
                                        </div>
                                    </div>    
                                </td>    
                            </tr>
                         `);
                    });   
                }
            );
        })
    });

    $('#investReturnEdit').on('shown.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var balance = button.data('balance'); 
        var amount = button.data('amount'); 
        var date = button.data('date'); 
        $('#investReturnList').modal('hide')
        $('#investReturnEdit #return_id').val(id);
        $('#investReturnEdit #balance').val(parseFloat(balance) + parseFloat(amount));
        $('#investReturnEdit #amount').val(amount);
        $('#investReturnEdit #date').val(date.split('T')[0]);
    })
    $('#investReturnEdit #amount').on('input', function () {
            var balance = parseFloat($('#investReturnEdit #balance').val());
            var amount = parseFloat($(this).val());
            if(balance < amount){
                alert('You can not return more than invested balance');
                $(this).val(balance);
            }
        });

</script>
    @endpush
</x-admin>

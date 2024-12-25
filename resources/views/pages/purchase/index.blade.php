<x-admin title="Purchase">
    <x-page-header head="Purchase" />
    <a href="{{route('purchase.create')}}" class="btn btn-sm btn-primary m-2">
        <i class="fas fa-fw fa-plus"></i> Add Purchase
    </a>
    <div class="row mb-3">
        <div class="col-md-4 offset-md-4">
            <div class="card">
                <div class="card-body">
                    <label for="sort_by_status">Filter by payment status</label>
                    <select name="sort_by_status" id="sort_by_status" class="mt-2 form-control">
                        <option value="">Select Status</option>
                        <option value="1">Paid</option>
                        <option value="-1">Due</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="table-responsive p-3">
                    <table class="table align-items-center table-flush" id="saleTable">
                        <thead class="bg-primary text-light">
                            <tr> 
                                <th>Date</th>
                                <th>Voucher No</th>
                                <th>Supplier</th>
                                <th>Grand Total</th>
                                <th>Paid Amount</th>
                                <th>Due Amount</th>
                                <th>Payment Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <x-large-modal id="view_sale_details">
    </x-large-modal>
    @php
        $accounts = App\Models\Account::get();
        $banks = App\Models\Bank::get();
    @endphp
    <x-modal id="paymentModal">
        <x-form method="post" action="{{ route('supplier.payment') }}">
            <div class="row mb-3">
                <x-input id="voucher" type="hidden" />
                <x-input id="supplier" type="hidden" />
                <div class="col-md-6">
                    <x-input type="date" id="date" value="{{ date('Y-m-d') }}" />
                </div>
                <div class="col-md-6">
                    <x-input readonly id="payable"/>
                </div>
                <div class="col-md-6">
                    <x-input id="paid"/>
                </div>
                <div class="col-md-6">
                    <x-input readonly id="current_due"/>
                </div>
                <div class="col-md-6">
                    <label for="payment_method">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-control selectpicker" title="select payment method">
                        <option value="0">Cash</option>
                        <option value="1">Bank</option>
                    </select>
                </div>
                <div class="col-md-6 d-none" id="account_part">
                    <x-select id="account" selectedId="1" :options="$accounts" />
                </div>
                <div class="col-md-6 d-none" id="bank_part">
                    <x-select id="bank" key="bank_name" :options="$banks" />
                </div>
                <div class="col-md-12">
                    <x-textarea id="note"/>
                </div>
                <div class="col-md-12">
                    <x-button value="Save" />
                </div>
        </x-form>
    </x-modal>
    @php
        $accounts = App\Models\Account::select('name as name', 'id', 'account_no')
            ->get()
            ->append('balance');
        $banks = App\Models\Bank::select('bank_name as name', 'id', 'account_no')
            ->get()
            ->append('balance');
    @endphp
    @push('js')
        <script>
            $('#paymentModal #payment_method').on('change', function () {
                $('#paymentModal').find('#paid').val(null);
                const payable = parseFloat( $('#paymentModal').find('#payable').val());
                $('#paymentModal').find('#current_due').val(payable)
                if($(this).val() == 0){
                    $('#paymentModal #account_part').removeClass('d-none');
                    $('#paymentModal #bank_part').addClass('d-none');
                    $('#paymentModal #account').attr('required', 'true');
                    $('#paymentModal #bank').removeAttr('required');
                } else {
                    $('#paymentModal #bank_part').removeClass('d-none');
                    $('#paymentModal #account_part').addClass('d-none');
                    $('#paymentModal #account').removeAttr('required');
                    $('#paymentModal #bank').attr('required', 'true');
                }
            });

        const table = $('#saleTable').dataTable({
            searching: true,
            processing: true,
            serverSide: true,
            ajax: {
                method: 'get',
                url: "{{ route('purchase.index') }}",
                data: function(d) {
                    d.payment_status = $('#sort_by_status').val();
                }
            }, 
            lengthMenu: [
                [25, 50, 100, 200, 300, 500, 1000, 2000,-1],
                [25, 50, 100, 200, 300, 500, 1000, 2000, 'All'],
            ],
             columns: [{
                    data: 'date',
                    name: 'date',
                },
                {
                    data: 'voucher_no',
                    name: 'voucher_no',
                },
                {
                    data: 'supplier',
                    name: 'supplier',
                },
                {
                    data: 'grand_total',
                    name: 'grand_total',
                },
                {
                    data: 'paid_amount',
                    name: 'paid_amount',
                },
                {
                    data: 'due',
                    name: 'due',
                },
                {
                    data: 'payment_status',
                    name: 'payment_status',
                },
                {
                    data: 'action',
                    name: 'action',
                }
            ],
            "dom": '<lBf<t>i<"m-0"p>>',
            buttons: [
                {
                    extend: "print",
                    className: "datatable_print_button",
                    text: "<i class='fa fa-print'></i>",
                    exportOptions: { columns: ":visible" },
                },
                {
                    extend: "copy",
                    className: "datatable_copy_button",
                    text: "<i class='fa fa-copy'></i>",
                    exportOptions: { columns: ":visible" },
                },
                {
                    extend: "pdf",
                    className: "datatable_pdf_button",
                    text: "<i class='fa fa-file-pdf'></i>",
                    exportOptions: { columns: ":visible" },
                },
                {
                    extend: "excel",
                    className: "datatable_excel_button",
                    text: "<i class='fa fa-file-excel'></i>",
                    exportOptions: { columns: ":visible" },
                },
                {
                    extend: "colvis",
                    className: "datatable_colvis_button",
                    text: "<i class='fa fa-eye-slash'></i>",
                    columnText: function (e, n, t) {
                        return 0 == n ? "Select" : t;
                    },
                },
            ],
            "rowCallback": function(row, tableData, index) {
                $(row).css("cursor", "pointer");
                $(row).on("click", function(e) { 
                    if ($(e.target).hasClass('dropdown-toggle') || $(e.target).parent().hasClass('dropdown-menu')) {
                        return;
                    }
                    
                    $.get('/purchase/show/' + tableData.id, function(data, status) {
                        console.log(data); 
                        const html = `
                            <table class="table" style="margin-top:5px">
                                
                                <tr>
                                    <td width="">Reference</td>
                                    <td>${data?.purchase?.voucher_no}</td>
                                </tr>
                                <tr>
                                    <td width="35%">Customer</td>
                                    <td>${data?.purchase?.supplier?.name} <br /> </td>
                                </tr>
                                <tr>
                                    <td width="40%">Date</td>
                                    <td>${moment(data?.purchase?.created_at).format('LL')}</td>
                                </tr>
                            </table>
                            <table class="table" style="margin-top:15px;">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>MRP</th>
                                        <th>Qty</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${
                                        data.items.map(item => (
                                            `<tr> 
                                                <td>${item.product.name}</td>
                                                <td>${item.unit_cost}</td>
                                                <td>${item.qty}</td>
                                                <td>${item.total_cost}</td>
                                            </tr>`    
                                        )).join('')
                                    }
                                <div class="divider"></div>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3">Total Amount</td>
                                    <td>${data.purchase.total_amount} Tk</td>
                                </tr>
                                <tr>
                                    <td colspan="3">Discount</td>
                                    <td>${data.purchase.discount || 0 } Tk</td>
                                </tr>
                                <tr>
                                    <td colspan="3">Paid Amount</td>
                                    <td>${data.purchase.grand_total} Tk</td>
                                </tr>
                                <tr>
                                    <td colspan="3">Paid Amount</td>
                                    <td>${data.paid} Tk</td>
                                </tr>
                                <tr>
                                    <td colspan="3">Due</td>
                                    <td>${data?.purchase?.grand_total - data?.paid} Tk</td>
                                </tr> 
                            </tfoot>
                        </table>
                        `;
                        $('#view_sale_details').modal('show');
                        $('#view_sale_details .modal-body').html(html);
                    });
                });
            }
        });
        $('#sort_by_status').on('change', function() {
            table.api().ajax.reload();
        });
        
        $('#paymentModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var dataId = button.data('id'); // Extract the data-id attribute
            $.get("/purchase/dueamount/"+dataId,
                function (data) {
                    console.log(data.paid);
                    $('#paymentModal').find('#payable').val(data.purchase.grand_total - data.paid);
                    $('#paymentModal').find('#current_due').val(data.purchase.grand_total - data.paid);
                    $('#paymentModal').find('#voucher').val(data.purchase.id);
                    $('#paymentModal').find('#supplier').val(data.purchase.supplier_id);
                }
            );
        })
        $('#paymentModal').find('#paid').on('input',function(){
            varifyPayment();
        })
        const accounts = @json($accounts);
        const banks = @json($banks);
        function varifyPayment(){
            const payable = parseFloat( $('#paymentModal').find('#payable').val());
            const paid = parseFloat( $('#paymentModal').find('#paid').val());
            $('#paymentModal').find('#current_due').val((payable - paid).toFixed(2))
            var payment_method = $('#paymentModal #payment_method').val();
            if(payment_method){
                    var bank = $('#paymentModal #bank').val();
                    var account = $('#paymentModal #account').val();
                    if(payment_method == 0 && account){
                        var filteredBank = accounts.find(item => item.id == account);
                        var balance = parseFloat(filteredBank.balance);
                        if(paid > balance){
                            alert(`You Account Balance ${balance}`);
                            $('#paymentModal').find('#paid').val(balance);
                        }
                    }else{
                        var filteredBank = banks.find(item => item.id == bank);
                        var balance = parseFloat(filteredBank.balance);
                        if(paid > balance){
                            alert(`You Bank Balance ${balance}`);
                            $('#paymentModal').find('#paid').val(balance);
                        }
                    }        
                    if(paid > payable){
                        alert(`You can not pay more than ${payable}`);
                        $('#paymentModal').find('#paid').val(payable);
                        $('#paymentModal').find('#current_due').val(0)
                    }
            }else{
                alert('Select Payment Method First');
                $('#paymentModal').find('#paid').val(null)
            }
        }
        </script>
    @endpush
</x-admin>

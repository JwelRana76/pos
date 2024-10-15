<x-admin title="Purchase Trash List">
    <x-page-header head="Purchase Trash List" />p
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
    @push('js')
        <script>

        const table = $('#saleTable').dataTable({
            searching: true,
            processing: true,
            serverSide: true,
            ajax: {
                method: 'get',
                url: "{{ route('purchase.trash') }}",
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
        
        </script>
    @endpush
</x-admin>

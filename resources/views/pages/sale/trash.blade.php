<x-admin title="Sale Trash List">
    <x-page-header head="Sale Trash List" />
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="table-responsive p-3">
                    <table class="table align-items-center table-flush" id="saleTable">
                        <thead class="bg-primary text-light">
                            <tr> 
                                <th>Date</th>
                                <th>Voucher No</th>
                                <th>Customer</th>
                                <th>Discount</th>
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
    @push('js')
        <script>
        const table = $('#saleTable').dataTable({
            searching: true,
            processing: true,
            serverSide: true,
            ajax: {
                method: 'get',
                url: "{{ route('sale.trash') }}",
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
                    data: 'customer',
                    name: 'customer',
                },
                {
                    data: 'total_discount',
                    name: 'total_discount',
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
            ]
        });
        </script>
    @endpush
</x-admin>

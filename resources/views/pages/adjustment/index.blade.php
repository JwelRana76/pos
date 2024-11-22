<x-admin title="Adjustment">
    <x-page-header head="Adjustment" />
    <a href="{{route('adjustment.create')}}" class="btn btn-sm btn-primary m-2">
        <i class="fas fa-fw fa-plus"></i> Add New
    </a>
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="table-responsive p-3">
                    <table class="table align-items-center table-flush" id="adjustmentTable">
                        <thead class="bg-primary text-light">
                            <tr> 
                                <th>Date</th>
                                <th>User</th>
                                <th>Quantity</th>
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
    <x-large-modal id="view_adjustment_details">
    </x-large-modal>
    
    @push('js')
        <script>
        const table = $('#adjustmentTable').dataTable({
            searching: true,
            processing: true,
            serverSide: true,
            ajax: {
                method: 'get',
                url: "{{ route('adjustment.index') }}",
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
                    data: 'user',
                    name: 'user',
                },
                {
                    data: 'qty',
                    name: 'qty',
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
                    
                    $.get('/adjustment/show/' + tableData.id, function(data, status) {
                        console.log(data); 
                        const html = `
                            <table class="table" style="margin-top:5px">
                                <tr>
                                    <td width="35%">User</td>
                                    <td>${data?.adjustment?.user?.name} <br /> </td>
                                </tr>
                                <tr>
                                    <td width="40%">Date</td>
                                    <td>${moment(data?.adjustment?.created_at).format('LL')}</td>
                                </tr>
                            </table>
                            <table class="table" style="margin-top:15px;">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${
                                        data.items.map(item => (
                                            `<tr> 
                                                <td>${item.product.name}</td>
                                                <td>${item.qty}</td>
                                            </tr>`    
                                        )).join('')
                                    }
                                <div class="divider"></div>
                                </tbody>
                            </table>
                        `;
                        $('#view_adjustment_details').modal('show');
                        $('#view_adjustment_details .modal-body').html(html);
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

function itd_makeDataTable(e = "", n = "", t = []) {
    const o = new DataTable(e, {
        serverSide: true,
        processing: true,
        fixedHeader: {
            header: true, 
        },
        preDrawCallback: function (settings) {
            $("#custom-loader").show();
        },
        drawCallback: function (settings) {
            $("#custom-loader").hide();
        },
        columnDefs: [
            { orderable: !1, className: "select-checkbox", targets: 0 },
        ],
        lengthMenu: [
            [10, 25, 50, 100, 200, 300, -1],
            [10, 25, 50, 100, 200, 300, "All"],
        ],
        columns: [
            {
                data: "id",
                orderable: !1,
                searchable: !1,
                render: function (e, n, t) {
                    return (
                        '<label class="itd-checkbox mb-4"><input id="select_row" type="checkbox" class="mt-1" name="selected[]" value="' +
                        e +
                        '"><span class="checkmark"></span></label>'
                    );
                },
            },
            ...t,
        ],
        dom: '<lBf<t>i<"m-0"p>>',
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
        footerCallback: function (tfoot, data, start, end, display) {
            var api = this.api();

            if (data.length === 0) {
                // Hide the footer if there is no data
                $(api.table().footer()).hide();
                return;
            }

            let hasNumericData = false;

            // Check for numeric data in columns (excluding the first column)
            api.columns().every(function () {
                const column = this;
                if (column.index() !== 0) {
                    const columnData = column.data();
                    columnData.each(function (d) {
                        if (typeof d === 'number') {
                            hasNumericData = true;
                        }
                    });
                }
            });

            if (!hasNumericData) {
                // Hide the footer if there is no numeric data
                $(api.table().footer()).hide();
                return;
            }

            // Show the footer and proceed with the existing logic
            $(api.table().footer()).show();

            // Set the first footer cell to display 'Total'
            $(api.column(0).footer()).html('Total');

            // Calculate the sum of numeric columns
            api.columns().every(function () {
                const column = this;
                if (column.index() !== 0) { // Skip the first column (checkbox column)
                    const columnData = column.data();
                    let isNumeric = true;
                    columnData.each(function (d) {
                        if (typeof d !== 'number') {
                            isNumeric = false;
                        }
                    });
                    if (isNumeric) {
                        const total = columnData.reduce((a, b) => a + b, 0);
                        $(column.footer()).html(total);
                    }
                }
            });
        }
    });

    return (
        $(document).on("change", "#dtb_all_selector", function () {
            var isChecked = $(this).is(":checked");
            $("input[name='selected[]']").prop("checked", isChecked);

            if (isChecked) {
                o.rows().select();
            } else {
                o.rows().deselect();
            }
        }),
        $(document).on("change", "#warehouse_id", function () {
            const e = $(this).val();
            o.ajax.reload(function (n) {
                n.warehouse_id = e;
            });
        }),
        $(e).on("click", "input[name='selected[]']", function () {
            if ($(this).prop("checked")) {
                o.row($(this).closest("tr")).data();
            }
        }),
        $(".delete_items_from_dt").on("click", function () {
            const e = o
                .rows({ selected: true })
                .data()
                .toArray()
                .map((e) => e.id);
            $.ajax({
                method: "DELETE",
                url: n + "/delete-records",
                data: { ids: e },
                success: function (n) {
                    o.ajax.reload();
                    toastr.success(`${e.length} items deleted.`);
                },
                error: function (e, n, t) {
                    console.error(t);
                },
            });
        }),
        o
    );
}
function itd_makeDataTable2(e = "", n = "", t = []) {
    const o = new DataTable(e, {
        serverSide: true,
        processing: true,
        fixedHeader: {
            header: true,
        },
        preDrawCallback: function (settings) {
            $("#custom-loader").show();
        },
        drawCallback: function (settings) {
            $("#custom-loader").hide();
        },
        columnDefs: [
            { orderable: !1, className: "select-checkbox", targets: 0 },
        ],
        lengthMenu: [
            [10, 25, 50, 100, 200, 300, -1],
            [10, 25, 50, 100, 200, 300, "All"],
        ],
        columns: [
            {
                data: "id",
                orderable: !1,
                searchable: !1,
                render: function (e, n, t) {
                    return (
                        '<label class="itd-checkbox mb-4"><input id="select_row" type="checkbox" class="mt-1" name="selected[]" value="' +
                        e +
                        '"><span class="checkmark"></span></label>'
                    );
                },
            },
            ...t,
        ],
        dom: '<lBf<t>i<"m-0"p>>',
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
        footerCallback: function (tfoot, data, start, end, display) {
            var api = this.api();

            if (data.length === 0) {
                // Hide the footer if there is no data
                $(api.table().footer()).hide();
                return;
            }

            let hasNumericData = false;

            // Check for numeric data in columns (excluding the first column)
            api.columns().every(function () {
                const column = this;
                if (column.index() !== 0) {
                    const columnData = column.data();
                    columnData.each(function (d) {
                        if (typeof d === 'number') {
                            hasNumericData = true;
                        }
                    });
                }
            });

            if (!hasNumericData) {
                // Hide the footer if there is no numeric data
                $(api.table().footer()).hide();
                return;
            }

            // Show the footer and proceed with the existing logic
            $(api.table().footer()).show();

            // Set the first footer cell to display 'Total'
            $(api.column(0).footer()).html('Total');

            // Calculate the sum of numeric columns
            api.columns().every(function () {
                const column = this;
                if (column.index() !== 0) { // Skip the first column (checkbox column)
                    const columnData = column.data();
                    let isNumeric = true;
                    columnData.each(function (d) {
                        if (typeof d !== 'number') {
                            isNumeric = false;
                        }
                    });
                    if (isNumeric) {
                        const total = columnData.reduce((a, b) => a + b, 0);
                        $(column.footer()).html(total);
                    }
                }
            });
        },
        rowCallback: function(row, data) {
            // Add click event listener to each row
            $(row).addClass('cursor-pointer');
            $(row).on('click', function() {
                // Get the sale ID from the data
                const saleId = data.id;

                const currentUrl = window.location.href;
                const urlToShowSale = currentUrl + '/show/' + saleId;
                // Fetch sale details using AJAX and open modal
                $.ajax({
                    method: 'GET',
                    url: urlToShowSale, // Adjust the URL as per your route definition
                    success: function(response) {
                        // Open modal and populate with sale details
                        $('#saleModal .modal-body').html(response);
                        $('#saleModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        }
    });

    $(document).on("change", "#dtb_all_selector", function () {
        var isChecked = $(this).is(":checked");
        $("input[name='selected[]']").prop("checked", isChecked);

        if (isChecked) {
            o.rows().select();
        } else {
            o.rows().deselect();
        }
    });

    $(document).on("change", "#warehouse_id", function () {
        const e = $(this).val();
        o.ajax.reload(function (n) {
            n.warehouse_id = e;
        });
    });

    $(e).on("click", "input[name='selected[]']", function () {
        if ($(this).prop("checked")) {
            o.row($(this).closest("tr")).data();
        }
    });

    $(".delete_items_from_dt").on("click", function () {
        const e = o
            .rows({ selected: true })
            .data()
            .toArray()
            .map((e) => e.id);
        $.ajax({
            method: "DELETE",
            url: n + "/delete-records",
            data: { ids: e },
            success: function (n) {
                o.ajax.reload();
                toastr.success(`${e.length} items deleted.`);
            },
            error: function (e, n, t) {
                console.error(t);
            },
        });
    });

    return o;
}

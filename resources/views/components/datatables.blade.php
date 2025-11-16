<style>
    .action-btn-info {
        background-color: rgba(13, 110, 253, 0.3) !important;
        border: 1px solid rgba(13, 110, 253) !important;
        color: rgb(8, 8, 8) !important;
    }

    .action-btn-danger {
        background-color: rgba(220, 53, 69, 0.3) !important;
        border: 1px solid rgba(220, 53, 69) !important;
        color: rgb(5, 5, 5) !important;
    }

    .action-btn-warning {
        background-color: rgba(255, 193, 7, 0.3) !important;
        border: 1px solid rgba(255, 193, 7) !important;
        color: rgb(7, 7, 7) !important;
    }

    
    .dt-button {
        background-color: rgba(13, 110, 253, 0.3) !important;
        border: 1px solid rgba(13, 110, 253) !important;
        color: rgb(8, 8, 8) !important;
    }

    .add-button {
        color: rgb(8, 8, 8) !important;
    }

    div.dt-buttons>.dt-button:first-child, div.dt-buttons>div.dt-button-split .dt-button:first-child {
        background: rgba(93, 151, 237, 0.748) !important;
    }
    div.dt-button-collection .dt-button {
        background: rgba(93, 151, 237, 0.748) !important;
        border: 1px solid rgba(13, 110, 253) !important;
        color: rgb(8, 8, 8) !important;
    }
    #dataTable-length-wrapper, .dataTables_length{
        justify-content: center;
        align-items: center;
        padding-top: 5px ! important;
    }
    #dataTable-length-wrapper label{
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }
    #dataTable-length-wrapper select {
        min-width: 60px !important;
        margin-left: 5px;
        margin-right: 5px;
    }
    div.dt-button-background {
        background: none !important;
    }
</style>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        initializeDataTables();
    });

    function getTableId(table) {
        return $(table).attr('id');
    }

    function initializeDataTables() {

        $('table').each(function() {
            var tableName = getTableId(this);

            if (tableName) {
                var token = getCsrfToken();
                var table = $('#' + tableName).DataTable({
                    dom: getDataTableDom(),
                    caption: "A fictional company's timesheets table.",
                    buttons: getTableButtons(),
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    paging: true,
                    pageLength: 10,
                    select: true,
                    ajax: getAjaxConfig(token, tableName),
                    columns: getTableColumns(tableName),
                    order: [
                        [0, 'desc']
                    ],
                    drawCallback: function(settings) {
                        handleDrawCallback.call(this, settings, tableName);
                        attachRowClickEvent(tableName);
                    },
                    sPaginationType: "full_numbers",
                    lengthMenu: getLengthMenuOptions(),
                    language: getLanguageOptions()
                });

                attachSearchInputListener(table, tableName);
            }
        });
    }


    function getCsrfToken() {
        return $('meta[name="csrf-token"]').attr('content');
    }


    function getAjaxConfig(token, tableName) {
        return {
            url: indexUrlBuilder(tableName),
            type: 'GET',
            data: function(d) {
                d.page = d.start / d.length + 1;
                d.search = $('#' + tableName + '_filter input').val();
            },
            beforeSend: function(xhr) {
                xhr.setRequestHeader("X-CSRF-TOKEN", token);
            },
            dataSrc: function(json) {
                if (json && json.data && json.data.data) {
                    json.recordsTotal = json.data.meta.total;
                    json.recordsFiltered = json.data.meta.total;
                    return json.data.data;
                }
                return [];
            }
        };
    }
        

    function getDataTableDom() {
        return `
            <"row py-2"<"col-sm-12 col-md-8"B><"col-sm-12 col-md-4"f>>
            <"row"<"col-sm-12"tr>>
            <"row py-2"
            <"col-sm-12 col-md-3"i>
            <"col-sm-12 col-md-2"l>
            <"col-sm-12 col-md-7"p>>
        `;
    }


function getTableColumns(tableName) {
    return [
        getSLColumnConfig(),
        ...getColumns(tableName),
        getActions(tableName)
    ];
}

function getColumns(tableName) {
    let table = document.getElementById(tableName);
    if (!table) {
        console.error("Table not found:", tableName);
        return [];
    }

    let columnsData = table.getAttribute("data-fields");
    if (!columnsData) {
        console.warn("No data-columns attribute found on table:", tableName);
        return [];
    }

    try {
        let columns = JSON.parse(columnsData);

        if (!Array.isArray(columns)) {
            console.error("Invalid format for data-columns. Expected an array.");
            return [];
        }

        return columns.map(column => ({
            data: column.trim(),
            name: column.trim(),
            title: column.trim().replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()) // Format title
        }));

    } catch (error) {
        console.error("Error parsing data-columns:", error);
        return [];
    }
}


    function indexUrlBuilder(tableName) {
        return $('#' + tableName).data('index-url');
    }

    function viewUrlBuilder(tableName, id) {
        return $('#' + tableName).data('show-url').replace(':id', id);
    }

    function editUrlBuilder(tableName, id) {
        return $('#' + tableName).data('edit-url').replace(':id', id);
    }

    function deleteUrlBuilder(tableName, id) {
        return $('#' + tableName).data('delete-url').replace(':id', id);
    }

    function getActions(tableName) {
        return {
            data: 'id',
            name: 'actions',
            title: 'Actions',
            orderable: false,
            searchable: false,
            render: function(data, type, row) {
                var view = viewUrlBuilder(tableName, row.id);
                var edit = editUrlBuilder(tableName, row.id);
                var deleteUrl = deleteUrlBuilder(tableName, row.id);
                return `
                <a href="${view}" class="btn btn-sm action-btn-info action-btn" data-action="view">
                    <span class="default-text"><i class="fa fa-eye"></i></span>
                    <span class="spinner-border spinner-border-sm d-none"></span><span class="">&nbsp;View</span>
                </a>
                <a href="${edit}" class="btn btn-sm action-btn-warning action-btn" data-action="edit">
                    <span class="default-text"><i class="fa fa-edit"></i></span>
                    <span class="spinner-border spinner-border-sm d-none"></span><span class="">&nbsp;Edit</span>
                </a>
                <a href="${deleteUrl}" class="btn btn-sm action-btn-danger delete action-btn" data-id="${row.id}" data-action="delete">
                    <span class="default-text"><i class="fa fa-trash"></i></span>
                    <span class="spinner-border spinner-border-sm d-none"></span><span class="">&nbsp;Delete</span>
                </a>
            `;
            }
        };
    }

    function getSLColumnConfig(tableName) {
        return {
            title: 'SL',
            data: null,
            orderable: true,
            searchable: false,
            render: function(data, type, row, meta) {
                var table = $('#' + tableName).DataTable();
                return generateSLNumber(meta, table);
            }
        };
    }

    // function getSLColumnConfig(team_invitations) {
    //     return {
    //         title: 'SL',
    //         data: null,
    //         orderable: true,
    //         searchable: false,
    //         render: function(data, type, row, meta) {
    //             var table = $('#' + team_invitations).DataTable();
    //             var pageInfo = table.page.info();
    //             return pageInfo.start + meta.row + 1;
    //         }
    //     };
    // }

    function generateSLNumber(meta, table) {
        var pageInfo = table.page.info();
        var total = meta.settings.json.data.meta.total || 0;
        var start =  meta.settings.json.data.meta.from - 1 || 0;

        return table.order() === 'desc' ?
            total - (meta.row + start) :
            meta.row + 1 + start;
    }

    function handleDrawCallback(settings, team_invitations) {
        var api = this.api();
        var json = api.ajax.json();
        if (json && json.data && json.data.meta) {
            var meta = json.data.meta;
            $('#' + team_invitations + '_info').html(
                `Showing ${meta.from || 0} to ${meta.to || 0} of ${meta.total || 0} entries`
            );
        }
    }

    function getLengthMenuOptions() {
        return [
            [10, 20, 40, 50, 80, 100, 150, 200, 500, -1],
            [10, 20, 40, 50, 80, 100, 150, 200, 500, "All"]
        ];
    }

    function getLanguageOptions() {
        return {
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            },
            emptyTable: "No data available in table",
            loadingRecords: "Loading...",
            processing: "Processing...",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            lengthMenu: "Show _MENU_ entries",
            search: "Search:",
            zeroRecords: "No matching records found"
        };
    }

    function attachSearchInputListener(table, tableName) {
        $('#' + tableName + '_filter input').on('keyup', function() {
            table.ajax.reload();
        });
    }

    function getTableButtons() {
        return [
        {
            extend: 'collection',
            text: 'Export',
            className: 'btn btn-primary',
            buttons: [
                getCopyButton(),
                getCsvButton(),
                getExcelButton(),
                getPdfButton(),
                getJsonButton(),
                getXmlButton(),
                getPrintButton()
            ]
        },
            getColVisButton()
        ];
    }

    function getCopyButton() {
        return {
            extend: 'copy',
            className: 'btn btn-primary'
        };
    }

    function getPrintButton() {
        return {
            extend: 'print',
            className: 'btn btn-secondary',
            text: 'Print',
            exportOptions: {
                columns: ':not(:last-child)'
            },
            customize: function(doc) {
                doc.pageSize = 'A4';
                doc.pageMargins = [20, 20, 20, 20];
                doc.styles.tableHeader.alignment = 'center';
                doc.styles.tableHeader.fontSize = 10;
                doc.styles.tableHeader.fillColor = '#f2f2f2';
                doc.styles.tableHeader.bold = true;
                doc.defaultStyle.fontSize = 10;
                doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                doc.content = [{
                    table: doc.content[1].table
                }];
            }
        };
    };

    function getCsvButton() {
        return {
            extend: 'csv',
            className: 'btn btn-success'
        };
    }

    function getExcelButton() {
        return {
            extend: 'excel',
            className: 'btn btn-info'
        };
    }

    function getPdfButton() {
        return {
            extend: 'pdf',
            className: 'btn btn-danger',
            exportOptions: {
                columns: ':not(:last-child)'
            },
            customize: function(doc) {
                doc.pageSize = 'A4';
                doc.pageMargins = [15, 15, 15, 15];
                doc.styles.tableHeader.alignment = 'center';
                doc.styles.tableHeader.fontSize = 10;
                doc.defaultStyle.fontSize = 10;
                doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
            }
        };
    }

    function getJsonButton() {
        return {
            text: 'JSON',
            className: 'btn btn-warning',
            action: function(e, dt, node, config) {
                let table = dt;
                let jsonData = table.rows({
                    search: 'applied'
                }).data().toArray();
                let jsonStr = JSON.stringify(jsonData, null, 2);

                let blob = new Blob([jsonStr], {
                    type: "application/json"
                });
                let link = document.createElement("a");
                link.href = URL.createObjectURL(blob);
                link.download = "data.json";
                link.click();
            }
        };
    }

    function getXmlButton() {
        return {
            text: 'XML',
            className: 'btn btn-dark',
            action: function(e, dt, node, config) {
                let xml = '<?xml version="1.0" encoding="UTF-8"?><data>';
                let table = dt;

                table.rows({
                    search: 'applied'
                }).data().each(function(row) {
                    xml += '<row>';
                    for (let key in row) {
                        xml += `<${key}>${row[key]}</${key}>`;
                    }
                    xml += '</row>';
                });
                xml += '</data>';

                let blob = new Blob([xml], {
                    type: "text/xml"
                });
                let link = document.createElement("a");
                link.href = URL.createObjectURL(blob);
                link.download = "data.xml";
                link.click();
            }
        };
    }


    function getColVisButton() {
        return {
            extend: 'colvis',
            className: 'btn btn-light',
            text: 'Column Visibility'
        };
    }

    function getSpacer() {
        return {
            extend: 'spacer',
            style: 'bar',
            text: 'Export:'
        };
    }

    function attachRowClickEvent(tableName) {

        $('#' + tableName + ' tbody').off("click").on("click", "tr", function(e) {

            if (!$(e.target).closest('.action-btn').length) {
                let editButton = $(this).find('.action-btn-warning');
                let editUrl = editButton.attr("href");

                if (editUrl) {
                    window.location.href = editUrl;
                }
            }
        });
    }


    $(document).on("click", ".action-btn", function(e) {
        e.preventDefault();

        let $btn = $(this);
        let action = $btn.data("action");

        $btn.find(".default-text").addClass("d-none");
        $btn.find(".spinner-border").removeClass("d-none");
        $btn.prop("disabled", true);

        if (action === "delete") {
            let id = $btn.data("id");
            deleteItem(id, $btn);
        } else {
            window.location.href = $btn.attr("href");
        }
    });

    function deleteItem(id, button) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, keep it',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: $(button).attr("href"),
                    type: "DELETE",
                    data: {
                        _token: getCsrfToken()
                    },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            'Your item has been deleted.',
                            'success'
                        );
                        // $('#example1').DataTable().ajax.reload(); // reload table data
                        button.closest("tr").remove(); // Optionally remove the row from the table
                    },
                    error: function(xhr, status, error) {
                        Swal.fire(
                            'Error!',
                            'An error occurred. Please try again.',
                            'error'
                        );
                        button.find(".default-text").removeClass("d-none");
                        button.find(".spinner-border").addClass("d-none");
                        button.prop("disabled", false);
                    }
                });
            } else {
                // Reset the button if the action was cancelled
                button.find(".default-text").removeClass("d-none");
                button.find(".spinner-border").addClass("d-none");
                button.prop("disabled", false);
            }
        });
    }

    $(window).on("pageshow", function(event) {
        // Reset button state when the page is shown
        $(".action-btn .default-text").removeClass("d-none");
        $(".action-btn .spinner-border").addClass("d-none");
        $(".action-btn").prop("disabled", false);
    });
</script>

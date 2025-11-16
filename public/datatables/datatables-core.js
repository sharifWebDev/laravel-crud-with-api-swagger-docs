// datatables-core.js - Loading Spinner Only on First Load
class DataTablesManager {
    constructor() {
        this.tables = new Map();
        this.initialized = false;
        this.init();
    }

    async init() {
        await this.loadCSS();
        await this.loadJS();
        this.initialized = true;
        this.initTables();
    }

    loadCSS() {
        return new Promise((resolve) => {
            if (document.querySelector("#dt-styles")) {
                resolve();
                return;
            }

            const css = `
            <link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/dt-1.13.6/datatables.min.css"/>
            <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css"/>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
            <style>
                .dt-container {
                    background: #fff;
                    border-radius: 12px;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                    overflow: hidden;
                    margin-bottom: 2rem;
                    position: relative;
                    min-height: 400px;
                }

                /* Loading Spinner Styles - Only for initial load */
                .dt-initial-loading {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(255, 255, 255, 0.95);
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    z-index: 1000;
                    border-radius: 12px;
                }

                .dt-initial-loading-spinner {
                    width: 50px;
                    height: 50px;
                    border: 4px solid #f3f4f6;
                    border-top: 4px solid #3b82f6;
                    border-radius: 50%;
                    animation: dt-spin 1s linear infinite;
                    margin-bottom: 1rem;
                }

                .dt-initial-loading-text {
                    color: #6b7280;
                    font-size: 1rem;
                    font-weight: 500;
                }

                @keyframes dt-spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }

                .dt-header {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 1.5rem;
                }
                .dt-filter-row {
                    background: #f8fafc;
                    padding: 1rem 1.5rem;
                    border-bottom: 1px solid #e2e8f0;
                    display: flex;
                    gap: 12px;
                    flex-wrap: wrap;
                    align-items: center;
                }

                /* DataTables Custom Layout */
                .dataTables_wrapper {
                    padding: 0;
                }

                /* Row 1: Buttons & Search */
                .dt-controls-row {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: .4rem .5rem;
                    background: #f8fafc;
                    border-bottom: 1px solid #e2e8f0;
                    margin: 0;
                }

                .dt-controls-row .dt-buttons {
                    margin: 0 !important;
                    display: flex;
                    gap: 8px;
                    flex-wrap: wrap;
                }

                .dt-controls-row .dataTables_filter {
                    margin: 0 !important;
                    display: flex;
                    align-items: center;
                }

                .dt-controls-row .dataTables_filter label {
                    margin: 0 !important;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }

                .dt-controls-row .dataTables_filter input {
                    border: 1px solid #e2e8f0;
                    border-radius: 8px;
                    padding: 8px 12px;
                    font-size: 0.875rem;
                    min-width: 250px;
                }

                /* Row 2: Table */
                .dt-table-row {
                    margin: 0;
                    padding: 0;
                }

                .table-modern {
                    border: none;
                    margin: 0;
                    width: 100% !important;
                }

                .table-modern thead th {
                    background: #f8fafc;
                    border-bottom: 2px solid #e2e8f0;
                    font-weight: 600;
                    color: #374151;
                    padding: 1rem;
                    font-size: 0.875rem;
                    text-transform: uppercase;
                    letter-spacing: 0.05em;
                }

                .table-modern tbody td {
                    padding: 1rem;
                    border-bottom: 1px solid #f1f5f9;
                    vertical-align: middle;
                }

                .table-modern tbody tr:hover {
                    background: #f8fafc;
                }

                /* Row 3: Info | Per Page | Pagination */
                .dt-pagination-row {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 1rem .5rem;
                    background: #f8fafc;
                    border-top: 1px solid #e2e8f0;
                    margin: 0;
                    flex-wrap: wrap;
                }

                .dt-pagination-row .dataTables_info {
                    margin: 0 !important;
                    padding: 0 !important;
                    color: #64748b;
                    font-size: 0.875rem;
                    font-weight: 500;
                    display: block !important;
                }

                .dt-pagination-row .dataTables_length {
                    margin: 0 !important;
                    padding: 0 !important;
                    display: flex !important;
                    align-items: center;
                    gap: 8px;
                }

                .dt-pagination-row .dataTables_length label {
                    margin: 0 !important;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    color: #64748b;
                    font-size: 0.875rem;
                    font-weight: 500;
                }

                .dt-pagination-row .dataTables_length select {
                    border: 1px solid #e2e8f0;
                    border-radius: 8px;
                    padding: 6px 12px;
                    font-size: 0.875rem;
                    background: white;
                    min-width: 80px;
                }

                .dt-pagination-row .dataTables_paginate {
                    margin: 0 !important;
                    padding: 0 !important;
                    display: flex;
                    justify-content: flex-end;
                    flex: 1;
                }

                .pagination {
                    gap: 4px;
                    margin: 0 !important;
                }

                .page-link {
                    border: 1px solid #e2e8f0;
                    border-radius: 8px !important;
                    padding: 8px 12px;
                    color: #64748b;
                    font-weight: 500;
                    min-width: 40px;
                    text-align: center;
                }

                .page-link:hover {
                    background: #f8fafc;
                    border-color: #cbd5e0;
                    color: #374151;
                }

                .page-item.active .page-link {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border-color: #667eea;
                    color: white;
                }

                .page-item.disabled .page-link {
                    color: #9ca3af;
                    background: #f9fafb;
                    border-color: #e5e7eb;
                }

                /* Other Styles */
                .badge {
                    font-size: 0.75rem;
                    font-weight: 600;
                    padding: 6px 12px;
                    border-radius: 20px;
                    text-transform: capitalize;
                }

                .btn-group-sm .btn {
                    border-radius: 6px;
                    margin: 2px;
                }

                .dt-button {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
                    color: white !important;
                    border: none !important;
                    border-radius: 8px !important;
                    padding: 8px 16px !important;
                    margin: 2px !important;
                    transition: all 0.2s !important;
                }

                .dt-button:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
                }

                div.dt-button-collection {
                    background: white;
                    border: 1px solid #e2e8f0;
                    border-radius: 8px;
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                    padding: 8px;
                    min-width: 200px;
                }

                div.dt-button-collection .dt-button {
                    background: transparent !important;
                    color: #374151 !important;
                    border: none !important;
                    text-align: left;
                    margin: 2px 0 !important;
                    padding: 8px 12px !important;
                    border-radius: 6px !important;
                }

                div.dt-button-collection .dt-button:hover {
                    background: #f8fafc !important;
                    transform: none !important;
                    box-shadow: none !important;
                }

                .dt-filter-input {
                    border: 1px solid #e2e8f0;
                    border-radius: 8px;
                    padding: 8px 12px;
                    font-size: 0.875rem;
                    transition: all 0.2s;
                    min-width: 140px;
                }

                .dt-filter-input:focus {
                    outline: none;
                    border-color: #3b82f6;
                    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
                }

                .dt-actions {
                    display: flex;
                    gap: 8px;
                    margin-left: auto;
                }

                .dt-btn {
                    background: white;
                    border: 1px solid #e2e8f0;
                    border-radius: 8px;
                    padding: 8px 16px;
                    font-size: 0.875rem;
                    font-weight: 500;
                    cursor: pointer;
                    transition: all 0.2s;
                    display: flex;
                    align-items: center;
                    gap: 6px;
                }

                .dt-btn:hover {
                    background: #f8fafc;
                    border-color: #cbd5e0;
                    transform: translateY(-1px);
                }

                .dt-btn-primary {
                    background: #3b82f6;
                    color: white;
                    border-color: #3b82f6;
                }

                .dt-btn-primary:hover {
                    background: #2563eb;
                    border-color: #2563eb;
                }

                /* Responsive Design */
                @media (max-width: 768px) {
                    .dt-controls-row {
                        flex-direction: column;
                        gap: 1rem;
                        align-items: stretch;
                    }

                    .dt-controls-row .dt-buttons {
                        justify-content: center;
                    }

                    .dt-controls-row .dataTables_filter {
                        justify-content: center;
                    }

                    .dt-controls-row .dataTables_filter input {
                        min-width: 200px;
                    }

                    .dt-pagination-row {
                        flex-direction: column;
                        text-align: center;
                    }

                    .dt-pagination-row .dataTables_info,
                    .dt-pagination-row .dataTables_length,
                    .dt-pagination-row .dataTables_paginate {
                        justify-content: center;
                        flex: none;
                    }
                }

                /* ==== SMART DATATABLE PAGINATION ==== */
                .dataTables_wrapper .dataTables_paginate {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 4px;
                    margin-top: 12px;
                }

                .dataTables_wrapper .dataTables_paginate .paginate_button {
                    background-color: #f3f4f6;
                    border: none;
                    border-radius: 8px;
                    color: #374151;
                    padding: 6px 10px;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    font-weight: 500;
                    font-size: 14px;
                }

                .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                    background-color: #e5e7eb;
                    color: #111827;
                }

                .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                    background-color: #2563eb;
                    color: white !important;
                    box-shadow: 0 1px 3px rgba(37, 99, 235, 0.3);
                }

                .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
                    opacity: 0.4;
                    cursor: not-allowed;
                }

                .dataTables_wrapper .dataTables_paginate .previous:before {
                    content: '←';
                    margin-right: 6px;
                }

                .dataTables_wrapper .dataTables_paginate .next:after {
                    content: '→';
                    margin-left: 6px;
                }

                .dataTables_wrapper .dataTables_info {
                    text-align: center;
                    font-size: 13px;
                    color: #4b5563;
                    margin-top: 8px;
                }

                .smart-pagination-row .dataTables_info {
                    font-size: 14px;
                    color: #374151;
                }

                .smart-pagination-row select {
                    border-radius: 6px;
                    border: 1px solid #d1d5db;
                    padding: 4px 6px;
                    background-color: #fff;
                    color: #374151;
                }

                .smart-pagination-row .dataTables_paginate .paginate_button {
                    background: #f3f4f6;
                    border-radius: 6px;
                    padding: 4px 10px;
                    margin: 0 2px;
                    color: #374151;
                    transition: all 0.2s ease;
                }
                .smart-pagination-row .dataTables_paginate .paginate_button:hover {
                    background: #e5e7eb;
                }
                .smart-pagination-row .dataTables_paginate .paginate_button.current {
                    background: #2563eb;
                    color: #fff !important;
                    box-shadow: 0 1px 2px rgba(37, 99, 235, 0.3);
                }
            </style>
        `;
            document.head.insertAdjacentHTML("beforeend", css);
            setTimeout(resolve, 100);
        });
    }

    loadJS() {
        return new Promise((resolve) => {
            const scripts = [
                "https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js",
                "https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js",
                "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js",
                "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js",
                "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js",
                "https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js",
                "https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js",
                "https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js",
            ];

            const loadScript = (src) => {
                return new Promise((scriptResolve) => {
                    if (document.querySelector(`script[src="${src}"]`)) {
                        scriptResolve();
                        return;
                    }

                    const script = document.createElement("script");
                    script.src = src;
                    script.onload = scriptResolve;
                    script.onerror = scriptResolve;
                    document.body.appendChild(script);
                });
            };

            const loadSequentially = async () => {
                for (const src of scripts) {
                    await loadScript(src);
                }
                setTimeout(resolve, 500);
            };

            loadSequentially();
        });
    }

    initTables() {
        if (!this.initialized) {
            console.warn("DataTables Manager not fully initialized");
            return;
        }

        document.querySelectorAll("table[data-dt]").forEach((table) => {
            if (!this.tables.has(table.id) && table.id) {
                try {
                    this.tables.set(table.id, new ModernDataTable(table));
                } catch (error) {
                    console.error(
                        `Failed to initialize table ${table.id}:`,
                        error
                    );
                }
            }
        });
    }

    getTable(id) {
        return this.tables.get(id);
    }
}

class ModernDataTable {
    constructor(tableEl) {
        this.id = tableEl.id;
        this.el = tableEl;
        this.config = this.parseConfig();
        this.filters = {};
        this.dt = null;
        this.currentPerPage = 10;
        this.initialLoadCompleted = false; // Track if initial load is done

        this.ensureTableStructure();
        this.init();
    }

    ensureTableStructure() {
        if (!this.el.querySelector("thead")) {
            const thead = document.createElement("thead");
            const tbody = document.createElement("tbody");
            this.el.appendChild(thead);
            this.el.appendChild(tbody);
        }
    }

    parseConfig() {
        return {
            title: this.el.dataset.title || "Data Table",
            fields: JSON.parse(this.el.dataset.fields || "[]"),
            headers: JSON.parse(this.el.dataset.headers || "[]"),
            indexUrl: this.el.dataset.indexUrl,
            showUrl: this.el.dataset.showUrl,
            editUrl: this.el.dataset.editUrl,
            deleteUrl: this.el.dataset.deleteUrl,
            filters: JSON.parse(this.el.dataset.filters || "[]"),
            export: this.el.dataset.export === "true",
            colvis: this.el.dataset.colvis === "true",
            buttons: {
                csv: this.el.dataset.csv === "true",
                excel: this.el.dataset.excel === "true",
                pdf: this.el.dataset.pdf === "true",
                print: this.el.dataset.print === "true",
            },
        };
    }

    init() {
        this.wrapTable();
        this.createHeader();
        this.createFilterRow();

        // Show initial loading spinner only on first load
        if (!this.initialLoadCompleted) {
            this.showInitialLoading();
        }

        this.initDataTable();
        this.bindEvents();
    }

    wrapTable() {
        if (this.el.closest(".dt-container")) {
            this.container = this.el.closest(".dt-container");
            return;
        }

        const container = document.createElement("div");
        container.className = "dt-container";
        this.el.parentNode.insertBefore(container, this.el);
        container.appendChild(this.el);
        this.container = container;
    }

    createHeader() {
        if (!this.config.title) return;
        const existingHeader = document.querySelector(".card-title");
        if (existingHeader) existingHeader.textContent = this.config.title;
    }

    createFilterRow() {
        if (this.config.filters.length === 0) return;

        const existingFilter = this.container.querySelector(".dt-filter-row");
        if (existingFilter) existingFilter.remove();

        const filterRow = document.createElement("div");
        filterRow.className = "dt-filter-row";
        filterRow.innerHTML = `
            <span style="font-weight: 500; color: #64748b;">Filters:</span>
            ${this.config.filters
                .map(
                    (field) => `
                <input type="text"
                       class="dt-filter-input"
                       placeholder="${this.formatHeader(field)}"
                       data-field="${field}"
                       autocomplete="off">
            `
                )
                .join("")}
            <button class="dt-btn" onclick="window.dtManager.getTable('${
                this.id
            }').clearFilters()" style="margin-left: auto;">
                <i class="fas fa-times"></i>
                Clear
            </button>
        `;

        const header = this.container.querySelector(".dt-header");
        if (header) {
            header.parentNode.insertBefore(filterRow, header.nextSibling);
        } else {
            this.container.insertBefore(filterRow, this.container.firstChild);
        }
    }

    // Initial loading spinner (only shown once)
    showInitialLoading() {
        // Create initial loading overlay
        const loadingOverlay = document.createElement("div");
        loadingOverlay.className = "dt-initial-loading";
        loadingOverlay.id = `${this.id}-initial-loading`;
        loadingOverlay.innerHTML = `
            <div class="dt-initial-loading-spinner"></div>
            <div class="dt-initial-loading-text">Loading table data...</div>
        `;

        // Add to container
        if (this.container) {
            this.container.style.position = "relative";
            this.container.appendChild(loadingOverlay);
        }
    }

    hideInitialLoading() {
        // Remove initial loading overlay
        const loadingOverlay = document.getElementById(
            `${this.id}-initial-loading`
        );
        if (loadingOverlay) {
            loadingOverlay.remove();
        }
        this.initialLoadCompleted = true;
    }

    initDataTable() {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable(this.el)) {
            this.dt = $(this.el).DataTable();
            return;
        }

        if (!$.fn.DataTable) {
            console.error("DataTables not loaded properly");
            this.hideInitialLoading();
            return;
        }

        try {
            this.dt = $(this.el)
                .addClass("table-modern")
                .DataTable({
                    dom: `
                    <"row dt-row-1"<"col-md-8"B><"col-md-4"f>>
                    <"row dt-row-2"<"col-md-12"tr>>
                    <"row dt-row-3"<"col-md-4"i><"col-md-4 text-center"l><"col-md-4"p>>
                `,
                    processing: true,
                    serverSide: true,
                    searchDelay: 500,
                    ajax: {
                        url: this.config.indexUrl,
                        type: "GET",
                        data: (data) => this.serializeData(data),
                        dataSrc: (response) => {
                            // Hide initial loading on first successful data load
                            if (!this.initialLoadCompleted) {
                                this.hideInitialLoading();
                            }
                            // Store the original response for pagination info
                            if (response && response.data) {
                                response.recordsTotal =
                                    response.data.meta?.total || 0;
                                response.recordsFiltered =
                                    response.data.meta?.total || 0;
                            }
                            return this.handleAjaxResponse(response);
                        },
                        error: (xhr) => {
                            // Hide initial loading on error
                            if (!this.initialLoadCompleted) {
                                this.hideInitialLoading();
                            }
                            console.error("DataTables AJAX error:", xhr);
                            this.showToast("Failed to load data", "error");
                            return [];
                        },
                    },
                    columns: this.getColumns(),
                    buttons: this.getButtons(),
                    pageLength: this.currentPerPage,
                    lengthMenu: [
                        [10, 20, 30, 50, 100, 200, 500, -1],
                        [10, 20, 30, 50, 100, 200, 500, "All"],
                    ],
                    order: [[0, "desc"]],
                    pagingType: "full_numbers",
                    language: {
                        emptyTable: "No records found",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "Showing 0 to 0 of 0 entries",
                        infoFiltered: "(filtered from _MAX_ total entries)",
                        lengthMenu: "Show _MENU_ entries",
                        search: "Search:",
                        zeroRecords: "No matching records found",
                        paginate: {
                            first: '<i class="fas fa-angle-double-left"></i>',
                            last: '<i class="fas fa-angle-double-right"></i>',
                            next: "",
                            previous: '<i class="fas fa-angle-left"></i>',
                        },
                    },
                    drawCallback: (settings) => {
                        this.updateCustomPaginationInfo(settings);
                    },
                    initComplete: (settings) => {
                        this.updateCustomPaginationInfo(settings);
                        this.customizeDataTablesLayout();
                        this.createCustomRefreshButton();

                        // Final safety net to hide initial loading
                        setTimeout(() => {
                            if (!this.initialLoadCompleted) {
                                this.hideInitialLoading();
                            }
                        }, 3000);
                    },
                });
        } catch (error) {
            console.error("DataTable initialization error:", error);
            this.hideInitialLoading();
            this.showToast("Failed to initialize table", "error");
        }
    }

    customizeDataTablesLayout() {
        const wrapper = $(this.el).closest(".dataTables_wrapper");
        wrapper.find(".dataTables_length").hide();
        wrapper.find(".dt-row-1").addClass("dt-controls-row");
        wrapper.find(".dt-row-2").addClass("dt-table-row");
        wrapper.find(".dt-row-3").addClass("dt-pagination-row");
    }

    updateCustomPaginationInfo(settings) {
        if (!this.dt) return;

        const info = this.dt.page.info();

        // Get server-side pagination data from the last AJAX response
        const ajaxData = this.dt.ajax.json();

        let start = info.start + 1;
        let end = info.end;
        let total = info.recordsTotal;
        let filteredTotal = info.recordsFiltered;

        // Use server-side pagination data if available (for serverSide: true)
        if (ajaxData && ajaxData.data && ajaxData.data.meta) {
            const meta = ajaxData.data.meta;
            start = meta.from || start;
            end = meta.to || end;
            total = meta.total || total;
            filteredTotal = total; // For server-side, filtered total usually equals total unless searching
        }

        // If there's searching/filtering, use recordsFiltered from DataTables
        if (info.recordsFiltered !== info.recordsTotal) {
            filteredTotal = info.recordsFiltered;
        }

        let infoText = `Showing ${start} to ${end} of ${total} entries`;

        if (filteredTotal !== total) {
            infoText += ` (filtered from ${total} total entries)`;
        }

        // Update the pagination info element
        const infoElement = document.getElementById(
            `${this.id}-pagination-info`
        );
        if (infoElement) {
            infoElement.textContent = infoText;
        }

        // Update per page selector
        const perPageSelect = document.getElementById(`${this.id}-per-page`);
        if (perPageSelect) {
            perPageSelect.value = this.currentPerPage || info.length;
        }
        this.updateBuiltInInfo(start, end, filteredTotal, total);
    }

    updateBuiltInInfo(start, end, filteredTotal, total) {
        const wrapper = $(this.el).closest(".dataTables_wrapper");
        const builtInInfo = wrapper.find(".dataTables_info");

        if (builtInInfo.length) {
            let text = `Showing ${start} to ${end} of ${total} entries`;
            builtInInfo.text(text);
        }
    }

    getColumns() {
        const columns = [
            {
                data: null,
                title: "SL",
                searchable: false,
                sortable: true,
                render: (data, type, row, meta) => {
                    if (!this.dt) return meta.row + 1;
                    const info = this.dt.page.info();
                    return `<div style="font-weight: 600; color: #64748b;">${
                        info.start + meta.row + 1
                    }</div>`;
                },
                orderable: false,
                className: "text-center",
            },
        ];

        this.config.fields.forEach((field, index) => {
            columns.push({
                data: field,
                name: field,
                title: this.config.headers[index] || this.formatHeader(field),
                render: (data, type, row) => this.renderField(field, data, row),
                defaultContent: "",
            });
        });

        columns.push({
            data: "id",
            render: (data, type, row) => this.renderActions(row),
            orderable: false,
            searchable: false,
            className: "text-center",
            title: "Actions",
        });

        return columns;
    }

    getButtons() {
        const buttons = [];

        if (this.config.export) {
            buttons.push({
                extend: "collection",
                text: '<i class="fas fa-download me-1"></i> Export',
                className: "dt-button",
                buttons: [
                    {
                        extend: "csv",
                        text: '<i class="fas fa-file-csv me-1"></i> CSV',
                        className: "dt-button",
                        exportOptions: { columns: ":visible:not(:last-child)" },
                    },
                    {
                        extend: "excel",
                        text: '<i class="fas fa-file-excel me-1"></i> Excel',
                        className: "dt-button",
                        exportOptions: { columns: ":visible:not(:last-child)" },
                    },
                    {
                        extend: "pdf",
                        text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                        className: "dt-button",
                        exportOptions: { columns: ":visible:not(:last-child)" },
                    },
                    {
                        extend: "print",
                        text: '<i class="fas fa-print me-1"></i> Print',
                        className: "dt-button",
                        exportOptions: { columns: ":visible:not(:last-child)" },
                    },
                ],
            });
        }

        if (this.config.colvis) {
            buttons.push({
                extend: "colvis",
                text: '<i class="fas fa-eye me-1"></i> Columns',
                className: "dt-button",
                postfixButtons: ["colvisRestore"],
            });
        }

        return buttons;
    }

    createCustomRefreshButton() {
        const wrapper = $(this.el).closest(".dataTables_wrapper");
        const buttonsContainer = wrapper.find(".dt-buttons");

        if (buttonsContainer.length) {
            const refreshButton = $(`
                <button class="dt-button refresh-btn" title="Refresh Table">
                    <i class="fas fa-sync-alt me-1"></i> Refresh
                </button>
            `);

            refreshButton.on("click", () => this.refresh());
            buttonsContainer.prepend(refreshButton);
        }
    }

    serializeData(data) {
        const params = new URLSearchParams();

        // Pagination
        const page = data.start / data.length + 1;
        params.append("page", page);
        params.append("per_page", data.length);

        // Search
        if (data.search.value) {
            params.append("search", data.search.value);
        }

        // Order
        if (data.order && data.order.length > 0) {
            const orderColumn = data.columns[data.order[0].column];
            if (orderColumn && orderColumn.data) {
                params.append("sort_by", orderColumn.data);
                params.append("sort_order", data.order[0].dir);
            }
        }

        // Custom filters
        Object.keys(this.filters).forEach((field) => {
            if (this.filters[field]) {
                params.append(`filters[${field}]`, this.filters[field]);
            }
        });

        return params.toString();
    }

    handleAjaxResponse(response) {
        // Handle Laravel API response format
        const result = response.data || response;

        let recordsTotal = 0;
        let recordsFiltered = 0;
        let tableData = [];

        if (result && typeof result === "object") {
            // Laravel paginated response
            if (result.data && Array.isArray(result.data)) {
                tableData = result.data;
                recordsTotal = result.meta?.total || result.total || 0;
                recordsFiltered = result.meta?.total || result.total || 0;
            }
            // Simple array response
            else if (Array.isArray(result)) {
                tableData = result;
                recordsTotal = result.length;
                recordsFiltered = result.length;
            }
            // Direct data response
            else {
                tableData = [result];
                recordsTotal = 1;
                recordsFiltered = 1;
            }
        } else if (Array.isArray(response)) {
            tableData = response;
            recordsTotal = response.length;
            recordsFiltered = response.length;
        }

        return tableData;
    }

    refresh() {
        if (this.dt) {
            // Use DataTables built-in AJAX reload - no custom spinner
            this.dt.ajax.reload();
        }
    }

    bindEvents() {
        // Filter inputs with debounce - no custom spinner
        if (this.container) {
            this.container
                .querySelectorAll(".dt-filter-input")
                .forEach((input) => {
                    let timeout;
                    input.addEventListener("input", (e) => {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => {
                            this.filters[e.target.dataset.field] =
                                e.target.value.trim();
                            this.refresh(); // Uses DataTables built-in loading
                        }, 500);
                    });
                });
        }

        // Per page selector - no custom spinner
        const perPageSelect = document.getElementById(`${this.id}-per-page`);
        if (perPageSelect) {
            perPageSelect.addEventListener("change", (e) => {
                const newPerPage = parseInt(e.target.value);
                this.currentPerPage = newPerPage;
                if (this.dt) {
                    this.dt.page.len(newPerPage).draw(); // Uses DataTables built-in loading
                }
            });
        }

        // Make rows clickable for edit
        $(this.el).on("click", "tbody tr", (e) => {
            if (!$(e.target).closest(".btn").length && this.config.editUrl) {
                const data = this.dt.row(e.currentTarget).data();
                if (data && data.id) {
                    window.location.href = this.config.editUrl.replace(
                        ":id",
                        data.id
                    );
                }
            }
        });
    }

    showExportMenu() {
        if (this.dt) {
            const exportButton = $(this.el)
                .closest(".dataTables_wrapper")
                .find(".buttons-collection")
                .first();
            if (exportButton.length) {
                exportButton.trigger("click");
            }
        }
    }

    toggleColumnVisibility() {
        if (this.dt) {
            this.dt.button(".buttons-colvis").trigger("click");
        }
    }

    setFilter(field, value) {
        this.filters[field] = value;
        this.refresh(); // Uses DataTables built-in loading
    }

    clearFilters() {
        this.filters = {};
        if (this.container) {
            this.container
                .querySelectorAll(".dt-filter-input")
                .forEach((input) => {
                    input.value = "";
                });
        }
        this.refresh(); // Uses DataTables built-in loading
    }

    deleteItem(id) {
        if (!confirm("Are you sure you want to delete this item?")) return;

        $.ajax({
            url: this.config.deleteUrl.replace(":id", id),
            type: "DELETE",
            data: {
                _token: this.getCsrfToken(),
                _method: "DELETE",
            },
            success: () => {
                this.refresh(); // Uses DataTables built-in loading
                this.showToast("Item deleted successfully", "success");
            },
            error: (xhr) => {
                console.error("Delete error:", xhr);
                this.showToast("Error deleting item", "error");
            },
        });
    }

    renderField(field, data, row) {
        if (window.dtHelpers && window.dtHelpers.applyCustomRenderers) {
            const customRender = window.dtHelpers.applyCustomRenderers(
                this.id,
                field,
                data,
                row
            );
            if (customRender) return customRender;
        }

        if (data == null || data === "")
            return '<span class="text-muted">-</span>';

        const renderers = {
            status: () => {
                if (
                    data === true ||
                    data === 1 ||
                    data === "1" ||
                    data === "active"
                ) {
                    return '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Active</span>';
                } else if (
                    data === false ||
                    data === 0 ||
                    data === "0" ||
                    data === "inactive"
                ) {
                    return '<span class="badge bg-danger"><i class="fas fa-times me-1"></i>Inactive</span>';
                }
                return `<span class="badge bg-secondary">${data}</span>`;
            },
            created_at: () =>
                `<div class="text-nowrap">${new Date(
                    data
                ).toLocaleDateString()}</div>`,
            updated_at: () =>
                `<div class="text-nowrap">${new Date(
                    data
                ).toLocaleDateString()}</div>`,
            default: () => {
                const text = String(data);
                return text.length > 50
                    ? `<span title="${text}">${text.substring(0, 50)}...</span>`
                    : text;
            },
        };

        return (renderers[field] || renderers.default)();
    }

    renderActions(row) {
        const viewUrl = this.config.showUrl?.replace(":id", row.id);
        const editUrl = this.config.editUrl?.replace(":id", row.id);
        const deleteUrl = this.config.deleteUrl?.replace(":id", row.id);

        return `
            <div class="btn-group btn-group-sm">
                ${
                    viewUrl
                        ? `<a href="${viewUrl}" class="btn btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>`
                        : ""
                }
                ${
                    editUrl
                        ? `<a href="${editUrl}" class="btn btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>`
                        : ""
                }
                ${
                    deleteUrl
                        ? `<a href="${deleteUrl}" class="btn btn-outline-danger delete"
                    data-id="${row.id}"
                     title="Delete"><i class="fas fa-trash"></i></a>`
                        : ""
                }
            </div>
        `;
    }

    formatHeader(field) {
        return field
            .replace(/_/g, " ")
            .replace(/([A-Z])/g, " $1")
            .replace(/^./, (str) => str.toUpperCase())
            .trim();
    }

    getCsrfToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.content : "";
    }

    showToast(message, type = "info") {
        const toast = document.createElement("div");
        toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        toast.style.cssText =
            "top: 20px; right: 20px; z-index: 9999; min-width: 300px;";
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas ${
                    type === "success"
                        ? "fa-check-circle"
                        : "fa-exclamation-circle"
                } me-2"></i>
                <div>${message}</div>
                <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 4000);
    }
}

// Initialize when DOM is ready
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        window.dtManager = new DataTablesManager();
    });
} else {
    window.dtManager = new DataTablesManager();
}

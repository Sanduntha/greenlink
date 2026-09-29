<?php
include "include/header.php";
include "include/topnavbar.php";
?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3">
                        <h1 class="page-header-title font-weight-light">
                            <div class="page-header-icon"><i class="fas fa-truck"></i></div>
                            <span>Goods Received Note</span>
                        </h1>
                    </div>
                </div>
            </div>

            <div class="container-fluid mt-2 p-0 p-2">
                <!-- Form Card -->
                <div class="card mb-3">
                    <div class="card-header bg-light py-2">
                        <h6 class="font-weight-bold text-dark mb-0" id="formTitle">CREATE NEW GRN</h6>
                    </div>
                    <div class="card-body p-3">
                        <form id="grnForm" enctype="multipart/form-data" novalidate>
                            <!-- GRN HEADER SECTION -->
                            <div class="form-section mb-4">
                                <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2">
                                    <i class="fas fa-file-alt mr-2"></i>GRN HEADER
                                </h6>

                                <div class="form-row">
                                    <div class="col-md-3 mb-2">
                                        <label class="small font-weight-bold">GRN Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control form-control-sm" id="grn_date"
                                            name="grn_date" value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="small font-weight-bold">GRN Time <span
                                                class="text-danger">*</span></label>
                                        <input type="time" class="form-control form-control-sm" id="grn_time"
                                            name="grn_time" value="<?php echo date('H:i'); ?>" required>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="small font-weight-bold">GRN No</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control form-control-sm" id="grn_no"
                                                name="grn_no"
                                                value="<?php echo isset($next_grn_number) ? $next_grn_number : ''; ?>"
                                                readonly style="background-color: #f8f9fa;">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    id="btnRefreshGrnNo" title="Refresh GRN Number">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">Auto-generated</small>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <label class="small font-weight-bold">GRN Type <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm" id="grn_type" name="grn_type"
                                            required>
                                            <option value="">Select Type</option>
                                            <option value="company">Company</option>
                                            <option value="individual">Individual</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-4 mb-2">
                                        <label class="small font-weight-bold">Supervisor Name <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm" id="supervisor_id"
                                            name="supervisor_id" required>
                                            <option value="">Select Supervisor</option>
                                            <?php foreach ($supervisors as $supervisor): ?>
                                                <option value="<?php echo $supervisor['idtbl_employee']; ?>">
                                                    <?php echo htmlspecialchars($supervisor['fullname']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- SUPPLIER DETAILS SECTION -->
                            <div class="form-section mb-4">
                                <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2">
                                    <i class="fas fa-user-tie mr-2"></i>SUPPLIER / CUSTOMER DETAILS
                                </h6>

                                <div class="form-row">
                                    <div class="col-md-4 mb-2">
                                        <label class="small font-weight-bold">Supplier Name <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm" id="supplier_id" name="supplier_id"
                                            required>
                                            <option value="">Select Supplier</option>
                                            <?php foreach ($suppliers as $supplier): ?>
                                                <option value="<?php echo $supplier['idtbl_supplier']; ?>">
                                                    <?php echo htmlspecialchars($supplier['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="small font-weight-bold">Contact No</label>
                                        <input type="text" class="form-control form-control-sm" id="contact_no"
                                            name="contact_no" readonly>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-4 mb-2">
                                        <label class="small font-weight-bold">Vehicle No</label>
                                        <input type="text" class="form-control form-control-sm" id="vehicle_no"
                                            name="vehicle_no" placeholder="e.g. WP-AB-1234">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="small font-weight-bold">Driver Name</label>
                                        <input type="text" class="form-control form-control-sm" id="driver_name"
                                            name="driver_name" placeholder="e.g. Perera">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="small font-weight-bold">Gate Pass No</label>
                                        <input type="text" class="form-control form-control-sm" id="gatepass_no"
                                            name="gatepass_no" placeholder="e.g. GP-9974">
                                    </div>
                                </div>
                            </div>

                            <!-- SITE & WAREHOUSE SECTION -->
                            <div class="form-section mb-4">
                                <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2">
                                    <i class="fas fa-warehouse mr-2"></i>SITE & WAREHOUSE
                                </h6>

                                <div class="form-row">
                                    <div class="col-md-4 mb-2">
                                        <label class="small font-weight-bold">Site / Warehouse <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm" id="site_location"
                                            name="site_location" required>
                                            <option value="">Select Location</option>
                                            <?php foreach ($site_locations as $location): ?>
                                                <option value="<?php echo $location['idtbl_location']; ?>">
                                                    <?php echo htmlspecialchars($location['location_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="small font-weight-bold">Zone <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm" id="warehouse" name="warehouse"
                                            required>
                                            <option value="">Select Site first...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="small font-weight-bold">Upload Document/Photo</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="document_file"
                                                name="document_file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                            <label class="custom-file-label" for="document_file">Choose file</label>
                                        </div>
                                        <small class="form-text text-muted">Max size: 2MB | Allowed: JPG, PNG, PDF,
                                            DOC</small>
                                    </div>
                                </div>
                            </div>

                            <!-- CAPACITY INFO PANEL -->
                            <div id="capacity_info_panel" style="display:none;" class="mb-4">
                                <div id="capacity_alert" class="alert mb-0 py-2 px-3">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                                        <div>
                                            <strong><i class="fas fa-warehouse mr-1"></i> Zone Capacity:</strong>
                                            <span id="cap_zone"></span> &nbsp;|&nbsp;
                                            <strong>Site:</strong> <span id="cap_site"></span>
                                        </div>
                                        <div class="text-right">
                                            <span>Used: <strong id="cap_used"></strong> kg</span> &nbsp;/&nbsp;
                                            <span>Max: <strong id="cap_max"></strong> kg</span> &nbsp;|&nbsp;
                                            <span>Available: <strong id="cap_available"></strong> kg</span>
                                        </div>
                                    </div>
                                    <div class="progress mt-2" style="height: 8px;">
                                        <div id="cap_progress" class="progress-bar" role="progressbar" style="width:0%">
                                        </div>
                                    </div>
                                    <div class="text-right mt-1">
                                        <small id="cap_percent_label"></small>
                                    </div>
                                </div>
                            </div>

                            <!-- GRN SOURCE SECTION -->
                            <div class="form-section mb-4">
                                <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2">
                                    <i class="fas fa-source mr-2"></i>GRN SOURCE
                                </h6>

                                <div class="form-row">
                                    <div class="col-md-4 mb-2">
                                        <label class="small font-weight-bold">GRN Source <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm" id="grn_source" name="grn_source"
                                            required>
                                            <option value="">Select Source</option>
                                            <option value="po">With Purchase Order</option>
                                            <option value="no_po">No Purchase Order</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2" id="po_section" style="display: none;">
                                        <label class="small font-weight-bold">Purchase Order No</label>
                                        <select class="form-control form-control-sm" id="purchase_order"
                                            name="purchase_order">
                                            <option value="">Select PO</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="small font-weight-bold">Invoice No</label>
                                        <input type="text" class="form-control form-control-sm" id="invoice_no"
                                            name="invoice_no" placeholder="e.g. INV-5520">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="small font-weight-bold">Delivery No</label>
                                        <input type="text" class="form-control form-control-sm" id="delivery_no"
                                            name="delivery_no" placeholder="e.g. DN-001">
                                    </div>
                                </div>
                            </div>

                            <!-- ITEMS SECTION -->
                            <div class="form-section mb-4">
                                <h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2">
                                    <i class="fas fa-boxes mr-2"></i>ITEMS
                                </h6>

                                <!-- PO Items Table (Visible when source is PO) -->
                                <div id="po_items_section" style="display: none;">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm" id="poItemsTable">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Item Code</th>
                                                    <th class="text-center">Material</th>
                                                    <th class="text-center">UOM</th>
                                                    <th class="text-center">PO Qty</th>
                                                    <th class="text-center">Already Received</th>
                                                    <th class="text-center">Received Qty</th>
                                                    <th class="text-center">Accepted Qty</th>
                                                    <th class="text-center">Rejected Qty</th>
                                                    <th class="text-center">Unit Price</th>
                                                    <th class="text-center">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody id="po_items_body">
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="10" class="text-right font-weight-bold">Grand Total:
                                                    </td>
                                                    <td class="text-right font-weight-bold" id="po_total">0.00</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <!-- Manual Items Section (Visible when source is No PO) -->
                                <div id="manual_items_section" style="display: none;">
                                    <div class="border p-3 bg-light mb-3">
                                        <h6 class="font-weight-bold text-danger mb-3">
                                            <i class="fas fa-plus-circle mr-2"></i>MANUAL ITEM ENTRY
                                        </h6>

                                        <div class="form-row">
                                            <div class="col-md-3 mb-2">
                                                <label class="small font-weight-bold">Item</label>
                                                <select class="form-control form-control-sm" id="manual_item">
                                                    <option value="">Select Item</option>
                                                    <?php foreach ($materials as $material): ?>
                                                        <option value="<?php echo $material['idtbl_row_material']; ?>"
                                                            data-code="<?php echo $material['material_code']; ?>"
                                                            data-name="<?php echo htmlspecialchars($material['material_name']); ?>"
                                                            data-unit="<?php echo $material['measure_type']; ?>">
                                                            <?php echo htmlspecialchars($material['material_name']); ?>
                                                            (<?php echo $material['material_code']; ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <label class="small font-weight-bold">Unit</label>
                                                <input type="text" class="form-control form-control-sm" id="manual_unit"
                                                    readonly>
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <label class="small font-weight-bold">Quantity</label>
                                                <input type="number" class="form-control form-control-sm"
                                                    id="manual_qty" min="0" step="0.01">
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <label class="small font-weight-bold">Unit Price</label>
                                                <input type="number" class="form-control form-control-sm"
                                                    id="manual_price" min="0" step="0.01">
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <label class="small font-weight-bold">Total</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="manual_total" readonly>
                                            </div>
                                            <div class="col-md-1 mb-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                    id="add_manual_item">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm" id="manualItemsTable">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Item Code</th>
                                                    <th>Material Name</th>
                                                    <th>UOM</th>
                                                    <th class="text-right">Quantity</th>
                                                    <th class="text-right">Unit Price</th>
                                                    <th class="text-right">Total</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="manual_items_body">
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="6" class="text-right font-weight-bold">Grand Total:
                                                    </td>
                                                    <td class="text-right font-weight-bold" id="manual_total_sum">0.00
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- REMARKS & TOTAL SECTION -->
                            <div class="form-section mb-4">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label class="small font-weight-bold">Remarks</label>
                                        <textarea class="form-control form-control-sm" id="remarks" name="remarks"
                                            rows="2"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-right mt-4">
                                            <h4 class="font-weight-bold">Total Amount: <span
                                                    id="display_total">0.00</span></h4>
                                            <input type="hidden" id="grn_total" name="grn_total" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ACTION BUTTONS -->
                            <div class="form-section">
                                <div class="text-right">
                                    <button type="button" class="btn btn-secondary btn-sm mr-2" id="btnCancel">
                                        <i class="fas fa-times mr-1"></i> Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-sm" id="btnSaveGrn">
                                        <i class="fas fa-save mr-1"></i> Save GRN
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- GRN List Card -->
                <div class="card">
                    <div class="card-header bg-light py-2">
                        <h6 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-list mr-2"></i>GRN LIST
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm nowrap display" id="grnDataTable"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>GRN No</th>
                                        <th>Date & Time</th>
                                        <th>Supplier</th>
                                        <th class="text-right">Total</th>
                                        <th>Type</th>
                                        <th>Source</th>
                                        <th>Batch No</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>

<!-- View GRN Modal -->
<div class="modal fade" id="viewGrnModal" tabindex="-1" role="dialog" aria-labelledby="viewGrnModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewGrnModalLabel">GRN Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="viewGrnContent">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>

<script>
    $(document).ready(function () {
        var addcheck = '<?php echo $addcheck; ?>';
        var editcheck = '<?php echo $editcheck; ?>';
        var statuscheck = '<?php echo $statuscheck; ?>';
        var deletecheck = '<?php echo $deletecheck; ?>';

        let manualItems = [];
        let poItems = [];
        let currentGrnId = null;
        let isEditMode = false;

        // Initialize DataTable
        let grnDataTable = $('#grnDataTable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            responsive: true,
            dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
            buttons: [
                { extend: 'csv', className: 'btn btn-success btn-sm', title: 'GRN List', text: '<i class="fas fa-file-csv mr-2"></i> CSV' },
                { extend: 'pdf', className: 'btn btn-danger btn-sm', title: 'GRN List', text: '<i class="fas fa-file-pdf mr-2"></i> PDF' },
                { extend: 'print', className: 'btn btn-primary btn-sm', title: 'GRN List', text: '<i class="fas fa-print mr-2"></i> Print' }
            ],
            ajax: {
                url: "<?php echo base_url() ?>scripts/goodreceivelist.php",
                type: 'POST'
            },
            order: [[1, 'desc']],
            columnDefs: [
                { targets: 0, data: null, orderable: false, searchable: false, render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                { targets: 1, data: 'grn_no' },
                { targets: 2, data: 'datetime' },
                { targets: 3, data: 'supplier_name' },
                { targets: 4, data: 'total', className: 'text-right', render: function (data) { return parseFloat(data || 0).toFixed(2); } },
                { targets: 5, data: 'grntype', render: function (data) { return data === 'company' ? '<span class="badge badge-info">Company</span>' : '<span class="badge badge-primary">Individual</span>'; } },
                { targets: 6, data: 'grn_source', render: function (data) { return data === 'po' ? '<span class="badge badge-success">PO</span>' : '<span class="badge badge-warning">No PO</span>'; } },
                { targets: 7, data: 'batch_number' },
                { targets: 8, data: 'approval_status', render: function (data) { return data === 'approved' ? '<span class="badge badge-success">Approved</span>' : '<span class="badge badge-warning">Pending</span>'; } },
                {
                    targets: 9,
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function (data, type, full) {
                        var button = '';
                        button += '<button class="btn btn-primary btn-sm mr-1 btn-view" data-id="' + full.idtbl_grn + '" title="View"><i class="fas fa-eye"></i></button>';
                        if (full.approval_status === 'approved') {
                            return button;
                        }
                        if (typeof editcheck !== 'undefined' && editcheck == 1) {
                            button += '<button class="btn btn-primary btn-sm mr-1 btn-edit" data-id="' + full.idtbl_grn + '" title="Edit"><i class="fas fa-pen"></i></button>';
                        }
                        if (typeof statuscheck !== 'undefined' && statuscheck == 1) {
                            button += '<button class="btn btn-success btn-sm mr-1 btn-approve" data-id="' + full.idtbl_grn + '" title="Approve"><i class="fas fa-check"></i></button>';
                        }
                        if (typeof deletecheck !== 'undefined' && deletecheck == 1) {
                            button += '<button class="btn btn-danger btn-sm btn-delete" data-id="' + full.idtbl_grn + '" title="Delete"><i class="fas fa-trash-alt"></i></button>';
                        }
                        return button;
                    }
                }
            ]
        });

        // Load GRN number
        if (!isEditMode) {
            loadGrnNumber();
        }

        // Refresh GRN number
        $('#btnRefreshGrnNo').click(function () {
            loadGrnNumber();
        });

        // File input change
        $('#document_file').on('change', function () {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });

        // Supplier change
        $('#supplier_id').change(function () {
            let supplierId = $(this).val();
            if (supplierId) {
                $.ajax({
                    url: '<?php echo base_url(); ?>Goodreceive/GetSupplierContact',
                    type: 'POST',
                    data: { supplier_id: supplierId },
                    dataType: 'json',
                    success: function (response) {
                        $('#contact_no').val(response.contact_no);
                        loadSupplierPos(supplierId);
                    }
                });
            }
        });

        // GRN Source change
        $('#grn_source').change(function () {
            clearAllErrors();
            let source = $(this).val();
            if (source === 'po') {
                $('#po_section').show();
                $('#po_items_section').show();
                $('#manual_items_section').hide();
            } else if (source === 'no_po') {
                $('#po_section').hide();
                $('#po_items_section').hide();
                $('#manual_items_section').show();
            } else {
                $('#po_section').hide();
                $('#po_items_section').hide();
                $('#manual_items_section').hide();
            }
            calculateTotal();
        });

        // Site location change - load zones
        $('#site_location').change(function () {
            var siteId = $(this).val();
            $('#warehouse').html('<option value="">Select Zone</option>').prop('disabled', true);
            $('#capacity_info_panel').hide();

            if (!siteId) return;

            $.ajax({
                url: '<?php echo base_url(); ?>Goodreceive/GetZonesBySite',
                type: 'POST',
                data: { site_id: siteId },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success' && response.zones.length > 0) {
                        var options = '<option value="">Select Zone</option>';
                        $.each(response.zones, function (i, zone) {
                            options += `<option value="${zone.idtbl_rack}">${zone.rack_number} ${zone.max_weight ? '(' + zone.max_weight + ' kg max)' : ''}</option>`;
                        });
                        $('#warehouse').html(options).prop('disabled', false);
                    } else {
                        $('#warehouse').html('<option value="">No zones available</option>');
                    }
                }
            });
        });

        // Manual item change - FIXED: removed price auto-fill
        $('#manual_item').on('change', function () {
            let selected = $(this).find('option:selected');
            let unit = selected.data('unit');
            $('#manual_unit').val(unit || '');
            $('#manual_price').val('');
            $('#manual_qty').val('');
            $('#manual_total').val('');
        });

        // GRN Type change
        $('#grn_type').change(function () {
            let type = $(this).val();
            if (type === 'individual' && $('#grn_source').val() === 'no_po') {
                $('#manual_items_section').show();
            }
        });

        // Purchase Order change - UPDATED with pending quantity check
        $('#purchase_order').change(function () {
            let poId = $(this).val();
            if (poId) {
                loadPoItems(poId);
            }
        });

        // Calculate manual item total
        $('#manual_qty, #manual_price').on('input', function () {
            let qty = parseFloat($('#manual_qty').val()) || 0;
            let price = parseFloat($('#manual_price').val()) || 0;
            let total = qty * price;
            $('#manual_total').val(total.toFixed(2));
        });

        // Add manual item
        $('#add_manual_item').click(function () {
            clearAllErrors();
            let itemSelect = $('#manual_item');
            let itemId = itemSelect.val();

            if (!itemId) {
                alert('Please select an item');
                return;
            }

            let qty = parseFloat($('#manual_qty').val()) || 0;
            if (qty <= 0) {
                alert('Please enter a valid quantity');
                return;
            }

            let price = parseFloat($('#manual_price').val()) || 0;
            if (price <= 0) {
                alert('Please enter a valid unit price');
                return;
            }

            let itemCode = itemSelect.find(':selected').data('code');
            let itemName = itemSelect.find(':selected').data('name');
            let unit = itemSelect.find(':selected').data('unit');
            let total = qty * price;

            let item = {
                material_id: itemId,
                item_code: itemCode,
                material_name: itemName,
                unit_of_measure: unit,
                qty: qty,
                unit_price: price,
                total: total,
                batch_number: generateBatchNumber()
            };

            manualItems.push(item);
            updateManualItemsTable();
            $('.no-items-error').remove();

            $('#manual_item').val('');
            $('#manual_unit').val('');
            $('#manual_qty').val('');
            $('#manual_price').val('');
            $('#manual_total').val('');
        });

        // Remove manual item
        $(document).on('click', '.remove-manual-item', function () {
            let index = $(this).data('index');
            manualItems.splice(index, 1);
            updateManualItemsTable();
        });

        // PO items quantity changes
        $(document).on('input', '.received-qty, .accepted-qty, .rejected-qty', function () {
            let row = $(this).closest('tr');
            let index = row.data('index');

            let poQty = parseFloat(row.find('.po-qty').text()) || 0;
            let alreadyReceived = parseFloat(row.find('.already-received').data('received')) || 0;
            let pendingQty = poQty - alreadyReceived;
            let unitPrice = parseFloat(row.find('.unit-price').text()) || 0;

            let receivedQty = parseFloat(row.find('.received-qty').val()) || 0;
            let acceptedQty = parseFloat(row.find('.accepted-qty').val()) || 0;
            let rejectedQty = parseFloat(row.find('.rejected-qty').val()) || 0;

            // Validate against pending quantity
            if (receivedQty > pendingQty) {
                alert('Received quantity cannot exceed pending quantity (' + pendingQty.toFixed(2) + ')');
                row.find('.received-qty').val(pendingQty);
                receivedQty = pendingQty;
            }

            if (acceptedQty > pendingQty) {
                alert('Accepted quantity cannot exceed pending quantity (' + pendingQty.toFixed(2) + ')');
                row.find('.accepted-qty').val(pendingQty);
                acceptedQty = pendingQty;
            }

            if (rejectedQty > pendingQty) {
                alert('Rejected quantity cannot exceed pending quantity (' + pendingQty.toFixed(2) + ')');
                row.find('.rejected-qty').val(pendingQty);
                rejectedQty = pendingQty;
            }

            if (acceptedQty > receivedQty) {
                alert('Accepted quantity cannot exceed received quantity');
                row.find('.accepted-qty').val(receivedQty);
                acceptedQty = receivedQty;
            }

            if (rejectedQty > receivedQty) {
                alert('Rejected quantity cannot exceed received quantity');
                row.find('.rejected-qty').val(receivedQty);
                rejectedQty = receivedQty;
            }

            if ((acceptedQty + rejectedQty) > receivedQty) {
                alert('Accepted + Rejected quantity cannot exceed received quantity');
                row.find('.accepted-qty').val(0);
                row.find('.rejected-qty').val(0);
                acceptedQty = 0;
                rejectedQty = 0;
            }

            let total = acceptedQty * unitPrice;
            row.find('.item-total').text(total.toFixed(2));

            if (poItems[index]) {
                poItems[index].received_qty = receivedQty;
                poItems[index].accepted_qty = acceptedQty;
                poItems[index].rejected_qty = rejectedQty;
                poItems[index].total = total;
            }

            calculateTotal();
        });

        // Form submit with capacity check
        $('#grnForm').submit(function (e) {
            e.preventDefault();

            var site = $('#site_location').val();
            var wh = $('#warehouse').val();

            if (!site || !wh) {
                if (!validateForm()) return;
                submitGrnForm();
                return;
            }

            $.ajax({
                url: '<?php echo base_url(); ?>Goodreceive/GetCapacityInfo',
                type: 'POST',
                data: { site_location: site, warehouse: wh },
                dataType: 'json',
                success: function (res) {
                    if (res.status !== 'success') {
                        if (!validateForm()) return;
                        submitGrnForm();
                        return;
                    }

                    var d = res.data;
                    var incomingQty = 0;
                    var source = $('#grn_source').val();

                    if (source === 'po') {
                        $.each(poItems, function (i, item) {
                            incomingQty += parseFloat(item.accepted_qty) || 0;
                        });
                    } else {
                        $.each(manualItems, function (i, item) {
                            incomingQty += parseFloat(item.qty) || 0;
                        });
                    }

                    var available = parseFloat(d.available_qty);
                    var max = parseFloat(d.max_capacity);
                    var used = parseFloat(d.used_qty);
                    var afterSave = used + incomingQty;
                    var pct = max > 0 ? Math.min(Math.round((afterSave / max) * 100), 100) : 0;

                    if (max > 0 && incomingQty > available) {
                        showCapacityBlockPanel(d, incomingQty, available, afterSave, pct);
                        return;
                    }

                    if (!validateForm()) return;
                    submitGrnForm();
                },
                error: function () {
                    if (!validateForm()) return;
                    submitGrnForm();
                }
            });
        });

        // Cancel button
        $('#btnCancel').click(function () {
            if (isEditMode) {
                isEditMode = false;
                currentGrnId = null;
                $('#btnSaveGrn').html('<i class="fas fa-save mr-1"></i> Save GRN');
                $('#formTitle').text('CREATE NEW GRN');
            }
            resetForm();
        });

        // View GRN
        $('#grnDataTable').on('click', '.btn-view', function () {
            let grnId = $(this).data('id');
            viewGrn(grnId);
        });

        // Edit GRN
        $('#grnDataTable').on('click', '.btn-edit', function () {
            let grnId = $(this).data('id');
            if (confirm('Are you sure you want to edit this record?')) {
                loadGrnForEdit(grnId);
            }
        });

        // Approve GRN
        $('#grnDataTable').on('click', '.btn-approve', function () {
            let grnId = $(this).data('id');
            if (!confirm('Are you sure you want to approve this GRN?')) {
                return;
            }

            $.ajax({
                url: '<?php echo base_url(); ?>Goodreceive/ApproveGrn',
                type: 'POST',
                data: { grn_id: grnId },
                dataType: 'json',
                success: function (response) {
                    showNotification(response.message, response.type);
                    if (response.status == 1) {
                        grnDataTable.ajax.reload();
                    }
                }
            });
        });

        // Delete GRN
        $('#grnDataTable').on('click', '.btn-delete', function () {
            let grnId = $(this).data('id');
            if (!confirm('Are you sure you want to delete this GRN?')) {
                return;
            }

            $.ajax({
                url: '<?php echo base_url(); ?>Goodreceive/DeleteGrn',
                type: 'POST',
                data: { grn_id: grnId },
                dataType: 'json',
                success: function (response) {
                    showNotification(response.message, response.type);
                    if (response.status == 1) {
                        grnDataTable.ajax.reload();
                    }
                }
            });
        });

        // Capacity check when site or warehouse changes
        $('#site_location, #warehouse').on('change', function () {
            checkCapacity();
        });

        // ============ FUNCTIONS ============

        function loadGrnNumber() {
            if (isEditMode) return;

            $.ajax({
                url: '<?php echo base_url(); ?>Goodreceive/GetGrnNumber',
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    $('#grn_no').val(response.grn_no);
                },
                error: function () {
                    showNotification('Error loading GRN number', 'danger');
                }
            });
        }

        function generateBatchNumber() {
            return 'BATCH-' + Date.now() + '-' + Math.floor(Math.random() * 1000);
        }

        function loadSupplierPos(supplierId) {
            $.ajax({
                url: '<?php echo base_url(); ?>Goodreceive/GetSupplierPos',
                type: 'POST',
                data: { supplier_id: supplierId },
                dataType: 'json',
                success: function (response) {
                    let options = '<option value="">Select PO</option>';
                    $.each(response, function (index, po) {
                        options += `<option value="${po.idtbl_porder}">PO-${po.idtbl_porder} (${po.podate})</option>`;
                    });
                    $('#purchase_order').html(options);
                }
            });
        }

        // UPDATED: Load PO Items with pending quantity check
        function loadPoItems(poId) {
            $.ajax({
                url: '<?php echo base_url(); ?>Goodreceive/GetPoDetailsForGrn',
                type: 'POST',
                data: { po_id: poId },
                dataType: 'json',
                success: function (response) {
                    // Show summary of already received quantities
                    let alreadyReceived = false;
                    let alertMessage = '';
                    let fullyReceived = true;

                    $.each(response, function (index, item) {
                        let poQty = parseFloat(item.po_qty || item.qty || 0);
                        let receivedQty = parseFloat(item.received_qty || 0);
                        let pendingQty = poQty - receivedQty;

                        if (receivedQty > 0) {
                            alreadyReceived = true;
                            alertMessage += `\n• ${item.material_name}: ${receivedQty.toFixed(2)} ${item.unit_of_measure} already received, ${pendingQty.toFixed(2)} pending`;
                        }

                        if (pendingQty > 0) {
                            fullyReceived = false;
                        }
                    });

                    // Check if PO is already fully received
                    if (fullyReceived && response.length > 0) {
                        alert('This PO is already fully received. All items have been received.');
                        $('#purchase_order').val('');
                        return;
                    }

                    // Show alert if some items already received
                    if (alreadyReceived) {
                        alert('Some items in this PO have already been received:' + alertMessage + '\n\nOnly pending quantities will be loaded.');
                    }

                    // Filter out items with zero pending quantity
                    let availableItems = response.filter(item => {
                        let poQty = parseFloat(item.po_qty || item.qty || 0);
                        let receivedQty = parseFloat(item.received_qty || 0);
                        return (poQty - receivedQty) > 0;
                    });

                    if (availableItems.length === 0) {
                        alert('No pending items available in this PO. All items have been fully received.');
                        $('#purchase_order').val('');
                        return;
                    }

                    // Map items with pending quantity as the received/accepted quantity
                    poItems = availableItems.map(item => {
                        let poQty = parseFloat(item.po_qty || item.qty || 0);
                        let receivedQty = parseFloat(item.received_qty || 0);
                        let pendingQty = poQty - receivedQty;
                        let unitPrice = parseFloat(item.unitprice || 0);

                        return {
                            ...item,
                            received_qty: pendingQty,
                            accepted_qty: pendingQty,
                            rejected_qty: 0,
                            total: pendingQty * unitPrice,
                            pending_qty: pendingQty,
                            already_received: receivedQty
                        };
                    });

                    updatePoItemsTable();
                    calculateTotal();
                    $('.no-items-error').remove();

                    // Show summary notification
                    if (alreadyReceived) {
                        showNotification('PO loaded with pending quantities. Items already received have been excluded.', 'info');
                    }
                },
                error: function () {
                    showNotification('Error loading PO details', 'danger');
                }
            });
        }

        // UPDATED: PO Items Table with Already Received column
        function updatePoItemsTable() {
            let html = '';
            let total = 0;

            $.each(poItems, function (index, item) {
                let itemTotal = parseFloat(item.total) || 0;
                let unitPrice = parseFloat(item.unitprice) || 0;
                let poQty = parseFloat(item.po_qty || item.qty || 0);
                let alreadyReceived = parseFloat(item.already_received || 0);
                let pendingQty = parseFloat(item.pending_qty || poQty - alreadyReceived || 0);
                let receivedQty = parseFloat(item.received_qty) || pendingQty;
                let acceptedQty = parseFloat(item.accepted_qty) || pendingQty;
                let rejectedQty = parseFloat(item.rejected_qty) || 0;

                total += itemTotal;

                html += `
<tr data-index="${index}">
    <td class="text-center">${index + 1}</td>
    <td class="text-center">${item.item_code}</td>
    <td class="text-center">${item.material_name}</td>
    <td class="text-center">${item.unit_of_measure}</td>
    <td class="text-center po-qty">${poQty.toFixed(2)}</td>
    <td class="text-center already-received" data-received="${alreadyReceived}">
        <span class="badge badge-info">${alreadyReceived.toFixed(2)}</span>
        <span class="text-muted d-block" style="font-size:10px;">Pending: ${pendingQty.toFixed(2)}</span>
    </td>
    <td class="text-center">
        <input type="number" class="form-control form-control-sm received-qty text-center mx-auto"
               value="${receivedQty}" min="0" max="${pendingQty}" step="0.01" style="width: 80px;">
        <small class="text-muted" style="font-size:9px;">Max: ${pendingQty.toFixed(2)}</small>
    </td>
    <td class="text-center">
        <input type="number" class="form-control form-control-sm accepted-qty text-center mx-auto"
               value="${acceptedQty}" min="0" max="${pendingQty}" step="0.01" style="width: 80px;">
        <small class="text-muted" style="font-size:9px;">Max: ${pendingQty.toFixed(2)}</small>
    </td>
    <td class="text-center">
        <input type="number" class="form-control form-control-sm rejected-qty text-center mx-auto"
               value="${rejectedQty}" min="0" max="${pendingQty}" step="0.01" style="width: 80px;">
        <small class="text-muted" style="font-size:9px;">Max: ${pendingQty.toFixed(2)}</small>
    </td>
    <td class="text-center unit-price">${unitPrice.toFixed(2)}</td>
    <td class="text-center item-total">${itemTotal.toFixed(2)}</td>
</tr>`;
            });

            $('#po_items_body').html(html);
            $('#po_total').text(total.toFixed(2));
        }

        function updateManualItemsTable() {
            let html = '';
            let total = 0;

            $.each(manualItems, function (index, item) {
                let qty = parseFloat(item.qty) || 0;
                let unitPrice = parseFloat(item.unit_price) || 0;
                let itemTotal = parseFloat(item.total) || (qty * unitPrice);

                total += itemTotal;

                html += `
<tr>
    <td>${index + 1}</td>
    <td>${item.item_code}</td>
    <td>${item.material_name}</td>
    <td>${item.unit_of_measure}</td>
    <td class="text-right">${qty.toFixed(2)}</td>
    <td class="text-right">${unitPrice.toFixed(2)}</td>
    <td class="text-right">${itemTotal.toFixed(2)}</td>
    <td class="text-center">
        <button type="button" class="btn btn-sm btn-danger remove-manual-item" data-index="${index}">
            <i class="fas fa-trash"></i>
        </button>
    </td>
</tr>`;
            });

            $('#manual_items_body').html(html);
            $('#manual_total_sum').text(total.toFixed(2));
            $('#display_total').text(total.toFixed(2));
            $('#grn_total').val(total.toFixed(2));
        }

        function calculateTotal() {
            let total = 0;

            if ($('#grn_source').val() === 'po') {
                $.each(poItems, function (index, item) {
                    total += parseFloat(item.total) || 0;
                });
            } else {
                $.each(manualItems, function (index, item) {
                    total += parseFloat(item.total) || 0;
                });
            }

            $('#display_total').text(total.toFixed(2));
            $('#grn_total').val(total);
        }

        function loadGrnForEdit(grnId) {
            $.ajax({
                url: '<?php echo base_url(); ?>Goodreceive/GetGrnForEdit',
                type: 'POST',
                data: { grn_id: grnId },
                dataType: 'json',
                success: function (response) {
                    if (response) {
                        isEditMode = true;
                        currentGrnId = grnId;

                        $('#formTitle').text('EDIT GRN');
                        $('#btnSaveGrn').html('<i class="fas fa-save mr-1"></i> Update GRN');

                        fillFormWithData(response);
                        $('html, body').animate({ scrollTop: $(".page-header").offset().top }, 500);
                    }
                }
            });
        }

        function fillFormWithData(grnData) {
            $('#grn_no').val(grnData.grn_no);
            if (grnData.date) {
                let formattedDate = grnData.date.split(' ')[0];
                $('#grn_date').val(formattedDate);
            }
            $('#grn_time').val(grnData.grn_time);
            $('#grn_type').val(grnData.grntype);
            $('#supervisor_id').val(grnData.supervisor_id).trigger('change');
            $('#supplier_id').val(grnData.supplier_id).trigger('change');

            setTimeout(() => {
                $('#contact_no').val(grnData.contact_no);
            }, 500);

            $('#vehicle_no').val(grnData.vehicle_no);
            $('#driver_name').val(grnData.driver_name);
            $('#gatepass_no').val(grnData.gatepass_no);
            $('#site_location').val(grnData.site_location);
            $('#warehouse').val(grnData.warehouse);

            if (grnData.document_path) {
                $('#document_file').next('.custom-file-label').html(grnData.document_path.split('/').pop());
            }

            $('#grn_source').val(grnData.grn_source).trigger('change');

            if (grnData.grn_source === 'po' && grnData.ponumber) {
                setTimeout(() => {
                    $('#purchase_order').val(grnData.ponumber);
                    loadPoItems(grnData.ponumber);
                }, 1000);
            }

            $('#invoice_no').val(grnData.invoicenum);
            $('#delivery_no').val(grnData.dispatchnum);
            $('#remarks').val(grnData.remarks);

            if (grnData.grn_source === 'po' && grnData.details) {
                setTimeout(() => {
                    poItems = grnData.details.map(item => ({
                        material_id: item.tbl_row_material_idtbl_row_material,
                        item_code: item.item_code || item.material_code,
                        material_name: item.material_name,
                        unit_of_measure: item.unit_of_measure || item.measure_type,
                        unitprice: item.unitprice,
                        po_qty: item.po_qty || item.qty,
                        received_qty: item.received_qty,
                        accepted_qty: item.accepted_qty,
                        rejected_qty: item.rejected_qty,
                        total: item.total,
                        batch_number: item.batch_number,
                        pending_qty: (item.po_qty || item.qty) - (item.received_qty || 0),
                        already_received: item.received_qty || 0
                    }));
                    updatePoItemsTable();
                    calculateTotal();
                }, 1500);
            } else if (grnData.grn_source === 'no_po' && grnData.details) {
                manualItems = grnData.details.map(item => ({
                    material_id: item.tbl_row_material_idtbl_row_material,
                    item_code: item.item_code || item.material_code,
                    material_name: item.material_name,
                    unit_of_measure: item.unit_of_measure || item.measure_type,
                    qty: item.qty,
                    unit_price: item.unitprice,
                    total: item.total,
                    batch_number: item.batch_number
                }));
                updateManualItemsTable();
                calculateTotal();
            }

            setTimeout(() => {
                $('#grn_total').val(grnData.total);
                $('#display_total').text(parseFloat(grnData.total).toFixed(2));
            }, 2000);
        }

        function submitGrnForm() {
            let formData = new FormData($('#grnForm')[0]);

            if ($('#grn_source').val() === 'po') {
                formData.append('po_details', JSON.stringify(poItems));
            } else {
                formData.append('manual_items', JSON.stringify(manualItems));
            }

            let btn = $('#btnSaveGrn');
            let originalText = btn.html();
            btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');
            btn.prop('disabled', true);

            let url = isEditMode ?
                '<?php echo base_url(); ?>Goodreceive/UpdateGrn/' + currentGrnId :
                '<?php echo base_url(); ?>Goodreceive/SaveNewGrn';

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (response) {
                    if (response.status == 1) {
                        showNotification(response.message, response.type);
                        if (isEditMode) {
                            isEditMode = false;
                            currentGrnId = null;
                            $('#btnSaveGrn').html('<i class="fas fa-save mr-1"></i> Save GRN');
                            $('#formTitle').text('CREATE NEW GRN');
                            resetForm();
                        } else {
                            resetForm();
                        }
                        grnDataTable.ajax.reload();
                    } else {
                        showNotification(response.message, response.type);
                    }
                },
                error: function () {
                    showNotification('Error saving GRN. Please try again.', 'danger');
                },
                complete: function () {
                    btn.html(originalText);
                    btn.prop('disabled', false);
                }
            });
        }

        // UPDATED: Validate Form with pending quantity checks
        function validateForm() {
            clearAllErrors();

            let headerFields = [
                { id: 'grn_date', label: 'GRN Date' },
                { id: 'grn_time', label: 'GRN Time' },
                { id: 'grn_type', label: 'GRN Type' },
                { id: 'supervisor_id', label: 'Supervisor Name' },
                { id: 'supplier_id', label: 'Supplier Name' },
                { id: 'site_location', label: 'Site / Location' },
                { id: 'warehouse', label: 'Zone' },
                { id: 'grn_source', label: 'GRN Source' },
            ];

            if (!validateFields(headerFields)) {
                return false;
            }

            let source = $('#grn_source').val();

            if (source === 'po') {
                if (!$('#purchase_order').val()) {
                    showFieldError('purchase_order', 'Purchase Order No is required');
                    $('#purchase_order').focus();
                    return false;
                }

                if (poItems.length === 0) {
                    $('.no-items-error').remove();
                    $('#po_items_section .table-responsive').before(
                        '<div class="alert alert-danger no-items-error mt-3"><i class="fas fa-exclamation-triangle mr-2"></i>No pending items available in this PO!</div>'
                    );
                    return false;
                }

                // Check if any items have pending quantities
                let hasPendingItems = false;
                let totalAccepted = 0;
                let validationErrors = [];

                $.each(poItems, function (index, item) {
                    let acceptedQty = parseFloat(item.accepted_qty) || 0;
                    let pendingQty = parseFloat(item.pending_qty) || 0;
                    let poQty = parseFloat(item.po_qty || item.qty || 0);
                    let alreadyReceived = parseFloat(item.already_received || 0);

                    if (acceptedQty > 0) {
                        hasPendingItems = true;
                        totalAccepted += acceptedQty;
                    }

                    // Check if accepted qty exceeds pending qty
                    if (acceptedQty > pendingQty) {
                        validationErrors.push('Accepted quantity (' + acceptedQty + ') exceeds pending quantity (' + pendingQty + ') for ' + item.material_name);
                    }

                    // Check if accepted qty exceeds PO qty
                    if (acceptedQty > poQty) {
                        validationErrors.push('Accepted quantity (' + acceptedQty + ') exceeds PO quantity (' + poQty + ') for ' + item.material_name);
                    }
                });

                if (validationErrors.length > 0) {
                    $('.no-items-error').remove();
                    let errorHtml = '<div class="alert alert-danger no-items-error mt-3"><i class="fas fa-exclamation-triangle mr-2"></i><ul>';
                    $.each(validationErrors, function (i, err) {
                        errorHtml += '<li>' + err + '</li>';
                    });
                    errorHtml += '</ul></div>';
                    $('#po_items_section .table-responsive').before(errorHtml);
                    return false;
                }

                if (!hasPendingItems || totalAccepted <= 0) {
                    $('.no-items-error').remove();
                    $('#po_items_section .table-responsive').before(
                        '<div class="alert alert-danger no-items-error mt-3"><i class="fas fa-exclamation-triangle mr-2"></i>Please enter accepted quantity for at least one item!</div>'
                    );
                    return false;
                }
            }

            if (source === 'no_po' && manualItems.length === 0) {
                $('.no-items-error').remove();
                $('#manual_items_section .table-responsive').before(
                    '<div class="alert alert-danger no-items-error mt-3"><i class="fas fa-exclamation-triangle mr-2"></i>Please add at least one item!</div>'
                );
                return false;
            }

            return true;
        }

        function resetForm() {
            $('#grnForm')[0].reset();
            $('#grn_date').val('<?php echo date('Y-m-d'); ?>');
            $('#grn_time').val('<?php echo date('H:i'); ?>');
            $('#contact_no').val('');
            $('#purchase_order').html('<option value="">Select PO</option>');
            $('#po_section').hide();
            $('#po_items_section').hide();
            $('#manual_items_section').hide();
            $('#document_file').next('.custom-file-label').html('Choose file');
            $('#capacity_info_panel').hide();

            manualItems = [];
            poItems = [];

            $('#po_items_body').html('');
            $('#manual_items_body').html('');
            $('#display_total').text('0.00');
            $('#grn_total').val('0');

            if (isEditMode) {
                isEditMode = false;
                currentGrnId = null;
                $('#btnSaveGrn').html('<i class="fas fa-save mr-1"></i> Save GRN');
                $('#formTitle').text('CREATE NEW GRN');
            }

            loadGrnNumber();
            clearAllErrors();
        }

        function viewGrn(grnId) {
            $.ajax({
                url: '<?php echo base_url(); ?>Goodreceive/GetGrnDetails',
                type: 'POST',
                data: { grn_id: grnId },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        let grn = response.data;
                        let html = `
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm table-bordered">
                            <tr><th class="bg-light" width="40%">GRN No:</th><td>${grn.grn_no}</td></tr>
                            <tr><th class="bg-light">Date:</th><td>${grn.date} ${grn.grn_time}</td></tr>
                            <tr><th class="bg-light">GRN Type:</th><td>${grn.grntype === 'company' ? 'Company' : 'Individual'}</td></tr>
                            <tr><th class="bg-light">Supervisor:</th><td>${grn.supervisor_name || 'N/A'}</td></tr>
                            <tr><th class="bg-light">Supplier:</th><td>${grn.supplier_name || 'N/A'}</td></tr>
                            <tr><th class="bg-light">Contact No:</th><td>${grn.contact_no || 'N/A'}</td></tr>
                            <tr><th class="bg-light">Vehicle No:</th><td>${grn.vehicle_no || 'N/A'}</td></tr>
                            <tr><th class="bg-light">Driver Name:</th><td>${grn.driver_name || 'N/A'}</td></tr>
                            <tr><th class="bg-light">Gate Pass No:</th><td>${grn.gatepass_no || 'N/A'}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-bordered">
                            <tr><th class="bg-light" width="40%">Site Location:</th><td>${grn.site_name || 'N/A'}</td></tr>
                            <tr><th class="bg-light">Zone:</th><td>${grn.warehouse_name || 'N/A'}</td></tr>
                            <tr><th class="bg-light">GRN Source:</th><td>${grn.grn_source === 'po' ? 'With PO' : 'No PO'}</td></tr>
                            ${grn.ponumber ? `<tr><th class="bg-light">PO No:</th><td>PO-${grn.ponumber}</td></tr>` : ''}
                            <tr><th class="bg-light">Invoice No:</th><td>${grn.invoicenum}</td></tr>
                            <tr><th class="bg-light">Delivery No:</th><td>${grn.dispatchnum}</td></tr>
                            <tr><th class="bg-light">Batch No:</th><td>${grn.batch_number}</td></tr>
                            <tr><th class="bg-light">Status:</th><td>${grn.approval_status === 'approved' ? '<span class="badge badge-success">Approved</span>' : '<span class="badge badge-warning">Pending</span>'}</td></tr>
                            <tr><th class="bg-light">Total Amount:</th><td class="font-weight-bold">${parseFloat(grn.total).toFixed(2)}</td></tr>
                            ${grn.document_path ? `<tr><th class="bg-light">Document:</th><td><a href="${grn.document_path}" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-download"></i> Download</a></td></tr>` : ''}
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="font-weight-bold">Items:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Item Code</th>
                                        <th>Material Name</th>
                                        <th>UOM</th>
                                        <th class="text-right">PO Qty</th>
                                        <th class="text-right">Already Received</th>
                                        <th class="text-right">Received Qty</th>
                                        <th class="text-right">Accepted Qty</th>
                                        <th class="text-right">Rejected Qty</th>
                                        <th class="text-right">Unit Price</th>
                                        <th class="text-right">Total</th>
                                        <th>Batch No</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                        $.each(grn.details || [], function (index, item) {
                            html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.item_code || 'N/A'}</td>
                        <td>${item.material_name || 'N/A'}</td>
                        <td>${item.unit_of_measure || item.measure_type || 'N/A'}</td>
                        <td class="text-right">${item.po_qty || item.qty}</td>
                        <td class="text-right">${item.received_qty ? (parseFloat(item.po_qty || item.qty) - parseFloat(item.received_qty)).toFixed(2) : '0.00'}</td>
                        <td class="text-right">${item.received_qty || item.qty}</td>
                        <td class="text-right">${item.accepted_qty || item.qty}</td>
                        <td class="text-right">${item.rejected_qty || 0}</td>
                        <td class="text-right">${parseFloat(item.unitprice).toFixed(2)}</td>
                        <td class="text-right">${parseFloat(item.total).toFixed(2)}</td>
                        <td>${item.batch_number}</td>
                    </tr>`;
                        });

                        html += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                ${grn.remarks ? `<div class="row mt-3"><div class="col-12"><h6 class="font-weight-bold">Remarks:</h6><div class="border p-2 bg-light">${grn.remarks}</div></div></div>` : ''}`;

                        $('#viewGrnContent').html(html);
                        $('#viewGrnModal').modal('show');
                    }
                }
            });
        }

        function showCapacityBlockPanel(d, incomingQty, available, afterSave, pct) {
            $('#capacity_block_modal').remove();

            var html = `
    <div id="capacity_block_modal" class="card border-danger mb-3 mt-2">
        <div class="card-header bg-danger text-white py-2">
            <strong><i class="fas fa-exclamation-triangle mr-2"></i>Cannot Save — Zone Capacity Exceeded</strong>
        </div>
        <div class="card-body py-3">
            <div class="row text-center mb-3">
                <div class="col-md-3">
                    <div class="border rounded p-2">
                        <div class="text-muted small">Zone</div>
                        <div class="font-weight-bold">${d.zone_name}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-2">
                        <div class="text-muted small">Max Capacity</div>
                        <div class="font-weight-bold">${parseFloat(d.max_capacity).toFixed(2)} kg</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-2">
                        <div class="text-muted small">Currently Used</div>
                        <div class="font-weight-bold">${parseFloat(d.used_qty).toFixed(2)} kg</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-2 border-success">
                        <div class="text-muted small">Available</div>
                        <div class="font-weight-bold text-success">${parseFloat(available).toFixed(2)} kg</div>
                    </div>
                </div>
            </div>
            <div class="row text-center mb-3">
                <div class="col-md-6 offset-md-3">
                    <div class="border rounded p-2 border-danger">
                        <div class="text-muted small">Incoming Qty (this GRN)</div>
                        <div class="font-weight-bold text-danger">${parseFloat(incomingQty).toFixed(2)} kg</div>
                    </div>
                </div>
            </div>
            <div class="progress mb-1" style="height: 14px; border-radius: 6px;">
                <div class="progress-bar bg-secondary" style="width:${Math.min(Math.round((parseFloat(d.used_qty) / parseFloat(d.max_capacity)) * 100), 100)}%" title="Current Stock"></div>
                <div class="progress-bar bg-danger progress-bar-striped" style="width:${Math.min(Math.round((parseFloat(incomingQty) / parseFloat(d.max_capacity)) * 100), 100)}%" title="Incoming GRN"></div>
            </div>
            <div class="d-flex justify-content-between mb-3">
                <small><span class="badge badge-secondary">Current Stock</span></small>
                <small class="text-danger font-weight-bold">After save: ${parseFloat(afterSave).toFixed(2)} kg / ${parseFloat(d.max_capacity).toFixed(2)} kg (${pct}%)</small>
                <small><span class="badge badge-danger">Incoming GRN</span></small>
            </div>
            <div class="alert alert-danger mb-0 py-2 text-center">
                <i class="fas fa-ban mr-1"></i>
                Incoming quantity <strong>${parseFloat(incomingQty).toFixed(2)} kg</strong> 
                exceeds available space of <strong>${parseFloat(available).toFixed(2)} kg</strong>. 
                Please reduce quantities or select a different zone.
                <br>
                <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="$('#capacity_block_modal').remove();">
                    <i class="fas fa-times mr-1"></i> Dismiss
                </button>
            </div>
        </div>
    </div>`;

            $('.form-section').last().before(html);
            $('html, body').animate({ scrollTop: $('#capacity_block_modal').offset().top - 100 }, 500);
        }

        function checkCapacity() {
            var site = $('#site_location').val();
            var wh = $('#warehouse').val();

            if (!site || !wh) {
                $('#capacity_info_panel').hide();
                return;
            }

            $.ajax({
                url: '<?php echo base_url(); ?>Goodreceive/GetCapacityInfo',
                type: 'POST',
                data: { site_location: site, warehouse: wh },
                dataType: 'json',
                success: function (res) {
                    if (res.status !== 'success') return;

                    var d = res.data;
                    var pct = parseFloat(d.percent_used);

                    $('#cap_site').text(d.location_name);
                    $('#cap_zone').text(d.zone_name);
                    $('#cap_used').text(parseFloat(d.used_qty).toFixed(2));
                    $('#cap_max').text(parseFloat(d.max_capacity).toFixed(2));
                    $('#cap_available').text(parseFloat(d.available_qty).toFixed(2));
                    $('#cap_percent_label').text(pct + '% used');

                    var $bar = $('#cap_progress');
                    var $alert = $('#capacity_alert');

                    $bar.css('width', Math.min(pct, 100) + '%');

                    $alert.removeClass('alert-success alert-warning alert-danger');
                    $bar.removeClass('bg-success bg-warning bg-danger');

                    if (d.max_capacity == 0) {
                        $alert.addClass('alert-warning');
                        $bar.addClass('bg-warning');
                    } else if (!d.has_capacity) {
                        $alert.addClass('alert-danger');
                        $bar.addClass('bg-danger');
                    } else if (pct >= 80) {
                        $alert.addClass('alert-warning');
                        $bar.addClass('bg-warning');
                    } else {
                        $alert.addClass('alert-success');
                        $bar.addClass('bg-success');
                    }

                    $('#capacity_info_panel').show();

                    if (d.max_capacity > 0 && !d.has_capacity) {
                        $('#capacity_info_panel').find('.alert').find('.cap-status-msg').remove();
                        $('#capacity_alert .progress').after(
                            '<div class="cap-status-msg mt-1 text-danger font-weight-bold">' +
                            '<i class="fas fa-exclamation-triangle mr-1"></i>This zone is FULL. No available capacity.' +
                            '</div>'
                        );
                    } else {
                        $('.cap-status-msg').remove();
                    }
                }
            });
        }

        function clearAllErrors() {
            $('.form-control, select, input[type="file"]').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('.no-items-error').remove();
        }

        function showFieldError(fieldId, message) {
            let field = $('#' + fieldId);
            field.addClass('is-invalid');
            if (field.next('.invalid-feedback').length === 0) {
                field.after('<div class="invalid-feedback">' + message + '</div>');
            } else {
                field.next('.invalid-feedback').text(message);
            }
        }

        function validateFields(fieldConfigs) {
            let hasError = false;
            let firstErrorField = null;

            fieldConfigs.forEach(function (conf) {
                let field = $('#' + conf.id);
                let value = field.val() ? field.val().trim() : '';
                let errorMsg = null;

                if (value === '') {
                    errorMsg = conf.label + ' is required';
                } else if (conf.numeric) {
                    let num = parseFloat(value);
                    if (isNaN(num) || num <= 0) {
                        errorMsg = conf.label + ' must be greater than zero';
                    }
                }

                if (errorMsg) {
                    showFieldError(conf.id, errorMsg);
                    if (!firstErrorField) {
                        firstErrorField = field;
                    }
                    hasError = true;
                }
            });

            if (hasError && firstErrorField) {
                firstErrorField.focus();
                $('html, body').animate({ scrollTop: firstErrorField.offset().top - 150 }, 500);
            }

            return !hasError;
        }

        function showNotification(message, type) {
            let alertClass = '';
            switch (type) {
                case 'success': alertClass = 'alert-success'; break;
                case 'danger': alertClass = 'alert-danger'; break;
                case 'warning': alertClass = 'alert-warning'; break;
                default: alertClass = 'alert-info';
            }

            let alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>`;

            $('body').append(alertHtml);
            setTimeout(() => { $('.alert').alert('close'); }, 5000);
        }
    });
</script>

<?php include "include/footer.php"; ?>
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
                            <div class="page-header-icon"><i class="fas fa-exchange-alt"></i></div>
                            <span>Shipment Planning</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <!-- Create Shipment Card -->
                <div class="card mb-3" id="createShipmentCard">
                    <div class="card-header bg-white">
                        <h6 class="m-0 font-weight-bold">New Shipment Plan</h6>
                    </div>
                    <div class="card-body p-0 p-2">
                        <form id="shipmentForm" autocomplete="off" novalidate>
                            <!-- SHIPMENT BASIC INFO -->
                            <h6 class="font-weight-bold text-primary border-bottom pb-2 mb-3">Shipment Basic Info</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Shipment ID *</label>
                                        <input type="text" class="form-control form-control-sm" name="shipment_id"
                                            id="shipment_id" readonly required>
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Supervisor</label>

                                        <select class="form-control form-control-sm" id="supervisor" name="supervisor"
                                            required>
                                            <option value="">Select Supervisor</option>
                                            <?php foreach ($supervisors as $supervisor): ?>
                                                <option value="<?php echo $supervisor['idtbl_employee']; ?>">
                                                    <?php echo htmlspecialchars($supervisor['fullname']); ?>

                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Shipment Date *</label>
                                        <input type="date" class="form-control form-control-sm" name="shipment_date"
                                            id="shipment_date" value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label class="small font-weight-bold">Shipment Status *</label>
                                        <select class="form-control form-control-sm" name="shipment_status" 
                                            id="shipment_status" required>
                                            <option value="">Select Status</option>
                                            <option value="Draft">Draft</option>
                                            <option value="Ready">Ready</option>
                                            <option value="Dispatched">Dispatched</option>
                                        </select>
                                    </div> -->
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Loading Location</label>


                                        <select class="form-control form-control-sm" id="loading_location"
                                            name="loading_location" required>
                                            <option value="">Select Location</option>
                                            <?php foreach ($locations as $location): ?>
                                                <option value="<?php echo $location['locationid']; ?>">
                                                    <?php echo htmlspecialchars($location['locationname']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Shipment Time *</label>
                                        <input type="time" class="form-control form-control-sm" name="shipment_time"
                                            id="shipment_time" value="<?php echo date('H:i'); ?>" required>
                                    </div>

                                </div>
                            </div>

                            <!-- EXPORT / CONSIGNEE -->
                            <h6 class="font-weight-bold text-primary border-bottom pb-2 mt-4 mb-3">Export / Consignee
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Exporter</label>
                                        <input type="text" class="form-control form-control-sm" name="exporter"
                                            id="exporter">
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Country</label>
                                        <input type="text" class="form-control form-control-sm" name="country"
                                            id="country">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Buyer</label>

                                        <select class="form-control form-control-sm" id="buyer" name="buyer" required>
                                            <option value="">Select Buyer</option>
                                            <?php foreach ($customers as $customer): ?>
                                                <option value="<?php echo $customer['idtbl_customer']; ?>">
                                                    <?php echo htmlspecialchars($customer['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Handling Company</label>
                                        <input type="text" class="form-control form-control-sm" name="handling_company"
                                            id="handling_company">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Consignee</label>
                                        <input type="text" class="form-control form-control-sm" name="consignee"
                                            id="consignee">
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Freight Company</label>
                                        <input type="text" class="form-control form-control-sm" name="freight_company"
                                            id="freight_company">
                                    </div>
                                </div>
                            </div>

                            <!-- TRANSPORT / DRIVER -->
                            <h6 class="font-weight-bold text-primary border-bottom pb-2 mt-4 mb-3">Transport / Driver
                                Details</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Vehicle / Lorry No</label>
                                        <input type="text" class="form-control form-control-sm" name="vehicle_no"
                                            id="vehicle_no">
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Transporter</label>
                                        <input type="text" class="form-control form-control-sm" name="transporter"
                                            id="transporter">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Driver Name</label>
                                        <input type="text" class="form-control form-control-sm" name="driver_name"
                                            id="driver_name">
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Driver NIC</label>
                                        <input type="text" class="form-control form-control-sm" name="driver_nic"
                                            id="driver_nic">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Driver TP</label>
                                        <input type="text" class="form-control form-control-sm" name="driver_tp"
                                            id="driver_tp">
                                    </div>
                                </div>
                            </div>

                            <!-- CONTAINER DETAILS -->
                            <h6 class="font-weight-bold text-primary border-bottom pb-2 mt-4 mb-3">Container Details
                            </h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Container ID</label>
                                        <input type="text" class="form-control form-control-sm" name="container_id"
                                            id="container_id">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Container Number</label>
                                        <input type="text" class="form-control form-control-sm" name="container_number"
                                            id="container_number">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Seal No</label>
                                        <input type="text" class="form-control form-control-sm" name="seal_no"
                                            id="seal_no">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Container Size</label>
                                        <select class="form-control form-control-sm" name="container_size"
                                            id="container_size">
                                            <option value="">Select Size</option>
                                            <option value="20FT">20FT</option>
                                            <option value="40FT">40FT</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- CUSDEC / INVOICE -->
                            <h6 class="font-weight-bold text-primary border-bottom pb-2 mt-4 mb-3">Cusdec / Invoice</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Cusdec Number</label>
                                        <input type="text" class="form-control form-control-sm" name="cusdec_number"
                                            id="cusdec_number">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Cusdec Date</label>
                                        <input type="date" class="form-control form-control-sm" name="cusdec_date"
                                            id="cusdec_date">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Net Weight (kg)</label>
                                        <input type="number" step="0.01" class="form-control form-control-sm"
                                            name="cusdec_net_weight" id="cusdec_net_weight">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Invoice Number</label>
                                        <input type="text" class="form-control form-control-sm" name="invoice_number"
                                            id="invoice_number" readonly>
                                        <small class="text-muted">Auto-generated</small>
                                    </div>
                                </div>
                            </div>

                            <!-- DISPATCH ITEMS -->
                            <h6 class="font-weight-bold text-primary border-bottom pb-2 mt-4 mb-3">Dispatch Items</h6>
                            <div class="row align-items-end">
                                <div class="col-md-3">
                                    <label class="small font-weight-bold">Select Item *</label>
                                    <select class="form-control form-control-sm select2" id="stock_item" required>
                                        <option value="">Select Item</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="small font-weight-bold">Quantity *</label>
                                    <input type="number" step="0.01" class="form-control form-control-sm" id="quantity"
                                        min="0.01" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="small font-weight-bold">Unit *</label>
                                    <select class="form-control form-control-sm" id="quantity_unit" required>
                                        <option value="KG">KG</option>
                                        <option value="Nos">Nos</option>
                                        <option value="MT">MT</option>
                                        <option value="LBS">LBS</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="small font-weight-bold">Material Type</label>
                                    <input type="text" class="form-control form-control-sm" id="material_type">
                                </div>
                                <div class="col-md-2">
                                    <label class="small font-weight-bold">Weight (kg) *</label>
                                    <input type="number" step="0.01" class="form-control form-control-sm"
                                        id="item_weight" min="0.01" required>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-success btn-sm w-100" onclick="addItem()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- ITEMS TABLE -->
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered table-sm" id="itemsTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Item Name</th>
                                            <th>Qty</th>
                                            <th>Unit</th>
                                            <th>Material Type</th>
                                            <th>Weight (kg)</th>
                                            <th>Batch No</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="noDataRow">
                                            <td colspan="8" class="text-center text-muted">No items added</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- ACTION BUTTONS -->
                            <input type="hidden" id="shipmentId" name="shipmentId">
                            <div class="text-right mt-4">
                                <button type="submit" class="btn btn-primary btn-sm px-5" id="submitBtn">
                                    <i class="fas fa-save"></i> Save Shipment
                                </button>
                                <button type="button" class="btn btn-info btn-sm px-5" id="updateBtn"
                                    style="display:none;">
                                    <i class="fas fa-save"></i> Update Shipment
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm px-5" id="cancelBtn"
                                    style="display:none;">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Shipment Records Table -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h6 class="m-0 font-weight-bold">Shipment Records</h6>
                    </div>
                    <div class="card-body p-0 p-2">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm nowrap" id="shipmentRecordsTable"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Shipment ID</th>
                                        <th>Date</th>
                                        <th>Exporter</th>
                                        <th>Consignee</th>
                                        <th>Total Items</th>
                                        <th>Total Weight</th>
                                        <th>Shipment status</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- View Details Modal -->
                <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTitle">Shipment Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="modalBody">
                                <!-- Details will be loaded here -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function () {


        var addcheck = '<?php echo $addcheck; ?>';
        var editcheck = '<?php echo $editcheck; ?>';
        var statuscheck = '<?php echo $statuscheck; ?>';
        var deletecheck = '<?php echo $deletecheck; ?>';

        var editMode = false;
        var editingRowId = null;
        var itemsCounter = 0;
        var itemsTable = [];

        // Initialize Select2
        $('.select2').select2({
            width: '100%'
        });


        function clearAllErrors() {
            $('.form-control, select').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('#itemsError').hide().text('');
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

        function showItemsError(message) {
            $('#itemsError').text(message).show();
        }

        function validateHeaderFields() {
            clearAllErrors();

            let hasError = false;
            let firstErrorField = null;

            let requiredFields = [
                { id: 'shipment_date', label: 'Shipment Date' },
                { id: 'shipment_time', label: 'Shipment Time' },
                { id: 'supervisor', label: 'Supervisor' },
                { id: 'loading_location', label: 'Loading Location' },
                { id: 'buyer', label: 'Buyer' },
                { id: 'exporter', label: 'Exporter' },
                { id: 'country', label: 'Country' },
                { id: 'handling_company', label: 'Handling Company' },
                { id: 'consignee', label: 'Consignee' },
                { id: 'freight_company', label: 'Freight Company' },
                { id: 'vehicle_no', label: 'Vehicle / Lorry No' },
                { id: 'transporter', label: 'Transporter' },
                { id: 'driver_name', label: 'Driver Name' },
                { id: 'driver_nic', label: 'Driver NIC' },
                { id: 'driver_tp', label: 'Driver TP' },
                { id: 'container_id', label: 'Container ID' },
                { id: 'container_number', label: 'Container Number' },
                { id: 'seal_no', label: 'Seal No' },
                { id: 'container_size', label: 'Container Size' },

                // Cusdec / Invoice
                { id: 'cusdec_number', label: 'Cusdec Number' },
                { id: 'cusdec_date', label: 'Cusdec Date' },
                { id: 'cusdec_net_weight', label: 'Net Weight (kg)' },
                { id: 'invoice_number', label: 'Invoice Number' }
            ];

            requiredFields.forEach(function (conf) {
                let field = $('#' + conf.id);
                let value = field.val() ? field.val().trim() : '';

                if (value === '') {
                    showFieldError(conf.id, conf.label + ' is required');
                    if (!firstErrorField) firstErrorField = field;
                    hasError = true;
                }
            });

            if (hasError && firstErrorField) {
                firstErrorField.focus();
                $('html, body').animate({
                    scrollTop: firstErrorField.offset().top - 150
                }, 500);
            }

            return !hasError;
        }

        function validateItemFields() {
            clearAllErrors();

            let hasError = false;
            let firstErrorField = null;

            let requiredFields = [
                { id: 'stock_item', label: 'Select Item' },
                { id: 'quantity', label: 'Quantity', numeric: true },
                { id: 'item_weight', label: 'Weight (kg)', numeric: true }
            ];

            requiredFields.forEach(function (conf) {
                let field = $('#' + conf.id);
                let value = field.val() ? field.val().trim() : '';
                let errorMsg = null;

                if (value === '') {
                    errorMsg = conf.label + ' is required';
                } else if (conf.numeric) {
                    let num = parseFloat(value);
                    if (isNaN(num) || num <= 0) {
                        errorMsg = conf.label + ' must be greater than 0';
                    }
                }

                if (errorMsg) {
                    showFieldError(conf.id, errorMsg);
                    if (!firstErrorField) firstErrorField = field;
                    hasError = true;
                }
            });

            if (hasError && firstErrorField) {
                firstErrorField.focus();
                $('html, body').animate({
                    scrollTop: firstErrorField.offset().top - 150
                }, 500);
            }

            return !hasError;
        }

        function validateItemsTable() {
            let hasError = false;

            if (itemsTable.length === 0) {
                showItemsError('Please add at least one item');
                hasError = true;
            }

            if (hasError) {
                $('html, body').animate({
                    scrollTop: $('#itemsTable').offset().top - 150
                }, 500);
            }

            return !hasError;
        }

        // Clear errors on input/change (including Select2)
        $(document).on('input change', '.form-control, select', function () {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
            $('#itemsError').hide();
        });

        $('#stock_item, #quantity_unit').on('select2:select select2:clear', function () {
            $(this).removeClass('is-invalid');
            $(this).nextAll('.invalid-feedback').remove();
        });

        // Load next shipment ID
        function loadNextShipmentId() {
            $.ajax({
                url: '<?php echo base_url(); ?>Shipmentplaning/GetNextShipmentId',
                type: 'POST',
                success: function (response) {
                    try {
                        var data = JSON.parse(response);
                        $('#shipment_id').val(data.shipment_id);
                    } catch (e) {
                        console.error('Error loading shipment ID:', e);
                    }
                }
            });
        }

        function loadNextInvoiceNo() {
            $.ajax({
                url: '<?php echo base_url(); ?>Shipmentplaning/GetNextInvoiceNo',
                type: 'POST',
                success: function (response) {
                    try {
                        var data = JSON.parse(response);
                        $('#invoice_number').val(data.invoice_number);
                    } catch (e) {
                        console.error('Error loading invoice number:', e);
                    }
                }
            });
        }

        // Load available stock
        function loadAvailableStock() {
            $.ajax({
                url: '<?php echo base_url(); ?>Shipmentplaning/GetAvailableStock',
                type: 'POST',
                success: function (response) {
                    try {
                        var stock = JSON.parse(response);
                        var options = '<option value="">Select Item</option>';
                        $.each(stock, function (index, item) {
                            options += '<option value="' + index + '" ' +
                                'data-material="' + item.material_name + '" ' +
                                'data-available="' + item.available_qty + '" ' +
                                'data-batch="' + (item.batchnumber || '') + '" ' +
                                'data-stockid="' + item.idtbl_stock + '">' +
                                item.material_name + ' - Available: ' + item.available_qty + ' ' +
                                (item.batchnumber ? '(Batch: ' + item.batchnumber + ')' : '') +
                                '</option>';
                        });
                        $('#stock_item').html(options).trigger('change');
                    } catch (e) {
                        console.error('Error loading stock:', e);
                    }
                }
            });
        }

        // Load shipment records
        function loadShipmentRecords() {
            $.ajax({
                url: '<?php echo base_url(); ?>Shipmentplaning/GetShipmentRecords',
                type: 'POST',
                success: function (response) {
                    try {
                        var shipments = JSON.parse(response);
                        var table = $('#shipmentRecordsTable').DataTable({
                            destroy: true,
                            data: shipments,
                            columns: [
                                {
                                    data: null,
                                    render: function (data, type, row, meta) {
                                        return meta.row + 1;
                                    }
                                },
                                { data: 'shipment_id' },
                                {
                                    data: 'created_at',
                                    render: function (data) {
                                        return formatDate(data);
                                    }
                                },
                                { data: 'exporter' },
                                { data: 'consignee' },
                                { data: 'total_items', className: 'text-right' },
                                {
                                    data: 'total_weight',
                                    className: 'text-right',
                                    render: function (data) {
                                        return parseFloat(data).toFixed(2) + ' kg';
                                    }
                                },
                                {
                                    data: 'shipment_status', // NEW COLUMN
                                    render: function (data) {
                                        if (data == 'Draft') return '<span class="badge badge-secondary">Draft</span>';
                                        if (data == 'Ready') return '<span class="badge badge-warning">Ready</span>';
                                        if (data == 'Dispatched') return '<span class="badge badge-success">Dispatched</span>';
                                        return '<span class="badge badge-light">' + (data || 'N/A') + '</span>';
                                    }
                                },
                                {
                                    data: 'status',
                                    render: function (data) {
                                        if (data == 1) return '<span class="badge badge-success">Approved</span>';
                                        if (data == 2) return '<span class="badge badge-warning">Pending</span>';
                                        if (data == 3) return '<span class="badge badge-danger">Rejected</span>';
                                        return '<span class="badge badge-secondary">Deleted</span>';
                                    }
                                },
                                { data: 'created_by_name' },
                                {
                                    data: null,
                                    className: 'text-center',
                                    render: function (data, type, row) {
                                        var actions = '';

                                        actions += '<button class="btn btn-primary btn-sm btnView mr-1" data-id="' + row.idtbl_shipmentplaning + '" title="View Details"><i class="fas fa-eye"></i></button>';
                                        if (row.status == 1 && row.shipment_status == 'Ready') {
                                            actions += '<button class="btn btn-warning btn-sm btnDispatch mr-1" data-id="' + row.idtbl_shipmentplaning + '" title="Mark as Dispatched"><i class="fas fa-truck"></i></button>';
                                        }
                                        // if (row.status == 1 && row.shipment_status == 'Dispatched') {
                                        //     actions += '<button class="btn btn-info btn-sm btnGenerateInvoice mr-1" data-id="' + row.idtbl_shipmentplaning + '" title="Generate Invoice"><i class="fas fa-file-invoice"></i></button>';
                                        // }

                                        if (row.status == 2) {
                                            if (editcheck) {
                                                actions += '<button class="btn btn-primary btn-sm btnEdit mr-1" data-id="' + row.idtbl_shipmentplaning + '" title="Edit"><i class="fas fa-pen"></i></button>';
                                            }
                                            if (statuscheck) {
                                                actions += '<button class="btn btn-success btn-sm btnApprove mr-1" data-id="' + row.idtbl_shipmentplaning + '" title="Approve"><i class="fas fa-check"></i></button>';
                                            }
                                            if (deletecheck) {
                                                actions += '<button class="btn btn-danger btn-sm btnDelete mr-1" data-id="' + row.idtbl_shipmentplaning + '" title="Delete"><i class="fas fa-trash-alt"></i></button>';
                                            }
                                        }

                                        // if (row.status == 2 && statuscheck) {
                                        //     actions += '<button class="btn btn-danger btn-sm btnReject" data-id="' + row.idtbl_shipmentplaning + '" title="Reject"><i class="fas fa-times"></i></button>';
                                        // }

                                        return actions;
                                    }
                                }
                            ],
                            order: [[2, 'desc']],
                            responsive: true,
                            pageLength: 25
                        });
                    } catch (e) {
                        console.error('Error loading shipments:', e);
                    }
                }
            });
        }

        // Format date
        function formatDate(dateString) {
            if (!dateString) return '';

            try {
                var date = new Date(dateString);

                if (!isNaN(date.getTime())) {
                    var datePart = date.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });

                    var timeString = date.toTimeString();
                    if (timeString !== '00:00:00 GMT+0530 (India Standard Time)' &&
                        timeString !== '00:00:00 GMT+0000 (Coordinated Universal Time)') {
                        var timePart = date.toLocaleTimeString('en-US', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        return datePart + ' ' + timePart;
                    }

                    return datePart;
                }

                var dateMatch = dateString.match(/\d{4}-\d{2}-\d{2}/);
                if (dateMatch) {
                    var simpleDate = new Date(dateMatch[0]);
                    return simpleDate.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                }

                return dateString;
            } catch (e) {
                return dateString; 
            }
        }


        // Add item to table
        window.addItem = function () {
            if (editingRowId !== null) {
                showItemsError('Finish updating the current item first');
                return;
            }

            if (!validateHeaderFields()) return;
            if (!validateItemFields()) return;
            var stockItem = $('#stock_item option:selected');
            var quantity = parseFloat($('#quantity').val()) || 0;
            var quantityUnit = $('#quantity_unit').val();
            var materialType = $('#material_type').val();
            var weight = parseFloat($('#item_weight').val()) || 0;
            var availableQty = parseFloat(stockItem.data('available')) || 0;
            var batchNo = stockItem.data('batch') || '';
            var stockId = stockItem.data('stockid') || '';

            if (!stockItem.val()) {
                alert('Please select an item');
                return;
            }

            if (quantity <= 0) {
                alert('Please enter a valid quantity');
                return;
            }

            if (weight <= 0) {
                alert('Please enter a valid weight');
                return;
            }

            if (quantity > availableQty) {
                alert('Quantity cannot exceed available quantity (' + availableQty + ')');
                return;
            }

            itemsCounter++;
            var itemData = {
                id: 'item-' + itemsCounter,
                index: itemsCounter,
                item_name: stockItem.text().split(' - ')[0],
                quantity: quantity,
                quantity_unit: quantityUnit,
                material_type: materialType,
                weight: weight,
                batch_no: batchNo,
                stock_id: stockId,
                available_qty: availableQty
            };

            itemsTable.push(itemData);
            updateItemsTable();

            // Reset form
            $('#quantity').val('');
            $('#material_type').val('');
            $('#item_weight').val('');
            $('#stock_item').val('').trigger('change');
        };

        // Update items table display
        function updateItemsTable() {
            var tableBody = $('#itemsTable tbody');
            tableBody.empty();

            if (itemsTable.length === 0) {
                tableBody.html('<tr id="noDataRow"><td colspan="8" class="text-center text-muted">No items added</td></tr>');
                return;
            }

            $.each(itemsTable, function (index, item) {
                var row = '<tr data-id="' + item.id + '">' +
                    '<td>' + (index + 1) + '</td>' +
                    '<td>' + item.item_name + '</td>' +
                    '<td class="text-right">' + parseFloat(item.quantity).toFixed(2) + '</td>' +
                    '<td>' + item.quantity_unit + '</td>' +
                    '<td>' + item.material_type + '</td>' +
                    '<td class="text-right">' + parseFloat(item.weight).toFixed(2) + ' kg</td>' +
                    '<td>' + (item.batch_no || '-') + '</td>' +
                    '<td class="text-center">' +
                    '<button class="btn btn-info btn-sm btnEditItem mr-1" data-id="' + item.id + '" title="Edit Item"><i class="fas fa-edit"></i></button>' +
                    '<button class="btn btn-danger btn-sm btnRemoveItem" data-id="' + item.id + '" title="Remove Item"><i class="fas fa-trash-alt"></i></button>' +
                    '</td>' +
                    '</tr>';
                tableBody.append(row);
            });
        }

        // Edit item
        $(document).on('click', '.btnEditItem', function () {
            var itemId = $(this).data('id');
            var itemIndex = itemsTable.findIndex(item => item.id === itemId);

            if (itemIndex !== -1) {
                var item = itemsTable[itemIndex];

                // Find and select the stock item
                $('#stock_item').val('');
                $('#stock_item option').each(function () {
                    if ($(this).text().includes(item.item_name)) {
                        $(this).prop('selected', true);
                        $('#stock_item').trigger('change');
                    }
                });

                $('#quantity').val(item.quantity);
                $('#quantity_unit').val(item.quantity_unit);
                $('#material_type').val(item.material_type);
                $('#item_weight').val(item.weight);

                editingRowId = itemId;

                // Remove the item from table temporarily
                itemsTable.splice(itemIndex, 1);
                updateItemsTable();
            }
        });

        // Remove item
        $(document).on('click', '.btnRemoveItem', function () {
            var itemId = $(this).data('id');

            if (confirm('Are you sure you want to remove this item?')) {
                var itemIndex = itemsTable.findIndex(item => item.id === itemId);
                if (itemIndex !== -1) {
                    itemsTable.splice(itemIndex, 1);
                    updateItemsTable();
                }
            }
        });

        // Submit shipment form
        $('#shipmentForm').submit(function (e) {
            e.preventDefault();
            if (!validateHeaderFields()) return;
            if (!validateItemsTable()) return;

            if (!confirm('Are you sure you want to ' + (editMode ? 'update' : 'save') + ' this shipment?')) {
                return;
            }

            if (itemsTable.length === 0) {
                alert('Please add at least one item');
                return;
            }

            if (!confirm('Are you sure you want to save this shipment?')) {
                return;
            }

            var formData = $(this).serializeArray();
            var shipmentData = {};

            // Convert form data to object
            $.each(formData, function () {
                shipmentData[this.name] = this.value;
            });

            shipmentData.items = itemsTable;

            $.ajax({
                url: '<?php echo base_url(); ?>Shipmentplaning/ProcessShipment',
                type: 'POST',
                data: shipmentData,
                success: function (response) {
                    try {
                        var result = JSON.parse(response);
                        showNotification(result.message, result.type);

                        if (result.status) {
                            resetForm();
                            loadShipmentRecords();
                            loadNextShipmentId();
                        }
                    } catch (e) {
                        showNotification('Error saving shipment', 'danger');
                    }
                },
                error: function () {
                    showNotification('Error saving shipment', 'danger');
                }
            });
        });

        // View shipment details
        $(document).on('click', '.btnView', function () {
            var shipmentId = $(this).data('id');

            $.ajax({
                url: '<?php echo base_url(); ?>Shipmentplaning/GetShipmentDetails',
                type: 'POST',
                data: { shipment_id: shipmentId },
                success: function (response) {
                    try {
                        var details = JSON.parse(response);
                        var modalContent = '<div class="container-fluid">';

                        if (details.header) {
                            // SHIPMENT BASIC INFO
                            modalContent += '<h6 class="font-weight-bold text-primary border-bottom pb-2 mb-3">Shipment Basic Info</h6>';
                            modalContent += '<div class="row mb-3">';
                            modalContent += '<div class="col-md-4"><strong>Shipment ID:</strong><br>' + details.header.shipment_id + '</div>';
                            modalContent += '<div class="col-md-4"><strong>Shipment Date:</strong><br>' + formatDate(details.header.shipment_date + ' ' + details.header.shipment_time) + '</div>';
                            modalContent += '<div class="col-md-4"><strong>Supervisor:</strong><br>' + (details.header.supervisor_name || details.header.supervisor || '-') + '</div>';
                            modalContent += '</div>';
                            modalContent += '<div class="row mb-3">';
                            modalContent += '<div class="col-md-4"><strong>Loading Location:</strong><br>' + (details.header.loading_location_name || details.header.loading_location || '-') + '</div>';
                            modalContent += '<div class="col-md-4"><strong>Status:</strong><br>';
                            if (details.header.status == 1) modalContent += '<span class="badge badge-success">Approved</span>';
                            else if (details.header.status == 2) modalContent += '<span class="badge badge-warning">Pending</span>';
                            else if (details.header.status == 3) modalContent += '<span class="badge badge-danger">Rejected</span>';
                            else modalContent += '<span class="badge badge-secondary">Deleted</span>';
                            modalContent += '</div>';
                            modalContent += '<div class="col-md-4"><strong>Shipment Status:</strong><br>';
                            if (details.header.shipment_status == 'Draft') modalContent += '<span class="badge badge-secondary">Draft</span>';
                            else if (details.header.shipment_status == 'Ready') modalContent += '<span class="badge badge-warning">Ready</span>';
                            else if (details.header.shipment_status == 'Dispatched') modalContent += '<span class="badge badge-success">Dispatched</span>';
                            else modalContent += '<span class="badge badge-light">' + (details.header.shipment_status || 'N/A') + '</span>';
                            modalContent += '</div>';
                            modalContent += '</div>';

                            // EXPORT / CONSIGNEE
                            modalContent += '<h6 class="font-weight-bold text-primary border-bottom pb-2 mt-4 mb-3">Export / Consignee</h6>';
                            modalContent += '<div class="row mb-3">';
                            modalContent += '<div class="col-md-4"><strong>Exporter:</strong><br>' + (details.header.exporter || '-') + '</div>';
                            modalContent += '<div class="col-md-4"><strong>Buyer:</strong><br>' + (details.header.buyer_name || details.header.buyer || '-') + '</div>';
                            modalContent += '<div class="col-md-4"><strong>Consignee:</strong><br>' + (details.header.consignee || '-') + '</div>';
                            modalContent += '</div>';
                            modalContent += '<div class="row mb-3">';
                            modalContent += '<div class="col-md-4"><strong>Country:</strong><br>' + (details.header.country || '-') + '</div>';
                            modalContent += '<div class="col-md-4"><strong>Handling Company:</strong><br>' + (details.header.handling_company || '-') + '</div>';
                            modalContent += '<div class="col-md-4"><strong>Freight Company:</strong><br>' + (details.header.freight_company || '-') + '</div>';
                            modalContent += '</div>';

                            // TRANSPORT / DRIVER
                            modalContent += '<h6 class="font-weight-bold text-primary border-bottom pb-2 mt-4 mb-3">Transport / Driver Details</h6>';
                            modalContent += '<div class="row mb-3">';
                            modalContent += '<div class="col-md-4"><strong>Vehicle / Lorry No:</strong><br>' + (details.header.vehicle_no || '-') + '</div>';
                            modalContent += '<div class="col-md-4"><strong>Transporter:</strong><br>' + (details.header.transporter || '-') + '</div>';
                            modalContent += '<div class="col-md-4"><strong>Driver Name:</strong><br>' + (details.header.driver_name || '-') + '</div>';
                            modalContent += '</div>';
                            modalContent += '<div class="row mb-3">';
                            modalContent += '<div class="col-md-4"><strong>Driver NIC:</strong><br>' + (details.header.driver_nic || '-') + '</div>';
                            modalContent += '<div class="col-md-4"><strong>Driver TP:</strong><br>' + (details.header.driver_tp || '-') + '</div>';
                            modalContent += '</div>';

                            // CONTAINER DETAILS
                            modalContent += '<h6 class="font-weight-bold text-primary border-bottom pb-2 mt-4 mb-3">Container Details</h6>';
                            modalContent += '<div class="row mb-3">';
                            modalContent += '<div class="col-md-3"><strong>Container ID:</strong><br>' + (details.header.container_id || '-') + '</div>';
                            modalContent += '<div class="col-md-3"><strong>Container Number:</strong><br>' + (details.header.container_number || '-') + '</div>';
                            modalContent += '<div class="col-md-3"><strong>Seal No:</strong><br>' + (details.header.seal_no || '-') + '</div>';
                            modalContent += '<div class="col-md-3"><strong>Container Size:</strong><br>' + (details.header.container_size || '-') + '</div>';
                            modalContent += '</div>';

                            // CUSDEC / INVOICE
                            modalContent += '<h6 class="font-weight-bold text-primary border-bottom pb-2 mt-4 mb-3">Cusdec / Invoice</h6>';
                            modalContent += '<div class="row mb-3">';
                            modalContent += '<div class="col-md-3"><strong>Cusdec Number:</strong><br>' + (details.header.cusdec_number || '-') + '</div>';
                            modalContent += '<div class="col-md-3"><strong>Cusdec Date:</strong><br>' + (details.header.cusdec_date ? formatDate(details.header.cusdec_date) : '-') + '</div>';
                            modalContent += '<div class="col-md-3"><strong>Net Weight:</strong><br>' + (details.header.cusdec_net_weight ? parseFloat(details.header.cusdec_net_weight).toFixed(2) + ' kg' : '-') + '</div>';
                            modalContent += '<div class="col-md-3"><strong>Invoice Number:</strong><br>' + (details.header.invoice_number || '-') + '</div>';
                            modalContent += '</div>';

                            // TOTALS
                            modalContent += '<h6 class="font-weight-bold text-primary border-bottom pb-2 mt-4 mb-3">Summary</h6>';
                            modalContent += '<div class="row mb-3">';
                            modalContent += '<div class="col-md-4"><strong>Total Items:</strong><br>' + details.header.total_items + '</div>';
                            modalContent += '<div class="col-md-4"><strong>Total Weight:</strong><br>' + parseFloat(details.header.total_weight).toFixed(2) + ' kg</div>';
                            modalContent += '<div class="col-md-4"><strong>Created By:</strong><br>' + (details.header.created_by_name || details.header.created_by || '-') + '</div>';
                            modalContent += '</div>';
                            modalContent += '<div class="row mb-3">';
                            modalContent += '<div class="col-md-4"><strong>Created At:</strong><br>' + formatDate(details.header.created_at) + '</div>';
                            if (details.header.approved_at) {
                                modalContent += '<div class="col-md-4"><strong>Approved At:</strong><br>' + formatDate(details.header.approved_at) + '</div>';
                            }
                            if (details.header.dispatched_at) {
                                modalContent += '<div class="col-md-4"><strong>Dispatched At:</strong><br>' + formatDate(details.header.dispatched_at) + '</div>';
                            }
                            modalContent += '</div>';
                        }

                        if (details.items && details.items.length > 0) {
                            modalContent += '<h6 class="font-weight-bold text-primary border-bottom pb-2 mt-4 mb-3">Dispatch Items (' + details.items.length + ')</h6>';
                            modalContent += '<div class="table-responsive">';
                            modalContent += '<table class="table table-bordered table-sm">';
                            modalContent += '<thead class="thead-light">';
                            modalContent += '<tr>';
                            modalContent += '<th>#</th>';
                            modalContent += '<th>Item Name</th>';
                            modalContent += '<th>Quantity</th>';
                            modalContent += '<th>Unit</th>';
                            modalContent += '<th>Material Type</th>';
                            modalContent += '<th>Weight (kg)</th>';
                            modalContent += '<th>Batch No</th>';
                            modalContent += '</tr>';
                            modalContent += '</thead>';
                            modalContent += '<tbody>';

                            var totalWeight = 0;
                            $.each(details.items, function (index, item) {
                                modalContent += '<tr>';
                                modalContent += '<td>' + (index + 1) + '</td>';
                                modalContent += '<td>' + (item.item_name || '') + '</td>';
                                modalContent += '<td class="text-right">' + parseFloat(item.quantity).toFixed(2) + '</td>';
                                modalContent += '<td>' + (item.quantity_unit || '') + '</td>';
                                modalContent += '<td>' + (item.material_type || '-') + '</td>';
                                modalContent += '<td class="text-right">' + parseFloat(item.weight).toFixed(2) + '</td>';
                                modalContent += '<td>' + (item.batch_no || '-') + '</td>';
                                modalContent += '</tr>';
                                totalWeight += parseFloat(item.weight || 0);
                            });

                            // Footer row
                            modalContent += '<tr class="table-secondary font-weight-bold">';
                            modalContent += '<td colspan="5" class="text-right"><strong>Total:</strong></td>';
                            modalContent += '<td class="text-right">' + totalWeight.toFixed(2) + ' kg</td>';
                            modalContent += '<td></td>';
                            modalContent += '</tr>';

                            modalContent += '</tbody>';
                            modalContent += '</table>';
                            modalContent += '</div>';
                        }

                        modalContent += '</div>';

                        $('#modalTitle').html('<i class="fas fa-ship mr-2"></i>Shipment Details - ' + details.header.shipment_id);
                        $('#modalBody').html(modalContent);
                        $('#detailsModal').modal('show');
                    } catch (e) {
                        console.error('Error loading details:', e);
                        showNotification('Error loading shipment details', 'danger');
                    }
                }
            });
        });

        // Edit shipment
        $(document).on('click', '.btnEdit', function () {
            var shipmentId = $(this).data('id');

            $.ajax({
                url: '<?php echo base_url(); ?>Shipmentplaning/GetShipmentForEdit',
                type: 'POST',
                data: { shipment_id: shipmentId },
                success: function (response) {
                    try {
                        var data = JSON.parse(response);

                        if (!data.status) {
                            alert(data.message);
                            return;
                        }

                        editMode = true;

                        // Set header values
                        $('#shipment_id').val(data.header.shipment_id);
                        $('#shipment_date').val(data.header.shipment_date);
                        $('#shipment_time').val(data.header.shipment_time);
                        // $('#shipment_status').val(data.header.shipment_status);
                        $('#supervisor').val(data.header.supervisor);
                        $('#loading_location').val(data.header.loading_location);
                        $('#exporter').val(data.header.exporter);
                        $('#buyer').val(data.header.buyer);
                        $('#consignee').val(data.header.consignee);
                        $('#country').val(data.header.country);
                        $('#handling_company').val(data.header.handling_company);
                        $('#freight_company').val(data.header.freight_company);
                        $('#vehicle_no').val(data.header.vehicle_no);
                        $('#transporter').val(data.header.transporter);
                        $('#driver_name').val(data.header.driver_name);
                        $('#driver_nic').val(data.header.driver_nic);
                        $('#driver_tp').val(data.header.driver_tp);
                        $('#container_id').val(data.header.container_id);
                        $('#container_number').val(data.header.container_number);
                        $('#seal_no').val(data.header.seal_no);
                        $('#container_size').val(data.header.container_size);
                        $('#cusdec_number').val(data.header.cusdec_number);
                        $('#cusdec_date').val(data.header.cusdec_date);
                        $('#cusdec_net_weight').val(data.header.cusdec_net_weight);
                        $('#invoice_number').val(data.header.invoice_number);
                        $('#shipmentId').val(shipmentId);

                        // Load items
                        itemsTable = [];
                        itemsCounter = 0;

                        $.each(data.items, function (index, item) {
                            itemsCounter++;
                            var itemData = {
                                id: 'item-' + itemsCounter,
                                index: itemsCounter,
                                item_name: item.item_name,
                                quantity: parseFloat(item.quantity),
                                quantity_unit: item.quantity_unit,
                                material_type: item.material_type,
                                weight: parseFloat(item.weight),
                                batch_no: item.batch_no,
                                stock_id: item.stock_id
                            };
                            itemsTable.push(itemData);
                        });

                        updateItemsTable();

                        // Show update button
                        $('#submitBtn').hide();
                        $('#updateBtn').show().data('shipment-id', shipmentId);
                        $('#cancelBtn').show();

                    } catch (e) {
                        console.error(e);
                        alert('Invalid response while editing');
                    }
                }
            });
        });

        // Update shipment
        $('#updateBtn').click(function () {
            var shipmentId = $(this).data('shipment-id');

            if (itemsTable.length === 0) {
                alert('Please add at least one item');
                return;
            }

            if (!confirm('Are you sure you want to update this shipment?')) {
                return;
            }

            var formData = $('#shipmentForm').serializeArray();
            var updateData = {};

            $.each(formData, function () {
                updateData[this.name] = this.value;
            });

            updateData.shipment_id = shipmentId;
            updateData.items = itemsTable;

            $.ajax({
                url: '<?php echo base_url(); ?>Shipmentplaning/UpdateShipment',
                type: 'POST',
                data: updateData,
                success: function (response) {
                    try {
                        var result = JSON.parse(response);
                        showNotification(result.message, result.type);

                        if (result.status) {
                            resetForm();
                            loadShipmentRecords();
                            loadNextShipmentId();
                        }
                    } catch (e) {
                        showNotification('Error updating shipment', 'danger');
                    }
                },
                error: function () {
                    showNotification('Error updating shipment', 'danger');
                }
            });
        });

        // Approve shipment
        $(document).on('click', '.btnApprove', function () {
            var shipmentId = $(this).data('id');

            if (!confirm('Are you sure you want to approve this shipment?')) {
                return;
            }

            $.ajax({
                url: '<?php echo base_url(); ?>Shipmentplaning/ApproveShipment',
                type: 'POST',
                data: { shipment_id: shipmentId },
                success: function (response) {
                    try {
                        var result = JSON.parse(response);
                        showNotification(result.message, result.type);

                        if (result.status) {
                            loadShipmentRecords();
                        }
                    } catch (e) {
                        showNotification('Error approving shipment', 'danger');
                    }
                }
            });
        });

        // Reject shipment
        // $(document).on('click', '.btnReject', function () {
        //     var shipmentId = $(this).data('id');

        //     if (!confirm('Are you sure you want to reject this shipment?')) {
        //         return;
        //     }

        //     $.ajax({
        //         url: '<?php echo base_url(); ?>Shipmentplaning/RejectShipment',
        //         type: 'POST',
        //         data: { shipment_id: shipmentId },
        //         success: function (response) {
        //             try {
        //                 var result = JSON.parse(response);
        //                 showNotification(result.message, result.type);

        //                 if (result.status) {
        //                     loadShipmentRecords();
        //                 }
        //             } catch (e) {
        //                 showNotification('Error rejecting shipment', 'danger');
        //             }
        //         }
        //     });
        // });
        $(document).on('click', '.btnDispatch', function () {
            var shipmentId = $(this).data('id');

            if (!confirm('Are you sure you want to mark this shipment as Dispatched?')) {
                return;
            }

            $.ajax({
                url: '<?php echo base_url(); ?>Shipmentplaning/UpdateShipmentStatus',
                type: 'POST',
                data: {
                    shipment_id: shipmentId,
                    shipment_status: 'Dispatched'
                },
                success: function (response) {
                    try {
                        var result = JSON.parse(response);
                        showNotification(result.message, result.type);

                        if (result.status) {
                            loadShipmentRecords();
                        }
                    } catch (e) {
                        showNotification('Error updating shipment status', 'danger');
                    }
                }
            });
        });

        // Delete shipment
        $(document).on('click', '.btnDelete', function () {
            var shipmentId = $(this).data('id');

            if (!confirm('Are you sure you want to delete this shipment? This action cannot be undone.')) {
                return;
            }

            $.ajax({
                url: '<?php echo base_url(); ?>Shipmentplaning/DeleteShipment',
                type: 'POST',
                data: { shipment_id: shipmentId },
                success: function (response) {
                    try {
                        var result = JSON.parse(response);
                        showNotification(result.message, result.type);

                        if (result.status) {
                            loadShipmentRecords();
                        }
                    } catch (e) {
                        showNotification('Error deleting shipment', 'danger');
                    }
                }
            });
        });

        // Cancel edit
        $('#cancelBtn').click(function () {
            if (confirm('Are you sure you want to cancel? All changes will be lost.')) {
                resetForm();
            }
        });

        // Reset form
        function resetForm() {
            $('#shipmentForm')[0].reset();
            $('#shipment_date').val('<?php echo date("Y-m-d"); ?>');
            $('#shipment_time').val('<?php echo date("H:i"); ?>');
            $('#shipmentId').val('');
            $('#invoice_number').val('');

            itemsTable = [];
            itemsCounter = 0;
            updateItemsTable();

            $('#submitBtn').show();
            $('#updateBtn').hide();
            $('#cancelBtn').hide();

            loadNextShipmentId();
            loadNextInvoiceNo();
            editMode = false;
        }

        // Show notification
        function showNotification(message, type) {
            let alertClass = '';
            switch (type) {
                case 'success':
                    alertClass = 'alert-success';
                    break;
                case 'danger':
                    alertClass = 'alert-danger';
                    break;
                case 'warning':
                    alertClass = 'alert-warning';
                    break;
                case 'info':
                    alertClass = 'alert-info';
                    break;
                default:
                    alertClass = 'alert-info';
            }

            let alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                 style="top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 300px;">
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>`;

            $('body').append(alertHtml);

            setTimeout(() => {
                $('.alert').alert('close');
            }, 5000);
        }

        // Initialize on page load
        loadNextShipmentId();
        loadNextInvoiceNo();
        loadAvailableStock();
        loadShipmentRecords();
    });
</script>
<?php include "include/footer.php"; ?>
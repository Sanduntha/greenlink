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
                            <span>Material Movement</span>
                        </h1>
                    </div>
                </div>
            </div>

            <div class="container-fluid mt-2 p-2">

                <!-- CREATE MOVEMENT CARD -->
                <div class="card mb-3" id="createMovementCard">
                    <div class="card-body p-3">
                        <form id="materialMovementForm" autocomplete="off" novalidate>

                            <!-- MOVEMENT HEADER -->
                            <h6 class="font-weight-bold text-primary border-bottom pb-2 mb-3">Movement Details</h6>

                            <div class="row">
                                <div class="col-md-4">
                                    <label class="small font-weight-bold">Movement ID</label>
                                    <input type="text" class="form-control form-control-sm" name="movement_id"
                                        id="movement_id" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="small font-weight-bold">Movement Type *</label>
                                    <select class="form-control form-control-sm" name="movement_type" id="movement_type"
                                        required>
                                        <option value="">Select Type</option>
                                        <option value="IN">IN</option>
                                        <option value="OUT">OUT</option>
                                        <option value="TRANSFER">TRANSFER</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="small font-weight-bold">Date & Time *</label>
                                    <input type="datetime-local" class="form-control form-control-sm"
                                        name="movement_datetime" id="movement_datetime"
                                        value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                                </div>
                            </div>

                            <!-- FROM SECTION -->
                            <h6 class="font-weight-bold text-primary border-bottom pb-2 mt-4 mb-2">From Location</h6>

                            <div class="row">
                                <div class="col-md-4">
                                    <label class="small font-weight-bold">Site / Warehouse*</label>
                                    <select class="form-control form-control-sm" name="from_site_location"
                                        id="from_site_location" required>
                                        <option value="">Select Location</option>
                                        <?php foreach ($site_locations as $location): ?>
                                            <option value="<?php echo $location['idtbl_location']; ?>">
                                                <?php echo htmlspecialchars($location['location_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="small font-weight-bold">Zone *</label>
                                    <select class="form-control form-control-sm" name="from_zone" id="from_zone"
                                        required>
                                        <option value="">Select Zone</option>
                                        <?php foreach ($warehouses as $warehouse): ?>
                                            <option value="<?php echo $warehouse['idtbl_rack']; ?>">
                                                <?php echo htmlspecialchars($warehouse['warehouse_code']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="small font-weight-bold">Available Stock</label>
                                    <div id="availableStockInfo" class="p-2 border rounded bg-light">
                                        <small>Select location and zone to view available stock</small>
                                    </div>
                                </div>
                            </div>

                            <!-- TO SECTION -->
                            <h6 class="font-weight-bold text-primary border-bottom pb-2 mt-4 mb-2">To Location</h6>

                            <div class="row">
                                <div class="col-md-4">
                                    <label class="small font-weight-bold">Site / Warehouse *</label>
                                    <select class="form-control form-control-sm" id="to_site_location"
                                        name="to_site_location" required>
                                        <option value="">Select Location</option>
                                        <?php foreach ($site_locations as $location): ?>
                                            <option value="<?php echo $location['idtbl_location']; ?>">
                                                <?php echo htmlspecialchars($location['location_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="small font-weight-bold">Zone *</label>
                                    <select class="form-control form-control-sm" name="to_zone" id="to_zone" required>
                                        <option value="">Select Zone</option>
                                        <?php foreach ($warehouses as $warehouse): ?>
                                            <option value="<?php echo $warehouse['idtbl_rack']; ?>">
                                                <?php echo htmlspecialchars($warehouse['warehouse_code']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="small font-weight-bold">Zone Capacity</label>
                                    <div id="zoneCapacityInfo" class="p-2 border rounded bg-light">
                                        <small>Select location and zone to view capacity</small>
                                    </div>
                                </div>
                            </div>

                            <!-- MATERIAL DETAILS -->
                            <h6 class="font-weight-bold text-primary border-bottom pb-2 mt-4 mb-2">
                                Material Details
                            </h6>

                            <div class="row">
                                <div class="col-md-3">
                                    <label class="small font-weight-bold">Material *</label>
                                    <select class="form-control form-control-sm select2" name="material_id"
                                        id="material_id" required>
                                        <option value="">Select Material</option>
                                        <?php foreach ($materials as $material): ?>
                                            <option value="<?php echo $material['idtbl_row_material']; ?>">
                                                <?php echo htmlspecialchars($material['material_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="small font-weight-bold">Batch No</label>
                                    <select class="form-control form-control-sm" name="batch_no" id="batch_no">
                                        <option value="">Select Batch</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="small font-weight-bold">Available Qty</label>
                                    <input type="text" class="form-control form-control-sm" id="available_qty" readonly>
                                </div>

                                <div class="col-md-2">
                                    <label class="small font-weight-bold">Quantity (KG / Unit) *</label>
                                    <input type="number" step="0.01" class="form-control form-control-sm"
                                        name="quantity" id="quantity" required>
                                </div>

                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="button" class="btn btn-primary btn-sm px-4 mr-2" id="addBtn">
                                        <i class="fas fa-plus"></i> Add
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm px-4" id="updateItemBtn"
                                        style="display:none;">
                                        <i class="fas fa-sync-alt"></i> Update
                                    </button>
                                </div>
                            </div>

                            <!-- MATERIALS TABLE -->
                            <h6 class="font-weight-bold text-primary border-bottom pb-2 mt-4 mb-2">Materials to Move
                            </h6>
                            <div id="tableError" class="alert alert-danger mt-2" style="display:none;"></div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm nowrap" id="itemsTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Material</th>
                                            <th>Batch No</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <!-- VEHICLE DETAILS -->
                            <h6 class="font-weight-bold text-primary border-bottom pb-2 mt-4 mb-2">
                                Vehicle Details
                            </h6>

                            <div class="row">
                                <div class="col-md-3">
                                    <label class="small font-weight-bold">Vehicle No</label>
                                    <input type="text" class="form-control form-control-sm" name="vehicle_no"
                                        id="vehicle_no">
                                </div>

                                <div class="col-md-2">
                                    <label class="small font-weight-bold">Moved By *</label>
                                    <input type="text" class="form-control form-control-sm" name="moved_by"
                                        id="moved_by" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mt-2">
                                    <label class="small font-weight-bold">Reason *</label>
                                    <textarea class="form-control form-control-sm" name="reason" id="reason" rows="2"
                                        required></textarea>
                                </div>
                            </div>

                            <!-- ACTION BUTTONS -->
                            <input type="hidden" id="movementId" name="movementId">
                            <div class="text-right mt-4">
                                <button type="submit" class="btn btn-primary btn-sm px-5" id="submitBtn">
                                    <i class="fas fa-save"></i> Save Movement
                                </button>
                                <button type="button" class="btn btn-info btn-sm px-5" id="updateBtn"
                                    style="display:none;">
                                    <i class="fas fa-save"></i> Update Movement
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm px-5" id="cancelBtn"
                                    style="display:none;">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

                <!-- MOVEMENT RECORDS TABLE -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h6 class="m-0 font-weight-bold">Movement Records</h6>
                    </div>
                    <div class="card-body p-0 p-2">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm nowrap" id="movementRecordsTable"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Movement ID</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>From Location</th>
                                        <th>To Location</th>
                                        <th>Moved By</th>
                                        <th>Status</th>
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
                                <h5 class="modal-title" id="modalTitle">Movement Details</h5>
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
        var rowCounter = 0;



        function clearAllErrors() {
            $('.form-control, select, textarea').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('#tableError').hide().text('');
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

        function validateMovementHeader() {
            clearAllErrors();

            let hasError = false;
            let firstInvalidField = null;

            const requiredFields = [
                { id: 'movement_type', label: 'Movement Type', type: 'select' },
                { id: 'movement_datetime', label: 'Date & Time' },
                { id: 'from_site_location', label: 'From Site / Warehouse', type: 'select' },
                { id: 'from_zone', label: 'From Zone', type: 'select' },
                { id: 'to_site_location', label: 'To Site / Warehouse', type: 'select' },
                { id: 'to_zone', label: 'To Zone', type: 'select' },
                { id: 'moved_by', label: 'Moved By' },
                { id: 'vehicle_no', label: 'Vehicle No' },
                { id: 'reason', label: 'Reason' }
            ];

            requiredFields.forEach(fieldConfig => {
                const $field = $('#' + fieldConfig.id);
                let value = $field.val() ? $field.val().trim() : '';

                let error = null;

                if (fieldConfig.type === 'select') {
                    if (!value || value === "") {
                        error = fieldConfig.label + ' is required';
                    }
                } else {
                    if (value === '') {
                        error = fieldConfig.label + ' is required';
                    }
                }

                if (error) {
                    showFieldError(fieldConfig.id, error);
                    hasError = true;
                    if (!firstInvalidField) firstInvalidField = $field;
                }
            });


            if (hasError && firstInvalidField) {
                firstInvalidField.focus();
                $('html, body').animate({
                    scrollTop: firstInvalidField.offset().top - 140
                }, 400);
            }

            return !hasError;
        }
        $('#materialMovementForm')
            .on('input change', '.form-control, select, textarea', function () {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            });

        // Initialize Select2
        $('.select2').select2({
            width: '100%'
        });

        // Initialize DataTables
        var itemsTable = $('#itemsTable').DataTable({
            responsive: true,
            paging: false,
            searching: false,
            info: false,
            ordering: false,
            columns: [
                { data: 'index' },
                { data: 'material_name' },
                { data: 'batch_no' },
                {
                    data: 'quantity',
                    className: 'text-right',
                    render: function (data) {
                        return parseFloat(data).toFixed(2);
                    }
                },
                {
                    data: null,
                    className: 'text-center',
                    render: function (data, type, row) {
                        return '<button class="btn btn-info btn-sm btnEditRow mr-1" data-rowid="' + row.id + '" title="Edit Item"><i class="fas fa-edit"></i></button>' +
                            '<button class="btn btn-danger btn-sm btnRemoveRow" data-rowid="' + row.id + '" title="Remove Item"><i class="fas fa-trash-alt"></i></button>';
                    }
                }
            ]
        });

        var recordsTable = $('#movementRecordsTable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,

            dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",

            responsive: true,

            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All']
            ],

            buttons: [
                {
                    extend: 'csv',
                    className: 'btn btn-success btn-sm',
                    title: 'Movement Records',
                    text: '<i class="fas fa-file-csv mr-2"></i> CSV'
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-danger btn-sm',
                    title: 'Movement Records',
                    text: '<i class="fas fa-file-pdf mr-2"></i> PDF'
                },
                {
                    extend: 'print',
                    title: 'Movement Records',
                    className: 'btn btn-primary btn-sm',
                    text: '<i class="fas fa-print mr-2"></i> Print',
                    customize: function (win) {
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ],

            ajax: {
                url: "<?php echo base_url(); ?>scripts/stockmovementlist.php",
                type: "POST"
            },

            order: [[1, "desc"]],

            columns: [

                // Row Number
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },

                { data: "movement_id" },

                {
                    data: "movement_date",
                    render: function (data) {
                        return formatDate(data);
                    }
                },

                { data: "movement_type" },

                { data: "from_location" },

                { data: "to_location" },

                { data: "moved_by" },

                {
                    data: "status",
                    render: function (data) {
                        if (data == 1) {
                            return '<span class="badge badge-success">Approved</span>';
                        } else if (data == 2) {
                            return '<span class="badge badge-warning">Pending</span>';
                        } else if (data == 3) {
                            return '<span class="badge badge-danger">Rejected</span>';
                        } else {
                            return '<span class="badge badge-secondary">Deleted</span>';
                        }
                    }
                },

                // Action Column
                {
                    data: null,
                    className: 'text-right',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, full) {

                        var button = '';

                        // View Button
                        button += '<button class="btn btn-primary btn-sm btnView mr-1" ';
                        button += 'data-id="' + full['id'] + '" ';
                        button += 'title="View"><i class="fas fa-eye"></i></button>';

                        // Only when Pending
                        if (full['status'] == 2) {

                            // Edit
                            button += '<button class="btn btn-primary btn-sm btnEdit mr-1 ';
                            if (editcheck != 1) { button += 'd-none'; }
                            button += '" data-id="' + full['id'] + '" ';
                            button += 'title="Edit"><i class="fas fa-pen"></i></button>';

                            // Approve
                            button += '<button class="btn btn-success btn-sm btnApprove mr-1 ';
                            if (statuscheck != 1) { button += 'd-none'; }
                            button += '" data-id="' + full['id'] + '" ';
                            button += 'title="Approve"><i class="fas fa-check"></i></button>';

                            // Delete
                            button += '<button class="btn btn-danger btn-sm btnDelete ';
                            if (deletecheck != 1) { button += 'd-none'; }
                            button += '" data-id="' + full['id'] + '" ';
                            button += 'title="Delete"><i class="fas fa-trash"></i></button>';
                        }

                        return button;
                    }
                }
            ],

            drawCallback: function () {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        // Load next movement ID
        function loadNextMovementId() {
            $.ajax({
                url: '<?php echo base_url(); ?>Stockmovement/GetNextMovementId',
                type: 'POST',
                success: function (response) {
                    try {
                        var data = JSON.parse(response);
                        $('#movement_id').val(data.movement_id);
                    } catch (e) {
                        console.error('Error loading movement ID:', e);
                        $('#movement_id').val('MOV-001');
                    }
                }
            });
        }

        loadNextMovementId();

        // Load batches when material is selected
        $('#material_id').change(function () {
            var materialId = $(this).val();
            var fromSite = $('#from_site_location').val();
            var fromZone = $('#from_zone').val();

            if (materialId && fromSite && fromZone) {
                loadBatches(materialId, fromSite, fromZone);
            }
        });

        // Load available stock when from location changes
        $('#from_site_location, #from_zone').change(function () {
            var fromSite = $('#from_site_location').val();
            var fromZone = $('#from_zone').val();
            var materialId = $('#material_id').val();

            if (fromSite && fromZone) {
                loadAvailableStock(fromSite, fromZone);
                if (materialId) {
                    loadBatches(materialId, fromSite, fromZone);
                }
            }
        });

        // Load zone capacity when to location changes
        $('#to_site_location, #to_zone').change(function () {
            var toSite = $('#to_site_location').val();
            var toZone = $('#to_zone').val();

            if (toSite && toZone) {
                loadZoneCapacity(toSite, toZone);
            }
        });

        function loadAvailableStock(siteId, zoneId) {
            $.ajax({
                url: '<?php echo base_url(); ?>Stockmovement/GetAvailableStock',
                type: 'POST',
                data: { site_id: siteId, zone_id: zoneId },
                success: function (response) {
                    try {
                        var data = JSON.parse(response);
                        var html = '<table class="table table-sm table-bordered mb-0">';
                        html += '<thead><tr><th>Material</th><th>Batch</th><th>Qty</th></tr></thead><tbody>';

                        if (data.length > 0) {
                            $.each(data, function (i, item) {
                                html += '<tr>';
                                html += '<td>' + item.material_name + '</td>';
                                html += '<td>' + item.batchnumber + '</td>';
                                html += '<td class="text-right">' + parseFloat(item.balanceqty).toFixed(2) + '</td>';
                                html += '</tr>';
                            });
                        } else {
                            html += '<tr><td colspan="3" class="text-center">No stock available</td></tr>';
                        }

                        html += '</tbody></table>';
                        $('#availableStockInfo').html(html);
                    } catch (e) {
                        console.error('Error loading available stock:', e);
                    }
                }
            });
        }

        function loadZoneCapacity(siteId, zoneId) {
            $.ajax({
                url: '<?php echo base_url(); ?>Stockmovement/GetZoneCapacity',
                type: 'POST',
                data: { site_id: siteId, zone_id: zoneId },
                success: function (response) {
                    try {
                        var data = JSON.parse(response);
                        console.log(data.used_capacity);
                        var usedCapacity = parseFloat(data.used_capacity) || 0;
                        var maxCapacity = parseFloat(data.max_capacity) || 0;
                        var availableCapacity = maxCapacity - usedCapacity;

                        var html = '<div>';
                        html += '<strong>Max Capacity:</strong> ' + maxCapacity.toFixed(2) + '<br>';
                        html += '<strong>Used:</strong> ' + usedCapacity.toFixed(2) + '<br>';
                        html += '<strong>Available:</strong> ' + availableCapacity.toFixed(2);
                        html += '</div>';

                        $('#zoneCapacityInfo').html(html);


                        $('#zoneCapacityInfo').data('available', availableCapacity);
                    } catch (e) {
                        console.error('Error loading zone capacity:', e);
                    }
                }
            });
        }


        function loadBatches(materialId, siteId, zoneId, selectedBatch = null) {
            $.ajax({
                url: '<?php echo base_url(); ?>Stockmovement/GetMaterialBatches',
                type: 'POST',
                data: {
                    material_id: materialId,
                    site_id: siteId,
                    zone_id: zoneId
                },
                success: function (response) {
                    try {
                        var batches = JSON.parse(response);
                        var options = '<option value="">Select Batch</option>';

                        $.each(batches, function (i, batch) {
                            options += '<option value="' + batch.batchnumber + '" data-qty="' + batch.qty + '">';
                            options += batch.batchnumber + ' (Qty: ' + parseFloat(batch.qty).toFixed(2) + ')';
                            options += '</option>';
                        });

                        $('#batch_no').html(options);

                        if (selectedBatch) {
                            $('#batch_no').val(selectedBatch).trigger('change');
                        }

                    } catch (e) {
                        console.error('Error loading batches:', e);
                    }
                }
            });
        }

        $('#batch_no').change(function () {
            var selected = $(this).find('option:selected');
            var qty = selected.data('qty') || 0;
            $('#available_qty').val(qty);
        });

        // Add item to table
        $('#addBtn').click(function () {
            if (editMode && editingRowId !== null) {
                alert('Finish updating the item first');
                return;
            }

            if (!validateItemFields()) return;
            addNewItemToTable();
        });

        $('#updateItemBtn').click(function () {
            if (!validateItemFields()) return;
            updateItemInTable();
        });

        function validateItemFields() {
            clearAllErrors();

            var materialId = $('#material_id').val();
            var quantity = parseFloat($('#quantity').val()) || 0;
            var batchNo = $('#batch_no').val();
            var availableQty = parseFloat($('#available_qty').val()) || 0;
            console.log(availableQty);
            var toAvailableCapacity = $('#zoneCapacityInfo').data('available') || 0;

            var hasError = false;

            if (!materialId) {
                showFieldError('material_id', 'Please select material');
                hasError = true;
            }

            if (!batchNo) {
                showFieldError('batch_no', 'Please select batch');
                hasError = true;
            }

            if (quantity <= 0) {
                showFieldError('quantity', 'Quantity must be greater than 0');
                hasError = true;
            } else if (quantity > availableQty) {
                showFieldError('quantity', 'Quantity exceeds available stock (' + availableQty.toFixed(2) + ')');
                hasError = true;
            }


            var totalQuantity = quantity;
            itemsTable.rows().every(function () {
                totalQuantity += parseFloat(this.data().quantity) || 0;
            });

            if (totalQuantity > toAvailableCapacity) {
                showTableError('Total quantity exceeds available zone capacity (' + toAvailableCapacity.toFixed(2) + ')');
                hasError = true;
            }

            return !hasError;
        }

        function addNewItemToTable() {
            var materialId = $('#material_id').val();
            var materialName = $('#material_id option:selected').text();
            var batchNo = $('#batch_no').val();
            var quantity = parseFloat($('#quantity').val()) || 0;

            rowCounter++;
            var rowData = {
                id: 'row-' + rowCounter,
                index: itemsTable.rows().count() + 1,
                material_id: materialId,
                material_name: materialName,
                batch_no: batchNo,
                quantity: quantity
            };

            itemsTable.row.add(rowData).draw();
            resetMaterialForm();
        }

        function updateItemInTable() {
            if (!editingRowId) return;

            var materialId = $('#material_id').val();
            var materialName = $('#material_id option:selected').text();
            var batchNo = $('#batch_no').val();
            var quantity = parseFloat($('#quantity').val()) || 0;

            itemsTable.rows().every(function () {
                if (this.data().id === editingRowId) {
                    var data = this.data();
                    data.material_id = materialId;
                    data.material_name = materialName;
                    data.batch_no = batchNo;
                    data.quantity = quantity;
                    this.data(data);
                    return false;
                }
            });

            itemsTable.draw(false);
            resetMaterialForm();
            $('#addBtn').show();
            $('#updateItemBtn').hide();
            editingRowId = null;
        }

        function resetMaterialForm() {
            $('#material_id').val('').trigger('change');
            $('#batch_no').val('');
            $('#available_qty').val('');
            $('#quantity').val('');
        }

        function resetAllForm() {
            $('#movement_type').val('');
            $('#movement_datetime').val('<?php echo date('Y-m-d\TH:i'); ?>');
            $('#from_site_location').val('');
            $('#from_zone').val('');
            $('#to_site_location').val('');
            $('#to_zone').val('');
            $('#vehicle_no').val('');
            $('#moved_by').val('');
            $('#reason').val('');

            resetMaterialForm();

            itemsTable.clear().draw();
            rowCounter = 0;

            $('#availableStockInfo').html('<small>Select location and zone to view available stock</small>');
            $('#zoneCapacityInfo').html('<small>Select location and zone to view capacity</small>');

            $('#submitBtn').show();
            $('#updateBtn').hide();
            $('#cancelBtn').hide();
            $('#addBtn').show();
            $('#updateItemBtn').hide();
            editMode = false;
            editingRowId = null;

            loadNextMovementId();
        }

        // Form submit
        // $('#materialMovementForm').submit(function (e) {
        //     if (!e.originalEvent || !e.originalEvent.submitter ||
        //         e.originalEvent.submitter.id !== 'submitBtn') {
        //         e.preventDefault();
        //         return;
        //     }

        //     e.preventDefault();

        //     if (itemsTable.rows().count() === 0) {
        //         alert('Please add at least one material');
        //         return;
        //     }

        //     if (!$('#moved_by').val()) {
        //         alert('Please enter who moved the material');
        //         return;
        //     }

        //     if (!$('#reason').val()) {
        //         alert('Please enter a reason for movement');
        //         return;
        //     }

        //     if (!confirm('Are you sure you want to save this movement?')) {
        //         return;
        //     }

        //     var movementData = {
        //         movement_type: $('#movement_type').val(),
        //         movement_datetime: $('#movement_datetime').val(),
        //         from_site_location: $('#from_site_location').val(),
        //         from_zone: $('#from_zone').val(),
        //         to_site_location: $('#to_site_location').val(),
        //         to_zone: $('#to_zone').val(),
        //         vehicle_no: $('#vehicle_no').val(),
        //         moved_by: $('#moved_by').val(),
        //         reason: $('#reason').val(),
        //         items: itemsTable.rows().data().toArray()
        //     };

        //     $.ajax({
        //         url: '<?php echo base_url(); ?>Stockmovement/SaveMovement',
        //         type: 'POST',
        //         data: movementData,
        //         success: function (response) {
        //             try {
        //                 var result = JSON.parse(response);
        //                 showNotification(result.message, result.type);

        //                 if (result.status) {
        //                     resetAllForm();
        //                     recordsTable.ajax.reload();
        //                 }
        //             } catch (e) {
        //                 showNotification('Error saving movement', 'danger');
        //             }
        //         },
        //         error: function () {
        //             showNotification('Error saving movement', 'danger');
        //         }
        //     });
        // });
        $('#materialMovementForm').on('submit', function (e) {
            e.preventDefault();

            if (!e.originalEvent?.submitter || e.originalEvent.submitter.id !== 'submitBtn') {
                return;
            }

          
            if (!validateMovementHeader()) {
                return;
            }

           
            if (itemsTable.rows().count() === 0) {
                $('#tableError')
                    .text('Please add at least one material to move.')
                    .show();
                $('html, body').animate({
                    scrollTop: $('#itemsTable').offset().top - 140
                }, 400);
                return;
            }

           

            if (!confirm('Are you sure you want to save this material movement?')) {
                return;
            }

            var movementData = {
                movement_type: $('#movement_type').val(),
                movement_datetime: $('#movement_datetime').val(),
                from_site_location: $('#from_site_location').val(),
                from_zone: $('#from_zone').val(),
                to_site_location: $('#to_site_location').val(),
                to_zone: $('#to_zone').val(),
                vehicle_no: $('#vehicle_no').val() || null,
                moved_by: $('#moved_by').val(),
                reason: $('#reason').val(),
                items: itemsTable.rows().data().toArray()
            };

            $.ajax({
                url: '<?php echo base_url(); ?>Stockmovement/SaveMovement',
                type: 'POST',
                data: movementData,
                beforeSend: function () {
                    $('#submitBtn')
                        .prop('disabled', true)
                        .html('<i class="fas fa-spinner fa-spin"></i> Saving...');
                },
                success: function (response) {
                    try {
                        var result = JSON.parse(response);
                        showNotification(result.message, result.type || 'info');

                        if (result.status === true || result.success) {
                            resetAllForm();
                            recordsTable.ajax.reload();
                        }
                    } catch (err) {
                        showNotification('Invalid response from server', 'danger');
                    }
                },
                error: function () {
                    showNotification('Network error while saving movement', 'danger');
                },
                complete: function () {
                    $('#submitBtn')
                        .prop('disabled', false)
                        .html('<i class="fas fa-save"></i> Save Movement');
                }
            });
        });

        // View details
        $('#movementRecordsTable tbody').on('click', '.btnView', function () {
            var movementId = $(this).data('id');

            $.ajax({
                url: '<?php echo base_url(); ?>Stockmovement/GetMovementDetails',
                type: 'POST',
                data: { movement_id: movementId },
                success: function (response) {
                    try {
                        var details = JSON.parse(response);
                        var modalContent = '<div class="container-fluid">';

                        // Header / Movement Info
                        if (details.header) {
                            modalContent += '<h6>Movement Information</h6>';
                            modalContent += '<table class="table table-bordered table-sm mb-3">';
                            modalContent += '<tr><th>Movement ID:</th><td>' + (details.header.movement_id || '') + '</td></tr>';
                            modalContent += '<tr><th>Date:</th><td>' + formatDate(details.header.movement_date) + '</td></tr>';
                            modalContent += '<tr><th>Type:</th><td>' + (details.header.movement_type || '') + '</td></tr>';
                            modalContent += '<tr><th>From:</th><td>' +
                                (details.header.from_site_location_name || '') + ' - ' +
                                (details.header.from_zone_name || '') + '</td></tr>';
                            modalContent += '<tr><th>To:</th><td>' +
                                (details.header.to_site_location_name || '') + ' - ' +
                                (details.header.to_zone_name || '') + '</td></tr>';
                            modalContent += '<tr><th>Vehicle No:</th><td>' + (details.header.vehicle_no || '') + '</td></tr>';
                            modalContent += '<tr><th>Moved By:</th><td>' + (details.header.moved_by || '') + '</td></tr>';
                            modalContent += '<tr><th>Reason:</th><td>' + (details.header.reason || '') + '</td></tr>';
                            modalContent += '<tr><th>Status:</th><td>';
                            if (details.header.status == 1) modalContent += '<span class="badge badge-success">Approved</span>';
                            else if (details.header.status == 2) modalContent += '<span class="badge badge-warning">Pending</span>';
                            else if (details.header.status == 3) modalContent += '<span class="badge badge-danger">Rejected</span>';
                            else modalContent += '<span class="badge badge-secondary">Deleted</span>';
                            modalContent += '</td></tr>';
                            modalContent += '<tr><th>Created At:</th><td>' + (details.header.created_at || '') + '</td></tr>';
                            modalContent += '<tr><th>Approved At:</th><td>' + (details.header.approved_at || 'N/A') + '</td></tr>';
                            modalContent += '<tr><th>Created By:</th><td>' + (details.header.created_by || '') + '</td></tr>';
                            modalContent += '<tr><th>Approved By:</th><td>' + (details.header.approved_by || 'N/A') + '</td></tr>';
                            modalContent += '</table>';
                        }

                        // Item Details
                        if (details.details && details.details.length > 0) {
                            modalContent += '<h6>Movement Items</h6>';
                            modalContent += '<table class="table table-bordered table-sm">';
                            modalContent += '<thead><tr><th>#</th><th>Material</th><th>Batch No</th><th>Quantity</th><th>Status</th></tr></thead>';
                            modalContent += '<tbody>';
                            $.each(details.details, function (index, item) {
                                modalContent += '<tr>';
                                modalContent += '<td>' + (index + 1) + '</td>';
                                modalContent += '<td>' + (item.material_name || '') + '</td>';
                                modalContent += '<td>' + (item.batch_no || '') + '</td>';
                                modalContent += '<td class="text-right">' + parseFloat(item.quantity).toFixed(2) + '</td>';

                                modalContent += '<td>';
                                if (item.status == 1) modalContent += '<span class="badge badge-success">Approved</span>';
                                else if (item.status == 2) modalContent += '<span class="badge badge-warning">Pending</span>';
                                else if (item.status == 3) modalContent += '<span class="badge badge-danger">Rejected</span>';
                                else modalContent += '<span class="badge badge-secondary">Deleted</span>';
                                modalContent += '</td>';
                                modalContent += '</tr>';
                            });
                            modalContent += '</tbody></table>';
                        }

                        modalContent += '</div>';

                        $('#modalTitle').text('Movement Details');
                        $('#modalBody').html(modalContent);
                        $('#detailsModal').modal('show');
                    } catch (e) {
                        console.error(e);
                        showNotification('Error loading details', 'danger');
                    }
                }
            });
        });
        // Edit movement
        $('#movementRecordsTable tbody').on('click', '.btnEdit', function () {
            var movementId = $(this).data('id');

            $.ajax({
                url: '<?php echo base_url(); ?>Stockmovement/GetMovementForEdit',
                type: 'POST',
                data: { movement_id: movementId },
                success: function (response) {
                    try {
                        var data = JSON.parse(response);

                        if (!data.status) {
                            alert(data.message || 'Failed to load movement');
                            return;
                        }

                        editMode = true;

                        $('#movement_id').val(data.header.movement_id);
                        $('#movement_type').val(data.header.movement_type);
                        $('#movement_datetime').val(data.header.movement_date.replace(' ', 'T'));
                        $('#from_site_location').val(data.header.from_site_location_id);
                        $('#from_zone').val(data.header.from_zone_id);
                        $('#to_site_location').val(data.header.to_site_location_id);
                        $('#to_zone').val(data.header.to_zone_id);
                        $('#vehicle_no').val(data.header.vehicle_no);
                        $('#moved_by').val(data.header.moved_by);
                        $('#reason').val(data.header.reason);

                        // Load available stock and zone capacity
                        loadAvailableStock(data.header.from_site_location_id, data.header.from_zone_id);
                        loadZoneCapacity(data.header.to_site_location_id, data.header.to_zone_id);

                        // Load items
                        itemsTable.clear().draw();
                        rowCounter = 0;

                        $.each(data.items, function (index, item) {
                            rowCounter++;
                            var rowData = {
                                id: 'row-' + rowCounter,
                                index: index + 1,
                                material_id: item.material_id,
                                material_name: item.material_name,
                                batch_no: item.batch_no,
                                quantity: parseFloat(item.quantity)
                            };
                            itemsTable.row.add(rowData).draw(false);
                        });

                        $('#submitBtn').hide();
                        $('#updateBtn').show().data('movement-id', movementId);
                        $('#cancelBtn').show();

                    } catch (e) {
                        console.error(e);
                        alert('Invalid response while editing');
                    }
                }
            });
        });

        // Update movement
        $('#updateBtn').click(function () {
            var movementId = $(this).data('movement-id');

            if (itemsTable.rows().count() === 0) {
                alert('Please add at least one material');
                return;
            }

            if (!$('#moved_by').val()) {
                alert('Please enter who moved the material');
                return;
            }

            if (!$('#reason').val()) {
                alert('Please enter a reason for movement');
                return;
            }

            if (!confirm('Are you sure you want to update this movement?')) {
                return;
            }

            var updateData = {
                movement_id: movementId,
                movement_type: $('#movement_type').val(),
                movement_datetime: $('#movement_datetime').val(),
                from_site_location: $('#from_site_location').val(),
                from_zone: $('#from_zone').val(),
                to_site_location: $('#to_site_location').val(),
                to_zone: $('#to_zone').val(),
                vehicle_no: $('#vehicle_no').val(),
                moved_by: $('#moved_by').val(),
                reason: $('#reason').val(),
                items: itemsTable.rows().data().toArray()
            };

            $.ajax({
                url: '<?php echo base_url(); ?>Stockmovement/UpdateMovement',
                type: 'POST',
                data: updateData,
                success: function (response) {
                    try {

                        var result = JSON.parse(response);
                        console.log(result);
                        showNotification(result.message, result.type);
                        if (result.status) {
                            resetAllForm();
                            recordsTable.ajax.reload();
                        }
                    } catch (e) {
                        showNotification('Error processing response', 'danger');
                    }
                },
                error: function () {
                    showNotification('Error updating movement', 'danger');
                }
            });
        });

        // Cancel edit
        $('#cancelBtn').click(function () {
            if (confirm('Are you sure you want to cancel editing? All changes will be lost.')) {
                resetAllForm();
            }
        });

        // Approve movement
        $('#movementRecordsTable tbody').on('click', '.btnApprove', function () {
            var movementId = $(this).data('id');
            console.log(movementId);

            if (!confirm('Are you sure you want to approve this movement?')) {
                return;
            }

            $.ajax({
                url: '<?php echo base_url(); ?>Stockmovement/ApproveMovement',
                type: 'POST',
                data: { movement_id: movementId },
                success: function (response) {
                    try {
                        var result = JSON.parse(response);
                        showNotification(result.message, result.type);
                        if (result.status) {
                            recordsTable.ajax.reload();
                        }
                    } catch (e) {
                        showNotification('Error processing response', 'danger');
                    }
                }
            });
        });

        // Delete movement
        $('#movementRecordsTable tbody').on('click', '.btnDelete', function () {
            var movementId = $(this).data('id');

            if (!confirm('Are you sure you want to delete this movement?')) {
                return;
            }

            $.ajax({
                url: '<?php echo base_url(); ?>Stockmovement/DeleteMovement',
                type: 'POST',
                data: { movement_id: movementId },
                success: function (response) {
                    try {
                        var result = JSON.parse(response);
                        showNotification(result.message, result.type);
                        if (result.status) {
                            recordsTable.ajax.reload();
                        }
                    } catch (e) {
                        showNotification('Error processing response', 'danger');
                    }
                }
            });
        });

        // Remove item from table
        $('#itemsTable tbody').on('click', '.btnRemoveRow', function () {
            var rowId = $(this).data('rowid');

            if (confirm('Are you sure you want to remove this item?')) {
                itemsTable.rows().every(function () {
                    if (this.data().id === rowId) {
                        this.remove().draw();
                        itemsTable.rows().every(function (rowIdx) {
                            this.data().index = rowIdx + 1;
                        });
                        itemsTable.draw(false);
                        return false;
                    }
                });
            }
        });


        // Edit item in table
        $('#itemsTable tbody').on('click', '.btnEditRow', function () {
            var rowId = $(this).data('rowid');
            var rowData = null;


            itemsTable.rows().every(function () {
                if (this.data().id === rowId) {
                    rowData = this.data();
                    return false;
                }
            });

            if (rowData) {
                var materialId = rowData.material_id;
                var materialName = rowData.material_name;


                if ($('#material_id option[value="' + materialId + '"]').length === 0) {
                    $('#material_id').append(new Option(materialName, materialId, true, true));
                } else {
                    $('#material_id').val(materialId).trigger('change');
                }


                var fromSite = $('#from_site_location').val();
                var fromZone = $('#from_zone').val();


                setTimeout(function () {

                    $('#batch_no').val(rowData.batch_no).trigger('change');


                    $('#quantity').val(rowData.quantity);
                }, 300);
                editingRowId = rowId;
                $('#addBtn').hide();
                $('#updateItemBtn').show();
            }
        });


        function clearAllErrors() {
            $('.form-control, select').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('#tableError').hide().text('');
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

        function showTableError(message) {
            $('#tableError').text(message).show();
        }

        function formatDate(dateString) {
            if (!dateString) return '';
            var date = new Date(dateString);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
        }

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
    });
</script>
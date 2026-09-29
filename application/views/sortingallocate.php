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
                            <div class="page-header-icon"><i class="fas fa-boxes"></i></div>
                            <span>Allocation Material for Processing</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form id="allocationForm">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">GRN Number*</label>
                                        <select class="form-control selecter2 form-control-sm" name="grnnumber"
                                            id="grnnumber" required>
                                            <option value="">Select</option>
                                            <?php foreach ($grnlist->result() as $grn) { ?>
                                                <option value="<?php echo $grn->idtbl_grn ?>">
                                                    <?php echo $grn->grn_number ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Material Name*</label>
                                        <select class="form-control selecter2 form-control-sm" name="materialname"
                                            id="materialname" required disabled>
                                            <option value="">Select GRN First</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Batch & Location*</label>
                                        <select class="form-control selecter2 form-control-sm" name="batchlocation"
                                            id="batchlocation" required disabled>
                                            <option value="">Select Material First</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Available Qty</label>
                                        <input type="text" class="form-control form-control-sm" id="availableqty"
                                            readonly>
                                    </div>

                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Allocation Qty*</label>
                                        <input type="number" step="0.01" class="form-control form-control-sm" name="qty"
                                            id="qty" required min="0.01">
                                        <small class="text-danger" id="qtyError"></small>
                                    </div>

                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Remarks</label>
                                        <textarea class="form-control form-control-sm" name="remarks" id="remarks"
                                            rows="2"></textarea>
                                    </div>

                                    <div class="form-group mt-2 text-right">
                                        <button type="button" id="addBtn" class="btn btn-primary btn-sm px-4">
                                            <i class="fas fa-plus"></i>&nbsp;Add to List
                                        </button>
                                        <button type="button" id="updateBtn" class="btn btn-warning btn-sm px-4"
                                            style="display: none;">
                                            <i class="fas fa-save"></i>&nbsp;Update
                                        </button>
                                        <button type="button" id="cancelEditBtn" class="btn btn-secondary btn-sm px-4"
                                            style="display: none;">
                                            <i class="fas fa-times"></i>&nbsp;Cancel
                                        </button>
                                    </div>

                                    <!-- Hidden fields for edit mode -->
                                    <input type="hidden" id="editItemIndex" value="-1">
                                    <input type="hidden" id="editAllocationId" value="">
                                    <input type="hidden" id="editMode" value="false">
                                </form>
                            </div>
                            <div class="col-9">
                                <div class="scrollbar pb-3" id="style-2">
                                    <h6 class="title-style small font-weight-bold mt-1"><span>Allocation Details</span>
                                    </h6>

                                    <table class="table table-bordered table-striped table-sm nowrap" id="tblallocate"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>GRN Number</th>
                                                <th>Material Name</th>
                                                <th>Batch Number</th>
                                                <th>Site location</th>
                                                <th>Zone</th>
                                                <th>Qty</th>
                                                <th>Remarks</th>
                                                <th class="text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="allocationList">
                                            <!-- Dynamic content will be added here -->
                                        </tbody>
                                    </table>
                                    <div class="text-right mt-2">
                                        <button type="button" id="submitAllBtn" class="btn btn-primary btn-sm px-4"
                                            disabled>
                                            <i class="fas fa-check"></i>&nbsp;Submit Allocations
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="border-dark">
                        <div class="col-12">
                            <h6 class="title-style small font-weight-bold mt-2"><span>Allocation Pending</span></h6>

                            <table class="table table-bordered table-striped table-sm nowrap" id="allocatepending"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>GRN Number</th>
                                        <th>Material Name</th>
                                        <th>Batch Number</th>
                                        <th>Site Location</th>
                                        <th>Zone</th>
                                        <th>Qty</th>
                                        <th>Date</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <hr class="border-dark">
                        <div class="col-12">
                            <h6 class="title-style small font-weight-bold mt-2"><span>Allocation History</span></h6>

                            <table class="table table-bordered table-striped table-sm nowrap" id="allocatehistory"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>GRN Number</th>
                                        <th>Material Name</th>
                                        <th>Batch Number</th>
                                        <th>Site Location</th>
                                        <th>Zone</th>
                                        <th>Qty</th>
                                        <th>Status</th>
                                        <th>Sorting Status</th>
                                        <th>Date</th>
                                        <th class="text-center">Action</th> <!-- NEW COLUMN -->
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Allocation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="rejectForm">
                    <input type="hidden" id="rejectAllocationId">
                    <div class="form-group">
                        <label>Reason for Rejection</label>
                        <textarea class="form-control" id="rejectReason" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmRejectBtn">Reject</button>
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

        var allocationItems = [];
        var pendingAllocationItems = [];
        var selectedBatchId = null;
        var selectedBatchQty = 0;
        var isEditMode = false;
        var currentEditIndex = -1;
        var currentEditSource = '';
        var currentEditAllocationId = null;
        var pendingWarningShown = false;

        $('.selecter2').select2();
        function clearAllErrors() {
            $('.form-control, select').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('#qtyError').text(''); // Keep existing qty error clear
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

        function validateAllocationForm() {
            clearAllErrors();

            let hasError = false;
            let firstErrorField = null;

            let requiredFields = [
                { id: 'grnnumber', label: 'GRN Number' },
                { id: 'materialname', label: 'Material Name' },
                { id: 'batchlocation', label: 'Batch & Location' },
                { id: 'qty', label: 'Allocation Qty', numeric: true }
            ];

            requiredFields.forEach(function (conf) {
                let field = $('#' + conf.id);
                let value = field.val() ? field.val().trim() : '';
                let errorMsg = null;

                if (value === '' || value === null) {
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


            if (!hasError) {
                let qty = parseFloat($('#qty').val()) || 0;
                if (selectedBatchQty > 0 && qty > selectedBatchQty) {
                    $('#qtyError').text('Quantity cannot exceed available quantity (' + selectedBatchQty + ')');
                    $('#qty').addClass('is-invalid');
                    hasError = true;
                    if (!firstErrorField) firstErrorField = $('#qty');
                }
            }

            if (hasError && firstErrorField) {
                firstErrorField.focus();
                $('html, body').animate({
                    scrollTop: firstErrorField.offset().top - 150
                }, 500);
            }

            return !hasError;
        }

        $(document).on('input change', '.form-control, select', function () {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
            if ($(this).attr('id') === 'qty') {
                $('#qtyError').text('');
            }
        });

        $('#grnnumber, #materialname, #batchlocation').on('select2:select select2:clear', function () {
            $(this).removeClass('is-invalid');
            $(this).nextAll('.invalid-feedback').remove();
        });

        $('#grnnumber').change(function () {
            if (!isEditMode) {
                var grnid = $(this).val();
                if (grnid) {
                    $.ajax({
                        url: '<?php echo base_url(); ?>SortingAllocate/get_materials_by_grn/' + grnid,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            var options = '<option value="">Select Material</option>';
                            $.each(data, function (key, item) {
                                options += '<option value="' + item.idtbl_row_material + '">' + item.material_name + '</option>';
                            });
                            $('#materialname').html(options).prop('disabled', false).trigger('change');
                            $('#batchlocation').prop('disabled', true).html('<option value="">Select Material First</option>').trigger('change');
                            $('#availableqty').val('');
                        }
                    });
                } else {
                    $('#materialname').html('<option value="">Select GRN First</option>').prop('disabled', true).trigger('change');
                    $('#batchlocation').prop('disabled', true).html('<option value="">Select Material First</option>').trigger('change');
                    $('#availableqty').val('');
                }
            }
        });



        $('#materialname').change(function () {
            if (!isEditMode) {
                var materialId = $(this).val();
                var grnId = $('#grnnumber').val();
                if (materialId) {
                    $.ajax({
                        url: '<?php echo base_url(); ?>SortingAllocate/get_batch_locations/' + materialId + '/' + grnId,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            var options = '<option value="">Select Batch & Location</option>';

                            // Remove any existing alert
                            $('.batch-alert').remove();

                            if (data && data.length > 0) {
                                $.each(data, function (key, item) {
                                    options += '<option value="' + item.idtbl_batchstock + '" ' +
                                        'data-batch="' + item.batchnumber + '" ' +
                                        'data-rack-id="' + item.rack_id + '" ' +
                                        'data-rack-number="' + item.rack_number + '" ' +
                                        'data-site-id="' + item.site_location_id + '" ' +
                                        'data-site-name="' + item.location_name + '" ' +
                                        'data-qty="' + item.balanceqty + '">';

                                    options += item.batchnumber + ' – ' +
                                        (item.location_name || '—') + ' – ' +
                                        (item.rack_number || '—') +
                                        ' (Avail: ' + item.balanceqty + ')';
                                    options += '</option>';
                                });
                                $('#batchlocation').html(options).prop('disabled', false).trigger('change');
                                $('#availableqty').val('');
                                selectedBatchId = null;
                                selectedBatchQty = 0;
                            } else {
                                // No batches found - show alert
                                $('#batchlocation').html('<option value="">No batches available</option>').prop('disabled', true);
                                $('#availableqty').val('');
                                selectedBatchId = null;
                                selectedBatchQty = 0;

                                // Show alert message
                                var alertHtml = '<div class="alert alert-warning alert-dismissible fade show batch-alert mt-2" role="alert" style="font-size: 0.9rem; padding: 0.5rem 0.75rem;">' +
                                    '<i class="fas fa-exclamation-triangle"></i> No available batches found for this material in the selected GRN. The batch may already be allocated or empty.' +
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="font-size: 1rem; padding: 0.5rem;">' +
                                    '<span aria-hidden="true">&times;</span>' +
                                    '</button>' +
                                    '</div>';

                                // Insert after the batch location select
                                $('#batchlocation').closest('.form-group').after(alertHtml);
                            }
                        }
                    });
                } else {
                    $('#batchlocation').prop('disabled', true).html('<option value="">Select Material First</option>').trigger('change');
                    $('#availableqty').val('');
                    $('.batch-alert').remove();
                }
            }
        });
        $('#batchlocation').change(function () {
            var selectedOption = $(this).find(':selected');
            if (selectedOption.val()) {
                selectedBatchId = selectedOption.val();
                selectedBatchQty = parseFloat(selectedOption.data('qty'));
                var batchNumber = selectedOption.data('batch');
                var location = selectedOption.data('location');

                $('#availableqty').val(selectedBatchQty);
                $('#qty').attr('max', selectedBatchQty);
                validateQty();
            } else {
                selectedBatchId = null;
                selectedBatchQty = 0;
                $('#availableqty').val('');
                $('#qty').removeAttr('max');
            }
        });
        $('#addBtn').prop('disabled', true);

        function toggleAddButtonBasedOnPending(pendingCount) {
            console.log(pendingCount);

            if (pendingCount > 0) {
                $('#addBtn').prop('disabled', true);
                // $('#qty').prop('disabled', true);

                if (!pendingWarningShown) {
                    showMessage(
                        'You have pending allocations. Please approve or reject them before adding new allocations.',
                        'warning'
                    );
                    pendingWarningShown = true;
                }
            } else {
                $('#addBtn').prop('disabled', false);
                $('#qty').prop('disabled', false);

                pendingWarningShown = false;
            }
        }



        $('#qty').on('input', validateQty);

        function validateQty() {
            var qty = parseFloat($('#qty').val()) || 0;
            if (selectedBatchQty > 0 && qty > selectedBatchQty) {
                $('#qtyError').text('Quantity cannot exceed available quantity (' + selectedBatchQty + ')');
                $('#addBtn').prop('disabled', true);
                $('#updateBtn').prop('disabled', true);
                $('#submitAllBtn').prop('disabled', true);
                return false;
            } else if (qty <= 0) {
                $('#qtyError').text('Quantity must be greater than 0');
                $('#addBtn').prop('disabled', true);
                $('#updateBtn').prop('disabled', true);
                $('#submitAllBtn').prop('disabled', true);
                return false;
            } else {
                $('#qtyError').text('');
                if (isEditMode) {
                    $('#updateBtn').prop('disabled', false);
                    $('#submitAllBtn').prop('disabled', false);
                } else {
                    $('#addBtn').prop('disabled', false);
                    $('#submitAllBtn').prop('disabled', allocationItems.length === 0);
                }
                return true;
            }
        }

        // Add new item to list
        $('#addBtn').click(function () {

            if (!validateAllocationForm()) {
                return;
            }
            if (!validateQty()) return;

            var grnNumber = $('#grnnumber option:selected').text();
            var materialName = $('#materialname option:selected').text();
            var batchLocation = $('#batchlocation option:selected');
            var batchNumber = batchLocation.data('batch');
            var location = batchLocation.data('location');
            var locationname = batchLocation.data('locationname');
            var qty = parseFloat($('#qty').val());
            var remarks = $('#remarks').val();

            var item = {
                id: Date.now(),
                source: 'new',
                grn_id: $('#grnnumber').val(),
                material_id: $('#materialname').val(),
                batch_id: selectedBatchId,
                batch_number: batchNumber,
                rack_id: parseInt(batchLocation.data('rack-id')) || null,
                site_location_id: parseInt(batchLocation.data('site-id')) || null,
                rack_number: batchLocation.data('rack-number') || '-',   // ← add this
                site_location: batchLocation.data('site-name') || '-',
                qty: qty,
                remarks: remarks,
                grn_display: grnNumber,
                material_display: materialName
            };
            console.log("===== Add Button Clicked =====");
            console.log("GRN Number:", grnNumber);
            console.log("Material Name:", materialName);
            console.log("Batch Number:", batchNumber);
            console.log("Location:", location);
            console.log("Location Name:", locationname);
            console.log("Quantity:", qty);
            console.log("Remarks:", remarks);
            console.log("Selected Batch ID:", selectedBatchId);
            console.log("Final Item Object:", item);
            console.log("================================");

            allocationItems.push(item);
            renderAllocationList();
            resetForm();
        });

        function renderAllocationList() {
            var html = '';
            var totalQty = 0;
            var itemCount = 0;


            var allItems = [...allocationItems, ...pendingAllocationItems];

            $.each(allItems, function (index, item) {
                totalQty += item.qty;
                itemCount++;
                html += '<tr data-index="' + index + '" data-source="' + item.source + '">';
                html += '<td>' + itemCount + '</td>';
                html += '<td>' + item.grn_display + '</td>';
                html += '<td>' + item.material_display + '</td>';
                html += '<td>' + item.batch_number + '</td>';
                html += '<td>' + item.rack_number + '</td>';
                html += '<td>' + item.site_location + '</td>';
                html += '<td>' + item.qty + '</td>';
                html += '<td>' + (item.remarks || '-') + '</td>';
                html += '<td class="text-right">';

                if (item.source === 'new') {

                    html += '<button class="btn btn-primary btn-sm mr-1 btn-edit-item"><i class="fas fa-edit"></i></button>';
                    html += '<button class="btn btn-danger btn-sm btn-delete-item"><i class="fas fa-trash"></i></button>';
                } else {

                    html += '<button class="btn btn-primary btn-sm mr-1 btn-edit-item" data-allocation-id="' + item.allocation_id + '"><i class="fas fa-edit"></i></button>';
                    html += '<button class="btn btn-danger btn-sm btn-remove-item"><i class="fas fa-times"></i></button>';
                }

                html += '</td>';
                html += '</tr>';
            });

            $('#allocationList').html(html);


            updateSubmitButton();

            if (allItems.length > 0) {
                $('#tblallocate caption').remove();
                $('#tblallocate').prepend('<caption>Total Items: ' + itemCount + ' | Total Qty: ' + totalQty.toFixed(2) + '</caption>');
            } else {
                $('#tblallocate caption').remove();
            }
        }

        function updateSubmitButton() {
            var totalItems = allocationItems.length + pendingAllocationItems.length;

            if (totalItems > 0) {
                $('#submitAllBtn').prop('disabled', false);

                if (pendingAllocationItems.length > 0) {

                    $('#submitAllBtn').html('<i class="fas fa-save"></i>&nbsp;Update All');
                } else {

                    $('#submitAllBtn').html('<i class="fas fa-check"></i>&nbsp;Submit Allocations');
                }
            } else {
                $('#submitAllBtn').prop('disabled', true);
                $('#submitAllBtn').html('<i class="fas fa-check"></i>&nbsp;Submit Allocations');
            }
        }


        $(document).on('click', '.btn-edit-item', function () {
            var row = $(this).closest('tr');
            var index = row.data('index');
            var source = row.data('source');
            var allocationId = $(this).data('allocation-id');


            var item;
            if (source === 'new') {
                item = allocationItems[index];
            } else {

                var pendingIndex = index - allocationItems.length;
                item = pendingAllocationItems[pendingIndex];
            }

            if (!item) return;


            isEditMode = true;
            currentEditIndex = index;
            currentEditSource = source;
            currentEditAllocationId = allocationId || null;


            $('#grnnumber').val(item.grn_id).trigger('change').prop('disabled', true);


            var materialSelect = $('#materialname');
            materialSelect.empty();
            materialSelect.append('<option value="' + item.material_id + '" selected>' + item.material_display + '</option>');
            materialSelect.prop('disabled', false);


            if (source === 'new') {
                $.ajax({
                    url: '<?php echo base_url(); ?>SortingAllocate/get_batch_locations/' + item.material_id + '/' + item.grn_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        var options = '<option value="">Select Batch & Location</option>';
                        $.each(data, function (key, batchItem) {
                            var selected = (batchItem.idtbl_batchstock == item.batch_id) ? 'selected' : '';
                            options += '<option value="' + batchItem.idtbl_batchstock + '" ' + selected +
                                ' data-batch="' + batchItem.batchnumber + '" ' +
                                ' data-rack-id="' + batchItem.rack_id + '" ' +
                                ' data-rack-number="' + batchItem.rack_number + '" ' +
                                ' data-site-id="' + batchItem.site_location_id + '" ' +
                                ' data-site-name="' + batchItem.location_name + '" ' +
                                ' data-qty="' + batchItem.balanceqty + '">';

                            options += batchItem.batchnumber + ' – ' +
                                (batchItem.location_name || '—') + ' – ' +
                                (batchItem.rack_number || '—') +
                                ' (Avail: ' + batchItem.balanceqty + ')</option>';
                        });
                        $('#batchlocation').html(options).prop('disabled', false).trigger('change');

                        // Very important: trigger change so .change() handler runs
                        $('#batchlocation').val(item.batch_id).trigger('change');

                        $('#qty').val(item.qty);
                        $('#remarks').val(item.remarks || '');

                        updateUIForEditMode();
                        // No need to call updateAvailableQty() again — trigger('change') already did it
                    }
                });
            } else {  // pending item
                $('#batchlocation').html(
                    '<option value="' + item.batch_id + '" selected ' +
                    'data-batch="' + item.batch_number + '" ' +
                    'data-rack-id="' + (item.rack_id || '') + '" ' +
                    'data-rack-number="' + (item.rack_number || '') + '" ' +
                    'data-site-id="' + (item.site_location_id || '') + '" ' +
                    'data-site-name="' + (item.site_location || item.locationname || '') + '" ' +
                    'data-qty="' + item.available_qty + '">' +
                    item.batch_number + ' - ' + (item.site_location || item.locationname || '—') +
                    ' (Available: ' + item.available_qty + ')</option>'
                ).prop('disabled', true).trigger('change');

                $('#qty').val(item.qty);
                $('#remarks').val(item.remarks || '');

                // Critical: update these two variables
                selectedBatchId = item.batch_id;
                selectedBatchQty = parseFloat(item.available_qty) || 0;
                $('#availableqty').val(selectedBatchQty);

                updateUIForEditMode();
                validateQty();
            }
        });

        function updateUIForEditMode() {
            $('#addBtn').hide();
            $('#updateBtn').show();
            $('#cancelEditBtn').show();
            $('#submitAllBtn').show();
            updateSubmitButton();
        }

        function updateAvailableQty() {
            var selectedOption = $('#batchlocation option:selected');
            if (selectedOption.val()) {
                selectedBatchId = selectedOption.val();
                selectedBatchQty = parseFloat(selectedOption.data('qty'));
                $('#availableqty').val(selectedBatchQty);
            }
        }


        $('#updateBtn').click(function () {
            if (!validateAllocationForm()) {
                return;
            }
            if (!validateQty()) return;

            var grnNumber = $('#grnnumber option:selected').text();
            var materialSelect = $('#materialname option:selected');
            var materialId = materialSelect.val();
            var materialName = materialSelect.text();
            var batchLocation = $('#batchlocation option:selected');
            var batchNumber = batchLocation.data('batch');
            var location = batchLocation.data('location');
            var locationname = batchLocation.data('locationname');
            var qty = parseFloat($('#qty').val());
            var remarks = $('#remarks').val();

            var updatedItem = {
                id: Date.now(),
                source: currentEditSource,
                grn_id: $('#grnnumber').val(),
                material_id: materialId,
                batch_id: selectedBatchId,
                batch_number: batchNumber,
                rack_id: parseInt(batchLocation.data('rack-id')) || null,
                site_location_id: parseInt(batchLocation.data('site-id')) || null,
                rack_number: batchLocation.data('rack-number') || '-',   // ← add this
                site_location: batchLocation.data('site-name') || '-',
                qty: qty,
                remarks: remarks,
                grn_display: grnNumber,
                material_display: materialName
            };
            console.log(updatedItem);


            if (currentEditSource === 'new') {

                updatedItem.id = allocationItems[currentEditIndex].id;
                allocationItems[currentEditIndex] = updatedItem;


                showMessage('Item updated successfully!', 'success');

            } else {

                var pendingIndex = currentEditIndex - allocationItems.length;
                if (pendingIndex >= 0) {
                    updatedItem.allocation_id = currentEditAllocationId;
                    updatedItem.available_qty = pendingAllocationItems[pendingIndex].available_qty;
                    pendingAllocationItems[pendingIndex] = updatedItem;


                    showMessage('Pending allocation updated in local list! Click "Update All" to save changes to database.', 'info');
                }
            }


            renderAllocationList();
            resetEditMode();
        });

        function showMessage(message, type) {

            // Remove any existing alert (optional, prevents stacking)
            $('.custom-alert').remove();

            var alertHtml =
                '<div class="alert alert-' + type + ' alert-dismissible fade show position-fixed custom-alert" ' +
                'role="alert" ' +
                'style="top:20px; left:50%; transform:translateX(-50%); z-index:9999; min-width:300px;">' +
                message +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>';

            // Add to page
            $('body').append(alertHtml);

            // Auto close after 5 seconds
            setTimeout(function () {
                $('.custom-alert').alert('close');
            }, 5000);
        }



        $('#cancelEditBtn').click(function () {
            resetEditMode();
        });

        function resetEditMode() {
            isEditMode = false;
            currentEditIndex = -1;
            currentEditSource = '';
            currentEditAllocationId = null;

            $('#addBtn').show();
            $('#updateBtn').hide();
            $('#cancelEditBtn').hide();
            $('#submitAllBtn').show();

            $('#grnnumber').prop('disabled', false);
            $('#materialname').prop('disabled', true);
            $('#batchlocation').prop('disabled', true);

            updateSubmitButton();
            resetForm();
        }


        $(document).on('click', '.btn-delete-item', function () {
            var row = $(this).closest('tr');
            var index = row.data('index');
            var source = row.data('source');

            if (source === 'new') {
                if (confirm('Are you sure you want to remove this item?')) {
                    allocationItems.splice(index, 1);
                    if (currentEditIndex === index && currentEditSource === 'new') {
                        resetEditMode();
                    }
                    renderAllocationList();
                }
            }
        });


        $(document).on('click', '.btn-remove-item', function () {
            var row = $(this).closest('tr');
            var index = row.data('index');
            var source = row.data('source');

            if (source === 'pending') {
                var pendingIndex = index - allocationItems.length;
                if (pendingIndex >= 0) {
                    if (confirm('Remove this pending allocation from edit list?')) {
                        pendingAllocationItems.splice(pendingIndex, 1);
                        if (currentEditIndex === index && currentEditSource === 'pending') {
                            resetEditMode();
                        }
                        renderAllocationList();
                    }
                }
            }
        });


        $('#submitAllBtn').click(function () {
            if (allocationItems.length === 0 && pendingAllocationItems.length === 0) {
                alert('No items to update!');
                return;
            }

            var action = pendingAllocationItems.length > 0 ? 'Update' : 'Submit';
            var totalItems = allocationItems.length + pendingAllocationItems.length;

            if (!confirm(action + ' ' + totalItems + ' item(s)?')) return;


            if (allocationItems.length > 0) {
                $.ajax({
                    url: '<?php echo base_url(); ?>SortingAllocate/save_allocation',
                    type: 'POST',
                    dataType: 'json',
                    data: { items: allocationItems },
                    success: function (response) {
                        if (response.success) {
                            allocationItems = [];
                            showMessage('New allocations submitted successfully!', 'success');
                            renderAllocationList();
                            pendingTable.ajax.reload();
                        } else {
                            showMessage('Error: ' + response.message, 'danger');
                        }
                    },
                    error: function () {
                        showMessage('Error submitting allocations', 'danger');
                    }
                });
            }


            if (pendingAllocationItems.length > 0) {
                updateAllPendingItems();
            }
        });

        function updateAllPendingItems() {
            var updatePromises = [];

            $.each(pendingAllocationItems, function (index, item) {
                var promise = $.ajax({
                    url: '<?php echo base_url(); ?>SortingAllocate/update_allocation/' + item.allocation_id,
                    type: 'POST',
                    data: {
                        qty: item.qty,
                        remarks: item.remarks
                    },
                    dataType: 'json'
                });
                updatePromises.push(promise);
            });


            $.when.apply($, updatePromises).then(function () {

                pendingAllocationItems = [];
                renderAllocationList();
                pendingTable.ajax.reload();
                showMessage('All pending allocations updated successfully!', 'success');
            }).fail(function () {
                showMessage('Some updates failed. Please check and try again.', 'danger');
            });
        }

        function resetForm() {
            $('#qty').val('');
            $('#remarks').val('');
            $('#qtyError').text('');
            $('#availableqty').val('');
            $('#batchlocation').prop('disabled', true).html('<option value="">Select Material First</option>').trigger('change');
            $('#materialname').prop('disabled', true).html('<option value="">Select GRN First</option>').trigger('change');
            $('#grnnumber').val('').trigger('change').prop('disabled', false);
            selectedBatchId = null;
            selectedBatchQty = 0;
        }


        var pendingTable = $('#allocatepending').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo base_url(); ?>scripts/allocationlist.php?status=1",
                "type": "POST"
            },
            "drawCallback": function () {
                var api = this.api();
                var pendingCount = api.rows({ search: 'applied' }).data().length; // rows currently shown
                console.log(pendingCount);
                toggleAddButtonBasedOnPending(pendingCount);
            }

            ,


            "columns": [
                { "data": "idtbl_allocation" },
                {
                    "data": "grn_number",
                    "render": function (data) {
                        return 'GRN - ' + data;
                    }
                },
                { "data": "material_name" },
                { "data": "batchnumber" },
                { "data": "rack_number" },
                { "data": "site_location" },
                { "data": "qty" },
                {
                    "data": "insertdatetime",
                    "render": function (data) {
                        return data ? data.split(' ')[0] : '';
                    }
                },
                {
                    "data": null,
                    "className": 'text-right',
                    "render": function (data, type, row) {
                        var buttons = '';
                        if (editcheck == 1) {
                            buttons += '<button class="btn btn-success btn-sm mr-1 btn-approve" data-id="' + row.idtbl_allocation + '"><i class="fas fa-check"></i></button>';
                            buttons += '<button class="btn btn-primary btn-sm mr-1 btn-add-to-edit" data-id="' + row.idtbl_allocation + '"><i class="fas fa-edit"></i></button>';
                        }
                        if (deletecheck == 1) {
                            // buttons += '<button class="btn btn-danger btn-sm mr-1 btn-reject" data-id="' + row.idtbl_allocation + '"><i class="fas fa-times"></i></button>';
                            buttons += '<button class="btn btn-danger btn-sm btn-delete-pending" data-id="' + row.idtbl_allocation + '"><i class="fas fa-trash"></i></button>';
                        }
                        return buttons;
                    }
                }
            ]
        });


        // Initialize DataTables for history

        var historyTable = $('#allocatehistory').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo base_url(); ?>scripts/allocationlist.php?status=2",
                "type": "POST"
            },
            "columns": [
                { "data": "idtbl_allocation" },
                {
                    "data": "grn_number",
                    "render": function (data) {
                        return 'GRN - ' + data;
                    }
                },
                { "data": "material_name" },
                { "data": "batchnumber" },
                { "data": "rack_number" },
                { "data": "site_location" },
                { "data": "qty" },
                {
                    "data": "status",
                    "render": function (data) {
                        if (data == 2) return '<span class="badge badge-success">Approved</span>';
                        if (data == 3) return '<span class="badge badge-danger">Rejected</span>';
                        return '<span class="badge badge-warning">Pending</span>';
                    }
                },
                {
                    "data": "sorting_complete",
                    "render": function (data, type, row) {
                        var badgeClass = (data == 1) ? 'badge-success' : 'badge-warning';
                        var text = (data == 1) ? 'Complete' : 'Pending';
                        return '<span class="badge ' + badgeClass + '">' + text + '</span>';
                    }
                },
                {
                    "data": "insertdatetime",
                    "render": function (data) {
                        return data ? data.split(' ')[0] : '';
                    }
                },
                {
                    "data": null,
                    "className": 'text-center',
                    "render": function (data, type, row) {
                        var btnClass = (row.sorting_complete == 1) ? 'btn-warning' : 'btn-success';
                        var btnIcon = (row.sorting_complete == 1) ? 'fas fa-times' : 'fas fa-check';
                        var btnText = (row.sorting_complete == 1) ? 'Mark Incomplete' : 'Mark Complete';

                        return '<button class="btn btn-sm ' + btnClass + ' btn-toggle-sorting" data-id="' + row.idtbl_allocation + '" data-status="' + row.sorting_complete + '">' +
                            '<i class="' + btnIcon + '"></i> ' + btnText +
                            '</button>';
                    }
                }
            ]
        });


        $(document).on('click', '.btn-toggle-sorting', function () {
            var allocationId = $(this).data('id');
            var currentStatus = $(this).data('status');
            var actionText = (currentStatus == 1) ? 'mark as incomplete' : 'mark as complete';

            if (confirm('Are you sure you want to ' + actionText + '?')) {
                $.ajax({
                    url: '<?php echo base_url(); ?>SortingAllocate/update_sorting_complete/' + allocationId,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {

                            historyTable.ajax.reload();


                            showMessage('Sorting status updated successfully!', 'success');
                        } else {
                            showMessage('Error: ' + response.message, 'danger');
                        }
                    },
                    error: function () {
                        showMessage('Error updating sorting status', 'danger');
                    }
                });
            }
        });


        $(document).on('click', '.btn-add-to-edit', function () {
            var allocationId = $(this).data('id');


            var exists = pendingAllocationItems.find(function (item) {
                return item.allocation_id == allocationId;
            });

            if (exists) {
                showMessage('This allocation is already in the edit list.', 'warning');
                return;
            }


            $.ajax({
                url: '<?php echo base_url(); ?>SortingAllocate/get_allocation_details/' + allocationId,
                type: 'GET',
                dataType: 'json',
                success: function (allocation) {
                    if (allocation) {

                        $.ajax({
                            url: '<?php echo base_url(); ?>SortingAllocate/get_batch_info/' + allocation.batch_id,
                            type: 'GET',
                            dataType: 'json',
                            success: function (batchInfo) {

                                var pendingItem = {
                                    id: Date.now(),
                                    source: 'pending',
                                    allocation_id: allocation.idtbl_allocation,
                                    grn_id: allocation.grn_id,
                                    material_id: allocation.material_id,
                                    batch_id: allocation.batch_id,
                                    batch_number: allocation.batch_number,

                                    rack_id: allocation.rack_id,
                                    site_location_id: allocation.site_location_id,
                                    rack_number: batchInfo.rack_number || '—',
                                    site_location: batchInfo.site_location_name || '—',
                                    qty: allocation.qty,
                                    remarks: allocation.remarks,
                                    available_qty: batchInfo.balanceqty,
                                    grn_display: 'GRN - ' + allocation.grn_id,
                                    material_display: allocation.material_name || 'Material #' + allocation.material_id
                                };

                                pendingAllocationItems.push(pendingItem);
                                renderAllocationList();

                                showMessage('Allocation added to edit list. You can now edit it in the table above.', 'success');
                            }
                        });
                    }
                }
            });
        });

        // Approve allocation
        $(document).on('click', '.btn-approve', function () {
            var allocationId = $(this).data('id');
            if (confirm('Approve this allocation?')) {
                $.ajax({
                    url: '<?php echo base_url(); ?>SortingAllocate/approve_allocation/' + allocationId,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        // showMessage(response.messagem,response.success);
                        // alert(response.message);
                        if (response.success) {
                            showMessage('Allocation approved successfully', 'success');
                            pendingAllocationItems = pendingAllocationItems.filter(function (item) {
                                return item.allocation_id != allocationId;
                            });
                            renderAllocationList();
                            pendingTable.ajax.reload();
                            historyTable.ajax.reload();
                        }
                    }
                });
            }
        });

        // Reject allocation
        $(document).on('click', '.btn-reject', function () {
            var allocationId = $(this).data('id');
            $('#rejectAllocationId').val(allocationId);
            $('#rejectModal').modal('show');
        });


        $('#confirmRejectBtn').click(function () {
            var allocationId = $('#rejectAllocationId').val();
            var reason = $('#rejectReason').val();

            $.ajax({
                url: '<?php echo base_url(); ?>SortingAllocate/reject_allocation/' + allocationId,
                type: 'POST',
                data: { reject_reason: reason },
                dataType: 'json',
                success: function (response) {
                    alert(response.message);
                    if (response.success) {
                        $('#rejectModal').modal('hide');
                        $('#rejectReason').val('');

                        pendingAllocationItems = pendingAllocationItems.filter(function (item) {
                            return item.allocation_id != allocationId;
                        });
                        renderAllocationList();
                        pendingTable.ajax.reload();
                        historyTable.ajax.reload();
                    }
                }
            });
        });

        // Delete pending allocation from database
        $(document).on('click', '.btn-delete-pending', function () {
            var allocationId = $(this).data('id');
            if (confirm('Delete this allocation?')) {
                $.ajax({
                    url: '<?php echo base_url(); ?>SortingAllocate/delete_allocation/' + allocationId,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        alert(response.message);
                        if (response.success) {

                            pendingAllocationItems = pendingAllocationItems.filter(function (item) {
                                return item.allocation_id != allocationId;
                            });
                            renderAllocationList();
                            pendingTable.ajax.reload();
                        }
                    }
                });
            }
        });


        function loadPendingAllocations() {
            pendingTable.ajax.reload();
        }
    });
</script>
<?php include "include/footer.php"; ?>
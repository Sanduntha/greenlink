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
			<div id="msgBox" class="alert alert-danger alert-dismissible fade d-none" role="alert">
				<span id="msgText"></span>
				<button type="button" class="close" onclick="hideMessage()">
					<span>&times;</span>
				</button>
			</div>
			<div class="page-header page-header-light bg-white shadow">
				<div class="container-fluid">
					<div class="page-header-content py-3">
						<h1 class="page-header-title font-weight-light">
							<div class="page-header-icon"><i class="fas fa-truck"></i></div>
							<span>Supplier Purchase Order</span>
						</h1>
					</div>
				</div>
			</div>

			<div class="container-fluid mt-2 p-0 p-2">
				<div class="card">
					<div class="card-body p-2">
						<div class="row mb-4">
							<div class="col-12">
								<h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2">
									<i class="fas fa-file-alt mr-2"></i>Create Purchase Order
								</h6>
								<h5 class="font-weight-bold mb-3"></h5>
								<form id="createporderform" autocomplete="off">

									<div class="row">
										<div class="col-md-3">
											<div class="form-group mb-2">
												<label class="small font-weight-bold text-dark">PO Number*</label>
												<input type="text" class="form-control form-control-sm" name="ponumber"
													id="ponumber" readonly>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group mb-2">
												<label class="small font-weight-bold text-dark">PO Date*</label>
												<input type="date" class="form-control form-control-sm" name="orderdate"
													id="orderdate" value="<?php echo date('Y-m-d') ?>" required>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group mb-2">
												<label class="small font-weight-bold text-dark">Supplier*</label>
												<select class="form-control form-control-sm" name="supplier"
													id="supplier" required>
													<option value="">Select Supplier</option>
													<?php foreach ($supplierlist->result() as $supplier) { ?>
														<option value="<?php echo $supplier->idtbl_supplier ?>">
															<?php echo htmlspecialchars($supplier->name) ?>
														</option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group mb-2">
												<label class="small font-weight-bold text-dark">Payment Terms*</label>
												<select class="form-control form-control-sm" name="paymentterms"
													id="paymentterms" required>
													<option value="">Select</option>
													<option value="COD">Cash on Delivery</option>
													<option value="NET30">Net 30</option>
													<option value="NET60">Net 60</option>
													<option value="ADVANCE">Advance Payment</option>
												</select>
											</div>
										</div>
									</div>

									<!-- Row 2: Supplier Address, Warehouse Location, Expected Delivery Date -->
									<div class="row">
										<div class="col-md-4">
											<div class="form-group mb-2">
												<label class="small font-weight-bold text-dark">Supplier
													Address*</label>
												<textarea class="form-control form-control-sm" name="supplieraddress"
													id="supplieraddress" rows="2" readonly></textarea>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group mb-2">
												<label class="small font-weight-bold text-dark">Warehouse
													Location*</label>
												<select class="form-control form-control-sm" name="warehouselocation"
													id="warehouselocation" required>
													<option value="">Select Location</option>
													<?php foreach ($locations as $location): ?>
														<option value="<?php echo $location['locationid']; ?>">
															<?php echo htmlspecialchars($location['locationname']); ?>
														</option>
													<?php endforeach; ?>
												</select>
												<input type="hidden" name="deliverylocationid" id="deliverylocationid">
												<input type="hidden" name="deliverylocation" id="deliverylocation">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group mb-2">
												<label class="small font-weight-bold text-dark">Expected Delivery
													Date*</label>
												<input type="date" class="form-control form-control-sm"
													name="expecteddeliverydate" id="expecteddeliverydate" required>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group mb-2">
												<label class="small font-weight-bold text-dark">Remarks</label>
												<textarea class="form-control form-control-sm" name="remarks"
													id="remarks" rows="2"></textarea>
											</div>
										</div>
									</div>

									<!-- Items Section -->
									<hr>
									<div class="row">
										<div class="col-12">
											<h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2">
												<i class="fas fa-file-alt mr-2"></i>Order Items
											</h6>
											<h6 class="font-weight-bold"></h6>
										</div>
									</div>

									<div class="row">
										<div class="col-md-3">
											<div class="form-group mb-2">
												<label class="small font-weight-bold text-dark">Material Name*</label>
												<select class="form-control form-control-sm" name="material"
													id="material" required>
													<option value="">Select Supplier First</option>
												</select>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group mb-2">
												<label class="small font-weight-bold text-dark">Material Code*</label>
												<input type="text" class="form-control form-control-sm"
													name="materialcode" id="materialcode" readonly>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group mb-2">
												<label class="small font-weight-bold text-dark">UOM*</label>
												<input type="text" class="form-control form-control-sm"
													name="unitofmeasure" id="unitofmeasure" readonly>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group mb-2">
												<label class="small font-weight-bold text-dark">Quantity*</label>
												<input type="number" step="0.01" class="form-control form-control-sm"
													name="qty" id="qty" required>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group mb-2">
												<label class="small font-weight-bold text-dark">Unit Price*</label>
												<input type="number" step="0.01" class="form-control form-control-sm"
													name="unitprice" id="unitprice" required>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-2">
											<div class="form-group mb-2">
												<label class="small font-weight-bold text-dark">Discount %</label>
												<input type="number" step="0.01" class="form-control form-control-sm"
													name="discount" id="discount" value="0" min="0" max="100">
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group mb-2">
												<label class="small font-weight-bold text-dark">Tax Amount</label>
												<input type="number" step="0.01" class="form-control form-control-sm"
													name="taxamount" id="taxamount" value="0" readonly>
											</div>
										</div>


										<div class="col-md-8">
											<div class="form-group text-right" style="margin-top: 30px;">
												<button type="button" id="formsubmit"
													class="btn btn-primary btn-sm px-4">
													<i class="fas fa-plus"></i> Add to List
												</button>
												<button type="button" id="updateitembutton"
													class="btn btn-warning btn-sm px-4 d-none">
													<i class="fas fa-sync"></i> Update
												</button>
												<button type="button" id="cancelEditButton"
													class="btn btn-secondary btn-sm px-4 d-none">
													<i class="fas fa-times"></i> Cancel
												</button>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>

						<hr>

						<!-- Order Details Table -->
						<div class="row mb-4">
							<div class="col-12">
								<h6 class="font-weight-bold text-primary mb-3 border-bottom pb-2">
									<i class="fas fa-file-alt mr-2"></i>Order Details
								</h6>

								<div class="table-responsive">
									<table class="table table-striped table-bordered table-sm" id="tblporderdetails">
										<thead>
											<tr>
												<th>Supplier</th>
												<th>Material</th>
												<th>Unit Price</th>
												<th>Qty</th>
												<th>UOM</th>
												<th>Discount</th>
												<th>Tax</th>
												<th>Total</th>
												<th>Action</th>
												<th class="d-none">ProductId</th>
												<th class="d-none">MaterialCode</th>
												<th class="d-none">UOM</th>
												<th class="d-none">TotalValue</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>

								<div class="row mb-3">
									<div class="col text-right">
										<h5 class="font-weight-bold">Total: <span id="divtotal">Rs. 0.00</span></h5>
									</div>
									<input type="hidden" id="hidetotalorder" value="0">
								</div>

								<div class="form-group text-right">
									<button type="button" id="btncreateporder" class="btn btn-outline-primary btn-sm">
										<i class="fas fa-save"></i> Create PO
									</button>
								</div>
							</div>
						</div>

						<hr>

						<!-- Purchase Orders History Table -->
						<div class="row">
							<div class="col-12">
								<h5 class="font-weight-bold mb-3">Purchase Orders History</h5>
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-sm" id="dataTable">
										<thead>
											<tr>
												<th>#</th>
												<th>PO Number</th>
												<th>PO Date</th>
												<th>Name</th>
												<th>Location</th>
												<th>Total</th>
												<th>Status</th>
												<th>Remark</th>
												<th class="text-right">Actions</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</main>
		<?php include "include/footerbar.php"; ?>
	</div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="jobviewmodal" data-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Purchase Order Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="viewhtml"></div>
			</div>
		</div>
	</div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="editModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editModalLabel">Edit Purchase Order</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="editporderform" autocomplete="off">
					<input type="hidden" id="edit_order_id">

					<div class="row">
						<div class="col-md-3">
							<div class="form-group mb-2">
								<label class="small font-weight-bold text-dark">PO Number</label>
								<input type="text" class="form-control form-control-sm" id="edit_ponumber" readonly>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group mb-2">
								<label class="small font-weight-bold text-dark">PO Date*</label>
								<input type="date" class="form-control form-control-sm" id="edit_orderdate" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group mb-2">
								<label class="small font-weight-bold text-dark">Supplier*</label>
								<select class="form-control form-control-sm" id="edit_supplier" required>
									<option value="">Select Supplier</option>
									<?php foreach ($supplierlist->result() as $supplier) { ?>
										<option value="<?php echo $supplier->idtbl_supplier ?>">
											<?php echo htmlspecialchars($supplier->name) ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group mb-2">
								<label class="small font-weight-bold text-dark">Payment Terms*</label>
								<select class="form-control form-control-sm" id="edit_paymentterms" required>
									<option value="">Select</option>
									<option value="COD">Cash on Delivery</option>
									<option value="NET30">Net 30</option>
									<option value="NET60">Net 60</option>
									<option value="ADVANCE">Advance Payment</option>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group mb-2">
								<label class="small font-weight-bold text-dark">Supplier Address*</label>
								<textarea class="form-control form-control-sm" id="edit_supplieraddress" rows="2"
									readonly></textarea>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group mb-2">
								<label class="small font-weight-bold text-dark">Warehouse Location*</label>
								<select class="form-control form-control-sm" id="edit_warehouselocation" required>
									<option value="">Select Location</option>
									<?php foreach ($locations as $location): ?>
										<option value="<?php echo $location['locationid']; ?>">
											<?php echo htmlspecialchars($location['locationname']); ?>
										</option>
									<?php endforeach; ?>
								</select>
								<input type="hidden" id="edit_deliverylocationid">
								<input type="hidden" id="edit_deliverylocation">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group mb-2">
								<label class="small font-weight-bold text-dark">Expected Delivery Date*</label>
								<input type="date" class="form-control form-control-sm" id="edit_expecteddeliverydate"
									required>
							</div>
						</div>
					</div>

					<hr>
					<div class="row">
						<div class="col-12">
							<h6 class="font-weight-bold">Order Items</h6>
						</div>
					</div>

					<div class="row">
						<div class="col-md-3">
							<div class="form-group mb-2">
								<label class="small font-weight-bold text-dark">Material Name*</label>
								<select class="form-control form-control-sm" id="edit_material" required>
									<option value="">Select Material</option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group mb-2">
								<label class="small font-weight-bold text-dark">Material Code*</label>
								<input type="text" class="form-control form-control-sm" id="edit_materialcode" readonly>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group mb-2">
								<label class="small font-weight-bold text-dark">UOM*</label>
								<input type="text" class="form-control form-control-sm" id="edit_unitofmeasure"
									readonly>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group mb-2">
								<label class="small font-weight-bold text-dark">Quantity*</label>
								<input type="number" step="0.01" class="form-control form-control-sm" id="edit_qty"
									required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group mb-2">
								<label class="small font-weight-bold text-dark">Unit Price*</label>
								<input type="number" step="0.01" class="form-control form-control-sm"
									id="edit_unitprice" required>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-2">
							<div class="form-group mb-2">
								<label class="small font-weight-bold text-dark">Discount %</label>
								<input type="number" step="0.01" class="form-control form-control-sm" id="edit_discount"
									value="0" min="0" max="100">
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group mb-2">
								<label class="small font-weight-bold text-dark">Tax Amount</label>
								<input type="number" step="0.01" class="form-control form-control-sm"
									id="edit_taxamount" value="0" readonly>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group mb-2">
								<label class="small font-weight-bold text-dark">Remarks</label>
								<textarea class="form-control form-control-sm" id="edit_remarks" rows="2"></textarea>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group text-right" style="margin-top: 30px;">
								<button type="button" id="edit_formsubmit" class="btn btn-primary btn-sm px-4">
									<i class="fas fa-plus"></i> Add to List
								</button>
								<button type="button" id="edit_updateitembutton"
									class="btn btn-warning btn-sm px-4 d-none">
									<i class="fas fa-sync"></i> Update
								</button>
								<button type="button" id="edit_cancelEditButton"
									class="btn btn-secondary btn-sm px-4 d-none">
									<i class="fas fa-times"></i> Cancel
								</button>
							</div>
						</div>
					</div>

					<hr>
					<div class="row">
						<div class="col-12">
							<h6 class="font-weight-bold">Order Items List</h6>
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-sm" id="tbleditporderdetails">
									<thead>
										<tr>
											<th>Supplier</th>
											<th>Material</th>
											<th>Unit Price</th>
											<th>Qty</th>
											<th>UOM</th>
											<th>Discount</th>
											<th>Tax</th>
											<th>Total</th>
											<th>Actions</th>
											<th class="d-none">ProductId</th>
											<th class="d-none">MaterialCode</th>
											<th class="d-none">TotalValue</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>

							<div class="row mb-3">
								<div class="col text-right">
									<h5 class="font-weight-bold">Total: <span id="edit_divtotal">Rs. 0.00</span></h5>
								</div>
								<input type="hidden" id="edit_hidetotalorder" value="0">
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" id="btneditporder" class="btn btn-primary">
					<i class="fas fa-save"></i> Update Order
				</button>
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



		initializeDataTable();


		generatePONumber();


		$('#supplier').change(function () {
			let supplierId = $(this).val();
			handleSupplierChange(supplierId);
		});


		$('#warehouselocation').on('change', function () {
			let locationText = $('#warehouselocation option:selected').text();
			let locationId = $(this).val();
			if (locationId) {
				$('#deliverylocation').val(locationText);
				$('#deliverylocationid').val(locationId);
			}
		});


		$('#edit_warehouselocation').on('change', function () {
			let locationText = $('#edit_warehouselocation option:selected').text();
			let locationId = $(this).val();
			if (locationId) {
				$('#edit_deliverylocation').val(locationText);
				$('#edit_deliverylocationid').val(locationId);
			}
		});


		$('#material').change(function () {
			let materialId = $(this).val();
			if (materialId) {
				getMaterialDetails(materialId, 'create');
			}
		});


		$('#edit_material').change(function () {
			let materialId = $(this).val();
			if (materialId) {
				getMaterialDetails(materialId, 'edit');
			}
		});


		$('#unitprice, #qty, #discount').on('input', function () {
			calculateTaxAndTotal();
		});

		$('#edit_unitprice, #edit_qty, #edit_discount').on('input', function () {
			calculateEditTaxAndTotal();
		});


		$('#formsubmit').click(function () {
			if (!validateAddItem('create')) return;
			addItemToTable('create');
		});

		$('#btncreateporder').click(function () {
			if (!validatePOForm('create')) return;
			createPurchaseOrder();
		});

		$('#btneditporder').click(function () {
			if (!validatePOForm('edit')) return;
			updatePurchaseOrder();
		});

		$('#edit_updateitembutton').click(function () {
			if (!validateAddItem('edit')) return;
			addItemToTable('edit');
		});


		$(document).on('click', '.btn-remove-item', function () {
			removeItemFromTable(this);
		});


		$(document).on('click', '.btndelete', function () {
			let id = $(this).data('id');
			let confirmDelete = confirm("Are you sure you want to delete this purchase order?");
			if (confirmDelete) {
				deletePurchaseOrder(id);
			}
		});




		$(document).on('click', '.btnview', function () {
			let id = $(this).data('id');
			viewOrderDetails(id);
		});


		$(document).on('click', '.btnedit', function () {
			let id = $(this).data('id');
			let confirmEdit = confirm("Are you sure you want to edit this purchase order?");
			if (confirmEdit) {
				loadOrderForEdit(id);
			}
		});


		$('#edit_supplier').change(function () {
			let supplierId = $(this).val();
			if (supplierId) {
				loadMaterialsForEdit(supplierId);
				getSupplierAddressForEdit(supplierId);
			}
		});

		$(document).on('click', '.btn-edit-item-edit', function () {
			editItemFromEditTable(this);
		});

		$(document).on('click', '.btn-remove-item-edit', function () {
			removeItemFromEditTable(this);
		});


		$('#edit_unitprice, #edit_qty, #edit_discount').on('input', function () {
			calculateEditTaxAndTotal();
		});


		$('#edit_formsubmit').click(function () {
			if (!validateAddItem('edit')) return;
			addItemToTable('edit');
		});

		$('#updateitembutton').click(function () {
			if (!validateAddItem('create')) {
				return;
			}
			addItemToTable('create');
		});


		$('#edit_cancelEditButton').click(function () {
			resetEditFormButtons();
			$('#tbleditporderdetails tbody tr.editing').removeClass('editing table-warning');
			clearItemForm('edit');
		});
		$(document).on('input change', '.form-control', function () {
			let fieldId = $(this).attr('id');
			if (fieldId) {
				$('#' + fieldId).removeClass('is-invalid');
				$('#' + fieldId).next('.invalid-feedback').remove();
			}
		});





	});
	function clearAllErrors() {
		$('.form-control').removeClass('is-invalid');
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
		clearAllErrors();

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

			$('html, body').animate({
				scrollTop: firstErrorField.offset().top - 150
			}, 500);
		}

		return !hasError;
	}

	function initializeDataTable() {
		$('#dataTable').DataTable({
			"destroy": true,
			"processing": true,
			"serverSide": true,
			dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			responsive: true,
			lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
			"buttons": [
				{
					extend: 'csv',
					className: 'btn btn-success btn-sm',
					title: 'Purchase Order Information'
				},
				{
					extend: 'pdf',
					className: 'btn btn-danger btn-sm',
					title: 'Purchase Order Information'
				},
				{
					extend: 'print',
					className: 'btn btn-primary btn-sm',
					title: 'Purchase Order Information'
				}
			],
			ajax: {
				url: "<?php echo base_url() ?>scripts/porderlist.php",
				type: "POST"
			},
			"order": [[1, "desc"]],
			"columns": [

				{
					data: null,
					title: "#",
					orderable: false,
					searchable: false,
					className: "text-center",
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},
				{
					data: "ponumber",
					title: "PO Number"
				},
				{
					data: "podate",
					title: "PO Date",
					render: function (data) {
						return new Date(data).toLocaleDateString('en-US', {
							year: 'numeric', month: 'short', day: 'numeric'
						});
					}
				},
				{
					data: "suppliername",
					title: "Supplier Name"
				},
				{
					data: "location",
					title: "location"
				},
				{
					data: "total",
					title: "Total",
					className: "text-right",
					render: function (data, type, full) {
						return 'Rs. ' + parseFloat(full.total).toFixed(2);
					}
				},
				{
					data: "remarks",
					title: "Remarks"
				},
				{
					"data": null,
					"title": "Status",
					"className": 'text-center',
					"orderable": false,
					"render": function (data, type, full) {
						if (full['confirmedstatus'] == 0) {
							return '<span class="badge badge-warning">Pending</span>';
						} else {
							return '<span class="badge badge-success">Confirmed</span>';
						}
					}
				},
				{
					"data": null,
					"title": "Actions",
					"className": 'text-right',
					"orderable": false,
					"render": function (data, type, full) {
						var buttons = '';
						buttons += '<button class="btn btn-primary btn-sm btnview mr-1" data-id="' + full['idtbl_porder'] + '" title="View"><i class="fas fa-eye"></i></button>';

						if (full['confirmedstatus'] == 0) {
							buttons += '<button class="btn btn-primary btn-sm btnedit mr-1" data-id="' + full['idtbl_porder'] + '" title="Edit"><i class="fas fa-pen"></i></button>';
							buttons += '<button class="btn btn-danger btn-sm btndelete mr-1" data-id="' + full['idtbl_porder'] + '" title="Delete"><i class="fas fa-trash"></i></button>';
						}


						if (full['confirmedstatus'] == 0) {
							buttons += '<a href="<?php echo base_url() ?>Purchaseorder/ConfirmPorder/' + full['idtbl_porder'] + '" onclick="return confirm_po()" class="btn btn-success btn-sm mr-1" title="Confirm"><i class="fas fa-check"></i></a>';
						}


						return buttons;
					}
				}
			]
		});
	}
	function generatePONumber() {
		$.ajax({
			url: '<?php echo base_url(); ?>Purchaseorder/GeneratePONumber',
			type: 'GET',
			success: function (response) {
				let data = JSON.parse(response);
				$('#ponumber').val(data.ponumber);
			}
		});
	}

	function handleSupplierChange(supplierId) {
		if (supplierId) {
			$.ajax({
				url: '<?php echo base_url(); ?>Purchaseorder/GetSupplierAddress',
				type: 'POST',
				data: { supplier_id: supplierId },
				success: function (response) {
					let address = JSON.parse(response);
					let addressParts = [];
					if (address.address_line1) addressParts.push(address.address_line1);
					if (address.address_line2) addressParts.push(address.address_line2);
					if (address.city) addressParts.push(address.city);
					if (address.state) addressParts.push(address.state);
					$('#supplieraddress').val(addressParts.join(', '));

					loadMaterials(supplierId);


					if ($('#tblporderdetails tbody tr').length > 0) {
						let existingSupplier = $('#tblporderdetails tbody tr:first td:first').text();
						let selectedSupplier = $('#supplier option:selected').text();
						if (existingSupplier !== selectedSupplier) {
							showMessage('You can only add items from the same supplier', 'warning');
							$('#supplier').val('');
							$('#supplieraddress').val('');
							$('#material').empty().append('<option value="">Select Supplier First</option>');
						}
					}
				}
			});
		} else {
			$('#supplieraddress').val('');
			$('#material').empty().append('<option value="">Select Supplier First</option>');
		}
	}

	function loadMaterials(supplierId) {
		$.ajax({
			url: '<?php echo base_url(); ?>Purchaseorder/GetMaterialsBySupplier',
			type: 'POST',
			data: { supplier_id: supplierId },
			success: function (response) {
				let materials = JSON.parse(response);
				$('#material').empty().append('<option value="">Select Material</option>');
				$.each(materials, function (index, material) {
					$('#material').append('<option value="' + material.idtbl_row_material + '">' + material.material_name + '</option>');
				});
			}
		});
	}

	function loadMaterialsForEdit(supplierId) {
		$.ajax({
			url: '<?php echo base_url(); ?>Purchaseorder/GetMaterialsBySupplier',
			type: 'POST',
			data: { supplier_id: supplierId },
			success: function (response) {
				let materials = JSON.parse(response);
				$('#edit_material').empty().append('<option value="">Select Material</option>');
				$.each(materials, function (index, material) {
					$('#edit_material').append('<option value="' + material.idtbl_row_material + '">' + material.material_name + '</option>');
				});
			}
		});
	}

	function getMaterialDetails(materialId, mode) {
		$.ajax({
			url: '<?php echo base_url(); ?>Purchaseorder/GetMaterialDetails',
			type: 'POST',
			data: { material_id: materialId },
			success: function (response) {
				let material = JSON.parse(response);
				if (mode === 'create') {
					$('#materialcode').val(material.material_code || '');
					$('#unitofmeasure').val(material.unit_of_measure || '');
					$('#unitprice').val(material.unitprice || '');
				} else {
					$('#edit_materialcode').val(material.material_code || '');
					$('#edit_unitofmeasure').val(material.unit_of_measure || '');
					$('#edit_unitprice').val(material.unitprice || '');
				}
			}
		});
	}

	function getSupplierAddressForEdit(supplierId) {
		$.ajax({
			url: '<?php echo base_url(); ?>Purchaseorder/GetSupplierAddress',
			type: 'POST',
			data: { supplier_id: supplierId },
			success: function (response) {
				let address = JSON.parse(response);
				let addressParts = [];
				if (address.address_line1) addressParts.push(address.address_line1);
				if (address.address_line2) addressParts.push(address.address_line2);
				if (address.city) addressParts.push(address.city);
				if (address.state) addressParts.push(address.state);
				$('#edit_supplieraddress').val(addressParts.join(', '));
			}
		});
	}


	function validateAddItem(mode) {
		let prefix = mode === 'edit' ? 'edit_' : '';

		let fields = [
			{ id: prefix + 'material', label: 'Material Name' },
			{ id: prefix + 'qty', label: 'Quantity', numeric: true },
			{ id: prefix + 'unitprice', label: 'Unit Price', numeric: true }
		];


		if (mode === 'create') {
			fields = fields.concat([
				{ id: 'orderdate', label: 'PO Date' },
				{ id: 'supplier', label: 'Supplier' },
				{ id: 'paymentterms', label: 'Payment Terms' },
				{ id: 'warehouselocation', label: 'Warehouse Location' },
				{ id: 'expecteddeliverydate', label: 'Expected Delivery Date' }
			]);
		}

		return validateFields(fields);
	}


	function validatePOForm(mode) {
		let prefix = mode === 'edit' ? 'edit_' : '';

		let headerFields = [
			{ id: prefix + 'orderdate', label: 'PO Date' },
			{ id: prefix + 'supplier', label: 'Supplier' },
			{ id: prefix + 'paymentterms', label: 'Payment Terms' },
			{ id: prefix + 'warehouselocation', label: 'Warehouse Location' },
			{ id: prefix + 'expecteddeliverydate', label: 'Expected Delivery Date' }
		];

		if (!validateFields(headerFields)) {
			return false;
		}


		let tableId = mode === 'create' ? '#tblporderdetails' : '#tbleditporderdetails';
		let rowCount = $(tableId + ' tbody tr').length;

		if (rowCount === 0) {
			clearAllErrors();
			let totalRow = mode === 'create'
				? $('#hidetotalorder').closest('.row.mb-3')
				: $('#edit_hidetotalorder').closest('.row.mb-3');

			totalRow.after(
				'<div class="alert alert-danger no-items-error col-12 mt-3">' +
				'<i class="fas fa-exclamation-triangle mr-2"></i>' +
				'Please add at least one item to the order!' +
				'</div>'
			);


			$('html, body').animate({
				scrollTop: $('.no-items-error').offset().top - 150
			}, 500);

			return false;
		}

		return true;
	}

	function calculateTaxAndTotal() {

		let isUpdateMode = !$('#updateitembutton').hasClass('d-none');

		if (isUpdateMode) {
			let unitprice = parseFloat($('#unitprice').val()) || 0;
			let qty = parseFloat($('#qty').val()) || 0;
			let discount = parseFloat($('#discount').val()) || 0;

			let subtotal = unitprice * qty;
			let discountAmount = (subtotal * discount) / 100;
			let taxAmount = 0;


			let netTotal = subtotal - discountAmount + taxAmount;


			$('#taxamount').val(taxAmount.toFixed(2));


			$('#updateitembutton').html('<i class="fas fa-sync"></i> Update (Rs. ' + netTotal.toFixed(2) + ')');
		} else {
			let unitprice = parseFloat($('#unitprice').val()) || 0;
			let qty = parseFloat($('#qty').val()) || 0;
			let discount = parseFloat($('#discount').val()) || 0;

			let subtotal = unitprice * qty;
			let discountAmount = (subtotal * discount) / 100;
			let taxAmount = 0;
			$('#taxamount').val(taxAmount.toFixed(2));
		}
	}

	function calculateEditTaxAndTotal() {
		let unitprice = parseFloat($('#edit_unitprice').val()) || 0;
		let qty = parseFloat($('#edit_qty').val()) || 0;
		let discount = parseFloat($('#edit_discount').val()) || 0;

		let subtotal = unitprice * qty;
		let discountAmount = (subtotal * discount) / 100;
		let taxAmount = 0;
		$('#edit_taxamount').val(taxAmount.toFixed(2));
	}



	function addItemToTable(mode) {
		let supplierText, materialId, materialText, materialCode, unitOfMeasure, unitPrice, qty, discount, taxAmount, supplierId;

		if (mode === 'create') {
			supplierText = $("#supplier option:selected").text();
			supplierId = $('#supplier').val();
			materialId = $('#material').val();
			materialText = $("#material option:selected").text();
			materialCode = $('#materialcode').val();
			unitOfMeasure = $('#unitofmeasure').val();
			unitPrice = parseFloat($('#unitprice').val()) || 0;
			qty = parseFloat($('#qty').val()) || 0;
			discount = parseFloat($('#discount').val()) || 0;
			taxAmount = parseFloat($('#taxamount').val()) || 0;
		} else {
			supplierText = $("#edit_supplier option:selected").text();
			supplierId = $('#edit_supplier').val();
			materialId = $('#edit_material').val();
			materialText = $("#edit_material option:selected").text();
			materialCode = $('#edit_materialcode').val();
			unitOfMeasure = $('#edit_unitofmeasure').val();
			unitPrice = parseFloat($('#edit_unitprice').val()) || 0;
			qty = parseFloat($('#edit_qty').val()) || 0;
			discount = parseFloat($('#edit_discount').val()) || 0;
			taxAmount = parseFloat($('#edit_taxamount').val()) || 0;
		}


		let subtotal = unitPrice * qty;
		let discountAmount = (subtotal * discount) / 100;
		let netTotal = subtotal - discountAmount + taxAmount;


		if (mode === 'create') {
			let editingRow = $('#tblporderdetails tbody tr.editing');
			if (editingRow.length > 0) {
				updateExistingRow(editingRow, supplierText, materialText, unitPrice, qty, unitOfMeasure, discount, taxAmount, materialId, materialCode, netTotal, mode);
				return;
			}
		}


		if (mode === 'edit') {
			let editingRow = $('#tbleditporderdetails tbody tr.editing');
			if (editingRow.length > 0) {
				updateExistingRow(editingRow, supplierText, materialText, unitPrice, qty, unitOfMeasure, discount, taxAmount, materialId, materialCode, netTotal, mode);
				return;
			}
		}


		let tableId = mode === 'create' ? '#tblporderdetails' : '#tbleditporderdetails';
		let exists = false;
		$(tableId + " tbody tr").each(function () {
			if ($(this).data('material-id') === materialId) {
				exists = true;
				return false;
			}
		});

		if (exists) {
			showMessage("This material is already in the list!", 'warning');
			return;
		}


		let row = `
	<tr data-material-id="${materialId}" 
		data-total="${netTotal}" 
		data-material-code="${materialCode}"
		data-unit-of-measure="${unitOfMeasure}">
		<td>${supplierText}</td>
		<td>${materialText}</td>
		<td class="text-right">Rs. ${unitPrice.toFixed(2)}</td>
		<td class="text-right">${qty.toFixed(2)}</td>
		<td class="text-right">${unitOfMeasure}</td>
		<td class="text-right">${discount.toFixed(2)}%</td>
		<td class="text-right">Rs. ${taxAmount.toFixed(2)}</td>
		<td class="text-right font-weight-bold">Rs. ${netTotal.toFixed(2)}</td>
		<td class="text-center">
			<button type="button" class="btn btn-sm btn-info btn-edit-item${mode === 'edit' ? '-edit' : ''} mr-1" title="Edit">
				<i class="fas fa-edit"></i>
			</button>
			<button type="button" class="btn btn-sm btn-danger btn-remove-item${mode === 'edit' ? '-edit' : ''}" title="Remove">
				<i class="fas fa-trash"></i>
			</button>
		</td>
		<td class="d-none">${materialId}</td>
		<td class="d-none">${materialCode}</td>
		<td class="d-none">${netTotal}</td>
	</tr>
	`;

		$(tableId + " tbody").append(row);
		updateTotal(mode);
		clearItemForm(mode);
	}

	function updateExistingRow(row, supplierText, materialText, unitPrice, qty, unitOfMeasure, discount, taxAmount, materialId, materialCode, netTotal, mode) {
		row.find('td:eq(0)').text(supplierText);
		row.find('td:eq(1)').text(materialText);
		row.find('td:eq(2)').text('Rs. ' + unitPrice.toFixed(2));
		row.find('td:eq(3)').text(qty.toFixed(2));
		row.find('td:eq(4)').text(unitOfMeasure);
		row.find('td:eq(5)').text(discount.toFixed(2) + '%');
		row.find('td:eq(6)').text('Rs. ' + taxAmount.toFixed(2));
		row.find('td:eq(7)').text('Rs. ' + netTotal.toFixed(2));


		row.find('td.d-none:eq(0)').text(materialId);
		row.find('td.d-none:eq(1)').text(materialCode);
		row.find('td.d-none:eq(2)').text(netTotal);


		row.attr('data-material-id', materialId);
		row.attr('data-total', netTotal);
		row.attr('data-material-code', materialCode);
		row.attr('data-unit-of-measure', unitOfMeasure);

		row.removeClass('editing table-warning');

		if (mode === 'create') {
			resetFormButtons();
		} else {
			resetEditFormButtons();
		}

		updateTotal(mode);
	}


	$(document).on('click', '.btn-edit-item', function () {
		editItemFromTable(this);
	});

	function editItemFromTable(element) {
		let row = $(element).closest('tr');
		let supplierText = row.find('td:eq(0)').text();
		let materialText = row.find('td:eq(1)').text();
		let unitPrice = row.find('td:eq(2)').text().replace('Rs. ', '');
		let qty = row.find('td:eq(3)').text();
		let unitOfMeasure = row.find('td:eq(4)').text();
		let discount = row.find('td:eq(5)').text().replace('%', '');
		let taxAmount = row.find('td:eq(6)').text().replace('Rs. ', '');
		let materialId = row.find('td.d-none:eq(0)').text();
		let materialCode = row.find('td.d-none:eq(1)').text();
		let totalValue = row.find('td.d-none:eq(3)').text();


		$('#material').val(materialId);
		$('#materialcode').val(materialCode);
		$('#unitofmeasure').val(unitOfMeasure);
		$('#unitprice').val(unitPrice);
		$('#qty').val(qty);
		$('#discount').val(discount);
		$('#taxamount').val(taxAmount);


		$('#qty').focus();


		$('#formsubmit').addClass('d-none');
		$('#updateitembutton').removeClass('d-none');
		$('#cancelEditButton').removeClass('d-none');


		row.addClass('editing table-warning');
	}


	$('#cancelEditButton').click(function () {
		resetFormButtons();
		$('#tblporderdetails tbody tr.editing').removeClass('editing table-warning');
		clearItemForm('create');
	});


	$('#updateitembutton').click(function () {
		if (!validateAddItem('create')) return;
		addItemToTable('create');
	});

	function resetFormButtons() {
		$('#formsubmit').removeClass('d-none');
		$('#updateitembutton').addClass('d-none');
		$('#cancelEditButton').addClass('d-none');
	}

	function clearItemForm(mode) {
		if (mode === 'create') {
			$('#material').val('').focus();
			$('#materialcode').val('');
			$('#unitofmeasure').val('');
			$('#unitprice').val('');
			$('#qty').val('');
			$('#discount').val('0');
			$('#taxamount').val('0');
			resetFormButtons();
		} else {
			$('#edit_material').val('').focus();
			$('#edit_materialcode').val('');
			$('#edit_unitofmeasure').val('');
			$('#edit_unitprice').val('');
			$('#edit_qty').val('');
			$('#edit_discount').val('0');
			$('#edit_taxamount').val('0');
			resetEditFormButtons();
		}
	}

	function removeItemFromTable(element) {
		if (confirm("Are you sure you want to remove this item?")) {
			let row = $(element).closest('tr');
			let tableId = row.closest('#tbleditporderdetails').length > 0 ? 'edit' : 'create';
			row.remove();
			updateTotal(tableId);


			if (row.hasClass('editing')) {
				resetFormButtons();
				clearItemForm('create');
			}
		}
	}

	function updateTotal(mode) {
		let tableId = mode === 'create' ? '#tblporderdetails' : '#tbleditporderdetails';
		let totalDivId = mode === 'create' ? '#divtotal' : '#edit_divtotal';
		let hiddenTotalId = mode === 'create' ? '#hidetotalorder' : '#edit_hidetotalorder';

		let sum = 0;
		$(tableId + " tbody tr").each(function () {
			let totalText = $(this).find('td:eq(7)').text().replace('Rs. ', '');
			sum += parseFloat(totalText) || 0;
		});

		let formattedTotal = addCommas(sum.toFixed(2));
		$(totalDivId).html('Rs. ' + formattedTotal);
		$(hiddenTotalId).val(sum);
	}


	function createPurchaseOrder() {

		$('#btncreateporder')
			.prop('disabled', true)
			.html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Creating...');

		let jsonObj = [];

		$("#tblporderdetails tbody tr").each(function (index) {

			let materialId = $(this).data('material-id');
			let materialCode = $(this).data('material-code');
			let unitOfMeasure = $(this).data('unit-of-measure');
			let netTotal = $(this).data('total');


			if (!materialId) {
				materialId = $(this).find('td.d-none:eq(0)').text();
			}
			if (!materialCode) {
				materialCode = $(this).find('td.d-none:eq(1)').text();
			}
			if (!unitOfMeasure) {
				unitOfMeasure = $(this).find('td:eq(4)').text();
			}
			if (!netTotal) {
				netTotal = $(this).find('td.d-none:eq(2)').text();
			}

			let rowData = {
				materialid: materialId,
				materialcode: materialCode,
				unitofmeasure: unitOfMeasure,
				unitprice: $(this).find('td:eq(2)').text().replace('Rs. ', ''),
				qty: $(this).find('td:eq(3)').text(),
				discount: $(this).find('td:eq(5)').text().replace('%', ''),
				taxamount: $(this).find('td:eq(6)').text().replace('Rs. ', ''),
				total: netTotal
			};

			console.log(`Row ${index + 1} data:`, rowData);
			jsonObj.push(rowData);
		});

		console.log("Final tableData (jsonObj):", jsonObj);

		let postData = {
			tableData: jsonObj,
			ponumber: $('#ponumber').val(),
			orderdate: $('#orderdate').val(),
			remark: $('#remarks').val(),
			total: $('#hidetotalorder').val(),
			supplier: $('#supplier').val(),
			supplieraddress: $('#supplieraddress').val(),
			deliverylocation: $('#deliverylocation').val(),
			deliverylocationid: $('#deliverylocationid').val(),
			expecteddeliverydate: $('#expecteddeliverydate').val(),
			paymentterms: $('#paymentterms').val()
		};

		$.ajax({
			type: "POST",
			data: postData,
			url: '<?php echo base_url(); ?>Purchaseorder/Porderinsert',
			success: function (result) {
				let obj = JSON.parse(result);
				showNotification(obj.action);

				if (obj.status == 1) {
					setTimeout(function () {
						location.reload();
					}, 3000);
				}

				$('#btncreateporder')
					.prop('disabled', false)
					.html('<i class="fas fa-save"></i> Create PO');
			},
			error: function (xhr, status, error) {
				console.error("AJAX Error:", status, error);
				showMessage('Error creating purchase order', 'danger');
				$('#btncreateporder')
					.prop('disabled', false)
					.html('<i class="fas fa-save"></i> Create PO');
			}
		});
	}




	function loadOrderForEdit(orderId) {
		$.ajax({
			url: '<?php echo base_url(); ?>Purchaseorder/GetPorderDetails/' + orderId,
			type: 'POST',
			success: function (response) {
				let data = JSON.parse(response);
				let order = data.order;
				let items = data.items;


				let locationText = order.delivery_location_text.trim();
				let poDate = order.podate.split(' ')[0];

				$('#edit_order_id').val(order.idtbl_porder);
				$('#edit_ponumber').val(order.ponumber);
				$('#edit_orderdate').val(poDate);
				$('#edit_supplier').val(order.tbl_supplier_idtbl_supplier);
				$('#edit_paymentterms').val(order.payment_terms);
				$('#edit_supplieraddress').val(order.supplier_address);
				$('#edit_warehouselocation').val(order.delivery_location_id);
				$('#edit_deliverylocationid').val(order.delivery_location_id);
				$('#edit_deliverylocation').val(locationText);
				$('#edit_expecteddeliverydate').val(order.expected_delivery_date);
				$('#edit_remarks').val(order.remarks);


				loadMaterialsForEdit(order.tbl_supplier_idtbl_supplier);


				$('#tbleditporderdetails tbody').empty();
				let totalAmount = 0;

				if (items && items.length > 0) {
					$.each(items, function (index, item) {
						let unitOfMeasure = item.unit_of_measure || item.unitofmeasure || '';
						let materialName = item.material_name || '';
						let materialCode = item.material_code || '';
						let totalValue = parseFloat(item.totalvalue).toFixed(2);

						let row = `
					<tr>
						<td>${order.supplier_name}</td>
						<td>${materialName}</td>
						<td class="text-right">Rs. ${parseFloat(item.unitprice).toFixed(2)}</td>
						<td class="text-right">${parseFloat(item.qty).toFixed(2)}</td>
						<td class="text-right">${unitOfMeasure}</td>
						<td class="text-right">${parseFloat(item.discount || 0).toFixed(2)}%</td>
						<td class="text-right">Rs. ${parseFloat(item.tax_amount || 0).toFixed(2)}</td>
						<td class="text-right font-weight-bold">Rs. ${totalValue}</td>
						<td class="text-center">
							<button type="button" class="btn btn-sm btn-info btn-edit-item-edit mr-1" title="Edit">
								<i class="fas fa-edit"></i>
							</button>
							<button type="button" class="btn btn-sm btn-danger btn-remove-item-edit" title="Remove">
								<i class="fas fa-trash"></i>
							</button>
						</td>
						<td class="d-none">${item.tbl_row_material_idtbl_row_material}</td>
						<td class="d-none">${materialCode}</td>
						<td class="d-none">${item.totalvalue}</td>
					</tr>
				`;
						$('#tbleditporderdetails tbody').append(row);
						totalAmount += parseFloat(item.totalvalue);
					});
				}

				let formattedTotal = addCommas(totalAmount.toFixed(2));
				$('#edit_divtotal').html('Rs. ' + formattedTotal);
				$('#edit_hidetotalorder').val(totalAmount);


				$('#editModal').modal('show');
			},
			error: function (xhr) {
				console.error('Error loading order details:', xhr);
				showMessage('Error loading order details', 'danger');
			}
		});
	}

	function updatePurchaseOrder() {

		$('#btneditporder').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Updating...');

		let jsonObj = [];
		$("#tbleditporderdetails tbody tr").each(function () {
			// Use data attributes - most reliable
			let materialId = $(this).data('material-id');
			let materialCode = $(this).data('material-code');
			let unitOfMeasure = $(this).data('unit-of-measure');
			let netTotal = $(this).data('total');


			if (!materialId) {
				materialId = $(this).find('td.d-none:eq(0)').text();
			}
			if (!materialCode) {
				materialCode = $(this).find('td.d-none:eq(1)').text();
			}
			if (!unitOfMeasure) {
				unitOfMeasure = $(this).find('td:eq(4)').text();
			}
			if (!netTotal) {
				netTotal = $(this).find('td.d-none:eq(2)').text();
			}

			jsonObj.push({
				materialid: materialId,
				materialcode: materialCode,
				unitofmeasure: unitOfMeasure,
				unitprice: $(this).find('td:eq(2)').text().replace('Rs. ', ''),
				qty: $(this).find('td:eq(3)').text(),
				discount: $(this).find('td:eq(5)').text().replace('%', ''),
				taxamount: $(this).find('td:eq(6)').text().replace('Rs. ', ''),
				total: netTotal
			});
		});

		$.ajax({
			type: "POST",
			data: {
				tableData: jsonObj,
				order_id: $('#edit_order_id').val(),
				orderdate: $('#edit_orderdate').val(),
				remark: $('#edit_remarks').val(),
				total: $('#edit_hidetotalorder').val(),
				supplier: $('#edit_supplier').val(),
				supplieraddress: $('#edit_supplieraddress').val(),
				deliverylocation: $('#edit_deliverylocation').val(),
				deliverylocationid: $('#edit_deliverylocationid').val(),
				expecteddeliverydate: $('#edit_expecteddeliverydate').val(),
				paymentterms: $('#edit_paymentterms').val()
			},
			url: '<?php echo base_url(); ?>Purchaseorder/Porderupdate',
			success: function (result) {
				let obj = JSON.parse(result);
				if (obj.status == 1) {
					showMessage('Purchase Order Updated Successfully', 'success');
					$('#editModal').modal('hide');
					$('#dataTable').DataTable().ajax.reload();
				} else {
					showMessage(obj.message || 'Error updating order', 'danger');
				}
				$('#btneditporder').prop('disabled', false).html('<i class="fas fa-save"></i> Update Order');
			},
			error: function () {
				showMessage('Error updating purchase order', 'danger');
				$('#btneditporder').prop('disabled', false).html('<i class="fas fa-save"></i> Update Order');
			}
		});
	}

	function deletePurchaseOrder(id) {
		let $deleteBtn = $('#confirmDelete');
		$deleteBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');

		$.ajax({
			url: '<?php echo base_url(); ?>Purchaseorder/Porderdelete/' + id,
			type: 'POST',
			dataType: 'json',
			success: function (result) {
				if (result.status == 1) {

					showMessage(result.message, 'success');
					$('#dataTable').DataTable().ajax.reload();
				} else {
					showMessage(result.message || 'Error deleting order', 'danger');
				}
				$deleteBtn.prop('disabled', false).html('Delete');
			},
			error: function () {
				showMessage('Server error', 'danger');
				$deleteBtn.prop('disabled', false).html('Delete');
			}
		});
	}

	function viewOrderDetails(id) {
		$.ajax({
			type: "POST",
			data: { recordID: id },
			url: '<?php echo base_url(); ?>Purchaseorder/PorderdetailsView',
			success: function (result) {
				$('#jobviewmodal').modal('show');
				$('#viewhtml').html(result);
			},
			error: function () {
				showMessage('Error loading order details', 'danger');
			}
		});
	}

	function showMessage(msg, type = 'danger') {
		clearTimeout(window.msgTimeout);
		$('#msgBox')
			.removeClass('d-none alert-success alert-danger alert-warning')
			.addClass('alert-' + type + ' show')
			.fadeIn(200);
		$('#msgText').text(msg);
		window.msgTimeout = setTimeout(function () {
			hideMessage();
		}, 3000);
	}

	function hideMessage() {
		$('#msgBox').fadeOut(200, function () {
			$(this).addClass('d-none').removeClass('show');
		});
	}

	function showNotification(data) {
		let obj = data;
		if (typeof data === 'string') {
			obj = JSON.parse(data);
		}

		$.notify({
			icon: obj.icon || 'fas fa-info',
			title: obj.title || 'Notification',
			message: obj.message || 'Operation completed',
			url: obj.url || '',
			target: obj.target || '_blank'
		}, {
			element: 'body',
			type: obj.type || 'info',
			allow_dismiss: true,
			placement: { from: "top", align: "center" },
			offset: 100,
			spacing: 10,
			z_index: 1031,
			delay: 4000,
			animate: {
				enter: 'animated fadeInDown',
				exit: 'animated fadeOutUp'
			}
		});
	}

	function addCommas(nStr) {
		nStr += '';
		let x = nStr.split('.');
		let x1 = x[0];
		let x2 = x.length > 1 ? '.' + x[1] : '';
		let rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}

	function editItemFromEditTable(element) {
		let row = $(element).closest('tr');
		let materialText = row.find('td:eq(1)').text();
		let unitPrice = row.find('td:eq(2)').text().replace('Rs. ', '');
		let qty = row.find('td:eq(3)').text();
		let unitOfMeasure = row.find('td:eq(4)').text();
		let discount = row.find('td:eq(5)').text().replace('%', '');
		let taxAmount = row.find('td:eq(6)').text().replace('Rs. ', '');
		let materialId = row.find('td.d-none:eq(0)').text();
		let materialCode = row.find('td.d-none:eq(1)').text();


		$('#edit_material').val(materialId);
		$('#edit_materialcode').val(materialCode);
		$('#edit_unitofmeasure').val(unitOfMeasure);
		$('#edit_unitprice').val(unitPrice);
		$('#edit_qty').val(qty);
		$('#edit_discount').val(discount);
		$('#edit_taxamount').val(taxAmount);


		$('#edit_qty').focus();


		$('#edit_formsubmit').addClass('d-none');
		$('#edit_updateitembutton').removeClass('d-none');
		$('#edit_cancelEditButton').removeClass('d-none');


		row.addClass('editing table-warning');
	}

	function removeItemFromEditTable(element) {
		if (confirm("Are you sure you want to remove this item?")) {
			let row = $(element).closest('tr');


			if (row.hasClass('editing')) {
				resetEditFormButtons();
				clearItemForm('edit');
			}

			row.remove();
			updateTotal('edit');
		}
	}

	function resetEditFormButtons() {
		$('#edit_formsubmit').removeClass('d-none');
		$('#edit_updateitembutton').addClass('d-none');
		$('#edit_cancelEditButton').addClass('d-none');
	}

	function calculateEditTaxAndTotal() {

		let isUpdateMode = !$('#edit_updateitembutton').hasClass('d-none');

		let unitprice = parseFloat($('#edit_unitprice').val()) || 0;
		let qty = parseFloat($('#edit_qty').val()) || 0;
		let discount = parseFloat($('#edit_discount').val()) || 0;

		let subtotal = unitprice * qty;
		let discountAmount = (subtotal * discount) / 100;
		let taxAmount = 0;

		if (isUpdateMode) {

			let netTotal = subtotal - discountAmount + taxAmount;


			$('#edit_updateitembutton').html('<i class="fas fa-sync"></i> Update (Rs. ' + netTotal.toFixed(2) + ')');
		}


		$('#edit_taxamount').val(taxAmount.toFixed(2));
	}
	function confirm_po() {
		return confirm("Are you sure you want to confirm this purchase order?");
	}

</script>
<?php include "include/footer.php"; ?>
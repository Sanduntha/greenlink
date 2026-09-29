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
							<span>Stock Transfer</span>
						</h1>
					</div>
				</div>
			</div>
			<div class="container-fluid mt-2 p-0 p-2">
				<div class="card mb-3">
					<div class="card-header bg-white">
						<h6 class="m-0 font-weight-bold">New Transfer</h6>
					</div>
					<div class="card-body p-0 p-2">
						<div class="row">
							<div class="col-3">
								<form id="transferForm" autocomplete="off">
									<div class="form-group mb-1">
										<label class="small font-weight-bold">From Location*</label>
										<select class="form-control form-control-sm" name="fromlocation"
											id="fromlocation" required>
											<option value="">Select</option>
											<?php foreach ($locations as $location): ?>
												<option value="<?php echo $location['locationid']; ?>">
													<?php echo htmlspecialchars($location['locationname']); ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="form-group mb-1">
										<label class="small font-weight-bold">To Location*</label>
										<select class="form-control form-control-sm" name="tolocation" id="tolocation"
											required>
											<option value="">Select</option>
											<?php foreach ($locations as $location): ?>
												<option value="<?php echo $location['locationid']; ?>">
													<?php echo htmlspecialchars($location['locationname']); ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="form-group mb-1">
										<label class="small font-weight-bold">Product Name*</label>
										<select class="form-control form-control-sm" name="materialname"
											id="materialname" required>
											<option value="">Select Location First</option>
										</select>
									</div>
									<div class="form-group mb-1">
										<label class="small font-weight-bold">Available Qty</label>
										<input type="text" class="form-control form-control-sm" id="available_qty"
											readonly>
									</div>
									<div class="form-group mb-1">
										<label class="small font-weight-bold">Batch No*</label>
										<input type="text" class="form-control form-control-sm" name="batch_no"
											id="batch_no" required>
									</div>
									<div class="form-group mb-1">
										<label class="small font-weight-bold">Transfer Qty*</label>
										<input type="number" class="form-control form-control-sm" name="qty" id="qty"
											required oninput="validateTransferQty(this)">
									</div>
									<div class="form-group mt-2 text-right">
										<button type="button" id="addBtn" class="btn btn-primary btn-sm px-4">
											<i class="fas fa-plus"></i>&nbsp;Add
										</button>
									</div>
								</form>
							</div>
							<div class="col-9">
								<div class="row">
									<div class="scrollbar pb-3" id="style-2">
										<table class="table table-bordered table-striped table-sm nowrap"
											id="stocktransfer">
											<thead>
												<tr>
													<th>#</th>
													<th>From Location</th>
													<th>To Location</th>
													<th>Material</th>
													<th>Batch No</th>
													<th>Qty</th>
													<th class="text-right">Action</th>
												</tr>
											</thead>
											<tbody></tbody>
										</table>
										<form id="transferSubmitForm">
											<div class="form-group mt-2 text-right">
												<button type="submit" id="submitBtn"
													class="btn btn-primary btn-sm px-4">
													<i class="fas fa-exchange-alt"></i>&nbsp;Transfer Stock
												</button>
												<button type="button" id="updateBtn" class="btn btn-warning btn-sm px-4"
													style="display: none;">
													<i class="fas fa-sync"></i>&nbsp;Update Transfer Stock
												</button>
												<button type="button" id="cancelEditBtn"
													class="btn btn-secondary btn-sm px-4" style="display: none;">
													<i class="fas fa-times"></i>&nbsp;Cancel
												</button>
											</div>
										</form>
									</div>
								</div>
								<div class="row">
									<div class="col-12">
										<div class="card mt-3">
											<div class="card-header bg-white">
												<h6 class="m-0 font-weight-bold">Pending transfer</h6>
											</div>
											<div class="card-body">
												<table id="pendingtransfer"
													class="table table-bordered table-striped table-sm nowrap">
													<thead>
														<tr>
															<th>Transfer ID</th>
															<th>Date</th>
															<th>From Location</th>
															<th>To Location</th>
															<th>Action</th>
														</tr>
													</thead>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Transfer History Table -->
				<div class="card">
					<div class="card-header bg-white">
						<h6 class="m-0 font-weight-bold">Transfer History</h6>
					</div>
					<div class="card-body p-0 p-2">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-sm nowrap" id="transferHistory"
								style="width:100%">
								<thead>
									<tr>
										<th>Transfer ID</th>
										<th>Date</th>
										<th>From Location</th>
										<th>To Location</th>
										<th>Material</th>
										<th>Batch No</th>
										<th>Qty</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>

				<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
					aria-hidden="true">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="modalTitle">Transfer Details</h5>
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

		// Initialize DataTable for current transfer items
		var transferTable = $('#stocktransfer').DataTable({
			responsive: true,
			paging: false,
			searching: false,
			info: false,
			columns: [
				{ data: 'index' },
				{ data: 'fromlocation' },
				{ data: 'tolocation' },
				{ data: 'material_name' },
				{ data: 'batch_no' },
				{ data: 'qty', className: 'text-right' },
				{
					data: null,
					className: 'text-right',
					render: function (data, type, row) {
						return '<button class="btn btn-info btn-sm btnEditRow mr-1" data-rowid="' + row.id + '" title="Edit Item"><i class="fas fa-edit"></i></button>' +
							'<button class="btn btn-danger btn-sm btnRemove" data-id="' + row.id + '" title="Remove Item"><i class="fas fa-trash-alt"></i></button>';
					}
				}
			]
		});

		// Initialize DataTable for transfer history
		var historyTable = $('#transferHistory').DataTable({
			"destroy": true,
			"processing": true,
			"serverSide": true,
			dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			responsive: true,
			lengthMenu: [
				[10, 25, 50, -1],
				[10, 25, 50, 'All'],
			],
			"buttons": [
				{
					extend: 'csv',
					className: 'btn btn-success btn-sm',
					title: 'Stock Transfer History',
					text: '<i class="fas fa-file-csv mr-2"></i> CSV'
				},
				{
					extend: 'pdf',
					className: 'btn btn-danger btn-sm',
					title: 'Stock Transfer History',
					text: '<i class="fas fa-file-pdf mr-2"></i> PDF'
				},
				{
					extend: 'print',
					title: 'Stock Transfer History',
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
				url: "<?php echo base_url(); ?>scripts/stocktransferlist.php",
				type: "POST"
			},
			"order": [[0, "desc"]],
			"columns": [
				{ "data": "transfer_id" },
				{
					"data": "transfer_date",
					"render": function (data, type, row) {
						return formatDate(data);
					}
				},
				{ "data": "from_location" },
				{ "data": "to_location" },
				{ "data": "material_name" },
				{ "data": "batch_no" },
				{
					"data": "quantity",
					"className": "text-right"
				},
			],
			drawCallback: function (settings) {
				$('[data-toggle="tooltip"]').tooltip();
			}
		});

		var pendingTable = $('#pendingtransfer').DataTable({
			"destroy": true,
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?php echo base_url(); ?>scripts/pendingtransferlist.php",
				"type": "POST"
			},
			"columns": [
				{ "data": "id" },
				{
					"data": "transfer_date",
					"render": function (data, type, row) {
						return formatDate(data);
					}
				},
				{ "data": "from_location" },
				{ "data": "to_location" },
				{
					"data": null,
					"className": "text-right",
					"render": function (data, type, row) {
						var actions = '';
						if (editcheck) {
							actions += '<button class="btn btn-primary btn-sm btnView mr-1" data-id="' + row.id + '" title="View Details"><i class="fas fa-eye"></i></button>';
							actions += '<button class="btn btn-info btn-sm btnEdit mr-1" data-id="' + row.id + '" title="Edit"><i class="fas fa-edit"></i></button>';
						}
						if (statuscheck) {
							actions += '<button class="btn btn-success btn-sm btnApprove mr-1" data-id="' + row.id + '" title="Approve"><i class="fas fa-check"></i></button>';
							actions += '<button class="btn btn-danger btn-sm btnReject" data-id="' + row.id + '" title="Reject"><i class="fas fa-times"></i></button>';
						}
						return actions;
					}
				}
			],
			"order": [[1, "desc"]]
		});

		function formatDate(dateString) {
			if (!dateString) return '';
			var date = new Date(dateString);
			return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) + ' ' +
				date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
		}

		// Get materials when from location changes
		$('#fromlocation').change(function () {
			var location = $(this).val();
			if (location) {
				$.ajax({
					url: '<?php echo base_url(); ?>Stocktransfer/GetMaterialsByLocation',
					type: 'POST',
					data: { location: location },
					success: function (response) {
						var materials = JSON.parse(response);
						var options = '<option value="">Select Material</option>';
						$.each(materials, function (index, material) {
							options += '<option value="' + material.idtbl_row_material + '">' +
								material.material_name + '</option>';
						});
						$('#materialname').html(options);
						$('#batch_no').val('');
						$('#available_qty').val('');
					}
				});
			}
		});

		$('#materialname').change(function () {
			var materialId = $(this).val();
			var location = $('#fromlocation').val();

			$('#batch_no').val('');
			$('#available_qty').val('');

			if (materialId && location) {
				$.ajax({
					url: '<?php echo base_url(); ?>Stocktransfer/GetBatchesByMaterial',
					type: 'POST',
					data: {
						material_id: materialId,
						location: location
					},
					success: function (response) {
						var result = JSON.parse(response);
						$('#available_qty').val(result[0].qty);
						$('#batch_no').val(result[0].batch_number);
					}
				});
			}
		});

		$('#fromlocation, #tolocation').change(function () {
			var fromLocation = $('#fromlocation').val();
			var toLocation = $('#tolocation').val();

			if (fromLocation && toLocation && fromLocation === toLocation) {
				alert('From Location and To Location cannot be the same!');
				$(this).val('');
			}
		});

		$('#addBtn').click(function () {
			var editingRowId = $('#transferForm').data('editing-row');

			if (editingRowId) {
				updateRowInTable(editingRowId);
			} else {
				var rowCount = pendingTable.data().count();

				if (rowCount > 0) {
					alert("You already have a pending transfer request. Please process it before creating a new one.");
					$('#transferForm')[0].reset();
					$('#transferForm').removeData('editing-row');
					return;
				}

				addNewRowToTable();
			}
		});

		function addNewRowToTable() {
			var fromLocationName = $('#fromlocation option:selected').text();
			var toLocationName = $('#tolocation option:selected').text();
			var fromLocation = $('#fromlocation').val();
			var toLocation = $('#tolocation').val();
			var materialId = $('#materialname').val();
			var materialName = $('#materialname option:selected').text();
			var batchNo = $('#batch_no').val();
			var availableQty = parseFloat($('#available_qty').val()) || 0;
			var qty = parseFloat($('#qty').val()) || 0;

			if (!fromLocation || !toLocation || !materialId || !batchNo || isNaN(qty) || qty <= 0) {
				alert('Please fill all required fields with valid values');
				return;
			}

			if (qty > availableQty) {
				alert('Transfer quantity cannot exceed available quantity');
				return;
			}

			var rowData = {
				id: materialId + '-' + batchNo + '-' + fromLocation,
				index: transferTable.rows().count() + 1,
				fromlocation: fromLocationName,
				from_location: fromLocation,
				tolocation: toLocationName,
				to_location: toLocation,
				material_id: materialId,
				material_name: materialName,
				batch_no: batchNo,
				qty: qty
			};

			// Prevent duplicates
			var existingRow = transferTable.rows().data().toArray().find(function (row) {
				return row.material_id == materialId && row.batch_no == batchNo && row.from_location == fromLocation;
			});

			if (existingRow) {
				var newQty = existingRow.qty + qty;
				if (newQty > availableQty) {
					alert('Total transfer quantity cannot exceed available quantity');
					return;
				}
				existingRow.qty = newQty;
				transferTable.draw(false);
			} else {
				transferTable.row.add(rowData).draw();
			}

			resetAddForm();
		}

		function updateRowInTable(rowId) {
			var fromLocationName = $('#fromlocation option:selected').text();
			var toLocationName = $('#tolocation option:selected').text();
			var fromLocation = $('#fromlocation').val();
			var toLocation = $('#tolocation').val();
			var materialId = $('#materialname').val();
			var materialName = $('#materialname option:selected').text();
			var availableQty = parseFloat($('#available_qty').val()) || 0;
			var qty = parseFloat($('#qty').val()) || 0;
			var batchNo = $('#batch_no').val();

			if (!fromLocation || !toLocation || !materialId || !batchNo || isNaN(qty) || qty <= 0) {
				alert('Please fill all required fields with valid values');
				return;
			}

			if (qty > availableQty) {
				alert('Transfer quantity cannot exceed available quantity');
				return;
			}

			// Update row
			transferTable.rows().every(function () {
				if (this.data().id === rowId) {
					var data = this.data();
					data.fromlocation = fromLocationName;
					data.from_location = fromLocation;
					data.tolocation = toLocationName;
					data.to_location = toLocation;
					data.material_id = materialId;
					data.material_name = materialName;
					data.batch_no = batchNo;
					data.qty = qty;

					this.data(data);
					return false;
				}
			});

			transferTable.draw(false);
			$('#transferForm')[0].reset();
			resetButtonStates();
			alert('Item updated successfully');
		}

		function resetAddForm() {
			if (!$('#transferForm').data('editing-row')) {
				$('#batch_no').val('');
			}
			$('#available_qty').val('');
			$('#qty').val('');
		}

		function resetButtonStates() {
			$('#transferForm').removeData('editing-row');
			$('#addBtn').html('<i class="fas fa-plus"></i>&nbsp;Add').removeClass('btn-warning').addClass('btn-primary');
			$('#cancelEditBtn').hide();
			$('#materialname').html('<option value="">Select Location First</option>');
			$('#batch_no').val('');
			$('#available_qty').val('');
		}

		// Remove item from transfer table
		$('#stocktransfer tbody').on('click', '.btnRemove', function () {
			var rowId = $(this).data('id');
			transferTable.rows().every(function () {
				if (this.data().id === rowId) {
					this.remove().draw();
					return false;
				}
			});

			// Reindex rows
			transferTable.rows().every(function (rowIdx) {
				this.data().index = rowIdx + 1;
			});
			transferTable.draw(false);
		});

		// Edit individual row
		$('#stocktransfer tbody').on('click', '.btnEditRow', function () {
			var rowId = $(this).data('rowid');
			var rowData = null;

			transferTable.rows().every(function () {
				if (this.data().id === rowId) {
					rowData = this.data();
					return false;
				}
			});

			if (rowData) {
				$('#transferForm')[0].reset();
				
				$('#fromlocation').val(rowData.from_location);
				$('#tolocation').val(rowData.to_location);

				// Load materials first
				$.ajax({
					url: '<?php echo base_url(); ?>Stocktransfer/GetMaterialsByLocation',
					type: 'POST',
					data: { location: rowData.from_location },
					success: function (response) {
						var materials = JSON.parse(response);
						var options = '<option value="">Select Material</option>';
						$.each(materials, function (index, material) {
							options += '<option value="' + material.idtbl_row_material + '">' + material.material_name + '</option>';
						});
						$('#materialname').html(options);
						$('#materialname').val(rowData.material_id);
						$('#batch_no').val(rowData.batch_no);
						$('#qty').val(rowData.qty);

						// Get available quantity
						$.ajax({
							url: '<?php echo base_url(); ?>Stocktransfer/GetBatchesByMaterial',
							type: 'POST',
							data: {
								material_id: rowData.material_id,
								location: rowData.from_location
							},
							success: function (response) {
								var result = JSON.parse(response);
								if (result && result.length > 0) {
									$('#available_qty').val(result[0].qty);
								}
							}
						});
					}
				});

				// Change button states
				$('#addBtn').html('<i class="fas fa-sync"></i>&nbsp;Update').removeClass('btn-primary').addClass('btn-warning');
				$('#cancelEditBtn').show();
				$('#transferForm').data('editing-row', rowId);
			}
		});

		// Cancel individual row edit
		$('#cancelEditBtn').click(function () {
			var editingRowId = $('#transferForm').data('editing-row');

			if (editingRowId) {
				$('#transferForm')[0].reset();
				resetButtonStates();
			} else {
				$('#addBtn').show();
				$('#submitBtn').show();
				$('#updateBtn').hide();
				$(this).hide();
				transferTable.clear().draw();
				$('#transferForm')[0].reset();
			}
		});

		// Edit transfer from pending table
		$('#pendingtransfer tbody').on('click', '.btnEdit', function () {
			var transferId = $(this).data('id');

			// Clear current form and table
			transferTable.clear().draw();
			$('#transferForm')[0].reset();
			$('#cancelEditBtn').show();

			$('#updateBtn').show().data('transfer-id', transferId);
			$('#submitBtn').hide();

			// Load transfer details
			$.ajax({
				url: '<?php echo base_url(); ?>Stocktransfer/GetTransferDetails',
				type: 'POST',
				data: { transfer_id: transferId },
				success: function (response) {
					var details = JSON.parse(response);

					// Add all items to transfer table
					$.each(details.regular, function (index, item) {
						var rowData = {
							id: item.material_id + '-' + item.batch_no + '-' + item.from_location,
							index: transferTable.rows().count() + 1,
							fromlocation: item.from_location_name || item.from_location,
							from_location: item.from_location,
							tolocation: item.to_location_name || item.to_location,
							to_location: item.to_location,
							material_id: item.material_id,
							material_name: item.material_name,
							batch_no: item.batch_no,
							qty: item.quantity
						};
						transferTable.row.add(rowData).draw();
					});

					// Set form values from first item
					if (details.regular.length > 0) {
						var firstItem = details.regular[0];
						$('#fromlocation').val(firstItem.from_location);
						$('#tolocation').val(firstItem.to_location);
					}
				}
			});
		});

		// Update transfer
		$('#updateBtn').click(function () {
			var transferId = $(this).data('transfer-id');

			if (transferTable.rows().count() === 0) {
				alert('Please add at least one item to transfer');
				return;
			}

			if (!confirm('Are you sure you want to update this transfer?')) {
				return;
			}

			var transferData = [];
			transferTable.rows().every(function () {
				transferData.push(this.data());
			});

			$.ajax({
				url: '<?php echo base_url(); ?>Stocktransfer/UpdateTransfer',
				type: 'POST',
				data: {
					transfer_id: transferId,
					items: JSON.stringify(transferData)
				},
				success: function (response) {
					try {
						var result = JSON.parse(response);
						alert(result.message);
						if (result.status) {
							$('#addBtn').show();
							$('#submitBtn').show();
							$('#updateBtn').hide();
							$('#cancelEditBtn').hide();
							transferTable.clear().draw();
							$('#transferForm')[0].reset();
							pendingTable.ajax.reload();
						}
					} catch (e) {
						console.error('Invalid JSON from server:', response);
						alert('Server error. Check console.');
					}
				}
			});
		});

		// View transfer details
		$('#pendingtransfer tbody').on('click', '.btnView', function () {
			var transferId = $(this).data('id');

			$.ajax({
				url: '<?php echo base_url(); ?>Stocktransfer/GetTransferDetails',
				type: 'POST',
				data: { transfer_id: transferId },
				success: function (response) {
					var details = JSON.parse(response);
					var modalContent = '<div class="container-fluid">';

					// Regular items
					if (details.regular.length > 0) {
						modalContent += '<h6>Transfer Items</h6>';
						modalContent += '<table class="table table-bordered table-sm">';
						modalContent += '<thead><tr><th>Material</th><th>Batch No</th><th>From Location</th><th>To Location</th><th>Qty</th></tr></thead>';
						modalContent += '<tbody>';
						$.each(details.regular, function (index, item) {
							modalContent += '<tr>';
							modalContent += '<td>' + item.material_name + '</td>';
							modalContent += '<td>' + item.batch_no + '</td>';
							modalContent += '<td>' + item.from_location + '</td>';
							modalContent += '<td>' + item.to_location + '</td>';
							modalContent += '<td class="text-right">' + item.quantity + '</td>';
							modalContent += '</tr>';
						});
						modalContent += '</tbody></table>';
					}

					modalContent += '</div>';

					$('#modalTitle').text('Transfer Details #' + transferId);
					$('#modalBody').html(modalContent);
					$('#detailsModal').modal('show');
				}
			});
		});

		// Approve transfer
		$('#pendingtransfer tbody').on('click', '.btnApprove', function () {
			var transferId = $(this).data('id');

			if (!confirm('Are you sure you want to approve this transfer?')) {
				return;
			}

			$.ajax({
				url: '<?php echo base_url(); ?>Stocktransfer/ApproveTransfer',
				type: 'POST',
				data: { transfer_id: transferId },
				success: function (response) {
					var result = JSON.parse(response);
					alert(result.message);
					if (result.status) {
						pendingTable.ajax.reload();
						historyTable.ajax.reload();
					}
				}
			});
		});

		// Reject transfer
		$('#pendingtransfer tbody').on('click', '.btnReject', function () {
			var transferId = $(this).data('id');

			if (!confirm('Are you sure you want to reject this transfer?')) {
				return;
			}

			$.ajax({
				url: '<?php echo base_url(); ?>Stocktransfer/RejectTransfer',
				type: 'POST',
				data: { transfer_id: transferId },
				success: function (response) {
					var result = JSON.parse(response);
					alert(result.message);
					if (result.status) {
						pendingTable.ajax.reload();
					}
				}
			});
		});

		// Submit transfer
		$('#transferSubmitForm').submit(function (e) {
			e.preventDefault();

			if (transferTable.rows().count() === 0) {
				alert('Please add at least one item to transfer');
				return;
			}

			if (!confirm('Are you sure you want to submit this transfer for approval?')) {
				return;
			}

			var transferData = {
				items: transferTable.rows().data().toArray()
			};

			$.ajax({
				url: '<?php echo base_url(); ?>Stocktransfer/ProcessTransfer',
				type: 'POST',
				data: transferData,
				success: function (response) {
					var result = JSON.parse(response);
					alert(result.message);
					if (result.status) {
						transferTable.clear().draw();
						$('#transferForm')[0].reset();
						pendingTable.ajax.reload();
					}
				},
				error: function () {
					alert('Error processing transfer');
				}
			});
		});

		// Validation function
		function validateTransferQty(input) {
			var availableQty = parseFloat($('#available_qty').val()) || 0;
			var transferQty = parseFloat($(input).val()) || 0;

			if (transferQty > availableQty) {
				alert('Transfer quantity cannot exceed available quantity!');
				$(input).val(availableQty);
			}

			if (transferQty <= 0) {
				$(input).val('');
			}
		}

		window.validateTransferQty = validateTransferQty;
	});
</script>
<?php include "include/footer.php"; ?>
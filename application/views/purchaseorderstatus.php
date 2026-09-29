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
							<span>Supplier Purchase Order Status</span>
						</h1>
					</div>
				</div>
			</div>
			<div class="container-fluid mt-2 p-0 p-2">
				<div class="card">
					<div class="card-body p-0 p-2">
						<div class="row">
							<div class="col-12">
								<div class="scrollbar pb-3" id="style-2">
									<table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
										<thead>
											<tr>
												<th>#</th>
												<th>Po date</th>
												<th>Supplier</th>
												<th>Total</th>
												<th>Status</th>
												<th>Remarks</th>
												<th class="text-right">Actions</th>
											</tr>
										</thead>
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

<div class="modal fade" id="jobviewmodal" data-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Purchase Order Status Details - #<span
						id="porderId"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row mb-3">
					<div class="col-md-6">
						<strong>Supplier:</strong> <span id="supplierName"></span>
					</div>
					<div class="col-md-6">
						<strong>Date:</strong> <span id="porderDate"></span>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-md-6">
						<strong>Total Amount:</strong> <span id="porderTotal"></span>
					</div>
					<div class="col-md-6">
						<strong>Status:</strong> <span id="porderStatus"></span>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-12">
						<strong>Remarks:</strong> <span id="porderRemarks"></span>
					</div>
				</div>
				<hr>
				<h6>Order Items</h6>
				<div class="table-responsive">
					<table class="table table-bordered table-sm">
						<thead>
							<tr>
								<th>Material Name</th>
								<th>Requested Qty</th>
								<th>Received Qty</th>
								<th>Unit Price</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody id="orderItems">
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

		$('#dataTable').DataTable({
			"destroy": true,
			"processing": true,
			"serverSide": true,
			dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			responsive: true,
			lengthMenu: [
				[10, 25, 50, -1],
				[10, 25, 50, 'All'],
			],
			"buttons": [{
				extend: 'csv',
				className: 'btn btn-success btn-sm',
				title: 'Purchase Order Information',
				text: '<i class="fas fa-file-csv mr-2"></i> CSV',
			},
			{
				extend: 'pdf',
				className: 'btn btn-danger btn-sm',
				title: 'Purchase Order Information',
				text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
			},
			{
				extend: 'print',
				title: 'Purchase Order Information',
				className: 'btn btn-primary btn-sm',
				text: '<i class="fas fa-print mr-2"></i> Print',
				customize: function (win) {
					$(win.document.body).find('table')
						.addClass('compact')
						.css('font-size', 'inherit');
				},
			}],
			ajax: {
				url: "<?php echo base_url() ?>scripts/purchaseorderstatuslist.php",
				type: "POST",
			},
			"order": [
				[0, "desc"]
			],
			"columns": [
				{ "data": "idtbl_porder" },
				{
					"render": function (data, type, full) {
						var dateStr = full['podate'];
						var date = new Date(dateStr);
						var options = { year: 'numeric', month: 'short', day: 'numeric' };
						return date.toLocaleDateString('en-US', options);
					}
				},
				{ "data": "suppliername" },
				{
					"className": 'text-right',
					"render": function (data, type, full) {
						return parseFloat(full['total']).toFixed(2);
					}
				},
				{
					"render": function (data, type, full) {
						if (full['completedstatus'] == 0) {
							return '<span class="badge badge-warning">Order not complete</span>';
						} else {
							return '<span class="badge badge-success">Order complete</span>';
						}
					}
				},
				{
					"data": "remarks",
					"render": function (data, type, row) {
						return data || 'N/A';
					}
				},
				{
					"data": null,
					"className": 'text-right',
					"render": function (data, type, full) {
						var button = '';
						button += '<button class="btn btn-primary btn-sm btnview mr-1" data-id="' + full['idtbl_porder'] + '" title="View Details"><i class="fas fa-eye"></i></button>';

						if (full['completedstatus'] == 1) {
							button += '<a href="<?php echo base_url() ?>Purchaseorderstatus/Notcompleteoder/' +
								full['idtbl_porder'] +
								'/1" onclick="return active_confirm()" target="_self" class="btn btn-success btn-sm mr-1" title="Mark as Not Complete">';
							if (statuscheck != 1) {
								button += ' d-none';
							}
							button += '<i class="fas fa-check"></i></a>';
						} else {
							button += '<a href="<?php echo base_url() ?>Purchaseorderstatus/Completeoder/' +
								full['idtbl_porder'] +
								'/1" onclick="return active_confirm()" target="_self" class="btn btn-warning btn-sm mr-1" title="Complete Order">';
							if (statuscheck != 1) {
								button += ' d-none';
							}
							button += '<i class="fas fa-check-circle"></i></a>';
						}

						return button;
					}
				}
			],
			drawCallback: function (settings) {
				$('[data-toggle="tooltip"]').tooltip();
			}
		});

		// View purchase order details
		$(document).on('click', '.btnview', function () {
			var porderId = $(this).data('id');

			$('#orderItems').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');
			$('#porderId').text(porderId);

			$.ajax({
				url: '<?php echo base_url() ?>Purchaseorderstatus/getPorderDetails/' + porderId,
				type: 'GET',
				dataType: 'json',
				success: function (response) {
					if (response.success) {
						var porder = response.data;

						$('#supplierName').text(porder.suppliername || 'N/A');
						$('#porderDate').text(formatDate(porder.podate));
						$('#porderTotal').text('$' + parseFloat(porder.total).toFixed(2));


						if (porder.completedstatus == 0) {
							$('#porderStatus').html('<span class="badge badge-warning">Order not complete</span>');
						} else {
							$('#porderStatus').html('<span class="badge badge-success">Order complete</span>');
						}

						$('#porderRemarks').text(porder.remarks || 'N/A');

						if (porder.items && porder.items.length > 0) {
							var itemsHtml = '';
							$.each(porder.items, function (index, item) {
								itemsHtml += '<tr>' +
									'<td>' + (item.material_name || 'N/A') + '</td>' +
									'<td>' + (item.qty || '0') + '</td>' +
									'<td>' + (item.received_qty || '0') + '</td>' +
									'<td>$' + parseFloat(item.unitprice || 0).toFixed(2) + '</td>' +
									'<td>$' + parseFloat(item.totalvalue || 0).toFixed(2) + '</td>' +
									'</tr>';
							});
							$('#orderItems').html(itemsHtml);
						} else {
							$('#orderItems').html('<tr><td colspan="5" class="text-center">No items found</td></tr>');
						}

						$('#jobviewmodal').modal('show');
					} else {
						alert('Error loading purchase order details: ' + response.message);
					}
				},
				error: function (xhr, status, error) {
					alert('Error loading purchase order details: ' + error);
				}
			});
		});
	});

	function formatDate(dateStr) {
		if (!dateStr) return 'N/A';
		var date = new Date(dateStr);
		return date.toLocaleDateString('en-US', {
			year: 'numeric',
			month: 'short',
			day: 'numeric'
		});
	}

	function active_confirm() {
		return confirm("Are you sure you want to complete this purchase order?");
	}

	function delete_confirm() {
		return confirm("Are you sure you want to remove this?");
	}
</script>
<?php include "include/footer.php"; ?>
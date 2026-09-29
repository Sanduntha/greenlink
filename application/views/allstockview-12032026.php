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
							<span>All Material Stock View</span>
						</h1>
					</div>
				</div>
			</div>
			<div class="container-fluid mt-2 p-0 p-2">
				<div class="card">
					<div class="card-body p-0 p-2">
						<hr class="border-dark">
						<div class="row">
							<div class="col-12">
								<div class="scrollbar pb-3" id="style-2">
									<table class="table table-bordered table-striped table-sm nowrap display"
										id="stockdatatable" style="width:100%">
										<thead>
											<tr>
												<th>#</th>
												<th>Product Name</th>
												<th>Location</th>
												<th>Rack Number</th>
												<th>Quantity</th>
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
<?php include "include/footerscripts.php"; ?>
<script>
	$(document).ready(function () {
		$('#stockdatatable').DataTable({
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
					title: 'All Stock View',
					text: '<i class="fas fa-file-csv mr-2"></i> CSV',
					exportOptions: {
						columns: ':visible'
					}
				},
				{
					extend: 'pdf',
					className: 'btn btn-danger btn-sm',
					title: 'All Stock View',
					text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
					exportOptions: {
						columns: ':visible'
					}
				},
				{
					extend: 'print',
					title: 'All Stock View',
					className: 'btn btn-primary btn-sm',
					text: '<i class="fas fa-print mr-2"></i> Print',
					exportOptions: {
						columns: ':visible'
					},
					customize: function (win) {
						$(win.document.body).find('table')
							.addClass('compact')
							.css('font-size', 'inherit');
					}
				}
			],
			ajax: {
				url: "scripts/allstockviewlist.php",
				type: "POST",
				error: function (xhr, error, thrown) {
					console.log('AJAX error:', error, thrown);
					console.log('Server response:', xhr.responseText);
				}
			},
			"order": [[0, "desc"]],
			"columns": [
				{
					"data": null,
					"render": function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},
				{ "data": "material_name" },
				{ "data": "location" },
				{ 
					"data": "rack_number",
					"render": function(data, type, row) {
						return data ? data : '<span class="text-muted">Not Assigned</span>';
					}
				},
				{
					"data": "qty",
					"className": "text-right",
					"render": function(data, type, row) {
						return data ? parseFloat(data).toLocaleString('en-US') : '0';
					}
				}
			],
			"language": {
				"emptyTable": "No stock data available",
				"processing": "<i class='fa fa-spinner fa-spin'></i> Loading stock data..."
			},
			drawCallback: function (settings) {
				$('[data-toggle="tooltip"]').tooltip();
			},
			initComplete: function () {
				console.log('DataTable initialized');
			}
		});
	});
</script>
<?php include "include/footer.php"; ?>
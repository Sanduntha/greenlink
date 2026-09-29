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
						<h1 class="page-header-title">
							<div class="page-header-icon"><i class="fas fa-users"></i></div>
							<span>Supplier</span>
						</h1>
					</div>
				</div>
			</div>
			<div class="container-fluid mt-2 p-0 p-2">
				<div class="card">
					<div class="card-body p-0 p-2">

						<form action="<?php echo base_url() ?>Supplier/Supplierinsertupdate" method="post"
							enctype="multipart/form-data" autocomplete="off" novalidate id="supplierForm">
							<div class="row">
								<div class="col-3">
									<div class="form-group mb-1">
										<label class="small font-weight-bold">Registered Name of the Company</label>
										<input type="text" class="form-control form-control-sm" name="supplier_name"
											id="supplier_name">
									</div>
								</div>
								<div class=" col-3 form-group">
									<label class="small font-weight-bold">Supplier Category</label>
									<select class="form-control form-control-sm" name="suppliertype" id="suppliertype">
										<option value="">Select</option>
										<?php foreach ($Suppliercategory->result() as $rowsuppliercategory) { ?>
										<option value="<?php echo $rowsuppliercategory->idtbl_supplier_type ?>">
											<?php echo $rowsuppliercategory->type ?></option>
										<?php } ?>
									</select>
								</div>

								<div class="col-3">
									<div class="form-group mb-1">
										<label class="small font-weight-bold">Business registration No</label>
										<input type="text" class="form-control form-control-sm" name="business_regno"
											id="business_regno">
									</div>
								</div>
								<div class="col-3">
									<div class="form-group mb-1">
										<label class="small font-weight-bold">Submit copy of BR Certificate</label>
										<input type="file" class="form-control form-control-sm" name="image" id="br_certificate">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-2">
									<div class="form-group mb-1">
										<label class="small font-weight-bold">VAT Registration No</label>
										<input type="text" class="form-control form-control-sm" name="vatno" id="vatno">
									</div>
								</div>
								<div class="col-2">
									<div class="form-group mb-1">
										<label class="small font-weight-bold">NBT Registration No</label>
										<input type="text" class="form-control form-control-sm" name="nbtno" id="nbtno">
									</div>
								</div>
								<div class="col-2">
									<div class="form-group mb-1">
										<label class="small font-weight-bold">SVAT Registration No</label>
										<input type="text" class="form-control form-control-sm" name="svatno" id="svatno">
									</div>
								</div>
								<div class="col-2">
									<div class="form-group mb-1">
										<label class="small font-weight-bold">Telephone No</label>
										<input type="number" class="form-control form-control-sm" name="telephoneno"
											id="telephoneno">
									</div>
								</div>
								<div class="col-2 d-none">
									<label class="small font-weight-bold ">Company*</label>
									<input type="text" id="f_company_name" name="f_company_name"
										class="form-control form-control-sm" readonly>
								</div>
								<div class="col-2 d-none">
									<label class="small font-weight-bold ">Company Branch*</label>
									<input type="text" id="f_branch_name" name="f_branch_name"
										class="form-control form-control-sm" readonly>
								</div>
								<input type="hidden" name="f_company_id" id="f_company_id">
								<input type="hidden" name="f_branch_id" id="f_branch_id">
							</div>

							<div class="row">
								<div class="col-3">
									<div class="form-group mb-1">
										<label class="small font-weight-bold">FAX No</label>
										<input type="text" class="form-control form-control-sm" name="faxno" id="faxno">
									</div>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-3">
									<label class="small font-weight-bold"><b>Business Address</b></label>
									<div class="form-group mb-1">
										<label class="small font-weight-bold">Address Line 1</label>
										<input type="text" class="form-control form-control-sm" name="line1" id="line1">
									</div>
								</div>
								<div class="col-2">
								</div>
								<div class="col-3">
									<label class="small font-weight-bold"><b>Delivery Address</b></label>
									<div class="form-group mb-1">
										<label class="small font-weight-bold">Delivery Address Line 1</label>
										<input type="text" class="form-control form-control-sm" name="dline1"
											id="dline1">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-3">
									<div class="form-group mb-1">
										<label class="small font-weight-bold">Address Line 2</label>
										<input type="text" class="form-control form-control-sm" name="line2" id="line2">
									</div>
								</div>
								<div class="col-2">
								</div>
								<div class="col-3">
									<div class="form-group mb-1">
										<label class="small font-weight-bold">Delivery Address Line 2</label>
										<input type="text" class="form-control form-control-sm" name="dline2"
											id="dline2">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-3">
									<div class="form-group mb-1">
										<label class="small font-weight-bold">City</label>
										<input type="text" class="form-control form-control-sm" name="city" id="city">
									</div>
								</div>
								<div class="col-2">
								</div>
								<div class="col-3">
									<div class="form-group mb-1">
										<label class="small font-weight-bold">City</label>
										<input type="text" class="form-control form-control-sm" name="dcity" id="dcity">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-3">
									<div class="form-group mb-1">
										<label class="small font-weight-bold">State</label>
										<input type="text" class="form-control form-control-sm" name="state" id="state">
									</div>
								</div>
								<div class="col-2">
								</div>
								<div class="col-3">
									<div class="form-group mb-1">
										<label class="small font-weight-bold">State</label>
										<input type="text" class="form-control form-control-sm" name="dstate"
											id="dstate">
									</div>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-4">
									<div class="form-group mb-1">
										<label class="small font-weight-bold">Business Status</label><br>
										<input type="radio" id="Proprietorship" name="bstatus" value="Proprietorship">
										<label for="Proprietorship">Proprietorship</label>
										<input type="radio" id="bstatusPartnership" name="bstatus" value="Partnership">
										<label for="bstatusPartnership">Partnership</label>
										<input type="radio" id="bstatusIncorporation" name="bstatus" value="Incorporation">
										<label for="bstatusIncorporation">Incorporation</label><br><br>
									</div>

									<div class="form-group mb-1">
										<label class="small font-weight-bold">Method of Payment</label><br>
										<input type="checkbox" id="cashpayementmethod" name="payementmethod_cash" value="Cash">
										<label for="cashpayementmethod">Cash</label>
										<input type="checkbox" id="bankpayementmethod" name="payementmethod_bank" value="Bank">
										<label for="bankpayementmethod">Bank</label>
										
									</div>

									<div class="form-group mb-1">
										<label class="small font-weight-bold">Credit Days</label>
										<input type="text" class="form-control form-control-sm" name="credit_days"
											id="credit_days">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-9">
									<div class="form-group mt-2 text-right" style="padding-top: 5px;">
										<button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-5"
											<?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
									</div>
								</div>
							</div>
							<input type="hidden" name="recordOption" id="recordOption" value="1">
							<input type="hidden" name="recordID" id="recordID" value="">
						</form>
					</div>
				</div>
			</div>
			<div class="container-fluid mt-2 p-0 p-2">
				<div class="card">
					<div class="card-body p-0 p-2">
						<div class="row">
							<div class="col-12">
								<div class="scrollbar pb-3" id="style-2">
									<table class="table table-bordered table-striped table-sm nowrap"
										id="tblsuppliertype">
										<thead>
											<tr>
												<th>#</th>
												<th>Name</th>
												<th>Supplier Type</th>
												<th>BR No</th>
												<th>VAT No</th>
												<th>NBT No</th>
												<th>SVAT No</th>
												<th>Address</th>
												<th>City</th>
												<th>BR Certificate</th>
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
		<!-- Modal Image View -->
		<div class="modal fade" id="modalimageview" data-backdrop="static" data-keyboard="false" tabindex="-1"
			aria-labelledby="staticBackdropLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header p-2">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-12 text-center">
								<div id="imagelist" class=""></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
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

		$('#tblsuppliertype').DataTable({
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
					title: 'Supplier Information',
					text: '<i class="fas fa-file-csv mr-2"></i> CSV',
				},
				{
					extend: 'pdf',
					className: 'btn btn-danger btn-sm',
					title: 'Supplier Information',
					text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
				},
				{
					extend: 'print',
					title: 'Supplier Information',
					className: 'btn btn-primary btn-sm',
					text: '<i class="fas fa-print mr-2"></i> Print',
					customize: function (win) {
						$(win.document.body).find('table')
							.addClass('compact')
							.css('font-size', 'inherit');
					},
				},
			],

			ajax: {
				url: "<?php echo base_url() ?>scripts/supplierlist.php",
				type: "POST",
			},
			"order": [
				[1, "desc"]
			],
			"columns": [{
	            data: null,
	            orderable: false,
	            searchable: false,
	            render: function (data, type, row, meta) {
	                return meta.row + meta.settings._iDisplayStart + 1;
	            }
	        },
				{
					"data": "name"
				},
				{
					"data": "type"
				},
				{
					"data": "bus_reg_no"
				},
				{
					"data": "vat_no"
				},
				{
					"data": "nbt_no"
				},
				{
					"data": "svat_no"
				},
				{
					"targets": [4],
					"render": function (data, type, row) {
						return row.address_line1 + ',' + row.address_line2 + '';
					}
				},
				{
					"data": "city"
				},
				{
					data: "imagepath",
					render: function (data, type, row) {
						var imageUrl = '<?php echo base_url(); ?>images/supplier_br_cetificate/' + data;
						if (data !== null && data !== "") {
							return '<a href="' + imageUrl + '" target="_blank">' +
								'<img class="zoom-image" src="' + imageUrl +
								'" alt="Supplier Image" width="50" height="50">' +
								'</a>';
						} else {
							return '<span class="text-muted">No Image</span>';
						}
					}
				},
				{
					"targets": -1,
					"className": 'text-right',
					"data": null,
					"render": function (data, type, full) {
						var button = '';
						button += '<a href="<?php echo base_url() ?>Supplierbank/index/' + full['idtbl_supplier'] +
							'" target="_self" class="btn btn-secondary btn-sm mr-1"><i class="fas fa-file"></i></a>';

						button += '<button class="btn btn-primary btn-sm btnEdit mr-1 ';
						if (editcheck != 1) {
							button += 'd-none';
						}
						button += '" id="' + full['idtbl_supplier'] +
							'"><i class="fas fa-pen"></i></button>';
						if (full['status'] == 1) {
							button += '<a href="<?php echo base_url() ?>Supplier/Supplierstatus/' +
								full['idtbl_supplier'] +
								'/2" onclick="return deactive_confirm()" target="_self" class="btn btn-success btn-sm mr-1 ';
							if (statuscheck != 1) {
								button += 'd-none';
							}
							button += '"><i class="fas fa-check"></i></a>';
						} else {
							button += '<a href="<?php echo base_url() ?>Supplier/Supplierstatus/' +
								full['idtbl_supplier'] +
								'/1" onclick="return active_confirm()" target="_self" class="btn btn-warning btn-sm mr-1 ';
							if (statuscheck != 1) {
								button += 'd-none';
							}
							button += '"><i class="fas fa-times"></i></a>';
						}
						button += '<a href="<?php echo base_url() ?>Supplier/Supplierstatus/' +
							full['idtbl_supplier'] +
							'/3" onclick="return delete_confirm()" target="_self" class="btn btn-danger btn-sm ';
						if (deletecheck != 1) {
							button += 'd-none';
						}
						button += '"><i class="fas fa-trash-alt"></i></a>';

						return button;
					}
				}
			],
			drawCallback: function (settings) {
				$('[data-toggle="tooltip"]').tooltip();
			}
		});
		
		$('#tblsuppliertype tbody').on('click', '.btnEdit', function () {
			var r = confirm("Are you sure, You want to Edit this ? ");
			if (r == true) {
				var id = $(this).attr('id');
				$.ajax({
					type: "POST",
					data: {
						recordID: id
					},
					url: '<?php echo base_url() ?>Supplier/Supplieredit',
					success: function (result) {
						var obj = JSON.parse(result);
						$('#recordID').val(obj.id);
						$('#supplier_name').val(obj.name);
						$('#business_regno').val(obj.business_regno);
						$('#nbtno').val(obj.nbtno);
						$('#svatno').val(obj.svatno);
						$('#telephoneno').val(obj.telephoneno);
						$('#faxno').val(obj.faxno);
						$('#dline1').val(obj.dline1);
						$('#dline2').val(obj.dline2);
						$('#dcity').val(obj.dcity);
						$('#dstate').val(obj.dstate);
						$('#line1').val(obj.line1);
						$('#line2').val(obj.line2);
						$('#city').val(obj.city);
						$('#state').val(obj.state);
						$('#credit_days').val(obj.credit_days);
						
						var payementmethod = obj.payementmethod;
						// Uncheck all checkboxes first
						$('input[name="payementmethod_cash"], input[name="payementmethod_bank"], input[name="payementmethod_credit"], input[name="payementmethod_cheque"]').prop('checked', false);
						
						// Check the ones that match
						if (payementmethod) {
							var methods = payementmethod.split(', ');
							for (var i = 0; i < methods.length; i++) {
								if (methods[i] == 'Cash') $('#cashpayementmethod').prop('checked', true);
								else if (methods[i] == 'Bank') $('#bankpayementmethod').prop('checked', true);
								else if (methods[i] == 'Credit') $('#creditpayementmethod').prop('checked', true);
								else if (methods[i] == 'Cheque') $('#chequepayementmethod').prop('checked', true);
							}
						}
						
						var busstatus = obj.business_status;
						if (busstatus == "Proprietorship") {
							$('#Proprietorship').prop('checked', true);
						} else if (busstatus == "Partnership") {
							$('#bstatusPartnership').prop('checked', true);
						} else if (busstatus == "Incorporation") {
							$('#bstatusIncorporation').prop('checked', true);
						}
						$('#vatno').val(obj.vat_no);
						$('#suppliertype').val(obj.type);

						$('#recordOption').val('2');
						$('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
					}
				});
			}
		});

		function clearAllErrors() {
	        $('.form-control, select, input[type="file"]').removeClass('is-invalid');
	        $('.form-group').removeClass('is-invalid');
	        $('.invalid-feedback').remove();
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

	    $(document).on('input change', '.form-control, select, input[type="file"], input[type="radio"], input[type="checkbox"]', function () {
	        clearAllErrors();
	    });

	    $('#supplierForm').on('submit', function (e) {
	        clearAllErrors();
	        
	        let hasError = false;
	        let firstErrorElement = null;
	        
	        // Optional: Validate credit_days only if provided
	        let creditDays = $('#credit_days').val();
	        if (creditDays && creditDays.trim() !== '') {
	            let num = parseFloat(creditDays);
	            if (isNaN(num) || num < 0) {
	                showFieldError('credit_days', 'Credit Days must be a valid non-negative number');
	                hasError = true;
	                if (!firstErrorElement) firstErrorElement = $('#credit_days');
	            }
	        }
	        
	        // Optional: Validate telephone if provided
	        let telephone = $('#telephoneno').val();
	        if (telephone && telephone.trim() !== '') {
	            if (isNaN(parseFloat(telephone))) {
	                showFieldError('telephoneno', 'Telephone No must be a number');
	                hasError = true;
	                if (!firstErrorElement) firstErrorElement = $('#telephoneno');
	            }
	        }
	        
	        if (hasError) {
	            e.preventDefault();
	            if (firstErrorElement) {
	                firstErrorElement.focus();
	                $('html, body').animate({
	                    scrollTop: firstErrorElement.offset().top - 150
	                }, 500);
	            }
	        }
	    });
	});

	function deactive_confirm() {
		return confirm("Are you sure you want to deactive this?");
	}

	function active_confirm() {
		return confirm("Are you sure you want to active this?");
	}

	function delete_confirm() {
		return confirm("Are you sure you want to remove this?");
	}
</script>

<script>
	$(document).ready(function () {
		$('.zoom-image-link').magnificPopup({
			type: 'image',
			gallery: {
				enabled: true
			}
		});
	});
</script>

<?php include "include/footer.php"; ?>
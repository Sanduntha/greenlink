<?php 
include "include/header.php";  
include "include/topnavbar.php"; 
?>
<div id="layoutSidenav">

    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <head>
            <link rel="stylesheet" href="<?php echo base_url('path/to/magnific-popup.css'); ?>">
            <script src="<?php echo base_url('path/to/jquery.min.js'); ?>"></script>
            <script src="<?php echo base_url('path/to/jquery.magnific-popup.min.js'); ?>"></script>
        </head>
        <main>
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i class="fas fa-users"></i></div>
                            <span>Customer</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">

                        <form action="<?php echo base_url() ?>Customer/Customerinsertupdate"
                            enctype="multipart/form-data" method="post" autocomplete="off" id="customerForm"  novalidate >
                            <div class="row">
                                <div class="col-3">
        <div class="form-group mb-1">
            <label class="small font-weight-bold">Customer Type*</label>
            <select class="form-control form-control-sm" name="customer_type" id="customer_type" required>
                <option value="">Select Type</option>
                <option value="Business">Business</option>
                <option value="Individual">Individual</option>
            </select>
        </div>
    </div>
                                <div class="col-3">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Registered Name of the Company*</label>
                                        <input type="text" class="form-control form-control-sm" name="customer_name"
                                            id="customer_name" required>
                                    </div>
                                </div>
                                <!-- <div class="col-3">
								<div class="form-group mb-1">
									<label class="small font-weight-bold">NIC *</label>
									<input type="text" class="form-control form-control-sm" name="nic" id="nic"
										required>
								</div>
							    </div> -->

                                <div class="col-3">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Business registration No *</label>
                                        <input type="text" class="form-control form-control-sm" name="business_regno"
                                            id="business_regno">
                                    </div>
                                </div>
                                <!-- Add Business reg no cetificate -->
                                <div class="col-2">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Submit copy of BR Cetificate*</label>

                                        <input type="file" class="form-control form-control-sm" name="image">

                                    </div>
                                </div>
                                
                                <!-- <div class="col-3">
								<div class="form-group mb-1">
									<label class="small font-weight-bold">Postal Code*</label>
									<input type="text" class="form-control form-control-sm" name="potalcode" id="potalcode"
										required>
								</div>
							   </div> -->

                            </div>

                            <div class="row">

                                <div class="col-2">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">VAT Reg Type*</label>
                                        <select class="form-control form-control-sm  px-0" name="vat_customer"
                                            id="vat_customer" required>
                                            <option value="">Select VAT Type</option>
                                            <option value="0">Non VAT</option>
                                            <option value="1">VAT</option>
                                            <option value="2">SVAT</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-2">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">VAT Registration No*</label>
                                        <input type="text" class="form-control form-control-sm" name="vatno" id="vatno">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">NBT Registration No*</label>
                                        <input type="text" class="form-control form-control-sm" name="nbtno" id="nbtno">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">SVAT Registration No*</label>
                                        <input type="text" class="form-control form-control-sm" name="svatno"
                                            id="svatno">
                                    </div>
                                </div>

                                <div class="col-2 d-none">
                                    <label class="small font-weight-bold ">Company*</label>
                                    <input type="text" id="f_company_name" name="f_company_name"
                                        class="form-control form-control-sm" required readonly>
                                </div>
                                <div class="col-2 d-none">
                                    <label class="small font-weight-bold ">Company
                                        Branch*</label>
                                    <input type="text" id="f_branch_name" name="f_branch_name"
                                        class="form-control form-control-sm" required readonly>
                                </div>
                                <input type="hidden" name="f_company_id" id="f_company_id">
                                <input type="hidden" name="f_branch_id" id="f_branch_id">

                            </div>
                            <div class="row">
                                
                                <div class="col-2">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Telephone No*</label>
                                        <input type="number" class="form-control form-control-sm" name="telephoneno"
                                            id="telephoneno">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">FAX No*</label>
                                        <input type="text" class="form-control form-control-sm" name="faxno" id="faxno">
                                    </div>
                                </div>

                                
    <div class="col-3">
        <div class="form-group mb-1">
            <label class="small font-weight-bold">Customer Email Address*</label>
            <input type="email" class="form-control form-control-sm"
                   name="customer_email" id="customer_email" required>
        </div>
    </div>

    
    <div class="col-3">
        <div class="form-group mb-1">
            <label class="small font-weight-bold">Website URL</label>
            <input type="url" class="form-control form-control-sm"
                   name="website_url" id="website_url"
                   placeholder="https://www.example.com">
        </div>
    </div>

                                
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <label class="small font-weight-bold"><b>Business Address*</b></label>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Address Line 1*</label>
                                        <input type="text" class="form-control form-control-sm" name="line1" id="line1"
                                            required>
                                    </div>
                                </div>
                                <div class="col-2">
                                </div>
                                <div class="col-3">
                                    <label class="small font-weight-bold"><b>Delivery Address*</b></label>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Delivery Address Line 1*</label>
                                        <input type="text" class="form-control form-control-sm" name="dline1"
                                            id="dline1" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Address Line 2*</label>
                                        <input type="text" class="form-control form-control-sm" name="line2" id="line2"
                                            required>
                                    </div>
                                </div>
                                <div class="col-2">
                                </div>
                                <div class="col-3">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Delivery Address Line 2*</label>
                                        <input type="text" class="form-control form-control-sm" name="dline2"
                                            id="dline2" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">City*</label>
                                        <input type="text" class="form-control form-control-sm" name="city" id="city">
                                    </div>
                                </div>
                                <div class="col-2">
                                </div>
                                <div class="col-3">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">City*</label>
                                        <input type="text" class="form-control form-control-sm" name="dcity" id="dcity">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">State*</label>
                                        <input type="text" class="form-control form-control-sm" name="state" id="state">
                                    </div>
                                </div>
                                <div class="col-2">
                                </div>
                                <div class="col-3">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">State*</label>
                                        <input type="text" class="form-control form-control-sm" name="dstate"
                                            id="dstate">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
    <!-- Business Country -->
    <div class="col-3">
        <div class="form-group mb-1">
            <label class="small font-weight-bold">Country*</label>
            <input type="text" class="form-control form-control-sm"
                   name="country" id="country" required>
        </div>
    </div>

    <div class="col-2">
    </div>

    <!-- Delivery Country -->
    <div class="col-3">
        <div class="form-group mb-1">
            <label class="small font-weight-bold">Country*</label>
            <input type="text" class="form-control form-control-sm"
                   name="dcountry" id="dcountry" required>
        </div>
    </div>
</div>

                            <br>
                            <!-- Add VAT,NBT,SVAT cetificate -->
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Business Status*</label><br>
                                        <input type="radio" id="Proprietorship" name="bstatus" value="Proprietorship">
                                        <label for="age1">Proprietorship</label>
                                        <input type="radio" id="bstatusPartnership" name="bstatus" value="Partnership">
                                        <label for="age2">Partnership</label>
                                        <input type="radio" id="bstatusIncorporation" name="bstatus"
                                            value="Incorporation">
                                        <label for="age3">Incorporation</label><br><br>

                                    </div>

                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Method of Payment*</label><br>
                                        <input type="radio" id="cashpayementmethod" name="payementmethod" value="Cash">
                                        <label for="age1">Cash</label>
                                        <input type="radio" id="bankpayementmethod" name="payementmethod" value="Bank">
                                        <label for="age2">Bank</label>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-9">
                                    <div class="form-group mt-2 text-right" style="padding-top: 5px;">
                                        <button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-5"
                                            <?php if($addcheck==0){echo 'disabled';} ?>><i
                                                class="far fa-save"></i>&nbsp;Add</button>
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
                                    <table class="table table-bordered table-striped table-sm nowrap" id="tblcustomer">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>BR No</th>
                                                <th>VAT No</th>
                                                <th>NBT No</th>
                                                <th>SVAT No</th>
                                                <th>Address</th>
                                                <th>City</th>
                                                <th>BR Cetificate</th>
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

<!-- Modal Create-->
<div class="modal fade" id="deliveryAddressModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="deliveryAddressModal" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deliveryAddressModaltitle">Add Delivery Addresses</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-4">
						<form id="createporderform" autocomplete="off">
                            <input type="hidden" id="modal_address_id" name="modal_address_id">
							<div class="form-row mb-1">
								<div class="col">
									<label class="small font-weight-bold text-dark">Delivery Address*</label>
									
								</div>

							</div>
                            <div class="form-row mb-1">
								<div class="col">
									<label class="small font-weight-bold">Address Line 1*</label>
                                <input type="text" class="form-control form-control-sm" name="dline1" id="modal_dline1" required>
								</div>

							</div>
                            <div class="form-row mb-1">
								<div class="col">
									<label class="small font-weight-bold">Address Line 2*</label>
                                <input type="text" class="form-control form-control-sm" name="dline2" id="modal_dline2" required>
								</div>

							</div>
                            <div class="form-row mb-1">
								<div class="col">
									<label class="small font-weight-bold">City*</label>
                                <input type="text" class="form-control form-control-sm" name="dcity" id="modal_dcity" required>
								</div>

							</div>
                            <div class="form-row mb-1">
								<div class="col">
									 <label class="small font-weight-bold">State*</label>
                                <input type="text" class="form-control form-control-sm" name="dstate" id="modal_dstate" required>
								</div>

							</div>
                            <div class="form-row mb-1">
								<div class="col">
									 <label class="small font-weight-bold">country*</label>
                                <input type="text" class="form-control form-control-sm" name="ddcountry" id="modal_ddcountry" required>
								</div>

							</div>
                            <div class="form-row mb-1">
								<div class="col">
									 <label class="small font-weight-bold">Remark*</label>
                                <input type="text" class="form-control form-control-sm" name="remark" id="modal_remark" required>
								</div>

							</div>
                            
							<div class="form-group mt-3 text-right">
								<button type="button" id="formsubmit" class="btn btn-primary btn-sm px-4"><i
										class="fas fa-plus"></i>&nbsp;Add to
									list</button>
								<input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">
							</div>
						</form>
					</div>
					<div class="col-8">
						<div class="scrollbar pb-3" id="style-2">
									<table class="table table-bordered table-striped table-sm nowrap"
										id="tblDeliveryAddresses">
										<thead>
											<tr>
												<th>Address Line 1</th>
                                                <th>Address Line 2</th>
                                                <th>City</th>
                                                <th>State</th>
                                                <th>Country</th>
                                                <th>Remark</th>
												<th class="text-right">Actions</th>
											</tr>
										</thead>
									</table>
								</div>
						
						<div class="form-group mt-2">
							<button type="button" id="btncreateporder"
								class="btn btn-outline-primary btn-sm fa-pull-right mt-2"><i
									class="fas fa-save"></i>&nbsp;Save
								Address</button>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
 </div>
</div>
<?php include "include/footerscripts.php"; ?>

<script>
$(document).ready(function() {
    var addcheck = '<?php echo $addcheck; ?>';
    var editcheck = '<?php echo $editcheck; ?>';
    var statuscheck = '<?php echo $statuscheck; ?>';
    var deletecheck = '<?php echo $deletecheck; ?>';

    
    $('#tblcustomer').DataTable({
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
                title: 'Customer  Information',
                text: '<i class="fas fa-file-csv mr-2"></i> CSV',
            },
            {
                extend: 'pdf',
                className: 'btn btn-danger btn-sm',
                title: 'Customer  Information',
                text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
            },
            {
                extend: 'print',
                title: 'Customer  Information',
                className: 'btn btn-primary btn-sm',
                text: '<i class="fas fa-print mr-2"></i> Print',
                customize: function(win) {
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                },
            },
            // 'copy', 'csv', 'excel', 'pdf', 'print'
        ],

        ajax: {
            url: "<?php echo base_url() ?>scripts/customerlist.php",
            type: "POST", // you can use GET
        },
        "order": [[1, "asc"]]
,
        "columns": [{
        "data": null,
        "orderable": false,
        "searchable": false,
        "render": function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
        }
    },
            {
                "data": "name"
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
                "render": function(data, type, row) {
                    return row.address_line1 + ',' + row.address_line2 + '';
                }
            },
            {
                "data": "city"
            },
            {
                data: "imagepath",
                render: function(data, type, row) {
                    var imageUrl = '<?php echo base_url(); ?>images/cetificate/' + data;
                    if (data !== null && data !== "") {
                        return '<a href="' + imageUrl + '" target="_blank">' +
                            '<img class="zoom-image" src="' + imageUrl +
                            '" alt="Customer Image" width="50" height="50">' +
                            '</a>';
                    } else {
                        // Provide a placeholder image or icon
                        return '<img class="zoom-image" src="path_to_placeholder_image" alt="No Image" width="50" height="50">';
                    }
                }
            },

            {
                "targets": -1,
                "className": 'text-right',
                "data": null,
                "render": function(data, type, full) {
                    var button = '';
                    button += '<a href="<?php echo base_url() ?>Customerbank/index/' + full[
                            'idtbl_customer'] +
                        '" target="_self" class="btn btn-secondary btn-sm mr-1"><i class="fas fa-file"></i></a>';

                    button += '<button class="btn btn-primary btn-sm btnEdit mr-1 ';
                    if (editcheck != 1) {
                        button += 'd-none';
                    }
                    button += '" id="' + full['idtbl_customer'] +
                        '"><i class="fas fa-pen"></i></button>';

                    button += '<button class="btn btn-info btn-sm btnDeliveryAddress mr-1" id="' + full['idtbl_customer'] + 
                        '" data-name="' + full['name'] + '"><i class="fas fa-truck"></i></button>';

                    if (full['status'] == 1) {
                        button += '<a href="<?php echo base_url() ?>Customer/Customerstatus/' +
                            full['idtbl_customer'] +
                            '/2" onclick="return deactive_confirm()" target="_self" class="btn btn-success btn-sm mr-1 ';
                        if (statuscheck != 1) {
                            button += 'd-none';
                        }
                        button += '"><i class="fas fa-check"></i></a>';
                    } else {
                        button += '<a href="<?php echo base_url() ?>Customer/Customerstatus/' +
                            full['idtbl_customer'] +
                            '/1" onclick="return active_confirm()" target="_self" class="btn btn-warning btn-sm mr-1 ';
                        if (statuscheck != 1) {
                            button += 'd-none';
                        }
                        button += '"><i class="fas fa-times"></i></a>';
                    }
                    button += '<a href="<?php echo base_url() ?>Customer/Customerstatus/' +
                        full['idtbl_customer'] +
                        '/3" onclick="return delete_confirm()" target="_self" class="btn btn-danger btn-sm ';
                    if (deletecheck != 1) {
                        button += 'd-none';
                    }
                    button += '"><i class="fas fa-trash-alt"></i></a>';

                    return button;
                }
            }
        ],
        drawCallback: function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        },


    });

    $('#tblcustomer tbody').on('click', '.btnEdit', function() {
        var r = confirm("Are you sure, You want to Edit this ? ");
        if (r == true) {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Customer/Customeredit',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    $('#recordID').val(obj.id);
                    $('#customer_name').val(obj.name);
                                    $('#customer_type').val(obj.customer_type);

                    $('#business_regno').val(obj.business_regno);
                    $('#nbtno').val(obj.nbtno);
                    $('#svatno').val(obj.svatno);
                    $('#vat_customer').val(obj.vat_customer);
                    $('#telephoneno').val(obj.telephoneno);
                    $('#faxno').val(obj.faxno);
                    $('#customer_email').val(obj.customer_email);
                $('#website_url').val(obj.website_url);
                    $('#dline1').val(obj.dline1);
                    $('#dline2').val(obj.dline2);
                    $('#dcity').val(obj.dcity);
                    $('#dstate').val(obj.dstate);
                                    $('#dcountry').val(obj.dcountry);

                    $('#line1').val(obj.line1);
                    $('#line2').val(obj.line2);
                    $('#city').val(obj.city);
                    $('#state').val(obj.state);
                                    $('#country').val(obj.country);

                    // $('#potalcode').val(obj.postal_code);
                    // $('#country').val(obj.country);
                    $('#vatno').val(obj.vat_no);
                    //$('#bstatus').val(obj.business_status);
                    // $('#payementmethod').val(obj.payementmethod);

                    var payementmethod = obj.payementmethod;
                    //alert(busstatus);
                    if (payementmethod == "Cash") {
                        $('#cashpayementmethod').prop('checked', true);

                    } else if (busstatus == "Bank") {
                        $('#bankpayementmethod').prop('checked', true);
                    }
                    // $('#nic').val(obj.nic);
                    var busstatus = obj.business_status;
                    //alert(busstatus);
                    if (busstatus == "Proprietorship") {
                        $('#Proprietorship').prop('checked', true);

                    } else if (busstatus == "Partnership") {
                        $('#bstatusPartnership').prop('checked', true);
                    } else if (busstatus == "Incorporation") {
                        $('#bstatusIncorporation').prop('checked', true);
                    }
                    $('#recordOption').val('2');
                    $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                }
            });
        }
    });

    var deliveryAddresses = []; 
    var savedAddresses = [];    
    var currentCustomerID = null;
    var editingIndex = null;    
    var isEditingSavedAddress = false; 

    
    function initDeliveryAddressTable() {
    $('#tblDeliveryAddresses').DataTable({
        "destroy": true,
        "paging": false,
        "searching": false,
        "info": false,
        "ordering": false,
        "autoWidth": false,
        "columns": [
            { "width": "20%" },
            { "width": "20%" },
            { "width": "15%" },
            { "width": "15%" },
            { "width": "10%" },
            { "width": "10%" },
            { 
                "width": "10%",
                "className": "text-center"
            }
        ]
    });
}
  
    function refreshDeliveryAddressTable() {
        var table = $('#tblDeliveryAddresses').DataTable();
        table.clear().draw();
        
        if (savedAddresses.length > 0) {
            $.each(savedAddresses, function(index, address) {
                table.row.add([
                    address.address_line1,
                    address.address_line2,
                    address.city,
                    address.state,
                    address.ddcountry,
                    address.remark || '-',
                    `<div class="text-center">
                    <button class="btn btn-sm btn-secondary btnCopyAddress mr-1"
        data-address="${address.address_line1}, ${address.address_line2}, ${address.city}, ${address.state}, ${address.ddcountry}">
        <i class="fas fa-copy"></i>
    </button>
                        <button class="btn btn-sm btn-primary btnEditSavedAddress mr-1" 
                            data-id="${address.idtbl_customer_delivery_address}"
                            data-line1="${address.address_line1}"
                            data-line2="${address.address_line2}"
                            data-city="${address.city}"
                            data-state="${address.state}"
                            data-state="${address.ddcountry}"
                            data-remark="${address.remark || ''}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btnRemoveSavedAddress" 
                            data-id="${address.idtbl_customer_delivery_address}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>`
                ]).draw(false);
            });
        }
        if (deliveryAddresses.length > 0) {
            $.each(deliveryAddresses, function(index, address) {
                table.row.add([
                    address.dline1,
                    address.dline2,
                    address.dcity,
                    address.dstate,
                     address.ddcountry,
                    address.remark || '-',
                    `<div class="text-center">
                    <button class="btn btn-sm btn-secondary btnCopyAddress mr-1"
        data-address="${address.dline1}, ${address.dline2}, ${address.dcity}, ${address.dstate}, ${address.ddcountry}">
        <i class="fas fa-copy"></i>
    </button>
                        <button class="btn btn-sm btn-primary btnEditAddress mr-1" 
                            data-index="${index}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btnRemoveAddress" 
                            data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>`
                ]).draw(false);
            });
        }

    }

    $(document).on('click', '.btnCopyAddress', function () {
    var address = $(this).data('address');

    navigator.clipboard.writeText(address).then(function () {
        alert('Address copied to clipboard!');
    }).catch(function () {
        alert('Failed to copy address');
    });
});

$('#tblDeliveryAddresses').on('click', '.btnRemoveAddress', function() {
    var index = $(this).data('index');
    if (confirm('Are you sure you want to remove this address?')) {
        refreshDeliveryAddressTable();
    }
});
$('#tblDeliveryAddresses').on('click', '.btnRemoveSavedAddress', function() {
    var addressID = $(this).data('id');
    if (confirm('Are you sure you want to delete this address?')) {
        $.ajax({
            url: '<?php echo base_url(); ?>Customer/DeleteDeliveryAddress/' + addressID,
            type: 'POST', 
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    
                    savedAddresses = savedAddresses.filter(a => a.idtbl_customer_delivery_address != addressID);
                    refreshDeliveryAddressTable();
                    alert('Address deleted successfully');
                } else {
                    alert(response.message || 'Error deleting address');
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    }
});

     $('#deliveryAddressModal').on('shown.bs.modal', function() {
        initDeliveryAddressTable();
    });

    
$(document).on('click', '.btnDeliveryAddress', function() {
    currentCustomerID = $(this).attr('id');
    var customerName = $(this).data('name');
    
    $('#deliveryAddressModaltitle').text('Delivery Addresses for ' + customerName);
    $('#createporderform')[0].reset();
    deliveryAddresses = []; 
    isEditingSavedAddress = false;
    
   
    if ($.fn.DataTable.isDataTable('#tblDeliveryAddresses')) {
        $('#tblDeliveryAddresses').DataTable().destroy();
    }
    
   
    loadCustomerAddresses(currentCustomerID);
});

function loadCustomerAddresses(customerID) {
    $.ajax({
        url: '<?php echo base_url(); ?>Customer/GetDeliveryAddresses/' + customerID,
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {
            
        },
        success: function(response) {
            savedAddresses = response;
            initDeliveryAddressTable(); 
            refreshDeliveryAddressTable(); 
            $('#deliveryAddressModal').modal('show');
        },
        error: function(xhr, status, error) {
            console.error('Error loading addresses:', error);
            
            initDeliveryAddressTable();
            $('#deliveryAddressModal').modal('show');
        }
    });
}

    $('#formsubmit').click(function(e) {
        e.preventDefault();
        var dline1 = $('#modal_dline1').val();
        var dline2 = $('#modal_dline2').val();
        var dcity = $('#modal_dcity').val();
        var dstate = $('#modal_dstate').val();
         var ddcountry = $('#modal_ddcountry').val();
        var remark = $('#modal_remark').val();
        
        if (!dline1 || !dline2 || !dcity || !dstate) {
            alert('Please fill all required fields');
            return;
        }
        
        var addressData = {
            dline1: dline1,
            dline2: dline2,
            dcity: dcity,
            dstate: dstate,
            ddcountry:ddcountry,
            remark: remark
        };
        
        if (isEditingSavedAddress) {
            var addressID = $('#modal_address_id').val();
            $.ajax({
                url: '<?php echo base_url(); ?>Customer/UpdateDeliveryAddress',
                type: 'POST',
                data: {
                    addressID: addressID,
                    dline1: dline1,
                    dline2: dline2,
                    dcity: dcity,
                    dstate: dstate,
                    ddcountry:ddcountry,
                    remark: remark
                },
            
                dataType: 'json',
                success: function(response) {
                    console.log('AJAX Response:', response);
                    if (response.status === 'success') {
                        // Update the savedAddresses array with the new data
                        var index = savedAddresses.findIndex(a => a.idtbl_customer_delivery_address == addressID);
                        if (index !== -1) {
                            savedAddresses[index] = {
                                idtbl_customer_delivery_address: addressID,
                                address_line1: dline1,
                                address_line2: dline2,
                                city: dcity,
                                state: dstate,
                                ddcountry:ddcountry,
                                remark: remark
                            };
                        }
                        // Reset form and flags AFTER successful update
                        $('#createporderform')[0].reset();
                        isEditingSavedAddress = false;
                        $('#modal_address_id').val('');
                        
                        // Refresh the table to show updated data
                        refreshDeliveryAddressTable();
                        
                        alert('Address updated successfully');
                    } else {
                        alert(response.message || 'Error updating address');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                }
            });
        } else if (editingIndex !== null) {
            // Update unsaved address
            deliveryAddresses[editingIndex] = addressData;
            editingIndex = null;
            $('#createporderform')[0].reset();
            refreshDeliveryAddressTable();
        } else {
            // Add new address
            deliveryAddresses.push(addressData);
            $('#createporderform')[0].reset();
            refreshDeliveryAddressTable();
        }
    });
    // Edit unsaved address
    $('#tblDeliveryAddresses').on('click', '.btnEditAddress', function() {
        var index = $(this).data('index');
        var address = deliveryAddresses[index];
        
        // Populate form with address data
        $('#modal_dline1').val(address.dline1);
        $('#modal_dline2').val(address.dline2);
        $('#modal_dcity').val(address.dcity);
        $('#modal_dstate').val(address.dstate);
        $('#modal_ddcountry').val(address.ddcountry);
        $('#modal_remark').val(address.remark || '');
        
        editingIndex = index;
        isEditingSavedAddress = false;
    });
    
    $('#tblDeliveryAddresses').on('click', '.btnEditSavedAddress', function() {
        var addressID = $(this).data('id');
        var address = savedAddresses.find(a => a.idtbl_customer_delivery_address == addressID);
        
        $('#modal_dline1').val(address.address_line1);
        $('#modal_dline2').val(address.address_line2);
        $('#modal_dcity').val(address.city);
        $('#modal_dstate').val(address.state);
        $('#modal_ddcountry').val(address.ddcountry);
        $('#modal_remark').val(address.remark || '');
        $('#modal_address_id').val(addressID);
        
        isEditingSavedAddress = true;
        editingIndex = null;
    });
    
    
    // Remove address from table (client-side only)
     $('#tblDeliveryAddresses').on('click', '.btnRemoveSavedAddress', function() {
        var addressID = $(this).data('id');
        if (confirm('Are you sure you want to delete this address?')) {
            $.ajax({
                url: '<?php echo base_url(); ?>Customer/DeleteDeliveryAddress/' + addressID,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Remove from savedAddresses array
                        savedAddresses = savedAddresses.filter(a => a.idtbl_customer_delivery_address != addressID);
                        refreshDeliveryAddressTable();
                        alert('Address deleted successfully');
                    } else {
                        alert(response.message || 'Error deleting address');
                    }
                }
            });
        }
    });
    
    
    // Save all addresses s
     $('#btncreateporder').click(function() {
        if (deliveryAddresses.length === 0) {
            alert('No new addresses to save');
            return;
        }
        
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        
        $.ajax({
            url: '<?php echo base_url(); ?>Customer/AddDeliveryAddresses',
            type: 'POST',
            data: {
                customerID: currentCustomerID,
                addresses: JSON.stringify(deliveryAddresses)
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert('Addresses saved successfully');
                    response.addresses.forEach(function(newAddress) {
                        savedAddresses.push(newAddress);
                    });
                    deliveryAddresses = [];
                    refreshDeliveryAddressTable();
                    $('#deliveryAddressModal').modal('hide');
                } else {
                    alert(response.message || 'Error saving addresses');
                }
            },
            error: function(xhr, status, error) {
                alert('AJAX Error: ' + error);
            },
            complete: function() {
                $('#btncreateporder').prop('disabled', false).html('<i class="fas fa-save"></i> Save Addresses');
            }
        });
    });
    
    $('#deliveryAddressModal').on('hidden.bs.modal', function() {
        $('#createporderform')[0].reset();
        editingIndex = null;
        isEditingSavedAddress = false;
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

function showRadioError(radioName, message) {
    let firstRadio = $('input[name="' + radioName + '"]:first');
    let group = firstRadio.closest('.form-group');
    group.addClass('is-invalid');
    let feedback = group.find('.invalid-feedback');
    if (feedback.length === 0) {
        feedback = $('<div class="invalid-feedback d-block">' + message + '</div>');
        group.append(feedback);
    } else {
        feedback.text(message).addClass('d-block');
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
            if (isNaN(num) || num < 0) {
                errorMsg = conf.label + ' must be a valid non-negative number';
            }
        } else if (conf.email) {
            let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                errorMsg = conf.label + ' must be a valid email address';
            }
        } else if (conf.url) {
            
            if (value && !/^https?:\/\//i.test(value)) {
                errorMsg = conf.label + ' must be a valid URL (include http:// or https://)';
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


$(document).on('input change', '.form-control, select, input[type="file"]', function () {
    $(this).removeClass('is-invalid');
    $(this).next('.invalid-feedback').remove();
    $(this).closest('.custom-file').find('.invalid-feedback').remove();
});

$(document).on('change', 'input[type="radio"]', function () {
    let name = $(this).attr('name');
    let group = $(this).closest('.form-group');
    if ($('input[name="' + name + '"]:checked').length > 0) {
        group.removeClass('is-invalid');
        group.find('.invalid-feedback').remove();
    }
});


$('#br_certificate').on('change', function () {
    let fileName = this.files.length > 0 ? this.files[0].name : 'Choose file...';
    $(this).next('.custom-file-label').addClass('selected').html(fileName);
    $(this).removeClass('is-invalid');
});


$('#customerForm').on('submit', function (e) {
    clearAllErrors();

    let hasError = false;
    let firstErrorElement = null;

    
    let requiredFields = [
        { id: 'customer_type', label: 'Customer Type' },
        { id: 'customer_name', label: 'Registered Name of the Company' },
        { id: 'business_regno', label: 'Business registration No' },
        { id: 'vat_customer', label: 'VAT Reg Type' },
        { id: 'telephoneno', label: 'Telephone No', numeric: true },
        { id: 'faxno', label: 'FAX No' },
        { id: 'customer_email', label: 'Customer Email Address', email: true },
        { id: 'line1', label: 'Business Address Line 1' },
        { id: 'line2', label: 'Business Address Line 2' },
        { id: 'city', label: 'Business City' },
        { id: 'state', label: 'Business State' },
        { id: 'country', label: 'Business Country' },
        { id: 'dline1', label: 'Delivery Address Line 1' },
        { id: 'dline2', label: 'Delivery Address Line 2' },
        { id: 'dcity', label: 'Delivery City' },
        { id: 'dstate', label: 'Delivery State' },
        { id: 'dcountry', label: 'Delivery Country' },
     
    ];

    if (!validateFields(requiredFields)) {
        hasError = true;
    }

    // Conditional VAT/SVAT/NBT fields
    let vatType = $('#vat_customer').val();
    if (vatType === '1') { // VAT
        if (!$('#vatno').val().trim()) {
            showFieldError('vatno', 'VAT Registration No is required for VAT customers');
            hasError = true;
            if (!firstErrorElement) firstErrorElement = $('#vatno');
        }
    } else if (vatType === '2') { // SVAT
        if (!$('#svatno').val().trim()) {
            showFieldError('svatno', 'SVAT Registration No is required for SVAT customers');
            hasError = true;
            if (!firstErrorElement) firstErrorElement = $('#svatno');
        }
        
    }

    
    if (!$('#nbtno').val().trim()) {
        showFieldError('nbtno', 'NBT Registration No is required');
        hasError = true;
        if (!firstErrorElement) firstErrorElement = $('#nbtno');
    }

    if ($('input[name="bstatus"]:checked').length === 0) {
        showRadioError('bstatus', 'Business Status is required');
        hasError = true;
        if (!firstErrorElement) firstErrorElement = $('#Proprietorship');
    }

    if ($('input[name="payementmethod"]:checked').length === 0) {
        showRadioError('payementmethod', 'Method of Payment is required');
        hasError = true;
        if (!firstErrorElement) firstErrorElement = $('#cashpayementmethod');
    }

    // BR Certificate file - required only when adding new customer
    // if ($('#recordOption').val() === '1' && $('#br_certificate')[0].files.length === 0) {
    //     showFieldError('br_certificate', 'Submit copy of BR Certificate is required');
    //     hasError = true;
    //     if (!firstErrorElement) firstErrorElement = $('#br_certificate');
    // }

    if ($('#website_url').val().trim()) {
        let urlVal = $('#website_url').val().trim();
        if (!/^https?:\/\//i.test(urlVal)) {
            showFieldError('website_url', 'Website URL must include http:// or https://');
            hasError = true;
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
document.addEventListener("DOMContentLoaded", function() {
    var vatCustomer = document.getElementById("vat_customer");
    var vatNo = document.getElementById("vatno");
    var svatNo = document.getElementById("svatno");

    // Add event listener to the select element
    vatCustomer.addEventListener("change", function() {
        // Clear the values of vatNo and svatNo
        vatNo.value = "";
        svatNo.value = "";

        // Check the selected value
        if (vatCustomer.value === "1") {
            // If VAT customer value is 1, disable svatNo and show vatNo
            svatNo.disabled = true;
            vatNo.disabled = false;
        } else if (vatCustomer.value === "2") {
            // If VAT customer value is 2, show svatNo and vatNo
            svatNo.disabled = false;
            vatNo.disabled = false;
        } else {
            // For other values, disable both fields
            svatNo.disabled = true;
            vatNo.disabled = true;
        }
    });
});
</script>




<script>
$(document).ready(function() {
    $('.zoom-image-link').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true
        }
    });
});
</script>


<script>
/** Variables */
let files = [],
    dragArea = document.querySelector('.drag-area'),
    input = document.querySelector('.drag-area input'),
    button = document.querySelector('.card button'),
    select = document.querySelector('.drag-area .select'),
    container = document.querySelector('.container');

/** CLICK LISTENER */
select.addEventListener('click', () => input.click());

/* INPUT CHANGE EVENT */
input.addEventListener('change', () => {
    let file = input.files;

    // if user select no image
    if (file.length == 0) return;

    for (let i = 0; i < file.length; i++) {
        if (file[i].type.split("/")[0] != 'image') continue;
        if (!files.some(e => e.name == file[i].name)) files.push(file[i])
    }

    showImages();
});

/** SHOW IMAGES */
function showImages() {
    container.innerHTML = files.reduce((prev, curr, index) => {
        return `${prev}
		    <div class="image">
			    <span onclick="delImage(${index})">&times;</span>
			    <img src="${URL.createObjectURL(curr)}" />
			</div>`
    }, '');
}

/* DELETE IMAGE */
function delImage(index) {
    files.splice(index, 1);
    showImages();
}

/* DRAG & DROP */
dragArea.addEventListener('dragover', e => {
    e.preventDefault()
    dragArea.classList.add('dragover')
})

/* DRAG LEAVE */
dragArea.addEventListener('dragleave', e => {
    e.preventDefault()
    dragArea.classList.remove('dragover')
});

/* DROP EVENT */
dragArea.addEventListener('drop', e => {
    e.preventDefault()
    dragArea.classList.remove('dragover');

    let file = e.dataTransfer.files;
    for (let i = 0; i < file.length; i++) {
        /** Check selected file is image */
        if (file[i].type.split("/")[0] != 'image') continue;

        if (!files.some(e => e.name == file[i].name)) files.push(file[i])
    }
    showImages();
});
</script>
<?php include "include/footer.php"; ?>
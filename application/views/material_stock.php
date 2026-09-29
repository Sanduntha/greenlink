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
			<div class="row">
					<div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
						<form id="jobquotationform" autocomplete="off">
							<div class="form-row mb-1">
								<div class="col-4">
									<label class="small font-weight-bold text-dark">Order Date*</label>
									<input type="date" class="form-control form-control-sm" placeholder=""
										name="orderdate" id="orderdate" value="<?php echo date('Y-m-d') ?>" required>
								</div>
								<div class="col-8">
									<label class="small font-weight-bold text-dark">Inquiry*</label>
									<select class="form-control form-control-sm selecter2 px-0" name="inquiryId"
										id="inquiryId" required>
										<option value="">Select</option>
										<?php foreach($inquirylist->result() as $rowinquirylist){ ?>
										<option value="<?php echo $rowinquirylist->idtbl_customerinquiry ?>">
											<?php echo $rowinquirylist->name ?> - INQ
											No:<?php echo $rowinquirylist->idtbl_customerinquiry ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-row mb-1">
								<div class="col-8">
									<label class="small font-weight-bold text-dark">Main Item*</label>
									<select class="form-control form-control-sm selecter2 px-0" name="mainitem"
										id="mainitem" required>
										<option value="">Select</option>
										<?php foreach($mainitemlist->result() as $rowitemslist){ ?>
										<option value="<?php echo $rowitemslist->idtbl_mainitems ?>">
											<?php echo $rowitemslist->itemname ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-4">
									<label class="small font-weight-bold text-dark">Qty*</label>
									<input type="number" class="form-control form-control-sm" placeholder=""
										name="itemqty" id="itemqty" value="<?php echo date('Y-m-d') ?>" required>
								</div>
							</div>
							<div class="form-row mb-1">
								<div class="col">
									<label class="small font-weight-bold text-dark">Comment*</label>
									<textarea name="comment" class="form-control form-control-sm" id="comment" cols="10"
										rows="4" required></textarea>

								</div>
							</div>
							<div class="form-group mt-2">
								<button type="button" id="btnAddToList"
									class="btn btn-outline-primary btn-sm fa-pull-right"><i
										class="fas fa-plus"></i>&nbsp;
									Add</button>
								<input type="submit" id="hiddenformsubmit" class="d-none">
							</div>
						</form>
					</div>
					<div class="col-sm-12 col-md-12 col-lg-8 col-xl-8">
						<div class="scrollbar pb-3" id="style-3">
							<table class="table table-striped table-bordered table-sm small" id="tblnewquotation">
								<!-- Table headers remain unchanged -->
								<thead>
									<tr>
										<th>Item</th>
										<th>Quantity</th>
										<th>Comment</th>
										<th>Reel (Kg)</th>
										<th>Unit Price</th>
										<th>Total Price</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>

						<div class="row">
							<div class="col text-right">
								<h4 class="font-weight-600" id="divtotal">Rs. 0.00</h4>
							</div>
							<input type="hidden" id="hidetotalorder" value="0">
						</div>
						<hr>
						<div class="form-group">
							<label class="small font-weight-bold text-dark">Remark</label>
							<textarea name="remarks" id="remarks" class="form-control form-control-sm"></textarea>
						</div>
						<div class="form-group mt-2">
							<button type="button" id="btnccreatequotation"
								class="btn btn-outline-primary btn-sm fa-pull-right"><i class="fas fa-save"></i>&nbsp;
								Create Job Quotation</button>
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
										id="materialstock">
										<thead>
											<tr>
												<th>#</th>
												<th>Product Name</th>
												<th>Batch No</th>
												<th>Location</th>
												<th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Category</th>
												<th>Total</th>
												<th class="text-right">Actions</th>
											</tr>
										</thead>
                                        <tbody></tbody>
									</table>
                                    <div class="form-group mt-2 text-right">
										<button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-4"
											><i class="fas fa-exchange-alt"></i>&nbsp;Transfer Stock</button>
									</div>
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
    $('#materialstock').DataTable({
      responsive: true,
      pageLength: 5,
      columnDefs: [
        { targets: -1, orderable: false } // Disable sorting on "Actions" column
      ]
    });
  });
</script>

<?php include "include/footer.php"; ?>

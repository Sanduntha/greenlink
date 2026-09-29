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
				<div class="card">
					<div class="card-body p-0 p-2">
						<div class="row">
							<div class="col-3">
								<form action="" method="post"
									autocomplete="off">
									<div class="form-group mb-1">
										<label class="small font-weight-bold">From Location</label>
										<select class="form-control form-control-sm" name="from" id="from" required>
											<option value="">Select</option>
											
										</select>
									</div>
									<div class="form-group mb-1">
										<label class="small font-weight-bold">To Location</label>
										<select class="form-control form-control-sm" name="to" id="to" required>
											<option value="">Select</option>
											
										</select>
									</div>
									<div class="form-group mb-1">
										<label class="small font-weight-bold">Product</label>
										<select class="form-control form-control-sm" name="product" id="product" required>
											<option value="">Select</option>
											
										</select>
									</div>
									<div class="form-group mb-1">
										<label class="small font-weight-bold">Batch No</label>
										<input type="text" class="form-control form-control-sm" name="batch_no" id="batch_no"
											required>
									</div>
                                    <div class="form-group mb-1">
										<label class="small font-weight-bold">Qty</label>
										<input type="text" class="form-control form-control-sm" name="qty" id="qty"
											required>
									</div>
									<div class="form-group mt-2 text-right">
										<button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-4"
											><i
												class="far fa-save"></i>&nbsp;Add</button>
									</div>
									<input type="hidden" name="recordOption" id="recordOption" value="1">
									<input type="hidden" name="recordID" id="recordID" value="">
								</form>
							</div>
							<div class="col-9">
								<div class="scrollbar pb-3" id="style-2">
									<table class="table table-bordered table-striped table-sm nowrap"
										id="stocktransfer">
										<thead>
											<tr>
												<th>#</th>
												<th>From Location</th>
												<th>To Location</th>
												<th>Product</th>
												<th>Batch No</th>
                                                <th>Qty</th>
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
    $('#stocktransfer').DataTable({
      responsive: true,
      pageLength: 5,
      columnDefs: [
        { targets: -1, orderable: false } // Disable sorting on "Actions" column
      ]
    });
  });
</script>

<?php include "include/footer.php"; ?>

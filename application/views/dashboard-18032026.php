<?php 
include "include/header.php";  
include "include/topnavbar.php"; 
?>
<div id="layoutSidenav">
	<div id="layoutSidenav_nav">
		<?php 
        include "include/menubar.php";
         ?>
	</div>
	<div id="layoutSidenav_content">
		<main>
			<div class="page-header page-header-light bg-white shadow">
				<div class="container-fluid">
					<div class="page-header-content py-3">
						<h1 class="page-header-title font-weight-light">
							<div class="page-header-icon"><i class="fas fa-desktop"></i></div>
							<span>Dashboard</span>
						</h1>
					</div>
				</div>
			</div>
			<div class="container-fluid mt-2 p-0 p-2">
				<div class="card rounded-0">
					<div class="card-body p-0 p-2">
						<!-- <div class="row row-cols-1 row-cols-md-4">
							<div class="col-4 mb-3">
								<div class="card shadow-none border-warning card-icon p-0">
									<div class="row no-gutters h-100">
										<div class="col-auto card-icon-aside-new text-warning">
											<i class="fa fa-server"></i>
										</div>
										<div class="col">
											<div class="card-body p-0 p-2 text-right">
												<h1 class=" text-warning my-1">Rs.<?php echo $servicetotal; ?>
												</h1>
												<h6 class="card-title m-0 small">Servicel	</h6>
											</div>
										</div>
									</div>
									<div class="row no-gutters h-100">
										<div class="col">
											<div class="card-body p-0 p-2 text-right">
												<div class="progress" style="height: 3px;">
													<div class="progress-bar bg-warning" role="progressbar"
														style="width: <?php echo $servicetotal; ?>%;"
														aria-valuenow="<?php echo $servicetotal; ?>"
														aria-valuemin="0" aria-valuemax="100"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-4 mb-3">
								<div class="card shadow-none border-primary card-icon p-0">
									<div class="row no-gutters h-100">
										<div class="col-auto card-icon-aside-new text-primary">
											<i class="fa fa-paint-brush"></i>
										</div>
										<div class="col">
											<div class="card-body p-0 p-2 text-right">
												<h1 class=" text-primary my-1">Rs.<?php echo $accidenttotal; ?>
												</h1>
												<h6 class="card-title m-0 small">Accident and paint	</h6>
											</div>
										</div>
									</div>
									<div class="row no-gutters h-100">
										<div class="col">
											<div class="card-body p-0 p-2 text-right">
												<div class="progress" style="height: 3px;">
													<div class="progress-bar bg-primary" role="progressbar"
														style="width: <?php echo $accidenttotal; ?>%;"
														aria-valuenow="<?php echo $accidenttotal; ?>"
														aria-valuemin="0" aria-valuemax="100"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-4 mb-3">
								<div class="card shadow-none border-secondary card-icon p-0">
									<div class="row no-gutters h-100">
										<div class="col-auto card-icon-aside-new text-secondary">
											<i class="fa fa-wrench"></i>
										</div>
										<div class="col">
											<div class="card-body p-0 p-2 text-right">
												<h1 class=" text-secondary my-1">Rs.<?php echo $mechanicaltotal; ?>
												</h1>
												<h6 class="card-title m-0 small">Electrical and Mechanical	</h6>
											</div>
										</div>
									</div>
									<div class="row no-gutters h-100">
										<div class="col">
											<div class="card-body p-0 p-2 text-right">
												<div class="progress" style="height: 3px;">
													<div class="progress-bar bg-secondary" role="progressbar"
														style="width: <?php echo $mechanicaltotal; ?>%;"
														aria-valuenow="<?php echo $mechanicaltotal; ?>"
														aria-valuemin="0" aria-valuemax="100"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-4 mb-3">
								<div class="card shadow-none border-success card-icon p-0">
									<div class="row no-gutters h-100">
										<div class="col-auto card-icon-aside-new text-success">
											<i class="fa fa-credit-card"></i>
										</div>
										<div class="col">
											<div class="card-body p-0 p-2 text-right">
												<h1 class=" text-success my-1">Rs.<?php echo $fulltotal; ?>
												</h1>
												<h6 class="card-title m-0 small">Total</h6>
											</div>
										</div>
									</div>
									<div class="row no-gutters h-100">
										<div class="col">
											<div class="card-body p-0 p-2 text-right">
												<div class="progress" style="height: 3px;">
													<div class="progress-bar bg-success" role="progressbar"
														style="width: <?php echo $fulltotal; ?>%;"
														aria-valuenow="<?php echo $fulltotal; ?>"
														aria-valuemin="0" aria-valuemax="100"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-4 mb-3">
								<div class="card shadow-none border-info card-icon p-0">
									<div class="row no-gutters h-100">
										<div class="col-auto card-icon-aside-new text-info">
											<i class="fa fa-credit-card"></i>
										</div>
										<div class="col">
											<div class="card-body p-0 p-2 text-right">
												<h1 class=" text-info my-1">Rs.<?php echo $pendingtotal; ?>
												</h1>
												<h6 class="card-title m-0 small">Pending Payments</h6>
											</div>
										</div>
									</div>
									<div class="row no-gutters h-100">
										<div class="col">
											<div class="card-body p-0 p-2 text-right">
												<div class="progress" style="height: 3px;">
													<div class="progress-bar bg-info" role="progressbar"
														style="width: <?php echo $pendingtotal; ?>%;"
														aria-valuenow="<?php echo $pendingtotal; ?>"
														aria-valuemin="0" aria-valuemax="100"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-4 mb-3">
								<div class="card shadow-none border-secondary card-icon p-0">
									<div class="row no-gutters h-100">
										<div class="col-auto card-icon-aside-new text-secondary">
											<i class="fa fa-credit-card"></i>
										</div>
										<div class="col">
											<div class="card-body p-0 p-2 text-right">
												<h1 class=" text-secondary my-1">Rs.<?php echo $completedtotal; ?>
												</h1>
												<h6 class="card-title m-0 small">Completed Payments</h6>
											</div>
										</div>
									</div>
									<div class="row no-gutters h-100">
										<div class="col">
											<div class="card-body p-0 p-2 text-right">
												<div class="progress" style="height: 3px;">
													<div class="progress-bar bg-secondary" role="progressbar"
														style="width: <?php echo $completedtotal; ?>%;"
														aria-valuenow="<?php echo $completedtotal; ?>"
														aria-valuemin="0" aria-valuemax="100"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-4 mb-3">
								<div class="card shadow-none border-danger card-icon p-0">
									<div class="row no-gutters h-100">
										<div class="col-auto card-icon-aside-new text-danger">
											<i class="fa fa-check"></i>
										</div>
										<div class="col">
											<div class="card-body p-0 p-2 text-right">
												<h1 class=" text-danger my-1"><?php echo $totalactivejobs; ?>
												</h1>
												<h6 class="card-title m-0 small">Active Jobs</h6>
											</div>
										</div>
									</div>
									<div class="row no-gutters h-100">
										<div class="col">
											<div class="card-body p-0 p-2 text-right">
												<div class="progress" style="height: 3px;">
													<div class="progress-bar bg-danger" role="progressbar"
														style="width: <?php echo $totalactivejobs; ?>%;"
														aria-valuenow="<?php echo $totalactivejobs; ?>"
														aria-valuemin="0" aria-valuemax="100"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						
						</div> -->
					</div>
				</div>

		</main>
		<?php include "include/footerbar.php"; ?>
	</div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
	$(document).ready(function () {

	});

    function addCommas(nStr) {
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}
</script>
<?php include "include/footer.php"; ?>

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

            <!-- Page Header -->
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

            <div class="container-fluid mt-3">

                <!-- ═══════════════════════════════════════
                     SECTION 1 · KPI OVERVIEW
                ═══════════════════════════════════════ -->
                <p class="dash-section-label">Overview</p>
                <div class="row g-3 mb-4">

                    <div class="col-md-4">
                        <div class="dash-kpi">
                            <div class="dash-kpi-label">Total stock on hand</div>
                            <div class="dash-kpi-value text-success">
                                <?= number_format($total_stock->total_stock) ?>
                                <span class="dash-kpi-unit">KG</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="dash-kpi">
                            <div class="dash-kpi-label">Pending GRNs</div>
                            <div class="dash-kpi-value text-warning">
                                <?= number_format($pending_grn_count) ?>
                            </div>
                            <div class="dash-kpi-sub">Awaiting approval</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="dash-kpi">
                            <div class="dash-kpi-label">Batches waiting for processing</div>
                            <div class="dash-kpi-value text-danger">
                                <?= number_format($pending_processing_count) ?>
                            </div>
                            <div class="dash-kpi-sub">Sorting not yet complete</div>
                        </div>
                    </div>

                </div>

                <!-- ═══════════════════════════════════════
                     SECTION 2 · WAREHOUSE CAPACITY
                ═══════════════════════════════════════ -->
                <p class="dash-section-label">Warehouse capacity</p>
                <div class="row g-3 mb-4">

                    <?php foreach ($warehouse_zones as $zone):
                        $used    = isset($zone->used_kg)     ? (float) $zone->used_kg     : 0;
                        $total   = isset($zone->capacity_kg) ? (float) $zone->capacity_kg : 0;
                        $percent = $total > 0 ? ($used / $total) * 100 : 0;
                        $pct     = min(100, $percent);

                        if ($total <= 0) {
                            $barClass  = 'zone-bar-muted';
                            $textClass = 'text-muted';
                            $pctLabel  = 'No capacity set';
                        } elseif ($percent > 90) {
                            $barClass  = 'zone-bar-danger';
                            $textClass = 'text-danger';
                            $pctLabel  = number_format($pct, 1) . '% used';
                        } elseif ($percent > 75) {
                            $barClass  = 'zone-bar-warning';
                            $textClass = 'text-warning';
                            $pctLabel  = number_format($pct, 1) . '% used';
                        } else {
                            $barClass  = 'zone-bar-info';
                            $textClass = 'text-info';
                            $pctLabel  = number_format($pct, 1) . '% used';
                        }
                    ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="dash-zone-card">
                            <div class="dash-zone-name" title="<?= htmlspecialchars($zone->site_name . ' – ' . $zone->rack_number) ?>">
                                <?= htmlspecialchars($zone->site_name) ?> &mdash; <?= htmlspecialchars($zone->rack_number) ?>
                            </div>
                            <div class="dash-zone-kg <?= $textClass ?>">
                                <?= number_format($used) ?> /
                                <?= $total > 0 ? number_format($total) . ' KG' : '<span class="text-muted">N/A</span>' ?>
                            </div>
                            <div class="dash-zone-bar">
                                <div class="dash-zone-fill <?= $barClass ?>" style="width: <?= $pct ?>%"></div>
                            </div>
                            <small class="<?= $textClass ?>"><?= $pctLabel ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>

                </div>

                <hr class="dash-divider">

				<!-- ═══════════════════════════════════════
	 LOW STOCK MATERIALS – SIMPLIFIED
═════════════════════════════════════════ -->
				<p class="dash-section-label">Low Stock Materials (below ROL)</p>
				<div class="row g-3 mb-4">
					<div class="col-12 col-lg-6">
						<div class="dash-panel">
							<div class="dash-panel-header">Materials Needing Reorder</div>

							<?php if (!empty($low_stock_materials)): ?>
								<div class="table-responsive">
									<table class="dash-table">
										<thead>
											<tr>
												<th>Material</th>
												<th class="text-end">Current Stock</th>
												<th class="text-end">Reorder Level</th>
												<th class="text-end">Short by</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($low_stock_materials as $item):
												$shortage = $item->rol - $item->current_stock;
												?>
												<tr>
													<td>
														<a href="<?= base_url('material/view/' . $item->material_code) ?>"
															target="_blank" class="dash-link">
															<?= htmlspecialchars($item->material_name) ?>
														</a>
													</td>
													<td class="text-end text-danger fw-bold">
														<?= number_format($item->current_stock, 1) ?>
													</td>
													<td class="text-end">
														<?= number_format($item->rol, 1) ?>
													</td>
													<td class="text-end text-danger fw-bold">
														<?= number_format($shortage, 1) ?>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							<?php else: ?>
								<div class="dash-empty">No materials currently below reorder level</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="col-md-5">
						<div class="dash-panel">
							<div class="dash-panel-header">Stock by category</div>
							<div class="p-3">
								<?php
								// Build bar data for inline display
								$max_qty = 0;
								foreach ($stock_category as $row) {
									if ($row->total_qty > $max_qty)
										$max_qty = $row->total_qty;
								}
								$cat_colors = ['#7F77DD', '#1D9E75', '#EF9F27', '#D85A30', '#3B8BD4'];
								$i = 0;
								foreach ($stock_category as $row):
									$bar_w = $max_qty > 0 ? round(($row->total_qty / $max_qty) * 100) : 0;
									$color = $cat_colors[$i % count($cat_colors)];
									$i++;
									?>
									<div class="mb-3">
										<div class="d-flex justify-content-between mb-1" style="font-size: 12px;">
											<span><?= htmlspecialchars($row->category_name) ?></span>
											<span class="text-muted"><?= number_format($row->total_qty) ?> KG</span>
										</div>
										<div class="dash-zone-bar">
											<div class="dash-zone-fill"
												style="width: <?= $bar_w ?>%; background: <?= $color ?>;"></div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>

					<!-- Optional: empty space or another small widget -->
					<div class="col-12 col-lg-6">
						<!-- You can leave empty or add future content -->
					</div>
				</div>



                <!-- ═══════════════════════════════════════
                     SECTION 3 · PENDING ITEMS
                ═══════════════════════════════════════ -->
                <p class="dash-section-label">Pending items</p>
                <div class="row g-3 mb-4">

                    <!-- Recent Pending GRNs -->
                    <div class="col-md-6">
                        <div class="dash-panel">
                            <div class="dash-panel-header">Recent pending GRNs</div>
                            <?php if (!empty($recent_pending_grns)): ?>
                                <div class="table-responsive">
                                    <table class="dash-table">
                                        <thead>
                                            <tr>
                                                <th>GRN No</th>
                                                <th>Date</th>
                                                <th>Supplier</th>
                                                <th class="text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_pending_grns as $grn): ?>
                                                <tr>
                                                    <td>
                                                        <a href="<?= base_url('Goodreceive') ?>" target="_blank" class="dash-link">
                                                            <?= htmlspecialchars($grn->grn_no) ?>
                                                        </a>
                                                    </td>
                                                    <td><?= date('Y-m-d', strtotime($grn->date)) ?></td>
                                                    <td><?= htmlspecialchars($grn->supplier_name ?? '—') ?></td>
                                                    <td class="text-end"><?= number_format($grn->total, 2) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="dash-empty">No pending GRNs at the moment</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Batches Awaiting Processing -->
                    <div class="col-md-6">
                        <div class="dash-panel">
                            <div class="dash-panel-header">Batches awaiting processing</div>
                            <?php if (!empty($recent_pending_processing)): ?>
                                <div class="table-responsive">
                                    <table class="dash-table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Batch</th>
                                                <th>Material</th>
                                                <th class="text-end">Qty</th>
                                                <th>Location</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_pending_processing as $item): ?>
                                                <tr>
                                                    <td><?= date('Y-m-d', strtotime($item->allocation_date)) ?></td>
                                                    <td>
                                                        <a href="<?= base_url('allocation/view/' . $item->idtbl_allocation) ?>" target="_blank" class="dash-link">
                                                            <?= htmlspecialchars($item->batch_number ?: '—') ?>
                                                        </a>
                                                    </td>
                                                    <td><?= htmlspecialchars($item->material_name ?? '—') ?></td>
                                                    <td class="text-end"><?= number_format($item->qty) ?></td>
                                                    <td>
                                                        <?= htmlspecialchars($item->site_name ?? '—') ?>
                                                        <?= $item->rack_number ? ' / ' . htmlspecialchars($item->rack_number) : '' ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="dash-empty">No batches currently waiting for processing</div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

                <hr class="dash-divider">

                <!-- ═══════════════════════════════════════
                     SECTION 4 · EXPORT ANALYTICS
                ═══════════════════════════════════════ -->
                <p class="dash-section-label">Export analytics &mdash; <?= date('Y') ?></p>
                <div class="row g-3 mb-4">

                    <div class="col-6 col-md-3">
                        <div class="dash-kpi">
                            <div class="dash-kpi-label">Exports this month</div>
                            <div class="dash-kpi-value text-primary"><?= number_format($exports_this_month) ?></div>
                            <div class="dash-kpi-sub">Completed shipments</div>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="dash-kpi">
                            <div class="dash-kpi-label">Exports this year</div>
                            <div class="dash-kpi-value text-success"><?= number_format($exports_this_year) ?></div>
                            <div class="dash-kpi-sub"><?= date('Y') ?></div>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="dash-kpi">
                            <div class="dash-kpi-label">Revenue growth (MoM)</div>
                            <div class="dash-kpi-value <?= $revenue_growth['growth_class'] ?>">
                                <?= $revenue_growth['growth_icon'] ?>
                                <?= number_format(abs($revenue_growth['growth_percent']), 1) ?>%
                            </div>
                            <div class="dash-kpi-sub">
                                <?= number_format($revenue_growth['current_month_kg']) ?> kg
                                vs <?= number_format($revenue_growth['prev_month_kg']) ?> kg
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="dash-kpi">
                            <div class="dash-kpi-label">Top destination</div>
                            <?php $top = !empty($exports_by_country) ? $exports_by_country[0] : null; ?>
                            <div class="dash-kpi-value text-warning" style="font-size: 1.25rem;">
                                <?= $top ? htmlspecialchars($top->country) : '—' ?>
                            </div>
                            <div class="dash-kpi-sub">
                                <?= $top ? number_format($top->export_count) . ' shipments' : '' ?>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Exports by destination table + stock chart side by side -->
                <div class="row g-3 mb-4">

                    <!-- Exports by destination -->
                    <div class="col-md-7">
                        <div class="dash-panel">
                            <div class="dash-panel-header">Exports by destination</div>
                            <?php if (!empty($exports_by_country)): ?>
                                <div class="table-responsive">
                                    <table class="dash-table">
                                        <thead>
                                            <tr>
                                                <th>Country</th>
                                                <th class="text-end">Shipments</th>
                                                <th class="text-end">Weight (KG)</th>
                                                <th style="width: 120px;">Share</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $max_count = max(array_column((array) $exports_by_country, 'export_count'));
                                            foreach ($exports_by_country as $dest):
                                                $bar_w = $max_count > 0 ? round(($dest->export_count / $max_count) * 100) : 0;
                                            ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($dest->country ?: 'Unspecified') ?></td>
                                                    <td class="text-end"><?= number_format($dest->export_count) ?></td>
                                                    <td class="text-end"><?= number_format($dest->total_weight_kg, 1) ?></td>
                                                    <td>
                                                        <div class="dash-dest-bar">
                                                            <div class="dash-dest-fill" style="width: <?= $bar_w ?>%"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="dash-empty">No export data for <?= date('Y') ?> yet.</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    

                </div>

            </div><!-- /container-fluid -->
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>

<style>
/* ── Dashboard layout tokens ─────────────────────────── */
.dash-section-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #111111bd;
    margin-bottom: 12px;
}
.dash-divider {
    border: none;
    border-top: 1px solid #e3e6ea;
    margin: 2rem 0;
}

/* ── KPI cards ───────────────────────────────────────── */
.dash-kpi {
    background: #fff;
    border: 1px solid #e3e6ea;
    border-radius: 12px;
    padding: 28px 28px;
    height: 100%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.dash-kpi-label {
    font-size: 14px;
    font-weight: 600;
    color: #1a1a1aa4;
    margin-bottom: 10px;
    letter-spacing: 0.01em;
}
.dash-kpi-value {
    font-size: 3rem;
    font-weight: 700;
    line-height: 1.1;
    margin-bottom: 8px;
    color: #111111;
}
.dash-kpi-unit {
    font-size: 1.4rem;
    font-weight: 600;
    color: inherit;
}
.dash-kpi-sub {
    font-size: 13px;
    font-weight: 500;
    color: #1a1a1a;
}

/* ── Zone cards ──────────────────────────────────────── */
.dash-zone-card {
    background: #fff;
    border: 1px solid #e3e6ea;
    border-radius: 12px;
    padding: 18px 20px;
    height: 100%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.dash-zone-name {
    font-size: 14px;
    font-weight: 600;
    color: #111111;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 8px;
}
.dash-zone-kg {
    font-size: 15px;
    font-weight: 700;
    margin-bottom: 10px;
}
.dash-zone-bar {
    height: 10px;
    border-radius: 5px;
    background: #e3e6ea;
    overflow: hidden;
    margin-bottom: 8px;
}
.dash-zone-fill        { height: 100%; border-radius: 5px; }
.zone-bar-info         { background: #0aa8c8; }
.zone-bar-warning      { background: #e0a800; }
.zone-bar-danger       { background: #c82333; }
.zone-bar-muted        { background: #a0aab4; }

/* ── Panels (table containers) ───────────────────────── */
.dash-panel {
    background: #fff;
    border: 1px solid #e3e6ea;
    border-radius: 12px;
    overflow: hidden;
    height: 100%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.dash-panel-header {
    padding: 14px 18px;
    font-size: 11px;
    font-weight: 700;
    color: #111111;
    border-bottom: 1px solid #e3e6ea;
    background: #f8f9fb;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}

/* ── Tables ──────────────────────────────────────────── */
.dash-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    margin: 0;
}
.dash-table th {
    padding: 11px 16px;
    text-align: left;
    color: #111111;
    font-weight: 700;
    border-bottom: 1px solid #e3e6ea;
    background: #f8f9fb;
    white-space: nowrap;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.09em;
}
.dash-table td {
    padding: 12px 16px;
    border-bottom: 1px solid #edf0f3;
    vertical-align: middle;
    font-weight: 600;
    color: #111111;
    font-size: 14px;
}
.dash-table tbody tr:last-child td { border-bottom: none; }
.dash-table tbody tr:hover { background: #f8f9fb; }
.dash-link { color: #3a86ff; text-decoration: none; font-weight: 700; }
.dash-link:hover { text-decoration: underline; }
.dash-empty {
    padding: 32px;
    text-align: center;
    font-size: 14px;
    font-weight: 500;
    color: #111111;
}

/* ── Destination bar ─────────────────────────────────── */
.dash-dest-bar {
    height: 10px;
    border-radius: 5px;
    background: #e3e6ea;
    overflow: hidden;
}
.dash-dest-fill {
    height: 100%;
    border-radius: 5px;
    background: #0aa8c8;
}

/* ── Stock category bars ─────────────────────────────── */
.dash-panel .p-3 .mb-3 span {
    font-size: 13px;
    font-weight: 600;
    color: #111111;
}

/* ── zone small label ────────────────────────────────── */
.dash-zone-card small {
    font-size: 13px;
    font-weight: 600;
    color: inherit;
}
</style>

<?php include "include/footer.php"; ?>
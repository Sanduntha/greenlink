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
                            <div class="page-header-icon"><i class="fas fa-file"></i></div>
                            <span>Stock Report</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body">
                        <h2>Stock In Report</h2>
                        <form action="<?= base_url('StockReport/generate') ?>" method="post" target="_blank">
                            <div class="form-row align-items-end">
                                <!-- Main Material -->
                                <div class="form-group col-md-4">
                                    <label>Main Material:</label>
                                    <select name="main_material_id" id="mainMaterial" class="form-control" required>
                                        <option value="">-- Select Main Material --</option>
                                        <?php foreach ($Mainmaterials as $main): ?>
                                            <option value="<?= $main->idtbl_material_main_cat ?>"><?= $main->categoryname ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Row Material -->
                                <div class="form-group col-md-4">
                                    <label>Row Material:</label>
                                    <select name="material_id" id="rowMaterial" class="form-control" required>
                                        <option value="">-- Select Row Material --</option>
                                    </select>
                                </div>

                                <!-- Month-Year -->
                                <div class="form-group col-md-3">
                                    <label>Select Month & Year:</label>
                                    <input type="month" name="month_year" class="form-control" required>
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group col-md-1">
                                    <button type="submit" class="btn btn-primary btn-block">Generate</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="container-fluid mt-4 p-0 p-2">
                <div class="card">
                    <div class="card-body">
                        <h2>Balance Stock</h2>
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Month-Year Dropdown -->
                                <label for="monthYear">Select Month & Year</label>
                                <input type="month" id="monthYear" class="form-control">
                            </div>
                            <div class="col-md-2 mt-4">
                                <!-- Generate PDF Button -->
                                <button id="generatePDF" class="btn btn-primary mt-2">Generate PDF</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Report Section -->
            <div class="container-fluid mt-4 p-0 p-2">
                <div class="card">
                    <div class="card-body">
                        <h2>Materials Summary Report</h2>
                        <form id="summaryForm" method="post" target="_blank">
                            <div class="form-row align-items-end">
                                <div class="form-group col-md-10">
                                    <label>Select Materials:</label>
                                    <select name="material_ids[]" id="rowMaterialsSummary" class="form-control" multiple
                                        style="height: 200px;" required>
                                        <option value="">Loading materials...</option>
                                    </select>
                                    <small class="form-text text-muted">Hold Ctrl to select multiple materials</small>
                                </div>

                                <div class="form-group col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block">Generate Summary</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>

<script>
    // Load all materials when page loads for summary report
    $(document).ready(function () {
        loadAllMaterials();
    });

    function loadAllMaterials() {
        $.ajax({
            url: "<?= base_url('StockReport/getAllMaterials') ?>",
            method: "GET",
            success: function (response) {
                let data = JSON.parse(response);
                let options = '';
                data.forEach(material => {
                    options += `<option value="${material.idtbl_row_material}">${material.material_name}</option>`;
                });
                $('#rowMaterialsSummary').html(options);
            },
            error: function () {
                $('#rowMaterialsSummary').html('<option value="">Error loading materials</option>');
            }
        });
    }

    // Original functionality for single material report
    $('#mainMaterial').on('change', function () {
        var mainId = $(this).val();
        $('#rowMaterial').html('<option>Loading...</option>');
        if (mainId !== "") {
            $.ajax({
                url: "<?= base_url('StockReport/getRowMaterials/') ?>" + mainId,
                method: "GET",
                success: function (response) {
                    let data = JSON.parse(response);
                    let options = '<option value="">-- Select Row Material --</option>';
                    data.forEach(material => {
                        options += `<option value="${material.idtbl_row_material}">${material.material_name}</option>`;
                    });
                    $('#rowMaterial').html(options);
                }
            });
        }
    });

    // Summary form submission
    $('#summaryForm').on('submit', function (e) {
        e.preventDefault();

        var materialIds = $('#rowMaterialsSummary').val();

        if (!materialIds || materialIds.length === 0) {
            alert("Please select at least one material.");
            return false;
        }

        // Set the action URL
        $(this).attr('action', "<?= base_url('StockReport/generateSummary') ?>");

        // Submit the form
        this.submit();
    });

    $('#generatePDF').on('click', function () {
        var monthYear = $('#monthYear').val();
        if (!monthYear) {
            alert("Please select a month and year.");
            return;
        }

        // Split the input into year and month
        var parts = monthYear.split("-");
        var year = parts[0];
        var month = parts[1];

        // Trigger PDF generation route
        var url = "<?php echo base_url() ?>StockReport/GetBalanceStock?month=" + month + "&year=" + year;
        window.open(url, '_blank');
    });
</script>
<?php include "include/footer.php"; ?>
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
                            <div class="page-header-icon"><i class="fas fa-boxes"></i></div>
                            <span>Material Stock by Category</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="categoryFilter">Filter by Category</label>
                                    <select class="form-control form-control-sm" id="categoryFilter">
                                        <option value="all">All Categories</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['idtbl_material_main_cat'] ?>">
                                                <?= $category['categoryname'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="searchTerm">Search</label>
                                    <input type="text" class="form-control form-control-sm" id="searchTerm"
                                        placeholder="Search by material, location, rack number...">
                                    <small class="form-text text-muted">Search in material name, location, or rack number</small>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-center mb-2">
                                <button class="btn btn-primary btn-sm mr-2" id="searchBtn">Search</button>
                                <button class="btn btn-secondary btn-sm" id="resetBtn">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="materialTablesContainer">
                    <!-- Tables will be loaded here dynamically -->
                </div>

                <div id="categoryTableTemplate" class="d-none">
                    <div class="card mb-4 category-table">
                        <div class="card-header py-2">
                            <h6 class="m-0 font-weight-bold text-primary">{categoryName}</h6>
                        </div>
                        <div class="card-body p-0 p-2">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm display material-datatable"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Material Name</th>
                                            <th>Site / Location</th>
                                            <th>Zone</th>
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
        </main>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function () {
        var addcheck = '<?php echo $addcheck; ?>';
        var editcheck = '<?php echo $editcheck; ?>';
        var statuscheck = '<?php echo $statuscheck; ?>';
        var deletecheck = '<?php echo $deletecheck; ?>';
        let dataTables = {};

        loadMaterialData();

        $('#searchBtn').click(function () {
            loadMaterialData();
        });

        $('#resetBtn').click(function () {
            $('#categoryFilter').val('all');
            $('#searchTerm').val('');
            loadMaterialData();
        });

        $('#searchTerm').keypress(function (e) {
            if (e.which === 13) {
                loadMaterialData();
            }
        });

        $('#categoryFilter').change(function () {
            loadMaterialData();
        });

        function loadMaterialData() {
            const categoryId = $('#categoryFilter').val();
            const searchTerm = $('#searchTerm').val();

            $.ajax({
                url: 'scripts/materialavailabilitylist.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    categoryId: categoryId,
                    searchTerm: searchTerm,
                    draw: 1,
                    start: 0,
                    length: -1
                },
                success: function (response) {
                    renderMaterialTables(response.data);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    toastr.error('Failed to load material data');
                }
            });
        }

        function renderMaterialTables(data) {
            const groupedData = {};
            data.forEach(item => {
                if (!groupedData[item.categoryname]) {
                    groupedData[item.categoryname] = [];
                }
                groupedData[item.categoryname].push(item);
            });

            $('#materialTablesContainer').empty();
            dataTables = {};

            Object.keys(groupedData).forEach(categoryName => {
                const tableHtml = $('#categoryTableTemplate').html()
                    .replace('{categoryName}', categoryName);

                const $tableContainer = $(tableHtml);
                $('#materialTablesContainer').append($tableContainer);

                const $table = $tableContainer.find('.material-datatable');
                const tableId = 'table-' + categoryName.replace(/\s+/g, '-').toLowerCase();
                $table.attr('id', tableId);

                dataTables[categoryName] = $table.DataTable({
                    data: groupedData[categoryName],
                    columns: [
                        {
                            data: null,
                            render: function (data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        { data: 'material_name' },
                        { data: 'location' },
                        { 
                            data: 'rack_number',
                            render: function(data, type, row) {
                                return data ? data : '<span class="text-muted">N/A</span>';
                            }
                        },
                        {
                            data: 'quantity',
                            className: "text-right",
                            render: function(data, type, row) {
                                return data ? parseFloat(data).toLocaleString('en-US') : '0';
                            }
                        }
                    ],
                    responsive: true,
                    dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    language: {
                        emptyTable: "No materials found in this category",
                        search: "_INPUT_",
                        searchPlaceholder: "Search in this category..."
                    },
                    initComplete: function () {
                        this.api().columns().every(function () {
                            var column = this;
                        });
                    }
                });
            });

            if (Object.keys(groupedData).length === 0) {
                $('#materialTablesContainer').html(`
                <div class="alert alert-info">
                    No material data found matching your criteria.
                </div>
            `);
            }
        }
    });
</script>
<?php include "include/footer.php"; ?>
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
                            <span>Raw Materials</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-4">
                                <form action="<?php echo base_url() ?>Rowmaterials/Rowmaterialsinsertupdate" method="post"
                                    autocomplete="off" id="rawMaterialForm" novalidate enctype="multipart/form-data">
                                    
                                    <!-- Basic Material Information -->
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Material Main Category*</label>
                                        <select class="form-control selecter2 form-control-sm" name="materialmaincategory" id="materialmaincategory" required>
                                            <option value="">Select</option>
                                            <?php foreach ($maincategorylist->result() as $rowmateriallist) { ?>
                                            <option value="<?php echo $rowmateriallist->idtbl_material_main_cat ?>">
                                                <?php echo $rowmateriallist->categoryname ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Material Name*</label>
                                        <input type="text" class="form-control form-control-sm" name="materialname" id="materialname" required>
                                    </div>
                                    
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Material Code*</label>
                                        <input type="text" class="form-control form-control-sm" name="materialcode" id="materialcode" required>
                                    </div>
                                    
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Measurement*</label>
                                        <select class="form-control selecter2 form-control-sm" name="measurment" id="measurment" required>
                                            <option value="">Select</option>
                                            <?php foreach ($measurmentlist->result() as $rowmeasurmentlist) { ?>
                                            <option value="<?php echo $rowmeasurmentlist->idtbl_mesurements ?>">
                                                <?php echo $rowmeasurmentlist->measure_type ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    
                                    <!-- ROL field removed - will be set to NULL in model -->
                                    
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Zone</label>
                                        <select class="form-control selecter2 form-control-sm" name="racknumber" id="racknumber">
                                            <option value="">Select Zone</option>
                                            <?php foreach ($racklist->result() as $rowracklist) { ?>
                                                <option value="<?php echo $rowracklist->idtbl_rack ?>">
                                                    <?php echo $rowracklist->rack_number ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold">Attach Files</label>
                                        <input type="file" class="form-control form-control-sm" name="attachment" id="attachment" accept=".pdf,.jpg,.jpeg,.png,.gif">
                                        <small class="text-muted" style="font-size: 10px;">Allowed: PDF, JPG, PNG, GIF (Max: 5MB)</small>
                                    </div>

                                    <!-- Supplier Section -->
                                    <hr class="my-2">
                                    <label class="small font-weight-bold text-primary">Suppliers &amp; Pricing*</label>

                                    <div class="row mb-1">
                                        <div class="col-12">
                                            <select class="form-control selecter2 form-control-sm" id="sup_select" style="width: 100%;">
                                                <option value="">Select Supplier</option>
                                                <?php foreach ($supplierlist->result() as $r) { ?>
                                                    <option value="<?php echo $r->idtbl_supplier ?>" data-name="<?php echo $r->name ?>">
                                                        <?php echo $r->name ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-2">
                                        <div class="col-5">
                                            <input type="number" class="form-control form-control-sm" id="sup_unitprice"
                                                placeholder="Unit Price" step="0.01" min="0">
                                        </div>
                                        <div class="col-5">
                                            <input type="number" class="form-control form-control-sm" id="sup_saleprice"
                                                placeholder="Sale Price" step="0.01" min="0">
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-success btn-sm btn-block" id="btnAddSupplier">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Supplier table -->
                                    <table class="table table-sm table-bordered" id="tblSuppliers">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Supplier</th>
                                                <th>Unit Price</th>
                                                <th>Sale Price</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="supplierRows">
                                            <tr id="noSuppliersRow">
                                                <td colspan="4" class="text-center text-muted">No suppliers added yet</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                    <!-- hidden inputs built from supplier table -->
                                    <div id="supplierInputs"></div>

                                    <div class="form-group mt-2 text-right">
                                        <button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-4"
                                            <?php if($addcheck==0){echo 'disabled';} ?>>
                                            <i class="far fa-save"></i>&nbsp;Add
                                        </button>
                                    </div>
                                    
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                    <input type="hidden" name="existing_attachment" id="existing_attachment" value="">
                                </form>
                            </div>
                            
                            <div class="col-8">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm nowrap"
                                        id="tblmeasurment" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Main Material</th>
                                                <th>Name</th>
                                                <th>Code</th>
                                                <th>Supplier</th>
                                                <th>Measurment</th>
                                                <th>Zone</th> 
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
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function () {
        var addcheck = '<?php echo $addcheck; ?>';
        var editcheck = '<?php echo $editcheck; ?>';
        var statuscheck = '<?php echo $statuscheck; ?>';
        var deletecheck = '<?php echo $deletecheck; ?>';

        $('.selecter2').select2({
            width: '100%'
        });
        
        // Initialize supplier select with select2
        $('#sup_select').select2({
            width: '100%',
            placeholder: 'Select Supplier',
            allowClear: true
        });

        // Supplier table functions
        function updateNoSuppliersMessage() {
            if ($('#supplierRows tr').length === 0 || ($('#supplierRows tr').length === 1 && $('#supplierRows tr').first().attr('id') === 'noSuppliersRow')) {
                $('#supplierRows').html('<tr id="noSuppliersRow"><td colspan="4" class="text-center text-muted">No suppliers added yet</td></tr>');
            }
        }

        function refreshSupplierInputs() {
            var suppliers = [];
            
            $('#supplierRows tr').each(function() {
                if ($(this).attr('id') === 'noSuppliersRow') return;
                
                var supplierId = $(this).data('supplier-id');
                var supplierName = $(this).data('supplier-name');
                var unitprice = $(this).find('.supplier-unitprice').val();
                var saleprice = $(this).find('.supplier-saleprice').val();
                
                if (supplierId && unitprice && saleprice) {
                    suppliers.push({
                        id: supplierId,
                        name: supplierName,
                        unitprice: unitprice,
                        saleprice: saleprice
                    });
                }
            });
            
            // Build hidden inputs
            var html = '';
            $.each(suppliers, function(index, sup) {
                html += '<input type="hidden" name="suppliers[]" value="' + sup.id + '">';
                html += '<input type="hidden" name="unitprices[]" value="' + sup.unitprice + '">';
                html += '<input type="hidden" name="saleprices[]" value="' + sup.saleprice + '">';
            });
            $('#supplierInputs').html(html);
            
            return suppliers;
        }

        // Add supplier to table
        function addSupplierToTable(supplierId, supplierName, unitprice, saleprice) {
            // Remove no suppliers message if exists
            if ($('#noSuppliersRow').length) {
                $('#supplierRows').empty();
            }
            
            // Check if supplier already added
            if ($('#supplierRows tr[data-supplier-id="' + supplierId + '"]').length > 0) {
                alert('This supplier is already added!');
                return false;
            }
            
            var rowHtml = `
                <tr data-supplier-id="${supplierId}" data-supplier-name="${supplierName}">
                    <td>
                        ${supplierName}
                        <input type="hidden" class="supplier-id" value="${supplierId}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm supplier-unitprice" 
                               value="${unitprice}" step="0.01" min="0" style="width: 100px;">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm supplier-saleprice" 
                               value="${saleprice}" step="0.01" min="0" style="width: 100px;">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm btn-remove-supplier" title="Remove Supplier">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            $('#supplierRows').append(rowHtml);
            
            // Bind change events
            $('#supplierRows tr[data-supplier-id="' + supplierId + '"] .supplier-unitprice, #supplierRows tr[data-supplier-id="' + supplierId + '"] .supplier-saleprice').on('change keyup', function() {
                refreshSupplierInputs();
            });
            
            refreshSupplierInputs();
            return true;
        }

        // Add button click handler
        $('#btnAddSupplier').click(function() {
            var supplierId = $('#sup_select').val();
            var supplierName = $('#sup_select option:selected').text();
            
            // For select2, get the text properly
            if (!supplierName || supplierName == 'Select Supplier') {
                supplierName = $('#sup_select option:selected').data('name');
            }
            
            var unitprice = $('#sup_unitprice').val();
            var saleprice = $('#sup_saleprice').val();
            
            if (!supplierId) {
                alert('Please select a supplier');
                $('#sup_select').focus();
                return;
            }
            if (!unitprice || unitprice <= 0) {
                alert('Please enter a valid unit price');
                $('#sup_unitprice').focus();
                return;
            }
            if (!saleprice || saleprice <= 0) {
                alert('Please enter a valid sale price');
                $('#sup_saleprice').focus();
                return;
            }
            
            addSupplierToTable(supplierId, supplierName, unitprice, saleprice);
            
            // Clear inputs
            $('#sup_select').val('').trigger('change');
            $('#sup_unitprice').val('');
            $('#sup_saleprice').val('');
        });
        
        // Remove supplier
        $(document).on('click', '.btn-remove-supplier', function() {
            if (confirm('Are you sure you want to remove this supplier?')) {
                $(this).closest('tr').remove();
                refreshSupplierInputs();
                updateNoSuppliersMessage();
            }
        });
        
        // Allow Enter key in price fields to add supplier
        $('#sup_unitprice, #sup_saleprice').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#btnAddSupplier').click();
            }
        });

        $('#tblmeasurment').DataTable({
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
                    title: 'Raw Materials Information',
                    text: '<i class="fas fa-file-csv mr-2"></i> CSV',
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-danger btn-sm',
                    title: 'Raw Materials Information',
                    text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
                },
                {
                    extend: 'print',
                    title: 'Raw Materials Information',
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
                url: "<?php echo base_url() ?>scripts/rowmateriallist.php",
                type: "POST",
            },
            "order": [[1, "asc"]],
            "columns": [
                {
                    "data": null,
                    "orderable": false,
                    "searchable": false,
                    "render": function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { "data": "categoryname" },
                { "data": "material_name" },
                { "data": "material_code" },
                { "data": "name" },
                { "data": "measure_type" },
                { "data": "rack_number" },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function (data, type, full) {
                        var button = '';
                        button += '<button class="btn btn-primary btn-sm btnEdit mr-1 ';
                        if (editcheck != 1) {
                            button += 'd-none';
                        }
                        button += '" id="' + full['idtbl_row_material'] +
                            '"><i class="fas fa-pen"></i></button>';
                        if (full['status'] == 1) {
                            button += '<a href="<?php echo base_url() ?>Rowmaterials/Rowmaterialsstatus/' +
                                full['idtbl_row_material'] +
                                '/2" onclick="return deactive_confirm()" target="_self" class="btn btn-success btn-sm mr-1 ';
                            if (statuscheck != 1) {
                                button += 'd-none';
                            }
                            button += '"><i class="fas fa-check"></i></a>';
                        } else {
                            button += '<a href="<?php echo base_url() ?>Rowmaterials/Rowmaterialsstatus/' +
                                full['idtbl_row_material'] +
                                '/1" onclick="return active_confirm()" target="_self" class="btn btn-warning btn-sm mr-1 ';
                            if (statuscheck != 1) {
                                button += 'd-none';
                            }
                            button += '"><i class="fas fa-times"></i></a>';
                        }
                        button += '<a href="<?php echo base_url() ?>Rowmaterials/Rowmaterialsstatus/' +
                            full['idtbl_row_material'] +
                            '/3" onclick="return delete_confirm()" target="_self" class="btn btn-danger btn-sm mr-1';
                        if (deletecheck != 1) {
                            button += 'd-none';
                        }
                        button += '"><i class="fas fa-trash-alt"></i></a>';
                        if (full['attachment']) {
                            button += '<a href="<?php echo base_url() ?>images/material_attachment/' + full['attachment'] + '" target="_blank" class="btn btn-info btn-sm mr-1" data-toggle="tooltip" title="View Attachment"><i class="fas fa-paperclip"></i></a>';
                        }
                        return button;
                    }
                }
            ],
            drawCallback: function (settings) {
                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
                var last = null;
                var rowspan = 1;

                api.column(1, { page: 'current' }).data().each(function (group, i) {
                    if (last === group) {
                        rowspan++;
                        $('td:eq(1)', rows[i]).remove();
                        $('td:eq(1)', rows[i - rowspan + 1]).attr('rowspan', rowspan);
                    } else {
                        last = group;
                        rowspan = 1;
                    }
                });

                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        $('form').on('submit', function(e) {
            e.preventDefault();
            
            // Validate at least one supplier
            if ($('#supplierRows tr').length === 0 || $('#noSuppliersRow').length) {
                alert('Please add at least one supplier for this material');
                return false;
            }
            
            var formData = new FormData(this);
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    location.reload();
                },
                error: function(xhr, status, error) {
                    alert('An error occurred. Please try again.');
                }
            });
        });
        
        $('#tblmeasurment tbody').on('click', '.btnEdit', function () {
            var r = confirm("Are you sure, You want to Edit this?");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: '<?php echo base_url() ?>Rowmaterials/Rowmaterialsedit',
                    success: function (result) {
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#materialcode').val(obj.materialcode); 
                        $('#materialname').val(obj.materialname);
                        $('#materialmaincategory').val(obj.maincat).trigger('change');
                        $('#measurment').val(obj.measurment).trigger('change');
                        // ROL is not set in form as it's removed
                        $('#racknumber').val(obj.racknumber).trigger('change');
                        $('#existing_attachment').val(obj.attachment); 
                        
                        // Clear existing supplier rows
                        $('#supplierRows').empty();
                        
                        // Load existing suppliers
                        if (obj.suppliers && obj.suppliers.length > 0) {
                            $.each(obj.suppliers, function(index, supplier) {
                                addSupplierToTable(
                                    supplier.tbl_supplier_idtbl_supplier, 
                                    supplier.supplier_name, 
                                    supplier.unitprice, 
                                    supplier.saleprice
                                );
                            });
                        } else {
                            updateNoSuppliersMessage();
                        }
                        refreshSupplierInputs();
                        
                        if (obj.attachment) {
                            $('#attachment').parent().append(
                                '<div class="mt-1">' +
                                'Current Attachment: <a href="<?php echo base_url() ?>images/material_attachment/' + obj.attachment + '" target="_blank">' + obj.attachment + '</a>' +
                                '</div>'
                            );
                        }
                        
                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });

        function clearAllErrors() {
            $('.form-control, select, input[type="file"]').removeClass('is-invalid');
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

        function validateFields(fieldConfigs) {
            let hasError = false;
            let firstErrorField = null;

            fieldConfigs.forEach(function (conf) {
                let field = $('#' + conf.id);
                let value = field.val() ? field.val().trim() : '';
                let errorMsg = null;

                if (value === '') {
                    errorMsg = conf.label + ' is required';
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
        });

        $('#attachment').on('change', function () {
            $(this).removeClass('is-invalid');
        });

        $('#rawMaterialForm').on('submit', function (e) {
            clearAllErrors();

            let requiredFields = [
                { id: 'materialmaincategory', label: 'Material Main Category' },
                { id: 'materialname', label: 'Material Name' },
                { id: 'materialcode', label: 'Material Code' },
                { id: 'measurment', label: 'Measurement' }
            ];

            if (!validateFields(requiredFields)) {
                e.preventDefault();
                return false;
            }
        });
    });

    function deactive_confirm() {
        return confirm("Are you sure you want to deactivate this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to activate this?");
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
    }
</script>
<?php include "include/footer.php"; ?>
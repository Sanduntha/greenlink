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
                            <div class="page-header-icon"><i data-feather="users"></i></div>
                            <span>Employee</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-5">
                                <form action="<?php echo base_url() ?>Employee/Employeeinsertupdate" method="post"
                                    autocomplete="off" id="employeeForm" novalidate>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold">Title</label>
                                            <input type="text" class="form-control form-control-sm" name="title"
                                                id="title">
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold">First Name*</label>
                                            <input type="text" class="form-control form-control-sm" name="fname"
                                                id="fname" required>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold">Middle Name</label>
                                            <input type="text" class="form-control form-control-sm" name="mname"
                                                id="mname">
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold">Last Name</label>
                                            <input type="text" class="form-control form-control-sm" name="lname"
                                                id="lname">
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold">Designation</label>
                                            <select class="form-control form-control-sm select2" name="designation"
                                                id="designation">
                                                <option value="">-- Select Designation --</option>

                                                <option value="Managing Director">Managing Director</option>
                                                <option value="Director">Director</option>
                                                <option value="General Manager">General Manager</option>
                                                <option value="Manager">Manager</option>
                                                <option value="Assistant Manager">Assistant Manager</option>
                                                <option value="Supervisor">Supervisor</option>

                                                <option value="Team Lead">Team Lead</option>



                                                <option value="HR Manager">HR Manager</option>
                                                <option value="HR Executive">HR Executive</option>

                                                <option value="Accountant">Accountant</option>
                                                <option value="Finance Manager">Finance Manager</option>

                                                <option value="Sales Executive">Sales Executive</option>
                                                <option value="Marketing Executive">Marketing Executive</option>

                                                <option value="Intern">Intern</option>
                                                <option value="Trainee">Trainee</option>
                                            </select>
                                        </div>

                                        <div class="col">
                                            <label class="small font-weight-bold">Join Date*</label>
                                            <input type="date" class="form-control form-control-sm" name="joindate"
                                                id="joindate" required>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold">Mobile</label>
                                            <input type="text" class="form-control form-control-sm" name="contact"
                                                id="contact">
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold">Phone</label>
                                            <input type="text" class="form-control form-control-sm" name="contact2"
                                                id="contact2">
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold">Address</label>
                                            <textarea type="text" class="form-control form-control-sm" name="address"
                                                id="address"></textarea>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold">Email</label>
                                            <input type="text" class="form-control form-control-sm" name="email"
                                                id="email">
                                        </div>
                                    </div>
                                    <div class="form-group mt-2 text-right">
                                        <button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-4" <?php if ($addcheck == 0) {
                                            echo 'disabled';
                                        } ?>><i
                                                class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                            <div class="col-7">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap"
                                        id="employeedataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Title</th>
                                                <th>Name</th>
                                                <th>Join Date</th>
                                                <th>Designation</th>
                                                <th>Mobile</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Address</th>
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
        $('#designation').select2({
            placeholder: "Select Designation",
            allowClear: true,
            width: '100%'
        });

        $('#employeedataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            "buttons": [
                { extend: 'csv', className: 'btn btn-success btn-sm', title: 'Employee Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV', },
                { extend: 'pdf', className: 'btn btn-danger btn-sm', title: 'Employee Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF', },
                {
                    extend: 'print',
                    title: 'Employee Information',
                    className: 'btn btn-primary btn-sm',
                    text: '<i class="fas fa-print mr-2"></i> Print',
                    customize: function (win) {
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    },
                },
                // 'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            ajax: {
                url: "<?php echo base_url() ?>scripts/employeelist.php",
                type: "POST", // you can use GET
                // data: function(d) {}
            },
            "order": [[1, "desc"]],
            "columns": [
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "title"
                },
                {
                    "data": "fullname"
                },
                {
                    "data": "joindate"
                },
                {
                    "data": "designation"
                },
                {
                    "data": "contact"
                },
                {
                    "data": "contact2"
                },
                {
                    "data": "email"
                },
                {
                    "data": "address"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function (data, type, full) {
                        var button = '';
                        button += '<button class="btn btn-primary btn-sm btnEdit mr-1 '; if (editcheck != 1) { button += 'd-none'; } button += '" id="' + full['idtbl_employee'] + '"><i class="fas fa-pen"></i></button>';
                        if (full['status'] == 1) {
                            button += '<a href="<?php echo base_url() ?>Employee/Employeestatus/' + full['idtbl_employee'] + '/2" onclick="return deactive_confirm()" target="_self" class="btn btn-success btn-sm mr-1 '; if (statuscheck != 1) { button += 'd-none'; } button += '"><i class="fas fa-check"></i></a>';
                        } else {
                            button += '<a href="<?php echo base_url() ?>Employee/Employeestatus/' + full['idtbl_employee'] + '/1" onclick="return active_confirm()" target="_self" class="btn btn-warning btn-sm mr-1 '; if (statuscheck != 1) { button += 'd-none'; } button += '"><i class="fas fa-times"></i></a>';
                        }
                        button += '<a href="<?php echo base_url() ?>Employee/Employeestatus/' + full['idtbl_employee'] + '/3" onclick="return delete_confirm()" target="_self" class="btn btn-danger btn-sm '; if (deletecheck != 1) { button += 'd-none'; } button += '"><i class="fas fa-trash-alt"></i></a>';

                        return button;
                    }
                }
            ],
            drawCallback: function (settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
        $('#employeedataTable tbody').on('click', '.btnEdit', function () {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: '<?php echo base_url() ?>Employee/Employeeedit',
                    success: function (result) { 
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#title').val(obj.title);
                        $('#fname').val(obj.fname);
                        $('#mname').val(obj.mname);
                        $('#lname').val(obj.lname);
                        $('#empno').val(obj.empno);
                        $('#joindate').val(obj.joindate);
                        $('#designation').val(obj.designation);
                        $('#contact').val(obj.contact);
                        $('#contact2').val(obj.contact2);
                        $('#email').val(obj.email);
                        $('#address').val(obj.address);

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
    });
    function clearAllErrors() {
        $('.form-control, select, textarea').removeClass('is-invalid');
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
            } else if (conf.email) {
                let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    errorMsg = conf.label + ' must be a valid email address';
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

    $(document).on('input change', '.form-control, select, textarea', function () {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });

    $('#employeeForm').on('submit', function (e) {
        clearAllErrors();

        let hasError = false;
        let firstErrorElement = null;

        let requiredFields = [
            { id: 'fname', label: 'First Name' },
            { id: 'joindate', label: 'Join Date' }
        ];

        let recommendedRequired = [
            { id: 'title', label: 'Title' },
            { id: 'lname', label: 'Last Name' },
             { id: 'mname', label: 'Middle Name' },
            { id: 'designation', label: 'Designation' },
            { id: 'contact', label: 'Mobile' },
             { id: 'contact2', label: 'Phone' },
            { id: 'email', label: 'Email', email: true },
            { id: 'address', label: 'Address' }
        ];

        let allRequired = requiredFields.concat(recommendedRequired);

        if (!validateFields(allRequired)) {
            hasError = true;
        }

        if (hasError) {
            e.preventDefault();
            $('.is-invalid').first().focus();
        }
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
<?php include "include/footer.php"; ?>
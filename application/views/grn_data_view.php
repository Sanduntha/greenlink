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
                            <div class="page-header-icon"><i data-feather="check-circle"></i></div>
                            <span>Good Received</span>
                        </h1>
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
                                        id="tblapprovedinquiries">
                                        <thead>
                                            <tr>
                                                <th>Purches Order No</th>
                                                <th>Date</th>
                                                <th>Customer</th>
                                                <th>Status</th>
                                                <th class="text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- view details modal -->
        <div class="modal fade" id="detailsmodal" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header p-2">
                        <h5 class="modal-title" id="detailsModalLabel">Inquiry Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <div class="scrollbar pb-3" id="style-2">
                                <table class="table table-bordered table-striped table-sm nowrap">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Qty</th>
                                            <th>Die Cut</th>
                                            <th>Print</th>
                                            <th>Dimensions Type</th>
                                            <th>Dimensions (LxWxH)</th>
                                            <th>Carton Type</th>
                                            <th>Comments</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modaldetailsbody">
                                    </tbody>
                                </table>
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
        $('#tblapprovedinquiries').DataTable({
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
                {
                    extend: 'csv',
                    className: 'btn btn-success btn-sm',
                    title: 'Approved Customer Inquiries',
                    text: '<i class="fas fa-file-csv mr-2"></i> CSV'
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-danger btn-sm',
                    title: 'Approved Customer Inquiries',
                    text: '<i class="fas fa-file-pdf mr-2"></i> PDF'
                },
                {
                    extend: 'print',
                    title: 'Approved Customer Inquiries',
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
                url: "<?php echo base_url() ?>Approve_inquiries/approved_inquiries",
                type: "POST",
            },
            "order": [[0, "desc"]],
            "columns": [
                { "data": 0 }, // ID
                { "data": 1 }, // Date
                { "data": 2 }, // Customer Name
                {
                    "data": 3, // Status
                    "render": function (data) {
                        return '<span style="color: green;">Approved</span>';
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function (data, type, full) {
                        return '<button class="btn btn-dark btn-sm btnView mr-1" id="' + full[0] + '"><i class="fas fa-eye"></i></button>';
                    }
                }
            ],
            drawCallback: function (settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        // View button click handler
        $(document).on('click', '.btnView', function () {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: { recordID: id },
                url: '<?php echo base_url() ?>Approve_inquiries/Customerinquiryviewjoblist',
                success: function (result) {
                    $('#modaldetailsbody').html(result);
                    $('#detailsmodal').modal('show');
                }
            });
        });
    });
</script>
<?php include "include/footer.php"; ?>
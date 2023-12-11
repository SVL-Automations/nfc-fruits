<?php

include("sessioncheck.php");
date_default_timezone_set('Asia/Kolkata');

$userid = $_SESSION['userid'];
$todaydate = date('Y-m-d');



if (isset($_POST['tabledata'])) {
    $data = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8");
    $result = mysqli_query($connection, "SELECT p.*,DATE_FORMAT(p.date,'%d/%m/%Y') AS niceDate, f.name as farmername, f.mobile as farmermobile from farmer_purchase as p
                                            LEFT join farmer as f ON p.farmerid = f.id
                                            WHERE p.status = 1
                                            order by p.date desc ");
    $data->list = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $result = mysqli_query($connection, "Select id,name,mobile from farmer where status='1' order by name");
    $data->farmerlist = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($data);
    exit();
}


//Add  Bill
if (isset($_POST['Add'])) {

    $msg = new \stdClass();
    $farmerid = mysqli_real_escape_string($connection, $_POST['farmerid']);
    $date = mysqli_real_escape_string($connection, $_POST['date']);

    $caret = mysqli_real_escape_string($connection, $_POST['caretquantity']);
    $weight = mysqli_real_escape_string($connection, $_POST['caretweight']);
    $totalweight = mysqli_real_escape_string($connection, $_POST['totalweight']);
    $discount = mysqli_real_escape_string($connection, $_POST['discount']);
    $actualweight = mysqli_real_escape_string($connection, $_POST['actualweight']);
    $rate = mysqli_real_escape_string($connection, $_POST['rate']);
    $totalamount = mysqli_real_escape_string($connection, $_POST['totalamount']);

    $created = date("Y-m-d H:i:s");
    $updated = date("Y-m-d H:i:s");

    $res = mysqli_query($connection, "INSERT INTO `farmer_purchase`(`farmerid`, `date`, `carate`, `weight`, 
                                                    `totalweight`, `discount`, `actualweight`, `rate`, 
                                                    `totalamount`, `status`, `time`)
                                    VALUES('$farmerid','$date','$caret','$weight',
                                            '$totalweight','$discount','$actualweight','$rate',
                                            '$totalamount','1','$updated')
                                    ");
    if ($res > 0) {
        $msg->value = 1;
        $msg->data = "Purchase Bill Added Successfully.";
        $msg->type = "alert alert-success alert-dismissible ";
    } else {
        $msg->value = 0;
        $msg->data = "Please Try Again";
        $msg->type = "alert alert-danger alert-dismissible ";
    }

    echo json_encode($msg);
    exit();
}

//Delete Received Bill
if (isset($_POST['delete'])) {
    $msg = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8");

    $id = mysqli_real_escape_string($connection, trim(strip_tags($_POST['deleteid'])));


    $res = mysqli_query($connection, "UPDATE `farmer_purchase` SET status=0 WHERE id = '$id' ");

    if ($res > 0) {
        $msg->value = 1;
        $msg->data = "Purchase Bill Deleted Successfully.";
        $msg->type = "alert alert-success alert-dismissible ";
    } else {
        $msg->value = 0;
        $msg->data = " Some data is missing or Please Try Again.";
        $msg->type = "alert alert-danger alert-dismissible ";
    }


    echo json_encode($msg);
    exit();
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $project ?> : Add Purchase Details</title>
    <link rel="icon" href="../../dist/img/small.png" type="image/x-icon">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../../bower_components/select2/dist/css/select2.min.css">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>

<body class="hold-transition skin-blue sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

        <?php include("header.php"); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h4>
                    <?= $project ?>
                    <small><?= $slogan ?></small>
                </h4>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#"> Farmer </a></li>
                    <li class="active"> Purchase </li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Default box -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"> Purchase Details </h3>
                                <a class="btn btn-social-icon btn-success pull-right" title="Add Purchase" data-toggle="modal" data-target="#modaladdpurchase"><i class="fa fa-plus"></i></a>
                                <a class="btn btn-social-icon btn-warning pull-right" title="Generate Bill" data-toggle="modal" data-target="#modalpurchasebill" style="margin-right: 5px !important;"><i class="fa fa-file-excel-o"></i></a>
                            </div>
                            <div class="alert " id="alertclass" style="display: none">
                                <button type="button" class="close" onclick="$('#alertclass').hide()">×</button>
                                <p id="msg"></p>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <div class="box-body  table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class='text-center'>Update</th>
                                            <th class='text-center'>SrNo </th>
                                            <th class='text-center'>Date </th>
                                            <th class='text-center'>Farmer Name </th>
                                            <th class='text-center'>Mobile </th>
                                            <th class='text-center'>Caret </th>
                                            <th class='text-center'>Weight / Caret </th>
                                            <th class='text-center'>Total Weight </th>
                                            <th class='text-center'>Discount / 1000Kg </th>
                                            <th class='text-center'>Actual Weight </th>
                                            <th class='text-center'>Rate / 4kg </th>
                                            <th class='text-center'>Total amount </th>
                                        </tr>
                                    </thead>

                                    <tbody id="tbody">

                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th class='text-center'>Update</th>
                                            <th class='text-center'>SrNo </th>
                                            <th class='text-center'>Date </th>
                                            <th class='text-center'>Farmer Name </th>
                                            <th class='text-center'>Mobile </th>
                                            <th class='text-center'>Caret </th>
                                            <th class='text-center'>Weight / Caret </th>
                                            <th class='text-center'>Total Weight </th>
                                            <th class='text-center'>Discount / 1000Kg </th>
                                            <th class='text-center'>Actual Weight </th>
                                            <th class='text-center'>Rate / 4kg </th>
                                            <th class='text-center'>Total amount </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <!-- /.box-footer-->
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <!-- Add  purchase modal -->
        <form id="addpurchase" action="" method="post">
            <div class="modal fade" id="modaladdpurchase" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-green">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Add Purchase Details</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert " id="addalertclass" style="display: none">
                                <button type="button" class="close" onclick="$('#addalertclass').hide()">×</button>
                                <p id="addmsg"></p>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Farmer Name </label>
                                <select class="form-control select2 " style="width: 100%;" required name="farmerid" id="farmerid">
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Date</label>
                                <input type="date" class="form-control" id="date" name="date" max=<?= date('Y-m-d') ?>>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Caret Quantity</label>
                                <input type="number" step="any" class="form-control" placeholder="Quantity" name="caretquantity" id="caretquantity" required min="0" value="0">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Weight Per Caret</label>
                                <input type="number" step="any" class="form-control" placeholder="Weight Per Caret" name="caretweight" id="caretweight" required min="0" value="0">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Total Weight</label>
                                <input readonly type="number" step="any" class="form-control" placeholder="Total weight" name="totalweight" id="totalweight" required min="0" value="0">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Discount per 1000Kg</label>
                                <input type="number" step="any" class="form-control" placeholder="Discount" name="discount" id="discount" required min="0" value="0">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Actual Weight</label>
                                <input readonly type="number" step="any" class="form-control" placeholder="Actual weight" name="actualweight" id="actualweight" required min="0" value="0">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Rate per 4Kg</label>
                                <input type="number" step="any" class="form-control" placeholder="Rate" name="rate" id="rate" required min="0" value="0">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Total amount</label>
                                <input readonly type="number" step="any" class="form-control" placeholder="Total amount" name="totalamount" id="totalamount" required min="0" value="0">
                            </div>                                                                                 

                        </div>
                        <div class="modal-footer ">
                            <input type="hidden" name="Add" value="Add">
                            <button type="submit" name="Add" value="Add" id='add' class="btn btn-success">Add Purchase</button>
                            <button type="button" class="btn pull-right btn-warning" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->

            </div>
        </form>
        <!-- End Add purchase modal -->

        <!-- Add  purchase bill modal -->
        <form id="generatepurchasebill" action="farmerpurchasebill.php" method="get">
            <div class="modal fade" id="modalpurchasebill" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-green">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Generate Bill</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert " id="addalertclass" style="display: none">
                                <button type="button" class="close" onclick="$('#addalertclass').hide()">×</button>
                                <p id="addmsg"></p>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Farmer Name </label>
                                <select class="form-control select2 " style="width: 100%;" required name="farmerid" id="farmerid">
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Start Date</label>
                                <input type="date" class="form-control" id="sdate" name="sdate" max=<?= date('Y-m-d') ?>>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">End Date</label>
                                <input type="date" class="form-control" id="edate" name="edate" max=<?= date('Y-m-d') ?>>
                            </div>
                        </div>
                        <div class="modal-footer ">
                            <input type="hidden" name="Add" value="Add">
                            <button type="submit" name="Add" value="Add" id='add' class="btn btn-success">Generate Bill</button>
                            <button type="button" class="btn pull-right btn-warning" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->

            </div>
        </form>
        <!-- End Add sales bill modal -->

        <?php include("footer.php"); ?>

    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="../../bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="../../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="../../bower_components/fastclick/lib/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="../../dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../../dist/js/demo.js"></script>
    <!-- DataTables -->
    <script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- Select2 -->
    <script src="../../bower_components/select2/dist/js/select2.full.min.js"></script>
    <script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/dataTables.buttons.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/jszip.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/pdfmake.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/vfs_fonts.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/buttons.html5.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/buttons.print.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/buttons.colVis.min.js"></script>

    <script>
        $(document).ready(function() {

            $('.sidebar-menu').tree()

            $('[data-toggle="tooltip"]').tooltip();

            //display data table
            function tabledata() {
                $('.select2').empty();
                $('#example1').dataTable().fnDestroy();
                $('#example1 tbody').empty();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'tabledata': 'tabledata'
                    },
                    success: function(response) {
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        //console.log(returnedData);
                        var srno = 0;
                        $.each(returnedData['list'], function(key, value) {
                            srno++;
                            button1 = '';
                            button2 = '';

                            button1 = '<button type="submit" name="Delete" id="Delete" ' +
                                'data-deleteid="' + value.id + '"' +
                                '" class="btn btn-xs btn-danger delete-button" style= "margin:5px" title=" Delete purchase bill " ><i class="fa fa-times"></i></button>';
                          
                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-center">' + button1 + '</td>' +
                                '<td class="text-center">' + srno + '</td>' +
                                '<td class="text-center">' + value.niceDate + '</td>' +
                                '<td class="text-center">' + value.farmername + '</td>' +
                                '<td class="text-center">' + value.farmermobile + '</td>' +
                                '<td class="text-center">' + value.carate.toLocaleString('en-IN') + '</td>' +
                                '<td class="text-center">' + value.weight + '</td>' +
                                '<td class="text-center">' + value.totalweight.toLocaleString('en-IN') + '</td>' +
                                '<td class="text-center">' + value.discount + '</td>' +
                                '<td class="text-center">' + value.actualweight.toLocaleString('en-IN') + '</td>' +
                                '<td class="text-center">' + value.rate + '/-</td>' +
                                '<td class="text-center">' + value.totalamount.toLocaleString('en-IN') + '/-</td>' +                                
                                
                                '</tr>';
                            $('#example1 tbody').append(html);
                        });

                        $('.select2').append(new Option("Select farmer", ""));


                        $.each(returnedData['farmerlist'], function(key, value) {
                            $('.select2').append(new Option(value.name, value.id));
                        });

                        $('#example1').DataTable({
                            dom: 'Bfrtip',                            
                            buttons: [{
                                    extend: 'copy',
                                    className: ' btn btn-success',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },                                
                                {
                                    extend: 'csv',
                                    className: ' btn bg-maroon',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'excel',
                                    className: ' btn bg-purple',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'pdf',
                                    className: ' btn bg-navy',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'print',
                                    className: ' btn bg-olive',
                                    exportOptions: {
                                        columns: ':visible'
                                    }
                                },
                                {
                                    extend: 'colvis',
                                    columns: ' :not(.noVis)',
                                    className: ' btn btn-warning '
                                }
                            ],
                            stateSave: true,
                            destroy: true,
                        });
                        //Initialize Select2 Elements
                        $('.select2').select2()
                    }
                });
            }

            tabledata();



            $(document).on("click", ".delete-button", function(e) {

                var id = $(this).data('deleteid');
                $('#alertclass').removeClass();
                $('#msg').empty();
                e.preventDefault();

                if (confirm('Are you sure to remove this record ?')) {

                    $.ajax({
                        url: $(location).attr('href'),
                        dataType: "json",
                        type: 'POST',
                        data: {
                            delete: 'delete',
                            deleteid: id
                        },
                        encode: true,
                        success: function(response) {
                            //console.log(response);                      
                            var returnedData = response;

                            if (returnedData['value'] == 1) {
                                $('#alertclass').addClass(returnedData['type']);
                                $('#msg').append(returnedData['data']);
                                $("#alertclass").show();
                                tabledata();
                            } else {
                                $('#alertclass').addClass(returnedData['type']);
                                $('#msg').append(returnedData['data']);
                                $("#alertclass").show();
                                tabledata();
                            }
                        }
                    });
                }
            });

            //add purchase bill
            $('#addpurchase').submit(function(e) {
                $('#add').prop('disabled', true);
                $('#addalertclass').removeClass();
                $('#addmsg').empty();

                e.preventDefault();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: $('#addpurchase').serialize(),
                    success: function(response) {
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        // console.log(returnedData);
                        $('#add').prop('disabled', false);
                        if (returnedData['value'] == 1) {
                            $('#addpurchase')[0].reset();
                            $('#addalertclass').addClass(returnedData['type']);
                            $('#addmsg').append(returnedData['data']);
                            $("#addalertclass").show();
                            tabledata();
                        } else {
                            $('#addalertclass').addClass(returnedData['type']);
                            $('#addmsg').append(returnedData['data']);
                            $("#addalertclass").show();
                        }
                    }
                });

            });


            //Calculation
            //caretrate
            $("#caretquantity").change(function(e) {                
                totalCalculation();
            });

            $("#caretweight").change(function(e) {                
                totalCalculation();
            });

            //discount
            $("#discount").change(function(e) {                
               totalCalculation();
            });

            //Rate
            $("#rate").change(function(e) {                
                totalCalculation();
            });


            function totalCalculation() {
                $("#totalweight").val(
                    (isNaN(parseFloat($("#caretweight").val())) ? 0 : parseFloat($("#caretweight").val())) *
                    (isNaN(parseFloat($("#caretquantity").val())) ? 0 : parseFloat($("#caretquantity").val())));                

                var totalweight = isNaN(parseFloat($("#totalweight").val())) ? 0 : parseFloat($("#totalweight").val());
                $("#actualweight").val(
                    totalweight - (totalweight/1000 * (isNaN(parseFloat($("#discount").val())) ? 0 : parseFloat($("#discount").val())))
                    );

                $("#totalamount").val(
                    ((isNaN(parseFloat($("#actualweight").val())) ? 0 : parseFloat($("#actualweight").val()))/4.0) *
                    (isNaN(parseFloat($("#rate").val())) ? 0 : parseFloat($("#rate").val())));
                
            }
        })
    </script>
</body>

</html>
<?php

include("sessioncheck.php");

$addedby = $_SESSION['userid'];
date_default_timezone_set('Asia/Kolkata');

if (isset($_POST['data'])) {
  $data = new \stdClass();
  $result = mysqli_query($connection, "SET NAMES utf8");
  $result = mysqli_query($connection, "select count(*) as farmercount from farmer where status = 1");
  $data->farmercount = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $result = mysqli_query($connection, "select count(*) as vendorcount from vendor where status = 1");
  $data->vendorcount = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $result = mysqli_query($connection, "SET NAMES utf8");
  $result = mysqli_query($connection, "select count(*) as labourvendorcount from labour_vendor where status = 1");
  $data->labourvendor = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $result = mysqli_query($connection, "SET NAMES utf8");
  $result = mysqli_query($connection, "select count(*) as workerscount from workers where status = 1");
  $data->workerscount = mysqli_fetch_all($result, MYSQLI_ASSOC);



  $result = mysqli_query($connection, "SET NAMES utf8");
  $result = mysqli_query($connection, "select sum(amount) as farmer_payment from farmer_payment where status = 1");
  $data->farmerpayment = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $result = mysqli_query($connection, "SET NAMES utf8");
  $result = mysqli_query($connection, "select sum(amount) as vendor_payment from vendor_payment where status = 1");
  $data->vendorpayment = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $result = mysqli_query($connection, "SET NAMES utf8");
  $result = mysqli_query($connection, "select sum(totalamount) as farmer_purchase from farmer_purchase where status = 1");
  $data->farmerpurchase = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $result = mysqli_query($connection, "SET NAMES utf8");
  $result = mysqli_query($connection, "select sum(total) as vendor_purchase from vendor_purchase where status = 1");
  $data->vendorpurchase = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $result = mysqli_query($connection, "SET NAMES utf8");
  $result = mysqli_query($connection, "select sum(amount) as labour_vendor_work from labour_vendor_work where status = 1");
  $data->labourvendorwork = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $result = mysqli_query($connection, "SET NAMES utf8");
  $result = mysqli_query($connection, "select sum(amount) as labour_vendor_payment from labour_vendor_payment where status = 1");
  $data->labourvendorpayment = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $result = mysqli_query($connection, "SET NAMES utf8");
  $result = mysqli_query($connection, "select sum(amount) as worker_payment from worker_payment where status = 1");
  $data->workerpayment = mysqli_fetch_all($result, MYSQLI_ASSOC);

  echo json_encode($data);
  exit();
}

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $project ?> : Dashboard</title>
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
  <link rel="stylesheet" href="../../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

  <!-- daterange picker -->
  <link rel="stylesheet" href="../../bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="../../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

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
          <li><a href="#">User</a></li>
          <li class="active">Dashboard</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <h4 id="farmercount">00</h4>

                <p>Total Farmers</p>
              </div>
              <div class="icon">
                <i class="fa fa-users"></i>
              </div>
              <a href="farmer.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
              <div class="inner">
                <h4 id="purchasecount">00</h4>

                <p>Total Bills</p>
              </div>
              <div class="icon">
                <i class="fa fa-file-excel-o"></i>
              </div>
              <a href="farmerpurchase.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-purple">
              <div class="inner">
                <h4 id="farmerpayment">00</h4>

                <p>Total Paid To Farmer</p>
              </div>
              <div class="icon">
                <i class="fa fa-inr"></i>
              </div>
              <a href="farmerpayment.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                <h4 id="labourvendorcount">00</h4>

                <p>Total Labour Vendors</p>
              </div>
              <div class="icon">
                <i class="fa fa-user-secret"></i>
              </div>
              <a href="labour.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h4 id="labourvendorbill">00</h4>

                <p>Total Labour Vendor Bill</p>
              </div>
              <div class="icon">
                <i class="fa fa-cart-plus"></i>
              </div>
              <a href="labourwork.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-maroon">
              <div class="inner">
                <h4 id="labourvendorpayment">00</h4>

                <p>Total Payment Paid </p>
              </div>
              <div class="icon">
                <i class="fa fa-inr"></i>
              </div>
              <a href="labourpayment.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

        </div>

        <div class="row">
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <h4 id="vendorcount">00</h4>

                <p>Total Vendor</p>
              </div>
              <div class="icon">
                <i class="fa fa-users"></i>
              </div>
              <a href="vendors.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
              <div class="inner">
                <h4 id="vendorpurchasecount">00</h4>

                <p>Total Bills</p>
              </div>
              <div class="icon">
                <i class="fa fa-file-excel-o"></i>
              </div>
              <a href="vendorpurchase.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-purple">
              <div class="inner">
                <h4 id="vendorpayment">00</h4>

                <p>Total Paid To Vendor</p>
              </div>
              <div class="icon">
                <i class="fa fa-inr"></i>
              </div>
              <a href="vendorpayment.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>
      </section>


      <section class="content">
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Generate Report</h3>
              </div>
              <div class="alert " id="alertclass" style="display: none">
                <button type="button" class="close" onclick="$('#alertclass').hide()">×</button>
                <p id="msg"></p>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
              <form role="form" id="allreport" action="allreport.php" method="get">
                <div class="box-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Start Date</label>
                    <input type="date" class="form-control" id="sdate" name="sdate" max=<?= date('Y-m-d') ?>>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1">End Date</label>
                    <input type="date" class="form-control" id="edate" name="edate" max=<?= date('Y-m-d') ?>>
                  </div>

                  <!-- /.box-body -->

                  <div class="box-footer">
                    <input type="hidden" name="allreport" value="allreport" id="type">
                    <button type="submit" class="btn btn-success" onclick="return validate()" name="submit">Submit</button>
                    <button type="reset" class="btn btn-warning">Reset</button>
                  </div>
              </form>
            </div>
          </div>
        </div>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

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
  <!-- date-range-picker -->
  <script src="../../bower_components/moment/min/moment.min.js"></script>
  <script src="../../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap datepicker -->
  <script src="../../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <script>
    $(document).ready(function() {
      $('.sidebar-menu').tree()

      //display data card
      function tabledata() {
        $.ajax({
          url: $(location).attr('href'),
          type: 'POST',
          data: {
            'data': 'data'
          },
          success: function(response) {
            // console.log(response); 
            var returnedData = JSON.parse(response);
            console.log(returnedData);
            $('#farmercount').text(returnedData['farmercount'][0]['farmercount']);
            $('#purchasecount').text(parseFloat(returnedData['farmerpurchase'][0]['farmer_purchase']).toLocaleString('en-IN'));
            $('#farmerpayment').text(parseFloat(returnedData['farmerpayment'][0]['farmer_payment']).toLocaleString('en-IN'));

            $('#labourvendorcount').text(returnedData['labourvendor'][0]['labourvendorcount']);
            $('#labourvendorbill').text(parseFloat(returnedData['labourvendorwork'][0]['labour_vendor_work']).toLocaleString('en-IN'));
            $('#labourvendorpayment').text(parseFloat(returnedData['labourvendorpayment'][0]['labour_vendor_payment']).toLocaleString('en-IN'));

            $('#vendorcount').text(returnedData['vendorcount'][0]['vendorcount']);
            $('#vendorpurchasecount').text(parseFloat(returnedData['vendorpurchase'][0]['vendor_purchase']).toLocaleString('en-IN'));
            $('#vendorpayment').text(parseFloat(returnedData['vendorpayment'][0]['vendor_payment']).toLocaleString('en-IN'));

          }
        });
      }

      tabledata();
    })
  </script>
</body>

</html>
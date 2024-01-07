<?php
//include("sessioncheck.php");
session_start();
ob_start();

include("../../db.php");
//Farmer Purchase
if (isset($_POST['farmerpurchase'])) {

    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $data = new \stdClass();

    $data->sdate = date_format(date_create($_POST['sdate']), "d/m/Y");
    $data->edate = date_format(date_create($_POST['edate']), "d/m/Y");

    $res = mysqli_query($connection, "SELECT p.*,DATE_FORMAT(p.date,'%d/%m/%Y') AS niceDate, 
                                        f.name as farmername, f.mobile as farmermobile from farmer_purchase as p
                                        LEFT join farmer as f ON p.farmerid = f.id
                                        WHERE p.date <='$edate' AND p.date>='$sdate' AND p.status = 1
                                        order by p.date asc
                                        ");
    if (mysqli_num_rows($res) > 0) {
        $data->status = "1";
        $data->list = mysqli_fetch_all($res, MYSQLI_ASSOC);

        $res =  mysqli_query($connection, "SELECT IFNULL(SUM(amount),0) as totalSend FROM `farmer_payment` WHERE status = 1 ");
        $data->totalSend = mysqli_fetch_row($res);

        $res =  mysqli_query($connection, "SELECT IFNULL(SUM(totalamount),0) as totalPurchase FROM `farmer_purchase` WHERE status = 1 AND date < '$sdate'");
        $data->totalPurchase = mysqli_fetch_row($res);

        $res =  mysqli_query($connection, "SELECT IFNULL(pending,0) as pending FROM `farmer` ");
        $data->pending = mysqli_fetch_row($res);
    } else {
        $data->status = "0";
        $data->data = "Bill Data Not Found.";
    }
    echo json_encode($data);
    exit();
}

//Farmer Payment
if (isset($_POST['farmerpayment'])) {
    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $data = new \stdClass();

    $data->sdate = date_format(date_create($_POST['sdate']), "d/m/Y");
    $data->edate = date_format(date_create($_POST['edate']), "d/m/Y");

    $res = mysqli_query($connection, "SELECT p.*, DATE_FORMAT(p.date,'%d/%m/%Y') AS niceDate, 
                                        v.name AS farmername, v.mobile AS farmermobile,v.address FROM farmer_payment AS p
                                        LEFT JOIN farmer AS v ON p.farmerid = v.id
                                        WHERE p.date <='$edate' AND p.date>='$sdate' AND p.status = 1
                                        order by p.date asc");
    if (mysqli_num_rows($res) > 0) {
        $data->status = "1";
        $data->list = mysqli_fetch_all($res, MYSQLI_ASSOC);
    } else {
        $data->status = "0";
        $data->data = "Bill Data Not Found.";
    }
    echo json_encode($data);
    exit();
}

//Inhouse worker payment
if (isset($_POST['workerpayment'])) {

    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $data = new \stdClass();

    $data->sdate = date_format(date_create($_POST['sdate']), "d/m/Y");
    $data->edate = date_format(date_create($_POST['edate']), "d/m/Y");

    $res = mysqli_query($connection, "SELECT p.*,DATE_FORMAT(p.date,'%d/%m/%Y') AS niceDate, 
                                        w.name as workername, w.mobile as workermobile 
                                        FROM `worker_payment` as p                                         
                                        LEFT JOIN workers as w ON p.workerid = w.id
                                        WHERE p.date <='$edate' AND p.date>='$sdate' AND p.status = 1
                                        order by p.date asc 
                                        ");
    if (mysqli_num_rows($res) > 0) {
        $data->status = "1";
        $data->list = mysqli_fetch_all($res, MYSQLI_ASSOC);
    } else {
        $data->status = "0";
        $data->data = "Bill Data Not Found.";
    }
    echo json_encode($data);
    exit();
}

// labour vendor bill
if (isset($_POST['labourvendorbill'])) {

    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $data = new \stdClass();

    $data->sdate = date_format(date_create($_POST['sdate']), "d/m/Y");
    $data->edate = date_format(date_create($_POST['edate']), "d/m/Y");

    $res = mysqli_query($connection, "SELECT p.*, DATE_FORMAT(p.date,'%d/%m/%Y') AS niceDate, v.name AS vendorname, v.mobile AS vendormobile,v.address FROM labour_vendor_work AS p
                                        LEFT JOIN labour_vendor AS v ON p.labourvendorid = v.id
                                        WHERE p.date <='$edate' AND p.date>='$sdate' AND p.status = 1
                                        order by p.date asc");
    if (mysqli_num_rows($res) > 0) {
        $data->status = "1";
        $data->list = mysqli_fetch_all($res, MYSQLI_ASSOC);

        $res =  mysqli_query($connection, "SELECT IFNULL(SUM(amount),0) as totalSend FROM `labour_vendor_payment` WHERE status = 1 ");
        $data->totalSend = mysqli_fetch_row($res);

        $res =  mysqli_query($connection, "SELECT IFNULL(SUM(amount),0) as totalPurchase FROM `labour_vendor_work` WHERE status = 1 AND  date < '$sdate'");
        $data->totalPurchase = mysqli_fetch_row($res);

        $res =  mysqli_query($connection, "SELECT IFNULL(pending,0) as pending FROM `labour_vendor`");
        $data->pending = mysqli_fetch_row($res);
    } else {
        $data->status = "0";
        $data->data = "Bill Data Not Found.";
    }
    echo json_encode($data);
    exit();
}

//labour vendor payment
if (isset($_POST['labourvendorpayment'])) {

    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $data = new \stdClass();

    $data->sdate = date_format(date_create($_POST['sdate']), "d/m/Y");
    $data->edate = date_format(date_create($_POST['edate']), "d/m/Y");

    $res = mysqli_query($connection, "SELECT p.*, DATE_FORMAT(p.date,'%d/%m/%Y') AS niceDate, 
                                        v.name AS vendorname, v.mobile AS vendormobile,v.address FROM labour_vendor_payment AS p
                                        LEFT JOIN labour_vendor AS v ON p.labourvendorid = v.id
                                        WHERE p.date <='$edate' AND p.date>='$sdate' AND p.status = 1
                                        order by p.date asc");
    if (mysqli_num_rows($res) > 0) {
        $data->status = "1";
        $data->list = mysqli_fetch_all($res, MYSQLI_ASSOC);
    } else {
        $data->status = "0";
        $data->data = "Bill Data Not Found.";
    }
    echo json_encode($data);
    exit();
}

//Vendor purchse
if (isset($_POST['vendorbill'])) {
    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $data = new \stdClass();

    $data->sdate = date_format(date_create($_POST['sdate']), "d/m/Y");
    $data->edate = date_format(date_create($_POST['edate']), "d/m/Y");

    $res = mysqli_query($connection, "SELECT p.*, DATE_FORMAT(p.date,'%d/%m/%Y') AS niceDate, v.name AS vendorname, v.mobile AS vendormobile,v.address FROM vendor_purchase AS p
                                        LEFT JOIN vendor AS v ON p.vendorid = v.id
                                        WHERE p.date <='$edate' AND p.date>='$sdate' AND p.status = 1
                                        order by p.date asc");
    if (mysqli_num_rows($res) > 0) {
        $data->status = "1";
        $data->list = mysqli_fetch_all($res, MYSQLI_ASSOC);

        $res =  mysqli_query($connection, "SELECT IFNULL(SUM(amount),0) as totalSend FROM `vendor_payment` WHERE status = 1 ");
        $data->totalSend = mysqli_fetch_row($res);

        $res =  mysqli_query($connection, "SELECT IFNULL(SUM(total),0) as totalPurchase FROM `vendor_purchase` WHERE status = 1 AND date < '$sdate'");
        $data->totalPurchase = mysqli_fetch_row($res);

        $res =  mysqli_query($connection, "SELECT IFNULL(pending,0) as pending FROM `vendor` ");
        $data->pending = mysqli_fetch_row($res);
    } else {
        $data->status = "0";
        $data->data = "Bill Data Not Found.";
    }
    echo json_encode($data);
    exit();
}

//Vendor payment
if (isset($_POST['vendorpayment'])) {

    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $data = new \stdClass();

    $data->sdate = date_format(date_create($_POST['sdate']), "d/m/Y");
    $data->edate = date_format(date_create($_POST['edate']), "d/m/Y");

    $res = mysqli_query($connection, "SELECT p.*,DATE_FORMAT(p.date,'%d/%m/%Y') AS niceDate, 
                                        w.name as vendorname, w.mobile as vendormobile 
                                        FROM `vendor_payment` as p                                         
                                        LEFT JOIN vendor as w ON p.vendorid = w.id
                                        WHERE p.date <='$edate' AND p.date>='$sdate' AND p.status = 1
                                        order by p.date asc 
                                        ");
    if (mysqli_num_rows($res) > 0) {
        $data->status = "1";
        $data->list = mysqli_fetch_all($res, MYSQLI_ASSOC);
    } else {
        $data->status = "0";
        $data->data = "Bill Data Not Found.";
    }
    echo json_encode($data);
    exit();
}

//vehicle bill
if (isset($_POST['vehiclepurchase'])) {

    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $data = new \stdClass();

    $data->sdate = date_format(date_create($_POST['sdate']), "d/m/Y");
    $data->edate = date_format(date_create($_POST['edate']), "d/m/Y");

    $res = mysqli_query($connection, "SELECT p.*,DATE_FORMAT(p.date,'%d/%m/%Y') AS niceDate, 
                                        v.name as vehiclename, v.number as number from customer_purchase as p
                                        LEFT join vehicle as v ON p.vehicleid = v.id
                                        WHERE p.date <='$edate' AND p.date>='$sdate' AND p.status = 1
                                        order by p.date asc
                                        ");
    if (mysqli_num_rows($res) > 0) {
        $data->status = "1";
        $data->list = mysqli_fetch_all($res, MYSQLI_ASSOC);

        $res =  mysqli_query($connection, "SELECT IFNULL(SUM(amount),0) as totalSend FROM `vehicle_payment` WHERE status = 1 ");
        $data->totalSend = mysqli_fetch_row($res);

        $res =  mysqli_query($connection, "SELECT IFNULL(SUM(vehiclepayment),0) as totalPurchase FROM `customer_purchase` WHERE status = 1 AND date < '$sdate'");
        $data->totalPurchase = mysqli_fetch_row($res);

        // $res =  mysqli_query($connection, "SELECT IFNULL(pending,0) as pending FROM `farmer` WHERE id = '$id' ");
        // $data->pending = mysqli_fetch_row($res);
    } else {
        $data->status = "0";
        $data->data = "Bill Data Not Found.";
    }
    echo json_encode($data);
    exit();
}

//vehicle Payment
if (isset($_POST['vehiclepayment'])) {

    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $data = new \stdClass();

    $data->sdate = date_format(date_create($_POST['sdate']), "d/m/Y");
    $data->edate = date_format(date_create($_POST['edate']), "d/m/Y");

    $res = mysqli_query($connection, "SELECT p.*, DATE_FORMAT(p.date,'%d/%m/%Y') AS niceDate, 
                                        v.name AS vehiclename, v.number AS number FROM vehicle_payment AS p
                                        LEFT JOIN vehicle AS v ON p.vehicleid = v.id
                                        WHERE p.date <='$edate' AND p.date>='$sdate' AND p.status = 1
                                        order by p.date asc");
    if (mysqli_num_rows($res) > 0) {
        $data->status = "1";
        $data->list = mysqli_fetch_all($res, MYSQLI_ASSOC);
    } else {
        $data->status = "0";
        $data->data = "Bill Data Not Found.";
    }
    echo json_encode($data);
    exit();
}

//customer Purchase
if (isset($_POST['customerpurchase'])) {
    
    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $data = new \stdClass();

    $data->sdate = date_format(date_create($_POST['sdate']), "d/m/Y");
    $data->edate = date_format(date_create($_POST['edate']), "d/m/Y");

    $res = mysqli_query($connection, "SELECT p.*,DATE_FORMAT(p.date,'%d/%m/%Y') AS niceDate,
                                        v.name as vehiclename, v.number, 
                                        f.name as customername, f.mobile as customermobile from customer_purchase as p
                                        LEFT join customer as f ON p.customerid = f.id
                                        LEFT JOIN vehicle as v on p.vehicleid = v.id
                                        WHERE p.date <='$edate' AND p.date>='$sdate' AND p.status = 1
                                        order by p.date asc
                                        ");
    if (mysqli_num_rows($res) > 0) {
        $data->status = "1";
        $data->list = mysqli_fetch_all($res, MYSQLI_ASSOC);

        $res =  mysqli_query($connection, "SELECT IFNULL(SUM(amount),0) as totalSend FROM `customer_payment` WHERE status = 1 ");
        $data->totalSend = mysqli_fetch_row($res);

        $res =  mysqli_query($connection, "SELECT IFNULL(SUM(totalamount),0) as totalPurchase FROM `customer_purchase` WHERE status = 1 AND date < '$sdate'");
        $data->totalPurchase = mysqli_fetch_row($res);

        $res =  mysqli_query($connection, "SELECT IFNULL(pending,0) as pending FROM `customer`");
        $data->pending = mysqli_fetch_row($res);
    } else {
        $data->status = "0";
        $data->data = "Bill Data Not Found.";
    }
    echo json_encode($data);
    exit();
}

//customer Payment
if (isset($_POST['customerpayment'])) {    
    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $data = new \stdClass();

    $data->sdate = date_format(date_create($_POST['sdate']), "d/m/Y");
    $data->edate = date_format(date_create($_POST['edate']), "d/m/Y");   

    $res = mysqli_query($connection, "SELECT p.*, DATE_FORMAT(p.date,'%d/%m/%Y') AS niceDate, 
                                        v.name AS customername, v.mobile AS customermobile,v.address FROM customer_payment AS p
                                        LEFT JOIN customer AS v ON p.customerid = v.id
                                        WHERE  p.date <='$edate' AND p.date>='$sdate' AND p.status = 1
                                        order by p.date asc");
    if (mysqli_num_rows($res) > 0) {
        $data->status = "1";
        $data->list = mysqli_fetch_all($res, MYSQLI_ASSOC);
        
    } else {
        $data->status = "0";
        $data->data = "Bill Data Not Found.";
    }
    echo json_encode($data);
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $project ?> : All report </title>

    <!-- Font Awesome -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../assets/css/print.css">

</head>

<body>
    <!-- Farmer purcahse -->
    <div id="invoice">

        <div class="toolbar hidden-print">
            <div class="text-right">
                <button id="printInvoice" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
                <button class="btn btn-info"><i class="fa fa-file-pdf-o"></i> Export as PDF</button>
            </div>
            <hr>
        </div>
        <div class="invoice overflow-auto">
            <div style="min-width: 600px">
                <header>
                    <div class="row">
                        <div class="col">
                            <a href="index.php">
                                <img src="../../dist/img/small.png" data-holder-rendered="true" width="170" height="170" />
                            </a>
                        </div>
                        <div class="col company-details">
                            <h2 class="name">
                                <a target="_blank" href="" style="color:black !important">
                                    NFC Fruits
                                </a>
                                </h1>
                                <div class="address">Jath-Athani main road, </div>
                                <div class="address">A/P - Billur, Tal - Jath,</div>
                                <div> Dist - Sangli 416-404</div>
                                <div>Mobile : 91586-47228</div>
                                <!-- <div>adnanenterprises@gmail.com</div> -->
                        </div>
                    </div>
                </header>
                <main>
                    <div class="row contacts">
                        <div class="col invoice-to">
                            <div class="text-gray-light">Farmer Purchase:</div>
                            <h2 class="to" id="to"></h2>
                            <div id="mobile" class="mobile"></div>
                            <div class="address" id="address"></div>
                            <div id="email" class="email"></div>

                        </div>
                        <div class="col invoice-details">
                            <h3 class="invoice-id" id="invoice-id">Farmer Purchase Details : </h3>
                            <div class="date sdate" id="sdate">Start Date : </div>
                            <div class="date edate" id="edate">End Date: </div>
                        </div>
                    </div>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>

                            <tr>
                                <th class="text-center">Sr.no. </th>
                                <th class="text-center">Date</th>
                                <th class='text-center'>Caret </th>
                                <th class='text-center'>Weight / Caret </th>
                                <th class='text-center'>Total Weight </th>
                                <th class='text-center'>Discount / 1000Kg </th>
                                <th class='text-center'>Actual Weight </th>
                                <th class='text-center'>Rate / 4kg </th>
                                <th class='text-center'>Total amount </th>
                            </tr>
                        </thead>
                        <tbody id="farmersalesdetails">
                            <tr>

                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="padding-top:90px  !important; "></td>
                                <td colspan="2"></td>
                                <td></td>
                            </tr>

                        </tfoot>
                    </table>
                </main>
                <footer>
                    Invoice was created on a computer and is valid without the signature and seal.
                </footer>
            </div>
            <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
            <div></div>
        </div>
    </div>

    <!-- Farmer Payment -->
    <div id="invoice">

        <div class="toolbar hidden-print">
            <!-- <div class="text-right">
                <button id="printInvoice" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
                <button class="btn btn-info"><i class="fa fa-file-pdf-o"></i> Export as PDF</button>
            </div> -->
            <hr>
        </div>
        <div class="invoice overflow-auto">
            <div style="min-width: 600px">
                <header>
                    <div class="row">
                        <div class="col">
                            <a href="index.php">
                                <img src="../../dist/img/small.png" data-holder-rendered="true" width="170" height="170" />
                            </a>
                        </div>
                        <div class="col company-details">
                            <h2 class="name">
                                <a target="_blank" href="" style="color:black !important">
                                    NFC Fruits
                                </a>
                                </h1>
                                <div class="address">Jath-Athani main road, </div>
                                <div class="address">A/P - Billur, Tal - Jath,</div>
                                <div> Dist - Sangli 416-404</div>
                                <div>Mobile : 91586-47228</div>
                                <!-- <div>adnanenterprises@gmail.com</div> -->
                        </div>
                    </div>
                </header>
                <main>
                    <div class="row contacts">
                        <div class="col invoice-to">
                            <div class="text-gray-light">Payment To Farmer:</div>
                            <h2 class="to" id="to"></h2>
                            <div id="mobile" class="mobile"></div>
                            <div class="address" id="address"></div>
                            <div id="email"></div>

                        </div>
                        <div class="col invoice-details">
                            <h3 class="invoice-id" id="invoice-id">Payment To Farmer Details : </h3>
                            <div class="date sdate" id="sdate">Start Date : </div>
                            <div class="date edate" id="edate">End Date: </div>
                        </div>
                    </div>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>

                            <tr>
                                <th class="text-center">Sr.no. </th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Mode</th>
                                <th class="text-center">Details</th>
                            </tr>
                        </thead>
                        <tbody id="farmerpaymentdetails">
                            <tr>

                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="padding-top:90px  !important; "></td>
                                <td colspan="2"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </main>
                <footer>
                    Invoice was created on a computer and is valid without the signature and seal.
                </footer>
            </div>
            <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
            <div></div>
        </div>
    </div>

    <!-- Inhouse employee payment -->
    <div id="invoice">
        <div class="invoice overflow-auto">
            <div style="min-width: 600px">
                <header>
                    <div class="row">
                        <div class="col">
                            <a href="index.php">
                                <img src="../../dist/img/small.png" data-holder-rendered="true" width="170" height="170" />
                            </a>
                        </div>
                        <div class="col company-details">
                            <h2 class="name">
                                <a target="_blank" href="" style="color:black !important">
                                    NFC Fruits
                                </a>
                                </h1>
                                <div class="address">Jath-Athani main road, </div>
                                <div class="address">A/P - Billur, Tal - Jath,</div>
                                <div> Dist - Sangli 416-404</div>
                                <div>Mobile : 91586-47228</div>
                                <!-- <div>adnanenterprises@gmail.com</div> -->
                        </div>
                    </div>
                </header>
                <main>
                    <div class="row contacts">
                        <div class="col invoice-to">
                            <div class="text-gray-light">Payment To Inhouse worker:</div>

                        </div>
                        <div class="col invoice-details">
                            <h3 class="invoice-id" id="invoice-id">Payment To Inhouse worker : </h3>
                            <div class="date" id="sdate">Start Date : </div>
                            <div class="date" id="edate">End Date: </div>
                        </div>
                    </div>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>

                            <tr>
                                <th class="text-center">Sr.no. </th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Mode</th>
                                <th class="text-center">Details</th>
                            </tr>
                        </thead>
                        <tbody id="inhousesalesdetails">
                            <tr>

                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="padding-top:90px  !important; "></td>
                                <td colspan="2"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>


                </main>
                <footer>
                    Invoice was created on a computer and is valid without the signature and seal.
                </footer>
            </div>
            <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
            <div></div>
        </div>
    </div>

    <!-- Labour Vendor bill -->
    <div id="invoice">
        <div class="invoice overflow-auto">
            <div style="min-width: 600px">
                <header>
                    <div class="row">
                        <div class="col">
                            <a href="index.php">
                                <img src="../../dist/img/small.png" data-holder-rendered="true" width="170" height="170" />
                            </a>
                        </div>
                        <div class="col company-details">
                            <h2 class="name">
                                <a target="_blank" href="" style="color:black !important">
                                    NFC Fruits
                                </a>
                                </h1>
                                <div class="address">Jath-Athani main road, </div>
                                <div class="address">A/P - Billur, Tal - Jath,</div>
                                <div> Dist - Sangli 416-404</div>
                                <div>Mobile : 91586-47228</div>
                                <!-- <div>adnanenterprises@gmail.com</div> -->
                        </div>
                    </div>
                </header>
                <main>
                    <div class="row contacts">
                        <div class="col invoice-to">
                            <div class="text-gray-light">Labour Contractor Bill Details:</div>
                        </div>
                        <div class="col invoice-details">
                            <h3 class="invoice-id" id="invoice-id">Labour Contractor Bill Details : </h3>
                            <div class="date sdate" id="sdate">Start Date : </div>
                            <div class="date edate" id="edate">End Date: </div>
                        </div>
                    </div>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>

                            <tr>
                                <th class="text-center">Sr.no. </th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Gents/Charges</th>
                                <th class="text-center">Ladies/Charges</th>
                                <th class="text-center">Vehicle</th>
                                <th class="text-center">Charges</th>
                                <th class="text-center">Location</th>
                                <th class="text-center">Amount</th>
                            </tr>
                        </thead>
                        <tbody id="labourvendorsalesdetails">
                            <tr>

                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="padding-top:90px  !important; "></td>
                                <td colspan="2"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </main>
                <footer>
                    Invoice was created on a computer and is valid without the signature and seal.
                </footer>
            </div>
            <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
            <div></div>
        </div>
    </div>
    <!--Labour vendor payment details  -->
    <div id="invoice">
        <div class="invoice overflow-auto">
            <div style="min-width: 600px">
                <header>
                    <div class="row">
                        <div class="col">
                            <a href="index.php">
                                <img src="../../dist/img/small.png" data-holder-rendered="true" width="170" height="170" />
                            </a>
                        </div>
                        <div class="col company-details">
                            <h2 class="name">
                                <a target="_blank" href="" style="color:black !important">
                                    NFC Fruits
                                </a>
                                </h1>
                                <div class="address">Jath-Athani main road, </div>
                                <div class="address">A/P - Billur, Tal - Jath,</div>
                                <div> Dist - Sangli 416-404</div>
                                <div>Mobile : 91586-47228</div>
                                <!-- <div>adnanenterprises@gmail.com</div> -->
                        </div>
                    </div>
                </header>
                <main>
                    <div class="row contacts">
                        <div class="col invoice-to">
                            <div class="text-gray-light">Labour Contarctor Payment Details</div>

                        </div>
                        <div class="col invoice-details">
                            <h3 class="invoice-id" id="invoice-id">Labour Contarctor Payment Details : </h3>
                            <div class="date sdate" id="sdate">Start Date : </div>
                            <div class="date edate" id="edate">End Date: </div>
                        </div>
                    </div>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>

                            <tr>
                                <th class="text-center">Sr.no. </th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Mode</th>
                                <th class="text-center">Details</th>
                            </tr>
                        </thead>
                        <tbody id="labourvendorpaymentdetails">
                            <tr>

                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="padding-top:90px  !important; "></td>
                                <td colspan="2"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </main>
                <footer>
                    Invoice was created on a computer and is valid without the signature and seal.
                </footer>
            </div>
            <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
            <div></div>
        </div>
    </div>

    <!-- Vendor bill -->
    <div id="invoice">
        <div class="invoice overflow-auto">
            <div style="min-width: 600px">
                <header>
                    <div class="row">
                        <div class="col">
                            <a href="index.php">
                                <img src="../../dist/img/small.png" data-holder-rendered="true" width="170" height="170" />
                            </a>
                        </div>
                        <div class="col company-details">
                            <h2 class="name">
                                <a target="_blank" href="" style="color:black !important">
                                    NFC Fruits
                                </a>
                                </h1>
                                <div class="address">Jath-Athani main road, </div>
                                <div class="address">A/P - Billur, Tal - Jath,</div>
                                <div> Dist - Sangli 416-404</div>
                                <div>Mobile : 91586-47228</div>
                                <!-- <div>adnanenterprises@gmail.com</div> -->
                        </div>
                    </div>
                </header>
                <main>
                    <div class="row contacts">
                        <div class="col invoice-to">
                            <div class="text-gray-light">Vendor Bill Details</div>
                        </div>
                        <div class="col invoice-details">
                            <h3 class="invoice-id" id="invoice-id">Vendor Bill Details : </h3>
                            <div class="date sdate" id="sdate">Start Date : </div>
                            <div class="date edate" id="edate">End Date: </div>
                        </div>
                    </div>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>

                            <tr>
                                <th class="text-center">Sr.no. </th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Caret</th>
                                <th class="text-center">Rope</th>
                                <th class="text-center">Paper</th>
                                <th class="text-center">Tape</th>
                                <th class="text-center">Box</th>
                                <th class="text-center">Cooling Box</th>
                            </tr>
                        </thead>
                        <tbody id="vendorsalesdetails">
                            <tr>

                            </tr>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </main>
                <footer>
                    Invoice was created on a computer and is valid without the signature and seal.
                </footer>
            </div>
            <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
            <div></div>
        </div>
    </div>

    <!-- vendor payment -->
    <div id="invoice">
        <div class="invoice overflow-auto">
            <div style="min-width: 600px">
                <header>
                    <div class="row">
                        <div class="col">
                            <a href="index.php">
                                <img src="../../dist/img/small.png" data-holder-rendered="true" width="170" height="170" />
                            </a>
                        </div>
                        <div class="col company-details">
                            <h2 class="name">
                                <a target="_blank" href="" style="color:black !important">
                                    NFC Fruits
                                </a>
                                </h1>
                                <div class="address">Jath-Athani main road, </div>
                                <div class="address">A/P - Billur, Tal - Jath,</div>
                                <div> Dist - Sangli 416-404</div>
                                <div>Mobile : 91586-47228</div>
                                <!-- <div>adnanenterprises@gmail.com</div> -->
                        </div>
                    </div>
                </header>
                <main>
                    <div class="row contacts">
                        <div class="col invoice-to">
                            <div class="text-gray-light">Vendor Payment Vendor:</div>
                        </div>
                        <div class="col invoice-details">
                            <h3 class="invoice-id" id="invoice-id">Vendor Payment Details : </h3>
                            <div class="date sdate" id="sdate">Start Date : </div>
                            <div class="date edate" id="edate">End Date: </div>
                        </div>
                    </div>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>

                            <tr>
                                <th class="text-center">Sr.no. </th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Mode</th>
                                <th class="text-center">Details</th>
                            </tr>
                        </thead>
                        <tbody id="vendorpaymentdetails">
                            <tr>

                            </tr>
                        </tbody>
                        <tfoot>
                            <!-- <tr>
                                <td colspan="2" style="padding-top:90px  !important; "></td>
                                <td colspan="2"></td>
                                <td></td>
                            </tr> -->
                        </tfoot>
                    </table>
                </main>
                <footer>
                    Invoice was created on a computer and is valid without the signature and seal.
                </footer>
            </div>
            <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
            <div></div>
        </div>
    </div>

    <!-- vehicle purcahse -->
    <div id="invoice">
        <div class="invoice overflow-auto">
            <div style="min-width: 600px">
                <header>
                    <div class="row">
                        <div class="col">
                            <a href="index.php">
                                <img src="../../dist/img/small.png" data-holder-rendered="true" width="170" height="170" />
                            </a>
                        </div>
                        <div class="col company-details">
                            <h2 class="name">
                                <a target="_blank" href="" style="color:black !important">
                                    NFC Fruits
                                </a>
                                </h1>
                                <div class="address">Jath-Athani main road, </div>
                                <div class="address">A/P - Billur, Tal - Jath,</div>
                                <div> Dist - Sangli 416-404</div>
                                <div>Mobile : 91586-47228</div>
                                <!-- <div>adnanenterprises@gmail.com</div> -->
                        </div>
                    </div>
                </header>
                <main>
                    <div class="row contacts">
                        <div class="col invoice-to">
                            <div class="text-gray-light">Vehicle Bill Details:</div>
                        </div>
                        <div class="col invoice-details">
                            <h3 class="invoice-id" id="invoice-id">Vehicle Bill Details : </h3>
                            <div class="date sdate" id="sdate">Start Date : </div>
                            <div class="date edate" id="edate">End Date: </div>
                        </div>
                    </div>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>

                            <tr>
                                <th class="text-center">Sr.no. </th>
                                <th class="text-center">Date</th>
                                <th class='text-center'>Amount </th>
                            </tr>
                        </thead>
                        <tbody id="vehiclesalesdetails">
                            <tr>

                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="1" style="padding-top:90px  !important; "></td>
                                <td colspan="2"></td>

                            </tr>

                        </tfoot>
                    </table>
                </main>
                <footer>
                    Invoice was created on a computer and is valid without the signature and seal.
                </footer>
            </div>
            <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
            <div></div>
        </div>
    </div>

    <!-- vehicle Payment -->
    <div id="invoice">
        <div class="invoice overflow-auto">
            <div style="min-width: 600px">
                <header>
                    <div class="row">
                        <div class="col">
                            <a href="index.php">
                                <img src="../../dist/img/small.png" data-holder-rendered="true" width="170" height="170" />
                            </a>
                        </div>
                        <div class="col company-details">
                            <h2 class="name">
                                <a target="_blank" href="" style="color:black !important">
                                    NFC Fruits
                                </a>
                                </h1>
                                <div class="address">Jath-Athani main road, </div>
                                <div class="address">A/P - Billur, Tal - Jath,</div>
                                <div> Dist - Sangli 416-404</div>
                                <div>Mobile : 91586-47228</div>
                                <!-- <div>adnanenterprises@gmail.com</div> -->
                        </div>
                    </div>
                </header>
                <main>
                    <div class="row contacts">
                        <div class="col invoice-to">
                            <div class="text-gray-light">Payment To Vehicle Details:</div>

                        </div>
                        <div class="col invoice-details">
                            <h3 class="invoice-id" id="invoice-id">Payment To Vehicle Details : </h3>
                            <div class="date sdate" id="sdate">Start Date : </div>
                            <div class="date edate" id="edate">End Date: </div>
                        </div>
                    </div>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>

                            <tr>
                                <th class="text-center">Sr.no. </th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Mode</th>
                                <th class="text-center">Details</th>
                            </tr>
                        </thead>
                        <tbody id="vehiclepaymentdetails">
                            <tr>

                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="padding-top:90px  !important; "></td>
                                <td colspan="2"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </main>
                <footer>
                    Invoice was created on a computer and is valid without the signature and seal.
                </footer>
            </div>
            <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
            <div></div>
        </div>
    </div>

    <!-- customer purcahse -->
    <div id="invoice">        
        <div class="invoice overflow-auto">
            <div style="min-width: 600px">
                <header>
                    <div class="row">
                        <div class="col">
                            <a href="index.php">
                                <img src="../../dist/img/small.png" data-holder-rendered="true" width="170" height="170" />
                            </a>
                        </div>
                        <div class="col company-details">
                            <h2 class="name">
                                <a target="_blank" href="" style="color:black !important">
                                    NFC Fruits
                                </a>
                                </h1>
                                <div class="address">Jath-Athani main road, </div>
                                <div class="address">A/P - Billur, Tal - Jath,</div>
                                <div> Dist - Sangli 416-404</div>
                                <div>Mobile : 91586-47228</div>
                                <!-- <div>adnanenterprises@gmail.com</div> -->
                        </div>
                    </div>
                </header>
                <main>
                    <div class="row contacts">
                        <div class="col invoice-to">
                            <div class="text-gray-light">Customer Sales Details:</div>
                        </div>
                        <div class="col invoice-details">
                            <h3 class="invoice-id" id="invoice-id">Customer Sales Details : </h3>
                            <div class="date sdate" id="sdate">Start Date : </div>
                            <div class="date edate" id="edate">End Date: </div>
                        </div>
                    </div>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>

                            <tr>
                                <th class="text-center">Sr.no. </th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Type</th>
                                <th class='text-center'>Caret </th>
                                <th class='text-center'>Weight /<br> Caret </th>
                                <th class='text-center'>Total<br> Weight </th>
                                <th class='text-center'>Discount /<br> 1000Kg </th>
                                <th class='text-center'>Actual<br> Weight </th>
                                <th class='text-center'>Rate / 4kg </th>
                                <th class='text-center'>Total amount </th>
                                <th class='text-center'>Vehicle </th>
                                <th class='text-center'>Vehicle amount </th>
                            </tr>
                        </thead>
                        <tbody id="customersalesdetails">
                            <tr>

                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="padding-top:90px  !important; "></td>
                                <td colspan="2"></td>
                                <td></td>
                            </tr>

                        </tfoot>
                    </table>
                </main>
                <footer>
                    Invoice was created on a computer and is valid without the signature and seal.
                </footer>
            </div>
            <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
            <div></div>
        </div>
    </div>

    <!-- customer Payment -->
    <div id="invoice">        
        <div class="invoice overflow-auto">
            <div style="min-width: 600px">
                <header>
                    <div class="row">
                        <div class="col">
                            <a href="index.php">
                                <img src="../../dist/img/small.png" data-holder-rendered="true" width="170" height="170" />
                            </a>
                        </div>
                        <div class="col company-details">
                            <h2 class="name">
                                <a target="_blank" href="" style="color:black !important">
                                    NFC Fruits
                                </a>
                                </h1>
                                <div class="address">Jath-Athani main road, </div>
                                <div class="address">A/P - Billur, Tal - Jath,</div>
                                <div> Dist - Sangli 416-404</div>
                                <div>Mobile : 91586-47228</div>
                                <!-- <div>adnanenterprises@gmail.com</div> -->
                        </div>
                    </div>
                </header>
                <main>
                    <div class="row contacts">
                        <div class="col invoice-to">
                            <div class="text-gray-light">Payment From Customer Details:</div>
                        </div>
                        <div class="col invoice-details">
                            <h3 class="invoice-id" id="invoice-id">Payment From Customer Details : </h3>
                            <div class="date sdate" id="sdate">Start Date : </div>
                            <div class="date edate" id="edate">End Date: </div>
                        </div>
                    </div>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>

                            <tr>
                                <th class="text-center">Sr.no. </th>
                                <th class="text-center">Date</th>                                
                                <th class="text-center">Amount</th>
                                <th class="text-center">Mode</th>
                                <th class="text-center">Details</th>                                
                            </tr>
                        </thead>
                        <tbody id="customerpaymentdetails">
                            <tr>

                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="padding-top:90px  !important; "></td>
                                <td colspan="2"></td>
                                <td></td>
                            </tr>                           
                        </tfoot>
                    </table>                    
                </main>
                <footer>
                    Invoice was created on a computer and is valid without the signature and seal.
                </footer>
            </div>
            <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
            <div></div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#printInvoice').click(function() {
                Popup($('.invoice')[0].outerHTML);

                function Popup(data) {
                    window.print();
                    return true;
                }
            });

            //display farmer purchase data table
            function farmertabledatapurchase() {
                var vars = [],
                    hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'farmerpurchase': 'farmerpurchase',
                        'sdate': vars["sdate"],
                        'edate': vars["edate"]
                    },
                    success: function(response) {
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        console.log(returnedData);
                        var srno = 0;
                        if (returnedData['status'] == 0) {

                        } else {
                            document.title = "All report " + '_' + vars["sdate"] + " - " + vars["edate"];

                            $(".sdate").append(returnedData["sdate"]);
                            $(".edate").append(returnedData["edate"]);

                            var srno = 0;
                            var gentsTotal = 0;
                            var ladiesTotal = 0;
                            var total = 0;


                            $.each(returnedData['list'], function(key, value) {
                                srno++;

                                total = parseFloat(total) + parseFloat(value.totalamount);

                                var html = '<tr class="odd gradeX">' +
                                    '<td class="text-center">' + srno + '</td>' +
                                    '<td class="text-center">' + value.niceDate + '</td>' +
                                    '<td class="text-center">' + value.carate.toLocaleString('en-IN') + '</td>' +
                                    '<td class="text-center">' + value.weight + '</td>' +
                                    '<td class="text-center">' + value.totalweight.toLocaleString('en-IN') + '</td>' +
                                    '<td class="text-center">' + value.discount + '</td>' +
                                    '<td class="text-center">' + value.actualweight.toLocaleString('en-IN') + '</td>' +
                                    '<td class="text-center">' + value.rate + '/-</td>' +
                                    '<td class="text-center">' + value.totalamount.toLocaleString('en-IN') + '/-</td>' +
                                    '</tr>';
                                $('#farmersalesdetails').append(html);
                            });

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="8"> <b>Total Quantity</b>  </td>' +
                                '<td class="text-center">' + total.toLocaleString('en-IN') + '/-</td>' +
                                '</tr>';
                            $('#farmersalesdetails').append(html);

                            // var total1 = parseFloat(caretTotal) + parseFloat(box5kgTotal) + parseFloat(paperTotal) + parseFloat(tapeTotal) + parseFloat(tawimTotal) + parseFloat(brboxTotal) + parseFloat(whiterimTotal)+ parseFloat(pinkrimTotal);
                            // var pending = parseFloat(returnedData['totalPurchase'][0]) + parseFloat(returnedData['pending'][0]) - parseFloat(returnedData['totalSend'][0]);
                            // $("#subtotal").append(parseFloat(total1).toLocaleString('en-IN') + "/-");
                            // $("#pending").append(parseFloat(pending).toLocaleString('en-IN') + "/-");
                            // $("#grandtotal").append(parseFloat(parseFloat(total1) + parseFloat(pending)).toLocaleString('en-IN') + "/- ");
                        }
                    }
                });
            }

            farmertabledatapurchase();

            //display farmer payment data table
            function farmertabledatapayment() {
                var vars = [],
                    hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'farmerpayment': 'farmerpayment',
                        'sdate': vars["sdate"],
                        'edate': vars["edate"]
                    },
                    success: function(response) {
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        console.log(returnedData);
                        var srno = 0;
                        if (returnedData['status'] == 0) {

                        } else {

                            var srno = 0;
                            var total = 0;


                            $.each(returnedData['list'], function(key, value) {
                                srno++;
                                total = parseFloat(total) + parseFloat(value.amount);

                                var html = '<tr class="odd gradeX">' +
                                    '<td class="text-center">' + srno + '</td>' +
                                    '<td class="text-center">' + value.niceDate + '</td>' +
                                    '<td class="text-center">' + value.amount.toLocaleString('en-IN') + '/-</td>' +
                                    '<td class="text-center">' + value.mode + '</td>' +
                                    '<td class="text-center">' + value.details + '</td>' +
                                    '</tr>';
                                $('#farmerpaymentdetails').append(html);
                            });

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="2"> <b>Total Amount</b>  </td>' +

                                '<td class="text-center">' + total.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-right" colspan="2"> </td>' +
                                '</tr>';
                            $('#parmerpaymentdetails').append(html);


                            // var total1 = parseFloat(caretTotal) + parseFloat(box5kgTotal) + parseFloat(paperTotal) + parseFloat(tapeTotal) + parseFloat(tawimTotal) + parseFloat(brboxTotal) + parseFloat(whiterimTotal)+ parseFloat(pinkrimTotal);
                            // var pending = parseFloat(returnedData['totalPurchase'][0]) + parseFloat(returnedData['pending'][0]) - parseFloat(returnedData['totalSend'][0]);
                            // $("#subtotal").append(parseFloat(total1).toLocaleString('en-IN') + "/-");
                            // $("#pending").append(parseFloat(pending).toLocaleString('en-IN') + "/-");
                            // $("#grandtotal").append(parseFloat(parseFloat(total1) + parseFloat(pending)).toLocaleString('en-IN') + "/- ");
                        }
                    }
                });
            }

            farmertabledatapayment();

            //inhouse payment details
            function inhousetabledata() {
                var vars = [],
                    hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'workerpayment': 'workerpayment',
                        'sdate': vars["sdate"],
                        'edate': vars["edate"]
                    },
                    success: function(response) {
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        console.log(returnedData);
                        var srno = 0;
                        if (returnedData['status'] == 0) {

                        } else {
                            var srno = 0;
                            var total = 0;


                            $.each(returnedData['list'], function(key, value) {
                                srno++;
                                total = parseFloat(total) + parseFloat(value.amount);

                                var html = '<tr class="odd gradeX">' +
                                    '<td class="text-center">' + srno + '</td>' +
                                    '<td class="text-center">' + value.niceDate + '</td>' +
                                    '<td class="text-center">' + value.amount.toLocaleString('en-IN') + '/-</td>' +
                                    '<td class="text-center">' + value.mode + '</td>' +
                                    '<td class="text-center">' + value.details + '</td>' +
                                    '</tr>';
                                $('#inhousesalesdetails').append(html);
                            });

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="2"> <b>Total Amount</b>  </td>' +

                                '<td class="text-center">' + total.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-right" colspan="2"> </td>' +
                                '</tr>';
                            $('#inhousesalesdetails').append(html);

                        }
                    }
                });
            }

            inhousetabledata();

            //Labour vendor bill details            
            function labourvendortabledatabill() {
                var vars = [],
                    hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'labourvendorbill': 'labourvendorbill',
                        'sdate': vars["sdate"],
                        'edate': vars["edate"]
                    },
                    success: function(response) {
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        console.log(returnedData);
                        var srno = 0;
                        if (returnedData['status'] == 0) {

                        } else {


                            var srno = 0;
                            var gentsTotal = 0;
                            var ladiesTotal = 0;
                            var total = 0;


                            $.each(returnedData['list'], function(key, value) {
                                srno++;

                                // gentsTotal = parseFloat(gentsTotal) + parseFloat(value.gents);
                                // ladiesTotal = parseFloat(ladiesTotal) + parseFloat(value.ladies);                                
                                total = parseFloat(total) + parseFloat(value.amount);

                                var html = '<tr class="odd gradeX">' +
                                    '<td class="text-center">' + srno + '</td>' +
                                    '<td class="text-center">' + value.niceDate + '</td>' +
                                    '<td class="text-center">' + value.gents + ' / ' + value.gentscharges + '</td>' +
                                    '<td class="text-center">' + value.ladies + ' / ' + value.ladiescharges + '</td>' +
                                    '<td class="text-center">' + value.vehicle + '</td>' +
                                    '<td class="text-center">' + value.vehiclecharges + '</td>' +
                                    '<td class="text-center">' + value.location + '</td>' +
                                    '<td class="text-center">' + value.amount.toLocaleString('en-IN') + '/-</td>' +
                                    '</tr>';
                                $('#labourvendorsalesdetails').append(html);
                            });

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="7"> <b>Total Amount</b>  </td>' +
                                '<td class="text-center">' + total.toLocaleString('en-IN') + '/-</td>' +
                                '</tr>';
                            $('#labourvendorsalesdetails').append(html);
                        }
                    }
                });
            }

            labourvendortabledatabill();

            //labour vendor payment details
            function labourvendortabledatapayment() {
                var vars = [],
                    hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'labourvendorpayment': 'labourvendorpayment',
                        'sdate': vars["sdate"],
                        'edate': vars["edate"]
                    },
                    success: function(response) {
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        console.log(returnedData);
                        var srno = 0;
                        if (returnedData['status'] == 0) {

                        } else {
                            var srno = 0;
                            var total = 0;

                            $.each(returnedData['list'], function(key, value) {
                                srno++;
                                total = parseFloat(total) + parseFloat(value.amount);

                                var html = '<tr class="odd gradeX">' +
                                    '<td class="text-center">' + srno + '</td>' +
                                    '<td class="text-center">' + value.niceDate + '</td>' +
                                    '<td class="text-center">' + value.amount.toLocaleString('en-IN') + '/-</td>' +
                                    '<td class="text-center">' + value.mode + '</td>' +
                                    '<td class="text-center">' + value.details + '</td>' +
                                    '</tr>';
                                $('#labourvendorpaymentdetails').append(html);
                            });

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="2"> <b>Total Amount</b>  </td>' +

                                '<td class="text-center">' + total.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-right" colspan="2"> </td>' +
                                '</tr>';
                            $('#labourvendorpaymentdetails').append(html);
                        }
                    }
                });
            }
            labourvendortabledatapayment();

            //Vendor bill details
            function vendorbilltabledata() {
                var vars = [],
                    hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'vendorbill': 'vendorbill',
                        'sdate': vars["sdate"],
                        'edate': vars["edate"]
                    },
                    success: function(response) {
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        console.log(returnedData);
                        var srno = 0;
                        if (returnedData['status'] == 0) {

                        } else {
                            var srno = 0;
                            var caretTotal = 0;
                            var ropeTotal = 0;
                            var paperTotal = 0;
                            var tapeTotal = 0;
                            var boxTotal = 0;
                            var collingboxTotal = 0;

                            var discount = 0;
                            var total = 0;

                            var caretQuantity = 0;
                            var ropeQuantity = 0;
                            var paperQuantity = 0;
                            var tapeQuantity = 0;
                            var boxQuantity = 0;
                            var collingQuantity = 0;


                            $.each(returnedData['list'], function(key, value) {
                                srno++;

                                caretQuantity = parseFloat(caretQuantity) + parseFloat(value.caret_quantity);
                                ropeQuantity = parseFloat(ropeQuantity) + parseFloat(value.rope_quantity);
                                paperQuantity = parseFloat(paperQuantity) + parseFloat(value.paper_quantity);
                                tapeQuantity = parseFloat(tapeQuantity) + parseFloat(value.tape_quantity);
                                boxQuantity = parseFloat(boxQuantity) + parseFloat(value.box_quantity);
                                collingQuantity = parseFloat(collingQuantity) + parseFloat(value.collingbox_quantity);


                                caretTotal = parseFloat(caretTotal) + (parseFloat(value.caret_rate) * parseFloat(value.caret_quantity));
                                ropeTotal = parseFloat(ropeTotal) + (parseFloat(value.rope_rate) * parseFloat(value.rope_quantity));
                                paperTotal = parseFloat(paperTotal) + (parseFloat(value.paper_rate) * parseFloat(value.paper_quantity));
                                tapeTotal = parseFloat(tapeTotal) + (parseFloat(value.tape_rate) * parseFloat(value.tape_quantity));
                                boxTotal = parseFloat(boxTotal) + (parseFloat(value.box_rate) * parseFloat(value.box_quantity));
                                collingboxTotal = parseFloat(collingboxTotal) + (parseFloat(value.collingbox_rate) * parseFloat(value.collingbox_quantity));

                                total = parseFloat(total) + parseFloat(value.total);

                                var html = '<tr class="odd gradeX">' +
                                    '<td class="text-center">' + srno + '</td>' +
                                    '<td class="text-center">' + value.niceDate + '</td>' +
                                    '<td class="text-center">' + value.caret_quantity + '</td>' +
                                    '<td class="text-center">' + value.rope_quantity + '</td>' +
                                    '<td class="text-center">' + value.paper_quantity + '</td>' +
                                    '<td class="text-center">' + value.tape_quantity + '</td>' +
                                    '<td class="text-center">' + value.box_quantity + '</td>' +
                                    '<td class="text-center">' + value.collingbox_quantity + '</td>' +

                                    '</tr>';
                                $('#vendorsalesdetails').append(html);
                            });

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="2"> <b>Total Quantity</b>  </td>' +
                                '<td class="text-center">' + caretQuantity + '</td>' +
                                '<td class="text-center">' + ropeQuantity + '</td>' +
                                '<td class="text-center">' + paperQuantity + '</td>' +
                                '<td class="text-center">' + tapeQuantity + '</td>' +
                                '<td class="text-center">' + boxQuantity + '</td>' +
                                '<td class="text-center">' + collingQuantity + '</td>' +

                                '</tr>';
                            $('#vendorsalesdetails').append(html);

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="2"> <b>Total Price</b>  </td>' +
                                '<td class="text-center">' + caretTotal.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-center">' + ropeTotal.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-center">' + paperTotal.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-center">' + tapeTotal.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-center">' + boxTotal.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-center">' + collingboxTotal.toLocaleString('en-IN') + '/-</td>' +

                                '</tr>';
                            $('#vendorsalesdetails').append(html);

                            var total1 = parseFloat(caretTotal) + parseFloat(ropeTotal) + parseFloat(paperTotal) + parseFloat(tapeTotal) + parseFloat(boxTotal) + parseFloat(collingboxTotal);
                            var pending = parseFloat(returnedData['totalPurchase'][0]) + parseFloat(returnedData['pending'][0]) - parseFloat(returnedData['totalSend'][0]);
                            // $("#subtotal").append(parseFloat(total1).toLocaleString('en-IN') + "/-");
                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="4"> <b>Total </b>  </td>' +
                                '<td class="text-center" colspan="4">' + total1.toLocaleString('en-IN') + '/-</td>' +
                                '</tr>';
                            $('#vendorsalesdetails').append(html);
                            // $("#pending").append(parseFloat(pending).toLocaleString('en-IN') + "/-");
                            // $("#grandtotal").append(parseFloat(parseFloat(total1) + parseFloat(pending)).toLocaleString('en-IN') + "/- ");
                        }
                    }
                });
            }

            vendorbilltabledata();

            //vendor payment details
            function vendorpaymenttabledata() {
                var vars = [],
                    hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'vendorpayment': 'vendorpayment',
                        'sdate': vars["sdate"],
                        'edate': vars["edate"]
                    },
                    success: function(response) {
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        console.log(returnedData);
                        var srno = 0;
                        if (returnedData['status'] == 0) {

                        } else {

                            var srno = 0;
                            var total = 0;


                            $.each(returnedData['list'], function(key, value) {
                                srno++;
                                total = parseFloat(total) + parseFloat(value.amount);

                                var html = '<tr class="odd gradeX">' +
                                    '<td class="text-center">' + srno + '</td>' +
                                    '<td class="text-center">' + value.niceDate + '</td>' +
                                    '<td class="text-center">' + value.amount.toLocaleString('en-IN') + '/-</td>' +
                                    '<td class="text-center">' + value.mode + '</td>' +
                                    '<td class="text-center">' + value.details + '</td>' +
                                    '</tr>';
                                $('#vendorpaymentdetails').append(html);
                            });

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="2"> <b>Total Amount</b>  </td>' +

                                '<td class="text-center">' + total.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-right" colspan="2"> </td>' +
                                '</tr>';
                            $('#vendorpaymentdetails').append(html);

                        }
                    }
                });
            }

            vendorpaymenttabledata();

            //display vehicle purchase data table
            function vehicletabledatapurchase() {
                var vars = [],
                    hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'vehiclepurchase': 'vehiclepurchase',
                        'sdate': vars["sdate"],
                        'edate': vars["edate"]
                    },
                    success: function(response) {
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        console.log(returnedData);
                        var srno = 0;
                        if (returnedData['status'] == 0) {

                        } else {                            
                            var srno = 0;
                            var gentsTotal = 0;
                            var ladiesTotal = 0;
                            var total = 0;


                            $.each(returnedData['list'], function(key, value) {
                                srno++;

                                total = parseFloat(total) + parseFloat(value.vehiclepayment);

                                var html = '<tr class="odd gradeX">' +
                                    '<td class="text-center">' + srno + '</td>' +
                                    '<td class="text-center">' + value.niceDate + '</td>' +
                                    '<td class="text-center">' + value.vehiclepayment.toLocaleString('en-IN') + '</td>' +
                                    '</tr>';
                                $('#vehiclesalesdetails').append(html);
                            });

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="2"> <b>Total </b>  </td>' +
                                '<td class="text-center">' + total.toLocaleString('en-IN') + '/-</td>' +
                                '</tr>';
                            $('#vehiclesalesdetails').append(html);                            
                        }
                    }
                });
            }

            vehicletabledatapurchase();

            //display vehicle payment data table
            function vehicletabledatapayment() {
                var vars = [],
                    hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'vehiclepayment': 'vehiclepayment',
                        'sdate': vars["sdate"],
                        'edate': vars["edate"]
                    },
                    success: function(response) {
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        console.log(returnedData);
                        var srno = 0;
                        if (returnedData['status'] == 0) {

                        } else {    
                            var srno = 0;
                            var total = 0;


                            $.each(returnedData['list'], function(key, value) {
                                srno++;
                                total = parseFloat(total) + parseFloat(value.amount);

                                var html = '<tr class="odd gradeX">' +
                                    '<td class="text-center">' + srno + '</td>' +
                                    '<td class="text-center">' + value.niceDate + '</td>' +
                                    '<td class="text-center">' + value.amount.toLocaleString('en-IN') + '/-</td>' +
                                    '<td class="text-center">' + value.mode + '</td>' +
                                    '<td class="text-center">' + value.details + '</td>' +
                                    '</tr>';
                                $('#vehiclepaymentdetails').append(html);
                            });

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="2"> <b>Total Amount</b>  </td>' +

                                '<td class="text-center">' + total.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-right" colspan="2"> </td>' +
                                '</tr>';
                            $('#vehiclepaymentdetails').append(html);                            
                        }
                    }
                });
            }

            vehicletabledatapayment();

            //display customer purchase data table
            function customertabledatapurchase() {
                var vars = [],
                    hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'customerpurchase': 'customerpurchase',
                        'sdate': vars["sdate"],
                        'edate': vars["edate"]
                    },
                    success: function(response) {
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        console.log(returnedData);
                        var srno = 0;
                        if (returnedData['status'] == 0) {

                        } else {                            
                            var srno = 0;
                            var gentsTotal = 0;
                            var ladiesTotal = 0;
                            var total = 0;
                            var totalvehicle = 0;


                            $.each(returnedData['list'], function(key, value) {
                                srno++;

                                total = parseFloat(total) + parseFloat(value.totalamount);
                                totalvehicle = parseFloat(totalvehicle) + parseFloat(value.vehiclepayment);

                                var html = '<tr class="odd gradeX">' +
                                    '<td class="text-center">' + srno + '</td>' +
                                    '<td class="text-center">' + value.niceDate + '</td>' +
                                    '<td class="text-center">' + value.type + '</td>' +
                                    '<td class="text-center">' + value.carate.toLocaleString('en-IN') + '</td>' +
                                    '<td class="text-center">' + value.weight + '</td>' +
                                    '<td class="text-center">' + value.totalweight.toLocaleString('en-IN') + '</td>' +
                                    '<td class="text-center">' + value.discount + '</td>' +
                                    '<td class="text-center">' + value.actualweight.toLocaleString('en-IN') + '</td>' +
                                    '<td class="text-center">' + value.rate + '/-</td>' +
                                    '<td class="text-center">' + value.totalamount.toLocaleString('en-IN') + '/-</td>' +
                                    '<td class="text-center">' + value.number + '</td>' +
                                    '<td class="text-center">' + value.vehiclepayment.toLocaleString('en-IN') + '/-</td>' +
                                    '</tr>';
                                $('#customersalesdetails').append(html);
                            });

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="9"> <b>Total </b>  </td>' +
                                '<td class="text-center">' + total.toLocaleString('en-IN') + '/-</td>' +
                                '<td></td>'+
                                '<td class="text-center">' + totalvehicle.toLocaleString('en-IN') + '/-</td>' +
                                '</tr>';
                            $('#customersalesdetails').append(html);
                            
                        }
                    }
                });
            }

            customertabledatapurchase();

            //display customer payment data table
            function customertabledatapayment() {
                var vars = [],
                    hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'customerpayment': 'customerpayment',
                        'sdate': vars["sdate"],
                        'edate': vars["edate"]
                    },
                    success: function(response) {
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        console.log(returnedData);
                        var srno = 0;
                        if (returnedData['status'] == 0) {

                        } else {
                            var srno = 0;                                                      
                            var total = 0;                           


                            $.each(returnedData['list'], function(key, value) {
                                srno++;                                                                
                                total = parseFloat(total) + parseFloat(value.amount);

                                var html = '<tr class="odd gradeX">' +
                                    '<td class="text-center">' + srno + '</td>' +
                                    '<td class="text-center">' + value.niceDate + '</td>' +
                                    '<td class="text-center">' + value.amount.toLocaleString('en-IN') + '/-</td>' +
                                    '<td class="text-center">' + value.mode + '</td>' +                                    
                                    '<td class="text-center">' + value.details + '</td>' +                                    
                                    '</tr>';
                                $('#customerpaymentdetails').append(html);
                            });

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="2"> <b>Total Amount</b>  </td>' +                           
                                
                                '<td class="text-center">' + total.toLocaleString('en-IN') + '/-</td>' +  
                                '<td class="text-right" colspan="2"> </td>' +                               
                                '</tr>';
                            $('#customerpaymentdetails').append(html);                          
                            
                        }
                    }
                });
            }

            customertabledatapayment();

        })
    </script>
</body>

</html>
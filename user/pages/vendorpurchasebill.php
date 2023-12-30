<?php
//include("sessioncheck.php");
session_start();
ob_start();

include("../../db.php");

if (isset($_POST['vendoridbill'])) {
    $id = $_POST['vendoridbill'];
    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $data = new \stdClass();

    $data->sdate = date_format(date_create($_POST['sdate']), "d/m/Y");
    $data->edate = date_format(date_create($_POST['edate']), "d/m/Y");

    $res = mysqli_query($connection, "SELECT p.*, DATE_FORMAT(p.date,'%d/%m/%Y') AS niceDate, v.name AS vendorname, v.mobile AS vendormobile,v.address FROM vendor_purchase AS p
                                        LEFT JOIN vendor AS v ON p.vendorid = v.id
                                        WHERE p.vendorid = '$id' AND p.date <='$edate' AND p.date>='$sdate' AND p.status = 1
                                        order by p.date asc");
    if (mysqli_num_rows($res) > 0) {
        $data->status = "1";
        $data->list = mysqli_fetch_all($res, MYSQLI_ASSOC);

        $res =  mysqli_query($connection, "SELECT IFNULL(SUM(amount),0) as totalSend FROM `vendor_payment` WHERE status = 1 AND vendorid = '$id' ");
        $data->totalSend = mysqli_fetch_row($res);

        $res =  mysqli_query($connection, "SELECT IFNULL(SUM(total),0) as totalPurchase FROM `vendor_purchase` WHERE status = 1 AND vendorid = '$id' AND date < '$sdate'");
        $data->totalPurchase = mysqli_fetch_row($res);

        $res =  mysqli_query($connection, "SELECT IFNULL(pending,0) as pending FROM `vendor` WHERE id = '$id' ");
        $data->pending = mysqli_fetch_row($res);
    } else {
        $data->status = "0";
        $data->data = "Bill Data Not Found.";
    }
    echo json_encode($data);
    exit();
}

if (isset($_POST['vendoridpayment'])) {
    $id = $_POST['vendoridpayment'];
    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $data = new \stdClass();

    $data->sdate = date_format(date_create($_POST['sdate']), "d/m/Y");
    $data->edate = date_format(date_create($_POST['edate']), "d/m/Y");

    $res = mysqli_query($connection, "SELECT p.*,DATE_FORMAT(p.date,'%d/%m/%Y') AS niceDate, 
                                        w.name as vendorname, w.mobile as vendormobile 
                                        FROM `vendor_payment` as p                                         
                                        LEFT JOIN vendor as w ON p.vendorid = w.id
                                        WHERE p.vendorid = '$id' AND p.date <='$edate' AND p.date>='$sdate' AND p.status = 1
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
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $project ?> : Purchase Bill </title>

    <!-- Font Awesome -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../assets/css/print.css">

</head>

<body>
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
                            <div class="text-gray-light">Bill FROM:</div>
                            <h2 class="to" id="to"></h2>
                            <div id="mobile" class="mobile"></div>
                            <div class="address" id="address"></div>
                            <div id="email" class="email"></div>

                        </div>
                        <div class="col invoice-details">
                            <h3 class="invoice-id" id="invoice-id">Bill Details : </h3>
                            <div class="date sdate" id="sdate">Start Date : </div>
                            <div class="date edate" id="edate">End Date: </div>
                        </div>
                    </div>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>

                            <tr>
                                <th class="text-center">Sr.no. </th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Caret/Rate</th>
                                <th class="text-center">Rope/Rate</th>
                                <th class="text-center">Paper/Rate</th>
                                <th class="text-center">Tape/Rate</th>
                                <th class="text-center">Box/Rate</th>
                                <th class="text-center">Cooling Box/Rate</th>
                            </tr>
                        </thead>
                        <tbody id="salesdetails">
                            <tr>

                            </tr>
                        </tbody>
                        <tfoot>
                            <!-- <tr>
                                <td colspan="2" style="padding-top:90px  !important; "></td>
                                <td colspan="2"></td>
                                <td></td>
                            </tr> -->
                            <!-- <tr>
                                <td colspan="4">Total</td>
                                <td id="subtotal"><i class="fa fa-inr"></i></td>
                            </tr> -->
                            <!-- <tr>                                
                                <td colspan="4">Pending </td>
                                <td id="pending"><i class="fa fa-inr"></i></td>
                            </tr>
                            <tr>                                
                                <td colspan="4">Grand Total</td>
                                <td id="grandtotal"><i class="fa fa-inr"></i></td>
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

    <div id="invoice">

        <div class="toolbar hidden-print">
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
                            <div class="text-gray-light">Payment To Vendor:</div>
                            <h2 class="to" id="to"></h2>
                            <div id="mobile" class="mobile"></div>
                            <div class="address" id="address"></div>
                            <div id="email" class="email"></div>

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
                        <tbody id="paymentdetails">
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
    <!-- jQuery 3 -->
    <!-- <script src="../../bower_components/jquery/dist/jquery.min.js"></script> -->
    <!-- Bootstrap 3.3.7 -->
    <!-- <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script> -->
    <!-- SlimScroll -->
    <!-- <script src="../../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script> -->
    <!-- FastClick -->
    <!-- <script src="../../bower_components/fastclick/lib/fastclick.js"></script> -->

    <script>
        $(document).ready(function() {
            $('#printInvoice').click(function() {
                Popup($('.invoice')[0].outerHTML);

                function Popup(data) {
                    window.print();
                    return true;
                }
            });

            //display data table
            function billtabledata() {
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
                        'vendoridbill': vars["vendorid"],
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
                            document.title = returnedData['list'][0]['vendorname'] + '_' + vars["sdate"] + " - " + vars["edate"];
                            $(".to").append(returnedData['list'][0]['vendorname']);
                            $(".address").append(returnedData['list'][0]['address']);
                            $(".email").append(returnedData['list'][0]['email']);
                            $(".mobile").append("Mobile : " + returnedData['list'][0]['vendormobile']);

                            $(".sdate").append(returnedData["sdate"]);
                            $(".edate").append(returnedData["edate"]);

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
                                    '<td class="text-center">' + value.caret_quantity + ' / '+ value.caret_rate + '</td>' +
                                    '<td class="text-center">' + value.rope_quantity + ' / '+ value.rope_rate + '</td>' +
                                    '<td class="text-center">' + value.paper_quantity + ' / '+ value.paper_rate + '</td>' +
                                    '<td class="text-center">' + value.tape_quantity + ' / '+ value.tape_rate + '</td>' +
                                    '<td class="text-center">' + value.box_quantity + ' / '+ value.box_rate + '</td>' +
                                    '<td class="text-center">' + value.collingbox_quantity + ' / '+ value.collingbox_rate + '</td>' +

                                    '</tr>';
                                $('#salesdetails').append(html);
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
                            $('#salesdetails').append(html);

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="2"> <b>Total Price</b>  </td>' +
                                '<td class="text-center">' + caretTotal.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-center">' + ropeTotal.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-center">' + paperTotal.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-center">' + tapeTotal.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-center">' + boxTotal.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-center">' + collingboxTotal.toLocaleString('en-IN') + '/-</td>' +

                                '</tr>';
                            $('#salesdetails').append(html);

                            var total1 = parseFloat(caretTotal) + parseFloat(ropeTotal) + parseFloat(paperTotal) + parseFloat(tapeTotal) + parseFloat(boxTotal) + parseFloat(collingboxTotal);
                            var pending = parseFloat(returnedData['totalPurchase'][0]) + parseFloat(returnedData['pending'][0]) - parseFloat(returnedData['totalSend'][0]);
                            // $("#subtotal").append(parseFloat(total1).toLocaleString('en-IN') + "/-");
                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="4"> <b>Total </b>  </td>' +
                                '<td class="text-center" colspan="4">' + total1.toLocaleString('en-IN') + '/-</td>' +
                                '</tr>';
                            $('#salesdetails').append(html);
                            // $("#pending").append(parseFloat(pending).toLocaleString('en-IN') + "/-");
                            // $("#grandtotal").append(parseFloat(parseFloat(total1) + parseFloat(pending)).toLocaleString('en-IN') + "/- ");
                        }
                    }
                });
            }

            billtabledata();

            function paymenttabledata() {
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
                        'vendoridpayment': vars["vendorid"],
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
                            document.title = "Vendor Payment Details " + returnedData['list'][0]['vendorname'] + '_' + vars["sdate"] + " - " + vars["edate"];
                            
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
                                $('#paymentdetails').append(html);
                            });

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="2"> <b>Total Amount</b>  </td>' +

                                '<td class="text-center">' + total.toLocaleString('en-IN') + '/-</td>' +
                                '<td class="text-right" colspan="2"> </td>' +
                                '</tr>';
                            $('#paymentdetails').append(html);

                        }
                    }
                });
            }

            paymenttabledata();

            const wordify = (num) => {
                const single = ["Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"];
                const double = ["Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
                const tens = ["", "Ten", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
                const formatTenth = (digit, prev) => {
                    return 0 == digit ? "" : " " + (1 == digit ? double[prev] : tens[digit])
                };
                const formatOther = (digit, next, denom) => {
                    return (0 != digit && 1 != next ? " " + single[digit] : "") + (0 != next || digit > 0 ? " " + denom : "")
                };
                let res = "";
                let index = 0;
                let digit = 0;
                let next = 0;
                let words = [];
                if (num += "", isNaN(parseInt(num))) {
                    res = "";
                } else if (parseInt(num) > 0 && num.length <= 10) {
                    for (index = num.length - 1; index >= 0; index--) switch (digit = num[index] - 0, next = index > 0 ? num[index - 1] - 0 : 0, num.length - index - 1) {
                        case 0:
                            words.push(formatOther(digit, next, ""));
                            break;
                        case 1:
                            words.push(formatTenth(digit, num[index + 1]));
                            break;
                        case 2:
                            words.push(0 != digit ? " " + single[digit] + " Hundred" + (0 != num[index + 1] && 0 != num[index + 2] ? " and" : "") : "");
                            break;
                        case 3:
                            words.push(formatOther(digit, next, "Thousand"));
                            break;
                        case 4:
                            words.push(formatTenth(digit, num[index + 1]));
                            break;
                        case 5:
                            words.push(formatOther(digit, next, "Lakh"));
                            break;
                        case 6:
                            words.push(formatTenth(digit, num[index + 1]));
                            break;
                        case 7:
                            words.push(formatOther(digit, next, "Crore"));
                            break;
                        case 8:
                            words.push(formatTenth(digit, num[index + 1]));
                            break;
                        case 9:
                            words.push(0 != digit ? " " + single[digit] + " Hundred" + (0 != num[index + 1] || 0 != num[index + 2] ? " and" : " Crore") : "")
                    };
                    res = words.reverse().join("")
                } else res = "";
                return res
            };
        })
    </script>
</body>

</html>
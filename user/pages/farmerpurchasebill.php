<?php
//include("sessioncheck.php");
session_start();
ob_start();

include("../../db.php");
//Farmer Purchase
if (isset($_POST['farmeridpurchase'])) {
    $id = $_POST['farmeridpurchase'];
    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $data = new \stdClass();

    $data->sdate = date_format(date_create($_POST['sdate']), "d/m/Y");
    $data->edate = date_format(date_create($_POST['edate']), "d/m/Y");

    $res = mysqli_query($connection, "SELECT p.*,DATE_FORMAT(p.date,'%d/%m/%Y') AS niceDate, 
                                        f.name as farmername, f.mobile as farmermobile from farmer_purchase as p
                                        LEFT join farmer as f ON p.farmerid = f.id
                                        WHERE p.farmerid = '$id' AND p.date <='$edate' AND p.date>='$sdate' AND p.status = 1
                                        order by p.date asc
                                        ");
    if (mysqli_num_rows($res) > 0) {
        $data->status = "1";
        $data->list = mysqli_fetch_all($res, MYSQLI_ASSOC);

        $res =  mysqli_query($connection, "SELECT IFNULL(SUM(amount),0) as totalSend FROM `farmer_payment` WHERE status = 1 AND farmerid = '$id' ");
        $data->totalSend = mysqli_fetch_row($res);

        $res =  mysqli_query($connection, "SELECT IFNULL(SUM(totalamount),0) as totalPurchase FROM `farmer_purchase` WHERE status = 1 AND farmerid = '$id' AND date < '$sdate'");
        $data->totalPurchase = mysqli_fetch_row($res);

        $res =  mysqli_query($connection, "SELECT IFNULL(pending,0) as pending FROM `farmer` WHERE id = '$id' ");
        $data->pending = mysqli_fetch_row($res);
    } else {
        $data->status = "0";
        $data->data = "Bill Data Not Found.";
    }
    echo json_encode($data);
    exit();
}

//Farmer Payment
if (isset($_POST['farmeridpayment'])) {
    $id = $_POST['farmeridpayment'];
    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $data = new \stdClass();

    $data->sdate = date_format(date_create($_POST['sdate']), "d/m/Y");
    $data->edate = date_format(date_create($_POST['edate']), "d/m/Y");   

    $res = mysqli_query($connection, "SELECT p.*, DATE_FORMAT(p.date,'%d/%m/%Y') AS niceDate, 
                                        v.name AS farmername, v.mobile AS farmermobile,v.address FROM farmer_payment AS p
                                        LEFT JOIN farmer AS v ON p.farmerid = v.id
                                        WHERE p.farmerid = '$id' AND p.date <='$edate' AND p.date>='$sdate' AND p.status = 1
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
    <title><?= $project ?> : Farmer Purchase Bill </title>

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
                        <tbody id="salesdetails">
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
                        <tbody id="paymentdetails">
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
            function tabledatapurchase() {
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
                        'farmeridpurchase': vars["farmerid"],
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
                            document.title = "Farmer purchase bill " + returnedData['list'][0]['farmername'] + '_' + vars["sdate"] + " - " + vars["edate"];
                            $(".to").append(returnedData['list'][0]['farmername']);
                            // $(".address").append(returnedData['list'][0]['address']);
                            // $(".email").append(returnedData['list'][0]['email']);
                            $(".mobile").append("Mobile : " + returnedData['list'][0]['farmermobile']);

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
                                $('#salesdetails').append(html);
                            });

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="8"> <b>Total Quantity</b>  </td>' +
                                '<td class="text-center">' + total.toLocaleString('en-IN') + '/-</td>' +
                                '</tr>';
                            $('#salesdetails').append(html);

                            // var total1 = parseFloat(caretTotal) + parseFloat(box5kgTotal) + parseFloat(paperTotal) + parseFloat(tapeTotal) + parseFloat(tawimTotal) + parseFloat(brboxTotal) + parseFloat(whiterimTotal)+ parseFloat(pinkrimTotal);
                            // var pending = parseFloat(returnedData['totalPurchase'][0]) + parseFloat(returnedData['pending'][0]) - parseFloat(returnedData['totalSend'][0]);
                            // $("#subtotal").append(parseFloat(total1).toLocaleString('en-IN') + "/-");
                            // $("#pending").append(parseFloat(pending).toLocaleString('en-IN') + "/-");
                            // $("#grandtotal").append(parseFloat(parseFloat(total1) + parseFloat(pending)).toLocaleString('en-IN') + "/- ");
                        }
                    }
                });
            }

            tabledatapurchase();

            //display farmer payment data table
            function tabledatapayment() {
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
                        'farmeridpayment': vars["farmerid"],
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
                            document.title = "Farmer Payment Details "+ returnedData['list'][0]['farmername'] + '_' + vars["sdate"] + " - " + vars["edate"];
                            // $(".to").append(returnedData['list'][0]['farmername']);
                            // $(".address").append(returnedData['list'][0]['address']);
                            // $(".email").append(returnedData['list'][0]['email']);
                            // $(".mobile").append("Mobile : " + returnedData['list'][0]['farmermobile']);

                            // $(".sdate").append(returnedData["sdate"]);
                            // $(".edate").append(returnedData["edate"]);

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

                           
                            // var total1 = parseFloat(caretTotal) + parseFloat(box5kgTotal) + parseFloat(paperTotal) + parseFloat(tapeTotal) + parseFloat(tawimTotal) + parseFloat(brboxTotal) + parseFloat(whiterimTotal)+ parseFloat(pinkrimTotal);
                            // var pending = parseFloat(returnedData['totalPurchase'][0]) + parseFloat(returnedData['pending'][0]) - parseFloat(returnedData['totalSend'][0]);
                            // $("#subtotal").append(parseFloat(total1).toLocaleString('en-IN') + "/-");
                            // $("#pending").append(parseFloat(pending).toLocaleString('en-IN') + "/-");
                            // $("#grandtotal").append(parseFloat(parseFloat(total1) + parseFloat(pending)).toLocaleString('en-IN') + "/- ");
                        }
                    }
                });
            }

            tabledatapayment();

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
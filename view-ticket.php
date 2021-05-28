<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/navBar.css">
    <link rel="stylesheet" href="css/index.css">
    <title>SUPU</title>
</head>

<body>

    <?php
    include("includes/header.html");
    include("includes/navBar.php");
    ?>

    <?php
        $tid = $_GET['ticketid'];
        $ret = mysqli_query($con, "select * from tickets where ID='$tid'");
        $cnt = 1;
        while ($row = mysqli_fetch_array($ret)) {

            
$user_id = $_SESSION['user_id'];

$get_users = mysqli_query($con, "SELECT * FROM users WHERE ID = '$user_id'");
$row_user = mysqli_fetch_array($get_users); 

if ($row_user['UserType'] == 'admin')
{
        ?>
    <div style="margin-top:20px; margin-left: 50px; margin-right:40px; padding: 25px; background-color:white;">
        <a href="orders.php">Natrag</a>
    </div>
    <?php 
}
?>

    <div id="print"
        style="margin-top:20px; margin-left: 50px; margin-right:40px; padding: 25px; background-color:white;">
        <h4 class="header-title" style="color: blue">Identifikacijski broj ulaznice: <?php echo $row['TicketID']; ?>
        </h4>
        <h5 class="header-title" style="color: blue">Datum narudžbe: <?php echo $row['PostingDate']; ?></h5>
        <table class="table table-striped" style="text-align: center;">
            <tr>
                <th style="text-align: left;">Vrsta ulaznice</th>
                <th>Broj ulaznica</th>
                <th>Cijena po ulaznici</th>
                <th>Ukupno</th>
            </tr>

            <tr>
                <th style="text-align: left;">Za odrasle</th>
                <td style="padding-left: 10px;"><?php echo $noadult = $row['NoAdult']; ?></td>
                <td style="padding-left: 10px">$<?php echo $aup = $row['AdultUnitPrice']; ?></td>
                <td style="padding-left: 10px">$<?php echo $ta = $aup * $noadult; ?></td>
            </tr>

            <tr>
                <th style="text-align: left;">Za djecu</th>
                <td style="padding-left: 10px"><?php echo $nochild = $row['NoChildren']; ?></td>
                <td style="padding-left: 10px">$<?php echo $cup = $row['ChildUnitPrice']; ?></td>
                <td style="padding-left: 10px">$<?php echo $tc = $cup * $nochild; ?></td>
            </tr>

            <tr>
                <th style="text-align: center;color: red;font-size: 20px; padding-left:100px" colspan="3">Ukupna cijena
                    ulaznica</th>
                <td style="padding-left: 10px; color:red">$<?php echo ($ta + $tc); ?></td>
            </tr>
            <tr>
                <th style="text-align: center;color: red;font-size: 20px; padding-left:100px" colspan="3">Cijena s popustom</th>
                <td style="padding-left: 10px; color:red">$<?php echo (100-(($row['PromoDiscount']/($ta + $tc))*100)); ?></td>
            </tr>
        </table>
        <p style="margin-top:1%; text-align:center">
            <i class="fa fa-print fa-2x" style="cursor: pointer;" OnClick="CallPrint(this.value)"></i>
        </p>
    </div>

    <?php
if ($row_user['UserType'] == 'admin')
{
?>
    <div style="margin-top:20px; margin-left: 50px; margin-right:40px; padding: 25px; background-color:white;">
        <h4>Podaci o kupcu</h4>
        <p>IME: <span><?php echo $row['BuyerName'] ?></span></p>
        <p>PREZIME: <span><?php echo $row['BuyerSurname'] ?></span></p>
        <p>ADRESA: <span><?php echo $row['BuyerAddress'] ?></span></p>
        <p style="text-align: center;">STATUS NARUDŽBE: <span><?php echo $row['Status'] ?></span></p>
    </div>

    <?php 
        if ($row['Status'] != 'Isporučeno') {
    ?>
    <div style="margin-top:20px; margin-left: 50px; margin-right:40px; padding: 25px; background-color:white; text-align: center;">
        <a href="check.php?ticketid=<?php echo $row['ID']; ?>">Označi kao isporučeno</a>
    </div>
    <?php 
        }       
}
?>

    <script>
    function CallPrint(strid) {
        var prtContent = document.getElementById("print");
        var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
    }
    </script>
    <?php }  ?>

</body>

</html>
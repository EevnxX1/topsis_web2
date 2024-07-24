<?php
ini_set("display_errors", "Off");
session_start();
$username = $_SESSION['username'];
if (!isset($_SESSION['username'])) {
    header("Location:./login.php?msg=Silahkan Login Dahulu");
    exit();
}
include "connect.php";
$sql_user = "SELECT * FROM login WHERE user='$username'";
$hasil_user = mysqli_query($conn, $sql_user);
$pilih_user = mysqli_fetch_array($hasil_user);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>SPK TOPSIS</title>
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <link href="css/style.css" rel="stylesheet">
    <link href="css/colors/blue.css" id="theme" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<script language="JavaScript" type="text/JavaScript">
    var newwindow;
    function KonfirmasiHapus(submodule, id) {
        var jawaban = confirm("Anda Yakin record ini akan dihapus?");
        if (jawaban) {
            location.href = "" + submodule + "=" + id;
        }
    }
</script>
<body class="fix-header fix-sidebar card-no-border">
    <div id="main-wrapper">
        <header class="topbar">
            <nav class="navbar top-navbar navbar-toggleable-sm navbar-light" style="background-color: #6E7BF5;">
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.html">
                        <b></b>
                        <span class="" style="font-weight: 800; font-size: larger; color: white;">UnivApp</span>
                    </a>
                </div>
                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <li class="nav-item"><a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a></li>
                    </ul>
                </div>
            </nav>
        </header>

<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbase = "indekos";

$conn = mysqli_connect($host, $user, $pass, $dbase);
if (!$conn) {
    die("Database mysql tidak terkoneksi: " . mysqli_connect_error());
}
?>

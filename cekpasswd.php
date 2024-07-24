<?php
session_start();
include("connect.php");

$username = $_POST['username'];
$password = $_POST['password'];
$tipe = $_POST['tipe'];

$sql_user = "SELECT * FROM login WHERE user=? AND password=MD5(?) AND status=?";
$stmt = mysqli_prepare($conn, $sql_user);
mysqli_stmt_bind_param($stmt, "sss", $username, $password, $tipe);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rowcount = mysqli_num_rows($result);

if ($rowcount == 1) {
    $user = mysqli_fetch_array($result);
    $_SESSION['username'] = $username;
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['alamat'] = $user['alamat'];
    $_SESSION['telepon'] = $user['no_telepon'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['status'] = $user['status'];
    header("Location: halutama.php");
} else {
    header("Location: ./login.php?msg=Username atau password yang anda masukkan salah");
}
?>

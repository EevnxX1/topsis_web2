<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "header.php";
include "menu.php";
?>

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Dashboard</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-block">
                        <?php if ($_SESSION['status'] == 'admin'): ?>
                            <h1 class="mt-5"><p align="center">Halaman Utama Administrator</p></h1>
                            <p class="lead" align="center">
                                SISTEM PENDUKUNG KEPUTUSAN PEMILIHAN INDEKOS TERBAIK DI SEKITAR KAMPUS<br>MENGGUNAKAN METODE TOPSIS
                            </p>
                            <p class="lead" align="center"><img src="assets/images/logo1.png" align="center" width="150"></p>
                        <?php elseif ($_SESSION['status'] == 'pemilik'): ?>
                            <h1 class="mt-5"><p align="center">Anda Login Sebagai Pemilik Kos</p></h1>
                            <p align="center">
                                SISTEM PENDUKUNG KEPUTUSAN PEMILIHAN INDEKOS TERBAIK DI SEKITAR KAMPUS<br>MENGGUNAKAN METODE TOPSIS
                            </p>
                            <p class="lead" align="center"><img src="assets/images/logo1.png" align="center" width="150"></p>
                            <table class="table table-sm" align="left">
                                <tr><td>Nama</td><td>: <?php echo $_SESSION['nama']; ?></td></tr>
                                <tr><td>Alamat</td><td>: <?php echo $_SESSION['alamat']; ?></td></tr>
                                <tr><td>No. telepon</td><td>: <?php echo $_SESSION['telepon']; ?></td></tr>
                                <tr><td>Email</td><td>: <?php echo $_SESSION['email']; ?></td></tr>
                                <tr><td>Login Sebagai</td><td>: <?php echo $_SESSION['status'] == 'user' ? 'Pencari Kos' : 'Pemilik Kos'; ?></td></tr>
                            </table>
                        <?php elseif ($_SESSION['status'] == 'user'): ?>
                            <h1 class="mt-5"><p align="center">Anda Login Sebagai Pencari Universitas</p></h1>
                            <p align="center">
                                SISTEM PENDUKUNG KEPUTUSAN PEMILIHAN UNIVERSITAS TERBAIK<br>MENGGUNAKAN METODE TOPSIS
                            </p>
                            <p class="lead" align="center"><img src="assets/images/logo1.png" align="center" width="80"></p>
                            <table class="table table-sm" align="left">
                                <tr><td>Nama</td><td>: <?php echo $_SESSION['nama']; ?></td></tr>
                                <tr><td>Alamat</td><td>: <?php echo $_SESSION['alamat']; ?></td></tr>
                                <tr><td>No. telepon</td><td>: <?php echo $_SESSION['telepon']; ?></td></tr>
                                <tr><td>Email</td><td>: <?php echo $_SESSION['email']; ?></td></tr>
                                <tr><td>Login Sebagai</td><td>: <?php echo $_SESSION['status'] == 'user' ? 'Pencari Kos' : 'Pemilik Kos'; ?></td></tr>
                            </table>
                        <?php else: ?>
                            <h1 class="mt-5">Anda Tidak Dapat mengakses Halaman Ini</h1>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>

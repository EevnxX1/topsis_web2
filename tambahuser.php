<?php
ini_set("display_errors", "Off");
include("connect.php");
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
</head>
<body style="background-color: #6E7BF5; height: 100vh;">
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1 class="mt-5" style="color: white;">Tambah User</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form action="" class="form-horizontal" role="form" method="post">
                            <div style="margin-bottom: 25px" class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <input id="login-username" type="text" class="form-control" name="nama" placeholder="Nama Lengkap">
                            </div>
                            <div style="margin-bottom: 25px" class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <textarea class="form-control" name="alamat" rows="5" cols="50" placeholder="Alamat"></textarea>
                            </div>
                            <div style="margin-bottom: 25px" class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <input id="login-username" type="text" class="form-control" name="telepon" placeholder="Nomor Telepon">
                            </div>
                            <div style="margin-bottom: 25px" class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <input id="login-username" type="text" class="form-control" name="email" placeholder="Alamat Email">
                            </div>
                            <div style="margin-bottom: 25px" class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <select class="form-control" name="tipe">
                                    <option value="0" selected>- Pilih Level -</option>
                                    <option value="user">Pencari Universitas</option>
                                    <option value="pemilik">Pemilik Universitas</option>
                                </select>
                            </div>
                            <div style="margin-bottom: 25px" class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input id="login-password" type="text" class="form-control" name="username" placeholder="Username">
                            </div>
                            <div style="margin-bottom: 25px" class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input id="login-password" type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                            <div style="margin-top: 10px" class="form-group">
                                <div class="col-sm-12 controls">
                                    <input id="btn-fblogin" class="btn btn-primary" type="submit" value="Tambah User" />
                                    <a class="btn btn-warning" href="index.php">Kembali</a>
                                </div>
                            </div>
                        </form>

                        <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $nama = $_POST['nama'];
                            $alamat = $_POST['alamat'];
                            $telepon = $_POST['telepon'];
                            $email = $_POST['email'];
                            $tipe = $_POST['tipe'];
                            $username = $_POST['username'];
                            $password = $_POST['password'];

                            if (isset($nama, $telepon)) {
                                if (empty($nama) || empty($telepon)) {
                                    echo "<script>alert('Harap semua data diisi...!!');</script>";
                                    echo "<script>self.history.back('Gagal Menyimpan');</script>";
                                    exit();
                                }

                                $add_kelas = "INSERT INTO login(nama, alamat, no_telepon, email, status, user, password) VALUES 
                                    ('$nama', '$alamat', '$telepon', '$email', '$tipe', '$username', md5('$password'))";
                                if (mysqli_query($conn, $add_kelas)) {
                                    echo '<script>alert("Data Berhasil Ditambah!");</script>';
                                    echo '<meta http-equiv="refresh" content="1; url=user.php" />';
                                } else {
                                    echo '<script>alert("Gagal menambah data!");</script>';
                                }
                            }
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#frm-mhs').validate({
            rules: {
                nama_kriteria: {
                    minlength: 2,
                    required: true
                }
            },
            messages: {
                nama_kriteria: {
                    required: "* Kolom nama kriteria harus diisi",
                    minlength: "* Kolom nama kriteria harus terdiri dari minimal 2 digit"
                }
            }
        });
    });
</script>

</body>
</html>

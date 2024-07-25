<?php
include "header.php";
include "menu.php";
include "connect.php";

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get user ID from session
$id = $_SESSION['username'] ?? '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate inputs
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $namakos = mysqli_real_escape_string($conn, $_POST['namakos']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    if (!empty($nama) && !empty($namakos) && !empty($telepon) && !empty($alamat)) {
        $add_kelas = "INSERT INTO pemilik (user, nama, nama_kos, telepon, alamat) VALUES ('$id', '$nama', '$namakos', '$telepon', '$alamat')";
        if (mysqli_query($conn, $add_kelas)) {
            echo "<script>alert('Data Berhasil Ditambah!');</script>";
            echo '<meta http-equiv="refresh" content="1; url=biopemilikkos.php" />';
        } else {
            echo "<script>alert('Gagal menyimpan data: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Harap semua data diisi!');</script>";
    }
}

// Fetch existing data
$show_kelas = "SELECT * FROM pemilik WHERE user='$id'";
$hasil_kelas = mysqli_query($conn, $show_kelas);
$view_kelas = mysqli_fetch_assoc($hasil_kelas);
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
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h1 class="mt-5">Biodata Pemilik Universitas</h1>

                                    <?php if ($view_kelas) { ?>
                                        <div class="table-responsive">
                                            <table class="table" align="left">
                                                <tr>
                                                    <td>Nama Lengkap</td>
                                                    <td><?php echo htmlspecialchars($view_kelas['nama']); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Nama Universitas</td>
                                                    <td><?php echo htmlspecialchars($view_kelas['nama_kos']); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>No. Telepon</td>
                                                    <td><?php echo htmlspecialchars($view_kelas['telepon']); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Alamat</td>
                                                    <td><?php echo htmlspecialchars($view_kelas['alamat']); ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    <?php } else { ?>
                                        <div class="alert alert-info">
                                            <strong>Info!</strong> Sebelum anda dapat melakukan pengisian Ruangan Universitas, terlebih dahulu harus mengisi biodata pemilik Universitas. Terima kasih.
                                        </div>

                                        <form action="" class="form-horizontal" data-toggle="validator" role="form" method="post">
                                            <div class="form-group">
                                                <label class="control-label" for="nama">Nama Lengkap</label>
                                                <input class="form-control" data-error="Nama Lengkap Tidak Boleh Kosong." id="nama" placeholder="Nama Lengkap" name="nama" type="text" required />
                                                <div class="help-block with-errors"></div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label" for="namakos">Nama Universitas</label>
                                                <input class="form-control" data-error="Nama Kos Tidak Boleh Kosong." id="namakos" placeholder="Nama Hunian Kos" name="namakos" type="text" required />
                                                <div class="help-block with-errors"></div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label" for="telepon">Nomor Telepon</label>
                                                <input class="form-control" data-error="Nomor Telepon Tidak Boleh Kosong." type="number" placeholder="Nomor telepon" name="telepon" required />
                                                <div class="help-block with-errors"></div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label" for="alamat">Alamat</label>
                                                <textarea class="form-control" data-error="Alamat Tidak Boleh Kosong." id="alamat" name="alamat" rows="5" cols="50" placeholder="Alamat" required></textarea>
                                                <div class="help-block with-errors"></div>
                                            </div>

                                            <div class="form-group">
                                                <input id="btn-fblogin" class="btn btn-danger" type="submit" value="Input Data Pemilik Univ" />
                                            </div>
                                        </form>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery.js"></script>
<script src="js/validator.min.js"></script>
<script>
    $(document).ready(function() {
        $('form').parsley();
    });
</script>

<?php include "footer.php"; ?>

<?php
ini_set("display_errors", "Off");
include "header.php";
include "menu.php";
include("../connect.php");

// Ensure proper session handling if needed
session_start();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $nilai = mysqli_real_escape_string($conn, $_POST['nilai']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    if (!empty($nilai) && !empty($keterangan)) {
        // Prepare and execute the SQL statement
        $add_kelas = "INSERT INTO bobotpenilaian (nilai_bobot, keterangan_bobot) VALUES ('$nilai', '$keterangan')";
        if (mysqli_query($conn, $add_kelas)) {
            echo "<script>alert('Data Berhasil Ditambah!');</script>";
            echo '<meta http-equiv="refresh" content="1; url=' . $_SERVER['PHP_SELF'] . '" />';
        } else {
            echo "<script>alert('Gagal menyimpan data: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Harap semua data diisi!');</script>";
    }
}
?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">DATA BOBOT</h1>
            </div>
        </div>
        <div class="row">
            <div class="panel-body">
                <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
                    + TAMBAH DATA BOBOT
                </button>
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="myModalLabel">Tambah Data Bobot</h4>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post">
                                    <div class="form-group">
                                        <label>Nilai</label>
                                        <input type="text" class="form-control" name="nilai" required />
                                    </div>
                                    <div class="form-group">
                                        <label>Keterangan</label>
                                        <input type="text" class="form-control" name="keterangan" required />
                                    </div>
                                    <div class="form-group">
                                        <input class="btn btn-default" type="submit" value="Simpan" />
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                List Data Bobot
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Bobot Nilai</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = mysqli_query($conn, "SELECT * FROM bobotpenilaian ORDER BY id_bobotpenilaian DESC");
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($sql)) { ?>
                                <tr class='td' bgcolor='#FFF'>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo htmlspecialchars($row['nilai_bobot']); ?></td>
                                    <td><?php echo htmlspecialchars($row['keterangan_bobot']); ?></td>
                                    <td>
                                        <a class='btn btn-warning' href='editbobot.php?idk=<?php echo $row['id_bobotpenilaian']; ?>'>Ubah</a>
                                        <a class='btn btn-danger' href='javascript:KonfirmasiHapus("deletebobot.php?idk=<?php echo $row['id_bobotpenilaian']; ?>")'>Hapus</a>
                                    </td>
                                </tr>
                            <?php $no++; } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>

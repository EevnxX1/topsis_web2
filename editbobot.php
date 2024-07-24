<?php
ini_set("display_errors", "Off");
include "header.php";
include "menu.php";
include("../connect.php");

// Sanitize input
$id = intval($_GET['idk']);

// Prepare and execute query
$sql = $conn->prepare("SELECT * FROM bobotpenilaian WHERE id_bobotpenilaian = ?");
$sql->bind_param("i", $id);
$sql->execute();
$result = $sql->get_result();
$view_kelas = $result->fetch_assoc();
?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Ubah Data Bobot</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Ubah Data Bobot
                    </div>
                    <div class="panel-body">
                        <form action="" method="post">
                            <div class="form-group">
                                <label>Id Bobot</label>
                                <input readonly type="text" class="form-control" name="id_bobotpenilaian" value="<?php echo htmlspecialchars($view_kelas['id_bobotpenilaian']); ?>" />
                            </div>
                            <div class="form-group">
                                <label>Nilai</label>
                                <input type="text" class="form-control" name="nilai" value="<?php echo htmlspecialchars($view_kelas['nilai']); ?>" />
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <input type="text" class="form-control" name="keterangan" value="<?php echo htmlspecialchars($view_kelas['keterangan']); ?>" />
                            </div>
                            <div class="form-group">
                                <input class="btn btn-success" type="submit" name="submit" value="Simpan" />
                                <a class="btn btn-warning" href="bobot.php">Kembali</a>
                            </div>
                        </form>

                        <?php
                        if (isset($_POST['submit'])) {
                            // Sanitize form data
                            $id_bobotpenilaian = intval($_POST['id_bobotpenilaian']);
                            $nilai = $conn->real_escape_string($_POST['nilai']);
                            $keterangan = $conn->real_escape_string($_POST['keterangan']);

                            // Validation
                            if (empty($nilai) || empty($keterangan)) {
                                echo "<script>alert('Harap semua data diisi...!!');</script>";
                            } else {
                                // Prepare and execute update query
                                $update_sql = $conn->prepare("UPDATE bobotpenilaian SET nilai = ?, keterangan = ? WHERE id_bobotpenilaian = ?");
                                $update_sql->bind_param("ssi", $nilai, $keterangan, $id_bobotpenilaian);

                                if ($update_sql->execute()) {
                                    echo '<script>alert("Data Berhasil Diubah!");</script>';
                                    echo '<meta http-equiv="refresh" content="1; url=bobot.php" />';
                                } else {
                                    echo '<script>alert("Gagal menyimpan data.");</script>';
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

<?php
include "footer.php";
?>

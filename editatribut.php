<?php
ini_set("display_errors", "Off");
include "header.php";
include "menu.php";
include("../connect.php");

// Sanitize input
$id = intval($_GET['idk']);

// Fetch data
$sql = $conn->prepare("SELECT * FROM atribut WHERE id_atribut = ?");
$sql->bind_param("i", $id);
$sql->execute();
$view_kelas = $sql->get_result()->fetch_assoc();
?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Ubah Data Atribut</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Ubah Data Atribut
                    </div>
                    <div class="panel-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Id Atribut</label>
                                <input readonly type="text" class="form-control" name="id_atribut" value="<?php echo htmlspecialchars($view_kelas['id_atribut']); ?>" />
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <input type="text" class="form-control" name="keterangan" value="<?php echo htmlspecialchars($view_kelas['keterangan']); ?>" />
                            </div>
                            <div class="form-group">
                                <input class="btn btn-success" type="submit" name="submit" value="Simpan" />
                                <a class="btn btn-warning" href="atribut.php">Kembali</a>
                            </div>
                        </form>

                        <?php
                        if (isset($_POST['submit'])) {
                            // Sanitize form data
                            $id_atribut = intval($_POST['id_atribut']);
                            $keterangan = $conn->real_escape_string($_POST['keterangan']);

                            // Validation
                            if (empty($keterangan)) {
                                echo "<script>alert('Harap semua data diisi...!!');</script>";
                            } else {
                                // Update data
                                $update_sql = $conn->prepare("UPDATE atribut SET keterangan = ? WHERE id_atribut = ?");
                                $update_sql->bind_param("si", $keterangan, $id_atribut);

                                if ($update_sql->execute()) {
                                    echo '<script>alert("Data Berhasil Diubah!");</script>';
                                    echo '<meta http-equiv="refresh" content="1; url=atribut.php" />';
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

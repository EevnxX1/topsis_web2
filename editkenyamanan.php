<?php
ini_set("display_errors", "Off");
include "header.php";
include "connect.php";

// Sanitize input
$id = intval($_GET['idk']);

// Prepare and execute query
$sql = $conn->prepare("
    SELECT * 
    FROM kamar
    LEFT JOIN pemilik ON kamar.id_pemilik = pemilik.id_pemilik
    LEFT JOIN kenyamanankos ON kamar.id_kamar = kenyamanankos.id_kamar
    LEFT JOIN kenyamanan ON kenyamanan.id_kenyamanan = kenyamanankos.id_kenyamanan 
    WHERE kenyamanankos.id_kenyamanankos = ?
");
$sql->bind_param("i", $id);
$sql->execute();
$result = $sql->get_result();
$view_kelas = $result->fetch_assoc();
?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Ubah Data Kriteria</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form action="" method="post">
                            <div class="form-group">
                                <label>Id</label>
                                <input readonly type="text" class="form-control" name="id_kenyamanankos" value="<?php echo htmlspecialchars($view_kelas['id_kenyamanankos']); ?>" />
                            </div>
                            <div class="form-group">
                                <label>Nama Kos</label>
                                <select class="form-control" name="pemilik">
                                    <?php
                                    $tampil = $conn->query("SELECT * FROM pemilik JOIN kamar ON pemilik.id_pemilik = kamar.id_pemilik ORDER BY pemilik.id_pemilik");
                                    while ($w = $tampil->fetch_assoc()) {
                                        $selected = ($view_kelas['id_pemilik'] == $w['id_pemilik']) ? 'selected' : '';
                                        echo "<option value='{$w['id_kamar']}' $selected>{$w['nama_kos']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Kenyamanan</label>
                                <select class="form-control" name="kenyamanan">
                                    <?php
                                    $tampil = $conn->query("SELECT * FROM kenyamanan ORDER BY id_kenyamanan");
                                    while ($w = $tampil->fetch_assoc()) {
                                        $selected = ($view_kelas['id_kenyamanan'] == $w['id_kenyamanan']) ? 'selected' : '';
                                        echo "<option value='{$w['id_kenyamanan']}' $selected>{$w['kenyamanannya']}-{$w['id_kenyamanan']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <input class="btn btn-success" type="submit" value="Simpan" />
                                <a class="btn btn-warning" href="kenyamanan.php">Kembali</a>
                            </div>
                        </form>

                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            // Sanitize form data
                            $id_kenyamanankos = intval($_POST['id_kenyamanankos']);
                            $pemilik = intval($_POST['pemilik']);
                            $kenyamanan = intval($_POST['kenyamanan']);

                            if (empty($id_kenyamanankos) || empty($pemilik) || empty($kenyamanan)) {
                                echo "<script>alert('Harap semua data diisi!');</script>";
                                echo "<script>window.history.back();</script>";
                                exit();
                            }

                            // Prepare and execute update query
                            $update = $conn->prepare("UPDATE kenyamanankos SET id_kenyamanan = ?, id_kamar = ? WHERE id_kenyamanankos = ?");
                            $update->bind_param("iii", $kenyamanan, $pemilik, $id_kenyamanankos);
                            $update->execute();

                            echo '<script>alert("Data Berhasil Diubah!");</script>';
                            echo '<meta http-equiv="refresh" content="0; url=kenyamanan.php" />';
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

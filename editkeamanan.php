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
    LEFT JOIN keamanankos ON kamar.id_kamar = keamanankos.id_kamar
    LEFT JOIN keamanan ON keamanan.id_keamanan = keamanankos.id_keamanan
    WHERE keamanankos.id_keamanankos = ?
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
                                <input readonly type="text" class="form-control" name="id_keamanankos" value="<?php echo htmlspecialchars($view_kelas['id_keamanankos']); ?>" />
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
                                <label>Keamanan</label>
                                <select class="form-control" name="keamanan">
                                    <?php
                                    $tampil = $conn->query("SELECT * FROM keamanan ORDER BY id_keamanan");
                                    while ($w = $tampil->fetch_assoc()) {
                                        $selected = ($view_kelas['id_keamanan'] == $w['id_keamanan']) ? 'selected' : '';
                                        echo "<option value='{$w['id_keamanan']}' $selected>{$w['keamanannya']}-{$w['id_keamanan']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <input class="btn btn-success" type="submit" value="Simpan" />
                                <a class="btn btn-warning" href="keamanan.php">Kembali</a>
                            </div>
                        </form>

                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            // Sanitize form data
                            $id_keamanankos = intval($_POST['id_keamanankos']);
                            $pemilik = intval($_POST['pemilik']);
                            $keamanan = intval($_POST['keamanan']);

                            if (empty($id_keamanankos) || empty($pemilik) || empty($keamanan)) {
                                echo "<script>alert('Harap semua data diisi!');</script>";
                                echo "<script>window.history.back();</script>";
                                exit();
                            }

                            // Prepare and execute update query
                            $update = $conn->prepare("UPDATE keamanankos SET id_keamanan = ?, id_kamar = ? WHERE id_keamanankos = ?");
                            $update->bind_param("iii", $keamanan, $pemilik, $id_keamanankos);
                            $update->execute();

                            echo '<script>alert("Data Berhasil Diubah!");</script>';
                            echo '<meta http-equiv="refresh" content="1; url=keamanan.php" />';
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

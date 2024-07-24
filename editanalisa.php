<?php
ini_set("display_errors", "Off");
include "header.php";
include("connect.php");

// Sanitize input
$id = intval($_GET['idk']); // Ensure ID is an integer

// Fetch data
$sql = $conn->prepare("SELECT * FROM analisa WHERE id_analisa = ?");
$sql->bind_param("i", $id);
$sql->execute();
$view_kelas = $sql->get_result()->fetch_assoc();
?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Ubah Data Analisa Topsis </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Id Kriteria</label>
                                <input readonly type="text" class="form-control" name="id_kriteria" value="<?php echo htmlspecialchars($view_kelas['id_analisa']); ?>" />
                            </div>
                            <div class="form-group">
                                <label>Nama Kos</label>
                                <select class="form-control" name="nama_kos">
                                    <?php
                                    $tampil = $conn->query("SELECT * FROM pemilik ORDER BY id_pemilik");
                                    while ($w = $tampil->fetch_assoc()) {
                                        $selected = ($view_kelas['id_pemilik'] == $w['id_pemilik']) ? 'selected' : '';
                                        echo "<option value='" . htmlspecialchars($w['id_pemilik']) . "' $selected>" . htmlspecialchars($w['nama_kos']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nama Kriteria</label>
                                <select class="form-control" name="kriteria">
                                    <?php
                                    $tampil = $conn->query("SELECT * FROM kriteria ORDER BY id_kriteria");
                                    while ($w = $tampil->fetch_assoc()) {
                                        $selected = ($view_kelas['id_kriteria'] == $w['id_kriteria']) ? 'selected' : '';
                                        echo "<option value='" . htmlspecialchars($w['id_kriteria']) . "' $selected>" . htmlspecialchars($w['nama_kriteria']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nilainya</label>
                                <select class="form-control" name="nilai">
                                    <?php
                                    $nilaiOptions = [1, 2, 3, 4, 5];
                                    foreach ($nilaiOptions as $option) {
                                        $selected = ($view_kelas['nilainya'] == $option) ? 'selected' : '';
                                        echo "<option value='$option' $selected>$option</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <input class="btn btn-success" type="submit" name="submit" value="Simpan" />
                                <a class="btn btn-warning" href="analisa.php">Kembali</a>
                            </div>
                        </form>

                        <?php
                        if (isset($_POST['submit'])) {
                            $id_kriteria = intval($_POST['id_kriteria']);
                            $nama_kos = intval($_POST['nama_kos']);
                            $kriteria = intval($_POST['kriteria']);
                            $nilai = intval($_POST['nilai']);

                            if (!$nama_kos || !$kriteria || !$nilai) {
                                echo "<script>alert('Harap semua data diisi...!!');</script>";
                            } else {
                                $update_sql = $conn->prepare("UPDATE analisa SET id_kriteria = ?, id_pemilik = ?, nilainya = ? WHERE id_analisa = ?");
                                $update_sql->bind_param("iiii", $kriteria, $nama_kos, $nilai, $id_kriteria);

                                if ($update_sql->execute()) {
                                    echo '<script>alert("Data Berhasil Diubah!");</script>';
                                    echo '<meta http-equiv="refresh" content="1; url=analisa.php" />';
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

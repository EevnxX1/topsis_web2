<?php
ini_set("display_errors", "Off");
include "header.php";
include "menu.php";
include("connect.php");

// Ensure GET parameter is safe
$id = intval($_GET['idk']);

// Prepare and execute the query
$sql = $conn->prepare("SELECT * FROM kriteria WHERE id_kriteria = ?");
$sql->bind_param("i", $id);
$sql->execute();
$result = $sql->get_result();
$view_kelas = $result->fetch_assoc();
?>

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">EDIT KRITERIA</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Edit Data Kriteria</li>
                </ol>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-block">
                        <form action="" method="post">
                            <div class="form-group">
                                <label>Id Kriteria</label>
                                <input readonly type="text" class="form-control" name="id_kriteria" value="<?php echo htmlspecialchars($view_kelas['id_kriteria']); ?>" />
                            </div>
                            <div class="form-group">
                                <label>Nama Kriteria</label>
                                <input type="text" class="form-control" name="nama_kriteria" value="<?php echo htmlspecialchars($view_kelas['nama_kriteria']); ?>" />
                            </div>
                            <div class="form-group">
                                <label>Atribut</label>
                                <select class="form-control" name="atribut">
                                    <option value="benefit" <?php echo ($view_kelas['atribut'] == 'benefit') ? 'selected' : ''; ?>>benefit</option>
                                    <option value="cost" <?php echo ($view_kelas['atribut'] == 'cost') ? 'selected' : ''; ?>>cost</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nilai</label>
                                <select class="form-control" name="nilai">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php echo ($view_kelas['bobot_nilai'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <input class="btn btn-success" type="submit" value="Simpan" />
                                <a class="btn btn-warning" href="kriteria.php">Kembali</a>
                            </div>
                        </form>

                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $id_kriteria = intval($_POST['id_kriteria']);
                            $nama_kriteria = $_POST['nama_kriteria'];
                            $atribut = $_POST['atribut'];
                            $nilai = intval($_POST['nilai']);

                            if (empty($nama_kriteria) || empty($atribut) || empty($nilai)) {
                                echo "<script>alert('Harap semua data diisi!');</script>";
                                echo "<script>window.history.back();</script>";
                                exit();
                            }

                            // Prepare and execute the update query
                            $update = $conn->prepare("UPDATE kriteria SET atribut = ?, bobot_nilai = ?, nama_kriteria = ? WHERE id_kriteria = ?");
                            $update->bind_param("sisi", $atribut, $nilai, $nama_kriteria, $id_kriteria);
                            $update->execute();

                            echo '<script>alert("Data Berhasil Diubah!");</script>';
                            echo '<meta http-equiv="refresh" content="1; url=kriteria.php" />';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>

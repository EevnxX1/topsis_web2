<?php
ini_set("display_errors", "Off");
include "header.php";
include "menu.php";
include("connect.php");

// Ensure GET parameter is safe
$id = intval($_GET['idk']);

// Prepare and execute the query
$sql = $conn->prepare("SELECT * FROM pemilik WHERE id_pemilik = ?");
$sql->bind_param("i", $id);
$sql->execute();
$result = $sql->get_result();
$view_kelas = $result->fetch_assoc();
?>

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">UBAH PEMILIK KOS</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Ubah Pemilik Kos</li>
                </ol>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-block">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Id Pemilik</label>
                                <input readonly type="text" class="form-control" name="id_pemilik" value="<?php echo htmlspecialchars($view_kelas['id_pemilik']); ?>" />
                            </div>
                            <div class="form-group">
                                <label>Nama Pemilik</label>
                                <input type="text" class="form-control" name="nama_pemilik" value="<?php echo htmlspecialchars($view_kelas['nama']); ?>" />
                            </div>
                            <div class="form-group">
                                <label>Nama Kos</label>
                                <input type="text" class="form-control" name="nama_kos" value="<?php echo htmlspecialchars($view_kelas['nama_kos']); ?>" />
                            </div>
                            <div class="form-group">
                                <label>Nomor Telepon Pemilik</label>
                                <input type="text" class="form-control" name="telepon" value="<?php echo htmlspecialchars($view_kelas['telepon']); ?>" />
                            </div>
                            <div class="form-group">
                                <label>Alamat Pemilik</label>
                                <textarea class="form-control" name="alamat" rows="5" cols="50"><?php echo htmlspecialchars($view_kelas['alamat']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Foto Pemilik</label>
                                <input type="file" name="fupload">
                                <p>
                                    <?php 
                                    if (empty($view_kelas['foto'])) {
                                        echo '<img src="images/user.png" width="80">'; 
                                    } else {
                                        echo '<img src="images/pemilik/' . htmlspecialchars($view_kelas['foto']) . '" width="80">'; 
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="form-group">
                                <input class="btn btn-success" type="submit" value="Simpan" />
                                <a class="btn btn-warning" href="pemilik.php">Kembali</a>
                            </div>
                        </form>

                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $nama = $_POST['nama_pemilik'];
                            $namakos = $_POST['nama_kos'];
                            $telepon = $_POST['telepon'];
                            $alamat = $_POST['alamat'];

                            $lokasi_file = $_FILES['fupload']['tmp_name'];
                            $nama_file = $_FILES['fupload']['name'];

                            if (empty($nama) || empty($namakos) || empty($telepon)) {
                                echo "<script>alert('Harap semua data diisi!');</script>";
                                echo "<script>window.history.back();</script>";
                                exit();
                            }

                            // If a new file is uploaded, move it to the appropriate directory
                            if (!empty($nama_file)) {
                                move_uploaded_file($lokasi_file, "images/pemilik/$nama_file");
                                $foto = $nama_file;
                            } else {
                                // Retain existing photo if no new file is uploaded
                                $foto = $view_kelas['foto'];
                            }

                            // Prepare and execute the update query
                            $update = $conn->prepare("UPDATE pemilik SET nama = ?, nama_kos = ?, telepon = ?, alamat = ?, foto = ? WHERE id_pemilik = ?");
                            $update->bind_param("sssssi", $nama, $namakos, $telepon, $alamat, $foto, $id);
                            $update->execute();

                            echo '<script>alert("Data Berhasil Diubah!");</script>';
                            echo '<meta http-equiv="refresh" content="1; url=pemilik.php" />';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

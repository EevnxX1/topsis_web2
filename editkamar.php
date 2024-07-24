<?php
ini_set("display_errors", "Off");
include "header.php";
include "connect.php";

// Sanitize input
$id = intval($_GET['idk']);

// Prepare and execute query
$sql = $conn->prepare("SELECT * FROM kamar WHERE id_kamar = ?");
$sql->bind_param("i", $id);
$sql->execute();
$result = $sql->get_result();
$view_kelas = $result->fetch_assoc();
?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Ubah Data Kamar Kos</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Nama Pemilik</label>
                                <select class="form-control" name="pemilik">
                                    <?php
                                    $tampil = $conn->query("SELECT * FROM pemilik ORDER BY id_pemilik");
                                    while ($w = $tampil->fetch_assoc()) {
                                        $selected = ($view_kelas['id_pemilik'] == $w['id_pemilik']) ? 'selected' : '';
                                        echo "<option value='{$w['id_pemilik']}' $selected>{$w['nama']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Jenis Hunian Kos</label>
                                <select class="form-control" name="tipe">
                                    <?php
                                    $options = ['putra' => 'Kos Putra', 'putri' => 'Kos Putri', 'campur' => 'Kos Campur'];
                                    foreach ($options as $value => $label) {
                                        $selected = ($view_kelas['tipe'] == $value) ? 'selected' : '';
                                        echo "<option value='$value' $selected>$label</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Deskripsi Kamar</label>
                                <textarea class="form-control" name="deskripsi" rows="5"><?php echo htmlspecialchars($view_kelas['deskripsi']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Fasilitas Kamar</label>
                                <ul class="fasilitas">
                                    <?php
                                    $fasilitas_kost = $conn->query("SELECT * FROM fasilitaskos WHERE id_kamar = $id");
                                    $check = [];
                                    while ($r_fasilitas_kost = $fasilitas_kost->fetch_assoc()) {
                                        $check[] = $r_fasilitas_kost['id_fasilitas'];
                                    }
                                    $q_fasilitas = $conn->query("SELECT * FROM fasilitas");
                                    while ($r_fasilitas = $q_fasilitas->fetch_assoc()) {
                                        $checked = in_array($r_fasilitas['id_fasilitas'], $check) ? 'checked' : '';
                                        echo "<li><input type='checkbox' name='fasilitas[]' value='{$r_fasilitas['id_fasilitas']}' $checked/>";
                                        echo "<span>{$r_fasilitas['nama_fasilitas']}</span></li>";
                                    }
                                    ?>
                                </ul>
                            </div>
                            <div class="form-group">
                                <label>Harga kamar</label>
                                <input type="text" class="form-control" name="harga" value="<?php echo htmlspecialchars($view_kelas['harga']); ?>" />
                            </div>
                            <div class="form-group">
                                <label>Type Harga kamar</label>
                                <select name="type" class="form-control">
                                    <option value="bulan" <?php if ($view_kelas['tipeharga'] == 'bulan') echo 'selected'; ?>>Bulan</option>
                                    <option value="3bulan" <?php if ($view_kelas['tipeharga'] == '3bulan') echo 'selected'; ?>>3 Bulan</option>
                                    <option value="6bulan" <?php if ($view_kelas['tipeharga'] == '6bulan') echo 'selected'; ?>>6 Bulan</option>
                                    <option value="tahun" <?php if ($view_kelas['tipeharga'] == 'tahun') echo 'selected'; ?>>Tahun</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="fupload1">Gambar 1</label>
                                <input type="file" name="fupload1">
                                <p>
                                    <?php
                                    $image1 = $view_kelas['gambar1'] ? 'images/kamar/' . htmlspecialchars($view_kelas['gambar1']) : 'images/photo_not_available.png';
                                    echo "<img src='$image1' width='80'>";
                                    ?>
                                </p>
                            </div>
                            <div class="form-group">
                                <label for="fupload2">Gambar 2</label>
                                <input type="file" name="fupload2">
                                <p>
                                    <?php
                                    $image2 = $view_kelas['gambar2'] ? 'images/kamar/' . htmlspecialchars($view_kelas['gambar2']) : 'images/photo_not_available.png';
                                    echo "<img src='$image2' width='80'>";
                                    ?>
                                </p>
                            </div>
                            <div class="form-group">
                                <label for="fupload3">Gambar 3</label>
                                <input type="file" name="fupload3">
                                <p>
                                    <?php
                                    $image3 = $view_kelas['gambar3'] ? 'images/kamar/' . htmlspecialchars($view_kelas['gambar3']) : 'images/photo_not_available.png';
                                    echo "<img src='$image3' width='80'>";
                                    ?>
                                </p>
                            </div>
                            <div class="form-group">
                                <label for="fupload4">Gambar 4</label>
                                <input type="file" name="fupload4">
                                <p>
                                    <?php
                                    $image4 = $view_kelas['gambar4'] ? 'images/kamar/' . htmlspecialchars($view_kelas['gambar4']) : 'images/photo_not_available.png';
                                    echo "<img src='$image4' width='80'>";
                                    ?>
                                </p>
                            </div>
                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" value="Simpan" />
                                <a class="btn btn-warning" href="kamar.php">Kembali</a>
                            </div>
                        </form>

                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            // Sanitize form data
                            $pemilik = intval($_POST['pemilik']);
                            $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
                            $harga = $conn->real_escape_string($_POST['harga']);
                            $tipe = $conn->real_escape_string($_POST['tipe']);
                            $type = $conn->real_escape_string($_POST['type']);
                            $fasilitas = isset($_POST['fasilitas']) ? array_map('intval', $_POST['fasilitas']) : [];

                            $upload_dir = 'images/kamar/';
                            $uploaded_files = [];
                            $file_fields = ['fupload1', 'fupload2', 'fupload3', 'fupload4'];

                            foreach ($file_fields as $field) {
                                if (!empty($_FILES[$field]['name'])) {
                                    $file_name = basename($_FILES[$field]['name']);
                                    $target_file = $upload_dir . $file_name;
                                    if (move_uploaded_file($_FILES[$field]['tmp_name'], $target_file)) {
                                        $uploaded_files[$field] = $file_name;
                                    }
                                } else {
                                    $uploaded_files[$field] = $view_kelas[str_replace('fupload', 'gambar', $field)];
                                }
                            }

                            // Update query
                            $stmt = $conn->prepare("UPDATE kamar SET id_pemilik = ?, tipe = ?, deskripsi = ?, harga = ?, tipeharga = ?, gambar1 = ?, gambar2 = ?, gambar3 = ?, gambar4 = ? WHERE id_kamar = ?");
                            $stmt->bind_param("issssssssi", $pemilik, $tipe, $deskripsi, $harga, $type, $uploaded_files['fupload1'], $uploaded_files['fupload2'], $uploaded_files['fupload3'], $uploaded_files['fupload4'], $id);
                            $stmt->execute();

                            // Update fasilitas
                            $conn->query("DELETE FROM fasilitaskos WHERE id_kamar = $id");
                            if (!empty($fasilitas)) {
                                foreach ($fasilitas as $fasilitas_id) {
                                    $conn->query("INSERT INTO fasilitaskos (id_kamar, id_fasilitas) VALUES ($id, $fasilitas_id)");
                                }
                            }

                            echo '<script>alert("Data Berhasil Diubah!"); window.location.href = "kamar.php";</script>';
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

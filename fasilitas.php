<?php
ini_set("display_errors", "Off");
include "header.php";
include "menu.php";
include("../connect.php");

// Ensure POST method is used
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $pemilik = intval($_POST['pemilik']);
    $deskripsi = $_POST['deskripsi'];
    $fasilitas = $_POST['fasilitas'];
    $status = $_POST['status'];
    $harga = $_POST['harga'];

    if (empty($pemilik) || empty($deskripsi) || empty($harga)) {
        echo "<script>alert('Harap semua data diisi!');</script>";
        echo "<script>window.history.back();</script>";
        exit();
    }

    // Prepare and execute the insert query
    $stmt = $conn->prepare("INSERT INTO fasilitas (id_pemilik, deskripsi, fasilitasnya, status, harga) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $pemilik, $deskripsi, $fasilitas, $status, $harga);
    $stmt->execute();
    $stmt->close();
}
?>

<div id="content">
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
        <tr valign="top">
            <td width="75%" style="padding-right:20px;">
                <div id="body">
                    <div class="title">Master Kamar</div>
                    <div class="body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <table>
                                <tr>
                                    <td><b>Nama Pemilik Kos</b>
                                        <div class="desc">Pilih pemilik Kos</div>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <select name="pemilik" class="form-control">
                                            <option value="0" selected>- Pilih Pemilik Kos -</option>
                                            <?php
                                            $result = $conn->query("SELECT * FROM pemilik ORDER BY id_pemilik");
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<option value='" . htmlspecialchars($row['id_pemilik']) . "'>" . htmlspecialchars($row['nama']) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Deskripsi Kamar Kos</b>
                                        <div class="desc">Masukkan deskripsi Kamar Kos</div>
                                    </td>
                                    <td>:</td>
                                    <td><textarea name="deskripsi" rows="5" cols="50" class="form-control"></textarea></td>
                                </tr>
                                <tr>
                                    <td><b>Fasilitas Kamar Kos</b>
                                        <div class="desc">Masukkan Fasilitas Kamar Kos</div>
                                    </td>
                                    <td>:</td>
                                    <td><textarea name="fasilitas" rows="5" cols="50" class="form-control"></textarea></td>
                                </tr>
                                <tr>
                                    <td><b>Status Kamar</b>
                                        <div class="desc">Masukan Status Kamar</div>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <select class="form-control" name="status" id="status">
                                            <option value="kosong">Kamar Tersedia</option>
                                            <option value="booking">Kamar Booking</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Harga Kamar</b>
                                        <div class="desc">Masukan Harga Kamar</div>
                                    </td>
                                    <td>:</td>
                                    <td><input type="text" name="harga" class="form-control" required /></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td><input type="submit" value="Simpan" class="btn btn-success" /></td>
                                </tr>
                            </table>
                        </form>

                        <table class="table" width="100%">
                            <tr class="th">
                                <th>No.</th>
                                <th>Nama Pemilik</th>
                                <th>Deskripsi</th>
                                <th>Fasilitas</th>
                                <th>Status</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>

                            <?php
                            $result = $conn->query("SELECT * FROM pemilik JOIN fasilitas ON pemilik.id_pemilik = fasilitas.id_pemilik ORDER BY fasilitas.id_fasilitas DESC");

                            $no = 1;

                            while ($row = $result->fetch_assoc()) {
                                $harga = number_format($row['harga'], 2, ",", ".");
                                ?>

                                <tr class='td' bgcolor='#FFF'>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                                    <td><?php echo htmlspecialchars($row['fasilitasnya']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td><?php echo $harga; ?></td>
                                    <td>
                                        <?php
                                        // Assuming $pilih_user['status'] is retrieved somewhere
                                        if ($pilih_user['status'] === 'admin') {
                                            echo "<a href='editpemilik.php?idk=" . htmlspecialchars($row['id_pemilik']) . "'><img src='images/edit.png' alt='Edit'></a>";
                                            echo "<a href='javascript:KonfirmasiHapus(\"deletepemilik.php?idk=" . htmlspecialchars($row['id_pemilik']) . "\")'><img src='images/delete.png' alt='Delete'></a>";
                                        }
                                        ?>
                                    </td>
                                </tr>

                                <?php
                                $no++;
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>

<?php
include "footer.php";
?>

<?php
ini_set("display_errors", "Off");
include("connect.php");
include "header.php";

?>

<style>
.portfolio-item {
    margin-bottom: 30px;
}
</style>

<div class="container">
    <!-- Page Heading -->
    <h1 class="my-4">HASIL PENCARIAN</h1>

    <div class="row">
        <?php
        $options = array();

        if (!empty($_POST['keamanan'])) {
            $options[] = 'keamanankos.id_keamanan = "' . mysqli_real_escape_string($conn, $_POST['keamanan']) . '"';
        }

        if (!empty($_POST['kenyamanan'])) {
            $options[] = 'kenyamanankos.id_kenyamanan = "' . mysqli_real_escape_string($conn, $_POST['kenyamanan']) . '"';
        }

        if (!empty($_POST['kebersihan'])) {
            $options[] = 'kebersihankos.id_kebersihan = "' . mysqli_real_escape_string($conn, $_POST['kebersihan']) . '"';
        }

        if (!empty($_POST['nama'])) {
            $options[] = 'pemilik.nama_kos LIKE "%' . mysqli_real_escape_string($conn, $_POST['nama']) . '%"';
        }

        if (!empty($_POST['jenis'])) {
            $jenis = implode(',', array_map('mysqli_real_escape_string', $_POST['jenis']));
            $options[] = "kamar.tipe IN ('" . str_replace(",", "', '", $jenis) . "')";
        }

        if (!empty($_POST['fasilitas'])) {
            $in_fasilitas = implode(',', array_map('intval', $_POST['fasilitas']));
            $options[] = 'fasilitaskos.id_fasilitas IN (' . $in_fasilitas . ')';
        }

        if (!empty($_POST['type_sewa']) && !empty($_POST['harga_sewa']) && $_POST['type_sewa'] != '0' && $_POST['harga_sewa'] != '0') {
            $harga = explode('-', $_POST['harga_sewa']);
            $harga_min = intval($harga[0]);
            $harga_max = isset($harga[1]) ? intval($harga[1]) : null;
            $type_sewa = mysqli_real_escape_string($conn, $_POST['type_sewa']);

            $options[] = 'kamar.harga >= ' . $harga_min . ' AND kamar.tipeharga = "' . $type_sewa . '"' .
                ($harga_max ? ' AND kamar.harga <= ' . $harga_max : '');
        }

        $conditions = !empty($options) ? implode(' AND ', $options) : '1';

        $query = "
            SELECT DISTINCT pemilik.nama_kos, kamar.*,
                (SELECT kamar.status FROM kamar ORDER BY kamar.status DESC LIMIT 1) AS status,
                (SELECT kamar.harga FROM kamar ORDER BY kamar.tipeharga DESC LIMIT 1) AS harga,
                (SELECT kamar.tipeharga FROM kamar ORDER BY kamar.tipeharga DESC LIMIT 1) AS tipeharga,
                (SELECT COUNT(status) FROM kamar WHERE kamar.status= 'y') AS total_status_y,
                (SELECT COUNT(tipe) FROM kamar WHERE kamar.tipe = 'putra') AS total_putra,
                (SELECT COUNT(tipe) FROM kamar WHERE kamar.tipe = 'putri') AS total_putri,
                (SELECT COUNT(tipe) FROM kamar WHERE kamar.tipe = 'campur') AS total_campur
            FROM kamar
            LEFT JOIN pemilik ON kamar.id_pemilik = pemilik.id_pemilik
            LEFT JOIN keamanankos ON kamar.id_kamar = keamanankos.id_kamar
            LEFT JOIN kenyamanankos ON kamar.id_kamar = kenyamanankos.id_kamar
            LEFT JOIN kebersihankos ON kamar.id_kamar = kebersihankos.id_kamar
            LEFT JOIN fasilitaskos ON kamar.id_kamar = fasilitaskos.id_kamar
            WHERE " . $conditions;

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $kolom = 3;
            $i = 0;

            while ($data = mysqli_fetch_assoc($result)) {
                if ($i >= $kolom) {
                    echo "</div><div class='row'>";
                    $i = 0;
                }
                $i++;
                $harga = number_format($data['harga'], 2, ",", ".");
        ?>

        <div class="col-lg-4 col-sm-6 portfolio-item">
            <div class="card h-100">
                <a href="detailkamar.php?idk=<?php echo $data['id_kamar']; ?>">
                    <img class="card-img-top" src="images/kamar/<?php echo htmlspecialchars($data['gambar1']); ?>" alt="">
                </a>
                <div class="card-body">
                    <h4 class="card-title">
                        <a href="detailkamar.php?idk=<?php echo $data['id_kamar']; ?>">
                            <?php echo htmlspecialchars($data['nama_kos']); ?>
                        </a>
                    </h4>
                    <div>
                        <?php
                        if ($data['tipe'] == 'putra') {
                            echo '<img src="images/avatar-male2.png" alt="male" style="width:20px; height:20px;"> Putra';
                        } elseif ($data['tipe'] == 'putri') {
                            echo '<img src="images/avatar-female.png" alt="female" style="width:20px; height:20px;"> Putri';
                        } else {
                            echo '<img src="images/avatar-male2.png" alt="male" style="width:20px; height:20px;"> <img src="images/avatar-female.png" alt="female" style="width:20px; height:20px;"> Campur';
                        }
                        ?>
                    </div>
                    <div>
                        <span>Rp. <?php echo $harga; ?>/ bulan</span>
                    </div>
                    <br>
                    <p class="card-text"><?php echo htmlspecialchars(substr($data['deskripsi'], 0, 80)); ?>...</p>
                </div>
            </div>
        </div>

        <?php
            }
        } else {
            echo '
            <div class="col-lg-12 text-center">
                <h3><strong>Maaf!</strong> Pencarian Anda Tidak Bisa Kami Temukan.</h3>
                <p align="center"><img src="images/error-icon-notebook-design-template-website-white-background-graphic-98813130.jpg"></p>
            </div>';
        }
        ?>
    </div>
    <!-- /.row -->
</div>

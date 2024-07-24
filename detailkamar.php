<?php
ini_set("display_errors", "Off");
include("connect.php");
include "header.php";

// Validate and sanitize the input
$id = isset($_GET['idk']) ? intval($_GET['idk']) : 0;

if ($id > 0) {
    // Prepare the SQL query
    $stmt = $conn->prepare("
        SELECT pemilik.*, kamar.*, kamar.harga AS harga
        FROM pemilik
        JOIN kamar ON pemilik.id_pemilik = kamar.id_pemilik
        WHERE kamar.id_kamar = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    $nominal = $data['harga'];
    $harga = number_format($nominal, 2, ",", ".");

    // Prepare the facilities query
    $stmt_fasilitas = $conn->prepare("
        SELECT fasilitas.nama_fasilitas
        FROM fasilitas
        JOIN fasilitaskos ON fasilitas.id_fasilitas = fasilitaskos.id_fasilitas
        WHERE fasilitaskos.id_kamar = ?
    ");
    $stmt_fasilitas->bind_param("i", $id);
    $stmt_fasilitas->execute();
    $fasilitas_result = $stmt_fasilitas->get_result();
    $fasilitas_list = [];
    while ($fasilitas = $fasilitas_result->fetch_assoc()) {
        $fasilitas_list[] = htmlspecialchars($fasilitas['nama_fasilitas']);
    }
    $stmt_fasilitas->close();

    // Prepare the analysis query
    $stmt_analisa = $conn->prepare("
        SELECT kriteria.nama_kriteria, analisa.nilainya
        FROM analisa
        JOIN kriteria ON analisa.id_kriteria = kriteria.id_kriteria
        WHERE analisa.id_pemilik = (SELECT id_pemilik FROM kamar WHERE id_kamar = ?)
    ");
    $stmt_analisa->bind_param("i", $id);
    $stmt_analisa->execute();
    $analisa_result = $stmt_analisa->get_result();
    $analisa_list = [];
    while ($bobot = $analisa_result->fetch_assoc()) {
        $rating = '';
        switch ($bobot['nilainya']) {
            case 1:
                $rating = '<b>Sangat Buruk</b>';
                break;
            case 2:
                $rating = '<b>Buruk</b>';
                break;
            case 3:
                $rating = '<b>Cukup</b>';
                break;
            case 4:
                $rating = '<b>Baik</b>';
                break;
            case 5:
                $rating = '<b>Sangat Baik</b>';
                break;
        }
        $analisa_list[] = htmlspecialchars($bobot['nama_kriteria']) . ' : ' . $rating;
    }
    $stmt_analisa->close();
} else {
    echo "Invalid ID.";
    exit();
}
?>

<style type="text/css">
.gallery-wrap .img-big-wrap img {
    height: 450px;
    width: 458px;
    display: inline-block;
    cursor: zoom-in;
}

.gallery-wrap .img-small-wrap .item-gallery {
    width: 60px;
    height: 40px;
    border: 1px solid #ddd;
    margin: 7px 2px;
    display: inline-block;
    overflow: hidden;
}

.gallery-wrap .img-small-wrap {
    text-align: center;
}
.gallery-wrap .img-small-wrap img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
    border-radius: 4px;
    cursor: zoom-in;
}
</style>

<link href="css/bootstrap.min.css" rel="stylesheet" />
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<div class="container">
    <br>
    <div class="card">
        <div class="row">
            <aside class="col-sm-5 border-right">
                <article class="gallery-wrap">
                    <div class="img-big-wrap">
                        <div> 
                            <a href="#"><img src="images/kamar/<?php echo htmlspecialchars($data['gambar1']); ?>"></a>
                        </div>
                        <hr>
                        <dl class="param param-feature">
                            <dt>Fasilitas Kos</dt>
                            <dd>
                                <?php echo implode(', ', $fasilitas_list); ?>
                            </dd>
                        </dl>
                        <hr>
                        <dl class="param param-feature">
                            <dt>Nilai Bobot Kriteria</dt>
                            <dd>
                                <p>
                                    <?php echo implode('<br>', $analisa_list); ?>
                                </p>
                            </dd>
                        </dl>
                    </div>
                </article>
            </aside>
            <aside class="col-sm-7">
                <article class="card-body p-5">
                    <h3 class="title mb-3"><?php echo htmlspecialchars($data['nama_kos']); ?></h3>
                    <p class="price-detail-wrap">
                        <span class="price h3 text-warning">
                            <span class="currency">Rp. <?php echo $harga; ?></span>
                        </span>
                        <span>/bulan</span>
                    </p>
                    <dl class="item-property">
                        <dt>Deskripsi</dt>
                        <dd><p><?php echo htmlspecialchars($data['deskripsi']); ?></p></dd>
                    </dl>
                    <dl class="param param-feature">
                        <dt>Alamat</dt>
                        <dd><?php echo htmlspecialchars($data['alamat']); ?></dd>
                    </dl>
                    <dl class="param param-feature">
                        <dt>Info Pemilik Kos</dt>
                        <dd><?php echo htmlspecialchars($data['nama']); ?></dd>
                    </dl>
                    <dl class="param param-feature">
                        <dt>No.Telepon</dt>
                        <dd><?php echo htmlspecialchars($data['telepon']); ?></dd>
                    </dl>
                    <dl class="param param-feature">
                        <dt>Hunian Kos Untuk</dt>
                        <dd>
                            <?php
                            if ($data['tipe'] == 'putra') {
                                echo '<img src="images/avatar-male2.png" alt="male" style="width:20px; height:20px;"> Putra';
                            } else if ($data['tipe'] == 'putri') {
                                echo '<img src="images/avatar-female.png" alt="female" style="width:20px; height:20px;"> Putri';
                            } else {
                                echo '<img src="images/avatar-male2.png" alt="male" style="width:20px; height:20px;"><img src="images/avatar-female.png" alt="female" style="width:20px; height:20px;"> Campur';
                            }
                            ?>
                        </dd>
                    </dl>
                    <hr>
                </article>
            </aside>
        </div>
    </div>
    <br><br><br>
    <div class="row">
        <?php for ($i = 1; $i <= 4; $i++): ?>
            <?php if (!empty($data["gambar$i"])): ?>
                <div class="col-lg-3 col-md-4 col-xs-6">
                    <a href="#" class="d-block mb-4 h-100">
                        <img class="img-fluid img-thumbnail" style="width:200px;height:200px" src="images/kamar/<?php echo htmlspecialchars($data["gambar$i"]); ?>" alt="">
                    </a>
                </div>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
    <div class="col-md-12 removePadding">
        <p><strong>Peta Google Maps</strong></p>
        <style>
            #map {
                height: 400px;
                width: 500px;
            }

            #floating-panel {
                position: absolute;
                top: 10px;
                left: 25%;
                z-index: 5;
                background-color: #fff;
                padding: 5px;
                border: 1px solid #999;
                text-align: center;
                font-family: 'Roboto', 'sans-serif';
                line-height: 30px;
                padding-left: 10px;
                display: none;
            }

            #right-panel {
                font-family: 'Roboto', 'sans-serif';
                line-height: 30px;
                padding-left: 10px;
            }

            #right-panel select, #right-panel input {
                font-size: 15px;
            }

            #right-panel select {
                width: 100%;
            }

            #right-panel i {
                font-size: 12px;
            }

            #right-panel {
                height: 100%;
                float: right;
                width: 390px;
            }

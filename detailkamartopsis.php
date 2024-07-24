<?php
ini_set("display_errors", "Off");
include("connect.php");
include "header.php";

// Sanitize input
$id = htmlspecialchars($_GET['idk']);

// Using mysqli instead of mysql (assumes you have a connection $conn)
$sql = $conn->prepare("SELECT * FROM pemilik, kamar WHERE pemilik.id_pemilik = kamar.id_pemilik AND kamar.id_kamar = ?");
$sql->bind_param("i", $id);
$sql->execute();
$data = $sql->get_result()->fetch_assoc();

$nominal = $data['harga'];
$harga = number_format($nominal, 2, ",", ".");
?>

<style type="text/css">
/* Your styles remain unchanged */
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
                        <div> <a href="#"><img src="images/kamar/<?php echo htmlspecialchars($data['gambar1']); ?>"></a></div>
                        <hr>

                        <dl class="param param-feature">
                            <dt>Fasilitas Kos</dt>
                            <dd>
                                <?php
                                $fasilitas = $conn->prepare("SELECT * FROM fasilitas, fasilitaskos WHERE fasilitas.id_fasilitas = fasilitaskos.id_fasilitas AND fasilitaskos.id_kamar = ?");
                                $fasilitas->bind_param("i", $id);
                                $fasilitas->execute();
                                $result = $fasilitas->get_result();
                                while ($r_fasilitas = $result->fetch_assoc()) {
                                    echo htmlspecialchars($r_fasilitas['nama_fasilitas']) . ', ';
                                }
                                ?>
                            </dd>
                        </dl>

                        <hr>

                        <dl class="param param-feature">
                            <dt>Nilai Bobot Kriteria</dt>
                            <dd>
                                <p>
                                    <?php
                                    $analisa = $conn->prepare("SELECT * 
                                                                FROM analisa
                                                                LEFT JOIN pemilik ON analisa.id_pemilik = pemilik.id_pemilik
                                                                LEFT JOIN kamar ON kamar.id_pemilik = pemilik.id_pemilik 
                                                                LEFT JOIN kriteria ON kriteria.id_kriteria = analisa.id_kriteria 
                                                                WHERE kamar.id_kamar = ?");
                                    $analisa->bind_param("i", $id);
                                    $analisa->execute();
                                    $result = $analisa->get_result();
                                    while ($bobot = $result->fetch_assoc()) {
                                        echo htmlspecialchars($bobot['nama_kriteria']) . ' : ';
                                        if ($bobot['nilainya'] == 1) {
                                            echo '<b>Sangat Buruk</b>';
                                        } else if ($bobot['nilainya'] == 2) {
                                            echo '<b>Buruk</b>';
                                        } else if ($bobot['nilainya'] == 3) {
                                            echo '<b>Cukup</b>';
                                        } else if ($bobot['nilainya'] == 4) {
                                            echo '<b>Baik</b>';
                                        } else {
                                            echo '<b>Sangat Baik</b>';
                                        }
                                        echo '<br>';
                                    }
                                    ?>
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
                        <dd><?php echo htmlspecialchars($data['nama']); ?> </dd>
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
                </article>
            </aside>
        </div>
    </div>

    <br><br><br>

    <div class="row">
        <div class="col-lg-3 col-md-4 col-xs-6">
            <a href="#" class="d-block mb-4 h-100">
                <img class="img-fluid img-thumbnail" style="width:200px;height:200px" src="images/kamar/<?php echo htmlspecialchars($data['gambar1']); ?>" alt="">
            </a>
        </div>
        <div class="col-lg-3 col-md-4 col-xs-6">
            <a href="#" class="d-block mb-4 h-100">
                <img class="img-fluid img-thumbnail" style="width:200px;height:200px" src="images/kamar/<?php echo htmlspecialchars($data['gambar2']); ?>" alt="">
            </a>
        </div>
        <div class="col-lg-3 col-md-4 col-xs-6">
            <a href="#" class="d-block mb-4 h-100">
                <img class="img-fluid img-thumbnail" style="width:200px;height:200px" src="images/kamar/<?php echo htmlspecialchars($data['gambar3']); ?>" alt="">
            </a>
        </div>
        <div class="col-lg-3 col-md-4 col-xs-6">
            <a href="#" class="d-block mb-4 h-100">
                <img class="img-fluid img-thumbnail" style="width:200px;height:200px" src="images/kamar/<?php echo htmlspecialchars($data['gambar4']); ?>" alt="">
            </a>
        </div>
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
            }

            #right-panel {
                font-family: 'Roboto', 'sans-serif';
                line-height: 30px;
                padding-left: 10px;
                height: 100%;
                float: right;
                width: 390px;
                overflow: auto;
            }
        </style>

        <div id="floating-panel">
            <p>CEK JARAK :</p>
            <strong>Start:</strong>
            <select id="start">
                <option value="-7.434842, 109.250192">Kampus</option>
            </select>
            <br>
            <strong>End:</strong>
            <select id="end">
                <option value="0">Pilih</option>
                <option value="<?php echo htmlspecialchars($data['latitude']); ?>, <?php echo htmlspecialchars($data['longtitude']); ?>">Hunian Kos</option>
            </select>
        </div>
        <div id="right-panel"></div>
        <div id="map"></div>

        <script>
            function initMap() {
                var directionsDisplay = new google.maps.DirectionsRenderer;

                var directionsService = new google.maps.DirectionsService;

                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 18,
                    center: {lat: <?php echo htmlspecialchars($data['latitude']); ?>, lng: <?php echo htmlspecialchars($data['longtitude']); ?>}
                });

                var marker = new google.maps.Marker({
                    position: {lat: <?php echo htmlspecialchars($data['latitude']); ?>, lng: <?php echo htmlspecialchars($data['longtitude']); ?>},
                    map: map
                });

                directionsDisplay.setMap(map);
                directionsDisplay.setPanel(document.getElementById('right-panel'));

                var control = document.getElementById('floating-panel');
                control.style.display = 'block';
                map.controls[google.maps.ControlPosition.TOP_CENTER].push(control);

                var onChangeHandler = function() {
                    calculateAndDisplayRoute(directionsService, directionsDisplay);
                };
                document.getElementById('start').addEventListener('change', onChangeHandler);
                document.getElementById('end').addEventListener('change', onChangeHandler);
            }

            function calculateAndDisplayRoute(directionsService, directionsDisplay) {
                var start = document.getElementById('start').value;
                var end = document.getElementById('end').value;
                directionsService.route({
                    origin: start,
                    destination: end,
                    travelMode: 'DRIVING'
                }, function(response, status) {
                    if (status === 'OK') {
                        directionsDisplay.setDirections(response);
                    } else {
                        window.alert('Directions request failed due to ' + status);
                    }
                });
            }
        </script>

        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap">
        </script>
    </div>
</div>

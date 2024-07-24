<?php
ini_set("display_errors", "Off");
include("connect.php");
include "header.php";
?>

<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.21.custom.min.js"></script>
<script type="text/javascript" src="js/validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.search-check').on('click', function(){
            $('.hidden').hide();
            if($(this).val() == 'ya'){
                $('.hidden').show();
            }
        });

        $('#type-sewa').on('change', function(){
            var option = '<option value="0">Semua Harga Sewa</option>';
            if($(this).val() == 'bulan'){
                option += '<option value="500000">Lebih dari Rp. 500.000</option>'
                        + '<option value="300000-500000">Rp. 300.000 - Rp. 500.000</option>'
                        + '<option value="250000-300000">Rp. 250.000 - Rp. 300.000</option>'
                        + '<option value="250000-200000">Rp. 200.000 - Rp. 250.000</option>';
            } else if($(this).val() == '3bulan'){
                option += '<option value="1000000-2500000">Rp. 1.000.000 - Rp. 2.500.000</option>'
                        + '<option value="2500000-5000000">Rp. 2.500.000 - Rp. 5.000.000</option>'
                        + '<option value="5000000">Lebih dari Rp. 5.000.000</option>';
            } else if($(this).val() == '6bulan'){
                option += '<option value="1000000-2500000">Rp. 1.000.000 - Rp. 2.500.000</option>'
                        + '<option value="2500000-5000000">Rp. 2.500.000 - Rp. 5.000.000</option>'
                        + '<option value="5000000">Lebih dari Rp. 5.000.000</option>';
            } else if($(this).val() == 'tahun'){
                option += '<option value="1500000-3000000">Rp. 1.500.000 - Rp. 3.000.000</option>'
                        + '<option value="3000000-5000000">Rp. 3.000.000 - Rp. 5.000.000</option>'
                        + '<option value="5000000-8000000">Rp. 5.000.000 - Rp. 8.000.000</option>'
                        + '<option value="8000000">Lebih dari Rp. 8.000.000</option>';
            }
            $('#harga-sewa').html(option);
        });
    });
</script>

<section class="second listingDetail ng-scope" ng-controller="ListingDetailController">
  <div class="container contentAllCK">
    <div class="row">
        <div class="col-md-12 col-md-offset-1 listing-wrapper">
            <h1 class="listingTitle"><p align="center">PENCARIAN HUNIAN KOS</p></h1>
            
            <div class="col-md-12 removePadding showDesktop">
                <div style="margin-bottom:20px;">
                    <form action="caricustom.php" method="post">

                        <div class="row">
                            <div class="col-sm-4">
                                <h3 class="panel-title" data-toggle="collapse" data-target="#panelOne" aria-expanded="true">Cari Nama Hunian Kos</h3>
                                <input type="text" name="nama" class="form-control" />
                                <br>
                                <h3 class="panel-title" data-toggle="collapse" data-target="#panelOne" aria-expanded="true">Cari Tipe Hunian Kos</h3>
                                <ul class="list-group">
                                    <?php
                                    $jenis_query = mysqli_query($conn, 'SELECT DISTINCT tipe FROM kamar ORDER BY tipe ASC');
                                    while ($r = mysqli_fetch_assoc($jenis_query)) {
                                        echo '<li class="list-group-item"><input type="checkbox" name="jenis[]" value="' . htmlspecialchars($r['tipe']) . '"/> <span> ' . htmlspecialchars($r['tipe']) . '</span></li>';
                                    }
                                    ?>
                                </ul>

                                <h3 class="panel-title" data-toggle="collapse" data-target="#panelOne" aria-expanded="true">Cari Keamanan</h3>
                                <select class='form-control' name='keamanan'>
                                    <option value=0 selected>- Pilih Keamanan -</option>
                                    <?php
                                    $keamanan_query = mysqli_query($conn, "SELECT * FROM keamanan ORDER BY id_keamanan DESC");
                                    while ($r = mysqli_fetch_assoc($keamanan_query)) {
                                        echo "<option value='{$r['id_keamanan']}'>" . htmlspecialchars($r['keamanannya']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <h3 class="panel-title" data-toggle="collapse" data-target="#panelOne" aria-expanded="true">Cari Harga Hunian Kos</h3>
                                <select class="form-control" id="type-sewa" name="type_sewa">
                                    <option value="0">Semua Jenis Sewa</option>
                                    <option value="bulan">Bulanan</option>
                                    <option value="3bulan">3 Bulanan</option>
                                    <option value="6bulan">6 Bulanan</option>
                                    <option value="tahun">Tahunan</option>
                                </select>
                                <br>
                                <select class="form-control" id="harga-sewa" name="harga_sewa">
                                    <option value="0">-- Pilih Harga Sewa --</option>
                                </select>
                                <br>
                                <h3 class="panel-title" data-toggle="collapse" data-target="#panelOne" aria-expanded="true">Cari Berdasarkan Jarak</h3>
                                <select class="form-control" name="jarak">
                                    <option value="0">Semua Jarak</option>
                                    <option value="1000">Lebih Dari 1 KM</option>
                                    <option value="500-1000">500M - 1KM</option>
                                    <option value="250-500">250M - 500M</option>
                                    <option value="50-250">50M - 250M</option>
                                </select>
                                <br>
                                <h3 class="panel-title" data-toggle="collapse" data-target="#panelOne" aria-expanded="true">Cari Kebersihan</h3>
                                <select class='form-control' name='kebersihan'>
                                    <option value=0 selected>- Pilih Kebersihan -</option>
                                    <?php
                                    $kebersihan_query = mysqli_query($conn, "SELECT * FROM kebersihan ORDER BY id_kebersihan DESC");
                                    while ($r = mysqli_fetch_assoc($kebersihan_query)) {
                                        echo "<option value='{$r['id_kebersihan']}'>" . htmlspecialchars($r['kebersihannya']) . "</option>";
                                    }
                                    ?>
                                </select>
                                <br>
                                <h3 class="panel-title" data-toggle="collapse" data-target="#panelOne" aria-expanded="true">Cari Kenyamanan</h3>
                                <select class='form-control' name='kenyamanan'>
                                    <option value=0 selected>- Pilih Kenyamanan -</option>
                                    <?php
                                    $kenyamanan_query = mysqli_query($conn, "SELECT * FROM kenyamanan ORDER BY id_kenyamanan DESC");
                                    while ($r = mysqli_fetch_assoc($kenyamanan_query)) {
                                        echo "<option value='{$r['id_kenyamanan']}'>" . htmlspecialchars($r['kenyamanannya']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <h3 class="panel-title" data-toggle="collapse" data-target="#panelOne" aria-expanded="true">Cari Fasilitas Hunian Kos</h3>
                                <ul class="list-group">
                                    <?php
                                    $fasilitas_query = mysqli_query($conn, 'SELECT * FROM fasilitas');
                                    while ($r_fasilitas = mysqli_fetch_assoc($fasilitas_query)) {
                                        echo '<li class="list-group-item"><input class="single-checkbox" type="checkbox" name="fasilitas[]" value="' . htmlspecialchars($r_fasilitas['id_fasilitas']) . '"/> <span> ' . htmlspecialchars($r_fasilitas['nama_fasilitas']) . '</span></li>';
                                    }
                                    ?>
                                </ul>
                            </div>

                            <input class="btn btn-primary" type="submit" value="Cari Data" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
  </div>
</section>

<?php
include "header.php";
include "menu.php";
ini_set("display_errors", "Off");
include("connect.php");

// Pastikan koneksi berhasil
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Menghindari penggunaan fungsi mysql_ dan menggantinya dengan mysqli_

function jml_kriteria($conn) {
    $sql = "SELECT COUNT(*) as count FROM kriteria";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

function get_kriteria($conn) {
    $kriteria = array();
    $sql = "SELECT nama_kriteria FROM kriteria";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $kriteria[] = $row['nama_kriteria'];
    }
    return $kriteria;
}

function jml_alternatif($conn) {
    $sql = "SELECT COUNT(DISTINCT id_pemilik) as count FROM analisa";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

function get_alt_name($conn) {
    $alternatif = array();
    $sql = "SELECT nama_kos FROM pemilik";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $alternatif[] = $row['nama_kos'];
    }
    return $alternatif;
}

function get_alternatif($conn) {
    $alternatifkriteria = array();
    $sql = "SELECT * FROM pemilik ORDER BY id_pemilik";
    $result = $conn->query($sql);
    $i = 0;
    while ($dataalternatif = $result->fetch_assoc()) {
        $id_pemilik = $dataalternatif['id_pemilik'];
        $sql_kriteria = "SELECT * FROM kriteria ORDER BY id_kriteria";
        $result_kriteria = $conn->query($sql_kriteria);
        $j = 0;
        while ($datakriteria = $result_kriteria->fetch_assoc()) {
            $id_kriteria = $datakriteria['id_kriteria'];
            $sql_alternatif_kriteria = "SELECT nilainya FROM analisa WHERE id_pemilik = '$id_pemilik' AND id_kriteria = '$id_kriteria'";
            $result_alternatif_kriteria = $conn->query($sql_alternatif_kriteria);
            $dataalternatifkriteria = $result_alternatif_kriteria->fetch_assoc();
            $alternatifkriteria[$i][$j] = $dataalternatifkriteria['nilainya'];
            $j++;
        }
        $i++;
    }
    return $alternatifkriteria;
}

function pembagi($alternatifkriteria) {
    $pembagi = array();
    for ($i = 0; $i < count($alternatifkriteria[0]); $i++) {
        $pembagi[$i] = 0;
        for ($j = 0; $j < count($alternatifkriteria); $j++) {
            $pembagi[$i] += pow($alternatifkriteria[$j][$i], 2);
        }
        $pembagi[$i] = sqrt($pembagi[$i]);
    }
    return $pembagi;
}

function get_kepentingan($conn) {
    $kepentingan = array();
    $sql = "SELECT bobot_nilai FROM kriteria";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $kepentingan[] = $row['bobot_nilai'];
    }
    return $kepentingan;
}

function get_costbenefit($conn) {
    $costbenefit = array();
    $sql = "SELECT atribut FROM kriteria";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $costbenefit[] = $row['atribut'];
    }
    return $costbenefit;
}

function cmp($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a > $b) ? -1 : 1;
}

function print_ar(array $x) {
    echo "<pre>";
    print_r($x);
    echo "</pre></br>";
}

// Mendapatkan data dari database
$k = jml_kriteria($conn);
$kri = get_kriteria($conn);
$a = jml_alternatif($conn);
$alt = get_alternatif($conn);
$alt_name = get_alt_name($conn);
$kep = get_kepentingan($conn);
$cb = get_costbenefit($conn);

?>

<div class="page-wrapper">
    <div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">PERHITUNGAN TOPSIS</h1>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#home">Matrix Ternormalisasi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#menu1">Matrix Normalisasi Terbobot</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#menu2">Solusi Ideal positif Negatif</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#menu3">Preferensi</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane container active" id="home">
                                <hr>
                                <h3 class="page-head-line">HASIL ANALISA</h3>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Alternatif / Kriteria</th>
                                                <?php
                                                foreach ($kri as $kriteria) {
                                                    echo "<th>" . ucwords($kriteria) . "</th>";
                                                }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($alt_name as $i => $name) {
                                                echo "<tr><td><b>" . ucwords($name) . "</b></td>";
                                                foreach ($kri as $j => $kriteria) {
                                                    echo "<td>" . $alt[$i][$j] . "</td>";
                                                }
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                                <hr>
                                <h3 class="page-head-line">PEMBAGI</h3>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <?php
                                                foreach ($kri as $kriteria) {
                                                    echo "<th>" . ucwords($kriteria) . "</th>";
                                                }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td><b>Pembagi</b></td>
                                                <?php
                                                $pembagi = pembagi($alt);
                                                foreach ($pembagi as $value) {
                                                    echo "<td>" . round($value, 4) . "</td>";
                                                }
                                                ?>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <hr>
                                <h3 class="page-head-line">MATRIX TERNORMALISASI</h3>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Alternatif / Kriteria</th>
                                                <?php
                                                foreach ($kri as $kriteria) {
                                                    echo "<th>" . ucwords($kriteria) . "</th>";
                                                }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $nor = array();
                                            foreach ($alt_name as $i => $name) {
                                                echo "<tr><td><b>" . ucwords($name) . "</b></td>";
                                                foreach ($kri as $j => $kriteria) {
                                                    $nor[$i][$j] = round(($alt[$i][$j] / $pembagi[$j]), 4);
                                                    echo "<td>" . $nor[$i][$j] . "</td>";
                                                }
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane container fade" id="menu1">
                                <hr>
                                <h3 class="page-head-line">MATRIX NORMALISASI TERBOBOT</h3>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Alternatif / Kriteria</th>
                                                <?php
                                                foreach ($kri as $kriteria) {
                                                    echo "<th>" . ucwords($kriteria) . "</th>";
                                                }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $bob = array();
                                            foreach ($alt_name as $i => $name) {
                                                echo "<tr><td><b>" . ucwords($name) . "</b></td>";
                                                foreach ($kri as $j => $kriteria) {
                                                    $bob[$i][$j] = round(($nor[$i][$j] * $kep[$j]), 4);
                                                    echo "<td>" . $bob[$i][$j] . "</td>";
                                                }
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane container fade" id="menu2">
                                <hr>
                                <h3 class="page-head-line">Min Max Berdasarkan Cost Benefit Kriteria</h3>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <?php
                                                foreach ($kri as $kriteria) {
                                                    echo "<th>" . ucwords($kriteria) . "</th>";
                                                }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td><b>A+</b></td>
                                                <?php
                                                $aplus = array();
                                                foreach ($kri as $i => $kriteria) {
                                                    $temp = array();
                                                    foreach ($alt_name as $j => $name) {
                                                        $temp[] = $bob[$j][$i];
                                                    }
                                                    $aplus[$i] = ($cb[$i] == 'benefit') ? max($temp) : min($temp);
                                                    echo "<td>" . $aplus[$i] . "</td>";
                                                }
                                                ?>
                                            </tr>

                                            <tr><td><b>A-</b></td>
                                                <?php
                                                $amin = array();
                                                foreach ($kri as $i => $kriteria) {
                                                    $temp = array();
                                                    foreach ($alt_name as $j => $name) {
                                                        $temp[] = $bob[$j][$i];
                                                    }
                                                    $amin[$i] = ($cb[$i] == 'benefit') ? min($temp) : max($temp);
                                                    echo "<td>" . $amin[$i] . "</td>";
                                                }
                                                ?>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr><th>#</th><th>D+</th><th>D-</th></tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $dplus = array();
                                            $dmin = array();
                                            foreach ($alt_name as $i => $name) {
                                                echo "<tr><td><b>" . ucwords($name) . "</b></td>";
                                                $dplus[$i] = 0;
                                                foreach ($kri as $j => $kriteria) {
                                                    $dplus[$i] += pow(($aplus[$j] - $bob[$i][$j]), 2);
                                                }
                                                $dplus[$i] = round(sqrt($dplus[$i]), 4);
                                                echo "<td>" . $dplus[$i] . "</td>";
                                                $dmin[$i] = 0;
                                                foreach ($kri as $j => $kriteria) {
                                                    $dmin[$i] += pow(($amin[$j] - $bob[$i][$j]), 2);
                                                }
                                                $dmin[$i] = round(sqrt($dmin[$i]), 4);
                                                echo "<td>" . $dmin[$i] . "</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane container fade" id="menu3">
                                <hr>
                                <h3 class="page-head-line">Preferensi</h3>
                                <hr>
                                <?php
                                echo "<table class='table table-striped table-bordered table-hover'>";
                                echo "<thead><tr><th></th><th>V</th></tr></thead>";
                                $v = array();
                                foreach ($alt_name as $i => $name) {
                                    $v[$i][0] = round(($dmin[$i] / ($dplus[$i] + $dmin[$i])), 4);
                                    $v[$i][1] = $name;
                                    echo "<tr><td><b>" . ucwords($name) . "</b></td><td>" . $v[$i][0] . "</td></tr>";
                                }
                                echo "</table><hr>";
                                usort($v, "cmp");
                                $hsl = array();
                                foreach ($v as $value) {
                                    $hsl[] = array($value[1], $value[0]);
                                }
                                echo "<b>Hasil Akhir Analisa</b></br>";
                                echo "Berikut ini hasil analisa diurutkan berdasarkan hasil nilai tertinggi. </br>Jadi dapat disimpulkan bahwa Alternatif terbaik adalah <b>" . ucwords(($hsl[0][0])) . "</b> dengan nilai <b>" . $hsl[0][1] . "</b>.";
                                echo "<table class='table table-striped table-bordered table-hover'>";
                                echo "<thead><tr><th>No.</th><th>Alternatif</th><th>Hasil Akhir</th></tr></thead>";
                                echo "<tbody>";
                                foreach ($hsl as $i => $item) {
                                    echo "<tr><td>" . ($i + 1) . ".</td><td>" . ucwords($item[0]) . "</td><td>" . $item[1] . "</td></tr>";
                                }
                                echo "</tbody></table><hr>";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

<?php
include 'footer.php';
$conn->close();
?>

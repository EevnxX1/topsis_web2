<?php
include("connect.php");
include "header.php";
include "menu.php";
include "function.php"; // Make sure this file is properly defined

// Ensure connect.php uses mysqli or PDO for a secure connection
?>

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Dashboard</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-block">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php
                                    // Ensure all necessary variables are initialized and valid
                                    if (!isset($alt, $k, $a, $alt_name, $kep, $cb)) {
                                        echo "<p>Error: Missing data for TOPSIS calculation.</p>";
                                    } else {
                                        // Calculations
                                        for ($i = 0; $i < $k; $i++) {
                                            $pembagi[$i] = 0;
                                            for ($j = 0; $j < $a; $j++) {
                                                $pembagi[$i] += pow($alt[$j][$i], 2);
                                            }
                                            $pembagi[$i] = round(sqrt($pembagi[$i]), 4);
                                        }

                                        for ($i = 0; $i < $a; $i++) {
                                            for ($j = 0; $j < $k; $j++) {
                                                $nor[$i][$j] = round(($alt[$i][$j] / $pembagi[$j]), 4);
                                            }
                                        }

                                        for ($i = 0; $i < $a; $i++) {
                                            for ($j = 0; $j < $k; $j++) {
                                                $bob[$i][$j] = round(($nor[$i][$j] * $kep[$j]), 4);
                                            }
                                        }

                                        for ($i = 0; $i < $k; $i++) {
                                            $temp = array();
                                            for ($j = 0; $j < $a; $j++) {
                                                $temp[$j] = $bob[$j][$i];
                                            }
                                            $aplus[$i] = ($cb[$i] === 'benefit') ? max($temp) : min($temp);
                                        }

                                        for ($i = 0; $i < $k; $i++) {
                                            $temp = array();
                                            for ($j = 0; $j < $a; $j++) {
                                                $temp[$j] = $bob[$j][$i];
                                            }
                                            $amin[$i] = ($cb[$i] === 'benefit') ? min($temp) : max($temp);
                                        }

                                        for ($i = 0; $i < $a; $i++) {
                                            $dplus[$i] = round(sqrt(array_sum(array_map(function($x, $y) { return pow(($x - $y), 2); }, $aplus, $bob[$i]))), 4);
                                            $dmin[$i] = round(sqrt(array_sum(array_map(function($x, $y) { return pow(($x - $y), 2); }, $amin, $bob[$i]))), 4);
                                        }

                                        // TOPSIS calculation
                                        $v = array();
                                        for ($i = 0; $i < $a; $i++) {
                                            $v[$i][0] = round(($dmin[$i] / ($dplus[$i] + $dmin[$i])), 4);
                                            $v[$i][1] = $alt_name[$i];
                                        }

                                        usort($v, "cmp");
                                        $hsl = array_map(function($item) { return array($item[1], $item[0]); }, $v);

                                        // Output results
                                        echo "<b>Hasil Akhir Analisa Metode Topsis Dengan Nilai bobot preferensi sebagai : W adalah = (5,4,3,3,2,2)</b></br>";
                                        echo "Jadi dapat disimpulkan bahwa Alternatif terbaik menggunakan metode topsis adalah <b>" . ucwords($hsl[0][0]) . "</b> dengan nilai Metode Topsis <b>" . $hsl[0][1] . "</b>.<br><br>";
                                        echo "<table id='dataTable' class='display' style='width:100%'>";
                                        echo "<thead><tr>
                                        <th>Rangking</th>
                                        <th>Hunian Kos</th>
                                        <th>Hasil Nilai Metode Topsis</th>
                                        <th>Lihat Kos</th>
                                        </tr></thead>";
                                        echo "<tbody>";
                                        foreach ($hsl as $index => $item) {
                                            echo "<tr>
                                            <td>" . ($index + 1) . ".</td>
                                            <td>" . ucwords($item[0]) . "</td>
                                            <td>" . $item[1] . "</td>
                                            <td><a href='detailkamartopsis.php?idk=" . urlencode($item[0]) . "' class='btn btn-lg btn-outline-primary text-uppercase'>Lihat Kos</a></td>
                                            </tr>";
                                        }
                                        echo "</tbody></table><hr>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <script type="text/javascript">
        $(function() {
            $("#dataTable").dataTable();
        });
    </script>
</div>

<?php
include "footer.php";
?>

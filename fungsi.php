<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$alternatif = array();
$queryalternatif = $conn->query("SELECT * FROM talternatif ORDER BY id_alternatif");
$i = 0;
while ($dataalternatif = $queryalternatif->fetch_assoc()) {
    $alternatif[$i] = $dataalternatif['nama_alternatif'];
    $i++;
}

$kriteria = array();
$costbenefit = array();
$kepentingan = array();

$querykriteria = $conn->query("SELECT * FROM tkriteria ORDER BY id_kriteria");
$i = 0;
while ($datakriteria = $querykriteria->fetch_assoc()) {
    $kriteria[$i] = $datakriteria['nama_kriteria'];
    $costbenefit[$i] = $datakriteria['costbenefit'];
    $kepentingan[$i] = $datakriteria['kepentingan'];
    $i++;
}

$alternatifkriteria = array();
$queryalternatif = $conn->query("SELECT * FROM talternatif ORDER BY id_alternatif");
$i = 0;
while ($dataalternatif = $queryalternatif->fetch_assoc()) {
    $querykriteria = $conn->query("SELECT * FROM tkriteria ORDER BY id_kriteria");
    $j = 0;
    while ($datakriteria = $querykriteria->fetch_assoc()) {
        $stmt = $conn->prepare("SELECT * FROM talternatif_kriteria WHERE id_alternatif = ? AND id_kriteria = ?");
        $stmt->bind_param("ii", $dataalternatif['id_alternatif'], $datakriteria['id_kriteria']);
        $stmt->execute();
        $result = $stmt->get_result();
        $dataalternatifkriteria = $result->fetch_assoc();
        
        $alternatifkriteria[$i][$j] = $dataalternatifkriteria['nilai'];
        $j++;
    }
    $i++;
}

// Calculate pembagi
$pembagi = array();
for ($i = 0; $i < count($kriteria); $i++) {
    $pembagi[$i] = 0;
    for ($j = 0; $j < count($alternatif); $j++) {
        $pembagi[$i] += ($alternatifkriteria[$j][$i] * $alternatifkriteria[$j][$i]);
    }
    $pembagi[$i] = sqrt($pembagi[$i]);
}

// Normalize
$normalisasi = array();
for ($i = 0; $i < count($alternatif); $i++) {
    for ($j = 0; $j < count($kriteria); $j++) {
        $normalisasi[$i][$j] = $alternatifkriteria[$i][$j] / $pembagi[$j];
    }
}

// Weighting
$terbobot = array();
for ($i = 0; $i < count($alternatif); $i++) {
    for ($j = 0; $j < count($kriteria); $j++) {
        $terbobot[$i][$j] = $normalisasi[$i][$j] * $kepentingan[$j];
    }
}

// Calculate A+ and A-
$aplus = array();
$amin = array();
for ($i = 0; $i < count($kriteria); $i++) {
    if ($costbenefit[$i] == 'Cost') {
        $aplus[$i] = min(array_column($terbobot, $i));
        $amin[$i] = max(array_column($terbobot, $i));
    } else {
        $aplus[$i] = max(array_column($terbobot, $i));
        $amin[$i] = min(array_column($terbobot, $i));
    }
}

// Calculate distances
$dplus = array();
$dmin = array();
for ($i = 0; $i < count($alternatif); $i++) {
    $dplus[$i] = 0;
    $dmin[$i] = 0;
    for ($j = 0; $j < count($kriteria); $j++) {
        $dplus[$i] += pow($aplus[$j] - $terbobot[$i][$j], 2);
        $dmin[$i] += pow($terbobot[$i][$j] - $amin[$j], 2);
    }
    $dplus[$i] = sqrt($dplus[$i]);
    $dmin[$i] = sqrt($dmin[$i]);
}

// Calculate results
$hasil = array();
for ($i = 0; $i < count($alternatif); $i++) {
    $hasil[$i] = $dmin[$i] / ($dmin[$i] + $dplus[$i]);
}

// Ranking
$alternatifrangking = array();
$hasilrangking = array();
for ($i = 0; $i < count($alternatif); $i++) {
    $hasilrangking[$i] = $hasil[$i];
    $alternatifrangking[$i] = $alternatif[$i];
}

// Sort by hasil
array_multisort($hasilrangking, SORT_DESC, $alternatifrangking);

// Close connection
$conn->close();
?>

<?php

function jml_kriteria($conn) {    
    $sql = "SELECT * FROM kriteria";
    $query = $conn->query($sql);
    $count = $query->num_rows;

    return $count;
}

function get_kriteria($conn) {
    $sql = "SELECT * FROM kriteria";
    $result = $conn->query($sql);
    $kri = array();
    $i = 1;
    while ($row = $result->fetch_assoc()) {
        $kri[$i] = $row['nama_kriteria'];
        $i++;
    }
    
    return $kri;
}

function jml_alternatif($conn) {  
    $sql = "SELECT * FROM analisa GROUP BY id_pemilik";
    $query = $conn->query($sql);
    $alternatif = $query->num_rows;

    return $alternatif;
}

function get_alt_name($conn) {
    $sql = "SELECT * FROM pemilik";
    $result = $conn->query($sql);
    $alt = array();
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        $alt[$i] = $row['nama_kos'];
        $i++;
    }
    
    return $alt;
}

function get_alternatif($conn) {
    $alternatifkriteria = array();

    $queryalternatif = $conn->query("SELECT * FROM pemilik ORDER BY id_pemilik");
    $i = 0;
    while ($dataalternatif = $queryalternatif->fetch_assoc()) {
        $querykriteria = $conn->query("SELECT * FROM kriteria ORDER BY id_kriteria");
        $j = 0;
        while ($datakriteria = $querykriteria->fetch_assoc()) {
            $queryalternatifkriteria = $conn->prepare("SELECT * FROM analisa WHERE id_pemilik = ? AND id_kriteria = ?");
            $queryalternatifkriteria->bind_param("ii", $dataalternatif['id_pemilik'], $datakriteria['id_kriteria']);
            $queryalternatifkriteria->execute();
            $result = $queryalternatifkriteria->get_result();
            $dataalternatifkriteria = $result->fetch_assoc();
            
            $alternatifkriteria[$i][$j] = $dataalternatifkriteria['nilainya'];
            $j++;
        }
        $i++;
    }
    
    return $alternatifkriteria;
}

function pembagi($kriteria, $alternatif, $alternatifkriteria) {
    $pembagi = array();

    for ($i = 0; $i < count($kriteria); $i++) {
        $pembagi[$i] = 0;
        for ($j = 0; $j < count($alternatif); $j++) {
            $pembagi[$i] += ($alternatifkriteria[$j][$i] * $alternatifkriteria[$j][$i]);
        }
        $pembagi[$i] = sqrt($pembagi[$i]);
    }

    return $pembagi;
}

function get_kepentingan($conn) {
    $sql = "SELECT * FROM kriteria";
    $result = $conn->query($sql);
    $kep = array();
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        $kep[$i] = $row['bobot_nilai'];
        $i++;
    }
    
    return $kep;
}

function get_costbenefit($conn) {
    $sql = "SELECT * FROM kriteria";
    $result = $conn->query($sql);
    $cb = array();
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        $cb[$i] = $row['atribut'];
        $i++;
    }
    
    return $cb;
}

function cmp($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a > $b) ? -1 : 1;
}

function print_ar(array $x) {    //just for print array
    echo "<pre>";
    print_r($x);
    echo "</pre></br>";
}

// Assuming $conn is your mysqli connection
$k = jml_kriteria($conn);
$kri = get_kriteria($conn);
$a = jml_alternatif($conn);
$alt = get_alternatif($conn);
$alt_name = get_alt_name($conn);
$kep = get_kepentingan($conn);
$cb = get_costbenefit($conn);

?>

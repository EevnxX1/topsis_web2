<?php
include "header.php";
include "menu.php";
include "connect.php"; // Pastikan koneksi mysqli ada di sini

// Pastikan koneksi berhasil
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
                        <p align="left"><a class='btn btn-primary' href="tambahkriteria.php">Tambah Data Kriteria</a></p>
                        <div class="table-responsive">
                            <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Kriteria</th>
                                        <th>Atribut</th>
                                        <th>Bobot Nilai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Prepare and execute the query
                                    $stmt = $conn->prepare("SELECT * FROM kriteria ORDER BY id_kriteria DESC");
                                    if (!$stmt) {
                                        die("Prepare failed: " . $conn->error);
                                    }

                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if (!$result) {
                                        die("Get result failed: " . $stmt->error);
                                    }

                                    $no = 1;
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr class='td' bgcolor='#FFF'>";
                                        echo "<td>{$no}</td>";
                                        echo "<td>" . htmlspecialchars($row['nama_kriteria'], ENT_QUOTES, 'UTF-8') . "</td>";
                                        echo "<td>" . htmlspecialchars($row['atribut'], ENT_QUOTES, 'UTF-8') . "</td>";
                                        echo "<td>" . htmlspecialchars($row['bobot_nilai'], ENT_QUOTES, 'UTF-8') . "</td>";
                                        echo "<td>
                                                <a class='btn btn-warning' href='editkriteria.php?idk={$row['id_kriteria']}'>Ubah</a>
                                                <a class='btn btn-danger' href='javascript:KonfirmasiHapus(\"deletekriteria.php?idk={$row['id_kriteria']}\")'>Hapus</a>
                                              </td>";
                                        echo "</tr>";
                                        $no++;
                                    }

                                    // Close statement
                                    $stmt->close();

                                    // Close connection
                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function() {
            $("#datatable").dataTable();
        });

        function KonfirmasiHapus(href) {
            if (confirm('Are you sure you want to delete this item?')) {
                window.location.href = href;
            }
        }
    </script>

<?php
include "footer.php";
?>

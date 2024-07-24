<?php
include "header.php";
include "menu.php";
ini_set("display_errors", "Off");
include("connect.php");

// Pastikan koneksi berhasil
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">DATA PEMAKAI SISTEM</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">User List</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-block">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Username</th>
                                        <th>Nama Lengkap</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Mengambil data dari database
                                    $sql = "SELECT * FROM login ORDER BY user DESC";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        $no = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr class='td' bgcolor='#FFF'>";
                                            echo "<td>" . $no . "</td>";
                                            echo "<td>" . htmlspecialchars($row['user']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                            echo "<td>
                                                    <a class='btn btn-warning' href='edituser.php?idk=" . urlencode($row['user']) . "'>Ubah</a>
                                                    <a class='btn btn-danger' href='javascript:KonfirmasiHapus(\"deleteuser.php?idk=" . urlencode($row['user']) . "\")'>Hapus</a>
                                                  </td>";
                                            echo "</tr>";
                                            $no++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
                                    }
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
    </script>

<?php
include "footer.php";
$conn->close();
?>

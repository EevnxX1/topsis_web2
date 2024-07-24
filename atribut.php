<?php
ini_set("display_errors", "Off");
include "header.php";
include "menu.php";
include("../connect.php");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $atribut = mysqli_real_escape_string($conn, $_POST['atribut']);
    
    if (empty($atribut)) {
        echo "<script>alert('Harap semua data diisi!');</script>";
    } else {
        $add_atribut = "INSERT INTO atribut (nama_atribut) VALUES ('$atribut')";
        if (mysqli_query($conn, $add_atribut)) {
            echo "<script>alert('Data Berhasil Disimpan!');</script>";
        } else {
            echo "<script>alert('Gagal menyimpan data: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">DATA ATRIBUT</h1>
            </div>
        </div>
        <div class="row">
            <div class="panel-body">
                <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
                    + TAMBAH DATA ATRIBUT
                </button>
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="myModalLabel">Tambah Data Atribut</h4>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post">
                                    <div class="form-group">
                                        <label>Atribut</label>
                                        <input type="text" class="form-control" name="atribut"/>
                                    </div>
                                    <div class="form-group">
                                        <input class="btn btn-default" type="submit" value="Simpan"/>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                List Data Atribut
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Atribut</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = mysqli_query($conn, "SELECT * FROM atribut ORDER BY id_atribut DESC");
                            $no = 1;
                            while ($row = mysqli_fetch_array($sql)) {
                                echo "
                                <tr class='td' bgcolor='#FFF'>
                                    <td>{$no}</td>
                                    <td>{$row['nama_atribut']}</td>
                                    <td>
                                        <a class='btn btn-warning' href='editatribut.php?idk={$row['id_atribut']}'>Ubah</a>
                                        <a class='btn btn-danger' href='javascript:KonfirmasiHapus(\"deleteatribut.php?idk={$row['id_atribut']}\")'>Hapus</a>
                                    </td>
                                </tr>";
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>

<script type="text/javascript">
    function KonfirmasiHapus(url) {
        if (confirm("Anda yakin record ini akan dihapus?")) {
            window.location.href = url;
        }
    }
</script>

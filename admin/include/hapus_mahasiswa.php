<?php
session_start();
include '../../koneksi/koneksi.php';
if (isset($_GET['nim'])) {
    $nim = $_GET['nim'];
    $query = mysqli_query($conn, "DELETE FROM mahasiswa WHERE nim='$nim'");
    if ($query) {
        $_SESSION['pesan'] = "<div class='alert alert-success' style='margin-top:5px;'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Berhasil Hapus Data Mahasiswa!
                              </div>";
        echo "<script>window.location = '../dashboard/index.php?page=12'</script>";
    } else {
        $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Gagal Hapus data Mahasiswa!
                              </div>";
        echo "<script>window.location = '../dashboard/index.php?page=12'</script>";
    }
}

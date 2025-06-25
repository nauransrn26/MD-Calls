<?php
session_start();
include '../../koneksi/koneksi.php';
if (isset($_GET['kd_matkul']) && isset($_GET['nim']) && isset($_GET['id_ujian'])) {
    $kd_matkul = $_GET['kd_matkul'];
    $nim = $_GET['nim'];
    $id_ujian = $_GET['id_ujian'];
    $query = mysqli_query($conn, "DELETE FROM detil_ujian WHERE id_ujian='$id_ujian' AND nim='$nim'");
    if ($query) {
        $_SESSION['pesan'] = "<div class='alert alert-success' style='margin-top:5px;'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Berhasil Hapus Mahasiswa dari matkul!
                              </div>";
        echo "<script>window.location = '../dashboard/index.php?page=10&&kd_matkul=$kd_matkul&&id_ujian=$id_ujian'</script>";
    } else {
        $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Gagal Hapus Mahasiswa dari matkul!
                              </div>";
        echo "<script>window.location = '../dashboard/index.php?page=10&&kd_matkul=$kd_matkul&&id_ujian=$id_ujian'</script>";
    }
}



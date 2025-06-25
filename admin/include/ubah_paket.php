<?php
// Pastikan sesi dimulai di awal file
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Sertakan file koneksi database Anda (misalnya, config.php atau connection.php)
// Pastikan variabel $conn tersedia dari file yang disertakan ini
include '../include/connection.php'; // Sesuaikan path sesuai kebutuhan Anda

$id_paket = "";
$nama_paket = "";
$harga = "";
$fasilitas = "";
$deskripsi = "";
$pesan_error = "";
$pesan_sukses = "";

// Cek apakah ID disediakan di URL untuk pengeditan
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_paket = htmlspecialchars($_GET['id']);

    // Ambil data paket yang sudah ada
    $query_fetch = mysqli_query($conn, "SELECT * FROM paket_umroh WHERE id_paket = '$id_paket'");

    if ($query_fetch && mysqli_num_rows($query_fetch) > 0) {
        $data_paket = mysqli_fetch_array($query_fetch);
        $nama_paket = htmlspecialchars($data_paket['nama_paket']);
        $harga = htmlspecialchars($data_paket['harga']);
        $fasilitas = htmlspecialchars($data_paket['fasilitas']);
        $deskripsi = htmlspecialchars($data_paket['deskripsi']);
    } else {
        $pesan_error = "Paket Umroh tidak ditemukan.";
    }
} else {
    $pesan_error = "ID Paket tidak diberikan untuk pengeditan.";
}

// Tangani pengiriman formulir untuk memperbarui data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitasi dan validasi input
    $id_paket_post = htmlspecialchars($_POST['id_paket']); // Field tersembunyi untuk ID asli
    $nama_paket_new = htmlspecialchars($_POST['nama_paket']);
    $harga_new = htmlspecialchars(str_replace('.', '', $_POST['harga'])); // Hapus titik untuk format angka
    $fasilitas_new = htmlspecialchars($_POST['fasilitas']);
    $deskripsi_new = htmlspecialchars($_POST['deskripsi']);

    // Validasi sederhana (tambahkan validasi yang lebih kuat sesuai kebutuhan)
    if (empty($nama_paket_new) || empty($harga_new) || empty($fasilitas_new) || empty($deskripsi_new)) {
        $pesan_error = "Semua kolom harus diisi.";
    } elseif (!is_numeric($harga_new)) {
        $pesan_error = "Harga harus berupa angka.";
    } else {
        // Perbarui data di database
        $query_update = "UPDATE paket_umroh SET 
                            nama_paket = '$nama_paket_new', 
                            harga = '$harga_new', 
                            fasilitas = '$fasilitas_new', 
                            deskripsi = '$deskripsi_new' 
                         WHERE id_paket = '$id_paket_post'";

        if (mysqli_query($conn, $query_update)) {
            $pesan_sukses = "Data Paket Umroh berhasil diperbarui!";
            // Alihkan kembali ke daftar paket setelah pembaruan berhasil
            // Anda mungkin ingin mengatur pesan sesi di sini untuk ditampilkan di halaman daftar
            $_SESSION['pesan'] = '<div class="alert alert-success alert-dismissible" role="alert">
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                      <strong>Berhasil!</strong> Data Paket Umroh ' . $nama_paket_new . ' berhasil diperbarui.
                                  </div>';
            header("Location: index.php?page=paket_umroh"); // Alihkan ke halaman daftar paket utama
            exit();
        } else {
            $pesan_error = "Error memperbarui data: " . mysqli_error($conn);
        }
    }
}
?>

<h1><b>Edit Paket Umroh</b></h1>
<ol class="breadcrumb">
    <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="index.php?page=paket_umroh"><i class="fa fa-user"></i>Paket Umroh</a></li>
    <li class="active">Edit Paket Umroh</li>
</ol>

<h5><b><?php echo date("l, M Y"); ?></b></h5>
<hr>

<div class="box box-info">
    <div class="box-header">
        <i class="fa fa-edit"></i>
        <h3 class="box-title">Form Edit Paket Umroh</h3>
    </div>
    <div class="box-body">
        <?php
        if (!empty($pesan_error)) {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <strong>Error!</strong> ' . $pesan_error . '
                  </div>';
        }
        if (!empty($pesan_sukses)) {
            echo '<div class="alert alert-success alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
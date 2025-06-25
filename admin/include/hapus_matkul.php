<?php
session_start();
include '../../koneksi/koneksi.php'; // Pastikan path ke koneksi.php sudah benar

if (isset($_GET['id_dokter'])) {
    $id_dokter_to_delete = $_GET['id_dokter']; // Gunakan nama variabel yang jelas

    // Pastikan koneksi ke database berhasil sebelum menjalankan query
    if ($conn) {
        // Gunakan prepared statement untuk keamanan yang lebih baik (melindungi dari SQL Injection)
        // Namun, jika Anda ingin tetap menggunakan mysqli_query, pastikan nilai sudah disanitasi.
        // Untuk contoh ini, saya akan tetap menggunakan mysqli_query seperti kode Anda,
        // tetapi sangat disarankan untuk beralih ke prepared statements.

        $query = mysqli_query($conn, "DELETE FROM dokter WHERE id_dokter='$id_dokter_to_delete'");

        if ($query) {
            $_SESSION['pesan'] = "<div class='alert alert-success' style='margin-top:5px;'>
                                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                    Berhasil Hapus Data Dokter!
                                  </div>";
            // Redireksi setelah menampilkan pesan
            echo "<script>window.location = '../dashboard/index.php?page=4'</script>";
            exit(); // Penting untuk menghentikan eksekusi script setelah redirect
        } else {
            $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                    Gagal Hapus Data Dokter! Error: " . mysqli_error($conn) . "
                                  </div>";
            echo "<script>window.location = '../dashboard/index.php?page=4'</script>";
            exit(); // Penting untuk menghentikan eksekusi script setelah redirect
        }
    } else {
        // Handle case where database connection failed
        $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Koneksi database gagal!
                              </div>";
        echo "<script>window.location = '../dashboard/index.php?page=4'</script>";
        exit();
    }
} else {
    // Jika parameter id_dokter tidak ditemukan
    $_SESSION['pesan'] = "<div class='alert alert-warning' style='margin-top:5px;'>
                            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                            ID Dokter tidak ditemukan.
                          </div>";
    echo "<script>window.location = '../dashboard/index.php?page=4'</script>";
    exit();
}
?>
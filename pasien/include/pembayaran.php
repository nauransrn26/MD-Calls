<?php
// FILE: include/daftar_berobat.php (atau nama file yang relevan)

// Pastikan session_start() sudah dipanggil di file utama (misal index.php)
// dan variabel $conn sudah tersedia dari file utama.
// Tidak perlu session_start() atau koneksi database di sini lagi jika sudah dari index.php.

// --- Ambil ID Pasien dari sesi ---
$id_pasien_login = '';
if (isset($_SESSION['id_pasien'])) {
    $id_pasien_login = $_SESSION['id_pasien'];
} else {
    // Jika id_pasien tidak ada di sesi, berarti pasien belum login atau sesi bermasalah.
    // Anda bisa mengarahkan kembali ke halaman login atau menampilkan pesan error.
    // header("Location: login.php");
    // exit();
    // Untuk saat ini, kita akan biarkan tampil "Tidak ada data kunjungan ditemukan."
}

?>

<h1>
    <b>Pendaftaran Berobat </b>
</h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-user"></i>Data Pembayaran</a></li>
</ol>

<h5><b><?php echo date("l, M Y"); ?></b></h5>
<hr>
<h5><b>Pendaftaran Berobat</b></h5>
<p>Berikut ini adalah Riwayat Pendaftaran anda di MD Calls:</p>
<br>
<div class="box box-info">
    <div class="box-header">
        <i class="fa fa-pencil"></i>
        <h3 class="box-title">Daftar Berobat</h3>
    </div>
    <?php
    if (isset($_SESSION['pesan']) && $_SESSION['pesan'] != "") {
        echo $_SESSION['pesan'];
        unset($_SESSION['pesan']);
    } else {
        echo "";
    }
    ?>

    <div class="box-body">
        <a href="index.php?page=4" class="btn btn-primary btn-lg" style="margin-bottom:15px; background-color: #dd4b39; border-color: #dd4b39;"><i class="fa fa-plus"></i> Daftar Berobat </a>

        <div class="table-responsive">
            <table id="table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID Kunjungan</th>
                        <th>ID Pasien</th>
                        <th>ID Dokter</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Waktu Kunjungan</th>
                        <th>Keluhan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Pastikan $id_pasien_login tidak kosong sebelum menjalankan query
                    if (!empty($id_pasien_login)) {
                        // Menggunakan Prepared Statement untuk mengambil data kunjungan berdasarkan ID Pasien
                        $query_kunjungan = "SELECT id_kunjungan, id_pasien, id_dokter, tanggal_kunjungan, waktu_kunjungan, keluhan FROM kunjungan WHERE id_pasien = ?";
                        
                        $stmt_kunjungan = mysqli_prepare($conn, $query_kunjungan);

                        if ($stmt_kunjungan) {
                            mysqli_stmt_bind_param($stmt_kunjungan, "s", $id_pasien_login);
                            mysqli_stmt_execute($stmt_kunjungan);
                            $result_kunjungan = mysqli_stmt_get_result($stmt_kunjungan);

                            if ($result_kunjungan && mysqli_num_rows($result_kunjungan) > 0) {
                                while ($rows = mysqli_fetch_array($result_kunjungan)) {
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($rows['id_kunjungan']); ?></td>
                                        <td><?php echo htmlspecialchars($rows['id_pasien']); ?></td>
                                        <td><?php echo htmlspecialchars($rows['id_dokter']); ?></td>
                                        <td><?php echo htmlspecialchars($rows['tanggal_kunjungan']); ?></td>
                                        <td><?php echo htmlspecialchars($rows['waktu_kunjungan']); ?></td>
                                        <td><?php echo htmlspecialchars($rows['keluhan']); ?></td>
                                    </tr>
                                <?php
                                }
                            } else {
                                echo '<tr><td colspan="6">Tidak ada riwayat kunjungan untuk pasien ini.</td></tr>';
                            }
                            mysqli_stmt_close($stmt_kunjungan);
                        } else {
                            echo '<tr><td colspan="6">Error saat menyiapkan kueri data kunjungan: ' . htmlspecialchars(mysqli_error($conn)) . '</td></tr>';
                        }
                    } else {
                        echo '<tr><td colspan="6">ID Pasien tidak ditemukan dalam sesi. Silakan login kembali.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#table').DataTable();
        // Bagian modal delete (jika ada)
        $(".btn.btn-danger").click(function(e) {
            var id = $(this).attr('id');
            var modal_id = "confirm-delete_" + id;
            $("#" + modal_id).modal('hide');
        });
    });
</script>

<?php
// Tidak perlu mysqli_close($conn) di sini jika $conn ditutup di file utama (index.php)
?>
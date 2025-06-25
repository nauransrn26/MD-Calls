<?php
// Pastikan sesi dimulai di awal file
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- Koneksi Database ---
// Pastikan $conn sudah terdefinisi dari file induk (index.php pasien)
// Jika belum, Anda bisa tambahkan ini, tetapi idealnya $conn sudah ada
// dari include '../koneksi/koneksi.php' di index.php pasien.
if (!isset($conn)) {
    $servername = "localhost";
    $username_db = "root"; // Ganti dengan username DB Anda
    $password_db = "";     // Ganti dengan password DB Anda
    $dbname = "k3";        // Ganti dengan nama DB Anda

    $conn = mysqli_connect($servername, $username_db, $password_db, $dbname);

    if (!$conn) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }
}

// Logika untuk menghasilkan ID Kunjungan Otomatis
// Asumsi 'id_kunjungan' adalah VARCHAR atau CHAR (misal: K001, K002)
// Jika ini AUTO_INCREMENT di database, Anda TIDAK perlu kode ini
$next_id_kunjungan = '';
$query_max_id = "SELECT MAX(id_kunjungan) AS max_id FROM kunjungan";
$result_max_id = mysqli_query($conn, $query_max_id);

if ($result_max_id && mysqli_num_rows($result_max_id) > 0) {
    $row_max_id = mysqli_fetch_assoc($result_max_id);
    $last_id = $row_max_id['max_id'];

    if ($last_id) {
        // Ekstrak angka dari ID terakhir (misal: K001 -> 1)
        preg_match('/(\d+)$/', $last_id, $matches);
        $last_number = isset($matches[1]) ? (int)$matches[1] : 0;
        $next_number = $last_number + 1;
        // Format ulang menjadi K001, K002, dst. Sesuaikan 'K' dan jumlah nol
        $next_id_kunjungan = 'K' . str_pad($next_number, 3, '0', STR_PAD_LEFT);
    } else {
        // Jika belum ada data, mulai dari K001
        $next_id_kunjungan = 'K001';
    }
} else {
    // Jika query gagal atau tabel kosong
    $next_id_kunjungan = 'K001';
    error_log("Error fetching max id_kunjungan: " . mysqli_error($conn));
}


if (isset($_POST['simpan'])) {
    // Ambil data dari $_POST
    // Gunakan id_kunjungan yang dihasilkan otomatis jika form tidak bisa diubah user
    // Jika id_kunjungan diisi user di form, maka gunakan $_POST['id_kunjungan']
    $id_kunjungan = $_POST['id_kunjungan'] ?? $next_id_kunjungan; // Gunakan yang dari form jika ada, jika tidak, gunakan auto-generated
    $id_pasien = $_POST['id_pasien'] ?? '';
    $id_dokter = $_POST['id_dokter'] ?? '';
    $tanggal_kunjungan = $_POST['tanggal_kunjungan'] ?? '';
    $waktu_kunjungan = $_POST['waktu_kunjungan'] ?? '';
    $keluhan = $_POST['keluhan'] ?? '';

    // Validasi input dasar
    if (empty($id_kunjungan) || empty($id_pasien) || empty($id_dokter) || empty($tanggal_kunjungan) || empty($waktu_kunjungan) || empty($keluhan)) {
        $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Tidak Boleh Ada Field yang Kosong!
                              </div>";
        // Mengarahkan ke halaman yang benar setelah submit form
        // Sesuaikan 'index.php?page=X' dengan page yang menampilkan form ini
        echo "<script>window.location = 'index.php?page=4'</script>"; // Jika form ini di page 4
        exit();
    }

    // --- Insert Data ke Database dengan Prepared Statements ---
    // Perbaiki SQL statement: hapus koma di akhir dan pastikan jumlah kolom dan placeholder sesuai
    $sql_insert = "INSERT INTO kunjungan (id_kunjungan, id_pasien, id_dokter, tanggal_kunjungan, waktu_kunjungan, keluhan) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_insert = mysqli_prepare($conn, $sql_insert);

    if ($stmt_insert) {
        // Bind parameter: "ssssss" untuk 6 parameter string
        // Sesuaikan tipe data jika ada kolom numerik, tanggal, atau waktu
        // id_kunjungan (s), id_pasien (s), id_dokter (s), tanggal_kunjungan (s/date), waktu_kunjungan (s/time), keluhan (s)
        mysqli_stmt_bind_param($stmt_insert, "ssssss", $id_kunjungan, $id_pasien, $id_dokter, $tanggal_kunjungan, $waktu_kunjungan, $keluhan);

        if (mysqli_stmt_execute($stmt_insert)) {
            $_SESSION['pesan'] = "<div class='alert alert-success' style='margin-top:5px;'>
                                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                    Berhasil Daftar Berobat!
                                  </div>";
            // Arahkan ke halaman daftar berobat yang menampilkan data, misalnya page 7
            echo "<script>window.location = 'index.php?page=7'</script>";
        } else {
            $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                    Gagal Daftar Berobat! Error: " . mysqli_error($conn) . "
                                  </div>";
            echo "<script>window.location = 'index.php?page=4'</script>"; // Kembali ke form jika gagal
        }
        mysqli_stmt_close($stmt_insert);
    } else {
        $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Gagal menyiapkan statement SQL: " . mysqli_error($conn) . "
                              </div>";
        echo "<script>window.location = 'index.php?page=4'</script>";
    }
}
?>

<h1> <b>Daftar Berobat</b>
</h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-user"></i> Daftar Berobat</a></li>
</ol>

<hr>

<h5><b><?php echo date("l, M Y"); ?></b></h5>
<hr>
<a href="index.php?page=7" class="btn btn-primary btn-lg" style="margin-right:10px;float:right;background-color: #dd4b39; border-color: #dd4b39;"><i class="fa fa-arrow-circle-left"></i> Kembali</a>

<h5><b>Daftar Berobat</b></h5>
<p>Isilah Form dibawah untuk mendaftar berobat:</p>
<br>

<div class="box box-info">
    <div class="box-header">
        <i class="fa fa-user"></i>
        <h3 class="box-title">Form Pendaftaran Berobat</h3> <?php
        // Pastikan Anda menampilkan pesan sesi di sini
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] != "") {
            echo $_SESSION['pesan'];
            unset($_SESSION['pesan']);
        }
        ?>
    </div>

    <div class="box-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="id_kunjungan">Id Kunjungan</label>
                <input type="text" class="form-control" name="id_kunjungan" id="id_kunjungan" 
                       value="<?php echo htmlspecialchars($next_id_kunjungan); ?>" readonly required>
            </div>
           <div class="form-group">
    <label for="id_pasien">Id Pasien</label>
    <input type="text" class="form-control" name="id_pasien" id="id_pasien" placeholder="Id Pasien" required
        value="<?php echo isset($_SESSION['id_pasien']) ? htmlspecialchars($_SESSION['id_pasien']) : ''; ?>" >
</div>
            <div class="form-group">
                <label for="id_dokter">Id Dokter</label>
                <input type="text" class="form-control" name="id_dokter" id="id_dokter" placeholder="Id Dokter" required>
            </div>
            <div class="form-group">
                <label for="tanggal_kunjungan">Tanggal Kunjungan</label>
                <input type="date" class="form-control" name="tanggal_kunjungan" id="tanggal_kunjungan" required>
            </div>
            <div class="form-group">
                <label for="waktu_kunjungan">Waktu Kunjungan</label>
                <input type="time" class="form-control" name="waktu_kunjungan" id="waktu_kunjungan" required>
            </div>
            <div class="form-group">
                <label for="keluhan">Keluhan</label>
                <textarea class="form-control" name="keluhan" id="keluhan" placeholder="Tulis keluhan Anda di sini..." required></textarea>
            </div>

            <div class="box-footer clearfix">
                <button type="submit" class="pull-right btn btn-primary" name="simpan" style="background-color: #dd4b39; border-color: #dd4b39;">Simpan <i class="fa fa-arrow-circle-right"></i></button>
            </div>
        </form>
    </div>
</div>
<?php
// PASTIKAN session_start(); ada di bagian paling atas file PHP utama Anda (misalnya index.php).
// Jika belum ada, tambahkan di baris paling atas:
// session_start();

// PASTIKAN $conn sudah terdefinisi dan merupakan koneksi MySQLi yang aktif.
// Ini adalah contoh bagaimana $conn bisa didefinisikan jika belum ada di file utama:
/*
$servername = "localhost";
$username_db = "root"; // Sesuaikan dengan username database Anda
$password_db = "";     // Sesuaikan dengan password database Anda
$dbname = "k3";        // Sesuaikan dengan nama database Anda

$conn = mysqli_connect($servername, $username_db, $password_db, $dbname);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
*/

// --- PENTING: Ambil username pasien yang sedang login dari sesi ---
$username_pasien_login = '';
if (isset($_SESSION['username'])) {
    $username_pasien_login = $_SESSION['username'];
} else {
    // Jika username tidak ada di sesi, arahkan kembali ke halaman login atau tampilkan pesan error
    // echo "<div class='alert alert-danger'>Anda belum login. Silakan <a href='login.php'>login</a> terlebih dahulu.</div>";
    // Jika ini adalah bagian dari sistem navigasi, bisa juga tidak melakukan apa-apa dan
    // tampilan "Tidak ada data pasien ditemukan" akan muncul.
}

$data_pasien = null; // Inisialisasi variabel untuk menampung data pasien

// Hanya jalankan query jika username pasien login berhasil didapatkan dari sesi
if (!empty($username_pasien_login)) {
    // Gunakan prepared statement untuk keamanan
    // Pilih semua kolom yang Anda butuhkan
    $query = "SELECT id_pasien, username, nama, email, jenis_kelamin, tempat_lahir, tanggal_lahir FROM pasien WHERE username = ?";
    
    $stmt = mysqli_prepare($conn, $query); //

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username_pasien_login); // Bind parameter 'username' sebagai string
        mysqli_stmt_execute($stmt); // Jalankan query
        $result = mysqli_stmt_get_result($stmt); // Ambil hasil

        if ($result && mysqli_num_rows($result) > 0) {
            $data_pasien = mysqli_fetch_array($result); // Ambil data sebagai array asosiatif/numerik
        }
        mysqli_stmt_close($stmt); // Tutup statement
    } else {
        // Handle error jika prepared statement gagal
        echo "<div class='alert alert-danger' style='margin-top:5px;'>Error saat menyiapkan kueri: " . htmlspecialchars(mysqli_error($conn)) . "</div>";
    }
}
?>

<h1><b>Data Pasien</b></h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-files-o"></i> Data Pasien</a></li>
</ol>

<hr>
<h5><b><?php echo date("l, M Y"); ?></b></h5>
<hr>

<h5><b>Data Pasien</b></h5>
<p>Berikut ini adalah data pasien yang terdaftar di MD Calls:</p><br>

<div class="row">
    <div class="box box-info">
        <div class="box-header">
            <i class="fa fa-user"></i>
            <h3 class="box-title">Data Pribadi</h3>
        </div>
        <div class="box-body">
            <div class="col-sm-6">
                <table class="table table-hover table-bordered table-striped">
                    <?php if ($data_pasien) : // Periksa apakah $data_pasien tidak null (data pasien ditemukan) ?>
                        <tr>
                            <td><b>Id Pasien </b></td>
                            <td>: <b><?php echo htmlspecialchars($data_pasien['id_pasien']); ?></b></td>
                        </tr>
                        <tr>
                            <td><b>Username</b></td>
                            <td>: <b><?php echo htmlspecialchars($data_pasien['username']); ?></b></td>
                        </tr>
                        <tr>
                            <td><b>Nama Pasien</b></td>
                            <td>: <b><?php echo htmlspecialchars($data_pasien['nama']); ?></b></td>
                        </tr>
                        <tr>
                            <td><b>Email</b></td>
                            <td>: <b><?php echo htmlspecialchars($data_pasien['email']); ?></b></td>
                        </tr>
                        <tr>
                            <td><b>Jenis Kelamin</b></td>
                            <td>: <b><?php echo htmlspecialchars($data_pasien['jenis_kelamin']); ?></b></td>
                        </tr>
                        <tr>
                            <td><b>Tempat Lahir</b></td>
                            <td>: <b><?php echo htmlspecialchars($data_pasien['tempat_lahir']); ?></b></td>
                        </tr>
                        <tr>
                            <td><b>Tanggal Lahir</b></td>
                            <td>: <b><?php echo htmlspecialchars($data_pasien['tanggal_lahir']); ?></b></td>
                        </tr>
                    <?php else : // Jika $data_pasien null (data tidak ditemukan atau belum login) ?>
                        <tr>
                            <td colspan="2">Tidak ada data pasien ditemukan atau Anda belum login. Silakan login untuk melihat data Anda.</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>

            <div class="col-sm-6">
                <?php
                if (isset($_SESSION['pesan']) && $_SESSION['pesan'] != "") {
                    echo $_SESSION['pesan'];
                    unset($_SESSION['pesan']);
                } else {
                    echo "";
                }
                ?>
                <form action="" method="post" enctype="multipart/form-data">
                    </form>
            </div>

            <div class="col-sm-12" style="margin-top:20px;">
                <p><b>Note : </b>
                    <font color="red">Jika ada kesalahan pada data Anda, harap hubungi pimpinan untuk memperbaiki.</font>
                </p>
            </div>
        </div>
    </div>
</div>

<?php
// Bagian fungsi random_string dan logika upload DP saya biarkan seperti kode asli Anda.
// Pastikan path ../images/ benar dan memiliki izin tulis (writable).

function random_string($key)
{
    $length = 30;
    $keys = array_merge(range(0, 9), range('a', 'z'));

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }
    return $key . '.jpg';
}

$imageTypes = array(
    1 => 'GIF',
    2 => 'JPEG',
    3 => 'PNG',
    4 => 'SWF',
    5 => 'PSD',
    6 => 'BMP',
    7 => 'TIFF_II',
    8 => 'TIFF_MM',
    9 => 'JPC',
    10 => 'JP2',
    11 => 'JPX',
    12 => 'JB2',
    13 => 'SWC',
    14 => 'IFF',
    15 => 'WBMP',
    16 => 'XBM',
    17 => 'ICO'
);

if (isset($_POST['upload'])) {
    if ($_FILES['dp']['tmp_name'] == "") {
        $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                    Display Profil tidak boleh kosong!
                                  </div>";
        echo "<script>window.location = 'index.php?page=2'</script>";
    } else {
        $type   = exif_imagetype($_FILES['dp']['tmp_name']);
        $types  = $imageTypes[$type];
        if ($types != 'JPEG' && $types != 'GIF' && $types != 'PNG' && $types != 'JPG') {
            $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                        Image harus dalam format JPEG, GIF, PNG atau JPG!
                                      </div>";
            echo "<script>window.location = 'index.php?page=2'</script>";
        } else {
            $nil = array();
            $dp_name = basename($_FILES["dp"]["name"]);
            $nil = explode('.', $dp_name);
            $dp_name = random_string($nil[0]);

            $target_file = "../images/" . $dp_name;

            if (move_uploaded_file($_FILES["dp"]["tmp_name"], $target_file)) {
                // *** PENTING: Tambahkan query UPDATE untuk menyimpan nama file DP di database ***
                // Asumsi kolom untuk gambar profil di tabel 'pasien' adalah 'dp_gambar' atau 'foto_profil'
                // dan Anda mengupdate berdasarkan username pasien yang sedang login.
                $update_query_dp = "UPDATE pasien SET dp_gambar = ? WHERE username = ?"; // Sesuaikan 'dp_gambar' jika nama kolom beda
                $stmt_update_dp = mysqli_prepare($conn, $update_query_dp);
                if ($stmt_update_dp) {
                    mysqli_stmt_bind_param($stmt_update_dp, "ss", $dp_name, $username_pasien_login);
                    mysqli_stmt_execute($stmt_update_dp);
                    mysqli_stmt_close($stmt_update_dp);
                } else {
                    // Handle error jika prepared statement update DP gagal
                    $_SESSION['pesan'] = "<div class='alert alert-warning' style='margin-top:5px;'>
                                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                                Gagal memperbarui nama file DP di database!
                                              </div>";
                }

                $_SESSION['pesan'] = "<div class='alert alert-success' style='margin-top:5px;'>
                                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                        Berhasil Ubah DP Profil!
                                      </div>";
                echo "<script>window.location = 'index.php?page=2'</script>"; // Pastikan page=2 adalah halaman Data Diri pasien
            } else {
                $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                        Gagal Ubah DP Profil!
                                      </div>";
                echo "<script>window.location = 'index.php?page=2'</script>";
            }
        }
    }
}
?>
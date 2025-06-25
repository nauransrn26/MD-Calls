<?php
session_start();
error_reporting(E_ALL); // Aktifkan ini untuk debugging
ini_set('display_errors', 1); // Aktifkan ini untuk debugging

include 'koneksi/koneksi.php';

$pesan_status = "";

if (isset($_POST['register_pasien'])) {
    // Ambil dan sanitasi input dari form
    $username = trim($_POST['username']);
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tempat_lahir = trim($_POST['tempat_lahir']);
    $tanggal_lahir = $_POST['tanggal_lahir'];

    // Validasi input
    if (empty($username) || empty($nama) || empty($email) || empty($password) || empty($confirm_password) || empty($jenis_kelamin) || empty($tempat_lahir) || empty($tanggal_lahir)) {
        $pesan_status = "<span style='color:red;'>Semua field harus diisi!</span>";
    } elseif ($password !== $confirm_password) {
        $pesan_status = "<span style='color:red;'>Konfirmasi password tidak cocok!</span>";
    } elseif (strlen($password) < 6) {
        $pesan_status = "<span style='color:red;'>Password minimal 6 karakter!</span>";
    } else {
        // Enkripsi password sebelum disimpan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Cek apakah username atau email sudah ada di tabel pasien
        $check_query = "SELECT COUNT(*) FROM pasien WHERE username = ? OR email = ?";
        $stmt_check = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt_check, "ss", $username, $email);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_bind_result($stmt_check, $count);
        mysqli_stmt_fetch($stmt_check);
        mysqli_stmt_close($stmt_check);

        if ($count > 0) {
            $pesan_status = "<span style='color:red;'>Username atau Email sudah terdaftar!</span>";
        } else {
            // --- BAGIAN BARU: GENERASI ID PASIEN OTOMATIS ---
            $new_id_pasien = '';
            $query_get_last_id = "SELECT id_pasien FROM pasien ORDER BY id_pasien DESC LIMIT 1";
            $result_last_id = mysqli_query($conn, $query_get_last_id);

            if ($result_last_id && mysqli_num_rows($result_last_id) > 0) {
                $row = mysqli_fetch_assoc($result_last_id);
                $last_id = $row['id_pasien']; // Contoh: P003
                
                // Ekstrak angka dari ID terakhir (misal: 003 dari P003)
                $numeric_part = (int) substr($last_id, 1); 
                $new_numeric_part = $numeric_part + 1;

                // Format angka kembali ke Pxxx (misal: P004)
                $new_id_pasien = 'P' . sprintf('%03d', $new_numeric_part); 
            } else {
                // Jika belum ada pasien, mulai dari P001
                $new_id_pasien = 'P001';
            }
            // --- AKHIR BAGIAN BARU ---

            // Masukkan data ke database menggunakan Prepared Statement
            // Sesuaikan kolom di INSERT sesuai dengan field tabel pasien Anda
            $insert_query = "INSERT INTO pasien (id_pasien, username, nama, email, password, jenis_kelamin, tempat_lahir, tanggal_lahir) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert = mysqli_prepare($conn, $insert_query);

            if ($stmt_insert) {
                // Binding parameter sesuai urutan dan tipe data
                // s = string, i = integer, d = double, b = blob
                mysqli_stmt_bind_param($stmt_insert, "ssssssss", $new_id_pasien, $username, $nama, $email, $hashed_password, $jenis_kelamin, $tempat_lahir, $tanggal_lahir);
                
                if (mysqli_stmt_execute($stmt_insert)) {
                    $pesan_status = "<span style='color:green;'>Registrasi pasien berhasil! ID Pasien Anda: <strong>" . htmlspecialchars($new_id_pasien) . "</strong>. Silakan <a href='index.php'>Login</a>.</span>";
                    // Opsional: Redirect ke halaman login pasien (index.php)
                    // header("location: index.php");
                    // exit;
                } else {
                    $pesan_status = "<span style='color:red;'>Error: " . mysqli_error($conn) . "</span>";
                }
                mysqli_stmt_close($stmt_insert);
            } else {
                $pesan_status = "<span style='color:red;'>Error saat menyiapkan statement: " . mysqli_error($conn) . "</span>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Pasien</title>
    <link rel="stylesheet" href="materialize/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    <style>
        body { background-color: #f5f5f5; }
        .reg-container { margin-top: 50px; }
        .card-panel {
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h5 { color: #3f51b5; margin-bottom: 20px; }
        hr { border: 0; height: 1px; background-color: #e0e0e0; margin-bottom: 20px; }
        .input-field input:not(.browser-default):focus + label,
        .input-field textarea:not(.browser-default):focus + label,
        .input-field select:not(.browser-default):focus + label { /* Tambahkan select */
            color: #3f51b5 !important;
        }
        .input-field input:not(.browser-default):focus,
        .input-field textarea:not(.browser-default):focus,
        .input-field select:not(.browser-default):focus { /* Tambahkan select */
            border-bottom: 1px solid #3f51b5 !important;
            box-shadow: 0 1px 0 0 #3f51b5 !important;
        }
        .btn {
            border-radius: 5px;
            box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);
        }
        .input-field i.prefix { font-size: 2rem; color: #757575; }
        .input-field input:focus + i.prefix,
        .input-field textarea:focus + i.prefix,
        .input-field select:focus + i.prefix { /* Tambahkan select */
            color: #3f51b5;
        }
        .input-field { margin-bottom: 20px; }
        .login-link {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9em;
        }
        .login-link a {
            color: #3f51b5;
            font-weight: bold;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .status-message {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }
        /* Style untuk dropdown Materialize */
        .select-wrapper input.select-dropdown:focus {
            border-bottom: 1px solid #3f51b5 !important;
            box-shadow: 0 1px 0 0 #3f51b5 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row reg-container">
            <div class="col s12 m8 offset-m2 l6 offset-l3 card-panel z-depth-3">
                <center>
                    <h5>REGISTRASI PASIEN</h5>
                </center>
                <div class="status-message">
                    <?php echo $pesan_status; ?>
                </div>
                <hr>
                <form method="post" action="">
                    <div class="row">
                        <div class="input-field col s12">
                            <i class="mdi mdi-account-circle prefix"></i>
                            <input id="username" type="text" class="validate" name="username" required>
                            <label for="username">USERNAME</label>
                        </div>
                        <div class="input-field col s12">
                            <i class="mdi mdi-account prefix"></i>
                            <input id="nama" type="text" class="validate" name="nama" required>
                            <label for="nama">NAMA LENGKAP</label>
                        </div>
                        <div class="input-field col s12">
                            <i class="mdi mdi-email-outline prefix"></i>
                            <input id="email" type="email" class="validate" name="email" required>
                            <label for="email">EMAIL</label>
                        </div>
                        <div class="input-field col s12">
                            <i class="mdi mdi-lock-outline prefix"></i>
                            <input id="password" type="password" class="validate" name="password" required>
                            <label for="password">PASSWORD</label>
                        </div>
                        <div class="input-field col s12">
                            <i class="mdi mdi-lock-check-outline prefix"></i>
                            <input id="confirm_password" type="password" class="validate" name="confirm_password" required>
                            <label for="confirm_password">KONFIRMASI PASSWORD</label>
                        </div>

                        <div class="input-field col s12">
                            <i class="mdi mdi-gender-male-female prefix"></i>
                            <select id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                            <label for="jenis_kelamin">JENIS KELAMIN</label>
                        </div>

                        <div class="input-field col s12">
                            <i class="mdi mdi-map-marker-outline prefix"></i>
                            <input id="tempat_lahir" type="text" class="validate" name="tempat_lahir" required>
                            <label for="tempat_lahir">TEMPAT LAHIR</label>
                        </div>

                        <div class="input-field col s12">
                            <i class="mdi mdi-calendar-blank-outline prefix"></i>
                            <input id="tanggal_lahir" type="date" class="datepicker" name="tanggal_lahir" required>
                            <label for="tanggal_lahir">TANGGAL LAHIR</label>
                        </div>
                        
                        <div class="input-field col s12">
                            <button class="btn xxy col s12 indigo darken-1 waves-effect waves-light" name="register_pasien">Daftar Pasien</button>
                        </div>
                        <div class="col s12 login-link">
                            Sudah punya akun pasien? <a href="index.php">Login di sini</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="materialize/js/materialize.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Materialize Select (Dropdown)
            var elemsSelect = document.querySelectorAll('select');
            M.FormSelect.init(elemsSelect);

            // Inisialisasi Materialize Datepicker
            var elemsDatepicker = document.querySelectorAll('.datepicker');
            M.Datepicker.init(elemsDatepicker, {
                format: 'yyyy-mm-dd', // Format tanggal yang akan disimpan ke database MySQL
                showClearBtn: true,
                autoClose: true,
                yearRange: 70 // Rentang tahun untuk pemilihan
            });
        });
    </script>
</body>
</html>
<?php mysqli_close($conn); ?>
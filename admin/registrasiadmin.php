<?php
session_start();
error_reporting(E_ALL); // Aktifkan ini untuk debugging
ini_set('display_errors', 1); // Aktifkan ini untuk debugging

// Asumsi file koneksi.php ada di '../koneksi/koneksi.php' jika register_admin.php ada di folder 'admin'
// Jika register_admin.php ada di root folder, maka 'koneksi/koneksi.php'
include '../koneksi/koneksi.php'; 

$pesan_status = "";

if (isset($_POST['registrasi_admin'])) {
    $username = trim($_POST['username']);
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi input
    if (empty($username) || empty($nama) || empty($email) || empty($password) || empty($confirm_password)) {
        $pesan_status = "<span style='color:red;'>Semua field harus diisi!</span>";
    } elseif ($password !== $confirm_password) {
        $pesan_status = "<span style='color:red;'>Konfirmasi password tidak cocok!</span>";
    } elseif (strlen($password) < 6) {
        $pesan_status = "<span style='color:red;'>Password minimal 6 karakter!</span>";
    } else {
        // Enkripsi password sebelum disimpan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Cek apakah username atau email sudah ada
        $check_query = "SELECT COUNT(*) FROM admin WHERE username = ? OR email = ?";
        $stmt_check = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt_check, "ss", $username, $email);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_bind_result($stmt_check, $count);
        mysqli_stmt_fetch($stmt_check);
        mysqli_stmt_close($stmt_check);

        if ($count > 0) {
            $pesan_status = "<span style='color:red;'>Username atau Email sudah terdaftar!</span>";
        } else {
            // Masukkan data ke database menggunakan Prepared Statement
            $insert_query = "INSERT INTO admin (username, nama, email, password) VALUES (?, ?, ?, ?)";
            $stmt_insert = mysqli_prepare($conn, $insert_query);

            if ($stmt_insert) {
                mysqli_stmt_bind_param($stmt_insert, "ssss", $username, $nama, $email, $hashed_password);
                if (mysqli_stmt_execute($stmt_insert)) {
                    $pesan_status = "<span style='color:green;'>Registrasi admin berhasil! Silakan <a href='index.php'>Login</a>.</span>";
                    // Opsional: Redirect ke halaman login admin
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
    <title>Registrasi Admin</title>
    <link rel="stylesheet" href="../materialize/css/materialize.min.css">
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
        .input-field input:not(.browser-default):focus + label { color: #3f51b5 !important; }
        .input-field input:not(.browser-default):focus {
            border-bottom: 1px solid #3f51b5 !important;
            box-shadow: 0 1px 0 0 #3f51b5 !important;
        }
        .btn {
            border-radius: 5px;
            box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);
        }
        .input-field i.prefix { font-size: 2rem; color: #757575; }
        .input-field input:focus + i.prefix { color: #3f51b5; }
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
    </style>
</head>
<body>
    <div class="container">
        <div class="row reg-container">
            <div class="col s12 m8 offset-m2 l6 offset-l3 card-panel z-depth-3">
                <center>
                    <h5>REGISTRASI ADMIN</h5>
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
                            <button class="btn xxy col s12 indigo darken-1 waves-effect waves-light" name="register_admin">Daftar Admin</button>
                        </div>
                        <div class="col s12 login-link">
                            Sudah punya akun admin? <a href="index.php">Login di sini</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../materialize/js/materialize.min.js"></script>
</body>
</html>
<?php mysqli_close($conn); ?>
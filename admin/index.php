<?php
session_start();

$error = "";

include '../koneksi/koneksi.php'; // Pastikan path ke file koneksi.php benar

if (isset($_POST['login'])) {
    // Sanitasi input username dan password
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password_input = $_POST['password']; // Ambil password input

    // Gunakan Prepared Statement untuk mengambil password dari database
    $query = "SELECT password FROM admin WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_array($result);
            // Verifikasi password dengan hash yang tersimpan di database
            if (password_verify($password_input, $data['password'])) {
                $_SESSION['admin'] = $username;
                header("location:dashboard");
                exit;
            } else {
                $error = "Password salah!"; // Pesan error lebih spesifik
            }
        } else {
            $error = "Username tidak ditemukan!"; // Pesan error lebih spesifik
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = "Terjadi kesalahan sistem. Silakan coba lagi.";
        // Untuk debugging, Anda bisa menambahkan: error_log("Admin login prepare failed: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <link rel="stylesheet" href="../materialize/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">

    <style>
        body {
            background-color: #f5f5f5;
        }
        .log {
            margin-top: 50px;
        }

        .card-panel {
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        h5 {
            color: #3f51b5;
            margin-bottom: 20px;
        }

        hr {
            border: 0;
            height: 1px;
            background-color: #e0e0e0;
            margin-bottom: 20px;
        }

        /* Materialize input styling */
        .input-field input[type=text]:not(.browser-default):focus + label,
        .input-field input[type=password]:not(.browser-default):focus + label {
            color: #3f51b5 !important;
        }

        .input-field input[type=text]:not(.browser-default):focus,
        .input-field input[type=password]:not(.browser-default):focus {
            border-bottom: 1px solid #3f51b5 !important;
            box-shadow: 0 1px 0 0 #3f51b5 !important;
        }

        .btn {
            border-radius: 5px;
            box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);
        }

        /* Gaya untuk ikon Material Design */
        .input-field i.prefix {
            font-size: 2rem;
            color: #757575;
        }
        
        .input-field input:focus + i.prefix {
            color: #3f51b5;
        }

        /* Perbaikan untuk link tombol Login Pasien */
        .btn.login-pasien {
            margin-top: 20px;
            width: 100%;
        }

        .input-field {
            margin-bottom: 20px; /* Tambah sedikit jarak antar input field */
        }

        /* Gaya baru untuk link "Belum Memiliki Akun? Daftar" */
        .register-link {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9em;
        }
        .register-link a {
            color: #3f51b5; /* Warna biru untuk link "Daftar" */
            font-weight: bold;
            text-decoration: none; /* Opsional: hilangkan underline */
        }
        .register-link a:hover {
            text-decoration: underline; /* Opsional: tambahkan underline saat hover */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row log">
            <div class="col s12 m6 offset-m3 l4 offset-l4 card-panel z-depth-3">

                <center>
                    <h5>LOGIN ADMIN</h5>
                </center>
                <center>
                    <h5><?php echo "<font color='red'>$error</font>"; ?></h5> </center>
                <hr>

                <form method="post" action="">
                    <div class="row">
                        <div class="input-field col s12">
                            <i class="mdi mdi-account-outline prefix"></i>
                            <input id="username" type="text" class="validate" name="username" required>
                            <label for="username">USERNAME</label>
                        </div>

                        <div class="input-field col s12">
                            <i class="mdi mdi-lock-outline prefix"></i>
                            <input id="password" type="password" class="validate" name="password" required>
                            <label for="password">PASSWORD</label>
                        </div>

                        <div class="input-field col s12">
                            <button class="btn xxy col s12 indigo darken-1 waves-effect waves-light" name="login">Masuk</button>
                        </div>

                        <div class="col s12 register-link">
                            Belum Memiliki Akun? <a href="../registrasiadmin.php">Daftar</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col s12 m6 offset-m3 l4 offset-l4 card-panel z-depth-3">
                <center>
                    <a class="waves-effect waves-light btn indigo darken-1 login-pasien" href="../index.php">Login Pasien</a>
                </center>
            </div>
        </div>
    </div>
    <script src="../materialize/js/materialize.min.js"></script>
    <script>
        // Inisialisasi Materialize components jika diperlukan (untuk form, tidak wajib)
        // M.AutoInit();
    </script>
</body>

</html>
<?php mysqli_close($conn); ?>
<?php
include 'koneksi.php';

$err = "";

if (isset($_POST['login'])) {

    foreach ($_POST as $key => $value) {
        ${$key} = $value;
    }

    $query = mysqli_query($conn, "SELECT nim from pelanggan where nim='$nim'");

    if ($query) {

        if (mysqli_num_rows($query) > 0) {
            # code...
            $data  = mysqli_fetch_array($query);
            if (password_verify($password, $data['password'])) {
                echo $_SESSION['role'] = $nim;
                //header("location:auth.php");
                exit;
            } else echo "<script>alert('Password dan Email Salah!')</script>";
        } else echo "<script>alert('Password dan Email Salah!')</script>";
    } else echo "<script>alert('Password dan Email Salah!')</script>";
}


<?php
// Pastikan sesi sudah dimulai di awal setiap halaman
if (session_status() == PHP_SESSION_NONE) {
   ;
}

include '../auth.php'; // Asumsi ini menangani proses login dan mengatur $_SESSION['role']
include "../koneksi/koneksi.php";

// Inisialisasi variabel untuk ID jamaah dari sesi.
// Penting: Inisialisasi variabel dengan nilai default untuk menghindari 'Undefined variable'
$id_jamaah_dari_sesi = null;

// Periksa apakah ID jamaah ada di sesi
if (isset($_SESSION['role']) && !empty($_SESSION['role'])) {
    $id_jamaah_dari_sesi = $_SESSION['role'];
} else {
    // Jika tidak ada ID di sesi, user belum login atau sesi tidak valid.
    // Arahkan kembali ke halaman login.
    header("Location: ../login.php");
    exit();
}

// Inisialisasi variabel $data sebagai array kosong untuk menghindari warning 'Undefined variable $data'
$data = [];

// --- BAGIAN PENTING UNTUK MENGAMBIL DATA JAMAAH DENGAN AMAN ---
// Gunakan Prepared Statement untuk mencegah SQL Injection!
// Asumsi: 'id_jamaah' adalah nama kolom PRIMARY KEY di tabel 'jamaah'
$stmt = mysqli_prepare($conn, "SELECT * FROM pasien WHERE id_pasien = ?");

if ($stmt) {
    // 'i' berarti parameter adalah integer (sesuai untuk ID)
    mysqli_stmt_bind_param($stmt, "i", $id_jamaah_dari_sesi);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_array($result, MYSQLI_ASSOC); // Ambil data sebagai array asosiatif
    } else {
        // Log error jika data jamaah tidak ditemukan untuk ID yang ada di sesi
        error_log("Data jamaah dengan ID: " . $id_jamaah_dari_sesi . " tidak ditemukan di database.");
        // Opsional: Anda bisa mengarahkan user ke halaman logout atau error
        // header("Location: ../logout.php");
        // exit();
    }
    mysqli_stmt_close($stmt); // Penting: Tutup statement setelah selesai
} else {
    // Handle error jika prepared statement gagal dibuat
    error_log("Error preparing statement: " . mysqli_error($conn));
}
// --- AKHIR BAGIAN PENTING ---


// Sisa kode untuk mengatur active menu dan include file
$active1 = "";
$active2 = "";
$active3 = "";
$active4 = "";
$active5 = "";
$active6 = "";
$includeVal = "include/home.php"; // Default page jika tidak ada $_GET['page']

if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case '1':
            $includeVal = "include/home.php";
            $active1 = "active";
            break;
        case '2':
            $includeVal = "include/data_mhs.php"; // Ini mungkin halaman untuk menampilkan paket umroh, bukan data_mhs.php
            $active2 = "active";
            break;
        case '3':
            $includeVal = "include/profil.php"; // Untuk ubah password
            $active3 = "active";
            break;
        case '4':
            $includeVal = "include/tambah_pembayaran.php"; // Untuk kelola pembayaran
            $active4 = "active";
            break;
        case '5':
            $includeVal = "include/edit_data_mhs.php"; // Jika page 5 untuk tambah paket umroh
            $active5 = "active";
            break;
        case '6':
            $includeVal = "include/cetak_kwitansi.php"; // Contoh untuk cetak kwitansi
            $active6 = "active";
            break;
         case '7':
            $includeVal = "include/pembayaran.php"; // Contoh untuk cetak kwitansi
            $active7 = "active";
            break; 
        case '8':
            $includeVal = "include/khs.php"; // Contoh untuk cetak kwitansi
            $active8 = "active";
            break; 
        default:
            $active1 = "active"; // Jika page tidak dikenali, kembali ke home
            $includeVal = "include/home.php";
            break;
    }
} else {
    $active1 = "active";
    $includeVal = "include/home.php";
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pasien MD Calls</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="../../plugins/datatables/jquery.dataTables.css">
    <link rel="stylesheet" href="../plugins/iCheck/flat/blue.css">
    <link rel="stylesheet" href="../plugins/morris/morris.css">
    <link rel="stylesheet" href="../plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <link rel="stylesheet" href="../plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker-bs3.css">
    <link rel="stylesheet" href="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <link rel="stylesheet" href="../../plugins/CLNDR-master/clndr.min.js">
    <script src="../../plugins/jQuery/jQuery-2.1.4.min.js"></script>

    
     <style>
        #alert-box {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color:  #dd4b39;
            border: 1px solid #ddd;
            padding: 20px;
            z-index: 9999;
        }

        #dokter {
        background-color: #dd4b39 ;
            border: 1px solid #ccc;
            padding: 5px;
            color: white;
            font-family: Arial, sans-serif;
        }

        .alert {
            margin-bottom: 0px;
        }

        .skin-blue .main-header .navbar {
            background-color: #dd4b39;
        }

        .skin-blue .main-header .logo {
            border-bottom: 0 #dd4b39;
        }

        .skin-blue .sidebar-menu>li:hover>a,
        .skin-blue .sidebar-menu>li.active>a {
            color: #fff;

        }

        .skin-blue .wrapper,
        .skin-blue .main-sidebar,
        .skin-blue .left-side {
            background-color: #dd4b39;
        }

        .skin-blue .sidebar a {
            color:rgb(0, 0, 0);
        }

        .skin-blue .sidebar-menu>li.header {
            color: #8B0000;
            background: white;
        }

        .skin-blue .sidebar-menu>li>a {
            border-left: 0px solid transparent;
        }

        .user-panel>.info {
            padding: 15px 5px 5px 15px;
            line-height: 1;
            position: absolute;
            left: 55px;
        }

        table.dataTable tr {
            background-color: #dd4b39;
        }

        table.dataTable tr:nth-child(even) {
            background-color:#dd4b39;
            color:rgb(0, 0, 0);
        }

        table.dataTable tr:nth-child(even) a {
            color:rgb(0, 0, 0) ;
        }

        table.dataTable tr:nth-child(even) a:hover {
            color:#dd4b39;
        }
    </style>

</head>

<body class="hold-transition skin-red sidebar-mini">
    <div class="wrapper">

        <header class="main-header">
            <a href="index2.html" class="logo">
                <span class="logo-lg"><b>Pasien Rumah Sakit</b></span>
            </a>
            <nav class="navbar navbar-static-top" role="navigation">

            </nav>
        </header>


        <aside class="main-sidebar">
            <section class="sidebar">
                <div class="user-panel">
                  
                </div>


                <ul class="sidebar-menu">
                    <li class="header"><b>MENU</b></li>
                    <li class="<?php echo $active1; ?> treeview">
                        <a href="index.php?page=1">
                            <i class="fa fa-dashboard"></i> <span>HOME</span></i>
                        </a>
                    </li>

                    <li class="<?php echo $active2; ?> treeview">
                        <a href="index.php?page=2">
                            <i class="fa fa-phone"></i>
                            <span> Data Diri</span>
                        </a>
                    </li>
                    <li class="<?php echo $active5; ?> treeview">
                        <a href="index.php?page=5">
                            <i class="fa fa-phone"></i>
                            <span> Cek Jadwal Dokter </span>
                        </a>
                    </li>

                    <li class="<?php echo $active4; ?> treeview">
                        <a href="index.php?page=7">
                            <i class="fa fa-phone"></i>
                            <span> Daftar Berobat</span>
                        </a>
                    </li>

                    <li class="<?php echo $active3; ?> treeview">
                        <a href="index.php?page=3">
                            <i class="fa fa-user"></i> <span>Ubah Password</span>
                        </a>
                    </li>

                    <li class="treeview">
                        <a href="../logout.php">
                            <i class="fa fa-lock"></i> <span>Logout</span>
                        </a>
                    </li>

                </ul>
            </section>
            </aside>

        <div class="content-wrapper">


            <section class="content">
                <div class="row">

                </div><div class="row">
                    <section class="col-lg-12 connectedSortable">
                        <?php

                        // Memastikan $includeVal sudah diatur sebelum di-include
                        include "$includeVal";

                        ?>

                    </section><section class="col-lg-5 connectedSortable">

                    </section></div></section></div><script src="../plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
        <script>
            $.widget.bridge('uibutton', $.ui.button);
        </script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <script src="../../plugins/datatables/jquery.dataTables.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="../plugins/morris/morris.min.js"></script>
        <script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
        <script src="../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="../plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
        <script src="../plugins/knob/jquery.knob.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
        <script src="../plugins/daterangepicker/daterangepicker.js"></script>
        <script src="../plugins/datepicker/bootstrap-datepicker.js"></script>
        <script src="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
        <script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <script src="../plugins/fastclick/fastclick.min.js"></script>
        <script src="../dist/js/app.min.js"></script>
        <script src="../dist/js/pages/dashboard.js"></script>
        <script src="../dist/js/demo.js"></script>
</body>

</html>
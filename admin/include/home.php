<?php
// --- START: Kode Koneksi Database ---
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "K3";

$conn = mysqli_connect($servername, $username_db, $password_db, $dbname);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
// --- END: Kode Koneksi Database ---

$loggedInUsername = "Salwa"; // Default nama admin

if (isset($_SESSION['username'])) {
    $loggedInUsername = $_SESSION['username'];
}

// Data dummy untuk placeholder statistik
$total_pasien = 1250; // Placeholder
$kunjungan_hari_ini = 15; // Placeholder
$total_dokter = 25; // Placeholder
$notifikasi_baru = 3; // Placeholder

/*
// --- Contoh Query PHP untuk Mengambil Data Statistik dari Database (UNCOMMENT DAN SESUAIKAN) ---
// Total Pasien
$query_total_pasien = "SELECT COUNT(*) AS total FROM pasien";
$result_total_pasien = mysqli_query($conn, $query_total_pasien);
if ($result_total_pasien) {
    $data_total_pasien = mysqli_fetch_assoc($result_total_pasien);
    $total_pasien = $data_total_pasien['total'];
}

// Kunjungan Hari Ini
$today = date("Y-m-d");
$query_kunjungan_today = "SELECT COUNT(*) AS total FROM kunjungan WHERE tanggal_kunjungan = '$today'";
$result_kunjungan_today = mysqli_query($conn, $query_kunjungan_today);
if ($result_kunjungan_today) {
    $data_kunjungan_today = mysqli_fetch_assoc($result_kunjungan_today);
    $kunjungan_hari_ini = $data_kunjungan_today['total'];
}

// Total Dokter
$query_total_dokter = "SELECT COUNT(*) AS total FROM dokter";
$result_total_dokter = mysqli_query($conn, $query_total_dokter);
if ($result_total_dokter) {
    $data_total_dokter = mysqli_fetch_assoc($result_total_dokter);
    $total_dokter = $data_total_dokter['total'];
}

// Notifikasi Baru (contoh, sesuaikan query Anda)
// $query_notifikasi = "SELECT COUNT(*) AS total FROM notifikasi WHERE status = 'unread'";
// $result_notifikasi = mysqli_query($conn, $query_notifikasi);
// if ($result_notifikasi) {
//     $data_notifikasi = mysqli_fetch_assoc($result_notifikasi);
//     $notifikasi_baru = $data_notifikasi['total'];
// }
*/

// --- DATA UNTUK GRAFIK (Contoh & Placeholder PHP) ---
// !!! PENTING: UNCOMMENT DAN SESUAIKAN QUERY INI UNTUK MENGAMBIL DATA AKTUAL DARI DATABASE ANDA !!!

// Grafik Kunjungan Pasien (per bulan)
$labels_kunjungan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
$data_kunjungan = [20, 35, 45, 30, 50, 40]; // Contoh Data
/*
$data_kunjungan_db = [];
$labels_kunjungan_db = [];
// Asumsi ada tabel 'kunjungan' dengan kolom 'tanggal_kunjungan'
for ($i = 5; $i >= 0; $i--) { // Dari 5 bulan lalu hingga bulan ini
    $month = date('Y-m', strtotime("-$i months"));
    $query_monthly_visits = "SELECT COUNT(*) AS total FROM kunjungan WHERE DATE_FORMAT(tanggal_kunjungan, '%Y-%m') = '$month'";
    $result_monthly_visits = mysqli_query($conn, $query_monthly_visits);
    $row = mysqli_fetch_assoc($result_monthly_visits);
    $data_kunjungan_db[] = $row['total'];
    $labels_kunjungan_db[] = date('M', strtotime($month));
}
$labels_kunjungan = $labels_kunjungan_db;
$data_kunjungan = $data_kunjungan_db;
*/


// Grafik Dokter (berdasarkan spesialisasi)
$labels_dokter = ['Bedah', 'Jantung dan Paru Paru', 'Penyakit Dalam'];
$data_dokter = [10, 5, 7,]; // Contoh Data


// Grafik Jadwal Dokter (jumlah janji temu per dokter paling sibuk)
$labels_jadwal_dokter = ['dr. Salwa', 'dr. Budi', 'dr. Cinta'];
$data_jadwal_dokter = [25, 18, 22]; // Contoh Data

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin MD Calls</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        /* Custom CSS untuk Dashboard */
        body {
            /* background-color: #f4f6f9; */ /* Jika Anda sudah punya latar belakang global, bisa dihapus atau disesuaikan */
        }

        /* CSS untuk Card Dashboard */
        .card-dashboard {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            padding: 20px;
            text-align: center;
        }
        .card-dashboard h4 {
            margin-top: 0;
            color: #333;
        }
        .card-dashboard .value {
            font-size: 3em;
            font-weight: bold;
            color: #007bff; /* Warna biru untuk nilai */
            margin-bottom: 10px;
        }
        .card-dashboard.green .value { color: #28a745; } /* Hijau untuk Kunjungan */
        .card-dashboard.red .value { color: #dc3545; }   /* Merah untuk Notifikasi */

        /* CSS untuk Tombol Akses Cepat */
        .quick-actions .btn {
            margin: 10px;
            padding: 15px 30px;
            font-size: 1.2em;
            border-radius: 5px;
        }

        /* CSS untuk bagian konten utama, sesuaikan dengan layout Anda */
        #page-content-wrapper {
            padding: 20px; /* Atur padding sesuai kebutuhan layout Anda */
            /* Jika Anda memiliki sidebar, Anda mungkin perlu margin-left atau padding-left */
            /* Contoh: margin-left: 250px; jika sidebar Anda 250px */
        }

        /* Styling untuk area grafik */
        .chart-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            padding: 20px;
            /* --- Modifikasi Ini: Tambahkan tinggi maksimal dan posisi relatif --- */
            position: relative;
            height: 350px; /* Anda bisa menyesuaikan nilai ini sesuai keinginan */
            /* --- Akhir Modifikasi --- */
        }
        .chart-container h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        /* --- Tambahan Ini: Aturan untuk Canvas di dalam Kontainer Grafik --- */
        .chart-container canvas {
            max-width: 100%; /* Pastikan kanvas tidak melebihi lebar kontainer */
            max-height: 100%; /* Pastikan kanvas tidak melebihi tinggi kontainer */
            display: block; /* Membantu menghilangkan spasi ekstra di bawah kanvas */
        }
        /* --- Akhir Tambahan --- */

        /* Responsive adjustments */
        @media (max-width: 768px) {
            /* Sesuaikan media query ini agar sesuai dengan breakpoint layout Anda */
            .quick-actions .btn {
                width: 100%; /* Tombol akses cepat menjadi full width di layar kecil */
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>

<div id="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1>Home</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                </ol>

                <hr>

                <div class="text-center" style="margin-bottom: 30px;">
                    <h5><b><?php echo date("l, d M Y"); ?></b></h5>
                    <h2><b>Selamat Datang Admin MD Calls </b></h2>
                    <p class="text-muted">Ini adalah ringkasan aktivitas dan akses cepat untuk tugas-tugas administratif.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card-dashboard">
                    <h4><i class="fa fa-users"></i> Total Pasien</h4>
                    <div class="value">
                        <?php echo htmlspecialchars($total_pasien); ?>
                    </div>
                    <small>Pasien terdaftar</small>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card-dashboard green">
                    <h4><i class="fa fa-calendar-plus-o"></i> Kunjungan Hari Ini</h4>
                    <div class="value">
                        <?php echo htmlspecialchars($kunjungan_hari_ini); ?>
                    </div>
                    <small>Janji temu hari ini</small>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card-dashboard">
                    <h4><i class="fa fa-user-md"></i> Total Dokter</h4>
                    <div class="value">
                        <?php echo htmlspecialchars($total_dokter); ?>
                    </div>
                    <small>Dokter yang tersedia</small>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card-dashboard red">
                    <h4><i class="fa fa-exclamation-triangle"></i> Notifikasi Baru</h4>
                    <div class="value">
                        <?php echo htmlspecialchars($notifikasi_baru); ?>
                    </div>
                    <small>Perlu perhatian</small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                   
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="chart-container">
                    <h3>Grafik Kunjungan Pasien</h3>
                    <canvas id="kunjunganPasienChart"></canvas>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="chart-container">
                    <h3>Grafik Dokter Berdasarkan Spesialisasi</h3>
                    <canvas id="dokterSpesialisasiChart"></canvas>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="chart-container">
                    <h3>Grafik Jadwal Dokter (Janji Temu Terbanyak)</h3>
                    <canvas id="jadwalDokterChart"></canvas>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-history"></i> Aktivitas Terbaru</h3>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            <li class="list-group-item"><strong><?php echo date("d M Y, H:i"); ?>:</strong> Pasien "Salwa" berhasil didaftarkan.</li>
                            <li class="list-group-item"><strong><?php echo date("d M Y, H:i", strtotime("-1 hour")); ?>:</strong> Jadwal Dokter Budi diperbarui.</li>
                            <li class="list-group-item"><strong><?php echo date("d M Y, H:i", strtotime("-1 day")); ?>:</strong> Laporan kunjungan harian dicetak.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // Script SweetAlert untuk "Selamat Datang!"
        // Catatan: Jika ada SweetAlert lain dari kode Anda yang menyebabkan konflik,
        // pertimbangkan untuk menggabungkan atau menghapus salah satunya.
        setTimeout(function() {
            Swal.fire({
                title: 'Selamat Datang!',
                text: 'Admin MD Calls!',
                icon: 'success',
                confirmButtonText: 'Oke'
            });
        }, 500); // Penundaan 500ms agar tidak terlalu cepat muncul setelah halaman load

        // --- Data PHP untuk JavaScript ---
        // Variabel-variabel ini di-encode dari PHP ke JSON di server-side.
        // Pastikan Anda sudah meng-uncomment dan menyesuaikan query PHP di bagian atas file ini
        // agar data yang dihasilkan valid dan sesuai dengan database Anda.
        var labelsKunjungan = <?php echo json_encode($labels_kunjungan); ?>;
        var dataKunjungan = <?php echo json_encode($data_kunjungan); ?>;

        var labelsDokter = <?php echo json_encode($labels_dokter); ?>;
        var dataDokter = <?php echo json_encode($data_dokter); ?>;

        var labelsJadwalDokter = <?php echo json_encode($labels_jadwal_dokter); ?>;
        var dataJadwalDokter = <?php echo json_encode($data_jadwal_dokter); ?>;

        // --- Inisialisasi Grafik Chart.js ---

        // Grafik Kunjungan Pasien (Line Chart)
        var ctxKunjungan = document.getElementById('kunjunganPasienChart').getContext('2d');
        new Chart(ctxKunjungan, {
            type: 'line',
            data: {
                labels: labelsKunjungan,
                datasets: [{
                    label: 'Jumlah Kunjungan',
                    data: dataKunjungan,
                    backgroundColor: 'rgba(0, 123, 255, 0.2)', // Biru muda
                    borderColor: 'rgba(0, 123, 255, 1)',      // Biru
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Grafik Dokter Berdasarkan Spesialisasi (Doughnut Chart)
        var ctxDokter = document.getElementById('dokterSpesialisasiChart').getContext('2d');
        new Chart(ctxDokter, {
            type: 'doughnut',
            data: {
                labels: labelsDokter,
                datasets: [{
                    label: 'Jumlah Dokter',
                    data: dataDokter,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)', // Merah
                        'rgba(54, 162, 235, 0.7)', // Biru
                        'rgba(255, 206, 86, 0.7)', // Kuning
                        'rgba(153, 102, 255, 0.7)' // Ungu
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: false,
                    }
                }
            }
        });

        // Grafik Jadwal Dokter (Bar Chart)
        var ctxJadwal = document.getElementById('jadwalDokterChart').getContext('2d');
        new Chart(ctxJadwal, {
            type: 'bar',
            data: {
                labels: labelsJadwalDokter,
                datasets: [{
                    label: 'Jumlah Janji Temu',
                    data: dataJadwalDokter,
                    backgroundColor: 'rgba(40, 167, 69, 0.7)', // Hijau
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>

</body>
</html>
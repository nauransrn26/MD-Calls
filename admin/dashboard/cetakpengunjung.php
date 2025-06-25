<!DOCTYPE html>
<html>
<head>
    <title>LAPORAN DATA PASIEN</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
    <style>
        .report-footer {
            margin-top: 50px;
            text-align: right;
            font-size: 12px;
        }
        .report-footer p {
            margin: 5px 0;
        }
        /* Style untuk hari dan tanggal di bawah judul */
        .report-date-header {
            text-align: center;
            margin-top: -10px; /* Sesuaikan jarak dari judul */
            margin-bottom: 20px; /* Jarak ke tabel */
            font-size: 14px;
        }
    </style>
</head>
<body onload="print()">
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="frmedit">
    <?php
    // Memulai sesi (jika diperlukan)
    session_start();

    // Memasukkan file koneksi database (asumsi nama filenya koneksi.php)
    require_once('../../koneksi/koneksi.php');

    // Penanganan kesalahan koneksi
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    // Mengambil nama admin dari database (jika diperlukan untuk tanda tangan)
    // Dalam contoh ini, karena Anda sudah menetapkan 'Salwa', kita tidak perlu query lagi
    // $nama_admin = "Nama Admin Tidak Ditemukan";
    // $id_admin_yang_dicari = 1; // Sesuaikan ID admin yang ingin Anda tampilkan
    // $sql_admin = "SELECT nama FROM admin WHERE id_admin = $id_admin_yang_dicari";
    // $result_admin = mysqli_query($conn, $sql_admin);
    // if ($result_admin && mysqli_num_rows($result_admin) > 0) {
    //     $data_admin = mysqli_fetch_assoc($result_admin);
    //     $nama_admin = $data_admin['nama'];
    // }

    // --- Mengambil Data Pasien ---
    $no = 0;
    $sql = "SELECT * FROM pasien";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo '<h2><center>Laporan Data Pasien</center></h2>';
        echo '<p><center>Rumah Sakit MD Calls</center></p>';
        echo '<p class="report-date-header"> ' . date('l, d F Y') . '</p>';
        echo '<table id="table_id" class="table table-striped table-bordered" cellspacing="0" width="100%">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Id Pasien</th>';
        echo '<th>Username</th>';
        echo '<th>Nama</th>';
        echo '<th>Email</th>';
        echo '<th>Jenis Kelamin</th>';
        echo '<th>Tempat Lahir</th>';
        echo '<th>Tanggal Lahir</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($data = mysqli_fetch_array($result)) {
            $no++;
            echo '<tr>';
            echo '<td>' . $data[0] . '</td>';
            echo '<td>' . $data[1] . '</td>';
            echo '<td>' . $data[2] . '</td>';
            echo '<td>' . $data[3] . '</td>';
            echo '<td>' . $data[5] . '</td>';
            echo '<td>' . $data[6] . '</td>';
            echo '<td>' . $data[7] . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<h2>Tidak ada data ditemukan!</h2>';
    }

    // Menutup koneksi database
    mysqli_close($conn);
    ?>

    <div class="report-footer">
        <p>Pekanbaru, <?php echo date('d F Y'); ?></p>
        <p>Penanggung Jawab Laporan,</p>
        <br><br><br>
        <p>Salwa</p> <p>_________________________</p>
    </div>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#table_id').DataTable();
        });
    </script>
</form>
</body>
</html>
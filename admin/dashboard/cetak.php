<!DOCTYPE html>
<html>
<head>
    <title>KARTU BEROBAT</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
    <style>
        /* Styling untuk info pasien */
        .patient-info {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        .patient-info p {
            margin: 0 0 5px 0;
        }
        .patient-info strong {
            display: inline-block;
            width: 120px; /* Lebar tetap untuk label */
        }

        /* Styling untuk footer laporan (tanda tangan) */
        .report-footer {
            margin-top: 30px;
            text-align: left; /* Mengatur rata kiri untuk seluruh footer */
        }
        .report-footer p {
            margin: 0; /* Menghilangkan margin default paragraf */
            line-height: 1.5; /* Spasi antar baris */
        }

        /* Optional: Sembunyikan elemen yang tidak diperlukan untuk dicetak */
        @media print {
            .no-print {
                display: none;
            }
            .patient-info {
                border: none; /* Hilangkan border saat dicetak jika tidak diinginkan */
                background-color: transparent;
                padding: 0;
            }
        }
    </style>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="frmedit">
    <?php
    // --- MULAI: Kode Koneksi Database ---
    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "k3"; // Pastikan nama database benar

    // Buat koneksi
    $conn = mysqli_connect($servername, $username_db, $password_db, $dbname);

    // Periksa koneksi
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }
    // --- AKHIR: Kode Koneksi Database ---

    $id_pasien_to_display = '';
    // Logika untuk menentukan id_pasien yang akan ditampilkan
    if (isset($_GET['id_pasien']) && !empty($_GET['id_pasien'])) {
        $id_pasien_to_display = mysqli_real_escape_string($conn, $_GET['id_pasien']);
    } else {
        // Jika tidak ada id_pasien di URL, coba ambil id_pasien dari kunjungan terakhir (sebagai fallback)
        $query_latest_kunjungan = "SELECT id_pasien FROM kunjungan ORDER BY tanggal_kunjungan DESC, waktu_kunjungan DESC LIMIT 1";
        $result_latest_kunjungan = mysqli_query($conn, $query_latest_kunjungan);
        if ($result_latest_kunjungan && mysqli_num_rows($result_latest_kunjungan) > 0) {
            $row_latest_kunjungan = mysqli_fetch_assoc($result_latest_kunjungan);
            $id_pasien_to_display = $row_latest_kunjungan['id_pasien'];
        }
    }

    $patient_data = null;
    if (!empty($id_pasien_to_display)) {
        // Query untuk mengambil data pasien (kecuali password)
        // Kolom id di tabel pasien adalah id_pasien (berdasarkan image_b6524a.png)
        $sql_pasien = "SELECT id_pasien, username, nama, email, jenis_kelamin, tempat_lahir, tanggal_lahir FROM pasien WHERE id_pasien = ?";
        $stmt_pasien = mysqli_prepare($conn, $sql_pasien);
        if ($stmt_pasien) {
            mysqli_stmt_bind_param($stmt_pasien, "s", $id_pasien_to_display);
            mysqli_stmt_execute($stmt_pasien);
            $result_pasien = mysqli_stmt_get_result($stmt_pasien);
            $patient_data = mysqli_fetch_assoc($result_pasien);
            mysqli_stmt_close($stmt_pasien);
        }
    }
    ?>

    <h2><center>KARTU BEROBAT<center></h2>

    <?php if ($patient_data) : ?>
        <div class="patient-info">
            <h4>Data Pasien</h4>
            <p><strong>ID Pasien:</strong> <?php echo htmlspecialchars($patient_data['id_pasien']); ?></p>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($patient_data['username']); ?></p>
            <p><strong>Nama Pasien:</strong> <?php echo htmlspecialchars($patient_data['nama']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($patient_data['email']); ?></p>
            <p><strong>Jenis Kelamin:</strong> <?php echo htmlspecialchars($patient_data['jenis_kelamin']); ?></p>
            <p><strong>Tempat Lahir:</strong> <?php echo htmlspecialchars($patient_data['tempat_lahir']); ?></p>
            <p><strong>Tanggal Lahir:</strong> <?php echo htmlspecialchars($patient_data['tanggal_lahir']); ?></p>
        </div>
    <?php else : ?>
        <p>Data pasien tidak ditemukan untuk ID: <?php echo htmlspecialchars($id_pasien_to_display); ?>. Pastikan ID Pasien sudah benar atau data pasien tersedia.</p>
    <?php endif; ?>

    <hr> <h4>Pendaftaran Berobat</h4>
    <?php
    $no = 0;
    // Query SQL untuk mengambil kunjungan berdasarkan id_pasien yang dipilih
    $sql_kunjungan = "SELECT id_kunjungan, id_pasien, id_dokter, tanggal_kunjungan, waktu_kunjungan, keluhan
                      FROM kunjungan
                      WHERE id_pasien = ?
                      ORDER BY tanggal_kunjungan DESC, waktu_kunjungan DESC";
    $stmt_kunjungan = mysqli_prepare($conn, $sql_kunjungan);

    if ($stmt_kunjungan) {
        mysqli_stmt_bind_param($stmt_kunjungan, "s", $id_pasien_to_display);
        mysqli_stmt_execute($stmt_kunjungan);
        $result_kunjungan = mysqli_stmt_get_result($stmt_kunjungan);

        if (mysqli_num_rows($result_kunjungan) > 0) {
            echo '<table id="table_id" class="table table-striped table-bordered" cellspacing="0" width="100%">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>ID Kunjungan</th>';
            echo '<th>ID Pasien</th>';
            echo '<th>ID Dokter</th>';
            echo '<th>Tanggal Kunjungan</th>';
            echo '<th>Waktu Kunjungan</th>';
            echo '<th>Keluhan</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($data_kunjungan = mysqli_fetch_array($result_kunjungan)) {
                $no++;
                echo '<tr>';
                echo '<td>' . htmlspecialchars($data_kunjungan['id_kunjungan']) . '</td>';
                echo '<td>' . htmlspecialchars($data_kunjungan['id_pasien']) . '</td>';
                echo '<td>' . htmlspecialchars($data_kunjungan['id_dokter']) . '</td>';
                echo '<td>' . htmlspecialchars($data_kunjungan['tanggal_kunjungan']) . '</td>';
                echo '<td>' . htmlspecialchars($data_kunjungan['waktu_kunjungan']) . '</td>';
                echo '<td>' . htmlspecialchars($data_kunjungan['keluhan']) . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>Tidak ada riwayat kunjungan ditemukan untuk pasien ini.</p>';
        }
        mysqli_stmt_close($stmt_kunjungan);
    } else {
        echo '<p>Error saat menyiapkan query kunjungan: ' . mysqli_error($conn) . '</p>';
    }

    // Tutup koneksi
    mysqli_close($conn);
    ?>

    <div class="report-footer">
        <p>Pekanbaru, <?php echo date('d F Y'); ?></p>
        <p>Penanggung Jawab Laporan,</p>
        <br><br><br>
        <p>Salwa</p>
        <p>_________________________</p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button id="print-button" class="btn btn-primary">Cetak Kartu Berobat</button>
    </div>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#table_id').DataTable({
                "paging": false,    // Nonaktifkan paginasi untuk laporan cetak
                "searching": false, // Nonaktifkan pencarian
                "info": false,      // Nonaktifkan teks informasi
                "ordering": false   // Nonaktifkan pengurutan
            });

            $("#print-button").click(function() {
                window.print(); // Memicu dialog cetak browser
            });
        });
    </script>
</form>
</body>
</html>
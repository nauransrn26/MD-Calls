<?php
// Pastikan sesi dimulai di awal file
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Tidak perlu lagi include CSS/JS di sini, karena sudah di handle oleh index.php pasien
// HANYA SERTAKAN KONTEN HTML DAN SCRIPT INISIALISASI DATATABLES
?>

<section class="content-header">
    <h1><b>Jadwal Dokter</b></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"><i class="fa fa-user"></i>Jadwal Dokter</a></li>
    </ol>
</section>

<section class="content">
    <h5><b><?php echo date("l, M Y"); ?></b></h5>
    <hr>

    <h5><b>Jadwal Dokter</b></h5>
    <p>Berikut ini adalah Dokter yang bertugas hari ini:</p>

    <div class="box box-info">
        <div class="box-header">
            <i class="fa fa-pencil"></i>
            <h3 class="box-title">Daftar Dokter Hari Ini</h3>
        </div>
        <?php
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] != "") {
            echo $_SESSION['pesan'];
            unset($_SESSION['pesan']);
        }
        ?>
        <div class="box-body">
            <table id="table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th> No </th>
                        <th>Id Dokter</th>
                        <th>Nama Dokter</th>
                        <th>Spesialisasi</th>
                        <th>Telepon</th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    // Pastikan $conn sudah terdefinisi dari index.php pasien
                    $query = mysqli_query($conn, "SELECT * FROM dokter");

                    if ($query) {
                        while ($rows = mysqli_fetch_array($query)) {
                    ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($rows['id_dokter']); ?></td>
                                <td><?php echo htmlspecialchars($rows['nama']); ?></td>
                                <td><?php echo htmlspecialchars($rows['spesialisasi']); ?></td>
                                <td><?php echo htmlspecialchars($rows['telepon']); ?></td>
                                <td>
                                    </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='7'>Error mengambil data dokter: " . mysqli_error($conn) . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <p> </p>
    <h5><b>Jadwal Dokter</b></h5>
    <p>Berikut ini adalah Jadwal Dokter hari ini:</p>

    <div class="box box-info">
        <div class="box-header">
            <i class="fa fa-pencil"></i>
            <h3 class="box-title">Daftar Jadwal Dokter</h3>
        </div>
        <?php
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] != "") {
            echo $_SESSION['pesan'];
            unset($_SESSION['pesan']);
        }
        ?>
        <div class="box-body">
            <table id="tablejadwal" class="table table-bordered table-striped" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Id Jadwal</th>
                        <th>Id Dokter</th>
                        <th>Tanggal</th>
                        <th>Waktu Mulai</th>
                        <th>Waktu Selesai</th>
                        <th>Kuota Maksimal</th>
                        <th>Pendaftar Saat Ini</th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query = mysqli_query($conn, "SELECT * FROM jadwal_dokter");

                    if ($query) {
                        while ($rows = mysqli_fetch_array($query)) {
                    ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($rows['id_jadwal']); ?></td>
                                <td><?php echo htmlspecialchars($rows['id_dokter']); ?></td>
                                <td><?php echo htmlspecialchars($rows['tanggal']); ?></td>
                                <td><?php echo htmlspecialchars($rows['waktu_mulai']); ?></td>
                                <td><?php echo htmlspecialchars($rows['waktu_selesai']); ?></td>
                                <td><?php echo htmlspecialchars($rows['kuota_maks']); ?></td>
                                <td><?php echo htmlspecialchars($rows['pendaftar_saat_ini']); ?></td>
                                <td>
                                    </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='7'>Error mengambil data jadwal: " . mysqli_error($conn) . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('#table').DataTable();
        $('#tablejadwal').DataTable();
    });
</script>
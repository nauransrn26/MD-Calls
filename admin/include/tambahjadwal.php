<?php
// Make sure your database connection ($conn) is established here
// For example:
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "your_database_name";
// $conn = new mysqli($servername, $username, $password, $dbname);
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// Ensure session is started for $_SESSION['pesan']
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<h1> <b>Tambah Jadwal</b>
</h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-user"></i> Tambah Jadwal Manasik & Keberangkatan</a></li>
</ol>

<hr>

<h5><b><?php echo date("l, M Y"); ?></b></h5>
<hr>
<a href="index.php?page=15" class="btn btn-primary btn-lg" style="margin-right:10px;float:right;"><i class="fa fa-arrow-circle-left"></i> Kembali</a>

<h5><b>Tambah Jadwal Manasik & Keberangkatan </b></h5>
<p>Isilah Form dibawah untuk dapat menambah jadwal manasik & keberangkatan:
</p>
<br>

<div class="box box-info">
    <div class="box-header">
        <i class="fa fa-user"></i>
        <h3 class="box-title">Tambah Jadwal Manasik & Keberangkatan</h3>
        <?php
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] != "") {
            echo $_SESSION['pesan'];
            unset($_SESSION['pesan']);
        } else {
            echo "";
        }
        ?>
    </div>

    <?php
    // Logic for auto-generating ID_JADWAL if your database doesn't auto-increment it
    // If your database DOES auto-increment, you can remove this entire PHP block.
    $kode2 = ''; // Initialize $kode2
    $query_id = "select * from jadwalmanasik_keberangkatan order by id_jadwal desc limit 1";
    $baris = mysqli_query($conn, $query_id);
    if ($baris) {
        if (mysqli_num_rows($baris) > 0) {
            $auto = mysqli_fetch_array($baris);
            $kode = $auto['id_jadwal'];
            // Assuming format like "L001", "L010", etc.
            // Adjust substr arguments based on your actual id_jadwal format
            $baru = (int)substr($kode, 1); // Get numeric part after 'L'
            $nol = $baru;
        } else {
            $nol = 0;
        }
        $nol = $nol + 1;
        $nol2 = "";
        $nilai = 3 - strlen($nol); // Assuming ID_JADWAL is L001, L002... up to L999
        for ($i = 1; $i <= $nilai; $i++) {
            $nol2 = $nol2 . "0";
        }
        $kode2 = "L" . $nol2 . $nol; // Example: L001, L002, etc.
    }
    // End of auto-ID generation logic
    ?>

    <div class="box-body">
        <form action="" method="post">
            <div class="form-group">
                <label for="id_jadwal">Id Jadwal</label>
                <input type="text" class="form-control" name="id_jadwal" id="id_jadwal" value="<?php echo htmlspecialchars($kode2); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="id_paket">Id Paket</label>
                <input type="text" class="form-control" name="id_paket" id="id_paket" placeholder="Id Paket" required>
                </div>

            <div class="form-group">
                <label for="tanggal_manasik">Tanggal Manasik</label>
                <input type="date" class="form-control" name="tanggal_manasik" id="tanggal_manasik" required>
            </div>
            <div class="form-group">
                <label for="tanggal_berangkat">Tanggal Berangkat</label>
                <input type="date" class="form-control" name="tanggal_berangkat" id="tanggal_berangkat" required>
            </div>

            </div>

    <div class="box-footer clearfix">
        <button type="submit" class="pull-right btn btn-primary" name="simpan">Simpan <i class="fa fa-arrow-circle-right"></i></button>
        </form>
    </div>
</div>

<?php
// This PHP block handles the form submission
if (isset($_POST['simpan'])) {
    // Sanitize and fetch data from POST
    // Use mysqli_real_escape_string for basic security
    // Always validate and sanitize user input!
    $id_jadwal = isset($_POST['id_jadwal']) ? mysqli_real_escape_string($conn, $_POST['id_jadwal']) : '';
    $id_paket = mysqli_real_escape_string($conn, $_POST['id_paket']);
    $tanggal_manasik = mysqli_real_escape_string($conn, $_POST['tanggal_manasik']);
    $tanggal_berangkat = mysqli_real_escape_string($conn, $_POST['tanggal_berangkat']);

    // --- Debugging: Check received data ---
    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";
    // die("Debugging: Check POST data above.");
    // --- End Debugging ---

    // Validate if fields are empty
    if (empty($id_jadwal) || empty($id_paket) || empty($tanggal_manasik) || empty($tanggal_berangkat)) {
        $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Tidak Boleh Ada Field yang Kosong!
                              </div>";
        echo "<script>window.location = 'index.php?page=15'</script>";
        exit(); // Stop execution
    } else {
        // SQL INSERT Query
        // IMPORTANT: If id_jadwal is AUTO-INCREMENT, remove it from the INSERT query:
        // $query = mysqli_query($conn, "INSERT INTO jadwalmanasik_keberangkatan (id_paket, tanggal_manasik, tanggal_berangkat) VALUES('$id_paket', '$tanggal_manasik', '$tanggal_berangkat')");
        // Otherwise, use it:
        $query = mysqli_query($conn, "INSERT INTO jadwalmanasik_keberangkatan VALUES('$id_jadwal','$id_paket','$tanggal_manasik','$tanggal_berangkat')");

        // --- Debugging: Check SQL Query ---
        // if (!$query) {
        //     echo "SQL Error: " . mysqli_error($conn);
        //     echo "<br>Query: INSERT INTO jadwalmanasik_keberangkatan VALUES('$id_jadwal','$id_paket','$tanggal_manasik','$tanggal_berangkat')";
        //     die(); // Stop execution to show error
        // }
        // --- End Debugging ---

        if ($query) {
            $_SESSION['pesan'] = "<div class='alert alert-success' style='margin-top:5px;'>
                                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                    Berhasil Input Jadwal!
                                  </div>";
            echo "<script>window.location = 'index.php?page=15'</script>";
            exit(); // Stop execution
        } else {
            // Provide more specific error message from MySQL
            $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                    Gagal Input Jadwal! Error: " . mysqli_error($conn) . "
                                  </div>";
            echo "<script>window.location = 'index.php?page=15'</script>";
            exit(); // Stop execution
        }
    }
}
?>
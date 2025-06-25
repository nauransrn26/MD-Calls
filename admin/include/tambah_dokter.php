<h1> <b>Tambah Dokter</b>
</h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-user"></i> Tambah Paket Umroh</a></li>
</ol>

<hr>

<h5><b><?php echo date("l, M Y"); ?></b></h5>
<hr>
<a href="index.php?page=4" class="btn btn-primary btn-lg" style="margin-right:10px;float:right;"><i class="fa fa-arrow-circle-left"></i> Kembali</a>

<h5><b>Tambah Paket Umroh</b></h5>
<p>Isilah Form dibawah untuk dapat menambah paket umroh:
</p>
<br>

<!-- quick email widget -->
<div class="box box-info">
    <div class="box-header">
        <i class="fa fa-user"></i>
        <h3 class="box-title">Tambah Paket Umroh</h3>
        <?php
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] != "") {
            echo $_SESSION['pesan'];
            unset($_SESSION['pesan']);
        } else echo "";
        ?>
    </div>

    <?php
    $query = "select * from paket_umroh order by id_paket desc limit 1";
    $baris = mysqli_query($conn, $query);
    if ($baris) {
        if (mysqli_num_rows($baris) > 0) {
            $auto = mysqli_fetch_array($baris);
            $kode = $auto['id_paket'];
            $baru = substr($kode, 2, 3,);
            $nol = (int)$baru;
        } else {
            $nol = 0;
        }
        $nol = $nol + 1;
        $nol2 = "";
        $nilai = 3 - strlen($nol);
        for ($i = 1; $i <= $nilai; $i++) {
            $nol2 = $nol2 . "0";
        }

        $kode2 = "L" . $nol2 . $nol;
    }
    ?>

    <div class="box-body">
        <form action="" method="post">
            <div class="form-group">
                <label for="">Id Paket</label>
                <input type="text" class="form-control" name="id_paket" id="id_paket" placeholder="id_paket" >
            </div>
            <div class="form-group">
                <label for="">Nama Paket</label>
                <input type="text" class="form-control" name="nama_paket" id="nama_paket" placeholder="Nama Paket" >
            </div>

            <div class="form-group">
                <label for="">Harga</label>
                <input type="text" class="form-control" name="harga" id="harga" placeholder="harga" >
            </div>
            <div class="form-group">
                <label for="">Fasilitas</label>
                <input type="text" class="form-control" name="fasilitas" id="fasilitas" placeholder="fasilitas" >
            </div>
            <div class="form-group">
                <label for="">Deskripsi</label>
                <input type="text" class="form-control" name="deskripsi" id="deskripsi" placeholder="deskripsi">
            </div>
                       <!-- Bagian Kode HTML -->

<!-- Tambahkan Script JavaScript -->
<script>
    document.getElementById('jumlah').addEventListener('input', function() {
        var jumlah = parseFloat(this.value) || 0; // Ambil nilai jumlah
        var hargaPerKilo = 7000; // Harga per kilo tetap 7000
        var hargaTotal = jumlah * harga_per_kilo; // Hitung total
        document.getElementById('harga_total').value = harga_total; // Set nilai total
    });
</script>

    </div>

    <div class="box-footer clearfix">
        <button class="pull-right btn btn-primary" name="simpan">Simpan <i class="fa fa-arrow-circle-right"></i></button>
        </form>
    </div>
</div>

<?php

if (isset($_POST['simpan'])) {
    foreach ($_POST as $key => $value) {
        ${$key} = $value;
    }

    if ($id_paket == ""||$nama_paket == ""||$harga == ""||$fasilitas ==""||$deskripsi == "") {
        $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Tidak Boleh Ada Field yang Kosong!
                              </div>";
        echo "<script>window.location = 'index.php?page=5'</script>";
    } else {
        $query = mysqli_query($conn, "INSERT INTO paket_umroh VALUES('$id_paket','$nama_paket','$harga','$fasilitas','$deskripsi')");
        if ($query) {
            $_SESSION['pesan'] = "<div class='alert alert-success' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Berhasil Input Paket Umroh!
                              </div>";
            echo "<script>window.location = 'index.php?page=5'</script>";
        } else {
            $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Gagal Input Paket Umroh!
                              </div>";
            echo "<script>window.location = 'index.php?page=5'</script>";
        }
    }
}

?>
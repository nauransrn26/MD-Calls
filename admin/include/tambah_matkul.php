<h1> <b>Tambah Jadwal Dokter</b>
</h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-user"></i> Tambah Jadwal Dokter</a></li>
</ol>

<hr>

<h5><b><?php echo date("l, M Y"); ?></b></h5>
<hr>
<a href="index.php?page=4" class="btn btn-primary btn-lg" style="margin-right:10px;float:right;background-color: #dd4b39; border-color: #dd4b39;"><i class="fa fa-arrow-circle-left"></i> Kembali</a>

<h5><b>Tambah Jadwal Dokter</b></h5>
<p>Isilah Form dibawah untuk dapat menambah Jadwal Dokter:
</p>
<br>

<!-- quick email widget -->
<div class="box box-info">
    <div class="box-header">
        <i class="fa fa-user"></i>
        <h3 class="box-title">Tambah Jadwal Dokter</h3>
        <?php
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] != "") {
            echo $_SESSION['pesan'];
            unset($_SESSION['pesan']);
        } else echo "";
        ?>
    </div>

   
    <div class="box-body">
        <form action="" method="post">
            <div class="form-group">
                <label for="">Id Jadwal</label>
                <input type="text" class="form-control" name="id_jadwal" id="id_jadwal" placeholder="id jadwal" >
            </div>
            <div class="form-group">
                <label for="">Id Dokter</label>
                <input type="text" class="form-control" name="id_dokter" id="id_dokter" placeholder="id dokter" >
            </div>
                <div class="form-group">
                <label for="">Tanggal</label>
                <input type="date" class="form-control" name="tanggal" id="tanggal" placeholder="tanggal" >
            </div>
            <div class="form-group">
                <label for="">Waktu Mulai</label>
                <input type="time" class="form-control" name="waktu_mulai" id="waktu_mulai" placeholder="waktu mulai" >
            </div>
            <div class="form-group">
                <label for="">Waktu Selesai</label>
                <input type="time" class="form-control" name="waktu_selesai" id="waktu_selesai" placeholder="waktu selesai" >
            </div>
            <div class="form-group">
                <label for="">Kuota Maksimal</label>
                <input type="text" class="form-control" name="kuota_maks" id="kuota_maks" placeholder="kuota maksimal" >
            </div>
            <div class="form-group">
                <label for="">Pendaftar Saat Ini</label>
                <input type="text" class="form-control" name="pendaftar_saat_ini" id="pendaftar_saat_ini" placeholder="pendaftar saat ini" >
            </div>

    </div>

    <div class="box-footer clearfix">
        <button class="pull-right btn btn-primary" name="simpan"style="background-color: #dd4b39; border-color: #dd4b39";>Simpan <i class="fa fa-arrow-circle-right"></i></button>
        </form>
    </div>
</div>

<?php

if (isset($_POST['simpan'])) {
    foreach ($_POST as $key => $value) {
        ${$key} = $value;
    }

    if ($id_jadwal == ""||$id_dokter == ""||$tanggal == ""||$waktu_mulai ==""||$waktu_selesai == ""||$kuota_maks == ""||$pendaftar_saat_ini == "") {
        $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Tidak Boleh Ada Field yang Kosong!
                              </div>";
        echo "<script>window.location = 'index.php?page=5'</script>";
    } else {
  $query = mysqli_query($conn, "INSERT INTO jadwal_dokter (id_jadwal, id_dokter, tanggal, waktu_mulai, waktu_selesai, kuota_maks, pendaftar_saat_ini) VALUES('$id_jadwal','$id_dokter','$tanggal','$waktu_mulai','$waktu_selesai','$kuota_maks','$pendaftar_saat_ini')");
        if ($query) {
            $_SESSION['pesan'] = "<div class='alert alert-success' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Berhasil Input Jadwal Dokter!
                              </div>";
            echo "<script>window.location = 'index.php?page=5'</script>";
        } else {
            $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Gagal Input Jadwal Dokter!
                              </div>";
            echo "<script>window.location = 'index.php?page=5'</script>";
        }
    }
}

?>
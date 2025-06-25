<h1>
    <b>Ubah Data Dokter</b>
</h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-user"></i> Ubah Data Dokter</a></li>
</ol>

<hr>

<a href="index.php?page=4" class="btn btn-primary btn-lg" style="margin-right:10px;float:right; background-color: #dd4b39; border-color: #dd4b39;"><i class="fa fa-arrow-circle-left"></i> Kembali</a>

<h5><b><?php echo date("l, M Y"); ?></b></h5>
<hr>

<h5><b>Ubah Data Dokter</b></h5>
<p>Berikut ini adalah Form untuk mengubah data dokter:
</p>
<br>

<?php

$query = mysqli_query($conn, "SELECT * FROM dokter");
if ($query) {
    $rows = mysqli_fetch_array($query);
   
    }
 else echo mysqli_error($conn);

?>

<!-- quick email widget -->
<div class="box box-info">
    <div class="box-header">
        <i class="fa fa-user"></i>
        <h3 class="box-title">Ubah Data Dokter</h3>
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
                <label for="">Id Dokter</label>
                <input type="text" class="form-control" name="id_dokter" id="id_dokter" placeholder="id dokter" >
            </div>
            <div class="form-group">
                <label for="">Nama Dokter</label>
                <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama" >
            </div>

            <div class="form-group">
                <label for="">Spesialisasi</label>
                <input type="text" class="form-control" name="spesialisasi" id="spesialisasi" placeholder="spesialisasi" >
            </div>
            <div class="form-group">
                <label for="">Telepon</label>
                <input type="text" class="form-control" name="telepon" id="telepon" placeholder="Telepon">
            </div>
           
    </div>
   <div class="box-footer clearfix">
    <button type="submit" class="pull-right btn btn-primary" style="margin-right:10px;float:right; background-color: #dd4b39; border-color: #dd4b39;" name="simpan">
        <i class="fa fa-arrow-circle-right"></i> Simpan
    </button>
</div>
</form>
</div>

<?php

if (isset($_POST['simpan'])) {
    foreach ($_POST as $key => $value) {
        ${$key} = $value;
    }

    if ($id_dokter == "" || $nama == "" || $spesialisasi == "" || $telepon == "") {
        $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Tidak Boleh Ada Field yang Kosong!
                              </div>";
        echo "<script>window.location = 'index.php?page=6&&id_dokter=$id_dokter'</script>";
    } else {
        $query = mysqli_query($conn, "UPDATE dokter SET id_dokter='$id_dokter', nama='$nama',spesialisasi='$spesialisasi',telepon='$telepon' WHERE id_dokter='$id_dokter'");
        if ($query) {
            $_SESSION['pesan'] = "<div class='alert alert-success' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Berhasil Ubah Data Dokter!
                              </div>";
            echo "<script>window.location = 'index.php?page=6&&id_dokter=$id_dokter'</script>";
        } else {
            $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Gagal Ubah Data Dokter!
                              </div>";
            echo "<script>window.location = 'index.php?page=6&&id_dokter=$id_dokter'</script>";
        }
    }
}

?>


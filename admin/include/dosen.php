<h1> <b>Tambah Dokter</b>
</h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-user"></i> Tambah Dokter</a></li>
</ol>

<hr>

<h5><b><?php echo date("l, M Y"); ?></b></h5>
<hr>
<a href="index.php?page=4" class="btn btn-primary btn-lg" style="margin-right:10px;float:right;background-color: #dd4b39; border-color: #dd4b39;"><i class="fa fa-arrow-circle-left"></i> Kembali</a>

<h5><b>Tambah Dokter</b></h5>
<p>Isilah Form dibawah untuk dapat menambah dokter:
</p>
<br>

<!-- quick email widget -->
<div class="box box-info">
    <div class="box-header">
        <i class="fa fa-user"></i>
        <h3 class="box-title">Tambah Dokter</h3>
        <?php
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] != "") {
            echo $_SESSION['pesan'];
            unset($_SESSION['pesan']);
        } else echo "";
        ?>
    </div>

    <?php
    $query = "select * from dokter order by id_dokter desc limit 1";
    $baris = mysqli_query($conn, $query);
    if ($baris) {
        if (mysqli_num_rows($baris) > 0) {
            $auto = mysqli_fetch_array($baris);
            $kode = $auto['id_dokter'];
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

        
    }
    ?>

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
                       <!-- Bagian Kode HTML -->


    </div>

    <div class="box-footer clearfix">
        <button class="pull-right btn btn-primary" style="margin-bottom:15px; background-color: #dd4b39; border-color: #dd4b39"; name="simpan">Simpan <i class="fa fa-arrow-circle-right"></i></button>
        </form>
    </div>
</div>

<?php

if (isset($_POST['simpan'])) {
    foreach ($_POST as $key => $value) {
        ${$key} = $value;
    }

    if ($id_dokter == ""||$nama == ""||$spesialisasi == ""||$telepon =="") {
        $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Tidak Boleh Ada Field yang Kosong!
                              </div>";
        echo "<script>window.location = 'index.php?page=5'</script>";
    } else {
        $query = mysqli_query($conn, "INSERT INTO dokter VALUES('$id_dokter','$nama','$spesialisasi','$telepon')");
        if ($query) {
            $_SESSION['pesan'] = "<div class='alert alert-success' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Berhasil Input Data Dokter!
                              </div>";
            echo "<script>window.location = 'index.php?page=15'</script>";
        } else {
            $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Gagal Input Data Dokter!
                              </div>";
            echo "<script>window.location = 'index.php?page=15'</script>";
        }
    }
}

?>
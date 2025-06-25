<?php

$query = mysqli_query($conn, "SELECT * FROM matkul WHERE kode_matkul='$kd_matkul'");
if ($query) {
    $rows = mysqli_fetch_array($query);
} else echo mysqli_error($conn);
$query2 = mysqli_query($conn, "SELECT * FROM ujian WHERE id_ujian='$id_ujian'");
if ($query2) {
    $rows_u = mysqli_fetch_array($query2);
} else echo mysqli_error($conn);

?>
<h3>
    <b>Tambah Mahasiswa Ujian "<?php echo $rows['nama_matkul']; ?>"</b>
</h3>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-user"></i> Tambah Mahasiswa</a></li>
</ol>

<hr>

<h5><b><?php echo date("l, M Y"); ?></b></h5>
<hr>
<a href="index.php?page=10&&kd_matkul=<?php echo $kd_matkul; ?>&&id_ujian=<?php echo $id_ujian; ?>" class="btn btn-primary btn-lg" style="margin-right:10px;float:right;"><i class="fa fa-arrow-circle-left"></i> Kembali</a>

<h5><b>Tambah Mahasiswa</b></h5>
<p>Isilih Form dibawah untuk dapat Tambah Mahasiswa:
</p>
<br>

<!-- quick email widget -->
<div class="box box-info">
    <div class="box-header">
        <i class="fa fa-user"></i>
        <h3 class="box-title">Tambah Mahasiswa</h3>
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
                <label for="">Kode Matkul</label>
                <input type="text" class="form-control" readonly="readonly" name="kd_matkul" id="kd_matkul" placeholder="Kode Matkul" value="<?php echo $kd_matkul; ?>">
            </div>
            <div class="form-group">
                <label for="">Nama Matkul</label>
                <input type="text" class="form-control" readonly="readonly" name="nm_matkul" id="nm_matkul" placeholder="Nama Matkul" value="<?php echo $rows['nama_matkul']; ?>">
            </div>
            <div class="form-group">
                <label for="">Jumlah SKS</label>
                <input type="number" class="form-control" readonly="readonly" name="sks" id="sks" placeholder="Jumlah SKS" min="0" value="<?php echo $rows['sks']; ?>">
            </div>
            <div class="form-group">
                <label for="">Dosen Pengajar</label>
                <select name="dosen" id="dosen" class="form-control">
                    <option value="" disabled selected>-- Pilih Dosen Pengajar --</option>
                    <?php
                    $query = mysqli_query($conn, "SELECT * FROM dosen");
                    if ($query) {
                        while ($rowss = mysqli_fetch_array($query)) {
                            $selected = "";
                            if ($rowss['nid'] == $rows_u['nid']) {
                                $selected = "selected";
                            }
                    ?>
                            <option disabled value="<?php echo $rowss['nid']; ?>" <?php echo $selected; ?>><?php echo $rowss['nid']; ?> - <?php echo $rowss['nama_dosen']; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="">Pilih Mahasiswa</label>
                <select name="mhs" id="mhs" class="form-control">
                    <option value="" disabled selected>-- Pilih Mahasiswa --</option>
                    <?php
                    $query = mysqli_query($conn, "SELECT * FROM mahasiswa");
                    if ($query) {
                        while ($rowss = mysqli_fetch_array($query)) {

                    ?>
                            <option value="<?php echo $rowss['nim']; ?>"><?php echo $rowss['nim']; ?> - <?php echo $rowss['nama_mhs']; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
    </div>

    <div class="box-footer clearfix">
        <button class="pull-right btn btn-primary" name="simpan">Simpan <i class="fa fa-arrow-circle-right"></i></button>
        </form>
    </div>
</div>

<?php

if (isset($_POST['simpan'])) {
    foreach ($_POST as $key => $value) {
        echo ${$key} = $value;
    }

    if ($mhs == "") {
        $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Mahasiswa Harus Dipilih!
                              </div>";
        echo "<script>window.location = 'index.php?page=11&&kd_matkul=$kd_matkul&&id_ujian=$id_ujian'</script>";
    } else {
        $nil = 0;
        $q_c = mysqli_query($conn, "SELECT * FROM detil_ujian a, ujian b WHERE a.nim='$mhs' AND b.id_ujian='$id_ujian' AND a.id_ujian=b.id_ujian ");
        if ($q_c) {
            $nil = mysqli_num_rows($q_c);
        }

        if ($nil == 0) {

            $query = mysqli_query($conn, "INSERT INTO detil_ujian (id_ujian,nim)VALUES('$id_ujian','$mhs')");
            if ($query) {
                $_SESSION['pesan'] = "<div class='alert alert-success' style='margin-top:5px;'>
                                  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                  Berhasil Tambah Mahasiswa!
                                </div>";
                echo "<script>window.location = 'index.php?page=10&&kd_matkul=$kd_matkul&&id_ujian=$id_ujian'</script>";
            } else {
                $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                  Gagal Tambah Mahasiswa!
                                </div>";
                echo "<script>window.location = 'index.php?page=11&&kd_matkul=$kd_matkul&&id_ujian=$id_ujian'</script>";
            }
        } else {
            $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                  Mahasiswa ini sudah ditambahkan ke daftar.Pilih Mahasiswa Lain!
                                </div>";
            echo "<script>window.location = 'index.php?page=11&&kd_matkul=$kd_matkul&&id_ujian=$id_ujian'</script>";
        }
    }
}

?>


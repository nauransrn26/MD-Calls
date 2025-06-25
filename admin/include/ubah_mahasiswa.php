<?php

$query = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE nim='$nim'");
if ($query) {
    $rows = mysqli_fetch_array($query);
} else echo mysqli_error($conn);

$selected1 = "";
$selected2 = "";
$selected3 = "";
$selected4 = "";
$selected5 = "";
$selected6 = "";
$selected7 = "";
$selected8 = "";
if ($rows['jk'] == "Pria") {
    $selected1 = "selected";
} elseif ($rows['jk'] == "Wanita") {
    $selected2 = "selected";
} else {
    $selected1 = "";
    $selected2 = "";
}

switch ($rows['agama']) {
    case 'Islam':
        $selected3 = "selected";
        break;
    case 'Katolik':
        $selected4 = "selected";
        break;
    case 'Protestan':
        $selected5 = "selected";
        break;
    case 'Buddha':
        $selected6 = "selected";
        break;
    case 'Hindu':
        $selected7 = "selected";
        break;
    case 'Kong Hu Cu':
        $selected8 = "selected";
        break;
    default:
        $selected3 = "";
        $selected4 = "";
        $selected5 = "";
        $selected6 = "";
        $selected7 = "";
        $selected8 = "";
        break;
}
?>

<h1>
    <b>Ubah Data Mahasiswa</b>
</h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-user"></i> Ubah Data Mahasiswa</a></li>
</ol>

<hr>

<h5><b><?php echo date("l, M Y"); ?></b></h5>
<hr>
<a href="index.php?page=12" class="btn btn-primary btn-lg" style="margin-right:10px;float:right;"><i class="fa fa-arrow-circle-left"></i> Kembali</a>

<h5><b>Ubah Data Mahasiswa</b></h5>
<p>Isilih Form dibawah untuk dapat mengubah data mahasiswa:
</p>
<br>

<!-- quick email widget -->
<div class="box box-info">
    <div class="box-header">
        <i class="fa fa-user"></i>
        <h3 class="box-title">Ubah Data Mahasiswa</h3>
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
                <label for="">NIM</label>
                <input type="text" class="form-control" name="nim" id="nim" placeholder="NIM" readonly="readonly" value="<?php echo $rows['nim']; ?>">
            </div>
            <div class="form-group">
                <label for="">Nama Mahasiswa</label>
                <input type="text" class="form-control" name="nm_mhs" id="nm_mhs" placeholder="Nama Mahasiswa" value="<?php echo $rows['nama_mhs']; ?>">
            </div>
            <div class="form-group">
                <label for="">Jenis Kelamin</label>
                <select name="jk" id="jk" class="form-control">
                    <option value="" selected disabled>-- Pilih Jenis Kelamin --</option>
                    <option value="Pria" <?php echo $selected1; ?>>Pria</option>
                    <option value="Wanita" <?php echo $selected2; ?>>Wanita</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Tempat Lahir</label>
                <input type="text" class="form-control" name="tmpt_lahir" id="tmpt_lahir" placeholder="Tempat Lahir" value="<?php echo $rows['tempat_lahir']; ?>">
            </div>
            <div class="form-group">
                <label for="">Tanggal Lahir</label>
                <input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir" placeholder="Tanggal Lahir" value="<?php echo $rows['tgl_lahir']; ?>">
            </div>
            <div class="form-group">
                <label for="">Agama</label>
                <select name="agama" id="agama" class="form-control">
                    <option value="" selected disabled>-- Pilih Agama --</option>
                    <option value="Islam" <?php echo $selected3; ?>>Islam</option>
                    <option value="Katolik" <?php echo $selected4; ?>>Katolik</option>
                    <option value="Protestan" <?php echo $selected5; ?>>Protestan</option>
                    <option value="Buddha" <?php echo $selected6; ?>>Buddha</option>
                    <option value="Hindu" <?php echo $selected7; ?>>Hindu</option>
                    <option value="Kong Hu Cu" <?php echo $selected8; ?>>Kong Hu Cu</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Nama Ortu</label>
                <input type="text" class="form-control" name="nm_ortu" id="nm_ortu" placeholder="Nama Orang Tua" value="<?php echo $rows['nama_ortu']; ?>">
            </div>
            <div class="form-group">
                <label for="">Alamat</label>
                <textarea name="alamat" id="alamat" cols="30" rows="3" class="form-control" placeholder="Alamat"><?php echo $rows['alamat']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="">No.HP/Telp</label>
                <input type="tel" class="form-control" name="telp" id="telp" placeholder="No.HP/Telp" value="<?php echo $rows['telp']; ?>">
            </div>
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $rows['email']; ?>">
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
        ${$key} = $value;
    }

    if ($nim == "" || $nm_mhs == "" || $jk == "" || $tmpt_lahir == "" || $tgl_lahir == "" || $agama == "" || $nm_ortu == "" || $alamat == "" || $telp == "" || $email == "") {
        $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                              <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                              Tidak Boleh Ada Field yang Kosong!
                            </div>";
        echo "<script>window.location = 'index.php?page=14&&nim=$nim'</script>";
    } else {

        $query = mysqli_query($conn, "UPDATE mahasiswa SET nim='$nim',nama_mhs='$nm_mhs',jk='$jk',tempat_lahir='$tmpt_lahir',tgl_lahir='$tgl_lahir',
        agama='$agama',nama_ortu='$nm_ortu',alamat='$alamat',telp='$telp',email='$email' WHERE nim='$nim'");
        if ($query) {
            $_SESSION['pesan'] = "<div class='alert alert-success' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Berhasil Ubah Data Mahasiswa!
                              </div>";
            echo "<script>window.location = 'index.php?page=14&&nim=$nim'</script>";
        } else {
            $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Gagal Ubah Data Mahasiswa!
                              </div>";
            echo "<script>window.location = 'index.php?page=14&&nim=$nim'</script>";
        }
    }
}

?>

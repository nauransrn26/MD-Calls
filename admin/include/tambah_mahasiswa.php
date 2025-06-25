<h1>
    <b>Tambah Mahasiswa</b>
</h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-user"></i> Tambah Mahasiswa</a></li>
</ol>

<hr>

<h5><b><?php echo date("l, M Y"); ?></b></h5>
<hr>
<a href="index.php?page=12" class="btn btn-primary btn-lg" style="margin-right:10px;float:right;"><i class="fa fa-arrow-circle-left"></i> Kembali</a>

<h5><b>Tambah Mahasiswa</b></h5>
<p>Isilih Form dibawah untuk dapat menambah mahasiswa:
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
                <label for="">NIM</label>
                <input type="text" class="form-control" name="nim" id="nim" placeholder="NIM">
            </div>
            <div class="form-group">
                <label for="">Nama Mahasiswa</label>
                <input type="text" class="form-control" name="nm_mhs" id="nm_mhs" placeholder="Nama Mahasiswa">
            </div>
            <div class="form-group">
                <label for="">Jenis Kelamin</label>
                <select name="jk" id="jk" class="form-control">
                    <option value="" selected disabled>-- Pilih Jenis Kelamin --</option>
                    <option value="Pria">Pria</option>
                    <option value="Wanita">Wanita</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Tempat Lahir</label>
                <input type="text" class="form-control" name="tmpt_lahir" id="tmpt_lahir" placeholder="Tempat Lahir">
            </div>
            <div class="form-group">
                <label for="">Tanggal Lahir</label>
                <input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir" placeholder="Tanggal Lahir">
            </div>
            <div class="form-group">
                <label for="">Agama</label>
                <select name="agama" id="agama" class="form-control">
                    <option value="" selected disabled>-- Pilih Agama --</option>
                    <option value="Islam">Islam</option>
                    <option value="Katolik">Katolik</option>
                    <option value="Protestan">Protestan</option>
                    <option value="Buddha">Buddha</option>
                    <option value="Hindu">Hindu</option>
                    <option value="Kong Hu Cu">Kong Hu Cu</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Nama Ortu</label>
                <input type="text" class="form-control" name="nm_ortu" id="nm_ortu" placeholder="Nama Orang Tua">
            </div>
            <div class="form-group">
                <label for="">Alamat</label>
                <textarea name="alamat" id="alamat" cols="30" rows="3" class="form-control" placeholder="Alamat"></textarea>
            </div>
            <div class="form-group">
                <label for="">No.HP/Telp</label>
                <input type="tel" class="form-control" name="telp" id="telp" placeholder="No.HP/Telp">
            </div>
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email">
            </div>
            <div class="form-group">
                <label for="">Password</label>
                <input type="password" class="form-control" name="pass" id="pass" placeholder="Password">
            </div>
            <div class="form-group">
                <label for="">Konfirmasi Password</label>
                <input type="password" class="form-control" name="con_pass" id="con_pass" placeholder="Konfirmasi Password">
            </div>
    </div>

    <div class="box-footer clearfix">
        <button class="pull-right btn btn-primary" name="simpan">Simpan <i class="fa fa-arrow-circle-right"></i></button>
        </form>
    </div>
</div>

<?php

$cost = 10;
echo $hash = password_hash("mahasiswa3", PASSWORD_BCRYPT, ["cost" => $cost]);

if (isset($_POST['simpan'])) {
    foreach ($_POST as $key => $value) {
        ${$key} = $value;
    }

    if ($nim == "" || $nm_mhs == "" || $jk == "" || $tmpt_lahir == "" || $tgl_lahir == "" || $agama == "" || $nm_ortu == "" || $alamat == "" || $telp == "" || $email == "" || $pass == "" || $con_pass == "") {
        $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Tidak Boleh Ada Field yang Kosong!
                              </div>";
        echo "<script>window.location = 'index.php?page=13'</script>";
    } elseif ($pass != $con_pass) {
        $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Password Harus sesuai dengan Konfirmasi Password
                              </div>";
        echo "<script>window.location = 'index.php?page=13'</script>";
    } else {
        $query_c = mysqli_query($conn, "SELECT * FROM mahasiswa");
        $flag = 0;
        if ($query_c) {
            if (mysqli_num_rows($query_c) > 0) {
                while ($rows_c = mysqli_fetch_array($query_c)) {
                    if ($rows_c['nim'] == $nim) {
                        $flag = 1;
                        break;
                    }
                }
            }
        } else echo mysqli_error($conn);

        if ($flag == 1) {
            $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                NIM yang diinput sudah digunakan oleh Mahasiswa lain, Gunakan NIM lain!
                              </div>";
            echo "<script>window.location = 'index.php?page=13'</script>";
        } else {
            $cost = 10;
            $hash = password_hash($pass, PASSWORD_BCRYPT, ["cost" => $cost]);
            $query = mysqli_query($conn, "INSERT INTO mahasiswa (nim,nama_mhs,jk,tempat_lahir,tgl_lahir,agama,nama_ortu,alamat,telp,password,email)
          VALUES('$nim','$nm_mhs','$jk','$tmpt_lahir','$tgl_lahir','$agama','$nm_ortu','$alamat','$telp','$hash','$email')");
            if ($query) {
                $_SESSION['pesan'] = "<div class='alert alert-success' style='margin-top:5px;'>
                                  <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                  Berhasil Input Data Mahasiswa!
                                </div>";
                echo "<script>window.location = 'index.php?page=13'</script>";
            } else {
                $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                  <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                  Gagal Input Data Mahasiswa!
                                </div>";
                echo "<script>window.location = 'index.php?page=13'</script>";
            }
        }
    }
}

?>
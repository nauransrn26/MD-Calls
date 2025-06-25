<?php  

$query = mysqli_query($conn,"SELECT * FROM matkul WHERE kode_matkul='$kd_matkul'");
if ($query) {
  $rows = mysqli_fetch_array($query);
}else echo mysqli_error($conn);
$query2 = mysqli_query($conn,"SELECT * FROM ujian WHERE id_ujian='$id_ujian'");
if ($query2) {
  $rows_u = mysqli_fetch_array($query2);
}else echo mysqli_error($conn);

?>
          <h1>
            <b>Ubah Dosen Pengajar</b>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#"><i class="fa fa-user"></i> Ubah Dosen Pengajar</a></li>
          </ol>
       
    <hr>

    <h5><b><?php echo date("l, M Y"); ?></b></h5>
    <hr>
    <a href="index.php?page=7" class="btn btn-primary btn-lg" style="margin-right:10px;float:right;"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
                
    <h5><b>Ubah Dosen Pengajar</b></h5>
    <p>Isilih Form dibawah untuk dapat mengubah dosen pengajar:
    </p>
    <br>

<!-- quick email widget -->
              <div class="box box-info">
                <div class="box-header">
                  <i class="fa fa-user"></i>
                  <h3 class="box-title">Pilih Dosen Pengajar</h3>
                  <?php  
                   if (isset($_SESSION['pesan']) && $_SESSION['pesan'] != "") {
                     echo $_SESSION['pesan'];
                     unset($_SESSION['pesan']);
                   }else echo "";
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
                      <label for="">Pilih Dosen</label>
                      <select name="dosen" id="dosen" class="form-control">
                        <option value="" disabled selected>-- Pilih Dosen Pengajar --</option>
                        <?php  
                        $query = mysqli_query($conn,"SELECT * FROM dosen");
                        if ($query) {
                          while ($rowss = mysqli_fetch_array($query)) {
                            $selected = "";
                            if ($rowss['nid'] == $rows_u['nid']) {
                              $selected = "selected";
                            }
                        ?>
                        <option value="<?php echo $rowss['nid']; ?>" <?php echo $selected; ?>><?php echo $rowss['nid']; ?> - <?php echo $rowss['nama_dosen']; ?></option>
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
      ${$key} = $value;
    }
    
    if ($dosen == "") {
      $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Dosen Pengajar Harus Dipilih!
                              </div>";
      echo "<script>window.location = 'index.php?page=9&&kd_matkul=$kd_matkul&&id_ujian=$id_ujian'</script>";
    }else{
      $query = mysqli_query($conn,"UPDATE ujian SET nid='$dosen' WHERE id_ujian=$id_ujian");
      if ($query) {
        $_SESSION['pesan'] = "<div class='alert alert-success' style='margin-top:5px;'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Berhasil Pilih Dosen Pengajar!
                              </div>";
        echo "<script>window.location = 'index.php?page=9&&kd_matkul=$kd_matkul&&id_ujian=$id_ujian'</script>";
      }else{
        $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Gagal Pilih Dosen Pengajar!
                              </div>";
        echo "<script>window.location = 'index.php?page=9&&kd_matkul=$kd_matkul&&id_ujian=$id_ujian'</script>";
      }
    }

  }

?>

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
<h2>
    <b>Daftar Mahasiswa Matkul "<?php echo $rows['nama_matkul']; ?>"</b>
</h2>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-user"></i> Daftar Mahasiswa</a></li>
</ol>

<hr>

<h5><b><?php echo date("l, M Y"); ?></b></h5>
<hr>
<a href="index.php?page=7" class="btn btn-primary btn-lg" style="margin-right:10px;float:right;"><i class="fa fa-arrow-circle-left"></i> Kembali</a>

<h5><b>Daftar Mahasiswa</b></h5>
<p>Isilih Form dibawah untuk dapat mengubah dosen pengajar:
</p>
<br>

<!-- quick email widget -->
<div class="box box-info">
    <div class="box-header">
        <i class="fa fa-book"></i>
        <h3 class="box-title">Detail Matkul</h3>
        <?php
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] != "") {
            echo $_SESSION['pesan'];
            unset($_SESSION['pesan']);
        } else echo "";
        ?>
    </div>


    <div class="box-body">

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
    </div>

</div>


<div class="box box-info">
    <div class="box-header">
        <i class="fa fa-user"></i>
        <h3 class="box-title">Daftar Mahasiswa</h3>
        <?php
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] != "") {
            echo $_SESSION['pesan'];
            unset($_SESSION['pesan']);
        } else echo "";
        ?>
    </div>

    <a href="index.php?page=11&&kd_matkul=<?php echo $kd_matkul; ?>&&id_ujian=<?php echo $id_ujian; ?>" class="btn btn-primary btn-lg" style="float:right;margin-right:10px;margin-bottom:10px;"><i class="fa fa-plus"></i> Tambah Mahasiswa</a>
    <div class="box-body">
        <table id="table" class="row-border" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>JK</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>JK</th>
                    <th>Aksi</th>
                </tr>
            </tfoot>
            <tbody>
                <?php
                $query = mysqli_query($conn, "SELECT a.* FROM mahasiswa a, detil_ujian b,ujian c WHERE a.nim=b.nim AND b.id_ujian='$id_ujian' AND c.id_ujian=b.id_ujian");
                if ($query) {
                    $no = 1;
                    while ($rows = mysqli_fetch_array($query)) {
                ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $rows['nim']; ?></td>
                            <td><?php echo $rows['nama_mhs']; ?></td>
                            <td><?php echo $rows['jk']; ?></td>
                            <td><a href="#" data-toggle="modal" data-target="#confirm-delete_<?php echo $rows['nim']; ?>" class="btn btn-primary">Hapus</a></td>
                        </tr>

                        <div class="modal fade" id="confirm-delete_<?php echo $rows['nim']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Hapus Matkul
                                    </div>
                                    <div class="modal-body">
                                        Apakah anda yakin menghapus Matkul ini?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                        <a class="btn btn-danger btn-ok" id="<?php echo $rows['nim']; ?>" href="../include/hapus_mhs_ujian.php?nim=<?php echo $rows['nim']; ?>&&kd_matkul=<?php echo $kd_matkul; ?>&&id_ujian=<?php echo $id_ujian; ?>">Hapus</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<script>
    $(document).ready(function() {
        $('#table').DataTable();
    });

    $(".btn btn-danger").click(function(e) {
        var id = $(this).attr('id');
        var modal_id = "confirm-delete_" + id;
        $("#" + modal_id).modal('hide');
    });
</script>

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
    } else {
        $query = mysqli_query($conn, "UPDATE ujian SET nid='$dosen' WHERE id_ujian=$id_ujian");
        if ($query) {
            $_SESSION['pesan'] = "<div class='alert alert-success' style='margin-top:5px;'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Berhasil Pilih Dosen Pengajar!
                              </div>";
            echo "<script>window.location = 'index.php?page=9&&kd_matkul=$kd_matkul&&id_ujian=$id_ujian'</script>";
        } else {
            $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Gagal Pilih Dosen Pengajar!
                              </div>";
            echo "<script>window.location = 'index.php?page=9&&kd_matkul=$kd_matkul&&id_ujian=$id_ujian'</script>";
        }
    }
}

?>



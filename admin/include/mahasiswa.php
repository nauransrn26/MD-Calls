<h1>
    <b>Pendaftaran Kunjungan Pasien</b>
</h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-user"></i>Pendaftaran Pasien</a></li>
</ol>

<h5><b><?php echo date("l, M Y"); ?></b></h5>
<hr>
<h5><b>Pendaftaran Pasien</b></h5>
<p>Berikut ini adalah Pasien yang ingin mendaftar berobat hari ini:
</p>
<br>
<!-- quick email widget -->
<div class="box box-info">
    <div class="box-header">
        <i class="fa fa-pencil"></i>
        <h3 class="box-title">Pendaftaran Pasien</h3>
        <!-- tools box -->

    </div>
    <?php
    if (isset($_SESSION['pesan']) && $_SESSION['pesan'] != "") {
        echo $_SESSION['pesan'];
        unset($_SESSION['pesan']);
    } else echo "";
    ?>



     <table id="table" class="row-border" cellspacing="0" width="100%">
           <thead>
                <tr>
                    <th>id kunjungan</th>
                    <th>id pasien</th>
                    <th>id dokter</th>
                    <th>tanggal kunjungan</th>
                    <th>waktu kunjungan</th>
                    <th>keluhan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query = mysqli_query($conn, "SELECT * FROM kunjungan");
                if ($query) {
                    while ($rows = mysqli_fetch_array($query)) {
                ?>
                        <tr>
                            <td><?php echo $rows['id_kunjungan']; ?></td>
                            <td><?php echo $rows['id_pasien']; ?></td>
                            <td><?php echo $rows['id_dokter']; ?></td>
                            <td><?php echo $rows['tanggal_kunjungan']; ?></td>
                            <td><?php echo $rows['waktu_kunjungan']; ?></td>
                            <td><?php echo $rows['keluhan']; ?></td>
                            </tr>

                            

                        <div class="modal fade" id="confirm-delete_<?php echo $rows['nim']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Hapus Data Mahasiswa
                                    </div>
                                    <div class="modal-body">
                                        Apakah anda yakin menghapus data Mahasiswa ini?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                        <a class="btn btn-danger btn-ok" id="<?php echo $rows['nim']; ?>" href="../include/hapus_mahasiswa.php?nim=<?php echo $rows['nim']; ?>">Hapus</a>
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

    <div class="row">
    
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

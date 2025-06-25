<h1>
    <b>Data Pasien</b>
</h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-pencil"></i> Data Pasien</a></li>
</ol>


<h5><b><?php echo date("l, M Y"); ?></b></h5>
<hr>

<h5><b>Data Pasien</b></h5>
<p>Berikut ini adalah Data Pasien yang ingin berobat:
</p>
<br>

<!-- quick email widget -->
<div class="box box-info">
    <div class="box-header">
        <i class="fa fa-book"></i>
        <h3 class="box-title">Data Pasien </h3>
        <!-- tools box -->

    </div>

    <?php
    if (isset($_SESSION['pesan']) && $_SESSION['pesan'] != "") {
        echo $_SESSION['pesan'];
        unset($_SESSION['pesan']);
    } else echo "";
    ?>

    <div class="box-body">

        <table id="table" class="row-border" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>id pasien</th>
                    <th>username</th>
                    <th>nama</th>
                    <th>email</th>
                    <th>jenis kelamin</th>
                     <th>tempat lahir</th>
                     <th>tanggal lahir</th>
                    </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query = mysqli_query($conn, "SELECT * FROM pasien");
                if ($query) {
                    while ($rows = mysqli_fetch_array($query)) {
                        $val = "";
                        $nn = "";
                        $query2 = mysqli_query($conn, "SELECT id_pasien,username,nama,email,jenis_kelamin,tempat_lahir,tanggal_lahir FROM pasien");
                        if ($query2) {
                            if (mysqli_num_rows($query2) > 0) {
                                $rowss = mysqli_fetch_array($query2);
                                $val   = $rowss['id_pasien'];
                                $nn    = $rowss['username'];
                            }
                        }
                ?>
                        <tr>
                            <td><?php echo $rows['id_pasien']; ?></td>
                            <td><?php echo $rows['username']; ?></td>
                            <td><?php echo $rows['nama']; ?></td>
                            <td><?php echo $rows['email']; ?></td>
                            <td><?php echo $rows['jenis_kelamin']; ?></td>
                             <td><?php echo $rows['tempat_lahir']; ?></td>
                              <td><?php echo $rows['tanggal_lahir']; ?></td>
                            
                        </tr>

                        <div class="modal fade" id="confirm-delete_<?php echo $rows['kode_matkul']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                        <a class="btn btn-danger btn-ok" id="<?php echo $rows['kode_matkul']; ?>" href="../include/hapus_matkul.php?kd_matkul=<?php echo $rows['kode_matkul']; ?>">Hapus</a>
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
    <div class="col-lg-3">
        <a href="cetakpengunjung.php" target="_blank" alt="Cetak Laporan" class="btn btn-primary"
        style="background-color: #dd4b39; border-color: #dd4b39;">
        Cetak Laporan Data Pasien</a>
    </div>
</div>
</div>

<script>
    $(document).ready(function() {
        $('#table').DataTable();
    });

    $(".btn btn-danger").click(function(e) {
        var id = $(this).attr('id_pasien');
        var modal_id = "confirm-delete_" + id;
        $("#" + modal_id).modal('hide');
    });
</script>


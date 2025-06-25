
<h1>
    <b>Transkip</b>
</h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-user"></i>Transkip</a></li>
</ol>


<h5><b><?php echo date("l, M Y"); ?></b></h5>
<hr>

<h5><b>Transkip</b></h5>
<p>Berikut ini adalah nilai anda pada semester ini:
</p>
<div class="box">
    <table class="table table-bordered" border="2px solid black">
        <tr>
            <td>NIM</td>
            <td>: <?php echo $data['nim']; ?></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>: <?php echo $data['nama_mhs']; ?></td>
        </tr>
        <tr>
            <td>Tempat, Tanggal Lahir</td>
            <td>: <?php echo $data['tempat_lahir'] . ", " . $data['tgl_lahir']; ?></td>
        </tr>
    </table>
</div>


<!-- quick email widget -->
<div class="box box-info">
    <div class="box-header">
        <i class="fa fa-user"></i>
        <h3 class="box-title">Nilai Semester</h3>
        <!-- tools box -->
        <table class="table table-hover table-striped">
            <tr>
                <td rowspan="2">No.</td>
                <td rowspan="2">Kode</td>
                <td rowspan="2">Matakuliah</td>
                <td align="center" colspan="4">Indexs Prestasi</td>
            </tr>
            <tr>
                <td>K</td>
                <td>B</td>
                <td>NM</td>
                <td>N</td>
            </tr>
            <?php
            $sqli = mysqli_query($conn, "SELECT a.*,b.* FROM ujian a, detil_ujian b where b.nim='$nim' AND a.id_ujian=b.id_ujian");
            if ($sqli) {
                if (mysqli_num_rows($sqli) == 0) {
            ?>
                    <tr>
                        <td colspan="5" align="center">
                            <h1>Kosong</h1>
                        </td>
                    </tr>
                    <?php
                } else {
                    $no = 1;
                    $jkk = 0;
                    $tot_am = 0;
                    $ipk = 0;
                    while ($rows = mysqli_fetch_array($sqli)) {
                        $sqli2 = mysqli_query($conn, "SELECT nama_matkul,sks from matkul where kode_matkul='$rows[kode_matkul]'");
                        $rowss = mysqli_fetch_array($sqli2);

                        $absen   = $rows['nilai_absen'];
                        $tugas   = $rows['nilai_tugas'];
                        $uts   = $rows['nilai_uts'];
                        $uas   = $rows['nilai_uas'];
                        $nilai = 0;
                        $am    = 0;

                        $grade = "";

                        $nilai = (((0.4) * $uas) + ((0.3) * $uts) + ((0.2) * $tugas) + ((0.1) * $absen));

                        if ($nilai >= 85 && $nilai <= 100) {
                            $am = 4;
                            $grade = "A";
                        } elseif ($nilai >= 80 && $nilai < 85) {
                            $am = 3.7;
                            $grade = "A-";
                        } elseif ($nilai >= 75 && $nilai < 80) {
                            $am = 3.3;
                            $grade = "B+";
                        } elseif ($nilai >= 70 && $nilai < 75) {
                            $am = 3.0;
                            $grade = "B";
                        } elseif ($nilai >= 65 && $nilai < 70) {
                            $am = 2.7;
                            $grade = "B-";
                        } elseif ($nilai >= 60 && $nilai < 65) {
                            $am = 2.0;
                            $grade = "C";
                        } elseif ($nilai >= 45 && $nilai < 60) {
                            $am = 1.0;
                            $grade = "D";
                        } elseif ($nilai >= 0 && $nilai < 45) {
                            $am = 0;
                            $grade = "E";
                        }

                        $m = $am * $rowss['sks'];
                        $jkk = $jkk + $rowss['sks'];
                        $tot_am = $tot_am + $am;

                    ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $rows['kode_matkul']; ?></td>
                            <td><?php echo $rowss['nama_matkul'] ?></td>
                            <td><?php echo $rowss['sks']; ?></td>
                            <td><?php echo $am; ?></td>
                            <td><?php echo $m; ?></td>
                            <td><?php echo $grade; ?></td>
                        </tr>
            <?php
                        $no++;
                    }

                    $ipk = $tot_am / ($no - 1);
                }
            }
            ?>

        </table>

        Jumlah Kredit Komulatif : <?php echo $jkk; ?><br>
        <b>Index Prestasi Komulatif</b>: <?php echo $ipk; ?>
    </div>
    <div class="box-body">

    </div>

    <div class="box-footer clearfix">
        <a class="pull-right btn btn-primary" href="include/cetak.php?nim=<?php echo $nim; ?>">Cetak <i class="fa fa-print"></i></a>
    </div>
</div>

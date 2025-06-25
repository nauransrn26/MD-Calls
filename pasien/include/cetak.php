
<?php
ob_start();
include '../../fpdf/fpdf.php';
include '../../koneksi/koneksi.php';

$nim = $_GET['nim'];


class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        //$this->Image('../../images/.jpg', 10, 6, 30);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 14);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30, 10, 'KHS Universitas - Pekanbaru', 0, 0, 'C');

        $this->Ln();
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(190, 10, 'Hasil Studi Komulatif', 0, 0, 'C');
        // Line break

        $this->Line(10, 37, 210 - 10, 37);
        $this->Line(10, 39, 210 - 10, 39);
        $this->Ln(15);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$query = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE nim='$nim'");
if ($query) {
    $rows = mysqli_fetch_array($query);
} else echo mysqli_error($conn);

$pdf->SetFont('Times', 'B', 8);
$pdf->Cell(30, 15, 'NIM', 0, 0, 'L');
$pdf->SetFont('Times', '', 8);
$pdf->Cell(60, 15, ": " . $rows['nim'], 0, 0, 'L');

$pdf->SetFont('Times', 'B', 8);
$pdf->Cell(30, 15, 'Jenjang', 0, 0, 'L');
$pdf->SetFont('Times', '', 8);
$pdf->Cell(30, 15, ": Starata 1", 0, 0, 'L');

$pdf->Ln(5);
$pdf->SetFont('Times', 'B', 8);
$pdf->Cell(30, 15, 'Nama', 0, 0, 'L');
$pdf->SetFont('Times', '', 8);
$pdf->Cell(60, 15, ": " . $rows['nama_mhs'], 0, 0, 'L');

$pdf->SetFont('Times', 'B', 8);
$pdf->Cell(30, 15, 'Program Studi', 0, 0, 'L');
$pdf->SetFont('Times', '', 8);
$pdf->Cell(30, 15, ": Sistem Informasi", 0, 0, 'L');

$pdf->Ln(5);

$pdf->SetFont('Times', 'B', 8);
$pdf->Cell(30, 15, 'Tempat, Tanggal Lahir', 0, 0, 'L');
$pdf->SetFont('Times', '', 8);
$pdf->Cell(60, 15, ": " . $rows['tempat_lahir'] . ", " . $rows['tgl_lahir'], 0, 0, 'L');

$pdf->Ln(15);
$pdf->Cell(10, 20, ' ', 'LTR', 0, 'L', 0);
$pdf->Cell(30, 20, ' ', 'LTR', 0, 'L', 0);
$pdf->Cell(90, 20, ' ', 'LTR', 0, 'L', 0);
$pdf->Cell(60, 10, 'Prestasi SKS', 1, 0, "C");
$pdf->Ln();
$pdf->Cell(10, 3, 'No ', 'LR', 0, "C", 0);
$pdf->Cell(30, 3, 'Kode', 'LR', 0, "C", 0);
$pdf->Cell(90, 3, 'Mata Kuliah', 'LR', 0, "C", 0);
$pdf->Cell(15, 10, 'K', 1, 0, "C");
$pdf->Cell(15, 10, 'AM', 1, 0, "C");
$pdf->Cell(15, 10, 'M', 1, 0, "C");
$pdf->Cell(15, 10, 'HM', 1, 0, "C");
$pdf->SetFont('Times', '', 8);
$pdf->Ln();
$sqli = mysqli_query($conn, "SELECT a.*,b.* FROM ujian a, detil_ujian b where b.nim='$nim' AND a.id_ujian=b.id_ujian");
if ($sqli) {
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

        $pdf->Cell(10, 10, $no, 1, 0, "C");
        $pdf->Cell(30, 10, $rows['kode_matkul'], 1, 0, "C");
        $pdf->Cell(90, 10, $rowss['nama_matkul'], 1, 0, "C");
        $pdf->Cell(15, 10, $rowss['sks'], 1, 0, "C");
        $pdf->Cell(15, 10, $am, 1, 0, "C");
        $pdf->Cell(15, 10, $m, 1, 0, "C");
        $pdf->Cell(15, 10, $grade, 1, 0, "C");
        $pdf->Ln();

        $no++;
    }

    $ipk = $tot_am / ($no - 1);
    $pdf->Cell(10, 10, "HM", 0, 0, "L");
    $pdf->Cell(30, 10, ": Huruf Mutu", 0, 0, "L");

    $pdf->Cell(10, 10, "K", 0, 0, "L");
    $pdf->Cell(20, 10, ": Komulatif", 0, 0, "L");

    $pdf->Ln(5);
    $pdf->Cell(10, 10, "AM", 0, 0, "L");
    $pdf->Cell(30, 10, ": Angka Mutu", 0, 0, "L");


    $pdf->Cell(10, 10, "M", 0, 0, "L");
    $pdf->Cell(20, 10, ": Mutu", 0, 0, "L");

    $pdf->SetFont('Times', 'B', 10);
    $pdf->Ln(15);
    $pdf->Cell(50, 10, "Jumlah Kredit Komulatif", 0, 0, "L");
    $pdf->SetFont('Times', '', 10);
    $pdf->Cell(30, 10, ": " . $tot_am, 0, 0, "L");

    $pdf->Ln(5);
    $pdf->SetFont('Times', 'B', 10);

    $pdf->Cell(50, 10, "Index Prestasi Komulatif", 0, 0, "L");
    $pdf->SetFont('Times', '', 10);
    $pdf->Cell(30, 10, ": " . $ipk, 0, 0, "L");
}

$pdf->Output();


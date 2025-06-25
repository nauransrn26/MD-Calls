<h1><b>Data Admin</b></h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#"><i class="fa fa-files-o"></i> Data Admin</a></li>
</ol>

<hr>
<h5><b><?php echo date("l, M Y"); ?></b></h5>
<hr>

<h5><b>Data Admin</b></h5>
<p>Berikut ini adalah data admin yang terdaftar di MD Calls:</p><br>

<?php
$query = "SELECT * FROM admin where username='$username'";
$sqli  = mysqli_query($conn, $query);
if ($sqli) {
    $data = mysqli_fetch_array($sqli);
}
?>
<div class="row">
    <div class="box box-info">
        <div class="box-header">
            <i class="fa fa-user"></i>
            <h3 class="box-title">Data Pribadi</h3>
        </div>
        <div class="box-body">
            <div class="col-sm-6">
                <table class="table table-hover table-bordered table-stiped">
                    <tr>
                        <td><b>USERNAME </b></td>
                        <td>: <b><?php echo $data['username']; ?></b></td>
                    </tr>
                    <tr>
                        <td><b>ID ADMIN</b></td>
                        <td>: <b><?php echo $data['id']; ?></b></td>
                    </tr>
                    <tr>
                        <td><b>Nama Admin</b></td>
                        <td>: <b><?php echo $data['nama']; ?></b></td>
                    </tr>
                    <tr>
                        <td><b>Email</b></td>
                        <td>: <b><?php echo $data['email']; ?></b></td>
                    </tr>
                    
                </table>
            </div>

            <div class="col-sm-6">
                <?php
                if (isset($_SESSION['pesan']) && $_SESSION['pesan'] != "") {
                    echo $_SESSION['pesan'];
                    unset($_SESSION['pesan']);
                } else echo "";
                ?>
                <form action="" method="post" enctype="multipart/form-data">
                    
                    <?php
                    
                    ?>
                   
                   
                    </form>
            </div>

            <div class="col-sm-12" style="margin-top:20px;">
                <p><b>Note : </b>
                    <font color="red">Jika ada kesalahan pada data Anda, harap hubungi pimpinan untuk memperbaiki.</font>
                </p>
            </div>
        </div>
    </div>
</div>

<?php
function random_string($key)
{

    $length = 30;
    $keys = array_merge(range(0, 9), range('a', 'z'));

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }

    return $key . '.jpg';
}

$imageTypes = array(
    1 => 'GIF',
    2 => 'JPEG',
    3 => 'PNG',
    4 => 'SWF',
    5 => 'PSD',
    6 => 'BMP',
    7 => 'TIFF_II',
    8 => 'TIFF_MM',
    9 => 'JPC',
    10 => 'JP2',
    11 => 'JPX',
    12 => 'JB2',
    13 => 'SWC',
    14 => 'IFF',
    15 => 'WBMP',
    16 => 'XBM',
    17 => 'ICO'
);

if (isset($_POST['upload'])) {

    if ($_FILES['dp']['tmp_name'] == "") {
        $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Display Profil tidak boleh kosong!
                              </div>";
        echo "<script>window.location = 'index.php?page=2'</script>";
    } else {
        $type   = exif_imagetype($_FILES['dp']['tmp_name']);
        $types  = $imageTypes[$type];
        if ($types != 'JPEG' && $types != 'GIF' && $types != 'PNG' && $types != 'JPG') {
            $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Image harus dalam format JPEG, GIF, PNG atau JPG!
                              </div>";
            echo "<script>window.location = 'index.php?page=2'</script>";
        } else {

            $nil = array();
            $dp_name = basename($_FILES["dp"]["name"]);
            $nil = explode('.', $dp_name);
            $dp_name = random_string($nil[0]);

            $target_file = "../images/" . $dp_name;

            if (move_uploaded_file($_FILES["dp"]["tmp_name"], $target_file)) {
                if ($query) {
                    $_SESSION['pesan'] = "<div class='alert alert-success' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Berhasil Ubah DP Profil!
                              </div>";
                    echo "<script>window.location = 'index.php?page=2'</script>";
                } else echo mysqli_error($conn);
            } else {
                $_SESSION['pesan'] = "<div class='alert alert-danger' style='margin-top:5px;'>
                                <a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                Gagal Ubah DP Profil!
                              </div>";
                echo "<script>window.location = 'index.php?page=2'</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<h1>Home</h1>

<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
</ol>

<hr>

<h5><b><?php echo date("l, M Y"); ?></b></h5>
<center>
    <h2>Selamat Datang  <b>Pasien</b></h2>
    
</center>

<html>
<head>
    <title>Alert Gambar dengan SweetAlert</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        setTimeout(function() {
            Swal.fire({
                title: 'Selamat Datang!',
                text: 'Pasien MD Calls!',
                icon: 'success',
            })
        }, 0);
    </script>
</head>
<body>
</body>
</html>

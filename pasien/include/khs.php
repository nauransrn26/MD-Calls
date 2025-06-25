<!DOCTYPE html>
<html>
<head>
  <title>LAPORAN JAMAAH TRAVEL UMROH AMJAD</title>
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
</head>
<body>
<body onload="print()">
<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="frmedit">
  <?php
    

   
    // Error handling (consider using PDO exceptions later)
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }

    $no = 0;
    $sql = "SELECT * FROM pembayaran";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
      echo '<h2>Kwitansi Pembayaran</h2>';
      echo '<table id="table_id" class="table table-striped table-bordered" cellspacing="0" width="100%">';
      echo '<thead>';
      echo '<tr>';
      echo '<th>Id Pembayaran</th>';
      echo '<th>Id Transaksi</th>';
      echo '<th>Tanggal Bayar</th>';
      echo '<th>Jumlah Bayar</th>';
      echo '<th>Metode</th>';
      echo '<th>Bukti Bayar</th>';
      echo '</tr>';
      echo '</thead>';
      echo '<tbody>';

      while ($data = mysqli_fetch_array($result)) {
        $no++;
        echo '<tr>';
        echo '<td>' . $data[0] . '</td>'; 
        echo '<td>' . $data[1] . '</td>'; 
        echo '<td>' . $data[2] . '</td>'; 
        echo '<td>' . $data[3] . '</td>'; 
        echo '<td>' . $data[4] . '</td>'; 
        echo '<td>' . $data[5] . '</td>'; 
        
        echo '</td>';
        echo '</tr>';
      }

      echo '</tbody>';
      echo '</table>';
    } else {
      echo '<h2>No data found!</h2>';
    }

    // Close the connection (consider using mysqli_close() later)
    mysqli_close($conn);
  ?>

  <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#table_id').DataTable();

      // Add a print button with click event handler
      $("#print-button").click(function() {
        // Get the table element
        var table = document.getElementById("table_id");}
<!DOCTYPE html>
<html>
<head>
    <title>Pesan Pembuka</title>
    <style>
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <button onclick="showPopup()">Tampilkan Pesan</button>
    <div class="popup" id="myPopup">Selamat datang!</div>

    <script>
        function showPopup() {
            document.getElementById("myPopup").style.display = "block";
        }
    </script>
</body>
</html>
<?php
include '../config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$id = $_GET['id'];

$data = $conn->query("SELECT * FROM transaksi WHERE id='$id'");
$row = $data->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Struk Parkir</title>
    <style>
        body { font-family: monospace; }
        .struk { width: 300px; }
        hr { border: 1px dashed black; }
    </style>
</head>
<body onload="window.print()">

<div class="struk">
    <h3>STRUK PARKIR</h3>
    <hr>

    ID Transaksi : <?= $row['id']; ?> <br>
    Card ID      : <?= $row['card_id']; ?> <br>
    Masuk        : <?= $row['checkin_time']; ?> <br>
    Keluar       : <?= $row['checkout_time']; ?> <br>
    Durasi       : <?= $row['duration']; ?> Jam <br>

    <hr>
    Biaya        : Rp <?= $row['fee']; ?> <br>
    <hr>

    Terima Kasih
</div>

</body>
</html>

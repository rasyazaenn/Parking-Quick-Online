<?php
include '../config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$card_id = $_GET['card_id'] ?? '';

if ($card_id == '') {
    echo "CARD_ID_KOSONG";
    exit;
}

date_default_timezone_set('Asia/Jakarta');
$checkout = date('Y-m-d H:i:s');

$data = $conn->query("
    SELECT * FROM transaksi
    WHERE card_id='$card_id' AND status='IN'
    ORDER BY id DESC LIMIT 1
");

if ($data->num_rows == 0) {
    echo "BELUM_CHECKIN";
    exit;
}

$row = $data->fetch_assoc();

$masuk = strtotime($row['checkin_time']);
$keluar = strtotime($checkout);

$durasi = ceil(($keluar - $masuk) / 3600);
if ($durasi < 1) $durasi = 1;

$biaya = $durasi * 2000;

$conn->query("
    UPDATE transaksi SET
        checkout_time='$checkout',
        duration='$durasi',
        fee='$biaya',
        status='OUT'
    WHERE id='{$row['id']}'
");

echo "SUCCESS|$durasi|$biaya";
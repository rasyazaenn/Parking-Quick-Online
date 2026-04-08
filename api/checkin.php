<?php
include '../config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$card_id = $_GET['card_id'];
date_default_timezone_set('Asia/Jakarta');
$waktu = date('Y-m-d H:i:s');

/* VALIDASI → Tidak boleh sudah IN */
$cek = $conn->query("
    SELECT * FROM transaksi 
    WHERE card_id='$card_id' AND status='IN'
");

if ($cek->num_rows > 0) {
    echo "SUDAH_PARKIR";
    exit;
}

/* INSERT CHECKIN */
$conn->query("
    INSERT INTO transaksi (card_id, checkin_time, status)
    VALUES ('$card_id', '$waktu', 'IN')
");

echo "CHECKIN BERHASIL";

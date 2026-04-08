<?php
include '../config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$status = $_GET['status'];

$result = $conn->query("
    SELECT * FROM transaksi 
    WHERE status='$status'
    ORDER BY id DESC
");

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

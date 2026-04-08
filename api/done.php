<?php
include '../config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$id = $_GET['id'];

$conn->query("
    UPDATE transaksi
    SET status='DONE'
    WHERE id='$id'
");

echo "DONE_OK";

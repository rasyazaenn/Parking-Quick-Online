<?php
class Transaksi extends Model {

    public function getAll() {
        return $this->db->query("SELECT * FROM transaksi ORDER BY id DESC");
    }

    public function checkin($card_id) {
        date_default_timezone_set('Asia/Jakarta');
        $waktu = date("Y-m-d H:i:s");

        $cek = $this->db->query("
            SELECT * FROM transaksi 
            WHERE card_id='$card_id' AND status='IN'
        ");

        if ($cek->num_rows > 0) return;

        $this->db->query("
            INSERT INTO transaksi (card_id, checkin_time, status)
            VALUES ('$card_id', '$waktu', 'IN')
        ");
    }

    public function checkout($card_id) {
        date_default_timezone_set('Asia/Jakarta');
        $waktu = date("Y-m-d H:i:s");

        $data = $this->db->query("
            SELECT * FROM transaksi
            WHERE card_id='$card_id' AND status='IN'
            ORDER BY id DESC LIMIT 1
        ");

        if ($data->num_rows == 0) return;

        $row = $data->fetch_assoc();

        $masuk = strtotime($row['checkin_time']);
        $keluar = strtotime($waktu);

        $durasi = ceil(($keluar - $masuk) / 3600);
        if ($durasi < 1) $durasi = 1;

        $biaya = $durasi * 2000;

        $this->db->query("
            UPDATE transaksi SET
            checkout_time='$waktu',
            duration='$durasi',
            fee='$biaya',
            status='OUT'
            WHERE id='{$row['id']}'
        ");
    }
}
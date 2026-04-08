<?php 

// ================= ROOT =================
define('ROOT', dirname(__DIR__));

// ================= LOAD CONFIG =================
require_once ROOT . '/config.php';

// ================= LOAD CORE =================
require_once ROOT . '/app/core/Database.php';
require_once ROOT . '/app/core/Model.php';
require_once ROOT . '/app/models/Transaksi.php';
require_once ROOT . '/app/core/phpMQTT.php';

use Bluerhinos\phpMQTT;

/* ================= MQTT ================= */
$server = "broker.hivemq.com";
$port   = 1883;
$client_id = "PHPListener-" . rand(1000,9999);

$mqtt = new phpMQTT($server, $port, $client_id);

if (!$mqtt->connect()) {
    exit("❌ Gagal connect MQTT\n");
}

echo "✅ MQTT Connected...\n";

/* ================= SUBSCRIBE ================= */
$topics = [
    "parking/rasya/entry/rfid" => ["qos"=>0,"function"=>"prosesMasuk"],
    "parking/rasya/exit/rfid"  => ["qos"=>0,"function"=>"prosesKeluar"]
];

$mqtt->subscribe($topics, 0);

/* ================= LOOP ================= */
while ($mqtt->proc()) {}

$mqtt->close();

/* ================= MASUK ================= */
function prosesMasuk($topic, $msg)
{
    $data = json_decode($msg, true);

    if (!isset($data['rfid'])) {
        echo "⚠️ Format salah (ENTRY)\n";
        return;
    }

    $card_id = $data['rfid'];

    $transaksi = new Transaksi();
    $transaksi->checkin($card_id);

    echo "✅ CHECKIN: $card_id\n";
}

/* ================= KELUAR ================= */
function prosesKeluar($topic, $msg)
{
    global $mqtt;

    $data = json_decode($msg, true);

    if (!isset($data['rfid'])) {
        echo "⚠️ Format salah (EXIT)\n";
        return;
    }

    $card_id = $data['rfid'];

    $transaksi = new Transaksi();
    $transaksi->checkout($card_id);

    $db = new Database();
    $res = $db->query("
        SELECT * FROM transaksi 
        WHERE card_id='$card_id'
        ORDER BY id DESC LIMIT 1
    ");

    if ($res && $res->num_rows > 0) {

        $row = $res->fetch_assoc();
        $biaya = $row['fee'] ?? 0;

        if (!$biaya) {
            echo "⚠️ Biaya tidak ditemukan\n";
            return;
        }

        echo "✅ CHECKOUT: $card_id | Rp $biaya\n";

        if ($mqtt && $mqtt->connected) {

            // buka palang
            $mqtt->publish("parking/rasya/exit/servo", "OPEN", 0);

            // tampilkan biaya ke OLED
            $mqtt->publish("parking/rasya/lcd", $biaya . "|", 0);

            echo "📡 Kirim ke ESP32\n";
        }
    } else {
        echo "❌ Data tidak ditemukan\n";
    }
}
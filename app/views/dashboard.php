<!DOCTYPE html>
<html>
<head>
    <title>Parqo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            background: #eef1f5;
            font-family: "Segoe UI", sans-serif;
        }

        .container-box {
            max-width: 1200px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        h2 {
            font-weight: 600;
            margin-bottom: 20px;
        }

        h3 {
            margin-top: 35px;
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        table {
            background: white;
        }

        th {
            text-align: center;
            vertical-align: middle;
        }

        td {
            vertical-align: middle;
        }

        .btn {
            border-radius: 6px;
        }

        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container-box">

    <div class="header-bar">
        <h2>Dashboard Parking Quick Online</h2>
        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>

    <!-- ================= IN ================= -->
    <h3>Kendaraan Sedang Parkir (IN)</h3>
    <table class="table table-bordered table-hover" id="inTable">
        <tr class="table-primary">
            <th width="60">No</th>
            <th>Card ID</th>
            <th>Waktu Masuk</th>
            <th width="120">Status</th>
        </tr>
    </table>

    <!-- ================= OUT ================= -->
    <h3>Kendaraan Akan Keluar (OUT)</h3>
    <table class="table table-bordered table-hover" id="outTable">
        <tr class="table-warning">
            <th width="60">No</th>
            <th>Card ID</th>
            <th>Masuk</th>
            <th>Keluar</th>
            <th width="120">Durasi (Jam)</th>
            <th width="120">Biaya</th>
            <th width="120">Aksi</th>
        </tr>
    </table>

    <!-- ================= DONE ================= -->
    <h3>Log Aktivitas Parkir (DONE)</h3>
    <table class="table table-bordered table-hover" id="doneTable">
        <tr class="table-success">
            <th width="60">No</th>
            <th>Card ID</th>
            <th>Masuk</th>
            <th>Keluar</th>
            <th width="120">Durasi</th>
            <th width="120">Biaya</th>
            <th width="120">Aksi</th>
        </tr>
    </table>

</div>

<script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
<script>

// ================= MQTT =================
const client = mqtt.connect('wss://broker.hivemq.com:8884/mqtt');

client.on('connect', () => {
    console.log("MQTT Connected");
});

function bukaPalang(id, cardId) {

    fetch(`http://localhost/parkir_app/api/done.php?id=${id}`)
        .then(res => res.text())
        .then(result => {

            client.publish('parking/rasya/exit/servo', 'OPEN');
            client.publish('parking/rasya/lcd', 'Terima Kasih|OK');

            refreshTables();
        });
}

// ================= LOAD DATA =================
function loadData(status, tableId) {

    fetch(`http://localhost/parkir_app/api/getdata.php?status=${status}`)
        .then(res => res.json())
        .then(data => {

            const table = document.getElementById(tableId);

            while (table.rows.length > 1) {
                table.deleteRow(1);
            }

            let nomor = 1;

            data.forEach(row => {

                const newRow = table.insertRow();

                if (status === 'IN') {
                    newRow.insertCell(0).innerHTML = nomor++;
                    newRow.insertCell(1).innerHTML = row.card_id;
                    newRow.insertCell(2).innerHTML = row.checkin_time;
                    newRow.insertCell(3).innerHTML = row.status;
                }

                if (status === 'OUT') {
                    newRow.insertCell(0).innerHTML = nomor++;
                    newRow.insertCell(1).innerHTML = row.card_id;
                    newRow.insertCell(2).innerHTML = row.checkin_time;
                    newRow.insertCell(3).innerHTML = row.checkout_time;
                    newRow.insertCell(4).innerHTML = row.duration + " Jam";
                    newRow.insertCell(5).innerHTML = "Rp " + row.fee;

                    newRow.insertCell(6).innerHTML =
                        `<button class="btn btn-warning btn-sm"
                            onclick="bukaPalang(${row.id}, '${row.card_id}')">
                            Buka Palang
                         </button>`;
                }

                if (status === 'DONE') {
                    newRow.insertCell(0).innerHTML = nomor++;
                    newRow.insertCell(1).innerHTML = row.card_id;
                    newRow.insertCell(2).innerHTML = row.checkin_time;
                    newRow.insertCell(3).innerHTML = row.checkout_time;
                    newRow.insertCell(4).innerHTML = row.duration + " Jam";
                    newRow.insertCell(5).innerHTML = "Rp " + row.fee;

                    newRow.insertCell(6).innerHTML =
                        `<button class="btn btn-success btn-sm"
                            onclick="cetakStruk(${row.id})">
                            Cetak Struk
                         </button>`;
                }

            });

        });
}

// ================= BUKA PALANG =================
function bukaPalang(id, cardId) {

    fetch(`http://localhost/parkir_app/api/done.php?id=${id}`)
        .then(res => res.text())
        .then(result => {

            console.log("DONE:", result);

            client.publish('parking/rasya/exit/servo', 'OPEN');
            client.publish('parking/rasya/lcd', 'Terima Kasih Selamat Jalan');

            refreshTables();
        });
}

// ================= CETAK STRUK =================
function cetakStruk(id) {
    window.open(`http://localhost/parkir_app/api/struk.php?id=${id}`, '_blank');
}

// ================= REFRESH =================
function refreshTables() {
    loadData('IN', 'inTable');
    loadData('OUT', 'outTable');
    loadData('DONE', 'doneTable');
}

// Auto refresh
setInterval(refreshTables, 2000);

// Load awal
refreshTables();

</script>

</body>
</html>
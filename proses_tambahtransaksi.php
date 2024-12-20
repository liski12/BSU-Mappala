<?php
include 'config.php'; // Sesuaikan dengan file koneksi Anda
// Ambil data nasabah
$sql_nasabah = "SELECT id_nasabah, nama_nasabah FROM nasabah";
$result_nasabah = $conn->query($sql_nasabah);

// Proses penyimpanan transaksi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_nasabah = $_POST['id_nasabah'];
    $jumlah_transaksi = $_POST['jumlah_transaksi'];

    // Ambil total saldo awal dari setoran sampah yang terbaru
    $sql_saldo_awal = "SELECT SUM(harga) AS saldo FROM setoran WHERE id_nasabah = ?";
    $stmt_saldo = $conn->prepare($sql_saldo_awal);
    $stmt_saldo->bind_param("i", $id_nasabah);
    $stmt_saldo->execute();
    $result_saldo = $stmt_saldo->get_result();
    $row_saldo = $result_saldo->fetch_assoc();
    $saldo_awal = $row_saldo['saldo'] ?? 0; // Default 0 jika tidak ada saldo

    // Hitung sisa saldo setelah transaksi
    $sisa_saldo = $saldo_awal - $jumlah_transaksi;

    // Simpan transaksi ke tabel transaksi
    $sql_transaksi = "INSERT INTO transaksi (id_nasabah, total_saldo, sisa_saldo, jumlah_transaksi, tanggal_tariksaldo, manual_entry) 
                      VALUES (?, ?, ?, ?, NOW(), 1)";
    $stmt_transaksi = $conn->prepare($sql_transaksi);
    $stmt_transaksi->bind_param("iiii", $id_nasabah, $saldo_awal, $sisa_saldo, $jumlah_transaksi);
    
    if ($stmt_transaksi->execute()) {
        echo "Transaksi berhasil ditambahkan.";
    } else {
        echo "Error: " . $stmt_transaksi->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi</title>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #cce1ff;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #4a90e2;
            text-align: center;
            font-family: 'Concert One', cursive;
            margin-bottom: 20px;
        }
        .form-container {
            width: 40%;
            margin: 0 auto;
            padding: 40px;
            background-color: #d4f5c7;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        .form-container input, .form-container select {
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .button-container {
            display: flex;
            flex-direction: column; /* Membuat tombol berurutan vertikal */
            align-items: center; /* Posisi tombol di tengah */
            gap: 10px; /* Jarak antar tombol */
            margin-top: 20px;
        }
        .button-container button, .button-container a {
            width: 20%; /* Tombol memenuhi lebar */
            padding: 10px 18px; /* Ukuran tombol seragam */
            font-size: 15px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            box-sizing: border-box;
        }
        button {
            background-color: #4caf50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .back-link {
            color: #4a90e2;
            border: 1px solid #4a90e2;
            display: inline-block;
            text-align: center;
        }
        .back-link:hover {
            background-color: #4a90e2;
            color: white;
        }
    </style>
</head>
<body>

<h1>Tambah Transaksi Nasabah</h1>

<div class="form-container">
    <form method="POST" action="">
        <label for="id_nasabah">Nama Nasabah:</label>
        <select name="id_nasabah" id="id_nasabah" required onchange="getSaldo()">
            <option value="">Pilih Nasabah</option>
            <?php while ($row = $result_nasabah->fetch_assoc()) { ?>
                <option value="<?php echo $row['id_nasabah']; ?>"><?php echo $row['nama_nasabah']; ?></option>
            <?php } ?>
        </select>

        <label for="saldo_awal">Saldo Awal:</label>
        <input type="text" id="saldo_awal" name="saldo_awal" readonly />

        <label for="jumlah_transaksi">Jumlah Transaksi:</label>
        <input type="number" id="jumlah_transaksi" name="jumlah_transaksi" required oninput="hitungSisaSaldo()" />

        <label for="sisa_saldo">Sisa Saldo:</label>
        <input type="text" id="sisa_saldo" name="sisa_saldo" readonly />

        <div class="button-container">
            <button type="submit">Simpan</button>
            <a href="transaksi.php" class="back-link">Kembali</a>
        </div>
    </form>
</div>

<script>
    function getSaldo() {
        var idNasabah = document.getElementById('id_nasabah').value;
        if (idNasabah) {
            fetch('get_saldo.php?id_nasabah=' + idNasabah)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data); // Cek apakah data yang diterima sudah benar
                    document.getElementById('saldo_awal').value = data.saldo;
                    hitungSisaSaldo();
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }
    }

    function hitungSisaSaldo() {
        var saldoAwal = parseFloat(document.getElementById('saldo_awal').value) || 0;
        var jumlahTransaksi = parseFloat(document.getElementById('jumlah_transaksi').value) || 0;
        document.getElementById('sisa_saldo').value = saldoAwal - jumlahTransaksi;
    }
</script>


</body>
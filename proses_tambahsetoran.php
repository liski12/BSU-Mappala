<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "bsumappala");

// Periksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$id_nasabah = $_GET['id'] ?? null;
if (!$id_nasabah) {
    die("ID Nasabah tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $tanggal_setor = $_POST['tanggal_setor'] ?? null;
    $jenis_sampah = $_POST['jenis_sampah'] ?? null;
    $berat = (float) ($_POST['berat'] ?? 0);

    if (!$tanggal_setor || !$jenis_sampah || $berat <= 0) {
        die("Data tidak lengkap atau tidak valid.");
    }

    // Ambil harga per kg dari database
    $query_harga = $koneksi->prepare("SELECT harga FROM daftar_harga WHERE kode = ?");
    $query_harga->bind_param("s", $jenis_sampah);
    $query_harga->execute();
    $result = $query_harga->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $harga_per_kg = (float) $row['harga'];
        $saldo = $berat * $harga_per_kg;

        // Masukkan data ke tabel setoran
        $query = $koneksi->prepare(
            "INSERT INTO setoran (id_nasabah, tanggal_setor, jenis_sampah, berat, harga) 
            VALUES (?, ?, ?, ?, ?)"
        );
        $query->bind_param("issdd", $id_nasabah, $tanggal_setor, $jenis_sampah, $berat, $harga_per_kg);
        

        if ($query->execute()) {
            // Perbarui data transaksi (atau tambahkan jika belum ada)
            $query_transaksi = $koneksi->prepare(
                "INSERT INTO transaksi (id_nasabah, sisa_saldo, tanggal_tariksaldo) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE sisa_saldo = sisa_saldo + VALUES(sisa_saldo)"
            );
            $tanggal_tariksaldo = date("Y-m-d H:i:s");
            $query_transaksi->bind_param("ids", $id_nasabah, $saldo, $tanggal_tariksaldo);
            $query_transaksi->execute();

            header("Location: setoransampah.php?id=$id_nasabah");
            exit;
        } else {
            die("Gagal menambahkan setoran: " . $query->error);
        }
    } else {
        die("Harga untuk jenis sampah tidak ditemukan.");
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Setoran Sampah</title>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #cce1ff;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .form-box {
            background-color: #d4f5c7;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        h1 {
            text-align: center;
            font-family: 'Concert One', cursive;
            color: #4a90e2;
            margin-top: 40px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: normal;
            color: black;
        }
        input[type="text"], input[type="number"], input[type="date"], select {
            padding: 10px;
            font-size: 14px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        input[type="number"]:read-only {
            background-color: #f9f9f9;
        }
        .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }
        .button-container button, .button-container a {
            width: 40%;
            padding: 10px 18px;
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
    <h1>Tambah Setoran Sampah</h1>
    <div class="container">
        <div class="form-box">
            <form method="POST" action="">
                <label for="tanggal_setor">Tanggal Setor:</label>
                <input type="date" name="tanggal_setor" required>

                <label for="jenis_sampah">Jenis Sampah:</label>
                <select name="jenis_sampah" required>
                    <?php
                    $query_sampah = "SELECT kode, nama FROM daftar_harga ORDER BY kode";
                    $result_sampah = $koneksi->query($query_sampah);
                    while ($row = $result_sampah->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['kode']) . "'>" . htmlspecialchars($row['nama']) . "</option>";
                    }
                    ?>
                </select>

                <label for="berat">Berat (Kg):</label>
                <input type="number" name="berat" step="any" min="0.01" required>

                <div class="button-container">
                    <button type="submit">Tambah Setoran</button>
                    <a href="setoransampah.php?id=<?= $id_nasabah ? urlencode($id_nasabah) : ''; ?>" class="back-link">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

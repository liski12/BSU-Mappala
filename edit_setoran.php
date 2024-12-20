<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "bsumappala");

// Periksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil data daftar_harga
$query_daftar_harga = $koneksi->query("SELECT * FROM daftar_harga");
$daftar_harga_list = [];
while ($row = $query_daftar_harga->fetch_assoc()) {
    $daftar_harga_list[] = $row;
}

// Ambil data setoran berdasarkan ID
$id_setoran = $_GET['id'] ?? null;
if (!$id_setoran) {
    die("ID Setoran tidak ditemukan.");
}

$query_setoran = $koneksi->prepare("SELECT * FROM setoran WHERE id_setoran = ?");
$query_setoran->bind_param("i", $id_setoran);
$query_setoran->execute();
$result_setoran = $query_setoran->get_result();
$data_setoran = $result_setoran->fetch_assoc();

if (!$data_setoran) {
    die("Data setoran tidak ditemukan.");
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
    $result_harga = $query_harga->get_result();
    $row_harga = $result_harga->fetch_assoc();

    if ($row_harga) {
        // Hitung ulang harga total berdasarkan berat
        $harga_per_kg = (float) $row_harga['harga'];
        $harga_total = $berat * $harga_per_kg;

        // Update data setoran
        $query_update = $koneksi->prepare(
            "UPDATE setoran SET tanggal_setor = ?, jenis_sampah = ?, berat = ?, harga = ? WHERE id_setoran = ?"
        );
        $query_update->bind_param("ssddi", $tanggal_setor, $jenis_sampah, $berat, $harga_total, $id_setoran);

        if ($query_update->execute()) {
            header("Location: setoransampah.php?id=" . $data_setoran['id_nasabah']);
            exit;
        } else {
            die("Gagal mengupdate setoran: " . $query_update->error);
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
    <title>Edit Setoran Nasabah</title>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #cce1ff;
            margin: 0;
            padding: 0;
        }

        
        .container {
            padding: 20px;
            max-width: 600px;
            margin: 50px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            font-family: 'Concert One', cursive;
            color: #4a90e2;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin: 10px 0 5px;
            font-weight: normal;
        }
        input, select {
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px 15px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 4px;
            margin-top: 20px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        function updateHarga() {
            const jenisSampah = document.getElementById('jenis_sampah');
            const berat = parseFloat(document.getElementById('berat').value) || 0;
            const hargaPerKg = parseFloat(jenisSampah.options[jenisSampah.selectedIndex].getAttribute('data-harga')) || 0;

            document.getElementById('harga').value = berat * hargaPerKg;
            document.getElementById('harga_per_kg').value = hargaPerKg;
        }
    </script>
</head>
<body>
        
    <div class="container">
        <h1>Edit Setoran</h1>
        <form method="post">
            <label for="tanggal_setor">Tanggal Setor:</label>
            <input type="date" id="tanggal_setor" name="tanggal_setor" value="<?= htmlspecialchars($data_setoran['tanggal_setor']); ?>" required>

            <label for="jenis_sampah">Jenis Sampah:</label>
            <select id="jenis_sampah" name="jenis_sampah" onchange="updateHarga()" required>
                <?php foreach ($daftar_harga_list as $item): ?>
                    <option 
                        value="<?= htmlspecialchars($item['kode']); ?>" 
                        data-harga="<?= htmlspecialchars($item['harga']); ?>" 
                        <?= $data_setoran['jenis_sampah'] === $item['kode'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($item['nama']); ?> 
                        (Rp <?= number_format($item['harga'], 0, ',', '.'); ?>/Kg)
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="berat">Berat (Kg):</label>
            <input type="number" step="0.01" id="berat" name="berat" value="<?= htmlspecialchars($data_setoran['berat']); ?>" oninput="updateHarga()" required>

            <label for="harga">Harga:</label>
            <input type="number" id="harga" name="harga" value="<?= htmlspecialchars($data_setoran['harga']); ?>" readonly>

            <input type="hidden" id="harga_per_kg" name="harga_per_kg" value="">

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>

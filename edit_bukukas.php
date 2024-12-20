<?php
// Cek apakah 'id' dikirim melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "ID tidak ditemukan.";
    exit;
}

// Koneksi ke database
include 'config.php';

// Query untuk mengambil data berdasarkan ID
$sql = "SELECT * FROM buku_kas WHERE id_buku_kas = '$id'";
$result = $conn->query($sql);

// Cek apakah data ditemukan
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    echo "Data tidak ditemukan.";
    exit;
}

// Cek apakah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_buku_kas = $_POST['id_buku_kas'];

    // Ambil nilai dari form, jika ada input baru gunakan input baru, jika tidak gunakan nilai lama
    $tanggal = !empty($_POST['tanggal']) ? $_POST['tanggal'] : $data['tanggal'];
    $jenis_transaksi = !empty($_POST['jenis_transaksi']) ? $_POST['jenis_transaksi'] : $data['jenis_transaksi'];
    $masuk = isset($_POST['masuk']) ? $_POST['masuk'] : $data['masuk'];
    $keluar = isset($_POST['keluar']) ? $_POST['keluar'] : $data['keluar'];

    // Query update ke database
    $sql_update = "UPDATE buku_kas SET 
                    tanggal = '$tanggal', 
                    jenis_transaksi = '$jenis_transaksi', 
                    masuk = '$masuk', 
                    keluar = '$keluar' 
                   WHERE id_buku_kas = '$id_buku_kas'";

    if ($conn->query($sql_update) === TRUE) {
        echo "Data berhasil diperbarui!";
        header("Location: bukukas.php");
        exit();
    } else {
        echo "Error: " . $sql_update . "<br>" . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku Kas</title>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #cce1ff;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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
            color: #4a90e2;
            text-align: center;
            font-family: 'Concert One', cursive;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input[type="date"],
        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .submit-btn {
            background-color: #4caf50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

        header {
            background-color: #7aa1d2;
            color: white;
            padding: 4px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        header .title {
            font-size: 16px;
            font-weight: bold;
        }

        header img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        header .back {
            font-size: 20px;
            font-weight: bold;
            color: white;
            text-decoration: none;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <header>
        <a href="bukukas.php" class="back">&#8592;</a>
        <span class="title">BSU Sejahtera Abadi Mappala</span>
        <img src="img/logo.png" alt="Logo">
    </header>

    <div class="container">
        <h1>Edit Laporan Buku Kas</h1>
        <form action="" method="POST">
            <input type="hidden" name="id_buku_kas" value="<?php echo htmlspecialchars($data['id_buku_kas'] ?? ''); ?>">
            
            <div class="form-group">
                <label for="tanggal">Tanggal:</label>
                <input type="date" id="tanggal" name="tanggal" 
                       value="<?php echo htmlspecialchars($data['tanggal'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="jenis_transaksi">Jenis Transaksi:</label>
                <input type="text" id="jenis_transaksi" name="jenis_transaksi" 
                       value="<?php echo htmlspecialchars($data['jenis_transaksi'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="masuk">Masuk (Rp):</label>
                <input type="number" id="masuk" name="masuk" min="0" 
                       value="<?php echo htmlspecialchars($data['masuk'] ?? '0'); ?>">
            </div>

            <div class="form-group">
                <label for="keluar">Keluar (Rp):</label>
                <input type="number" id="keluar" name="keluar" min="0" 
                       value="<?php echo htmlspecialchars($data['keluar'] ?? '0'); ?>">
            </div>

            <button type="submit" class="submit-btn">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>

<?php
include 'config.php'; // Sesuaikan dengan file koneksi Anda

// Cek jika ID transaksi ada di parameter GET
if (!isset($_GET['id'])) {
    die("ID transaksi tidak ditemukan.");
}

$id_nasabah = $_GET['id'];

// Query untuk mendapatkan data transaksi berdasarkan ID nasabah
$sql = "
    SELECT 
        nasabah.id_nasabah,
        nasabah.nama_nasabah, 
        transaksi.sisa_saldo, 
        transaksi.tanggal_tariksaldo,
        transaksi.jumlah_transaksi  
    FROM transaksi
    LEFT JOIN nasabah ON nasabah.id_nasabah = transaksi.id_nasabah
    WHERE transaksi.id_nasabah = ? AND transaksi.manual_entry = 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_nasabah);
$stmt->execute();
$result = $stmt->get_result();

// Jika data tidak ditemukan
if ($result->num_rows == 0) {
    die("Data transaksi tidak ditemukan.");
}

$row = $result->fetch_assoc();

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_nasabah = $_POST['nama_nasabah'];
    $jumlah_transaksi = $_POST['jumlah_transaksi'];
    $sisa_saldo = $_POST['sisa_saldo'];
    $tanggal_tariksaldo = $_POST['tanggal_tariksaldo'];

    // Update data transaksi
    $update_sql = "
        UPDATE transaksi 
        SET jumlah_transaksi = ?, sisa_saldo = ?, tanggal_tariksaldo = ?
        WHERE id_nasabah = ?
    ";

    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("dsdi", $jumlah_transaksi, $sisa_saldo, $tanggal_tariksaldo, $id_nasabah);
    
    if ($update_stmt->execute()) {
        echo "<script>window.location.href='transaksi.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data transaksi');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaksi</title>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #cce1ff;
            margin: 0;
            padding: 0;
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
        }
        form {
            display: flex;
            flex-direction: column;
            max-width: 500px;
            margin: 0 auto;
        }
        form input, form select {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        form button {
            padding: 10px;
            background-color: #4caf50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        form button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <header>
        <a href="transaksi.php" class="back">&#8592;</a>
        <span class="title">BSU Sejahtera Abadi Mappala</span>
        <img src="img/logo.png" alt="Logo">
    </header>

    <div class="container">
        <h1>Edit Data Transaksi</h1>

        <form method="POST">
            <label for="nama_nasabah">Nama Nasabah:</label>
            <input type="text" id="nama_nasabah" name="nama_nasabah" value="<?php echo $row['nama_nasabah']; ?>" readonly />

            <label for="jumlah_transaksi">Jumlah Transaksi:</label>
            <input type="number" id="jumlah_transaksi" name="jumlah_transaksi" value="<?php echo $row['jumlah_transaksi']; ?>" required />

            <label for="sisa_saldo">Sisa Saldo:</label>
            <input type="number" id="sisa_saldo" name="sisa_saldo" value="<?php echo $row['sisa_saldo']; ?>" required />

            <label for="tanggal_tariksaldo">Tanggal Transaksi:</label>
            <input type="date" id="tanggal_tariksaldo" name="tanggal_tariksaldo" value="<?php echo date('Y-m-d', strtotime($row['tanggal_tariksaldo'])); ?>" required />

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>

</body>
</html>

<?php
// Menutup koneksi jika perlu
$conn->close();
?>

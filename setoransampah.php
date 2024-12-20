<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "bsumappala");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil ID nasabah dari parameter URL
$id_nasabah = $_GET['id'];

// Ambil parameter bulan pencarian dari URL (jika ada)
$search_month = isset($_GET['search_month']) ? $_GET['search_month'] : '';

// Query data setoran berdasarkan ID nasabah dan bulan jika ada
if ($search_month) {
    // Jika ada pencarian berdasarkan bulan
    $query = $koneksi->prepare("SELECT * FROM setoran WHERE id_nasabah = ? AND DATE_FORMAT(tanggal_setor, '%Y-%m') = ?");
    $query->bind_param("is", $id_nasabah, $search_month);
} else {
    // Jika tidak ada pencarian bulan, tampilkan semua setoran
    $query = $koneksi->prepare("SELECT * FROM setoran WHERE id_nasabah = ?");
    $query->bind_param("i", $id_nasabah);
}
$query->execute();
$result = $query->get_result();

// Query data nasabah untuk informasi header
$query_nasabah = $koneksi->prepare("SELECT nama_nasabah FROM nasabah WHERE id_nasabah = ?");
$query_nasabah->bind_param("i", $id_nasabah);
$query_nasabah->execute();
$result_nasabah = $query_nasabah->get_result();
$data_nasabah = $result_nasabah->fetch_assoc();

// Ambil sisa saldo terbaru dari transaksi untuk ID nasabah
$query_sisa_saldo = $koneksi->prepare("SELECT sisa_saldo FROM transaksi WHERE id_nasabah = ? ORDER BY tanggal_tariksaldo DESC LIMIT 1");
$query_sisa_saldo->bind_param("i", $id_nasabah);
$query_sisa_saldo->execute();
$result_sisa_saldo = $query_sisa_saldo->get_result();
$sisa_saldo = 0;
if ($result_sisa_saldo->num_rows > 0) {
    $data_sisa_saldo = $result_sisa_saldo->fetch_assoc();
    $sisa_saldo = $data_sisa_saldo['sisa_saldo'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Setoran</title>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #cce1ff;
    margin: 0;
    padding: 0;
    overflow: hidden;
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
            max-width: 90%;
            margin: auto;
        }
        h1 {
            text-align: center;
            font-family: 'Concert One', cursive;
            color: #4a90e2;
        }

        .search-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
        .search-bar input {
            padding: 8px;
            width: 200px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .search-bar button {
            padding: 8px 12px;
            border: none;
            background-color: #4caf50;
            color: white;
            cursor: pointer;
            border-radius: 4px;
            margin-left: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        table th {
            background-color: #4caf50;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #e6ffe6;
        }
        table td.action-links a {
            text-decoration: none;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            margin: 0 3px;
        }
        table td .edit {
            background-color: #4caf50;
        }
        table td .delete {
            background-color: #f44336;
        }
        .add-button {
            display: inline-block;
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 20px;
            margin-top: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .add-button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }
        .add-button:active {
            transform: scale(1);
        }
        @media screen and (max-width: 768px) {
            table, th, td {
                font-size: 12px;
                padding: 8px;
            }
            .add-button {
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <a href="profilnasabah.php?id=<?= htmlspecialchars($id_nasabah); ?>" class="back">&#8592;</a>
        <span class="title">BSU Sejahtera Abadi Mappala</span>
        <img src="img/logo.png" alt="Logo">
    </header>
    <div class="container">
        <h1>Daftar Setoran Sampah</h1>

        <!-- Form Pencarian -->
        <div class="search-bar">
            <form method="get" action="">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id_nasabah); ?>">
                <input type="month" name="search_month" value="<?= htmlspecialchars($search_month); ?>" />
                <button type="submit">Cari</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal Setor</th>
                    <th>Jenis Sampah</th>
                    <th>Berat (Kg)</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- PHP Data Loop -->
                <?php 
                    $no = 1; 
                    $total_berat = 0;
                    $total_harga = 0;
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()): 
                            $total_berat += $row['berat'];
                            $total_harga += $row['harga']; // Menjumlahkan harga setiap transaksi setoran
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= date('d-m-Y', strtotime($row['tanggal_setor'])); ?></td>
                    <td><?= htmlspecialchars($row['jenis_sampah']); ?></td>
                    <td><?= number_format($row['berat'], 2, ',', '.'); ?> Kg</td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td class="action-links">
                        <a href="edit_setoran.php?id=<?= $row['id_setoran']; ?>" class="edit">Edit</a>
                        <a href="hapus_setoran.php?id=<?= $row['id_setoran']; ?>&id_nasabah=<?= $id_nasabah; ?>" class="delete">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <tr>
                    <td colspan="3"><b>Total</b></td>
                    <td><b><?= number_format($total_berat, 2, ',', '.'); ?> Kg</b></td>
                    <td colspan="2"><b>Rp <?= number_format($total_harga, 0, ',', '.'); ?></b></td>
                </tr>
                <?php } else { ?>
                    <tr>
                        <td colspan="6">Tidak ada data setoran.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <a href="proses_tambahsetoran.php?id=<?= $id_nasabah; ?>" class="add-button">Tambah Setoran</a>

        <a href="cetak_setoran.php?id=<?= $id_nasabah; ?>&search_month=<?= $search_month; ?>" class="add-button">Cetak Laporan PDF</a>

    </div>
</body>
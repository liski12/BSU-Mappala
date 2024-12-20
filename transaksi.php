<?php
include 'config.php'; // Sesuaikan dengan file koneksi Anda

// Proses pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query untuk mengambil data transaksi yang ditambahkan manual
$sql = "
    SELECT 
        nasabah.id_nasabah,
        nasabah.nama_nasabah, 
        transaksi.sisa_saldo, 
        transaksi.tanggal_tariksaldo,
        transaksi.jumlah_transaksi  
    FROM transaksi
    LEFT JOIN nasabah ON nasabah.id_nasabah = transaksi.id_nasabah
    WHERE transaksi.manual_entry = 1
    AND nasabah.nama_nasabah LIKE ?
    ORDER BY transaksi.tanggal_tariksaldo DESC
";

// Prepared statement untuk transaksi
$stmt = $conn->prepare($sql);
$search_param = "%$search%";
$stmt->bind_param("s", $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Transaksi</title>
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
        }
        h1 {
            color: #4a90e2;
            text-align: center;
            font-family: 'Concert One', cursive;
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
            margin-top: 20px;
            border-collapse: collapse;
            background-color: white;
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
            font-weight: bold;
        }
        table tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Tombol Edit */
        a.edit-button {
            background-color: #4caf50; /* Warna hijau */
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        a.edit-button:hover {
            background-color: #45a049; /* Warna hijau lebih gelap */
        }

        /* Tombol Hapus */
        a.delete-button {
            background-color: #f44336; /* Warna merah */
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        a.delete-button:hover {
            background-color: #e53935; /* Warna merah lebih gelap */
        }

        /* Jarak antar tombol */
        .action-buttons a {
            margin: 0 5px;
        }

        /* Tombol Tambah Transaksi */
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

        /* Responsif Design */
        @media screen and (max-width: 768px) {
            table, th, td {
                font-size: 12px;
                padding: 8px;
            }

            .search-bar input {
                width: 150px;
            }

            .add-button {
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <a href="dashboard.php" class="back">&#8592;</a>
        <span class="title">BSU Sejahtera Abadi Mappala</span>
        <img src="img/logo.png" alt="Logo">
    </header>
    <div class="container">
        <h1>Daftar Transaksi Nasabah</h1>

        <!-- Search bar -->
        <div class="search-bar">
            <form method="get" action="">
                <input type="text" name="search" placeholder="Cari transaksi..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" />
                <button type="submit">Cari</button>
            </form>
        </div>

        <!-- Tabel Transaksi -->
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Nasabah</th>
                    <th>Jumlah Transaksi</th>
                    <th>Sisa Saldo</th>
                    <th>Tanggal Transaksi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $no = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . $no++ . "</td>
                            <td>" . $row['nama_nasabah'] . "</td>
                            <td>Rp " . number_format($row['jumlah_transaksi'], 0, ',', '.') . "</td>
                            <td>Rp " . number_format($row['sisa_saldo'] ?? 0, 0, ',', '.') . "</td>
                            <td>" . (!empty($row['tanggal_tariksaldo']) ? date('d-m-Y', strtotime($row['tanggal_tariksaldo'])) : '-') . "</td>
                            <td class='action-buttons'>
                                <a href='edit_transaksi.php?id=" . $row['id_nasabah'] . "' class='edit-button'>Edit</a>
                                <a href='hapus_transaksi.php?id=" . $row['id_nasabah'] . "' class='delete-button' onclick='return confirm(\"Apakah Anda yakin ingin menghapus transaksi ini?\")'>Hapus</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Tidak ada transaksi</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Tombol Tambah Transaksi -->
        <a href="proses_tambahtransaksi.php" class="add-button">Tambah Transaksi</a>

    </div>

    <?php
    // Menutup koneksi jika perlu
    $conn->close(); 
    ?>
</body>
</html>

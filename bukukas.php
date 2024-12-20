<?php
// Asumsikan koneksi database sudah ada
include 'config.php'; // Sesuaikan dengan file koneksi Anda


// Query untuk mengambil data buku kas
$sql = "SELECT 
            id_buku_kas, 
            tanggal, 
            jenis_transaksi, 
            masuk, 
            keluar, 
            (SELECT COALESCE(SUM(masuk) - SUM(keluar), 0) 
             FROM buku_kas b2 
             WHERE b2.id_buku_kas <= b1.id_buku_kas) AS saldo
        FROM buku_kas b1
        ORDER BY tanggal ASC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Kas Pengurus</title>
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
            margin-bottom: 35px;
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
        .action-buttons a {
            display: inline-block;
            padding: 5px 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            border-radius: 5px;
            margin: 0 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .edit-button {
            background-color: #4caf50; /* Hijau */
            color: white;
        }
        .edit-button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }
        .delete-button {
            background-color: #f44336; /* Merah */
            color: white;
        }
        .delete-button:hover {
            background-color: #e53935;
            transform: scale(1.05);
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
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <a href="dashboard.php" class="back">&#8592;</a>
        <span class="title">BSU Sejahtera Abadi Mappala</span>
        <img src="img/logo.png" alt="Logo">
    </header>
    <div class="container">
        <h1>Buku Kas Pengurus</h1>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Jenis Transaksi</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Saldo</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["id_buku_kas"] . "</td>
                                <td>" . $row["tanggal"] . "</td>
                                <td>" . $row["jenis_transaksi"] . "</td>
                                <td>Rp " . number_format($row["masuk"], 0, ',', '.') . "</td>
                                <td>Rp " . number_format($row["keluar"], 0, ',', '.') . "</td>
                                <td>Rp " . number_format($row["saldo"], 0, ',', '.') . "</td>
                                <td class='action-buttons'>
                                    <a href='edit_bukukas.php?id=" . $row["id_buku_kas"] . "' class='edit-button'>Edit</a>
                                    <a href='hapus_bukukas.php?id=" . $row["id_buku_kas"] . "' class='delete-button'>Hapus</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Tidak ada Laporan</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="proses_tambahkas.php" class="add-button">Tambah</a>
    </div>

    <?php
    // Menutup koneksi jika perlu
    $conn->close(); 
    ?>
</body>
</html>

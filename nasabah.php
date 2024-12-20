<?php
// Konfigurasi database
include 'config.php';

// Ambil data nasabah dari database
$sql = "SELECT * FROM nasabah";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Nasabah</title>
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
        .card-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    padding: 20px;
}

/* Gaya untuk setiap kartu */
.card {
    background-color: #d5f5e3;
    border-radius: 12px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    width: 200px;
    height: auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 15px;
    text-align: center;
}

/* Kartu Tambah Nasabah */
.card.add-nasabah img {
    width: 60px;
    height: 60px;
    margin-bottom: 10px;
}

.card.add-nasabah .add-button {
    background-color: #4caf50;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
    font-weight: bold;
}

.card.add-nasabah .add-button:hover {
    background-color: #45a049;
    transform: scale(1.05);
}

/* Nama Nasabah */
.card .nasabah-name {
    background-color: #4caf50; /* Warna hijau */
    color: white;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    font-weight: bold;
    margin-bottom: 8px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

/* Alamat Nasabah */
.card .nasabah-address {
    font-size: 14px;
    color: #666;
    line-height: 1.4;
    margin-top: 0;
    text-align: center;
}

.card .nasabah-name:hover {
    background-color: #45a049;
    transform: scale(1.05); /* Efek zoom saat hover */
}

/* Pesan jika data kosong */
.empty-message {
    font-size: 16px;
    color: #999;
    margin-top: 20px;
}

    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <a href="dashboard.php" class="back">&#8592;</a>
        <span class="title">BSU Sejahtera Abadi Mappala</span>
        <img src="img/logo.png" alt="Logo">
    </header>

    <!-- Container -->
    <div class="container">
        <h1>Data Nasabah</h1>
        
        <div class="card-container">
    <!-- Card Tambah -->
    <div class="card add-nasabah">
        <img src="img/tambah.png" alt="Tambah Nasabah">
        <button class="add-button" onclick="location.href='tambahnasabah.php'">Tambah Nasabah</button>
    </div>

<!-- Tampilkan Data Nasabah -->
<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card">
            <button 
                type="button" 
                class="nasabah-name" 
                onclick="window.location.href='profilnasabah.php?id=<?php echo $row['id_nasabah']; ?>'">
                <?php echo htmlspecialchars($row['nama_nasabah']); ?>
            </button>
            <p class="nasabah-address">
                <?php echo htmlspecialchars($row['alamat']); ?>
            </p>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="empty-message">Belum ada nasabah yang ditambahkan.</div>
<?php endif; ?>


</body>
</html>

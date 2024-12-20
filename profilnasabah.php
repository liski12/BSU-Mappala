<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "bsumappala");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Validasi parameter URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Parameter id tidak ditemukan atau tidak valid.");
}

error_log("Parameter id: " . $_GET['id']);

// Ambil ID nasabah dari parameter URL
$id_nasabah = $_GET['id'];

// Query data nasabah berdasarkan ID
$query = $koneksi->prepare("SELECT * FROM nasabah WHERE id_nasabah = ?");
$query->bind_param("i", $id_nasabah);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    die("Nasabah tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Profil Nasabah</title>
    <link rel="stylesheet" href="profilnasabah.css">
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <a href="dashboard.php" class="back">&#8592;</a>
        <span class="title">BSU Sejahtera Abadi Mappala</span>
        <img src="img/logo.png" alt="Logo">
    </header>
    <div class="container">
        <aside class="sidebar">
            <div class="profile">
                <h2><?= htmlspecialchars($data['nama_nasabah']); ?></h2>
            </div>
            <nav>
            <button onclick="window.location.href='profilnasabah.php?id=<?= $data['id_nasabah']; ?>'">Detail Profil</button>
            <button onclick="window.location.href='setoransampah.php?id=<?= $data['id_nasabah']; ?>'">Rincian Setoran</button>
        </nav>
        </aside>
        <main class="content">
            <h3>Detail Profil</h3>
            <form class="profile-form">
                <label for="nama">Nama</label>
                <input id="nama" type="text" value="<?= htmlspecialchars($data['nama_nasabah']); ?>" disabled>
                
                <label for="nik">NIK</label>
                <input id="nik" type="text" value="<?= htmlspecialchars($data['nik']); ?>" disabled>
                
                <label for="no-hp">No. HP</label>
                <input id="no-hp" type="text" value="<?= htmlspecialchars($data['no_hp']); ?>" disabled>
                
                <label for="alamat">Alamat</label>
                <input id="alamat" type="text" value="<?= htmlspecialchars($data['alamat']); ?>" disabled>
                
                <label for="tanggal-pendaftaran">Tanggal Pendaftaran</label>
                <input id="tanggal-pendaftaran" type="text" value="<?= htmlspecialchars($data['tanggal_pendaftaran']); ?>" disabled>
                
                <button type="button" class="edit-button" onclick="window.location.href='editprofilnasabah.php?id=<?= $data['id_nasabah']; ?>'">Edit</button>
            </form>
        </main>
    </div>
</body>
</html>

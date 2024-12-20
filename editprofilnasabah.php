<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "bsumappala");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Validasi parameter URL
if (!isset($_GET['id'])) {
    die("Parameter id tidak ditemukan.");
}

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

// Proses update data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_nasabah = $_POST['nama'];
    $nik = $_POST['nik'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];
    $tanggal_pendaftaran = $_POST['tanggal_pendaftaran'];

    // Query untuk update data nasabah
    $update_query = $koneksi->prepare("UPDATE nasabah SET nama_nasabah = ?, nik = ?, no_hp = ?, alamat = ?, tanggal_pendaftaran = ? WHERE id_nasabah = ?");
    $update_query->bind_param("sssssi", $nama_nasabah, $nik, $no_hp, $alamat, $tanggal_pendaftaran, $id_nasabah);

    if ($update_query->execute()) {
        header("Location: profilnasabah.php?id=$id_nasabah"); // Redirect ke halaman profil setelah update berhasil
        exit();
    } else {
        echo "Terjadi kesalahan saat mengupdate data.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Nasabah</title>
    <link rel="stylesheet" href="profilnasabah.css">
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <a href="profilnasabah.php?id=<?= $data['id_nasabah']; ?>" class="back">&#8592;</a>
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
            <h3>Edit Profil Nasabah</h3>
            <form class="profile-form" method="POST" action="">
                <label for="nama">Nama</label>
                <input id="nama" type="text" name="nama" value="<?= htmlspecialchars($data['nama_nasabah']); ?>" required>
                
                <label for="nik">NIK</label>
                <input id="nik" type="text" name="nik" value="<?= htmlspecialchars($data['nik']); ?>" required>
                
                <label for="no-hp">No. HP</label>
                <input id="no-hp" type="text" name="no_hp" value="<?= htmlspecialchars($data['no_hp']); ?>" required>
                
                <label for="alamat">Alamat</label>
                <input id="alamat" type="text" name="alamat" value="<?= htmlspecialchars($data['alamat']); ?>" required>
                
                <label for="tanggal-pendaftaran">Tanggal Pendaftaran</label>
                <input id="tanggal-pendaftaran" type="date" name="tanggal_pendaftaran" value="<?= htmlspecialchars($data['tanggal_pendaftaran']); ?>" required>
                
                <button type="submit" class="save-button">Simpan</button>
            </form>
        </main>
    </div>
</body>
</html>

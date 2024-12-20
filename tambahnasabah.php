<?php
// Konfigurasi database
include 'config.php';

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses ketika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $tanggal = $_POST['tanggal'];
    $nik = $_POST['nik'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];

    // Validasi input kosong
    if (empty($nama) || empty($tanggal) || empty($nik) || empty($no_hp) || empty($alamat)) {
        echo "<script>alert('Semua field wajib diisi!');</script>";
    } else {
        // Query untuk menambahkan data
        $sql = "INSERT INTO nasabah (nama_nasabah, tanggal_pendaftaran, nik, no_hp, alamat) 
                VALUES ('$nama', '$tanggal', '$nik', '$no_hp', '$alamat')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Data nasabah berhasil disimpan!');</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Menutup koneksi
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Nasabah</title>
    <link rel="stylesheet" href="tambahnasabah.css">
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
</head>
<body>
    <h1>Registrasi/Tambah Nasabah</h1>
    <div class="container">
        <main class="content">
            <form class="registration-form" method="POST" action="">
                <input type="text" name="nama" placeholder="Masukkan Nama Nasabah">
                <input type="date" name="tanggal" placeholder="Tanggal Pendaftaran">
                <input type="text" name="nik" placeholder="NIK">
                <input type="text" name="no_hp" placeholder="No. HP">
                <input type="text" name="alamat" placeholder="Alamat">
                <button type="submit" class="save-button">Simpan</button>
                <button type="button" class="view-button" onclick="location.href='nasabah.php';">Lihat Data Nasabah</button>
            </form>
        </main>
    </div>
</body>
</html>

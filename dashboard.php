<?php
session_start();
include 'config.php'; // Koneksi ke database
// Periksa apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Placeholder untuk jumlah sampah dan nasabah, ubah sesuai data dari database
$jumlah_sampah = 0;
$total_berat = 0;
$jumlah_nasabah = 0;
// Query untuk menghitung jumlah sampah
$sql_sampah = "SELECT COUNT(*) as total_sampah FROM setoran"; // Ganti dengan nama tabel sampah yang sesuai
$result_sampah = $conn->query($sql_sampah);
$row_sampah = $result_sampah->fetch_assoc();
$jumlah_sampah = $row_sampah['total_sampah'];

$sql_berat = "SELECT SUM(berat) as total_berat FROM setoran"; // Ganti dengan nama kolom berat sampah yang sesuai
$result_berat = $conn->query($sql_berat);
$row_berat = $result_berat->fetch_assoc();
$total_berat = $row_berat['total_berat'];

// Query untuk menghitung jumlah nasabah
$sql_nasabah = "SELECT COUNT(*) as total_nasabah FROM nasabah"; // Ganti dengan nama tabel nasabah yang sesuai
$result_nasabah = $conn->query($sql_nasabah);
$row_nasabah = $result_nasabah->fetch_assoc();
$jumlah_nasabah = $row_nasabah['total_nasabah'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Tambahkan Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
    <style>
        /* Gaya umum untuk body */
    body {
        font-family: Arial, sans-serif; /* Menggunakan font Arial */
        background-color: #cce1ff; /* Latar belakang berwarna biru muda */
        margin: 0; /* Menghapus margin default */
        padding: 0; /* Menghapus padding default */
    }

    /* Gaya untuk header */
    header {
        background-color: #7aa1d2; /* Warna latar belakang header biru */
        color: white; /* Warna teks putih */
        padding: 10px; /* Padding di sekitar teks */
        text-align: left; /* Teks rata kiri */
    }

    /* Gaya untuk judul di dalam header */
    header h1 {
        margin: 0; /* Menghapus margin */
        font-size: 1.5em; /* Ukuran font lebih besar */
    }

    /* Gaya untuk container utama */
    .container {
        text-align: center; /* Menjajar teks di tengah */
        padding: 20px; /* Padding di sekitar konten */
    }

    /* Gaya untuk teks selamat datang */
    .welcome {
        margin-top: 10px; /* Margin di atas */
        font-family: 'Concert One', cursive; /* Menggunakan font Concert One */
        font-size: 1.5em; /* Ukuran font besar */
        color: #3b5998; /* Warna teks gelap */
        line-height: 1.5; /* Jarak antar baris */
    }

    /* Gaya untuk dua baris teks di dalam welcome */
    .welcome .line-1 {
        font-size: 1.2em; /* Ukuran font lebih besar untuk baris pertama */
        font-weight: bold; /* Membuat font tebal */
    }

    .welcome .line-2 {
        font-size: 1.2em; /* Ukuran font sedikit lebih kecil untuk baris kedua */
        font-weight: bold; /* Font tidak tebal */
    }

    /* Gaya untuk bagian statistik */
    .stats {
        display: flex; /* Menggunakan flexbox untuk penataan */
        justify-content: center; /* Menyusun elemen di tengah */
        margin-top: 20px; /* Margin atas */
    }

    /* Gaya untuk masing-masing box statistik */
    .stats div {
        margin: 0 10px; /* Margin horizontal antar statistik */
        padding: 20px; /* Padding di dalam box */
        background-color: #e0f7d4; /* Latar belakang hijau muda */
        border-radius: 10px; /* Sudut membulat */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Efek bayangan */
        width: 150px; /* Lebar box tetap */
        text-align: center; /* Teks di tengah */
        color: #3b5998;
    }

    /* Gaya untuk teks judul statistik */
    .stats div h2 {
        margin: 0; /* Menghapus margin */
        font-size: 2em; /* Ukuran font besar */
        color: #4CAF50; /* Warna hijau */
    }

    /* Gaya untuk box menu */
    .menu-box {
        margin-top: 40px; /* Margin atas */
        padding: 20px; /* Padding di dalam box */
        background-color: #e0f7d4; /* Latar belakang hijau muda */
        border-radius: 10px; /* Sudut membulat */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Efek bayangan */
        display: inline-block; /* Agar box tampak sejajar dengan elemen lainnya */
        text-align: center; /* Teks di tengah */
    }

    /* Gaya untuk judul menu */
    .menu-title {
        font-family: 'Concert One', cursive; /* Menggunakan font Concert One */
        font-size: 1.5em; /* Ukuran font besar */
        color: #3b5998; /* Warna hijau */
        margin-top: 5px; /* Margin bawah */
        font-weight: bold;
    }

    /* Gaya untuk menu dengan flexbox */
    .menu {
        display: flex; /* Menggunakan flexbox */
        justify-content: center; /* Menyusun menu di tengah */
        gap: 30px; /* Memberikan jarak antar item menu */
    }

.menu .item {
    text-align: center; /* Teks di tengah */
    display: inline-block; /* Agar item tampak sejajar dengan elemen lainnya */
    padding: 10px; /* Padding di sekitar gambar */
    background-color: #cce1ff; /* Latar belakang gambar */
    border-radius: 10%; /* Membuat box bulat */
    width: 100px; /* Menentukan ukuran box */
    height: 100px; /* Menentukan ukuran box */
    display: flex;
    justify-content: center; /* Menyusun gambar di tengah */
    align-items: center; /* Menyusun gambar di tengah secara vertikal */
}

/* Gaya untuk gambar di dalam box */
.menu .item img {
    width: 50px; /* Ukuran gambar */
    height: 50px; /* Ukuran gambar */
}

    /* Gaya untuk teks di bawah gambar menu */
    .menu .item p {
        margin: 10px 0 0; /* Margin di atas */
        font-size: 1em; /* Ukuran font standar */
        color: #333; /* Warna teks gelap */
    }

    /* Gaya untuk sidebar di kanan atas */
    .sidebar {
        position: absolute; /* Posisi absolut di kanan atas */
        top: 6px; /* Jarak dari atas */
        right: 10px; /* Jarak dari kanan */
        background-color: transparent; /* Latar belakang transparan */
        padding: 0; /* Tidak ada padding */
        text-align: center; /* Teks di tengah */
        height: auto; /* Tinggi otomatis */
    }

    /* Gaya untuk dropdown (menu profil) */
    .dropdown {
        position: relative; /* Posisi relatif untuk dropdown */
        display: inline-block; /* Menampilkan secara horizontal */
    }

    /* Gaya untuk konten dropdown yang tersembunyi */
    .dropdown-content {
        display: none; /* Tersembunyi secara default */
        position: absolute; /* Posisi absolut */
        right: 0; /* Posisi di sebelah kanan */
        background-color: #f9f9f9; /* Warna latar belakang putih */
        min-width: 150px; /* Lebar minimal dropdown */
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2); /* Efek bayangan */
        z-index: 1; /* Membuat dropdown muncul di atas elemen lainnya */
        border-radius: 5px; /* Sudut membulat */
    }

    /* Gaya untuk tombol di dalam dropdown */
    .dropdown-content button {
        background-color: #e0f7d4; /* Latar belakang hijau muda */
        color: #333; /* Warna teks gelap */
        border: none; /* Tanpa border */
        padding: 10px 15px; /* Padding di dalam tombol */
        text-align: left; /* Teks rata kiri */
        display: block; /* Menampilkan tombol secara blok */
        width: 100%; /* Lebar tombol 100% */
        font-size: 1em; /* Ukuran font standar */
        border-radius: 5px; /* Sudut membulat */
        cursor: pointer; /* Mengubah kursor menjadi pointer */
    }

    /* Gaya untuk tombol dropdown saat dihover */
    .dropdown-content button:hover {
        background-color: #d4f0c0; /* Mengubah latar belakang menjadi lebih terang */
    }

    /* Gaya untuk menampilkan dropdown saat dihover */
    .dropdown:hover .dropdown-content {
        display: block; /* Menampilkan dropdown saat hover */
    }

    /* Gaya untuk ikon profil */
    .profile-icon {
        width: 40px; /* Ukuran ikon profil */
        border-radius: 50%; /* Membuat ikon menjadi bulat */
        cursor: pointer; /* Menambahkan kursor pointer saat hover */
        margin-bottom: 15px;
        filter: invert(100%) brightness(200%);
    }

    </style>
</head>
<body>
    <header>
        <h1>Dashboard</h1>
    </header>

    <div class="container">
        <!-- Mengganti teks "Selamat Datang" dengan format terpisah -->
        <p class="welcome">
            <span class="line-1">Selamat Datang di BSU</span><br>
            <span class="line-2">Sejahtera Abadi Mappala</span>
        </p>

        <!-- Statistik -->
        <div class="stats">
            <div>
                <h2><?= $jumlah_sampah ?></h2>
                <p>Jumlah Sampah</p>
            </div>
            <div>
                <h2><?= $total_berat ?> kg</h2>
                <p>Total Berat Sampah</p>
            </div>
            <div>
                <h2><?= $jumlah_nasabah ?></h2>
                <p>Jumlah Nasabah</p>
            </div>
        </div>


        <!-- Menu dengan box -->
        <div class="menu-box">
            <p class="menu-title">DAFTAR MENU</p>
            <div class="menu">
                <div class="item">
                    <a href="nasabah.php">
                        <img src="img/nasabah.png" alt="Data Nasabah">
                        <p>Data Nasabah</p>
                    </a>
                </div>
                <div class="item">
                    <a href="transaksi.php">
                        <img src="img/transaksi.png" alt="Catatan Transaksi">
                        <p>Catatan Transaksi</p>
                    </a>
                </div>
                <div class="item">
                    <a href="bukukas.php">
                        <img src="img/bukukas.png" alt="Buku Kas">
                        <p>Buku Kas Pengurus</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar (Profil di kanan atas) -->
    <div class="sidebar">
        <div class="dropdown">
            <img src="img/profil.png" alt="Profil" class="profile-icon">
            <div class="dropdown-content">
                <button onclick="location.href='profil.php'">Profil</button>
                <button onclick="location.href='index.php'">Logout</button>
            </div>
        </div>
    </div>
</body>
</html>

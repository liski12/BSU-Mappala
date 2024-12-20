<?php
// tambahlaporan.php

// Koneksi ke database
include 'config.php'; // Sesuaikan dengan file koneksi Anda

// Cek apakah form sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $tanggal = $_POST['tanggal'];
    $jenis_transaksi = $_POST['jenis_transaksi'];
    $masuk = $_POST['masuk'];
    $keluar = $_POST['keluar'];
    
    // Query untuk menyimpan data
    $sql = "INSERT INTO buku_kas (tanggal, jenis_transaksi, masuk, keluar) 
            VALUES ('$tanggal', '$jenis_transaksi', '$masuk', '$keluar')";

    if ($conn->query($sql) === TRUE) {
        // Jika berhasil, tampilkan pesan sukses dan redirect atau tetap di halaman yang sama
        echo "<script>alert('Data berhasil disimpan!');</script>";
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kas</title>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #cce1ff;
        padding: 30px;
    }
    
    .form-container {
        background-color: #d4f5c7;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        max-width: 400px;
        margin: auto;
    }
    h1 {
        text-align: center;
        color: #4a90e2;
        font-family: 'Concert One', cursive;
    }

    
    input[type="text"], input[type="number"], input[type="date"] {
        width: 95%; /* Memastikan input mengisi seluruh lebar yang tersedia */
        padding: 10px;
        margin-bottom: 15px; /* Memberikan jarak antar input */
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .button-container {
        display: flex;
        flex-direction: column; /* Membuat tombol berurutan vertikal */
        align-items: center; /* Posisi tombol di tengah */
        gap: 10px; /* Jarak antar tombol */
        margin-top: 20px;
    }
    .button-container button, .button-container a {
        width: 30%; /* Tombol memenuhi lebar */
        padding: 10px 18px; /* Ukuran tombol seragam */
        font-size: 15px;
        text-align: center;
        border-radius: 5px;
        text-decoration: none;
        box-sizing: border-box;
    }
    button {
        background-color: #4caf50;
        color: white;
        border: none;
        cursor: pointer;
    }
    button:hover {
        background-color: #45a049;
    }
    .back-link {
        color: #4a90e2;
        border: 1px solid #4a90e2;
        display: inline-block;
        text-align: center;
    }
    .back-link:hover {
        background-color: #4a90e2;
        color: white;
    }
    </style>
</head>
<body>
<h1>Tambah Laporan</h1>
    <div class="form-container">
        <form action="proses_tambahkas.php" method="POST">
            <label for="tanggal">Tanggal</label>
            <input type="date" id="tanggal" name="tanggal" required>
            
            <label for="jenis_transaksi">Jenis Transaksi</label>
            <input type="text" id="jenis_transaksi" name="jenis_transaksi" required>
            
            <label for="masuk">Masuk</label>
            <input type="number" id="masuk" name="masuk" placeholder="Masukkan jumlah masuk (Rp)" value="0" required>
            
            <label for="keluar">Keluar</label>
            <input type="number" id="keluar" name="keluar" placeholder="Masukkan jumlah keluar (Rp)" value="0" required>
            
            <div class="button-container">
                <button type="submit">Simpan</button>
                <a href="bukukas.php" class="back-link">Kembali</a>
            </div>
        </form>
    </div>
</body>
</html>

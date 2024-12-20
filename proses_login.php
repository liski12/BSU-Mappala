<?php
session_start();
include 'config.php'; // Pastikan file config.php sudah terhubung ke database Anda

$username = $_POST['username'];
$password = $_POST['password'];

// Mencegah SQL Injection
$username = mysqli_real_escape_string($conn, $username);
$password = mysqli_real_escape_string($conn, $password);

// Query untuk memeriksa username
$query = "SELECT * FROM pengelola WHERE username = '$username'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    // Verifikasi password yang terenkripsi
    if (password_verify($password, $user['password'])) {
        // Simpan sesi login
        $_SESSION['username'] = $username;
        $_SESSION['loggedin'] = true; // Tandai bahwa pengguna telah login

        // Arahkan ke halaman dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        // Password salah
        header("Location: index.php?error=Password Salah, Masukan dengan Benar");
        exit();
    }
} else {
    // Username tidak ditemukan
    header("Location: index.php?error=Username Tidak Ditemukan");
    exit();
}

// Tutup koneksi
mysqli_close($conn);
?>

<?php
$host = "localhost"; // Nama host
$user = "root";      // Username database
$pass = "";          // Password database
$db   = "bsumappala"; // Nama database

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>

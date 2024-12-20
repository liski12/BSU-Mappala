<?php
include 'config.php'; // Pastikan file koneksi sudah benar

// Periksa apakah parameter id ada di URL
if (isset($_GET['id'])) {
    $id_nasabah = $_GET['id'];

    // Query untuk menghapus data berdasarkan id_nasabah
    $sql = "DELETE FROM transaksi WHERE id_nasabah = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_nasabah);

    if ($stmt->execute()) {
        // Jika berhasil, redirect kembali ke halaman utama dengan pesan sukses
        header("Location: transaksi.php?message=success");
    } else {
        // Jika gagal, redirect kembali dengan pesan error
        header("Location: transaksi.php?message=error");
    }

    // Tutup statement
    $stmt->close();
} else {
    // Jika parameter id tidak ada, redirect kembali dengan pesan error
    header("Location: transaksi.php?message=invalid");
}

// Tutup koneksi
$conn->close();
?>

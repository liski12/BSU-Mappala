<?php
// Include file konfigurasi untuk koneksi database
include 'config.php'; // Sesuaikan dengan file koneksi Anda

// Periksa apakah parameter ID tersedia di URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ambil dan validasi ID

    // Query untuk menghapus data berdasarkan ID
    $sql = "DELETE FROM buku_kas WHERE id_buku_kas = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect ke halaman buku kas setelah berhasil dihapus
        header("Location: bukukas.php?status=success&message=Data berhasil dihapus");
        exit();
    } else {
        // Tampilkan pesan error jika gagal
        echo "Error: " . $stmt->error;
    }

    // Tutup statement
    $stmt->close();
} else {
    // Jika ID tidak ada di URL, redirect ke halaman buku kas
    header("Location: bukukas.php?status=error&message=ID tidak valid");
    exit();
}

// Tutup koneksi
$conn->close();
?>

<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "bsumappala");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Periksa apakah parameter ID tersedia
if (isset($_GET['id'])) {
    $id_setoran = intval($_GET['id']);
    $id_nasabah = intval($_GET['id_nasabah']);

    // Query untuk menghapus data berdasarkan ID
    $query = $koneksi->prepare("DELETE FROM setoran WHERE id_setoran = ?");
    $query->bind_param("i", $id_setoran);

    if ($query->execute()) {
        // Redirect kembali ke halaman utama setelah penghapusan
        header("Location: setoransampah.php?id=" . $id_nasabah);
        exit();
    } else {
        echo "<script>alert('Gagal menghapus data!'); window.location.href = 'setoransampah.php?id=" . $id_nasabah . "';</script>";
    }
} else {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href = 'setoransampah.php';</script>";
}
?>

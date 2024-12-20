<?php
// Koneksi ke database
include 'config.php';


// Cek apakah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_buku_kas = $_POST['id_buku_kas'];

    // Query untuk mengambil data lama
    $sql_select = "SELECT * FROM buku_kas WHERE id_buku_kas = '$id_buku_kas'";
    $result = $conn->query($sql_select);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Ambil nilai dari form, jika ada input baru gunakan input baru, jika tidak gunakan nilai lama
        $tanggal = !empty($_POST['tanggal']) ? $_POST['tanggal'] : $row['tanggal'];
        $jenis_transaksi = !empty($_POST['jenis_transaksi']) ? $_POST['jenis_transaksi'] : $row['jenis_transaksi'];
        $masuk = isset($_POST['masuk']) ? $_POST['masuk'] : $row['masuk'];
        $keluar = isset($_POST['keluar']) ? $_POST['keluar'] : $row['keluar'];

        // Query update ke database
        $sql_update = "UPDATE buku_kas SET 
                        tanggal = '$tanggal', 
                        jenis_transaksi = '$jenis_transaksi', 
                        masuk = '$masuk', 
                        keluar = '$keluar' 
                       WHERE id_buku_kas = '$id_buku_kas'";

        if ($conn->query($sql_update) === TRUE) {
            echo "Data berhasil diperbarui!";
            header("Location: bukukas.php");
            exit();
        } else {
            echo "Error: " . $sql_update . "<br>" . $conn->error;
        }
    } else {
        echo "Data tidak ditemukan.";
    }
} else {
    echo "Metode request tidak valid.";
}

$conn->close();
?>

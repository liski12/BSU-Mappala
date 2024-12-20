<?php
include 'config.php';

if (isset($_GET['id_nasabah'])) {
    $id_nasabah = $_GET['id_nasabah'];

    // Query untuk mengambil saldo nasabah
    $sql_saldo_awal = "SELECT SUM(harga) AS saldo FROM setoran WHERE id_nasabah = ?";
    $stmt = $conn->prepare($sql_saldo_awal);
    $stmt->bind_param("i", $id_nasabah);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $saldo = $row['saldo'] ?? 0;

    // Kirim data saldo sebagai JSON
    echo json_encode(['saldo' => $saldo]);
}
?>

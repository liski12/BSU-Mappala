<?php
require('fpdf/fpdf.php');

// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "bsumappala");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil ID nasabah dan bulan pencarian dari URL
$id_nasabah = $_GET['id'];
$search_month = isset($_GET['search_month']) ? $_GET['search_month'] : '';

// Query untuk mengambil data nasabah
$query_nasabah = $koneksi->prepare("SELECT nama_nasabah FROM nasabah WHERE id_nasabah = ?");
$query_nasabah->bind_param("i", $id_nasabah);
$query_nasabah->execute();
$result_nasabah = $query_nasabah->get_result();
$data_nasabah = $result_nasabah->fetch_assoc();
$nama_nasabah = $data_nasabah['nama_nasabah'];

// Query data setoran berdasarkan bulan yang dipilih
$query = $koneksi->prepare("SELECT * FROM setoran WHERE id_nasabah = ? AND DATE_FORMAT(tanggal_setor, '%Y-%m') = ?");
$query->bind_param("is", $id_nasabah, $search_month);
$query->execute();
$result = $query->get_result();

// Inisialisasi PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

// Header PDF
$pdf->Cell(0, 10, 'Laporan Setoran Sampah Per Bulan', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Nama Nasabah: ' . $nama_nasabah, 0, 1, 'L');
$pdf->Cell(0, 10, 'Bulan: ' . date('F Y', strtotime($search_month . '-01')), 0, 1, 'L');

// Tambahkan header tabel
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(10, 10, 'No', 1);
$pdf->Cell(40, 10, 'Tanggal Setor', 1);
$pdf->Cell(50, 10, 'Jenis Sampah', 1);
$pdf->Cell(30, 10, 'Berat (Kg)', 1);
$pdf->Cell(40, 10, 'Harga (Rp)', 1);
$pdf->Ln();

// Isi tabel
$pdf->SetFont('Arial', '', 12);
$no = 1;
$total_berat = 0;
$total_harga = 0;

while ($row = $result->fetch_assoc()) {
    $pdf->Cell(10, 10, $no++, 1);
    $pdf->Cell(40, 10, date('d-m-Y', strtotime($row['tanggal_setor'])), 1);
    $pdf->Cell(50, 10, $row['jenis_sampah'], 1);
    $pdf->Cell(30, 10, number_format($row['berat'], 2, ',', '.') . ' Kg', 1);
    $pdf->Cell(40, 10, 'Rp ' . number_format($row['harga'], 0, ',', '.'), 1);
    $pdf->Ln();

    // Hitung total
    $total_berat += $row['berat'];
    $total_harga += $row['harga'];
}

// Tambahkan total di akhir tabel
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100, 10, 'Total', 1);
$pdf->Cell(30, 10, number_format($total_berat, 2, ',', '.') . ' Kg', 1);
$pdf->Cell(40, 10, 'Rp ' . number_format($total_harga, 0, ',', '.'), 1);

// Output file PDF
$pdf->Output('I', 'Laporan_Setoran.pdf');
?>

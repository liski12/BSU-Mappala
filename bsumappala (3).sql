-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Des 2024 pada 09.30
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bsumappala`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku_kas`
--

CREATE TABLE `buku_kas` (
  `id_buku_kas` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `jenis_transaksi` varchar(50) DEFAULT NULL,
  `masuk` bigint(20) DEFAULT NULL,
  `keluar` bigint(20) DEFAULT NULL,
  `saldo` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `buku_kas`
--

INSERT INTO `buku_kas` (`id_buku_kas`, `tanggal`, `jenis_transaksi`, `masuk`, `keluar`, `saldo`) VALUES
(6, '2024-12-15', 'Jual Sampah', 100000, 0, NULL),
(7, '2024-12-20', 'beli dos', 0, 10000, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `daftar_harga`
--

CREATE TABLE `daftar_harga` (
  `kode` varchar(10) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `harga` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `daftar_harga`
--

INSERT INTO `daftar_harga` (`kode`, `nama`, `harga`) VALUES
('B01', 'Botol Markisa Bensin', 1000),
('B02', 'Botol Kecap/Bir', 600),
('B03', 'Botol Tebal (Pendek)', 250),
('B05', 'Botol Soda Tebal', 300),
('B06', 'Botol Tebal (Lainnya)', 350),
('K01', 'Kertas Putih', 1400),
('K02', 'Kertas Campur/Warna', 600),
('K03', 'Kertas Buram', 1100),
('K04', 'Kardus', 1600),
('K05A', 'Kertas Semen A', 600),
('K05B', 'Kertas Pembungkus', 500),
('K06', 'Koran', 1200),
('K08', 'Cones', 400),
('L01', 'Besi Tebal', 3200),
('L02', 'Kaleng', 2000),
('L03', 'Kuningan', 40000),
('L05', 'Tembaga', 80000),
('L06', 'Aluminium Tebal', 14000),
('L07', 'Aluminium Tipis', 10000),
('L08', 'Aluminium Siku', 17000),
('L09', 'Aluminium Campur', 6000),
('L10', 'Perunggu', 7000),
('L11', 'Besi Seng', 1500),
('M01', 'Minyak Jelantah', 8500),
('P01B', 'PP Gelas Bening Bersih', 6500),
('P01K', 'PP Gelas Bening Kotor', 3000),
('P02B', 'PP Gelas Warna', 2800),
('P02C', 'PP Cincin Gelas', 1600),
('P02P', 'PP Pipet', 1000),
('P03B', 'PET Bening Bersih', 6300),
('P04B', 'PET Biru Muda Bersih', 5000),
('P04C', 'PET Campur', 1200),
('P04K', 'PET Kotor', 1000),
('P05', 'PET Toples', 1500),
('P05B', 'PET Warna Bersih', 1500),
('P05C', 'PET Warna Campur', 1300),
('P06C', 'Plastik HD Campur', 1500),
('P06K', 'HD Tutup Galon', 1800),
('P09K', 'Tutup Galon Campur', 3500),
('P10', 'Plastik Daun', 700),
('P11', 'Plastik PP Cetak', 500),
('P13', 'Plastik HD (Blow) Campur', 2300);

-- --------------------------------------------------------

--
-- Struktur dari tabel `nasabah`
--

CREATE TABLE `nasabah` (
  `id_nasabah` int(11) NOT NULL,
  `nama_nasabah` varchar(100) DEFAULT NULL,
  `tanggal_pendaftaran` date DEFAULT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `nasabah`
--

INSERT INTO `nasabah` (`id_nasabah`, `nama_nasabah`, `tanggal_pendaftaran`, `nik`, `no_hp`, `alamat`) VALUES
(5, 'Liski', '2024-12-01', 'MKS/RPC/MPL/01', '081243540621', 'MAPPALA '),
(6, 'l1sk@#w', '2024-12-20', 'jskadhjdhsiHEWI1', 'iawhirhowjojoje', 'iwajdojodjwaohriajodjaofhnigajsdojfaofbhaih'),
(7, 'WA ODE NISWATY', '2024-12-05', 'hhhhhhhhhhhhhhhh', 'hhhhhhhhhhhhhhh', 'hhhhhhhhhhh');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengelola`
--

CREATE TABLE `pengelola` (
  `id_pengelola` int(11) NOT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengelola`
--

INSERT INTO `pengelola` (`id_pengelola`, `no_telepon`, `username`, `password`, `foto`) VALUES
(1, '085242568811', 'Lenina Rani Lubis', '$2y$10$9fYbcEVCwSnIaLCgFvI6zuP8nd0M4ZetZRUgPzKkg/jFNjokFdCLy', 'img/Screenshot 2024-12-07 003637.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `setoran`
--

CREATE TABLE `setoran` (
  `id_setoran` int(11) NOT NULL,
  `id_nasabah` int(11) DEFAULT NULL,
  `tanggal_setor` date DEFAULT NULL,
  `jenis_sampah` varchar(50) DEFAULT NULL,
  `berat` float DEFAULT NULL,
  `harga` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `setoran`
--

INSERT INTO `setoran` (`id_setoran`, `id_nasabah`, `tanggal_setor`, `jenis_sampah`, `berat`, `harga`) VALUES
(2, 5, '2024-12-01', 'B01', 1, 1000),
(5, 6, '2024-12-20', 'L01', 10.5, 3200);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_nasabah` int(11) DEFAULT NULL,
  `tanggal_tariksaldo` date DEFAULT NULL,
  `total_saldo` bigint(20) DEFAULT NULL,
  `jumlah_transaksi` bigint(20) DEFAULT NULL,
  `sisa_saldo` bigint(20) DEFAULT NULL,
  `manual_entry` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_nasabah`, `tanggal_tariksaldo`, `total_saldo`, `jumlah_transaksi`, `sisa_saldo`, `manual_entry`) VALUES
(1, 5, '2024-12-18', NULL, NULL, 600, 0),
(2, 5, '2024-12-18', NULL, NULL, 1000, 0),
(3, 5, '2024-12-18', NULL, NULL, 600, 0),
(4, 5, '2024-12-20', 1000, 200, 800, 1),
(5, 6, '2024-12-20', NULL, NULL, 120000000, 0),
(6, 6, '2024-12-20', NULL, NULL, 33600, 0),
(7, 6, '2024-12-20', 3200, 1100, 2100, 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `buku_kas`
--
ALTER TABLE `buku_kas`
  ADD PRIMARY KEY (`id_buku_kas`);

--
-- Indeks untuk tabel `daftar_harga`
--
ALTER TABLE `daftar_harga`
  ADD PRIMARY KEY (`kode`);

--
-- Indeks untuk tabel `nasabah`
--
ALTER TABLE `nasabah`
  ADD PRIMARY KEY (`id_nasabah`);

--
-- Indeks untuk tabel `pengelola`
--
ALTER TABLE `pengelola`
  ADD PRIMARY KEY (`id_pengelola`);

--
-- Indeks untuk tabel `setoran`
--
ALTER TABLE `setoran`
  ADD PRIMARY KEY (`id_setoran`),
  ADD KEY `id_nasabah` (`id_nasabah`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_nasabah` (`id_nasabah`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `buku_kas`
--
ALTER TABLE `buku_kas`
  MODIFY `id_buku_kas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `nasabah`
--
ALTER TABLE `nasabah`
  MODIFY `id_nasabah` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `pengelola`
--
ALTER TABLE `pengelola`
  MODIFY `id_pengelola` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `setoran`
--
ALTER TABLE `setoran`
  MODIFY `id_setoran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `setoran`
--
ALTER TABLE `setoran`
  ADD CONSTRAINT `setoran_ibfk_1` FOREIGN KEY (`id_nasabah`) REFERENCES `nasabah` (`id_nasabah`);

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_nasabah`) REFERENCES `nasabah` (`id_nasabah`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

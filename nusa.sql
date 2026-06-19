-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 19, 2026 at 01:12 AM
-- Server version: 8.0.30
-- PHP Version: 8.5.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nusa`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`) VALUES
(10, 'ASESORIES'),
(9, 'DRONE LIDAR SYSTEM'),
(6, 'GNSS'),
(7, 'GNSS RTK SYSTEM'),
(11, 'LiDAR SLAM SYSTEM (MOBILE 3D MAPPING)'),
(8, 'TOTAL STATION'),
(12, 'USV BATHYMETRIC SYSTEM (ECHOSOUNDER INTEGRATION)');

-- --------------------------------------------------------

--
-- Table structure for table `layanan`
--

CREATE TABLE `layanan` (
  `id` int NOT NULL,
  `nama` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `pesan` text NOT NULL,
  `ttd_data` longtext NOT NULL,
  `tanggal` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `deskripsi` text NOT NULL,
  `harga` decimal(15,2) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `tanggal_input` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `nama_produk`, `kategori`, `deskripsi`, `harga`, `gambar`, `tanggal_input`) VALUES
(17, 'CHCNAV GNSS i73 / i73+ ', 'GNSS RTK SYSTEM', 'CHCNAV GNSS i73/i73+ adalah alat GNSS RTK presisi tinggi yang mendukung multi-konstelasi satelit, dilengkapi IMU Tilt Compensation, konektivitas Bluetooth/Wi-Fi, serta akurasi pengukuran hingga tingkat sentimeter untuk kebutuhan survei dan pemetaan.', 20000000.00, 'gnss-smart-full-antennas-chcnav-i83.png', '2026-06-19 00:54:47'),
(18, 'DJI Matrice 400 + Zenmuse L2 LiDAR', 'DRONE LIDAR SYSTEM', 'DJI Matrice 400 + Zenmuse L2 LiDAR merupakan sistem drone pemetaan profesional yang dilengkapi sensor LiDAR dan kamera RGB untuk akuisisi data spasial berpresisi tinggi, mendukung pemetaan topografi, survei, dan pemodelan 3D pada area luas secara cepat dan efisien.', 30000000.00, 'DJI-Matrice-400.png', '2026-06-19 00:56:06'),
(19, 'TOPCON  GM-50', 'TOTAL STATION', 'TOPCON GM-50 merupakan Total Station manual berakurasi tinggi yang digunakan untuk pengukuran jarak, sudut, dan koordinat pada pekerjaan survei, pemetaan, konstruksi, serta stake out. Dilengkapi teknologi EDM untuk pengukuran cepat dan presisi, memori internal, serta desain tangguh untuk penggunaan lapangan.', 12000000.00, 'GM-50.png', '2026-06-19 00:57:38'),
(21, 'Tersus MVP S1 LiDAR SLAM Scanner', 'LiDAR SLAM SYSTEM (MOBILE 3D MAPPING)', 'Tersus MVP S1 LiDAR SLAM Scanner merupakan alat pemindai 3D portabel berbasis teknologi LiDAR dan SLAM (Simultaneous Localization and Mapping) yang mampu menghasilkan data point cloud berakurasi tinggi tanpa memerlukan GNSS. Cocok digunakan untuk pemetaan indoor maupun outdoor, pemodelan 3D, inspeksi bangunan, serta survei area yang sulit dijangkau.', 15000000.00, '3eafee9db21e3374cced281bf1eb268d551fa485.jpeg', '2026-06-19 01:07:07'),
(22, 'Tersus TheDuck™ USV Platform', 'ASESORIES', 'Tersus TheDuck™ USV Platform merupakan kapal survei tanpa awak (Unmanned Surface Vehicle/USV) yang dirancang untuk pengukuran hidrografi, batimetri, dan pemetaan perairan. Dilengkapi sistem navigasi GNSS presisi tinggi serta mendukung integrasi berbagai sensor survei untuk pengumpulan data yang akurat dan efisien pada sungai, danau, waduk, maupun wilayah pesisir.', 25000000.00, 'HydroBoat-990-hydrographic-survey-usv.png', '2026-06-19 01:08:53'),
(23, 'Comnav GNSS', 'ASESORIES', 'ComNav GNSS merupakan perangkat Global Navigation Satellite System (GNSS) berpresisi tinggi yang mendukung teknologi RTK dan multi-konstelasi satelit untuk menghasilkan koordinat dengan akurasi tingkat sentimeter. Alat ini digunakan dalam survei, pemetaan, konstruksi, dan berbagai pekerjaan geospasial yang membutuhkan data posisi yang cepat dan akurat.', 4000000.00, 'm300-pro-gnss-receiver-front.png', '2026-06-19 01:11:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

--
-- Indexes for table `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

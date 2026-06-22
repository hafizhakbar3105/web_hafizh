-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 22, 2026 at 01:29 AM
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
-- Table structure for table `kategori_hafizh_2430511012`
--

CREATE TABLE `kategori_hafizh_2430511012` (
  `id` int NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori_hafizh_2430511012`
--

INSERT INTO `kategori_hafizh_2430511012` (`id`, `nama_kategori`) VALUES
(10, 'ASESORIES'),
(9, 'DRONE LIDAR SYSTEM'),
(6, 'GNSS'),
(7, 'GNSS RTK SYSTEM'),
(11, 'LiDAR SLAM SYSTEM (MOBILE 3D MAPPING)'),
(8, 'TOTAL STATION'),
(12, 'USV BATHYMETRIC SYSTEM (ECHOSOUNDER INTEGRATION)');

-- --------------------------------------------------------

--
-- Table structure for table `layanan_hafizh_2430511012`
--

CREATE TABLE `layanan_hafizh_2430511012` (
  `id` int NOT NULL,
  `nama` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `pesan` text NOT NULL,
  `ttd_data` longtext NOT NULL,
  `tanggal` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `layanan_hafizh_2430511012`
--

INSERT INTO `layanan_hafizh_2430511012` (`id`, `nama`, `email`, `telepon`, `kategori`, `pesan`, `ttd_data`, `tanggal`) VALUES
(5, 'MUHAMMAD HAFIZH ', 'nusageopasial@gmail.com', '08123456789', 'Konsultasi Proyek Topografi', 'saya menginginkan konsultasi terkait topografi lapangan ', 'data:image/png;base64,...', '2026-06-19 01:21:21'),
(6, 'PT NUSA INTERNASIONAL GEOPASILA ', 'nusainternasional@gmail.com', '085723234567', 'Konsultasi Proyek Topografi', 'saya ingin konsultasi perihal proyek topografi ', 'data:image/png;base64,...', '2026-06-22 01:15:04');

-- --------------------------------------------------------

--
-- Table structure for table `produk_hafizh_2430511012`
--

CREATE TABLE `produk_hafizh_2430511012` (
  `id` int NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `deskripsi` text NOT NULL,
  `harga` decimal(15,2) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `tanggal_input` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produk_hafizh_2430511012`
--

INSERT INTO `produk_hafizh_2430511012` (`id`, `nama_produk`, `kategori`, `deskripsi`, `harga`, `gambar`, `tanggal_input`) VALUES
(17, 'CHCNAV GNSS i73 / i73+ ', 'GNSS RTK SYSTEM', 'CHCNAV GNSS i73/i73+ adalah alat GNSS RTK presisi tinggi yang mendukung multi-konstelasi satelit, dilengkapi IMU Tilt Compensation, konektivitas Bluetooth/Wi-Fi, serta akurasi pengukuran hingga tingkat sentimeter untuk kebutuhan survei dan pemetaan.', 20000000.00, 'gnss-smart-full-antennas-chcnav-i83.png', '2026-06-19 00:54:47'),
(18, 'DJI Matrice 400 + Zenmuse L2 LiDAR', 'DRONE LIDAR SYSTEM', 'DJI Matrice 400 + Zenmuse L2 LiDAR merupakan sistem drone pemetaan profesional yang dilengkapi sensor LiDAR dan kamera RGB untuk akuisisi data spasial berpresisi tinggi, mendukung pemetaan topografi, survei, dan pemodelan 3D pada area luas secara cepat dan efisien.', 30000000.00, 'DJI-Matrice-400.png', '2026-06-19 00:56:06'),
(19, 'TOPCON  GM-50', 'TOTAL STATION', 'TOPCON GM-50 merupakan Total Station manual berakurasi tinggi yang digunakan untuk pengukuran jarak, sudut, dan koordinat pada pekerjaan survei, pemetaan, konstruksi, serta stake out. Dilengkapi teknologi EDM untuk pengukuran cepat dan presisi, memori internal, serta desain tangguh untuk penggunaan lapangan.', 12000000.00, 'GM-50.png', '2026-06-19 00:57:38'),
(21, 'Tersus MVP S1 LiDAR SLAM Scanner', 'LiDAR SLAM SYSTEM (MOBILE 3D MAPPING)', 'Tersus MVP S1 LiDAR SLAM Scanner merupakan alat pemindai 3D portabel berbasis teknologi LiDAR dan SLAM (Simultaneous Localization and Mapping) yang mampu menghasilkan data point cloud berakurasi tinggi tanpa memerlukan GNSS. Cocok digunakan untuk pemetaan indoor maupun outdoor, pemodelan 3D, inspeksi bangunan, serta survei area yang sulit dijangkau.', 15000000.00, '3eafee9db21e3374cced281bf1eb268d551fa485.jpeg', '2026-06-19 01:07:07'),
(22, 'Tersus TheDuck™ USV Platform', 'ASESORIES', 'Tersus TheDuck™ USV Platform merupakan kapal survei tanpa awak (Unmanned Surface Vehicle/USV) yang dirancang untuk pengukuran hidrografi, batimetri, dan pemetaan perairan. Dilengkapi sistem navigasi GNSS presisi tinggi serta mendukung integrasi berbagai sensor survei untuk pengumpulan data yang akurat dan efisien pada sungai, danau, waduk, maupun wilayah pesisir.', 25000000.00, 'HydroBoat-990-hydrographic-survey-usv.png', '2026-06-19 01:08:53'),
(23, 'Comnav GNSS', 'ASESORIES', 'ComNav GNSS merupakan perangkat Global Navigation Satellite System (GNSS) berpresisi tinggi yang mendukung teknologi RTK dan multi-konstelasi satelit untuk menghasilkan koordinat dengan akurasi tingkat sentimeter. Alat ini digunakan dalam survei, pemetaan, konstruksi, dan berbagai pekerjaan geospasial yang membutuhkan data posisi yang cepat dan akurat.', 4000000.00, 'm300-pro-gnss-receiver-front.png', '2026-06-19 01:11:25'),
(24, 'GNSS Kinematik A70 / i30 Series ', 'GNSS RTK SYSTEM', 'GNSS Kinematik A70/i30 Series merupakan perangkat GNSS RTK berpresisi tinggi yang mendukung multi-konstelasi satelit untuk pengukuran koordinat dengan akurasi tingkat sentimeter. Dilengkapi teknologi komunikasi nirkabel dan desain yang tangguh, alat ini digunakan untuk survei, pemetaan, konstruksi, serta pekerjaan geospasial lainnya secara cepat dan efisien.', 17000000.00, 'GPS-GNSS-FOIF-A90-de-Tu-Equipo-SAS.png', '2026-06-19 02:09:52');

-- --------------------------------------------------------

--
-- Table structure for table `users_hafizh_2430511012`
--

CREATE TABLE `users_hafizh_2430511012` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users_hafizh_2430511012`
--

INSERT INTO `users_hafizh_2430511012` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori_hafizh_2430511012`
--
ALTER TABLE `kategori_hafizh_2430511012`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

--
-- Indexes for table `layanan_hafizh_2430511012`
--
ALTER TABLE `layanan_hafizh_2430511012`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produk_hafizh_2430511012`
--
ALTER TABLE `produk_hafizh_2430511012`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_hafizh_2430511012`
--
ALTER TABLE `users_hafizh_2430511012`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori_hafizh_2430511012`
--
ALTER TABLE `kategori_hafizh_2430511012`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `layanan_hafizh_2430511012`
--
ALTER TABLE `layanan_hafizh_2430511012`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `produk_hafizh_2430511012`
--
ALTER TABLE `produk_hafizh_2430511012`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users_hafizh_2430511012`
--
ALTER TABLE `users_hafizh_2430511012`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
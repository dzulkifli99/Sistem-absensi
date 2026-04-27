-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2026 at 08:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siswa`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id` int(10) NOT NULL,
  `NIS` varchar(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam_datang` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `status` enum('Hadir','Terlambat','Izin','Alpa') DEFAULT NULL,
  `last_scan` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(10) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'hasby', '$2y$10$cxyzcHvocWMc.lLroqww2eP8SjZ7DkGg75Wd7NCFr2JYFZEQR1J8W'),
(2, 'admin', '$2y$10$f0zft5nGHoMBwj/2JsSlrujN.Z7V7m6ZXM2mwHimcUISCHiovKLAy');

-- --------------------------------------------------------

--
-- Table structure for table `data`
--

CREATE TABLE `data` (
  `NIS` varchar(20) NOT NULL,
  `nama` varchar(25) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `no_hp` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data`
--

INSERT INTO `data` (`NIS`, `nama`, `kelas`, `no_hp`) VALUES
('25001', 'ADITYA KURNIAWAN', '10 TKJ B', '085784880169'),
('25002', 'Afnan Nur Ghofur', '10 TKJ B', '085784880169'),
('25003', 'AHMAD ZAKARIA', '10 TKJ B', '085784880169'),
('25004', 'DEDI CANDRA WIJAYA', '10 TKJ B', '085784880169'),
('25005', 'Evan Septiyan Ramadhani', '10 TKJ B', '085784880169'),
('25006', 'FAREL EMARALDI DINATA', '10 TKJ B', '085784880169'),
('25007', 'Nafis Arifin', '10 TKJ B', '085784880169'),
('25008', 'KEVIN KURNIAWAN', '10 TKJ B', '085784880169'),
('25009', 'MAULANAFIZ AL RAFIDIN', '10 TKJ B', '085784880169'),
('25010', 'MOCH RAKA RADITYA PRATAMA', '10 TKJ B', '085784880169'),
('25011', 'MUHAMMAD AGUSTIAN FERDIAN', '10 TKJ B', '085784880169'),
('25012', 'Muhammad Ardin Ardiansyah', '10 TKJ B', '085784880169'),
('25013', 'MUHAMMAD FAREL AFIFUDIN', '10 TKJ B', '085784880169'),
('25014', 'MUHAMMAD NAJIVA', '10 TKJ B', '085784880169'),
('25015', 'MUKHAMMAD IQBAL GOZALI', '10 TKJ B', '085784880169'),
('25016', 'Putrawan', '10 TKJ B', '085784880169'),
('25017', 'Rahmad Mauludin', '10 TKJ B', '085784880169'),
('25018', 'RAMADANI', '10 TKJ B', '085784880169'),
('25019', 'Ridho Firmansyah', '10 TKJ B', '085784880169'),
('25020', 'Rohizul Kifli Jaelani', '10 TKJ B', '085784880169'),
('25021', 'Satrio Dwi Wahono', '10 TKJ B', '085784880169'),
('25022', 'SEPTIAN AGUNG PRASETYO', '10 TKJ B', '085784880169'),
('25023', 'TORIKUL FATA ZAIDAAN ARIE', '10 TKJ B', '085784880169'),
('25024', 'WAHYU ADI PRATAMA', '10 TKJ B', '085784880169'),
('25025', 'Andika', '10 TKJ B', '085784880169'),
('25026', 'ADINDA PUTRI NURLAILIN', '10 TKJ A', '085784880169'),
('25027', 'DEA APRILIA', '10 TKJ A', '085784880169'),
('25028', 'Dinda Uswatun Hasanah', '10 TKJ A', '085784880169'),
('25029', 'ELSI DINI ANANTA', '10 TKJ A', '085784880169'),
('25030', 'FARADINA AMIROTUL ADILAH', '10 TKJ A', '085784880169'),
('25031', 'FIKA RAHMAWATI', '10 TKJ A', '085784880169'),
('25032', 'FIRDAUSYAH SALSABILA', '10 TKJ A', '085784880169'),
('25033', 'Halimah Tusakdiyah', '10 TKJ A', '085784880169'),
('25034', 'HILWA SEPTIANA SAFITRI', '10 TKJ A', '085784880169'),
('25035', 'IMELIA HERNIATI', '10 TKJ A', '085784880169'),
('25036', 'LIDIA AMELIA', '10 TKJ A', '085784880169'),
('25037', 'NIKMATUL JANNAH', '10 TKJ A', '085784880169'),
('25038', 'Risa Lisdiana', '10 TKJ A', '085784880169'),
('25039', 'ROHMATULLAH', '10 TKJ A', '085784880169'),
('25040', 'Safira Ayu Lestari', '10 TKJ A', '085784880169'),
('25041', 'SITI NUR AISYAH', '10 TKJ A', '085784880169'),
('25042', 'SITI ROHMAIDA', '10 TKJ A', '085784880169'),
('25043', 'SUSANTI', '10 TKJ A', '085784880169'),
('25044', 'Umayro Uswatun Aninia', '10 TKJ A', '085784880169'),
('25045', 'WILDA AGUSTIANI', '10 TKJ A', '085784880169');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` int(10) NOT NULL,
  `hari` varchar(10) NOT NULL,
  `jam_masuk` time NOT NULL,
  `batas_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL,
  `batas_pulang` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `hari`, `jam_masuk`, `batas_masuk`, `jam_pulang`, `batas_pulang`) VALUES
(1, 'senin', '06:00:00', '09:00:00', '14:00:00', '15:00:00'),
(2, 'selasa', '06:00:00', '08:00:00', '14:30:00', '15:00:00'),
(3, 'rabu', '06:00:00', '07:00:00', '14:00:00', '15:00:00'),
(4, 'kamis', '06:00:00', '07:00:00', '14:00:00', '15:00:00'),
(5, 'jumat', '06:00:00', '07:00:00', '14:00:00', '15:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_absensi_siswa` (`NIS`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `data`
--
ALTER TABLE `data`
  ADD UNIQUE KEY `NIS` (`NIS`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1022;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `fk_absensi_siswa` FOREIGN KEY (`NIS`) REFERENCES `data` (`NIS`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nis` FOREIGN KEY (`NIS`) REFERENCES `data` (`NIS`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

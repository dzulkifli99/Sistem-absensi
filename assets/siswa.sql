-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2026 at 04:20 AM
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

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id`, `NIS`, `tanggal`, `jam_datang`, `jam_pulang`, `status`, `last_scan`) VALUES
(1422, '25002', '2026-04-28', '18:03:30', NULL, 'Hadir', '2026-04-28 18:03:30'),
(1423, '25003', '2026-04-28', '18:06:55', NULL, 'Hadir', '2026-04-28 18:06:55'),
(1424, '25001', '2026-04-28', '18:30:04', NULL, 'Hadir', '2026-04-28 18:30:04'),
(1425, '25004', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1426, '25005', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1427, '25006', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1428, '25007', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1429, '25008', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1430, '25009', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1431, '25010', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1432, '25011', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1433, '25012', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1434, '25013', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1435, '25014', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1436, '25015', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1437, '25016', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1438, '25017', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1439, '25018', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1440, '25019', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1441, '25020', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1442, '25021', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1443, '25022', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1444, '25023', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1445, '25024', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1446, '25025', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1447, '25026', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1448, '25027', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1449, '25028', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1450, '25029', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1451, '25030', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1452, '25031', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1453, '25032', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1454, '25033', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1455, '25034', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1456, '25035', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1457, '25036', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1458, '25037', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1459, '25038', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1460, '25039', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1461, '25040', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1462, '25041', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1463, '25042', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1464, '25043', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1465, '25044', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1466, '25045', '2026-04-28', NULL, NULL, 'Alpa', NULL),
(1467, '25001', '2026-04-29', '05:54:27', '08:04:02', 'Hadir', '2026-04-29 08:04:02'),
(1468, '25002', '2026-04-29', '05:54:50', '08:04:04', 'Hadir', '2026-04-29 08:04:04'),
(1469, '25003', '2026-04-29', '05:54:53', '08:10:01', 'Hadir', '2026-04-29 08:10:01'),
(1470, '25004', '2026-04-29', '06:03:03', '08:10:04', 'Terlambat', '2026-04-29 08:10:04'),
(1471, '25005', '2026-04-29', '06:04:12', '08:10:09', 'Terlambat', '2026-04-29 08:10:09'),
(1472, '25006', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1473, '25007', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1474, '25008', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1475, '25009', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1476, '25010', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1477, '25011', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1478, '25012', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1479, '25013', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1480, '25014', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1481, '25015', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1482, '25016', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1483, '25017', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1484, '25018', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1485, '25019', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1486, '25020', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1487, '25021', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1488, '25022', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1489, '25023', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1490, '25024', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1491, '25025', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1492, '25026', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1493, '25027', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1494, '25028', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1495, '25029', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1496, '25030', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1497, '25031', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1498, '25032', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1499, '25033', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1500, '25034', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1501, '25035', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1502, '25036', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1503, '25037', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1504, '25038', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1505, '25039', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1506, '25040', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1507, '25041', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1508, '25042', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1509, '25043', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1510, '25044', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1511, '25045', '2026-04-29', NULL, NULL, 'Alpa', NULL),
(1512, '25001', '2026-04-30', '08:10:21', NULL, 'Hadir', '2026-04-30 08:10:21'),
(1513, '25002', '2026-04-30', '08:10:29', NULL, 'Hadir', '2026-04-30 08:10:29'),
(1514, '25003', '2026-04-30', '08:10:32', NULL, 'Hadir', '2026-04-30 08:10:32'),
(1515, '25004', '2026-04-30', '08:10:36', NULL, 'Hadir', '2026-04-30 08:10:36'),
(1516, '25005', '2026-04-30', '08:10:40', NULL, 'Hadir', '2026-04-30 08:10:40'),
(1517, '25007', '2026-04-30', '08:12:26', NULL, 'Hadir', '2026-04-30 08:12:26');

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
(2, 'admin', '$2y$10$f0zft5nGHoMBwj/2JsSlrujN.Z7V7m6ZXM2mwHimcUISCHiovKLAy'),
(3, 'sofi', '$2y$10$r7O0QS5HunYalqVkkidUe.NcqEQzFg37TCi11uYK6wvWax3gISHKG');

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
(2, 'selasa', '18:00:00', '19:00:00', '20:30:00', '23:00:00'),
(3, 'rabu', '05:00:00', '06:00:00', '08:00:00', '09:00:00'),
(4, 'kamis', '08:00:00', '09:00:00', '14:00:00', '15:00:00'),
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
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1518;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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

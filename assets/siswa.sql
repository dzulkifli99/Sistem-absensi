-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2026 at 05:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
  `status_pulang` enum('Pulang','Bolos','Izin') NOT NULL,
  `last_scan` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id`, `NIS`, `tanggal`, `jam_datang`, `jam_pulang`, `status`, `status_pulang`, `last_scan`) VALUES
(301, '10024', '2026-02-14', '12:56:24', NULL, 'Hadir', 'Pulang', '2026-02-14 12:56:24'),
(302, '10024', '2026-02-15', '09:17:57', '13:25:07', 'Hadir', 'Pulang', '2026-02-15 09:17:57'),
(303, '10024', '2026-02-16', '08:40:08', NULL, 'Hadir', 'Pulang', '2026-02-16 08:40:08');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(10) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(0, 'admin', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `data`
--

CREATE TABLE `data` (
  `id` int(10) NOT NULL,
  `NIS` varchar(20) NOT NULL,
  `nama` varchar(25) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `no_hp` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data`
--

INSERT INTO `data` (`id`, `NIS`, `nama`, `kelas`, `no_hp`) VALUES
(9, '10009', 'Siswa XTKJA 9', 'X TKJ A', '0812310009'),
(12, '10012', 'Siswa XTKJB 2', 'X TKJ B', '0812310012'),
(13, '10013', 'Siswa XTKJB 3', 'X TKJ B', '0812310013'),
(14, '10014', 'Siswa XTKJB 4', 'X TKJ B', '0812310014'),
(15, '10015', 'Siswa XTKJB 5', 'X TKJ B', '0812310015'),
(16, '10016', 'Siswa XTKJB 6', 'X TKJ B', '0812310016'),
(17, '10017', 'AMEL', 'X TKJ B', '6281232869055'),
(18, '10018', 'Siswa XTKJB 8', 'X TKJ B', '0812310018'),
(19, '10019', 'PUPUT', 'X TKJ B', '6285784880169'),
(20, '10020', 'PUTRI', 'X TKJ B', '6281456159616'),
(21, '10021', 'SASA', 'X DKV A', '6281232869055'),
(22, '10022', 'MALA', 'X DKV A', '6281232869055'),
(23, '10023', 'Siswa XDKVA 3', 'X DKV A', '0812310023'),
(24, '10024', 'DAMAR', 'X DKV A', '6285784880169'),
(25, '10025', 'Siswa XDKVA 5', 'X DKV A', '0812310025'),
(26, '10026', 'Siswa XDKVA 6', 'X DKV A', '0812310026'),
(27, '10027', 'Siswa XDKVA 7', 'X DKV A', '0812310027'),
(28, '10028', 'Siswa XDKVA 8', 'X DKV A', '0812310028'),
(29, '10029', 'Siswa XDKVA 9', 'X DKV A', '0812310029'),
(30, '10030', 'Siswa XDKVA 10', 'X DKV A', '0812310030'),
(31, '10031', 'Siswa XDKVB 1', 'X DKV B', '0812310031'),
(32, '10032', 'Siswa XDKVB 2', 'X DKV B', '0812310032'),
(33, '10033', 'Siswa XDKVB 3', 'X DKV B', '0812310033'),
(34, '10034', 'Siswa XDKVB 4', 'X DKV B', '0812310034'),
(35, '10035', 'Siswa XDKVB 5', 'X DKV B', '0812310035'),
(36, '10036', 'Siswa XDKVB 6', 'X DKV B', '0812310036'),
(37, '10037', 'Siswa XDKVB 7', 'X DKV B', '0812310037'),
(38, '10038', 'Siswa XDKVB 8', 'X DKV B', '0812310038'),
(39, '10039', 'Siswa XDKVB 9', 'X DKV B', '0812310039'),
(40, '10040', 'Siswa XDKVB 10', 'X DKV B', '0812310040'),
(41, '10041', 'Siswa XATUA 1', 'X ATU A', '0812310041'),
(42, '10042', 'Siswa XATUA 2', 'X ATU A', '0812310042'),
(43, '10043', 'Siswa XATUA 3', 'X ATU A', '0812310043'),
(44, '10044', 'Siswa XATUA 4', 'X ATU A', '0812310044'),
(45, '10045', 'Siswa XATUA 5', 'X ATU A', '0812310045'),
(46, '10046', 'Siswa XATUA 6', 'X ATU A', '0812310046'),
(47, '10047', 'Siswa XATUA 7', 'X ATU A', '0812310047'),
(48, '10048', 'Siswa XATUA 8', 'X ATU A', '0812310048'),
(49, '10049', 'Siswa XATUA 9', 'X ATU A', '0812310049'),
(50, '10050', 'Siswa XATUA 10', 'X ATU A', '0812310050'),
(51, '10051', 'Siswa XATUB 1', 'X ATU B', '0812310051'),
(52, '10052', 'Siswa XATUB 2', 'X ATU B', '0812310052'),
(53, '10053', 'Siswa XATUB 3', 'X ATU B', '0812310053'),
(54, '10054', 'Siswa XATUB 4', 'X ATU B', '0812310054'),
(55, '10055', 'Siswa XATUB 5', 'X ATU B', '0812310055'),
(56, '10056', 'Siswa XATUB 6', 'X ATU B', '0812310056'),
(57, '10057', 'Siswa XATUB 7', 'X ATU B', '0812310057'),
(58, '10058', 'Siswa XATUB 8', 'X ATU B', '0812310058'),
(59, '10059', 'Siswa XATUB 9', 'X ATU B', '0812310059'),
(60, '10060', 'Siswa XATUB 10', 'X ATU B', '0812310060'),
(61, '10061', 'Siswa XTSMA 1', 'X TSM A', '0812310061'),
(62, '10062', 'Siswa XTSMA 2', 'X TSM A', '0812310062'),
(63, '10063', 'Siswa XTSMA 3', 'X TSM A', '0812310063'),
(64, '10064', 'Siswa XTSMA 4', 'X TSM A', '0812310064'),
(65, '10065', 'Siswa XTSMA 5', 'X TSM A', '0812310065'),
(66, '10066', 'Siswa XTSMA 6', 'X TSM A', '0812310066'),
(67, '10067', 'Siswa XTSMA 7', 'X TSM A', '0812310067'),
(68, '10068', 'Siswa XTSMA 8', 'X TSM A', '0812310068'),
(69, '10069', 'Siswa XTSMA 9', 'X TSM A', '0812310069'),
(70, '10070', 'Siswa XTSMA 10', 'X TSM A', '0812310070'),
(71, '10071', 'Siswa XTSMB 1', 'X TSM B', '0812310071'),
(72, '10072', 'Siswa XTSMB 2', 'X TSM B', '0812310072'),
(73, '10073', 'Siswa XTSMB 3', 'X TSM B', '0812310073'),
(74, '10074', 'Siswa XTSMB 4', 'X TSM B', '0812310074'),
(75, '10075', 'Siswa XTSMB 5', 'X TSM B', '0812310075'),
(76, '10076', 'Siswa XTSMB 6', 'X TSM B', '0812310076'),
(77, '10077', 'Siswa XTSMB 7', 'X TSM B', '0812310077'),
(78, '10078', 'Siswa XTSMB 8', 'X TSM B', '0812310078'),
(79, '10079', 'Siswa XTSMB 9', 'X TSM B', '0812310079'),
(80, '10080', 'Siswa XTSMB 10', 'X TSM B', '0812310080'),
(81, '10081', 'Siswa XTBA 1', 'X TB A', '0812310081'),
(82, '10082', 'Siswa XTBA 2', 'X TB A', '0812310082'),
(83, '10083', 'Siswa XTBA 3', 'X TB A', '0812310083'),
(84, '10084', 'Siswa XTBA 4', 'X TB A', '0812310084'),
(85, '10085', 'Siswa XTBA 5', 'X TB A', '0812310085'),
(86, '10086', 'Siswa XTBA 6', 'X TB A', '0812310086'),
(87, '10087', 'Siswa XTBA 7', 'X TB A', '0812310087'),
(88, '10088', 'Siswa XTBA 8', 'X TB A', '0812310088'),
(89, '10089', 'Siswa XTBA 9', 'X TB A', '0812310089'),
(90, '10090', 'Siswa XTBA 10', 'X TB A', '0812310090'),
(91, '10091', 'Siswa XTBB 1', 'X TB B', '0812310091'),
(92, '10092', 'Siswa XTBB 2', 'X TB B', '0812310092'),
(93, '10093', 'Siswa XTBB 3', 'X TB B', '0812310093'),
(94, '10094', 'Siswa XTBB 4', 'X TB B', '0812310094'),
(95, '10095', 'Siswa XTBB 5', 'X TB B', '0812310095'),
(96, '10096', 'Siswa XTBB 6', 'X TB B', '0812310096'),
(97, '10097', 'Siswa XTBB 7', 'X TB B', '0812310097'),
(98, '10098', 'Siswa XTBB 8', 'X TB B', '0812310098'),
(99, '10099', 'Siswa XTBB 9', 'X TB B', '0812310099'),
(100, '10100', 'Siswa XTBB 10', 'X TB B', '0812310100'),
(101, '10101', 'Siswa XITKJA 1', 'XI TKJ A', '0812310101'),
(102, '10102', 'Siswa XITKJA 2', 'XI TKJ A', '0812310102'),
(103, '10103', 'Siswa XITKJA 3', 'XI TKJ A', '0812310103'),
(104, '10104', 'Siswa XITKJA 4', 'XI TKJ A', '0812310104'),
(105, '10105', 'Siswa XITKJA 5', 'XI TKJ A', '0812310105'),
(106, '10106', 'Siswa XITKJA 6', 'XI TKJ A', '0812310106'),
(107, '10107', 'Siswa XITKJA 7', 'XI TKJ A', '0812310107'),
(108, '10108', 'Siswa XITKJA 8', 'XI TKJ A', '0812310108'),
(109, '10109', 'Siswa XITKJA 9', 'XI TKJ A', '0812310109'),
(110, '10110', 'Siswa XITKJA 10', 'XI TKJ A', '0812310110'),
(111, '10111', 'Siswa XITKJB 1', 'XI TKJ B', '0812310111'),
(112, '10112', 'Siswa XITKJB 2', 'XI TKJ B', '0812310112'),
(113, '10113', 'Siswa XITKJB 3', 'XI TKJ B', '0812310113'),
(114, '10114', 'Siswa XITKJB 4', 'XI TKJ B', '0812310114'),
(115, '10115', 'Siswa XITKJB 5', 'XI TKJ B', '0812310115'),
(116, '10116', 'Siswa XITKJB 6', 'XI TKJ B', '0812310116'),
(117, '10117', 'Siswa XITKJB 7', 'XI TKJ B', '0812310117'),
(118, '10118', 'Siswa XITKJB 8', 'XI TKJ B', '0812310118'),
(119, '10119', 'Siswa XITKJB 9', 'XI TKJ B', '0812310119'),
(120, '10120', 'Siswa XITKJB 10', 'XI TKJ B', '0812310120'),
(121, '10121', 'Siswa XIDKVA 1', 'XI DKV A', '0812310121'),
(122, '10122', 'Siswa XIDKVA 2', 'XI DKV A', '0812310122'),
(123, '10123', 'Siswa XIDKVA 3', 'XI DKV A', '0812310123'),
(124, '10124', 'Siswa XIDKVA 4', 'XI DKV A', '0812310124'),
(125, '10125', 'Siswa XIDKVA 5', 'XI DKV A', '0812310125'),
(126, '10126', 'Siswa XIDKVA 6', 'XI DKV A', '0812310126'),
(127, '10127', 'Siswa XIDKVA 7', 'XI DKV A', '0812310127'),
(128, '10128', 'Siswa XIDKVA 8', 'XI DKV A', '0812310128'),
(129, '10129', 'Siswa XIDKVA 9', 'XI DKV A', '0812310129'),
(130, '10130', 'Siswa XIDKVA 10', 'XI DKV A', '0812310130'),
(131, '10131', 'Siswa XIDKVB 1', 'XI DKV B', '0812310131'),
(132, '10132', 'Siswa XIDKVB 2', 'XI DKV B', '0812310132'),
(133, '10133', 'Siswa XIDKVB 3', 'XI DKV B', '0812310133'),
(134, '10134', 'Siswa XIDKVB 4', 'XI DKV B', '0812310134'),
(135, '10135', 'Siswa XIDKVB 5', 'XI DKV B', '0812310135'),
(136, '10136', 'Siswa XIDKVB 6', 'XI DKV B', '0812310136'),
(137, '10137', 'Siswa XIDKVB 7', 'XI DKV B', '0812310137'),
(138, '10138', 'Siswa XIDKVB 8', 'XI DKV B', '0812310138'),
(139, '10139', 'Siswa XIDKVB 9', 'XI DKV B', '0812310139'),
(140, '10140', 'Siswa XIDKVB 10', 'XI DKV B', '0812310140'),
(141, '10141', 'Siswa XIATUA 1', 'XI ATU A', '0812310141'),
(142, '10142', 'Siswa XIATUA 2', 'XI ATU A', '0812310142'),
(143, '10143', 'Siswa XIATUA 3', 'XI ATU A', '0812310143'),
(144, '10144', 'Siswa XIATUA 4', 'XI ATU A', '0812310144'),
(145, '10145', 'Siswa XIATUA 5', 'XI ATU A', '0812310145'),
(146, '10146', 'Siswa XIATUA 6', 'XI ATU A', '0812310146'),
(147, '10147', 'Siswa XIATUA 7', 'XI ATU A', '0812310147'),
(148, '10148', 'Siswa XIATUA 8', 'XI ATU A', '0812310148'),
(149, '10149', 'Siswa XIATUA 9', 'XI ATU A', '0812310149'),
(150, '10150', 'Siswa XIATUA 10', 'XI ATU A', '0812310150'),
(151, '10151', 'Siswa XIATUB 1', 'XI ATU B', '0812310151'),
(152, '10152', 'Siswa XIATUB 2', 'XI ATU B', '0812310152'),
(153, '10153', 'Siswa XIATUB 3', 'XI ATU B', '0812310153'),
(154, '10154', 'Siswa XIATUB 4', 'XI ATU B', '0812310154'),
(155, '10155', 'Siswa XIATUB 5', 'XI ATU B', '0812310155'),
(156, '10156', 'Siswa XIATUB 6', 'XI ATU B', '0812310156'),
(157, '10157', 'Siswa XIATUB 7', 'XI ATU B', '0812310157'),
(158, '10158', 'Siswa XIATUB 8', 'XI ATU B', '0812310158'),
(159, '10159', 'Siswa XIATUB 9', 'XI ATU B', '0812310159'),
(160, '10160', 'Siswa XIATUB 10', 'XI ATU B', '0812310160'),
(161, '10161', 'Siswa XITSMA 1', 'XI TSM A', '0812310161'),
(162, '10162', 'Siswa XITSMA 2', 'XI TSM A', '0812310162'),
(163, '10163', 'Siswa XITSMA 3', 'XI TSM A', '0812310163'),
(164, '10164', 'Siswa XITSMA 4', 'XI TSM A', '0812310164'),
(165, '10165', 'Siswa XITSMA 5', 'XI TSM A', '0812310165'),
(166, '10166', 'Siswa XITSMA 6', 'XI TSM A', '0812310166'),
(167, '10167', 'Siswa XITSMA 7', 'XI TSM A', '0812310167'),
(168, '10168', 'Siswa XITSMA 8', 'XI TSM A', '0812310168'),
(169, '10169', 'Siswa XITSMA 9', 'XI TSM A', '0812310169'),
(170, '10170', 'Siswa XITSMA 10', 'XI TSM A', '0812310170'),
(171, '10171', 'Siswa XITSMB 1', 'XI TSM B', '0812310171'),
(172, '10172', 'Siswa XITSMB 2', 'XI TSM B', '0812310172'),
(173, '10173', 'Siswa XITSMB 3', 'XI TSM B', '0812310173'),
(174, '10174', 'Siswa XITSMB 4', 'XI TSM B', '0812310174'),
(175, '10175', 'Siswa XITSMB 5', 'XI TSM B', '0812310175'),
(176, '10176', 'Siswa XITSMB 6', 'XI TSM B', '0812310176'),
(177, '10177', 'Siswa XITSMB 7', 'XI TSM B', '0812310177'),
(178, '10178', 'Siswa XITSMB 8', 'XI TSM B', '0812310178'),
(179, '10179', 'Siswa XITSMB 9', 'XI TSM B', '0812310179'),
(180, '10180', 'Siswa XITSMB 10', 'XI TSM B', '0812310180'),
(181, '10181', 'Siswa XITBA 1', 'XI TB A', '0812310181'),
(182, '10182', 'Siswa XITBA 2', 'XI TB A', '0812310182'),
(183, '10183', 'Siswa XITBA 3', 'XI TB A', '0812310183'),
(184, '10184', 'Siswa XITBA 4', 'XI TB A', '0812310184'),
(185, '10185', 'Siswa XITBA 5', 'XI TB A', '0812310185'),
(186, '10186', 'Siswa XITBA 6', 'XI TB A', '0812310186'),
(187, '10187', 'Siswa XITBA 7', 'XI TB A', '0812310187'),
(188, '10188', 'Siswa XITBA 8', 'XI TB A', '0812310188'),
(189, '10189', 'Siswa XITBA 9', 'XI TB A', '0812310189'),
(190, '10190', 'Siswa XITBA 10', 'XI TB A', '0812310190'),
(191, '10191', 'Siswa XITBB 1', 'XI TB B', '0812310191'),
(192, '10192', 'Siswa XITBB 2', 'XI TB B', '0812310192'),
(193, '10193', 'Siswa XITBB 3', 'XI TB B', '0812310193'),
(194, '10194', 'Siswa XITBB 4', 'XI TB B', '0812310194'),
(195, '10195', 'Siswa XITBB 5', 'XI TB B', '0812310195'),
(196, '10196', 'Siswa XITBB 6', 'XI TB B', '0812310196'),
(197, '10197', 'Siswa XITBB 7', 'XI TB B', '0812310197'),
(198, '10198', 'Siswa XITBB 8', 'XI TB B', '0812310198'),
(199, '10199', 'Siswa XITBB 9', 'XI TB B', '0812310199'),
(200, '10200', 'Siswa XITBB 10', 'XI TB B', '0812310200'),
(201, '10201', 'Siswa XIITKJA 1', 'XII TKJ A', '0812310201'),
(202, '10202', 'Siswa XIITKJA 2', 'XII TKJ A', '0812310202'),
(203, '10203', 'Siswa XIITKJA 3', 'XII TKJ A', '0812310203'),
(204, '10204', 'Siswa XIITKJA 4', 'XII TKJ A', '0812310204'),
(205, '10205', 'Siswa XIITKJA 5', 'XII TKJ A', '0812310205'),
(206, '10206', 'Siswa XIITKJA 6', 'XII TKJ A', '0812310206'),
(207, '10207', 'Siswa XIITKJA 7', 'XII TKJ A', '0812310207'),
(208, '10208', 'Siswa XIITKJA 8', 'XII TKJ A', '0812310208'),
(209, '10209', 'Siswa XIITKJA 9', 'XII TKJ A', '0812310209'),
(210, '10210', 'Siswa XIITKJA 10', 'XII TKJ A', '0812310210'),
(211, '10211', 'Siswa XIITKJB 1', 'XII TKJ B', '0812310211'),
(212, '10212', 'Siswa XIITKJB 2', 'XII TKJ B', '0812310212'),
(213, '10213', 'Siswa XIITKJB 3', 'XII TKJ B', '0812310213'),
(214, '10214', 'Siswa XIITKJB 4', 'XII TKJ B', '0812310214'),
(215, '10215', 'Siswa XIITKJB 5', 'XII TKJ B', '0812310215'),
(216, '10216', 'Siswa XIITKJB 6', 'XII TKJ B', '0812310216'),
(217, '10217', 'Siswa XIITKJB 7', 'XII TKJ B', '0812310217'),
(218, '10218', 'Siswa XIITKJB 8', 'XII TKJ B', '0812310218'),
(219, '10219', 'Siswa XIITKJB 9', 'XII TKJ B', '0812310219'),
(220, '10220', 'Siswa XIITKJB 10', 'XII TKJ B', '0812310220'),
(221, '10221', 'Siswa XIIDKVA 1', 'XII DKV A', '0812310221'),
(222, '10222', 'Siswa XIIDKVA 2', 'XII DKV A', '0812310222'),
(223, '10223', 'Siswa XIIDKVA 3', 'XII DKV A', '0812310223'),
(224, '10224', 'Siswa XIIDKVA 4', 'XII DKV A', '0812310224'),
(225, '10225', 'Siswa XIIDKVA 5', 'XII DKV A', '0812310225'),
(226, '10226', 'Siswa XIIDKVA 6', 'XII DKV A', '0812310226'),
(227, '10227', 'Siswa XIIDKVA 7', 'XII DKV A', '0812310227'),
(228, '10228', 'Siswa XIIDKVA 8', 'XII DKV A', '0812310228'),
(229, '10229', 'Siswa XIIDKVA 9', 'XII DKV A', '0812310229'),
(230, '10230', 'Siswa XIIDKVA 10', 'XII DKV A', '0812310230'),
(231, '10231', 'Siswa XIIDKVB 1', 'XII DKV B', '0812310231'),
(232, '10232', 'Siswa XIIDKVB 2', 'XII DKV B', '0812310232'),
(233, '10233', 'Siswa XIIDKVB 3', 'XII DKV B', '0812310233'),
(234, '10234', 'Siswa XIIDKVB 4', 'XII DKV B', '0812310234'),
(235, '10235', 'Siswa XIIDKVB 5', 'XII DKV B', '0812310235'),
(236, '10236', 'Siswa XIIDKVB 6', 'XII DKV B', '0812310236'),
(237, '10237', 'Siswa XIIDKVB 7', 'XII DKV B', '0812310237'),
(238, '10238', 'Siswa XIIDKVB 8', 'XII DKV B', '0812310238'),
(239, '10239', 'Siswa XIIDKVB 9', 'XII DKV B', '0812310239'),
(240, '10240', 'Siswa XIIDKVB 10', 'XII DKV B', '0812310240'),
(241, '10241', 'Siswa XIIATUA 1', 'XII ATU A', '0812310241'),
(242, '10242', 'Siswa XIIATUA 2', 'XII ATU A', '0812310242'),
(243, '10243', 'Siswa XIIATUA 3', 'XII ATU A', '0812310243'),
(244, '10244', 'Siswa XIIATUA 4', 'XII ATU A', '0812310244'),
(245, '10245', 'Siswa XIIATUA 5', 'XII ATU A', '0812310245'),
(246, '10246', 'Siswa XIIATUA 6', 'XII ATU A', '0812310246'),
(247, '10247', 'Siswa XIIATUA 7', 'XII ATU A', '0812310247'),
(248, '10248', 'Siswa XIIATUA 8', 'XII ATU A', '0812310248'),
(249, '10249', 'Siswa XIIATUA 9', 'XII ATU A', '0812310249'),
(250, '10250', 'Siswa XIIATUA 10', 'XII ATU A', '0812310250'),
(251, '10251', 'Siswa XIIATUB 1', 'XII ATU B', '0812310251'),
(252, '10252', 'Siswa XIIATUB 2', 'XII ATU B', '0812310252'),
(253, '10253', 'Siswa XIIATUB 3', 'XII ATU B', '0812310253'),
(254, '10254', 'Siswa XIIATUB 4', 'XII ATU B', '0812310254'),
(255, '10255', 'Siswa XIIATUB 5', 'XII ATU B', '0812310255'),
(256, '10256', 'Siswa XIIATUB 6', 'XII ATU B', '0812310256'),
(257, '10257', 'Siswa XIIATUB 7', 'XII ATU B', '0812310257'),
(258, '10258', 'Siswa XIIATUB 8', 'XII ATU B', '0812310258'),
(259, '10259', 'Siswa XIIATUB 9', 'XII ATU B', '0812310259'),
(260, '10260', 'Siswa XIIATUB 10', 'XII ATU B', '0812310260'),
(261, '10261', 'Siswa XIITSMA 1', 'XII TSM A', '0812310261'),
(262, '10262', 'Siswa XIITSMA 2', 'XII TSM A', '0812310262'),
(263, '10263', 'Siswa XIITSMA 3', 'XII TSM A', '0812310263'),
(264, '10264', 'Siswa XIITSMA 4', 'XII TSM A', '0812310264'),
(265, '10265', 'Siswa XIITSMA 5', 'XII TSM A', '0812310265'),
(266, '10266', 'Siswa XIITSMA 6', 'XII TSM A', '0812310266'),
(267, '10267', 'Siswa XIITSMA 7', 'XII TSM A', '0812310267'),
(268, '10268', 'Siswa XIITSMA 8', 'XII TSM A', '0812310268'),
(269, '10269', 'Siswa XIITSMA 9', 'XII TSM A', '0812310269'),
(270, '10270', 'Siswa XIITSMA 10', 'XII TSM A', '0812310270'),
(271, '10271', 'Siswa XIITSMB 1', 'XII TSM B', '0812310271'),
(272, '10272', 'Siswa XIITSMB 2', 'XII TSM B', '0812310272'),
(273, '10273', 'Siswa XIITSMB 3', 'XII TSM B', '0812310273'),
(274, '10274', 'Siswa XIITSMB 4', 'XII TSM B', '0812310274'),
(275, '10275', 'Siswa XIITSMB 5', 'XII TSM B', '0812310275'),
(276, '10276', 'Siswa XIITSMB 6', 'XII TSM B', '0812310276'),
(277, '10277', 'Siswa XIITSMB 7', 'XII TSM B', '0812310277'),
(278, '10278', 'Siswa XIITSMB 8', 'XII TSM B', '0812310278'),
(280, '10280', 'Siswa XIITSMB 10', 'XII TSM B', '0812310280'),
(281, '10281', 'Siswa XIITBA 1', 'XII TB A', '0812310281'),
(282, '10282', 'Siswa XIITBA 2', 'XII TB A', '0812310282'),
(283, '10283', 'Siswa XIITBA 3', 'XII TB A', '0812310283'),
(284, '10284', 'Siswa XIITBA 4', 'XII TB A', '0812310284'),
(285, '10285', 'Siswa XIITBA 5', 'XII TB A', '0812310285'),
(286, '10286', 'Siswa XIITBA 6', 'XII TB A', '0812310286'),
(287, '10287', 'Siswa XIITBA 7', 'XII TB A', '0812310287'),
(288, '10288', 'Siswa XIITBA 8', 'XII TB A', '0812310288'),
(289, '10289', 'Siswa XIITBA 9', 'XII TB A', '0812310289'),
(290, '10290', 'Siswa XIITBA 10', 'XII TB A', '0812310290'),
(291, '10291', 'Siswa XIITBB 1', 'XII TB B', '0812310291'),
(292, '10292', 'Siswa XIITBB 2', 'XII TB B', '0812310292'),
(293, '10293', 'Siswa XIITBB 3', 'XII TB B', '0812310293'),
(294, '10294', 'Siswa XIITBB 4', 'XII TB B', '0812310294'),
(295, '10295', 'Siswa XIITBB 5', 'XII TB B', '0812310295'),
(296, '10296', 'Siswa XIITBB 6', 'XII TB B', '0812310296'),
(297, '10297', 'Siswa XIITBB 7', 'XII TB B', '0812310297'),
(298, '10298', 'Siswa XIITBB 8', 'XII TB B', '0812310298'),
(299, '10299', 'Siswa XIITBB 9', 'XII TB B', '0812310299'),
(300, '10300', 'Siswa XIITBB 10', 'XII TB B', '0812310300');

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
(1, 'senin', '06:00:00', '07:00:00', '14:00:00', '15:00:00');

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`),
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
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=304;

--
-- AUTO_INCREMENT for table `data`
--
ALTER TABLE `data`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=302;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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

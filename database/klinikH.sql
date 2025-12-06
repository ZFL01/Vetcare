-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20251111.102c4d8cbc
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 01, 2025 at 02:46 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `klinikh`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_dokter`
--

CREATE TABLE `detail_dokter` (
  `id_dokter` int DEFAULT NULL,
  `id_kategori` tinyint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_dokter`
--

INSERT INTO `detail_dokter` (`id_dokter`, `id_kategori`) VALUES
(25, 3),
(1, 1),
(25, 1),
(30, 1),
(30, 3),
(100, 1),
(100, 5),
(101, 1),
(102, 2),
(102, 6),
(103, 1),
(104, 3),
(105, 1),
(106, 2),
(107, 4),
(108, 1),
(108, 5),
(109, 1),
(109, 3),
(109, 5),
(NULL, 1),
(NULL, 5),
(NULL, 1),
(NULL, 2),
(NULL, 6),
(NULL, 1),
(NULL, 3),
(NULL, 1),
(NULL, 2),
(NULL, 4),
(NULL, 1),
(NULL, 5),
(NULL, 1),
(NULL, 3),
(NULL, 5);

-- --------------------------------------------------------

--
-- Table structure for table `jwb_dokter`
--

CREATE TABLE `jwb_dokter` (
  `id_dokter` int NOT NULL,
  `id_tanya` int DEFAULT NULL,
  `nama_dokter` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `publish` timestamp NULL DEFAULT (now()),
  `update` timestamp NULL DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_location`
--

CREATE TABLE `log_location` (
  `id` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `koor` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `kabupaten` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `provinsi` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT (now())
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_rating`
--

CREATE TABLE `log_rating` (
  `idChat` char(19) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `liked?` tinyint(1) NOT NULL DEFAULT '1',
  `end` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_artikel`
--

CREATE TABLE `m_artikel` (
  `id_artikel` int NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `preview` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `isi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `referensi` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `author` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `author_id` int DEFAULT (0),
  `published` timestamp NOT NULL DEFAULT (now()),
  `views` int NOT NULL DEFAULT (0),
  `updated` timestamp NOT NULL DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP,
  `tag` tinyint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_artikel`
--

INSERT INTO `m_artikel` (`id_artikel`, `judul`, `preview`, `isi`, `referensi`, `author`, `author_id`, `published`, `views`, `updated`, `tag`) VALUES
(33, 'Pentingnya Skrining Rutin untuk Mencegah Parvovirus pada Anjing', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-08-18 12:08:15', 91, '2025-11-10 12:14:44', 14),
(34, 'Tanda-tanda dan Pengobatan Awal Abses pada Kucing', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-08-18 12:08:15', 27, '2025-11-10 12:14:44', 17),
(35, 'Panduan Pemasangan Microchip: Manfaat dan Prosedurnya', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-08-18 12:08:15', 11, '2025-11-10 12:14:44', 22),
(36, 'Strategi Diet Pasca Sterilisasi untuk Mencegah Kenaikan Berat Badan', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-08-21 12:08:15', 29, '2025-11-10 12:14:44', 20),
(37, 'Mengenali Gejala Distemper Anjing dan Langkah Penanganannya', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-08-25 12:08:15', 33, '2025-11-10 12:14:44', 13),
(38, 'Perawatan Khusus untuk Anjing Senior: Fokus pada Pakan dan Suplemen', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-08-29 12:08:15', 22, '2025-11-10 12:14:44', 24),
(39, 'Risiko dan Penanganan Keracunan Tikus pada Anjing dan Kucing', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-09-01 12:08:15', 21, '2025-11-10 12:14:44', 26),
(40, 'Pentingnya Perawatan Gigi Rutin untuk Mencegah Penyakit Periodontal Anjing', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-09-01 12:08:15', 96, '2025-11-10 12:14:44', 16),
(41, 'Langkah Darurat dan Penanganan Awal Cakar Retak pada Anjing', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-09-03 12:08:15', 112, '2025-11-10 12:14:44', 18),
(42, 'Kapan Operasi Amputasi Diperlukan pada Hewan Peliharaan?', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-09-10 12:08:15', 22, '2025-11-10 12:14:44', 21),
(43, 'Menjaga Imunitas Kucing di Tengah Wabah Feline Infectious Peritonitis (FIP)', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-09-11 12:08:15', 29, '2025-11-10 12:14:44', 15),
(44, 'Dampak dan Pencegahan Bulu Rontok Parah pada Anjing Ras Besar', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-09-14 12:08:15', 9, '2025-11-10 12:14:44', 18),
(45, 'Panduan Perawatan Gigi untuk Anjing Tua: Mencegah Penyakit Gusi', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-09-17 12:08:15', 33, '2025-11-10 12:14:44', 16),
(46, 'Cara Mengatasi Kecemasan Berpisah (Separation Anxiety) pada Anjing', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-09-19 12:08:15', 44, '2025-11-10 12:14:44', 23),
(47, 'Gejala Klinis Anemia Kucing dan Pilihan Terapi yang Tersedia', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-09-21 12:08:15', 20, '2025-11-10 12:14:44', 25),
(48, 'Penanganan Abses Kucing: Kapan Perlu Tindakan Bedah?', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-09-25 12:08:15', 21, '2025-11-10 12:14:44', 17),
(49, '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Oke', NULL, '2025-11-10 12:08:15', 12, '2025-11-10 18:18:58', 12),
(50, 'Memilih Pakan Terbaik untuk Anjing Senior dengan Masalah Sendi', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Oke', NULL, '2025-11-10 12:08:15', 12, '2025-11-10 18:18:58', 24),
(51, 'Prosedur dan Resiko Operasi Amputasi pada Kasus Fraktur Berat', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Oke', NULL, '2025-11-10 12:08:15', 92, '2025-11-10 18:18:58', 21),
(52, 'Perawatan Intensif untuk Anak Kucing dengan Sistem Imun yang Lemah', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Oke', NULL, '2025-11-10 12:08:15', 43, '2025-11-10 18:18:58', 26),
(53, 'Tips Menjaga Kebersihan Cakar Anjing dan Mencegah Retak', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Oke', NULL, '2025-11-10 12:08:15', 12, '2025-11-10 18:18:58', 18),
(54, 'Dampak Keracunan Bahan Kimia Rumah Tangga pada Hewan Peliharaan', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-11-10 12:08:15', 12, '2025-11-10 12:08:15', 26),
(55, 'Analisis Komponen Pakan Anjing untuk Mengatasi Alergi Kulit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-11-10 12:08:15', 23, '2025-11-10 12:08:15', 23),
(56, 'Pentingnya Microchip sebagai Identitas Permanen Hewan Kesayangan', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-10-08 12:08:15', 19, '2025-11-10 16:09:30', 22),
(57, 'Diet Khusus untuk Anjing Pasca Operasi Sterilisasi', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-10-14 12:08:15', 15, '2025-11-10 16:09:30', 20),
(58, 'Mencegah Penularan Virus Distemper pada Anjing yang Belum Divaksin', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-10-15 12:08:15', 12, '2025-11-10 16:09:30', 13),
(59, 'Pengenalan Gejala Awal dan Penanganan Parvovirus pada Anjing Muda', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-10-17 12:08:15', 11, '2025-11-10 16:09:30', 14),
(60, 'Perawatan Komprehensif untuk Kucing yang Didiagnosis Anemia', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-10-20 12:08:15', 24, '2025-11-10 16:09:30', 25),
(61, 'Langkah Pencegahan dan Pertolongan Pertama Keracunan pada Hewan', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-10-22 12:08:15', 25, '2025-11-10 16:09:30', 26),
(62, 'Pilihan Pengobatan Jangka Panjang untuk Anjing dengan Kecemasan Berpisah', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'Drh. Slamet', 1, '2025-10-28 12:08:15', 21, '2025-11-10 16:09:30', 23);

-- --------------------------------------------------------

--
-- Table structure for table `m_doc_dokter`
--

CREATE TABLE `m_doc_dokter` (
  `id_dokter` int DEFAULT NULL,
  `path_sip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `path_strv` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_doc_dokter`
--

INSERT INTO `m_doc_dokter` (`id_dokter`, `path_sip`, `path_strv`) VALUES
(30, 'sip_30251128131238.docx', 'strv_30251128131238.docx');

-- --------------------------------------------------------

--
-- Table structure for table `m_dokter`
--

CREATE TABLE `m_dokter` (
  `id_dokter` int NOT NULL,
  `nama_dokter` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `ttl` date NOT NULL DEFAULT (curdate()),
  `strv` char(21) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `exp_strv` date DEFAULT (curdate()),
  `sip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `exp_sip` date DEFAULT (curdate()),
  `foto` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pengalaman` year NOT NULL,
  `rate` decimal(3,2) NOT NULL DEFAULT '1.00',
  `status` enum('aktif','nonaktif') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'nonaktif',
  `harga` mediumint UNSIGNED DEFAULT NULL,
  `kabupaten` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `provinsi` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_dokter`
--

INSERT INTO `m_dokter` (`id_dokter`, `nama_dokter`, `ttl`, `strv`, `exp_strv`, `sip`, `exp_sip`, `foto`, `pengalaman`, `rate`, `status`, `harga`, `kabupaten`, `provinsi`) VALUES
(1, 'Slamet', '1995-10-09', '79snkdua', '2025-10-09', 'ra4456112', '2031-10-09', NULL, '2002', 1.00, 'aktif', NULL, NULL, NULL),
(25, 'hai', '1999-11-15', '67839jd', '2028-11-15', '6278ejk', '2030-11-15', NULL, '1970', 1.00, 'aktif', 20000, 'Jogja', 'Jogja'),
(30, 'darah darah', '2000-01-16', '0', '2025-11-28', '0', '2025-11-28', 'pr_30251128.jpg', '2022', 1.00, 'aktif', NULL, 'Kab. Situbondo', 'Jawa Timur'),
(100, 'Drh. Kurniawan Santoso', '1985-05-20', 'STRV-100-2025', '2030-05-20', 'SIP-100-DISHUB', '2030-05-20', 'pr_30251128.jpg', '2010', 4.80, 'aktif', 50000, 'Jakarta Selatan', 'DKI Jakarta'),
(101, 'Drh. Siti Aminah', '1990-08-14', 'STRV-101-2025', '2029-08-14', 'SIP-101-DISHUB', '2029-08-14', 'pr_30251128.jpg', '2015', 4.90, 'aktif', 45000, 'Bandung', 'Jawa Barat'),
(102, 'Drh. Bambang Pamungkas', '1988-02-10', 'STRV-102-2025', '2028-02-10', 'SIP-102-DISHUB', '2028-02-10', 'pr_30251128.jpg', '2012', 4.50, 'aktif', 40000, 'Surabaya', 'Jawa Timur'),
(103, 'Drh. Jessica Iskandar', '1995-12-01', 'STRV-103-2025', '2031-12-01', 'SIP-103-DISHUB', '2031-12-01', 'pr_30251128.jpg', '2020', 4.70, 'aktif', 60000, 'Denpasar', 'Bali'),
(104, 'Drh. Reza Rahardian', '1992-06-30', 'STRV-104-2025', '2029-06-30', 'SIP-104-DISHUB', '2029-06-30', 'pr_30251128.jpg', '2016', 5.00, 'aktif', 75000, 'Medan', 'Sumatera Utara'),
(105, 'Drh. Linda Permata', '1993-03-15', 'STRV-105-2025', '2029-03-15', 'SIP-105-DISHUB', '2029-03-15', 'pr_30251128.jpg', '2017', 4.60, 'nonaktif', 35000, 'Semarang', 'Jawa Tengah'),
(106, 'Drh. Agus Salim', '1980-11-11', 'STRV-106-2025', '2027-11-11', 'SIP-106-DISHUB', '2027-11-11', 'pr_30251128.jpg', '2005', 4.80, 'aktif', 55000, 'Makassar', 'Sulawesi Selatan'),
(107, 'Drh. Nadia Hutabarat', '1996-09-09', 'STRV-107-2025', '2031-09-09', 'SIP-107-DISHUB', '2031-09-09', 'pr_30251128.jpg', '2021', 4.40, 'aktif', 30000, 'Palembang', 'Sumatera Selatan'),
(108, 'Drh. Fajar Subekti', '1989-07-22', 'STRV-108-2025', '2028-07-22', 'SIP-108-DISHUB', '2028-07-22', 'pr_30251128.jpg', '2014', 4.50, 'aktif', 40000, 'Yogyakarta', 'DI Yogyakarta'),
(109, 'Drh. Clara Wong', '1994-01-25', 'STRV-109-2025', '2030-01-25', 'SIP-109-DISHUB', '2030-01-25', 'pr_30251128.jpg', '2018', 4.90, 'aktif', 65000, 'Surabaya', 'Jawa Timur');

-- --------------------------------------------------------

--
-- Table structure for table `m_hpraktik`
--

CREATE TABLE `m_hpraktik` (
  `id_dokter` int NOT NULL DEFAULT (0),
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Minggu',
  `buka` time DEFAULT NULL,
  `tutup` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_hpraktik`
--

INSERT INTO `m_hpraktik` (`id_dokter`, `hari`, `buka`, `tutup`) VALUES
(1, 'Minggu', '16:45:00', '07:30:00'),
(1, 'Rabu', '09:00:00', '14:50:00'),
(30, 'Jumat', '00:00:00', '09:00:00'),
(1, 'Minggu', '16:45:00', '07:30:00'),
(1, 'Rabu', '09:00:00', '14:50:00'),
(100, 'Senin', '09:00:00', '15:00:00'),
(100, 'Rabu', '09:00:00', '15:00:00'),
(100, 'Jumat', '13:00:00', '17:00:00'),
(100, 'Jumat', '21:30:00', '03:00:00'),
(101, 'Selasa', '10:00:00', '18:00:00'),
(101, 'Kamis', '10:00:00', '18:00:00'),
(101, 'Sabtu', '08:00:00', '12:00:00'),
(102, 'Senin', '16:00:00', '21:00:00'),
(102, 'Selasa', '16:00:00', '21:00:00'),
(102, 'Rabu', '16:00:00', '21:00:00'),
(103, 'Sabtu', '08:00:00', '14:00:00'),
(103, 'Minggu', '09:00:00', '15:00:00'),
(104, 'Senin', '08:00:00', '12:00:00'),
(104, 'Selasa', '08:00:00', '12:00:00'),
(104, 'Rabu', '08:00:00', '12:00:00'),
(104, 'Kamis', '08:00:00', '12:00:00'),
(105, 'Jumat', '13:00:00', '20:00:00'),
(106, 'Jumat', '08:00:00', '11:00:00'),
(106, 'Sabtu', '18:00:00', '22:00:00'),
(107, 'Senin', '18:00:00', '22:00:00'),
(107, 'Rabu', '18:00:00', '22:00:00'),
(107, 'Jumat', '18:00:00', '22:00:00'),
(108, 'Selasa', '09:00:00', '15:00:00'),
(108, 'Kamis', '09:00:00', '15:00:00'),
(108, 'Sabtu', '09:00:00', '14:00:00'),
(109, 'Minggu', '10:00:00', '16:00:00'),
(109, 'Senin', '10:00:00', '16:00:00'),
(109, 'Sabtu', '07:00:00', '00:00:00'),
(30, 'Jumat', '00:00:00', '09:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `m_kategori`
--

CREATE TABLE `m_kategori` (
  `id_kategori` tinyint UNSIGNED NOT NULL,
  `nama_kateg` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_kategori`
--

INSERT INTO `m_kategori` (`id_kategori`, `nama_kateg`) VALUES
(1, 'Peliharaan'),
(2, 'Ternak'),
(3, 'Eksotis'),
(4, 'Akuatik'),
(5, 'Hewan Kecil'),
(6, 'Unggas');

-- --------------------------------------------------------

--
-- Table structure for table `m_lokasipraktik`
--

CREATE TABLE `m_lokasipraktik` (
  `dokter` int DEFAULT NULL,
  `nama_klinik` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lat` decimal(10,8) NOT NULL DEFAULT (0),
  `long` decimal(11,8) NOT NULL DEFAULT (0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_lokasipraktik`
--

INSERT INTO `m_lokasipraktik` (`dokter`, `nama_klinik`, `lat`, `long`) VALUES
(100, 'Klinik Hewan Sejahtera Jakarta', -6.26149300, 106.81059900),
(101, 'Pet Care Bandung Juara', -6.91746400, 107.61912300),
(102, 'Surabaya Vet Center', -7.25747200, 112.75208800),
(103, 'Bali Veterinary House', -8.40951800, 115.18891900),
(104, 'Medan Pet Clinic & Care', 3.59519600, 98.67222300),
(105, 'Semarang Animal Care', -6.96666700, 110.41666400),
(106, 'Makassar Vet Point', -5.14766500, 119.43273100),
(107, 'Palembang Pet House', -2.97607400, 104.77543100),
(108, 'Jogja Vets & Grooming', -7.79558000, 110.36949000),
(109, 'Klinik Hewan Sahabat Surabaya', -7.26551200, 112.74110000),
(30, 'masjid al hikmah', -8.20933800, 113.60384000);

-- --------------------------------------------------------

--
-- Table structure for table `m_pengguna`
--

CREATE TABLE `m_pengguna` (
  `id_pengguna` int NOT NULL,
  `email` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pass` char(97) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('Admin','Dokter','Member') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created` timestamp NULL DEFAULT (now()),
  `reset_token` char(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `exp_token` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_pengguna`
--

INSERT INTO `m_pengguna` (`id_pengguna`, `email`, `pass`, `role`, `created`, `reset_token`, `exp_token`) VALUES
(1, 'o@o.mai.com', '12345', 'Dokter', '2025-10-08 17:00:00', NULL, '2025-10-19 10:17:52'),
(2, 'anu@mail.com', '12345', 'Member', '2025-10-09 12:45:37', NULL, '2025-10-19 10:17:52'),
(3, 'Tes@mail.com', '12345', 'Admin', '2025-10-25 13:33:20', NULL, NULL),
(25, 'akunbbersama@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$UjNRLlhBYkRPNGlDUXltdA$3dusYKj8nzEfYwSWSZ/bSKaImdBsrX0dBRvDxsK3tRs', 'Dokter', '2025-11-15 04:43:13', NULL, NULL),
(28, 'ohiyakah@mail.co', '$argon2id$v=19$m=65536,t=4,p=1$YWs3V1VUVHVVMW1zeTAxRQ$5ZExeTBmGfDPVF/MEPSI8MAISXcDqHUGiUedyNX5HTs', 'Member', '2025-11-15 06:06:25', NULL, NULL),
(29, 'user@user.com', '$argon2id$v=19$m=65536,t=4,p=1$WWF1am9xWjFkS0hlSi5OUQ$oT1jN7/1rLWYiqQf9F+aYm/6vewnQYD0Hz8hWfTj5eo', 'Member', '2025-11-17 11:37:31', NULL, NULL),
(30, 'darah@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 05:53:13', NULL, NULL),
(31, 'budiogemink@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$cEhValY0WmRiUjdrVThEcA$dwSQwua57ZjUm8jxPmon9kk3Tw5ceyOhWRGQGxeRe4E', 'Dokter', '2025-11-28 08:07:54', NULL, NULL),
(32, 'simanjutak@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$MURqelN3OGZWVU8vaUpzeA$qcwajp9QPlZR2MFh9VwVxR5pJfkhDsoA9+vnHfJ1lkc', 'Dokter', '2025-11-28 08:08:58', NULL, NULL),
(33, 'apacoba@gg.com', '$argon2id$v=19$m=65536,t=4,p=1$R1R3dURWdlBmODZrd2dzOA$K+MuL9NidAuSoBlJikOFoucM4K7gahVTK2O387j3G0w', 'Dokter', '2025-11-28 08:09:20', NULL, NULL),
(100, 'drh.kurniawan@klinikh.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 08:20:28', NULL, NULL),
(101, 'drh.siti.amina@klinikh.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 08:20:28', NULL, NULL),
(102, 'drh.bambang@klinikh.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 08:20:28', NULL, NULL),
(103, 'drh.jessica@klinikh.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 08:20:28', NULL, NULL),
(104, 'drh.reza.rahardian@klinikh.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 08:20:28', NULL, NULL),
(105, 'drh.linda.permata@klinikh.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 08:20:28', NULL, NULL),
(106, 'drh.agus.salim@klinikh.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 08:20:28', NULL, NULL),
(107, 'drh.nadia.hutabarat@klinikh.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 08:20:28', NULL, NULL),
(108, 'drh.fajar.subekti@klinikh.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 08:20:28', NULL, NULL),
(109, 'drh.clara.wong@klinikh.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 08:20:28', NULL, NULL),
(110, 'pecintakucing123@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Member', '2025-11-28 08:20:28', NULL, NULL),
(111, 'doglover_jakarta@yahoo.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Member', '2025-11-28 08:20:28', NULL, NULL),
(112, 'reptile_house@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Member', '2025-11-28 08:20:28', NULL, NULL),
(113, 'hamster.lucu@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Member', '2025-11-28 08:20:28', NULL, NULL),
(114, 'betta.fish.master@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Member', '2025-11-28 08:20:28', NULL, NULL),
(115, 'peternak.ayam.jago@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Member', '2025-11-28 08:20:28', NULL, NULL),
(116, 'siti.nurhaliza@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Member', '2025-11-28 08:20:28', NULL, NULL),
(117, 'dimas.anggara@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Member', '2025-11-28 08:20:28', NULL, NULL),
(118, 'ratna.sari@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Member', '2025-11-28 08:20:28', NULL, NULL),
(119, 'joko.widodo.kw@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Member', '2025-11-28 08:20:28', NULL, NULL),
(200, 'drh.kurniawan@klinikh.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 08:35:35', NULL, NULL),
(201, 'drh.siti.amina@klinikh.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 08:35:35', NULL, NULL),
(202, 'drh.bambang@klinikh.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 08:35:35', NULL, NULL),
(203, 'drh.jessica@klinikh.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 08:35:35', NULL, NULL),
(204, 'drh.reza.rahardian@klinikh.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 08:35:35', NULL, NULL),
(205, 'drh.linda.permata@klinikh.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 08:35:35', NULL, NULL),
(206, 'drh.agus.salim@klinikh.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 08:35:35', NULL, NULL),
(207, 'drh.nadia.hutabarat@klinikh.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 08:35:35', NULL, NULL),
(208, 'drh.fajar.subekti@klinikh.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 08:35:35', NULL, NULL),
(209, 'drh.clara.wong@klinikh.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Dokter', '2025-11-28 08:35:35', NULL, NULL),
(210, 'pecintakucing123@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Member', '2025-11-28 08:35:35', NULL, NULL),
(211, 'doglover_jakarta@yahoo.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Member', '2025-11-28 08:35:35', NULL, NULL),
(212, 'reptile_house@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Member', '2025-11-28 08:35:35', NULL, NULL),
(213, 'hamster.lucu@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Member', '2025-11-28 08:35:35', NULL, NULL),
(214, 'betta.fish.master@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Member', '2025-11-28 08:35:35', NULL, NULL),
(215, 'peternak.ayam.jago@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Member', '2025-11-28 08:35:35', NULL, NULL),
(216, 'siti.nurhaliza@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Member', '2025-11-28 08:35:35', NULL, NULL),
(217, 'dimas.anggara@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Member', '2025-11-28 08:35:35', NULL, NULL),
(218, 'ratna.sari@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Member', '2025-11-28 08:35:35', NULL, NULL),
(219, 'joko.widodo.kw@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$UEE1WHp2cFg0SkQvWW4uYg$I22conSotWKB1O0pvwSbTmjwL8qvHZ+h6Mgli0SwSCE', 'Member', '2025-11-28 08:35:35', NULL, NULL),
(220, 'vetcare@admin.com', '$argon2id$v=19$m=65536,t=4,p=1$Z2YveFFiOWFwcS5iSWRNNg$8pdFQXqDceBzNxql0jSmPstgrnSPzG5+t5wugq3FJ/o', 'Admin', '2025-11-29 20:40:25', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_tag`
--

CREATE TABLE `m_tag` (
  `idTag` tinyint UNSIGNED NOT NULL,
  `tag` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_tag`
--

INSERT INTO `m_tag` (`idTag`, `tag`) VALUES
(1, 'Pencernaan & Muntah'),
(2, 'Demam & Virus'),
(3, 'Masalah Kulit & Shedding'),
(4, 'Masalah Mata & Dropsy'),
(5, 'Benjolan & Tumor'),
(6, 'Pernafasan & Flu'),
(7, 'Tulang & Sendi'),
(8, 'Telinga'),
(9, 'Reproduksi'),
(10, 'Perilaku'),
(11, 'Lain-lain'),
(12, 'Vaksinasi Rabies'),
(13, 'Distemper Anjing'),
(14, 'Parvo virus Anjing'),
(15, 'FIP Vaksin Kucing'),
(16, 'Perawatan Gigi Anjing'),
(17, 'Abses Kucing'),
(18, 'Cakar Retak Anjing'),
(19, 'Bulu Rontok Parah'),
(20, 'Diet Pasca Steril'),
(21, 'Operasi Amputasi'),
(22, 'Pemasangan Microchip'),
(23, 'Pakan Anjing Senior'),
(24, 'Anemia Kucing'),
(25, 'Sistem Imun Bayi Kucing'),
(26, 'Keracunan Tikus'),
(27, 'Kalau buat baru'),
(28, 'Cakar Retak Anjing galak');

-- --------------------------------------------------------

--
-- Table structure for table `tr_tanya`
--

CREATE TABLE `tr_tanya` (
  `id_tanya` int NOT NULL,
  `id_penanya` int DEFAULT NULL,
  `penanya` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `judul` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `pertanyaan` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `dibuat` timestamp NOT NULL DEFAULT (now()),
  `status` enum('terjawab','menunggu') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'menunggu',
  `idTag` tinyint UNSIGNED DEFAULT (0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tr_tanya`
--

INSERT INTO `tr_tanya` (`id_tanya`, `id_penanya`, `penanya`, `judul`, `pertanyaan`, `dibuat`, `status`, `idTag`) VALUES
(1, 29, 'user@user.com', 'nyoba ya, ini buat tes aja sih sebenernya', 'kok bisa ya, habis digituin malah menjadi kyak gini', '2025-11-17 15:49:58', 'menunggu', NULL),
(2, 29, 'user@user.com', 'nyoba ya, ini buat tes aja sih sebenernya', 'kok bisa ya, habis digituin malah menjadi kyak gini', '2025-11-17 15:51:12', 'menunggu', 16),
(3, 29, 'user@user.com', 'nyoba ya, ini buat tes aja sih sebenernya', 'kok bisa ya, habis digituin malah menjadi kyak gini', '2025-11-17 15:51:40', 'menunggu', 21),
(4, 29, 'user@user.com', 'nyoba ya, ini buat tes aja sih sebenernya', 'kok bisa ya, habis digituin malah menjadi kyak gini', '2025-11-17 15:52:12', 'menunggu', 27),
(5, 29, 'user@user.com', 'nyoba ya, ini buat tes aja sih sebenernya', 'kok bisa ya, habis digituin malah menjadi kyak gini', '2025-11-17 15:52:39', 'menunggu', 18),
(6, 29, 'user@user.com', 'nyoba ya, ini buat tes aja sih sebenernya', 'kok bisa ya, habis digituin malah menjadi kyak gini', '2025-11-17 15:52:56', 'menunggu', 28),
(22, 110, 'pecintakucing123@gmail.com', 'Kucing tidak mau makan 3 hari', 'Dok, kucing saya lemas dan tidak mau makan dry food ataupun wet food selama 3 hari ini. Apakah ini gejala virus?', '2025-11-23 08:26:57', 'terjawab', 25),
(23, 110, 'pecintakucing123@gmail.com', 'Cara merawat luka cakar', 'Kucing saya berkelahi dan ada luka terbuka di telinga, bagaimana perawatannya?', '2025-11-24 08:26:57', 'terjawab', 17),
(24, 111, 'doglover_jakarta@yahoo.com', 'Anjing Diare Berdarah', 'Tolong dok, anjing saya pupnya ada darah segar, apakah ini parvo?', '2025-11-25 08:26:57', 'menunggu', 14),
(29, 116, 'siti.nurhaliza@mail.com', 'Jadwal Vaksin Kucing', 'Dok, umur berapa anak kucing boleh vaksin pertama kali?', '2025-11-20 08:26:57', 'terjawab', 15),
(31, 117, 'dimas.anggara@mail.com', 'Anjing gatal-gatal parah', 'Anjing golden saya garuk-garuk terus sampai bulunya botak di punggung.', '2025-11-27 22:26:57', 'menunggu', 19),
(32, 118, 'ratna.sari@mail.com', 'Steril Kucing Betina', 'Persiapan apa saja sebelum melakukan sterilisasi pada kucing betina?', '2025-11-28 03:26:57', 'menunggu', 20),
(34, 111, 'doglover_jakarta@yahoo.com', 'Makanan untuk anjing tua', 'Rekomendasi dogfood untuk anjing usia 10 tahun yang giginya sudah ompong.', '2025-11-16 08:26:57', 'terjawab', 23),
(36, 110, 'pecintakucing123@gmail.com', 'Vaksin Rabies Wajib?', 'Apakah kucing rumahan wajib vaksin rabies dok?', '2025-11-27 08:26:57', 'menunggu', 12),
(37, 210, 'pecintakucing123@gmail.com', 'Kucing tidak mau makan 3 hari', 'Dok, kucing saya lemas dan tidak mau makan dry food ataupun wet food selama 3 hari ini. Apakah ini gejala virus?', '2025-11-23 08:35:35', 'terjawab', 25),
(38, 210, 'pecintakucing123@gmail.com', 'Cara merawat luka cakar', 'Kucing saya berkelahi dan ada luka terbuka di telinga, bagaimana perawatannya?', '2025-11-24 08:35:35', 'terjawab', 17),
(39, 211, 'doglover_jakarta@yahoo.com', 'Anjing Diare Berdarah', 'Tolong dok, anjing saya pupnya ada darah segar, apakah ini parvo?', '2025-11-25 08:35:35', 'menunggu', 14),
(44, 216, 'siti.nurhaliza@mail.com', 'Jadwal Vaksin Kucing', 'Dok, umur berapa anak kucing boleh vaksin pertama kali?', '2025-11-20 08:35:35', 'terjawab', 15),
(46, 217, 'dimas.anggara@mail.com', 'Anjing gatal-gatal parah', 'Anjing golden saya garuk-garuk terus sampai bulunya botak di punggung.', '2025-11-27 22:35:35', 'menunggu', 19),
(47, 218, 'ratna.sari@mail.com', 'Steril Kucing Betina', 'Persiapan apa saja sebelum melakukan sterilisasi pada kucing betina?', '2025-11-28 03:35:35', 'menunggu', 20),
(49, 211, 'doglover_jakarta@yahoo.com', 'Makanan untuk anjing tua', 'Rekomendasi dogfood untuk anjing usia 10 tahun yang giginya sudah ompong.', '2025-11-16 08:35:35', 'terjawab', 23),
(51, 210, 'pecintakucing123@gmail.com', 'Vaksin Rabies Wajib?', 'Apakah kucing rumahan wajib vaksin rabies dok?', '2025-11-27 08:35:35', 'menunggu', 12);

-- --------------------------------------------------------

--
-- Table structure for table `tr_transaksi`
--

CREATE TABLE `tr_transaksi` (
  `id_tr` char(19) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'AUTO_INCREMENT',
  `eksternal_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int DEFAULT (0),
  `dokter_id` int DEFAULT (0),
  `created` timestamp NOT NULL DEFAULT (now()),
  `status` enum('pending','success','expired','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tr_transaksi`
--

INSERT INTO `tr_transaksi` (`id_tr`, `eksternal_id`, `user_id`, `dokter_id`, `created`, `status`, `paid_at`) VALUES
('TRX-20251120-001', 'ORD-1120-001', 210, NULL, '2025-11-20 08:35:35', 'success', '2025-11-20 08:35:35'),
('TRX-20251121-002', 'ORD-1121-002', 210, NULL, '2025-11-21 08:35:35', 'success', '2025-11-21 08:35:35'),
('TRX-20251122-003', 'ORD-1122-003', 211, NULL, '2025-11-22 08:35:35', 'pending', NULL),
('TRX-20251123-004', 'ORD-1123-004', 212, NULL, '2025-11-23 08:35:35', 'success', '2025-11-23 08:35:35'),
('TRX-20251123-005', 'ORD-1123-005', 213, NULL, '2025-11-23 08:35:35', 'failed', NULL),
('TRX-20251124-006', 'ORD-1124-006', 214, NULL, '2025-11-24 08:35:35', 'success', '2025-11-24 08:35:35'),
('TRX-20251124-007', 'ORD-1124-007', 215, NULL, '2025-11-24 08:35:35', 'expired', NULL),
('TRX-20251125-008', 'ORD-1125-008', 216, NULL, '2025-11-25 08:35:35', 'success', '2025-11-25 08:35:35'),
('TRX-20251125-009', 'ORD-1125-009', 217, NULL, '2025-11-25 08:35:35', 'pending', NULL),
('TRX-20251126-010', 'ORD-1126-010', 218, NULL, '2025-11-26 08:35:35', 'success', '2025-11-26 08:35:35'),
('TRX-20251126-011', 'ORD-1126-011', 219, NULL, '2025-11-26 08:35:35', 'success', '2025-11-26 08:35:35'),
('TRX-20251127-012', 'ORD-1127-012', 210, NULL, '2025-11-27 08:35:35', 'success', '2025-11-27 08:35:35'),
('TRX-20251127-013', 'ORD-1127-013', 211, NULL, '2025-11-27 08:35:35', 'success', '2025-11-27 08:35:35'),
('TRX-20251128-014', 'ORD-1128-014', 212, NULL, '2025-11-28 08:35:35', 'pending', NULL),
('TRX-20251128-015', 'ORD-1128-015', 213, NULL, '2025-11-28 08:35:35', 'pending', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_dokter`
--
ALTER TABLE `detail_dokter`
  ADD KEY `FK_dDokter` (`id_dokter`),
  ADD KEY `FK_dKateg` (`id_kategori`);

--
-- Indexes for table `jwb_dokter`
--
ALTER TABLE `jwb_dokter`
  ADD KEY `FK1_jwban` (`id_dokter`),
  ADD KEY `FK2_artanya` (`id_tanya`);

--
-- Indexes for table `log_location`
--
ALTER TABLE `log_location`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK-locateUser` (`id_user`);

--
-- Indexes for table `log_rating`
--
ALTER TABLE `log_rating`
  ADD KEY `FK-forchat` (`idChat`);

--
-- Indexes for table `m_artikel`
--
ALTER TABLE `m_artikel`
  ADD PRIMARY KEY (`id_artikel`),
  ADD KEY `FK_author` (`author_id`),
  ADD KEY `FK_Tag` (`tag`);

--
-- Indexes for table `m_doc_dokter`
--
ALTER TABLE `m_doc_dokter`
  ADD KEY `FK_doc-dokter` (`id_dokter`);

--
-- Indexes for table `m_dokter`
--
ALTER TABLE `m_dokter`
  ADD PRIMARY KEY (`id_dokter`),
  ADD UNIQUE KEY `STRV` (`strv`) USING BTREE,
  ADD UNIQUE KEY `SIP` (`sip`) USING BTREE,
  ADD KEY `FK_idDokter` (`id_dokter`);

--
-- Indexes for table `m_hpraktik`
--
ALTER TABLE `m_hpraktik`
  ADD KEY `FK_dokter` (`id_dokter`);

--
-- Indexes for table `m_kategori`
--
ALTER TABLE `m_kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `m_lokasipraktik`
--
ALTER TABLE `m_lokasipraktik`
  ADD KEY `FK_locdok` (`dokter`);

--
-- Indexes for table `m_pengguna`
--
ALTER TABLE `m_pengguna`
  ADD PRIMARY KEY (`id_pengguna`) USING BTREE;

--
-- Indexes for table `m_tag`
--
ALTER TABLE `m_tag`
  ADD PRIMARY KEY (`idTag`);

--
-- Indexes for table `tr_tanya`
--
ALTER TABLE `tr_tanya`
  ADD PRIMARY KEY (`id_tanya`),
  ADD KEY `FK_penanya` (`id_penanya`),
  ADD KEY `FK_tanyatag` (`idTag`);

--
-- Indexes for table `tr_transaksi`
--
ALTER TABLE `tr_transaksi`
  ADD PRIMARY KEY (`id_tr`) USING BTREE,
  ADD UNIQUE KEY `order_id` (`eksternal_id`) USING BTREE,
  ADD KEY `FK_trUser` (`user_id`),
  ADD KEY `FK2_tr-dokter` (`dokter_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_location`
--
ALTER TABLE `log_location`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_artikel`
--
ALTER TABLE `m_artikel`
  MODIFY `id_artikel` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `m_kategori`
--
ALTER TABLE `m_kategori`
  MODIFY `id_kategori` tinyint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `m_pengguna`
--
ALTER TABLE `m_pengguna`
  MODIFY `id_pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=221;

--
-- AUTO_INCREMENT for table `m_tag`
--
ALTER TABLE `m_tag`
  MODIFY `idTag` tinyint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tr_tanya`
--
ALTER TABLE `tr_tanya`
  MODIFY `id_tanya` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_dokter`
--
ALTER TABLE `detail_dokter`
  ADD CONSTRAINT `FK_dDokter` FOREIGN KEY (`id_dokter`) REFERENCES `m_dokter` (`id_dokter`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `FK_dKateg` FOREIGN KEY (`id_kategori`) REFERENCES `m_kategori` (`id_kategori`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `jwb_dokter`
--
ALTER TABLE `jwb_dokter`
  ADD CONSTRAINT `FK1_jwban` FOREIGN KEY (`id_dokter`) REFERENCES `m_dokter` (`id_dokter`),
  ADD CONSTRAINT `FK2_artanya` FOREIGN KEY (`id_tanya`) REFERENCES `tr_tanya` (`id_tanya`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `log_location`
--
ALTER TABLE `log_location`
  ADD CONSTRAINT `FK-locateUser` FOREIGN KEY (`id_user`) REFERENCES `m_pengguna` (`id_pengguna`) ON DELETE SET NULL;

--
-- Constraints for table `log_rating`
--
ALTER TABLE `log_rating`
  ADD CONSTRAINT `FK-forchat` FOREIGN KEY (`idChat`) REFERENCES `tr_transaksi` (`id_tr`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `m_artikel`
--
ALTER TABLE `m_artikel`
  ADD CONSTRAINT `FK_author` FOREIGN KEY (`author_id`) REFERENCES `m_dokter` (`id_dokter`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `FK_Tag` FOREIGN KEY (`tag`) REFERENCES `m_tag` (`idTag`);

--
-- Constraints for table `m_doc_dokter`
--
ALTER TABLE `m_doc_dokter`
  ADD CONSTRAINT `FK_doc-dokter` FOREIGN KEY (`id_dokter`) REFERENCES `m_dokter` (`id_dokter`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `m_dokter`
--
ALTER TABLE `m_dokter`
  ADD CONSTRAINT `FK_idDokter` FOREIGN KEY (`id_dokter`) REFERENCES `m_pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `m_hpraktik`
--
ALTER TABLE `m_hpraktik`
  ADD CONSTRAINT `FK_dokter` FOREIGN KEY (`id_dokter`) REFERENCES `m_dokter` (`id_dokter`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `m_lokasipraktik`
--
ALTER TABLE `m_lokasipraktik`
  ADD CONSTRAINT `FK_locdok` FOREIGN KEY (`dokter`) REFERENCES `m_dokter` (`id_dokter`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `tr_tanya`
--
ALTER TABLE `tr_tanya`
  ADD CONSTRAINT `FK_penanya` FOREIGN KEY (`id_penanya`) REFERENCES `m_pengguna` (`id_pengguna`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `FK_tanyatag` FOREIGN KEY (`idTag`) REFERENCES `m_tag` (`idTag`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tr_transaksi`
--
ALTER TABLE `tr_transaksi`
  ADD CONSTRAINT `FK2_tr-dokter` FOREIGN KEY (`dokter_id`) REFERENCES `m_dokter` (`id_dokter`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_trUser` FOREIGN KEY (`user_id`) REFERENCES `m_pengguna` (`id_pengguna`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

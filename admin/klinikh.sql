-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20251031.ff9df302b7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 23, 2025 at 04:51 AM
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
(25, 1);

-- --------------------------------------------------------

--
-- Table structure for table `jwb_dokter`
--

CREATE TABLE `jwb_dokter` (
  `id_dokter` int NOT NULL,
  `id_tanya` int DEFAULT NULL,
  `nama_dokter` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `publish` timestamp NULL DEFAULT (now()),
  `update` timestamp NULL DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_rating`
--

CREATE TABLE `log_rating` (
  `idChat` char(19) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_pengguna` int DEFAULT '0',
  `id_dokter` int DEFAULT NULL,
  `liked?` tinyint(1) NOT NULL DEFAULT '1'
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
  `author` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
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
(25, '2026', '2026');

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
  `foto` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pengalaman` year NOT NULL,
  `rate` decimal(3,2) NOT NULL DEFAULT '1.00',
  `status` enum('aktif','nonaktif') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'nonaktif',
  `harga` mediumint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_dokter`
--

INSERT INTO `m_dokter` (`id_dokter`, `nama_dokter`, `ttl`, `strv`, `exp_strv`, `sip`, `exp_sip`, `foto`, `pengalaman`, `rate`, `status`, `harga`) VALUES
(1, 'Slamet', '1995-10-09', '79snkdua', '2025-10-09', 'ra4456112', '2031-10-09', NULL, '2002', 1.00, 'aktif', NULL),
(25, 'hai', '1999-11-15', '67839jd', '2028-11-15', '6278ejk', '2030-11-15', NULL, '2020', 1.00, 'aktif', NULL);

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

-- --------------------------------------------------------

--
-- Table structure for table `m_kategori`
--

CREATE TABLE `m_kategori` (
  `id_kategori` tinyint UNSIGNED NOT NULL,
  `nama_kateg` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_kategori`
--

INSERT INTO `m_kategori` (`id_kategori`, `nama_kateg`, `foto`) VALUES
(1, 'Peliharaan', 'https://images.unsplash.com/photo-1623387641168-d9803ddd3f35?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=1170\''),
(2, 'Ternak', 'https://images.unsplash.com/photo-1762330468228-ccef22e1d651?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=1170\''),
(3, 'Eksotis', 'https://images.unsplash.com/photo-1758699211694-582e2817e5d4?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTJ8fGN1dGUlMjBleG90aWMlMjBhbmltYWxzfGVufDB8fDB8fHww&auto=format&fit=crop&q=60&w=600\''),
(4, 'Akuatik', 'https://plus.unsplash.com/premium_photo-1759353494873-56fc92f72979?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=687\''),
(5, 'Hewan Kecil', 'https://images.unsplash.com/photo-1761212129559-b731072924c9?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=1170\''),
(6, 'Unggas', 'https://images.unsplash.com/photo-1716560410803-dcd1f81c7ab1?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=1074\'');

-- --------------------------------------------------------

--
-- Table structure for table `m_lokasipraktik`
--

CREATE TABLE `m_lokasipraktik` (
  `dokter` int DEFAULT NULL,
  `nama_klinik` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `lat` decimal(10,8) NOT NULL DEFAULT (0),
  `long` decimal(11,8) NOT NULL DEFAULT (0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(27, 'akunbaru@ak.com', '$argon2id$v=19$m=65536,t=4,p=1$UmY3TGxMNDhUZWY2a1VsaA$VPXcWLz9mnnULNMMOiJUHEHoUdgStErPA9ovq2p2JK0', 'Dokter', '2025-11-15 05:53:33', NULL, NULL),
(28, 'ohiyakah@mail.co', '$argon2id$v=19$m=65536,t=4,p=1$YWs3V1VUVHVVMW1zeTAxRQ$5ZExeTBmGfDPVF/MEPSI8MAISXcDqHUGiUedyNX5HTs', 'Member', '2025-11-15 06:06:25', NULL, NULL),
(29, 'user@user.com', '$argon2id$v=19$m=65536,t=4,p=1$WWF1am9xWjFkS0hlSi5OUQ$oT1jN7/1rLWYiqQf9F+aYm/6vewnQYD0Hz8hWfTj5eo', 'Member', '2025-11-17 11:37:31', NULL, NULL);

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
(6, 29, 'user@user.com', 'nyoba ya, ini buat tes aja sih sebenernya', 'kok bisa ya, habis digituin malah menjadi kyak gini', '2025-11-17 15:52:56', 'menunggu', 28);

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
-- Indexes for table `log_rating`
--
ALTER TABLE `log_rating`
  ADD KEY `FK-forchat` (`idChat`),
  ADD KEY `fk-chatuser` (`id_pengguna`),
  ADD KEY `fk-chatdokter` (`id_dokter`);

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
  MODIFY `id_pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `m_tag`
--
ALTER TABLE `m_tag`
  MODIFY `idTag` tinyint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tr_tanya`
--
ALTER TABLE `tr_tanya`
  MODIFY `id_tanya` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- Constraints for table `log_rating`
--
ALTER TABLE `log_rating`
  ADD CONSTRAINT `fk-chatdokter` FOREIGN KEY (`id_dokter`) REFERENCES `m_dokter` (`id_dokter`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-chatuser` FOREIGN KEY (`id_pengguna`) REFERENCES `m_pengguna` (`id_pengguna`) ON DELETE SET NULL ON UPDATE CASCADE,
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

-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versi server:                 8.4.3 - MySQL Community Server - GPL
-- OS Server:                    Win64
-- HeidiSQL Versi:               12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Membuang struktur basisdata untuk klinikh
CREATE DATABASE IF NOT EXISTS `klinikh` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `klinikh`;

-- membuang struktur untuk table klinikh.detail_dokter
DROP TABLE IF EXISTS `detail_dokter`;
CREATE TABLE IF NOT EXISTS `detail_dokter` (
  `id_dokter` int DEFAULT NULL,
  `id_kategori` tinyint unsigned DEFAULT NULL,
  KEY `FK_dDokter` (`id_dokter`),
  KEY `FK_dKateg` (`id_kategori`),
  CONSTRAINT `FK_dDokter` FOREIGN KEY (`id_dokter`) REFERENCES `m_dokter` (`id_dokter`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `FK_dKateg` FOREIGN KEY (`id_kategori`) REFERENCES `m_kategori` (`id_kategori`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Membuang data untuk tabel klinikh.detail_dokter: ~3 rows (lebih kurang)
INSERT INTO `detail_dokter` (`id_dokter`, `id_kategori`) VALUES
	(25, 3),
	(1, 1),
	(25, 1);

-- membuang struktur untuk table klinikh.jwb_dokter
DROP TABLE IF EXISTS `jwb_dokter`;
CREATE TABLE IF NOT EXISTS `jwb_dokter` (
  `id_dokter` int NOT NULL,
  `id_tanya` int DEFAULT NULL,
  `nama_dokter` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `publish` timestamp NULL DEFAULT (now()),
  `update` timestamp NULL DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP,
  KEY `FK1_jwban` (`id_dokter`),
  KEY `FK2_artanya` (`id_tanya`),
  CONSTRAINT `FK1_jwban` FOREIGN KEY (`id_dokter`) REFERENCES `m_dokter` (`id_dokter`),
  CONSTRAINT `FK2_artanya` FOREIGN KEY (`id_tanya`) REFERENCES `tr_tanya` (`id_tanya`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Membuang data untuk tabel klinikh.jwb_dokter: ~0 rows (lebih kurang)

-- membuang struktur untuk table klinikh.log_location
DROP TABLE IF EXISTS `log_location`;
CREATE TABLE IF NOT EXISTS `log_location` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `koor` varchar(35) COLLATE utf8mb4_general_ci DEFAULT '0',
  `kabupaten` varchar(30) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `provinsi` varchar(30) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT (now()),
  PRIMARY KEY (`id`),
  KEY `FK-locateUser` (`id_user`),
  CONSTRAINT `FK-locateUser` FOREIGN KEY (`id_user`) REFERENCES `m_pengguna` (`id_pengguna`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Membuang data untuk tabel klinikh.log_location: ~0 rows (lebih kurang)

-- membuang struktur untuk table klinikh.log_rating
DROP TABLE IF EXISTS `log_rating`;
CREATE TABLE IF NOT EXISTS `log_rating` (
  `idChat` char(19) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `liked?` tinyint(1) NOT NULL DEFAULT '1',
  `end` timestamp NULL DEFAULT NULL,
  KEY `FK-forchat` (`idChat`),
  CONSTRAINT `FK-forchat` FOREIGN KEY (`idChat`) REFERENCES `tr_transaksi` (`id_tr`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Membuang data untuk tabel klinikh.log_rating: ~0 rows (lebih kurang)

-- membuang struktur untuk table klinikh.m_artikel
DROP TABLE IF EXISTS `m_artikel`;
CREATE TABLE IF NOT EXISTS `m_artikel` (
  `id_artikel` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `preview` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `isi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `referensi` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `author` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `author_id` int DEFAULT (0),
  `published` timestamp NOT NULL DEFAULT (now()),
  `views` int NOT NULL DEFAULT (0),
  `updated` timestamp NOT NULL DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP,
  `tag` tinyint unsigned DEFAULT NULL,
  PRIMARY KEY (`id_artikel`),
  KEY `FK_author` (`author_id`),
  KEY `FK_Tag` (`tag`),
  CONSTRAINT `FK_author` FOREIGN KEY (`author_id`) REFERENCES `m_dokter` (`id_dokter`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `FK_Tag` FOREIGN KEY (`tag`) REFERENCES `m_tag` (`idTag`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Membuang data untuk tabel klinikh.m_artikel: ~30 rows (lebih kurang)
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

-- membuang struktur untuk table klinikh.m_doc_dokter
DROP TABLE IF EXISTS `m_doc_dokter`;
CREATE TABLE IF NOT EXISTS `m_doc_dokter` (
  `id_dokter` int DEFAULT NULL,
  `path_sip` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `path_strv` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  KEY `FK_doc-dokter` (`id_dokter`),
  CONSTRAINT `FK_doc-dokter` FOREIGN KEY (`id_dokter`) REFERENCES `m_dokter` (`id_dokter`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Membuang data untuk tabel klinikh.m_doc_dokter: ~0 rows (lebih kurang)

-- membuang struktur untuk table klinikh.m_dokter
DROP TABLE IF EXISTS `m_dokter`;
CREATE TABLE IF NOT EXISTS `m_dokter` (
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
  `harga` mediumint unsigned DEFAULT NULL,
  `kabupaten` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `provinsi` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_dokter`),
  UNIQUE KEY `STRV` (`strv`) USING BTREE,
  UNIQUE KEY `SIP` (`sip`) USING BTREE,
  KEY `FK_idDokter` (`id_dokter`),
  CONSTRAINT `FK_idDokter` FOREIGN KEY (`id_dokter`) REFERENCES `m_pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Membuang data untuk tabel klinikh.m_dokter: ~2 rows (lebih kurang)
INSERT INTO `m_dokter` (`id_dokter`, `nama_dokter`, `ttl`, `strv`, `exp_strv`, `sip`, `exp_sip`, `foto`, `pengalaman`, `rate`, `status`, `harga`, `kabupaten`, `provinsi`) VALUES
	(1, 'Slamet', '1995-10-09', '79snkdua', '2025-10-09', 'ra4456112', '2031-10-09', NULL, '2002', 1.00, 'aktif', NULL, NULL, NULL),
	(25, 'hai', '1999-11-15', '67839jd', '2028-11-15', '6278ejk', '2030-11-15', NULL, '1970', 1.00, 'aktif', 20000, 'Jogja', 'Jogja');

-- membuang struktur untuk table klinikh.m_hpraktik
DROP TABLE IF EXISTS `m_hpraktik`;
CREATE TABLE IF NOT EXISTS `m_hpraktik` (
  `id_dokter` int NOT NULL DEFAULT (0),
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Minggu',
  `buka` time DEFAULT NULL,
  `tutup` time DEFAULT NULL,
  KEY `FK_dokter` (`id_dokter`),
  CONSTRAINT `FK_dokter` FOREIGN KEY (`id_dokter`) REFERENCES `m_dokter` (`id_dokter`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Membuang data untuk tabel klinikh.m_hpraktik: ~0 rows (lebih kurang)

-- membuang struktur untuk table klinikh.m_kategori
DROP TABLE IF EXISTS `m_kategori`;
CREATE TABLE IF NOT EXISTS `m_kategori` (
  `id_kategori` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kateg` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Membuang data untuk tabel klinikh.m_kategori: ~6 rows (lebih kurang)
INSERT INTO `m_kategori` (`id_kategori`, `nama_kateg`) VALUES
	(1, 'Peliharaan'),
	(2, 'Ternak'),
	(3, 'Eksotis'),
	(4, 'Akuatik'),
	(5, 'Hewan Kecil'),
	(6, 'Unggas');

-- membuang struktur untuk table klinikh.m_lokasipraktik
DROP TABLE IF EXISTS `m_lokasipraktik`;
CREATE TABLE IF NOT EXISTS `m_lokasipraktik` (
  `dokter` int DEFAULT NULL,
  `nama_klinik` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lat` decimal(10,8) NOT NULL DEFAULT (0),
  `long` decimal(11,8) NOT NULL DEFAULT (0),
  KEY `FK_locdok` (`dokter`),
  CONSTRAINT `FK_locdok` FOREIGN KEY (`dokter`) REFERENCES `m_dokter` (`id_dokter`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Membuang data untuk tabel klinikh.m_lokasipraktik: ~0 rows (lebih kurang)

-- membuang struktur untuk table klinikh.m_pengguna
DROP TABLE IF EXISTS `m_pengguna`;
CREATE TABLE IF NOT EXISTS `m_pengguna` (
  `id_pengguna` int NOT NULL AUTO_INCREMENT,
  `email` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pass` char(97) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('Admin','Dokter','Member') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created` timestamp NULL DEFAULT (now()),
  `reset_token` char(6) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `exp_token` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pengguna`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Membuang data untuk tabel klinikh.m_pengguna: ~7 rows (lebih kurang)
INSERT INTO `m_pengguna` (`id_pengguna`, `email`, `pass`, `role`, `created`, `reset_token`, `exp_token`) VALUES
	(1, 'o@o.mai.com', '12345', 'Dokter', '2025-10-08 17:00:00', NULL, '2025-10-19 10:17:52'),
	(2, 'anu@mail.com', '12345', 'Member', '2025-10-09 12:45:37', NULL, '2025-10-19 10:17:52'),
	(3, 'Tes@mail.com', '12345', 'Admin', '2025-10-25 13:33:20', NULL, NULL),
	(25, 'akunbbersama@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$UjNRLlhBYkRPNGlDUXltdA$3dusYKj8nzEfYwSWSZ/bSKaImdBsrX0dBRvDxsK3tRs', 'Dokter', '2025-11-15 04:43:13', NULL, NULL),
	(28, 'ohiyakah@mail.co', '$argon2id$v=19$m=65536,t=4,p=1$YWs3V1VUVHVVMW1zeTAxRQ$5ZExeTBmGfDPVF/MEPSI8MAISXcDqHUGiUedyNX5HTs', 'Member', '2025-11-15 06:06:25', NULL, NULL),
	(29, 'user@user.com', '$argon2id$v=19$m=65536,t=4,p=1$WWF1am9xWjFkS0hlSi5OUQ$oT1jN7/1rLWYiqQf9F+aYm/6vewnQYD0Hz8hWfTj5eo', 'Member', '2025-11-17 11:37:31', NULL, NULL);

-- membuang struktur untuk table klinikh.m_tag
DROP TABLE IF EXISTS `m_tag`;
CREATE TABLE IF NOT EXISTS `m_tag` (
  `idTag` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`idTag`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Membuang data untuk tabel klinikh.m_tag: ~15 rows (lebih kurang)
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

-- membuang struktur untuk table klinikh.tr_tanya
DROP TABLE IF EXISTS `tr_tanya`;
CREATE TABLE IF NOT EXISTS `tr_tanya` (
  `id_tanya` int NOT NULL AUTO_INCREMENT,
  `id_penanya` int DEFAULT NULL,
  `penanya` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `judul` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `pertanyaan` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `dibuat` timestamp NOT NULL DEFAULT (now()),
  `status` enum('terjawab','menunggu') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'menunggu',
  `idTag` tinyint unsigned DEFAULT (0),
  PRIMARY KEY (`id_tanya`),
  KEY `FK_penanya` (`id_penanya`),
  KEY `FK_tanyatag` (`idTag`),
  CONSTRAINT `FK_penanya` FOREIGN KEY (`id_penanya`) REFERENCES `m_pengguna` (`id_pengguna`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `FK_tanyatag` FOREIGN KEY (`idTag`) REFERENCES `m_tag` (`idTag`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Membuang data untuk tabel klinikh.tr_tanya: ~6 rows (lebih kurang)
INSERT INTO `tr_tanya` (`id_tanya`, `id_penanya`, `penanya`, `judul`, `pertanyaan`, `dibuat`, `status`, `idTag`) VALUES
	(1, 29, 'user@user.com', 'nyoba ya, ini buat tes aja sih sebenernya', 'kok bisa ya, habis digituin malah menjadi kyak gini', '2025-11-17 15:49:58', 'menunggu', NULL),
	(2, 29, 'user@user.com', 'nyoba ya, ini buat tes aja sih sebenernya', 'kok bisa ya, habis digituin malah menjadi kyak gini', '2025-11-17 15:51:12', 'menunggu', 16),
	(3, 29, 'user@user.com', 'nyoba ya, ini buat tes aja sih sebenernya', 'kok bisa ya, habis digituin malah menjadi kyak gini', '2025-11-17 15:51:40', 'menunggu', 21),
	(4, 29, 'user@user.com', 'nyoba ya, ini buat tes aja sih sebenernya', 'kok bisa ya, habis digituin malah menjadi kyak gini', '2025-11-17 15:52:12', 'menunggu', 27),
	(5, 29, 'user@user.com', 'nyoba ya, ini buat tes aja sih sebenernya', 'kok bisa ya, habis digituin malah menjadi kyak gini', '2025-11-17 15:52:39', 'menunggu', 18),
	(6, 29, 'user@user.com', 'nyoba ya, ini buat tes aja sih sebenernya', 'kok bisa ya, habis digituin malah menjadi kyak gini', '2025-11-17 15:52:56', 'menunggu', 28);

-- membuang struktur untuk table klinikh.tr_transaksi
DROP TABLE IF EXISTS `tr_transaksi`;
CREATE TABLE IF NOT EXISTS `tr_transaksi` (
  `id_tr` char(19) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'AUTO_INCREMENT',
  `eksternal_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int DEFAULT (0),
  `dokter_id` int DEFAULT (0),
  `created` timestamp NOT NULL DEFAULT (now()),
  `status` enum('pending','success','expired','failed') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_tr`) USING BTREE,
  UNIQUE KEY `order_id` (`eksternal_id`) USING BTREE,
  KEY `FK_trUser` (`user_id`),
  KEY `FK2_tr-dokter` (`dokter_id`),
  CONSTRAINT `FK2_tr-dokter` FOREIGN KEY (`dokter_id`) REFERENCES `m_dokter` (`id_dokter`) ON DELETE SET NULL,
  CONSTRAINT `FK_trUser` FOREIGN KEY (`user_id`) REFERENCES `m_pengguna` (`id_pengguna`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Membuang data untuk tabel klinikh.tr_transaksi: ~0 rows (lebih kurang)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

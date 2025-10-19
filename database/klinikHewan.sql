-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versi server:                 8.4.3 - MySQL Community Server - GPL
-- OS Server:                    Win64
-- HeidiSQL Versi:               12.10.0.7000
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
CREATE TABLE IF NOT EXISTS `detail_dokter` (
  `id_dokter` int DEFAULT NULL,
  `id_kategori` tinyint DEFAULT NULL,
  KEY `FK_kategdokter` (`id_dokter`),
  KEY `FK_kategori` (`id_kategori`),
  CONSTRAINT `FK_kategdokter` FOREIGN KEY (`id_dokter`) REFERENCES `m_dokter` (`id_dokter`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `m_kategori` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table klinikh.detail_tanya
CREATE TABLE IF NOT EXISTS `detail_tanya` (
  `tr_tanya` int DEFAULT NULL,
  `kategori` tinyint DEFAULT NULL,
  KEY `FK_pnanya` (`tr_tanya`),
  KEY `FK_kategtanya` (`kategori`),
  CONSTRAINT `FK_kategtanya` FOREIGN KEY (`kategori`) REFERENCES `m_kategori` (`id_kategori`),
  CONSTRAINT `FK_pnanya` FOREIGN KEY (`tr_tanya`) REFERENCES `tr_tanya` (`id_tanya`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table klinikh.jwb_dokter
CREATE TABLE IF NOT EXISTS `jwb_dokter` (
  `id_dokter` int NOT NULL,
  `id_tanya` int DEFAULT NULL,
  `nama_dokter` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isi` text COLLATE utf8mb4_general_ci,
  `publish` timestamp NULL DEFAULT (now()),
  `update` timestamp NULL DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP,
  KEY `FK1_jwban` (`id_dokter`),
  KEY `FK2_artanya` (`id_tanya`),
  CONSTRAINT `FK1_jwban` FOREIGN KEY (`id_dokter`) REFERENCES `m_dokter` (`id_dokter`),
  CONSTRAINT `FK2_artanya` FOREIGN KEY (`id_tanya`) REFERENCES `tr_tanya` (`id_tanya`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table klinikh.m_artikel
CREATE TABLE IF NOT EXISTS `m_artikel` (
  `id_artikel` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `isi` text COLLATE utf8mb4_general_ci NOT NULL,
  `author` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `author_id` int DEFAULT (0),
  `published` timestamp NOT NULL DEFAULT (now()),
  `views` int NOT NULL DEFAULT (0),
  `updated` timestamp NOT NULL DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_artikel`),
  KEY `FK_author` (`author_id`),
  CONSTRAINT `FK_author` FOREIGN KEY (`author_id`) REFERENCES `m_dokter` (`id_dokter`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table klinikh.m_dokter
CREATE TABLE IF NOT EXISTS `m_dokter` (
  `id_dokter` int NOT NULL AUTO_INCREMENT,
  `nama_dokter` varchar(70) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `ttl` date NOT NULL DEFAULT (curdate()),
  `no_wa` char(12) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `STRV` char(21) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `exp_strv` date NOT NULL DEFAULT (curdate()),
  `SIP` varchar(30) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `exp_SIP` date NOT NULL DEFAULT (curdate()),
  `foto` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pengalaman` tinyint NOT NULL DEFAULT '2',
  `terdaftar` date NOT NULL DEFAULT (curdate()),
  PRIMARY KEY (`id_dokter`),
  UNIQUE KEY `STRV` (`STRV`),
  UNIQUE KEY `SIP` (`SIP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table klinikh.m_hpraktik
CREATE TABLE IF NOT EXISTS `m_hpraktik` (
  `id_dokter` int NOT NULL DEFAULT (0),
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Minggu',
  `buka` time DEFAULT NULL,
  `tutup` time DEFAULT NULL,
  KEY `FK_dokter` (`id_dokter`),
  CONSTRAINT `FK_dokter` FOREIGN KEY (`id_dokter`) REFERENCES `m_dokter` (`id_dokter`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table klinikh.m_kategori
CREATE TABLE IF NOT EXISTS `m_kategori` (
  `id_kategori` tinyint NOT NULL AUTO_INCREMENT,
  `nama_kateg` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table klinikh.m_lokasipraktik
CREATE TABLE IF NOT EXISTS `m_lokasipraktik` (
  `dokter` int DEFAULT NULL,
  `nama_klinik` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `lat` decimal(10,8) NOT NULL DEFAULT (0),
  `long` decimal(11,8) NOT NULL DEFAULT (0),
  KEY `FK_locdok` (`dokter`),
  CONSTRAINT `FK_locdok` FOREIGN KEY (`dokter`) REFERENCES `m_dokter` (`id_dokter`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table klinikh.m_pengguna
CREATE TABLE IF NOT EXISTS `m_pengguna` (
  `id_pengguna` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(75) COLLATE utf8mb4_general_ci NOT NULL,
  `pass` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `no_wa` char(12) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_pengguna`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Pengeluaran data tidak dipilih.

-- membuang struktur untuk table klinikh.tr_tanya
CREATE TABLE IF NOT EXISTS `tr_tanya` (
  `id_tanya` int NOT NULL AUTO_INCREMENT,
  `id_penanya` int DEFAULT NULL,
  `penanya` varchar(30) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `judul` varchar(150) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `pertanyaan` varchar(1000) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `dibuat` timestamp NOT NULL DEFAULT (now()),
  `status` enum('terjawab','menunggu') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'menunggu',
  `privasi` enum('PUBLIK','PRIVASI') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'PUBLIK',
  PRIMARY KEY (`id_tanya`),
  KEY `FK_penanya` (`id_penanya`),
  CONSTRAINT `FK_penanya` FOREIGN KEY (`id_penanya`) REFERENCES `m_pengguna` (`id_pengguna`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Pengeluaran data tidak dipilih.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versi server:                 10.1.37-MariaDB - mariadb.org binary distribution
-- OS Server:                    Win32
-- HeidiSQL Versi:               9.1.0.4908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table lj_elibrary.elib_anggota
CREATE TABLE IF NOT EXISTS `elib_anggota` (
  `id_anggota` int(11) NOT NULL AUTO_INCREMENT,
  `no_kartu` varchar(25) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `tempat_lahir` varchar(70) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `kel` enum('L','P') DEFAULT 'L',
  `alamat` varchar(200) DEFAULT NULL,
  `telp` varchar(50) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `foto` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_anggota`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table lj_elibrary.elib_anggota: ~2 rows (approximately)
DELETE FROM `elib_anggota`;
/*!40000 ALTER TABLE `elib_anggota` DISABLE KEYS */;
INSERT INTO `elib_anggota` (`id_anggota`, `no_kartu`, `nama`, `tempat_lahir`, `tgl_lahir`, `kel`, `alamat`, `telp`, `email`, `foto`, `created_at`, `updated_at`) VALUES
	(1, 'LiJy-2020-0001', 'Muhardi', 'Malongka', '1985-06-13', 'L', 'Dusun Ballapati, Moncongloe Lappara, Maros', '081297503009', '123hardi@gmail.com', 'foto muhardi.jpg', '2020-06-28 03:44:03', '2020-06-28 03:44:03'),
	(2, 'LiJy-2020-0002', 'Tuti Melianti', 'Malongka', '1988-01-07', 'P', 'Dusun Ballapati, Moncongloe Lappara, Maros', '081297503008', 'meliantituti2020@gmail.com', 'img_20151201_111156.jpg', '2020-06-28 03:45:41', '2020-06-28 03:45:41');
/*!40000 ALTER TABLE `elib_anggota` ENABLE KEYS */;


-- Dumping structure for table lj_elibrary.elib_banner
CREATE TABLE IF NOT EXISTS `elib_banner` (
  `id_banner` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(100) DEFAULT NULL,
  `status` enum('Y','N') DEFAULT NULL,
  `gambar` varchar(300) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_banner`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table lj_elibrary.elib_banner: ~0 rows (approximately)
DELETE FROM `elib_banner`;
/*!40000 ALTER TABLE `elib_banner` DISABLE KEYS */;
INSERT INTO `elib_banner` (`id_banner`, `judul`, `status`, `gambar`, `created_at`, `updated_at`) VALUES
	(1, 'Selamat Datang', 'Y', 'banner_01.jpg', '2020-07-12 05:32:56', '2020-07-12 05:32:56'),
	(2, 'Mari Membaca', 'Y', 'banner_02.jpg', '2020-07-12 05:33:17', '2020-07-12 05:33:17');
/*!40000 ALTER TABLE `elib_banner` ENABLE KEYS */;


-- Dumping structure for table lj_elibrary.elib_buku
CREATE TABLE IF NOT EXISTS `elib_buku` (
  `id_buku` int(11) NOT NULL AUTO_INCREMENT,
  `isbn` varchar(35) DEFAULT NULL,
  `judul` varchar(250) DEFAULT NULL,
  `penerbit` varchar(100) DEFAULT NULL,
  `tempat_terbit` varchar(100) DEFAULT NULL,
  `tahun_terbit` varchar(10) DEFAULT NULL,
  `pengarang` varchar(75) DEFAULT NULL,
  `jenis` int(11) DEFAULT NULL,
  `jumlah_hal` int(11) DEFAULT NULL,
  `kategori` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `cover` varchar(125) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_buku`),
  UNIQUE KEY `judul` (`judul`),
  KEY `jenis` (`jenis`),
  KEY `kategori` (`kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table lj_elibrary.elib_buku: ~2 rows (approximately)
DELETE FROM `elib_buku`;
/*!40000 ALTER TABLE `elib_buku` DISABLE KEYS */;
INSERT INTO `elib_buku` (`id_buku`, `isbn`, `judul`, `penerbit`, `tempat_terbit`, `tahun_terbit`, `pengarang`, `jenis`, `jumlah_hal`, `kategori`, `stok`, `cover`, `created_at`, `updated_at`) VALUES
	(1, '9795339338', 'Panduan Lengkap Macromedia Flash MX', 'ANDI', 'Yogyakarta', '2003', 'Dhani Yudhiantoro', 9, 286, 2, 1, 'hub.jpg', '2020-06-27 02:24:37', '2020-07-05 06:14:42'),
	(2, '9789792943351', 'Ragam Aplikasi Android untuk UKM', 'ANDI dan WAHANA KOMPUTER', 'Yogyakarta', '2014', 'Seno Wibowo', 9, 202, 2, 1, 'coaxial.jpg', '2020-06-27 02:27:55', '2020-07-09 12:19:49');
/*!40000 ALTER TABLE `elib_buku` ENABLE KEYS */;


-- Dumping structure for table lj_elibrary.elib_buku_hilang
CREATE TABLE IF NOT EXISTS `elib_buku_hilang` (
  `id_hilang` int(11) NOT NULL AUTO_INCREMENT,
  `id_buku` int(11) DEFAULT '0',
  `tanggal` date DEFAULT NULL,
  `jenis` enum('HILANG','RUSAK') DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `catatan` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_hilang`),
  KEY `id_buku` (`id_buku`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table lj_elibrary.elib_buku_hilang: ~1 rows (approximately)
DELETE FROM `elib_buku_hilang`;
/*!40000 ALTER TABLE `elib_buku_hilang` DISABLE KEYS */;
INSERT INTO `elib_buku_hilang` (`id_hilang`, `id_buku`, `tanggal`, `jenis`, `jumlah`, `catatan`) VALUES
	(1, 1, '2020-07-02', 'HILANG', 1, 'Buku tercecer saat dipinjam oleh anggota');
/*!40000 ALTER TABLE `elib_buku_hilang` ENABLE KEYS */;


-- Dumping structure for table lj_elibrary.elib_ebook
CREATE TABLE IF NOT EXISTS `elib_ebook` (
  `id_ebook` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(250) DEFAULT NULL,
  `penulis` varchar(150) DEFAULT NULL,
  `tahun_keluar` varchar(5) DEFAULT NULL,
  `jenis` int(11) DEFAULT NULL,
  `kategori` int(11) DEFAULT NULL,
  `file_ebook` varchar(250) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ebook`),
  UNIQUE KEY `judul` (`judul`),
  KEY `jenis` (`jenis`),
  KEY `kategori` (`kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table lj_elibrary.elib_ebook: ~2 rows (approximately)
DELETE FROM `elib_ebook`;
/*!40000 ALTER TABLE `elib_ebook` DISABLE KEYS */;
INSERT INTO `elib_ebook` (`id_ebook`, `judul`, `penulis`, `tahun_keluar`, `jenis`, `kategori`, `file_ebook`, `created_at`, `updated_at`) VALUES
	(1, 'Modul Praktikum Jaringan Komputer', 'Tim Dosen UIN Sunan Kalijaga Yogyakarta', '2011', 9, 3, 'moduljarkom1-14.pdf', '2020-06-28 02:56:28', '2020-06-28 02:56:28'),
	(2, 'Modul Keamanan Jaringan Komputer', 'Jagoanilmu.com', '2010', 11, 2, 'modul1-150830032745-lva1-app6891.pdf', '2020-06-28 02:57:54', '2020-06-28 02:57:54');
/*!40000 ALTER TABLE `elib_ebook` ENABLE KEYS */;


-- Dumping structure for table lj_elibrary.elib_harga
CREATE TABLE IF NOT EXISTS `elib_harga` (
  `id_harga` int(11) NOT NULL AUTO_INCREMENT,
  `id_buku` int(11) DEFAULT '0',
  `durasi_pinjam` int(11) DEFAULT '0',
  `harga_denda` double DEFAULT '0',
  `denda_hilang` double DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_harga`),
  KEY `id_buku` (`id_buku`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table lj_elibrary.elib_harga: ~2 rows (approximately)
DELETE FROM `elib_harga`;
/*!40000 ALTER TABLE `elib_harga` DISABLE KEYS */;
INSERT INTO `elib_harga` (`id_harga`, `id_buku`, `durasi_pinjam`, `harga_denda`, `denda_hilang`, `created_at`, `updated_at`) VALUES
	(1, 1, 7, 2500, 65000, '2020-07-03 06:16:21', '2020-07-03 06:17:46'),
	(2, 2, 7, 2500, 125000, '2020-07-03 06:16:41', '2020-07-03 06:17:54');
/*!40000 ALTER TABLE `elib_harga` ENABLE KEYS */;


-- Dumping structure for table lj_elibrary.elib_identitas
CREATE TABLE IF NOT EXISTS `elib_identitas` (
  `id_identitas` int(11) NOT NULL AUTO_INCREMENT,
  `nama_perpustakaan` varchar(100) DEFAULT NULL,
  `alamat` varchar(150) DEFAULT NULL,
  `telp` varchar(50) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `logo` varchar(200) DEFAULT NULL,
  `kodepos` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_identitas`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table lj_elibrary.elib_identitas: ~1 rows (approximately)
DELETE FROM `elib_identitas`;
/*!40000 ALTER TABLE `elib_identitas` DISABLE KEYS */;
INSERT INTO `elib_identitas` (`id_identitas`, `nama_perpustakaan`, `alamat`, `telp`, `fax`, `email`, `logo`, `kodepos`, `created_at`, `updated_at`) VALUES
	(1, 'Perpustakaan Suruh Cerdas', 'Jl. Perintis Kemerdekaan Km.9', '-', '-', 'perussuruhcerdas@gmail.com', 'logo perpus.jpg', '90245', '2020-07-08 12:59:46', '2020-07-08 12:59:46');
/*!40000 ALTER TABLE `elib_identitas` ENABLE KEYS */;


-- Dumping structure for table lj_elibrary.elib_jenis_buku
CREATE TABLE IF NOT EXISTS `elib_jenis_buku` (
  `id_jenis` int(11) NOT NULL AUTO_INCREMENT,
  `nama_jenis` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jenis`),
  UNIQUE KEY `nama_jenis` (`nama_jenis`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- Dumping data for table lj_elibrary.elib_jenis_buku: ~12 rows (approximately)
DELETE FROM `elib_jenis_buku`;
/*!40000 ALTER TABLE `elib_jenis_buku` DISABLE KEYS */;
INSERT INTO `elib_jenis_buku` (`id_jenis`, `nama_jenis`, `created_at`, `updated_at`) VALUES
	(1, 'Novel', '2020-06-26 02:32:18', '2020-06-26 02:32:18'),
	(2, 'Cergam', '2020-06-26 02:35:36', '2020-06-26 02:35:36'),
	(3, 'Komik', '2020-06-26 02:35:43', '2020-06-26 02:35:43'),
	(4, 'Ensiklopedia', '2020-06-26 02:36:15', '2020-06-26 02:36:15'),
	(5, 'Nomik', '2020-06-26 02:36:21', '2020-06-26 02:36:21'),
	(6, 'Dongeng', '2020-06-26 02:36:32', '2020-06-26 02:36:32'),
	(7, 'Biografi', '2020-06-26 02:36:48', '2020-06-26 02:36:48'),
	(8, 'Catatan Harian', '2020-06-26 02:36:56', '2020-06-26 02:36:56'),
	(9, 'Karya Ilmiah', '2020-06-26 02:37:12', '2020-06-26 02:37:12'),
	(10, 'Kamus', '2020-06-26 02:37:17', '2020-06-26 02:37:17'),
	(11, 'Tafsir', '2020-06-26 02:37:23', '2020-06-26 02:37:23'),
	(12, 'Atlas', '2020-06-26 02:37:37', '2020-06-26 02:37:37');
/*!40000 ALTER TABLE `elib_jenis_buku` ENABLE KEYS */;


-- Dumping structure for table lj_elibrary.elib_kategori
CREATE TABLE IF NOT EXISTS `elib_kategori` (
  `id_kategori` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kategori`),
  UNIQUE KEY `nama_kategori` (`nama_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table lj_elibrary.elib_kategori: ~4 rows (approximately)
DELETE FROM `elib_kategori`;
/*!40000 ALTER TABLE `elib_kategori` DISABLE KEYS */;
INSERT INTO `elib_kategori` (`id_kategori`, `nama_kategori`, `created_at`, `updated_at`) VALUES
	(1, 'Agama', '2020-06-26 02:54:07', '2020-06-26 02:54:07'),
	(2, 'Komputer', '2020-06-26 02:54:20', '2020-06-26 02:54:20'),
	(3, 'Bahan Ajar', '2020-06-26 02:54:32', '2020-06-26 02:54:32'),
	(4, 'Bacaan', '2020-06-26 02:54:38', '2020-06-26 02:54:38');
/*!40000 ALTER TABLE `elib_kategori` ENABLE KEYS */;


-- Dumping structure for table lj_elibrary.elib_lokasi_buku
CREATE TABLE IF NOT EXISTS `elib_lokasi_buku` (
  `id_lokasi` int(11) NOT NULL AUTO_INCREMENT,
  `id_buku` int(11) DEFAULT NULL,
  `id_rak` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_lokasi`),
  KEY `id_buku` (`id_buku`),
  KEY `id_rak` (`id_rak`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table lj_elibrary.elib_lokasi_buku: ~2 rows (approximately)
DELETE FROM `elib_lokasi_buku`;
/*!40000 ALTER TABLE `elib_lokasi_buku` DISABLE KEYS */;
INSERT INTO `elib_lokasi_buku` (`id_lokasi`, `id_buku`, `id_rak`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, '2020-06-27 09:05:30', '2020-06-27 09:05:30'),
	(2, 2, 1, '2020-06-27 09:08:05', '2020-06-27 09:08:05');
/*!40000 ALTER TABLE `elib_lokasi_buku` ENABLE KEYS */;


-- Dumping structure for table lj_elibrary.elib_peminjaman
CREATE TABLE IF NOT EXISTS `elib_peminjaman` (
  `id_pinjam` int(11) NOT NULL AUTO_INCREMENT,
  `id_buku` int(11) DEFAULT '0',
  `id_anggota` int(11) DEFAULT '0',
  `tgl_pinjam` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `hari` varchar(15) DEFAULT '0',
  `jumlah` int(11) DEFAULT '0',
  `durasi` int(11) DEFAULT '0',
  `status` enum('BELUM KEMBALI','KEMBALI') DEFAULT 'BELUM KEMBALI',
  `catatan` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_pinjam`),
  KEY `id_buku` (`id_buku`),
  KEY `id_anggota` (`id_anggota`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table lj_elibrary.elib_peminjaman: ~3 rows (approximately)
DELETE FROM `elib_peminjaman`;
/*!40000 ALTER TABLE `elib_peminjaman` DISABLE KEYS */;
INSERT INTO `elib_peminjaman` (`id_pinjam`, `id_buku`, `id_anggota`, `tgl_pinjam`, `hari`, `jumlah`, `durasi`, `status`, `catatan`) VALUES
	(1, 1, 1, '2020-07-03 06:32:16', 'Kamis', 1, 7, 'KEMBALI', 'Untuk menghindari denda kembalikan tepat waktu'),
	(2, 2, 1, '2020-07-08 14:05:41', 'Rabu', 1, 7, 'KEMBALI', 'ssss'),
	(3, 2, 1, '2020-07-09 12:19:49', 'Kamis', 1, 7, 'BELUM KEMBALI', 'tidak ada catatan');
/*!40000 ALTER TABLE `elib_peminjaman` ENABLE KEYS */;


-- Dumping structure for table lj_elibrary.elib_pengembalian
CREATE TABLE IF NOT EXISTS `elib_pengembalian` (
  `id_kembali` int(11) NOT NULL AUTO_INCREMENT,
  `id_pinjam` int(11) DEFAULT NULL,
  `id_anggota` int(11) DEFAULT NULL,
  `id_buku` int(11) DEFAULT NULL,
  `tgl_kembali` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lama_pinjam` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `lewat_hari` int(11) DEFAULT NULL,
  `denda` double DEFAULT NULL,
  `catatan` varchar(350) DEFAULT NULL,
  PRIMARY KEY (`id_kembali`),
  KEY `id_pinjam` (`id_pinjam`),
  KEY `id_anggota` (`id_anggota`),
  KEY `id_buku` (`id_buku`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table lj_elibrary.elib_pengembalian: ~2 rows (approximately)
DELETE FROM `elib_pengembalian`;
/*!40000 ALTER TABLE `elib_pengembalian` DISABLE KEYS */;
INSERT INTO `elib_pengembalian` (`id_kembali`, `id_pinjam`, `id_anggota`, `id_buku`, `tgl_kembali`, `lama_pinjam`, `jumlah`, `lewat_hari`, `denda`, `catatan`) VALUES
	(1, 1, 1, 1, '2020-07-05 06:14:42', 0, 1, 0, 0, 'tidak ada catatan'),
	(2, 2, 1, 2, '2020-07-08 14:12:18', 0, 1, 0, 0, 'tidak ada catatan');
/*!40000 ALTER TABLE `elib_pengembalian` ENABLE KEYS */;


-- Dumping structure for table lj_elibrary.elib_rak
CREATE TABLE IF NOT EXISTS `elib_rak` (
  `id_rak` int(11) NOT NULL AUTO_INCREMENT,
  `no_rak` varchar(25) DEFAULT NULL,
  `lokasi_rak` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_rak`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- Dumping data for table lj_elibrary.elib_rak: ~12 rows (approximately)
DELETE FROM `elib_rak`;
/*!40000 ALTER TABLE `elib_rak` DISABLE KEYS */;
INSERT INTO `elib_rak` (`id_rak`, `no_rak`, `lokasi_rak`, `created_at`, `updated_at`) VALUES
	(1, 'A1', 'Lokasi 1', '2020-06-27 01:05:10', '2020-06-27 01:05:10'),
	(2, 'A2', 'Lokasi 1', '2020-06-27 01:05:20', '2020-06-27 01:05:20'),
	(3, 'A3', 'Lokasi 1', '2020-06-27 01:05:30', '2020-06-27 01:05:30'),
	(4, 'B1', 'Lokasi 1', '2020-06-27 01:05:38', '2020-06-27 01:05:38'),
	(5, 'B2', 'Lokasi 1', '2020-06-27 01:05:47', '2020-06-27 01:05:47'),
	(6, 'B3', 'Lokasi 1', '2020-06-27 01:05:55', '2020-06-27 01:05:55'),
	(7, 'C1', 'Lokasi 2', '2020-06-27 01:06:03', '2020-06-27 01:06:03'),
	(8, 'C2', 'Lokasi 2', '2020-06-27 01:06:11', '2020-06-27 01:06:11'),
	(9, 'C3', 'Lokasi 2', '2020-06-27 01:06:18', '2020-06-27 01:06:18'),
	(10, 'D1', 'Lokasi 2', '2020-06-27 01:06:26', '2020-06-27 01:06:26'),
	(11, 'D2', 'Lokasi 2', '2020-06-27 01:06:34', '2020-06-27 01:06:34'),
	(12, 'D3', 'Lokasi 2', '2020-06-27 01:06:43', '2020-06-27 01:06:43');
/*!40000 ALTER TABLE `elib_rak` ENABLE KEYS */;


-- Dumping structure for table lj_elibrary.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lj_elibrary.migrations: ~0 rows (approximately)
DELETE FROM `migrations`;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;


-- Dumping structure for table lj_elibrary.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` enum('ADMINISTRATOR','OPERATOR','ANGGOTA') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ANGGOTA',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table lj_elibrary.users: ~1 rows (approximately)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `jenis`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Muhardi', '123hardi@gmail.com', 'ANGGOTA', NULL, '$2y$10$pGBem5m3hK5bTqmBZjW7nOhD8sCWl0Y3.t5VZCpA/AfLU/SWnLBz2', NULL, '2020-07-09 04:31:15', '2020-07-09 04:31:15');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

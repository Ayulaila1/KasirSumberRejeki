-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 26 Okt 2025 pada 08.24
-- Versi server: 8.4.3
-- Versi PHP: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kasir`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bahans`
--

CREATE TABLE `bahans` (
  `idbahan` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok` int DEFAULT NULL,
  `satuan` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `bahans`
--

INSERT INTO `bahans` (`idbahan`, `nama`, `stok`, `satuan`, `jenis`, `created_at`, `updated_at`) VALUES
(10, 'Gula Sachet', 12, 'bungkus', 'Racikan', NULL, '2025-10-25 15:58:30'),
(11, 'Nutri All', 0, 'bungkus', 'Siap Saji', NULL, '2025-10-24 17:15:35'),
(12, 'Frezee', 0, 'bungkus', 'Siap Saji', NULL, NULL),
(13, 'Hilo', 0, 'bungkus', 'Siap Saji', NULL, NULL),
(14, 'Zee All', 0, 'bungkus', 'Siap Saji', NULL, NULL),
(15, 'Chocolatos', 0, 'bungkus', 'Siap Saji', NULL, NULL),
(16, 'Milo', 0, 'bungkus', 'Siap Saji', NULL, NULL),
(17, 'Maxtea', 0, 'bungkus', 'Siap Saji', NULL, NULL),
(18, 'Drink Beng\"', 0, 'bungkus', 'Siap Saji', NULL, NULL),
(19, 'Capucino', 0, 'bungkus', 'Siap Saji', NULL, NULL),
(20, 'White Coffee', 0, 'bungkus', 'Siap Saji', NULL, NULL),
(21, 'Adem Sari', 0, 'bungkus', 'Siap Saji', NULL, NULL),
(22, 'Aquviva', 0, 'botol', 'Lainnya', NULL, NULL),
(23, 'Sprite', 0, 'botol', 'Lainnya', NULL, NULL),
(24, 'Fanta', 0, 'botol', 'Lainnya', NULL, NULL),
(25, 'Cola', 0, 'botol', 'Lainnya', NULL, NULL),
(26, 'Cola + Susu', 0, 'gelas', 'Racikan', NULL, '2025-10-22 05:36:22'),
(27, 'Soda', 0, 'botol', 'Lainnya', NULL, NULL),
(28, 'Soda + Susu', 0, 'gelas', 'Racikan', NULL, '2025-10-22 05:36:32'),
(29, 'Es Coco Pandan', 0, 'gelas', 'Racikan', NULL, NULL),
(30, 'Es Melon', 0, 'gelas', 'Racikan', NULL, NULL),
(31, 'Sirup susu', 0, 'gelas', 'Racikan', NULL, NULL),
(32, 'Hitam Gelas', 0, 'gelas', 'Racikan', NULL, NULL),
(33, 'Hitam Cangkir', 0, 'gelas', 'Racikan', NULL, NULL),
(34, 'Hitam Gelas', 0, 'gelas', 'Racikan', NULL, NULL),
(35, 'Hitam Cangkir', 0, 'gelas', 'Racikan', NULL, NULL),
(36, 'Country', 0, 'bungkus', 'Lainnya', NULL, NULL),
(37, 'Malboro', 0, 'bungkus', 'Lainnya', NULL, NULL),
(38, 'Lucky Strike', 0, 'bungkus', 'Lainnya', NULL, NULL),
(39, 'Samp A MILD', 0, 'bungkus', 'Lainnya', NULL, NULL),
(40, 'Samp AGA', 0, 'bungkus', 'Lainnya', NULL, NULL),
(41, 'Samp Prima', 0, 'bungkus', 'Lainnya', NULL, '2025-10-22 05:46:16'),
(42, 'LA Merah', 0, 'bungkus', 'Lainnya', NULL, NULL),
(43, 'LA Ungu', 0, 'bungkus', 'Lainnya', NULL, NULL),
(44, 'LA Mangga', 0, 'bungkus', 'Lainnya', NULL, '2025-10-22 06:46:00'),
(45, 'Geo Mild', 0, 'bungkus', 'Lainnya', NULL, NULL),
(46, 'Surya 12', 0, 'bungkus', 'Lainnya', NULL, NULL),
(47, 'Surya 16 Merah', 0, 'bungkus', 'Lainnya', NULL, NULL),
(48, 'LA BOLD', 0, 'bungkus', 'Lainnya', NULL, NULL),
(49, 'PS', 0, 'bungkus', 'Lainnya', NULL, NULL),
(50, 'Andalan 12 Baru', 0, 'bungkus', 'Lainnya', NULL, NULL),
(51, 'Andalan 16 Baru', 0, 'bungkus', 'Lainnya', NULL, '2025-10-22 05:50:54'),
(52, 'Raptor', 0, 'bungkus', 'Lainnya', NULL, NULL),
(53, 'Paku Alam', 0, 'bungkus', 'Lainnya', NULL, NULL),
(54, 'Dewi Hijau', 0, 'bungkus', 'Lainnya', NULL, NULL),
(55, 'Dewi Hitam', 0, 'bungkus', 'Lainnya', NULL, NULL),
(56, 'Samsu Premium', 0, 'bungkus', 'Lainnya', NULL, NULL),
(57, 'Samsoe Kuning', 0, 'bungkus', 'Lainnya', NULL, NULL),
(58, 'Magnum', 0, 'bungkus', 'Lainnya', NULL, NULL),
(59, 'AG Pro', 0, 'bungkus', 'Lainnya', NULL, NULL),
(60, 'Kopi Ijo Gelas', 0, 'gelas', 'Racikan', NULL, NULL),
(61, 'Ijo Cingkir', 0, 'cangkir', 'Racikan', NULL, NULL),
(62, 'Ijo susu gelas', 0, 'gelas', 'Racikan', NULL, NULL),
(63, 'Gula Teh', 0, 'gelas', 'Lainnya', NULL, NULL),
(64, 'Es Teh', 0, 'gelas', 'Racikan', NULL, NULL),
(65, 'Tape Sirup Susu', 0, 'gelas', 'Racikan', NULL, NULL),
(66, 'Susu Kaleng', 0, 'kaleng', 'Bahan Mentah', NULL, NULL),
(67, 'XTRA JOS', 0, 'gelas', 'Siap Saji', NULL, '2025-10-22 05:59:56'),
(68, 'Jos + susu', 0, 'gelas', 'Racikan', NULL, NULL),
(69, 'KUKUBIMA', 0, 'gelas', 'Siap Saji', NULL, NULL),
(70, 'KUKUBIMA + Susu', 0, 'gelas', 'Racikan', NULL, NULL),
(71, 'Ice Cream 4', 0, 'bungkus', 'Lainnya', NULL, NULL),
(72, 'Ice Cream 6', 0, 'bungkus', 'Lainnya', NULL, NULL),
(73, 'Ice Cream 7', 0, 'bungkus', 'Lainnya', NULL, NULL),
(74, 'Telur', 0, 'biji', 'Bahan Mentah', NULL, NULL),
(75, 'Pop Mie', 0, 'bungkus', 'Siap Saji', NULL, NULL),
(76, 'Mie Goreng', 0, 'bungkus', 'Racikan', NULL, NULL),
(77, 'MIe Kuah', 0, 'bungkus', 'Racikan', NULL, NULL),
(78, 'SPIX', 0, 'bungkus', 'Lainnya', NULL, NULL),
(79, 'Chikiball', 0, 'bungkus', 'Lainnya', NULL, NULL),
(80, 'Chitato', 0, 'bungkus', 'Lainnya', NULL, NULL),
(81, 'Apetito', 0, 'bungkus', 'Lainnya', NULL, NULL),
(82, 'Mie Kremez', 0, 'bungkus', 'Lainnya', NULL, NULL),
(83, 'Bengbeng', 0, 'bungkus', 'Lainnya', NULL, NULL),
(84, 'Chocolatos', 0, 'bungkus', 'Lainnya', NULL, NULL),
(85, 'Rolls', 0, 'bungkus', 'Lainnya', NULL, NULL),
(86, 'Superstarz', 0, 'bungkus', 'Lainnya', NULL, NULL),
(87, 'Jetz', 0, 'bungkus', 'Lainnya', NULL, NULL),
(88, 'Bolu', 0, 'bungkus', 'Lainnya', NULL, NULL),
(89, 'Krupuk Udang', 0, 'bungkus', 'Lainnya', NULL, NULL),
(90, 'Emping', 0, 'bungkus', 'Lainnya', NULL, NULL),
(91, 'Stik Sukun', 0, 'bungkus', 'Lainnya', NULL, NULL),
(92, 'Usus', 0, 'bungkus', 'Lainnya', NULL, NULL),
(93, 'Krupuk Tela', 0, 'bungkus', 'Lainnya', NULL, NULL),
(94, 'Krupuk Ikan', 0, 'bungkus', 'Lainnya', NULL, NULL),
(95, 'Telur Asin', 0, 'bungkus', 'Lainnya', NULL, NULL),
(96, 'Sundukan', 0, 'biji', 'Siap Saji', NULL, NULL),
(97, 'Nasi', 0, 'porsi', 'Lainnya', NULL, '2025-10-22 06:40:27'),
(98, 'Gorengan', 0, 'biji', 'Titipan', NULL, NULL),
(99, 'Rengginang', 0, 'biji', 'Lainnya', NULL, NULL),
(100, 'Tape', 0, 'bungkus', 'Lainnya', NULL, NULL),
(101, 'Roti Bakar', 0, 'porsi', 'Racikan', NULL, NULL),
(102, 'Gorengan', 0, 'biji', 'Titipan', NULL, NULL),
(103, 'Makroni', 0, 'bungkus', 'Lainnya', NULL, NULL),
(104, 'Pangsit', 10, 'porsi', 'Lainnya', NULL, '2025-10-26 08:15:03'),
(105, 'Ijo susu cingkir', 0, 'cangkir', 'Racikan', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1761466069),
('laravel_cache_356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1761466069;', 1761466069);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `holds`
--

CREATE TABLE `holds` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_transaksi` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `table_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kas_mutasis`
--

CREATE TABLE `kas_mutasis` (
  `id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `shift` tinyint DEFAULT NULL,
  `jenis` enum('masuk','keluar') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nominal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_iduser` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_06_29_010933_add_role_to_users_table', 2),
(5, '2025_06_29_094026_add_role_to_users_table', 3),
(6, '2025_07_06_030706_add_last_login_at_to_users_table', 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembeliandtls`
--

CREATE TABLE `pembeliandtls` (
  `idpembeliandtl` bigint UNSIGNED NOT NULL,
  `pembelian_idpembelian` bigint UNSIGNED NOT NULL,
  `bahan_idbahan` bigint UNSIGNED NOT NULL,
  `jumlah` int DEFAULT NULL,
  `isi_per_satuan` int DEFAULT NULL,
  `harga_beli` double DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pembeliandtls`
--

INSERT INTO `pembeliandtls` (`idpembeliandtl`, `pembelian_idpembelian`, `bahan_idbahan`, `jumlah`, `isi_per_satuan`, `harga_beli`, `subtotal`, `created_at`, `updated_at`) VALUES
(28, 23, 10, 1, 12, 12000, 12000, NULL, '2025-10-25 15:58:24'),
(29, 24, 104, 20, 1, 2000, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembelians`
--

CREATE TABLE `pembelians` (
  `idpembelian` bigint UNSIGNED NOT NULL,
  `status` varchar(19) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date DEFAULT NULL,
  `shift` tinyint DEFAULT NULL,
  `supplier_idsupplier` bigint NOT NULL,
  `user_iduser` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pembelians`
--

INSERT INTO `pembelians` (`idpembelian`, `status`, `tanggal`, `shift`, `supplier_idsupplier`, `user_iduser`, `created_at`, `updated_at`) VALUES
(23, 'saved', '2025-10-26', NULL, 4, 1, '2025-10-25 15:57:31', '2025-10-25 15:58:30'),
(24, 'saved', '2025-10-26', NULL, 4, 1, '2025-10-26 08:09:35', '2025-10-26 08:10:19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengeluarans`
--

CREATE TABLE `pengeluarans` (
  `idpengeluaran` bigint UNSIGNED NOT NULL,
  `bahan_idbahan` bigint UNSIGNED NOT NULL,
  `tanggal` date DEFAULT NULL,
  `shift` tinyint NOT NULL,
  `jumlah` decimal(10,2) NOT NULL DEFAULT '0.00',
  `harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_iduser` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualandtls`
--

CREATE TABLE `penjualandtls` (
  `idpenjualan` bigint NOT NULL,
  `penjualan_idpenjualan` bigint DEFAULT NULL,
  `produk_idproduk` bigint NOT NULL,
  `qty` int DEFAULT NULL,
  `harga_jual` double DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualans`
--

CREATE TABLE `penjualans` (
  `idpenjualan` bigint NOT NULL,
  `kode_penjualan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_meja` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `shift` tinyint DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `total` double DEFAULT '0',
  `bayar` double DEFAULT NULL,
  `kembalian` double DEFAULT NULL,
  `user_iduser` bigint UNSIGNED NOT NULL,
  `closed_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `produks`
--

CREATE TABLE `produks` (
  `idproduk` bigint NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `supplier_idsupplier` bigint NOT NULL,
  `jenisproduk` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_kedaluwarsa` date DEFAULT NULL,
  `stok_minimum` float DEFAULT NULL,
  `is_titipan` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `harga_jual` int DEFAULT NULL,
  `harga_beli` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `produks`
--

INSERT INTO `produks` (`idproduk`, `nama`, `image`, `supplier_idsupplier`, `jenisproduk`, `kategori`, `tanggal_kedaluwarsa`, `stok_minimum`, `is_titipan`, `harga_jual`, `harga_beli`, `created_at`, `updated_at`) VALUES
(1, 'Gula Sachet', 'produk20251025104718.png', 4, 'Siap Saji', 'Makanan', NULL, NULL, '0', 2000, 1000.00, '2025-10-25 03:47:18', '2025-10-25 03:47:18'),
(5, 'Pangsit', 'produk20251026150813.jpg', 4, 'Titipan', 'Makanan', NULL, NULL, '1', 3000, 2000.00, '2025-10-26 08:08:13', '2025-10-26 08:08:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk_racikans`
--

CREATE TABLE `produk_racikans` (
  `idproduk_racikan` bigint NOT NULL,
  `produk_idproduk` bigint NOT NULL,
  `bahan_idbahan` bigint UNSIGNED NOT NULL,
  `takaran` float DEFAULT NULL,
  `satuan` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `produk_racikans`
--

INSERT INTO `produk_racikans` (`idproduk_racikan`, `produk_idproduk`, `bahan_idbahan`, `takaran`, `satuan`, `created_at`, `updated_at`) VALUES
(1, 1, 10, 1, 'bungkus', '2025-10-25 03:47:36', '2025-10-25 03:47:36'),
(2, 5, 104, 1, '1', '2025-10-26 08:08:32', '2025-10-26 08:08:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `retur_titipan`
--

CREATE TABLE `retur_titipan` (
  `idretur_titipan` bigint NOT NULL,
  `tanggal` date DEFAULT NULL,
  `user_iduser` bigint UNSIGNED NOT NULL,
  `supplier_idsupplier` bigint NOT NULL,
  `produk_idproduk` bigint NOT NULL,
  `qty` float DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `retur_titipan`
--

INSERT INTO `retur_titipan` (`idretur_titipan`, `tanggal`, `user_iduser`, `supplier_idsupplier`, `produk_idproduk`, `qty`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, '2025-10-26', 1, 4, 5, 10, 'rusak', '2025-10-26 08:15:03', '2025-10-26 08:15:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('SpJlxP5qpYfzbxUEDlMUpEiRPMFKb0TNPvVn20N8', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiejBUZkNRakFxeEhaNnM4OGtkUkdpRnE1cGpWYU9HUVRobnE2bFJ2biI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozOToiaHR0cDovLzEyNy4wLjAuMTo4MDAyL2xhcG9yYW4vcGVtYnVrdWFuIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMi9sYXBvcmFuL3BlbWJ1a3VhbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1761466906);

-- --------------------------------------------------------

--
-- Struktur dari tabel `shift_closures`
--

CREATE TABLE `shift_closures` (
  `id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `shift` tinyint NOT NULL,
  `user_iduser` bigint UNSIGNED NOT NULL,
  `total_penjualan` decimal(15,2) DEFAULT '0.00',
  `total_modal` decimal(15,2) DEFAULT '0.00',
  `total_pengeluaran` decimal(15,2) DEFAULT '0.00',
  `kas_fisik` decimal(15,2) DEFAULT '0.00',
  `selisih` decimal(15,2) DEFAULT '0.00',
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `suppliers`
--

CREATE TABLE `suppliers` (
  `idsupplier` bigint NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kontak` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `suppliers`
--

INSERT INTO `suppliers` (`idsupplier`, `nama`, `kontak`, `alamat`, `created_at`, `updated_at`) VALUES
(4, 'Beli di luar', '08545678987', 'Bago', '2025-10-22 06:47:58', '2025-10-22 06:47:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shift` tinyint DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kasir',
  `last_login_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `shift`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `last_login_at`) VALUES
(1, 'Admin', 'admin12@gmail.com', NULL, NULL, '$2y$12$As3z3FD8f5GGJ5nN/0xiUuor6oqiE5aB80qfS1WKVndqMgbSCRY2G', NULL, '2025-10-24 23:23:17', '2025-10-24 23:23:17', 'admin', NULL),
(2, 'Kasir Shift 1', 'shift1@gmail.com', 1, NULL, '$2y$12$DjIJGl6zN/XjT4orHy3UNOOYAYfukYA8zcY2kNupC/s24Fy7OMXJe', NULL, '2025-10-24 23:23:18', '2025-10-24 23:23:18', 'kasir', NULL),
(3, 'Kasir Shift 2', 'shift2@gmail.com', 2, NULL, '$2y$12$GTwGdnKux1ZBnW1spVWw.OzhNFRBMruI9APO8n2Ux9G71jdGth052', NULL, '2025-10-24 23:23:18', '2025-10-24 23:23:18', 'kasir', NULL),
(4, 'Kasir Shift 3', 'shift3@gmail.com', 3, NULL, '$2y$12$3tkr30BimRUOWluQ8d8RU.kIyc4ixj1dexuKxiUQSEuFIB0Tsi4D2', NULL, '2025-10-24 23:23:19', '2025-10-24 23:23:19', 'kasir', NULL);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_laporan_pendapatan_bulanan`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_laporan_pendapatan_bulanan` (
`bulan` varchar(7)
,`total_penjualan` double
,`total_modal` decimal(44,2)
,`keuntungan` double
,`kerugian` double
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_laporan_penjualan`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_laporan_penjualan` (
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_laporan_shift`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_laporan_shift` (
`tanggal` date
,`shift` tinyint
,`total_penjualan` double
,`total_modal` decimal(44,2)
,`laba` double
);

-- --------------------------------------------------------

--
-- Struktur untuk view `view_laporan_pendapatan_bulanan`
--
DROP TABLE IF EXISTS `view_laporan_pendapatan_bulanan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_laporan_pendapatan_bulanan`  AS SELECT date_format(`p`.`tanggal`,'%Y-%m') AS `bulan`, sum(`d`.`subtotal`) AS `total_penjualan`, sum((`d`.`qty` * `pr`.`harga_beli`)) AS `total_modal`, (sum(`d`.`subtotal`) - sum((`d`.`qty` * `pr`.`harga_beli`))) AS `keuntungan`, (case when ((sum(`d`.`subtotal`) - sum((`d`.`qty` * `pr`.`harga_beli`))) < 0) then abs((sum(`d`.`subtotal`) - sum((`d`.`qty` * `pr`.`harga_beli`)))) else 0 end) AS `kerugian` FROM ((`penjualans` `p` join `penjualandtls` `d` on((`p`.`idpenjualan` = `d`.`penjualan_idpenjualan`))) left join `produks` `pr` on((`d`.`produk_idproduk` = `pr`.`idproduk`))) GROUP BY date_format(`p`.`tanggal`,'%Y-%m') ORDER BY `bulan` DESC ;

-- --------------------------------------------------------

--
-- Struktur untuk view `view_laporan_penjualan`
--
DROP TABLE IF EXISTS `view_laporan_penjualan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_laporan_penjualan`  AS SELECT `p`.`idpenjualan` AS `idpenjualan`, `p`.`kode_penjualan` AS `kode_penjualan`, `p`.`customer_name` AS `customer_name`, `p`.`tanggal` AS `tanggal`, `p`.`catatan` AS `catatan`, `p`.`total` AS `total`, `p`.`bayar` AS `bayar`, `p`.`kembalian` AS `kembalian`, `p`.`user_id` AS `user_id`, `d`.`penjualan_idpenjualan` AS `penjualan_idpenjualan`, `d`.`produk_idproduk` AS `produk_idproduk`, `d`.`qty` AS `qty`, `d`.`harga_jual` AS `harga_jual`, `d`.`subtotal` AS `subtotal`, `pr`.`nama` AS `nama_produk` FROM ((`penjualans` `p` join `penjualandtls` `d` on((`p`.`idpenjualan` = `d`.`penjualan_idpenjualan`))) join `produks` `pr` on((`d`.`produk_idproduk` = `pr`.`idproduk`))) ORDER BY `p`.`tanggal` DESC, `p`.`idpenjualan` ASC, `d`.`penjualan_idpenjualan` ASC ;

-- --------------------------------------------------------

--
-- Struktur untuk view `view_laporan_shift`
--
DROP TABLE IF EXISTS `view_laporan_shift`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_laporan_shift`  AS SELECT cast(`p`.`tanggal` as date) AS `tanggal`, `p`.`shift` AS `shift`, sum(`p`.`total`) AS `total_penjualan`, sum((`d`.`qty` * `pr`.`harga_beli`)) AS `total_modal`, (sum(`p`.`total`) - sum((`d`.`qty` * `pr`.`harga_beli`))) AS `laba` FROM ((`penjualans` `p` join `penjualandtls` `d` on((`p`.`idpenjualan` = `d`.`penjualan_idpenjualan`))) join `produks` `pr` on((`d`.`produk_idproduk` = `pr`.`idproduk`))) GROUP BY cast(`p`.`tanggal` as date), `p`.`shift` ORDER BY cast(`p`.`tanggal` as date) DESC, `p`.`shift` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bahans`
--
ALTER TABLE `bahans`
  ADD PRIMARY KEY (`idbahan`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `holds`
--
ALTER TABLE `holds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_transaksi` (`kode_transaksi`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `kas_mutasis`
--
ALTER TABLE `kas_mutasis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_iduser` (`user_iduser`);

--
-- Indeks untuk tabel `pembeliandtls`
--
ALTER TABLE `pembeliandtls`
  ADD PRIMARY KEY (`idpembeliandtl`),
  ADD KEY `pembelian_idpembelian` (`pembelian_idpembelian`),
  ADD KEY `bahan_idbahan` (`bahan_idbahan`);

--
-- Indeks untuk tabel `pembelians`
--
ALTER TABLE `pembelians`
  ADD PRIMARY KEY (`idpembelian`),
  ADD KEY `supplier_idsupplier` (`supplier_idsupplier`),
  ADD KEY `user_iduser` (`user_iduser`);

--
-- Indeks untuk tabel `pengeluarans`
--
ALTER TABLE `pengeluarans`
  ADD PRIMARY KEY (`idpengeluaran`),
  ADD KEY `bahan_idbahan` (`bahan_idbahan`),
  ADD KEY `user_iduser` (`user_iduser`);

--
-- Indeks untuk tabel `penjualandtls`
--
ALTER TABLE `penjualandtls`
  ADD PRIMARY KEY (`idpenjualan`),
  ADD KEY `penjualan_idpenjualan` (`penjualan_idpenjualan`),
  ADD KEY `produk_idproduk` (`produk_idproduk`);

--
-- Indeks untuk tabel `penjualans`
--
ALTER TABLE `penjualans`
  ADD PRIMARY KEY (`idpenjualan`),
  ADD KEY `closed_by` (`closed_by`),
  ADD KEY `kode_penjualan` (`kode_penjualan`),
  ADD KEY `user_iduser` (`user_iduser`) USING BTREE;

--
-- Indeks untuk tabel `produks`
--
ALTER TABLE `produks`
  ADD PRIMARY KEY (`idproduk`),
  ADD KEY `supplier_idsupplier` (`supplier_idsupplier`);

--
-- Indeks untuk tabel `produk_racikans`
--
ALTER TABLE `produk_racikans`
  ADD PRIMARY KEY (`idproduk_racikan`),
  ADD KEY `produk_idproduk` (`produk_idproduk`),
  ADD KEY `bahan_idbahan` (`bahan_idbahan`);

--
-- Indeks untuk tabel `retur_titipan`
--
ALTER TABLE `retur_titipan`
  ADD PRIMARY KEY (`idretur_titipan`),
  ADD KEY `produk_idproduk` (`produk_idproduk`),
  ADD KEY `user_iduser` (`user_iduser`),
  ADD KEY `supplier_idsupplier` (`supplier_idsupplier`);

--
-- Indeks untuk tabel `shift_closures`
--
ALTER TABLE `shift_closures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_iduser` (`user_iduser`);

--
-- Indeks untuk tabel `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`idsupplier`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kas_mutasis`
--
ALTER TABLE `kas_mutasis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pembeliandtls`
--
ALTER TABLE `pembeliandtls`
  MODIFY `idpembeliandtl` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `pembelians`
--
ALTER TABLE `pembelians`
  MODIFY `idpembelian` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `pengeluarans`
--
ALTER TABLE `pengeluarans`
  MODIFY `idpengeluaran` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `penjualandtls`
--
ALTER TABLE `penjualandtls`
  MODIFY `idpenjualan` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `penjualans`
--
ALTER TABLE `penjualans`
  MODIFY `idpenjualan` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `produks`
--
ALTER TABLE `produks`
  MODIFY `idproduk` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `produk_racikans`
--
ALTER TABLE `produk_racikans`
  MODIFY `idproduk_racikan` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `retur_titipan`
--
ALTER TABLE `retur_titipan`
  MODIFY `idretur_titipan` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `shift_closures`
--
ALTER TABLE `shift_closures`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `idsupplier` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `kas_mutasis`
--
ALTER TABLE `kas_mutasis`
  ADD CONSTRAINT `kas_mutasis_ibfk_1` FOREIGN KEY (`user_iduser`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `pembeliandtls`
--
ALTER TABLE `pembeliandtls`
  ADD CONSTRAINT `pembeliandtls_ibfk_1` FOREIGN KEY (`pembelian_idpembelian`) REFERENCES `pembelians` (`idpembelian`),
  ADD CONSTRAINT `pembeliandtls_ibfk_2` FOREIGN KEY (`bahan_idbahan`) REFERENCES `bahans` (`idbahan`);

--
-- Ketidakleluasaan untuk tabel `pembelians`
--
ALTER TABLE `pembelians`
  ADD CONSTRAINT `pembelians_ibfk_1` FOREIGN KEY (`supplier_idsupplier`) REFERENCES `suppliers` (`idsupplier`),
  ADD CONSTRAINT `pembelians_ibfk_2` FOREIGN KEY (`user_iduser`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `pengeluarans`
--
ALTER TABLE `pengeluarans`
  ADD CONSTRAINT `pengeluarans_ibfk_1` FOREIGN KEY (`bahan_idbahan`) REFERENCES `bahans` (`idbahan`),
  ADD CONSTRAINT `pengeluarans_ibfk_2` FOREIGN KEY (`user_iduser`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `penjualandtls`
--
ALTER TABLE `penjualandtls`
  ADD CONSTRAINT `penjualandtls_ibfk_1` FOREIGN KEY (`penjualan_idpenjualan`) REFERENCES `penjualans` (`idpenjualan`),
  ADD CONSTRAINT `penjualandtls_ibfk_2` FOREIGN KEY (`produk_idproduk`) REFERENCES `produks` (`idproduk`);

--
-- Ketidakleluasaan untuk tabel `penjualans`
--
ALTER TABLE `penjualans`
  ADD CONSTRAINT `penjualans_ibfk_1` FOREIGN KEY (`user_iduser`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `penjualans_ibfk_2` FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `produks`
--
ALTER TABLE `produks`
  ADD CONSTRAINT `produks_ibfk_1` FOREIGN KEY (`supplier_idsupplier`) REFERENCES `suppliers` (`idsupplier`);

--
-- Ketidakleluasaan untuk tabel `produk_racikans`
--
ALTER TABLE `produk_racikans`
  ADD CONSTRAINT `produk_racikans_ibfk_1` FOREIGN KEY (`produk_idproduk`) REFERENCES `produks` (`idproduk`),
  ADD CONSTRAINT `produk_racikans_ibfk_2` FOREIGN KEY (`bahan_idbahan`) REFERENCES `bahans` (`idbahan`);

--
-- Ketidakleluasaan untuk tabel `retur_titipan`
--
ALTER TABLE `retur_titipan`
  ADD CONSTRAINT `retur_titipan_ibfk_1` FOREIGN KEY (`produk_idproduk`) REFERENCES `produks` (`idproduk`),
  ADD CONSTRAINT `retur_titipan_ibfk_2` FOREIGN KEY (`user_iduser`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `retur_titipan_ibfk_3` FOREIGN KEY (`supplier_idsupplier`) REFERENCES `suppliers` (`idsupplier`);

--
-- Ketidakleluasaan untuk tabel `shift_closures`
--
ALTER TABLE `shift_closures`
  ADD CONSTRAINT `shift_closures_ibfk_1` FOREIGN KEY (`user_iduser`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

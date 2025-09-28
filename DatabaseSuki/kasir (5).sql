-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 26 Sep 2025 pada 04.34
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
  `nama` varchar(100) NOT NULL,
  `stok` int DEFAULT NULL,
  `satuan` varchar(12) DEFAULT NULL,
  `jenis` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `bahans`
--

INSERT INTO `bahans` (`idbahan`, `nama`, `stok`, `satuan`, `jenis`, `created_at`, `updated_at`) VALUES
(7, 'Gorengan', 5, 'pcs', 'Titipan', NULL, '2025-07-05 17:36:16'),
(8, 'fresh milk', 0, 'porsi', 'Racikan', NULL, '2025-07-05 16:16:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_da4b9237bacccdf19c0760cab7aec4a8359010b0', 'i:1;', 1755963025),
('laravel_cache_da4b9237bacccdf19c0760cab7aec4a8359010b0:timer', 'i:1755963025;', 1755963025);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
(5, '2025_06_29_094026_add_role_to_users_table', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembeliandtls`
--

CREATE TABLE `pembeliandtls` (
  `idpembeliandtl` bigint UNSIGNED NOT NULL,
  `pembelian_idpembelian` bigint UNSIGNED DEFAULT NULL,
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
(18, 11, 7, 20, 1, 1500, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembelians`
--

CREATE TABLE `pembelians` (
  `idpembelian` bigint UNSIGNED NOT NULL,
  `status` varchar(19) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `supplier_idsupplier` bigint DEFAULT NULL,
  `user_iduser` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pembelians`
--

INSERT INTO `pembelians` (`idpembelian`, `status`, `tanggal`, `supplier_idsupplier`, `user_iduser`, `created_at`, `updated_at`) VALUES
(11, 'saved', '2025-07-06', 1, 2, NULL, '2025-07-05 16:25:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualandtls`
--

CREATE TABLE `penjualandtls` (
  `idpenjualan` bigint NOT NULL,
  `penjualan_idpenjualan` bigint DEFAULT NULL,
  `produk_idproduk` bigint DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `harga_jual` double DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `penjualandtls`
--

INSERT INTO `penjualandtls` (`idpenjualan`, `penjualan_idpenjualan`, `produk_idproduk`, `qty`, `harga_jual`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 17, 1, 2000, 2000, '2025-07-11 07:46:54', '2025-07-11 07:46:54'),
(2, 2, 17, 1, 2000, 2000, '2025-07-11 07:47:36', '2025-07-11 07:47:36'),
(3, 3, 17, 5, 2000, 10000, '2025-07-11 08:01:16', '2025-07-11 08:01:16'),
(7, 7, 17, 2, 2000, 4000, '2025-07-11 08:07:00', '2025-07-11 08:07:00'),
(8, 8, 17, 2, 2000, 4000, '2025-07-11 08:08:22', '2025-07-11 08:08:22'),
(9, 10, 19, 1, 185000, 185000, '2025-09-14 20:16:10', '2025-09-14 20:16:10'),
(10, 11, 19, 1, 185000, 185000, '2025-09-14 20:16:41', '2025-09-14 20:16:41'),
(11, 12, 19, 1, 185000, 185000, '2025-09-14 20:25:22', '2025-09-14 20:25:22'),
(12, 13, 17, 5, 2000, 10000, '2025-09-14 20:26:44', '2025-09-14 20:26:44'),
(13, 14, 17, 2, 2000, 4000, '2025-09-14 23:00:13', '2025-09-14 23:00:13'),
(14, 15, 17, 1, 2000, 2000, '2025-09-14 23:02:29', '2025-09-14 23:02:29'),
(15, 16, 19, 2, 185000, 370000, '2025-09-14 23:03:59', '2025-09-14 23:03:59'),
(16, 17, 19, 2, 185000, 370000, '2025-09-14 23:23:54', '2025-09-14 23:23:54'),
(17, 18, 19, 2, 185000, 370000, '2025-09-14 23:26:41', '2025-09-14 23:26:41'),
(18, 19, 19, 2, 185000, 370000, '2025-09-14 23:26:55', '2025-09-14 23:26:55'),
(19, 20, 19, 2, 185000, 370000, '2025-09-14 23:26:55', '2025-09-14 23:26:55'),
(20, 21, 19, 2, 185000, 370000, '2025-09-14 23:26:55', '2025-09-14 23:26:55'),
(21, 22, 19, 2, 185000, 370000, '2025-09-14 23:26:55', '2025-09-14 23:26:55'),
(22, 23, 19, 2, 185000, 370000, '2025-09-14 23:26:55', '2025-09-14 23:26:55'),
(23, 24, 18, 1, 10000, 10000, '2025-09-14 23:28:31', '2025-09-14 23:28:31'),
(24, 25, 18, 1, 10000, 10000, '2025-09-14 23:29:53', '2025-09-14 23:29:53'),
(25, 26, 17, 1, 2000, 2000, '2025-09-14 23:30:50', '2025-09-14 23:30:50'),
(26, 27, 17, 1, 2000, 2000, '2025-09-14 23:31:59', '2025-09-14 23:31:59'),
(27, 28, 19, 1, 185000, 185000, '2025-09-14 23:37:25', '2025-09-14 23:37:25'),
(28, 29, 19, 1, 185000, 185000, '2025-09-14 23:40:13', '2025-09-14 23:40:13'),
(29, 30, 19, 1, 185000, 185000, '2025-09-14 23:40:43', '2025-09-14 23:40:43'),
(30, 31, 19, 1, 185000, 185000, '2025-09-15 01:01:56', '2025-09-15 01:01:56'),
(31, 31, 17, 1, 2000, 2000, '2025-09-15 01:01:56', '2025-09-15 01:01:56'),
(32, 32, 19, 1, 185000, 185000, '2025-09-15 01:32:48', '2025-09-15 01:32:48'),
(33, 33, 19, 1, 185000, 185000, '2025-09-15 01:34:39', '2025-09-15 01:34:39'),
(34, 34, 19, 1, 185000, 185000, '2025-09-15 01:35:29', '2025-09-15 01:35:29'),
(35, 35, 19, 1, 185000, 185000, '2025-09-22 19:38:56', '2025-09-22 19:38:56'),
(36, 36, 17, 1, 2000, 2000, '2025-09-24 19:22:53', '2025-09-24 19:22:53'),
(37, 37, 17, 1, 2000, 2000, '2025-09-24 19:23:21', '2025-09-24 19:23:21'),
(38, 38, 17, 1, 2000, 2000, '2025-09-24 19:53:55', '2025-09-24 19:53:55'),
(39, 39, 17, 1, 2000, 2000, '2025-09-25 01:04:19', '2025-09-25 01:04:19'),
(40, 39, 19, 1, 185000, 185000, '2025-09-25 01:04:19', '2025-09-25 01:04:19'),
(41, 40, 19, 1, 185000, 185000, '2025-09-25 01:21:40', '2025-09-25 01:21:40'),
(42, 41, 19, 1, 185000, 185000, '2025-09-25 01:29:01', '2025-09-25 01:29:01'),
(43, 41, 17, 1, 2000, 2000, '2025-09-25 01:29:01', '2025-09-25 01:29:01'),
(44, 42, 17, 1, 2000, 2000, '2025-09-25 01:35:27', '2025-09-25 01:35:27'),
(45, 42, 18, 1, 10000, 10000, '2025-09-25 01:35:27', '2025-09-25 01:35:27'),
(46, 43, 19, 1, 185000, 185000, '2025-09-25 01:42:11', '2025-09-25 01:42:11'),
(47, 43, 18, 1, 10000, 10000, '2025-09-25 01:42:11', '2025-09-25 01:42:11'),
(48, 44, 17, 2, 2000, 4000, '2025-09-25 02:05:06', '2025-09-25 02:05:06'),
(49, 44, 18, 1, 10000, 10000, '2025-09-25 02:05:06', '2025-09-25 02:05:06'),
(50, 45, 17, 1, 2000, 2000, '2025-09-25 06:38:53', '2025-09-25 06:38:53'),
(51, 46, 17, 1, 2000, 2000, '2025-09-25 19:51:30', '2025-09-25 19:51:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualans`
--

CREATE TABLE `penjualans` (
  `idpenjualan` bigint NOT NULL,
  `kode_penjualan` varchar(50) DEFAULT NULL,
  `customer_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_meja` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `catatan` text,
  `total` double DEFAULT '0',
  `bayar` double DEFAULT NULL,
  `kembalian` double DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `penjualans`
--

INSERT INTO `penjualans` (`idpenjualan`, `kode_penjualan`, `customer_name`, `no_meja`, `tanggal`, `catatan`, `total`, `bayar`, `kembalian`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'TRX-20250711-1CsH', NULL, NULL, '2025-07-11', NULL, 2000, 0, -2000, 2, '2025-07-11 07:46:54', '2025-07-11 07:46:54'),
(2, 'TRX-20250711-l6Xn', NULL, NULL, '2025-07-11', NULL, 2000, 0, -2000, 2, '2025-07-11 07:47:36', '2025-07-11 07:47:36'),
(3, 'TRX-20250711-fIwb', NULL, NULL, '2025-07-11', NULL, 10000, 20000, 10000, 2, '2025-07-11 08:01:16', '2025-07-11 08:01:16'),
(7, 'TRX-20250711-PNqy', NULL, NULL, '2025-07-11', NULL, 4000, 10000, 6000, 2, '2025-07-11 08:07:00', '2025-07-11 08:07:00'),
(8, 'TRX-20250711-rSoi', NULL, NULL, '2025-07-11', NULL, 4000, 10000, 6000, 2, '2025-07-11 08:08:22', '2025-07-11 08:08:22'),
(9, 'TRX-20250915-g5qT', NULL, NULL, '2025-09-15', NULL, 0, 0, 0, 2, '2025-09-14 19:39:24', '2025-09-14 19:39:24'),
(10, 'TRX-20250915-ftOJ', NULL, NULL, '2025-09-15', NULL, 185000, 200000, 15000, 2, '2025-09-14 20:16:10', '2025-09-14 20:16:10'),
(11, 'TRX-20250915-fBDW', NULL, NULL, '2025-09-15', NULL, 185000, 200000, 15000, 2, '2025-09-14 20:16:41', '2025-09-14 20:16:41'),
(12, 'TRX-20250915-83AS', NULL, NULL, '2025-09-15', NULL, 185000, 0, -185000, 2, '2025-09-14 20:25:22', '2025-09-14 20:25:22'),
(13, 'TRX-20250915-eJz3', NULL, NULL, '2025-09-15', NULL, 10000, 20000, 10000, 2, '2025-09-14 20:26:44', '2025-09-14 20:26:44'),
(14, 'TRX-20250915-Hece', NULL, NULL, '2025-09-15', NULL, 4000, 50000, 46000, 2, '2025-09-14 23:00:13', '2025-09-14 23:00:13'),
(15, 'TRX-20250915-MDPz', NULL, NULL, '2025-09-15', NULL, 2000, 5000, 3000, 2, '2025-09-14 23:02:29', '2025-09-14 23:02:29'),
(16, 'TRX-20250915-3YzC', NULL, NULL, '2025-09-15', NULL, 370000, 500000, 130000, 2, '2025-09-14 23:03:59', '2025-09-14 23:03:59'),
(17, 'TRX-20250915-FgFo', NULL, NULL, '2025-09-15', NULL, 370000, 500000, 130000, 2, '2025-09-14 23:23:54', '2025-09-14 23:23:54'),
(18, 'TRX-20250915-Va16', NULL, NULL, '2025-09-15', NULL, 370000, 400000, 30000, 2, '2025-09-14 23:26:41', '2025-09-14 23:26:41'),
(19, 'TRX-20250915-POjB', NULL, NULL, '2025-09-15', NULL, 370000, 400000, 30000, 2, '2025-09-14 23:26:55', '2025-09-14 23:26:55'),
(20, 'TRX-20250915-B3fg', NULL, NULL, '2025-09-15', NULL, 370000, 400000, 30000, 2, '2025-09-14 23:26:55', '2025-09-14 23:26:55'),
(21, 'TRX-20250915-I8DN', NULL, NULL, '2025-09-15', NULL, 370000, 400000, 30000, 2, '2025-09-14 23:26:55', '2025-09-14 23:26:55'),
(22, 'TRX-20250915-Xrd3', NULL, NULL, '2025-09-15', NULL, 370000, 400000, 30000, 2, '2025-09-14 23:26:55', '2025-09-14 23:26:55'),
(23, 'TRX-20250915-c5MJ', NULL, NULL, '2025-09-15', NULL, 370000, 400000, 30000, 2, '2025-09-14 23:26:55', '2025-09-14 23:26:55'),
(24, 'TRX-20250915-MYXv', NULL, NULL, '2025-09-15', NULL, 10000, 10000, 0, 2, '2025-09-14 23:28:31', '2025-09-14 23:28:31'),
(25, 'TRX-20250915-u6cH', NULL, NULL, '2025-09-15', NULL, 10000, 20000, 10000, 2, '2025-09-14 23:29:53', '2025-09-14 23:29:53'),
(26, 'TRX-20250915-KZeu', NULL, NULL, '2025-09-15', NULL, 2000, 10000, 8000, 2, '2025-09-14 23:30:50', '2025-09-14 23:30:50'),
(27, 'TRX-20250915-D1gA', NULL, NULL, '2025-09-15', NULL, 2000, 5000, 3000, 2, '2025-09-14 23:31:59', '2025-09-14 23:31:59'),
(28, 'TRX-20250915-OdUH', NULL, NULL, '2025-09-15', NULL, 185000, 20000, -165000, 2, '2025-09-14 23:37:25', '2025-09-14 23:37:25'),
(29, 'TRX-20250915-VZaz', NULL, NULL, '2025-09-15', NULL, 185000, 200000, 15000, 2, '2025-09-14 23:40:13', '2025-09-14 23:40:13'),
(30, 'TRX-20250915-7MGL', NULL, NULL, '2025-09-15', NULL, 185000, 200000, 15000, 2, '2025-09-14 23:40:43', '2025-09-14 23:40:43'),
(31, 'TRX-20250915-ZmIW', NULL, NULL, '2025-09-15', NULL, 187000, 200000, 13000, 2, '2025-09-15 01:01:56', '2025-09-15 01:01:56'),
(32, 'TRX-20250915-K265', NULL, NULL, '2025-09-15', NULL, 185000, 10000000, 9815000, 2, '2025-09-15 01:32:48', '2025-09-15 01:32:48'),
(33, 'TRX-20250915-CyoQ', NULL, NULL, '2025-09-15', NULL, 185000, 10000000, 9815000, 2, '2025-09-15 01:34:39', '2025-09-15 01:34:39'),
(34, 'TRX-20250915-WsEz', NULL, NULL, '2025-09-15', NULL, 185000, 0, -185000, 2, '2025-09-15 01:35:29', '2025-09-15 01:35:29'),
(35, 'TRX-20250923-oI2z', NULL, NULL, '2025-09-23', NULL, 185000, 200000, 15000, 2, '2025-09-22 19:38:55', '2025-09-22 19:38:55'),
(36, 'TRX-20250925-PeDX', NULL, NULL, '2025-09-25', NULL, 2000, 10000, 8000, 2, '2025-09-24 19:22:53', '2025-09-24 19:22:53'),
(37, 'TRX-20250925-Z03V', NULL, NULL, '2025-09-25', NULL, 2000, 10000, 8000, 2, '2025-09-24 19:23:21', '2025-09-24 19:23:21'),
(38, 'TRX-20250925-4JzS', NULL, NULL, '2025-09-25', NULL, 2000, 10000, 8000, 2, '2025-09-24 19:53:55', '2025-09-24 19:53:55'),
(39, 'TRX-20250925-vVY6', NULL, NULL, '2025-09-25', NULL, 187000, 200000, 13000, 2, '2025-09-25 01:04:19', '2025-09-25 01:04:19'),
(40, 'TRX-20250925-o4w0', NULL, NULL, '2025-09-25', NULL, 185000, 200000, 15000, 2, '2025-09-25 01:21:40', '2025-09-25 01:21:40'),
(41, 'TRX-20250925-EHlk', NULL, NULL, '2025-09-25', NULL, 187000, 200000, 13000, 2, '2025-09-25 01:29:01', '2025-09-25 01:29:01'),
(42, 'TRX-20250925-tECk', NULL, NULL, '2025-09-25', NULL, 12000, 20000, 8000, 2, '2025-09-25 01:35:27', '2025-09-25 01:35:27'),
(43, 'TRX-20250925-PDjk', NULL, NULL, '2025-09-25', NULL, 195000, 200000, 5000, 2, '2025-09-25 01:42:11', '2025-09-25 01:42:11'),
(44, 'TRX-20250925-rWmN', NULL, NULL, '2025-09-25', NULL, 14000, 20000, 6000, 2, '2025-09-25 02:05:06', '2025-09-25 02:05:06'),
(45, 'TRX-20250925-ZUVb', NULL, NULL, '2025-09-25', NULL, 2000, 10000, 8000, 2, '2025-09-25 06:38:53', '2025-09-25 06:38:53'),
(46, 'TRX-20250926-JL35', 'robi', NULL, '2025-09-26', NULL, 2000, 10000, 8000, 2, '2025-09-25 19:51:30', '2025-09-25 19:51:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produks`
--

CREATE TABLE `produks` (
  `idproduk` bigint NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `supplier_idsupplier` bigint DEFAULT NULL,
  `jenisproduk` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_kedaluwarsa` date DEFAULT NULL,
  `stok_minimum` float DEFAULT NULL,
  `is_titipan` varchar(20) DEFAULT '0',
  `harga_jual` int DEFAULT NULL,
  `harga_beli` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `produks`
--

INSERT INTO `produks` (`idproduk`, `nama`, `image`, `supplier_idsupplier`, `jenisproduk`, `kategori`, `tanggal_kedaluwarsa`, `stok_minimum`, `is_titipan`, `harga_jual`, `harga_beli`, `created_at`, `updated_at`) VALUES
(17, 'Gorengan', 'produk20250705232419.jpg', 1, 'Titipan', 'Snack', '2025-07-06', NULL, '1', 2000, 1500.00, '2025-07-05 16:24:19', '2025-09-14 19:36:32'),
(18, 'kriwil maroon 1th', NULL, 2, NULL, NULL, '2025-07-09', NULL, '0', 10000, 89000.00, '2025-07-09 01:23:14', '2025-07-09 01:50:59'),
(19, 'susu', 'produk20250823152954.jpg', NULL, 'Siap Saji', 'Minuman', '2025-08-23', NULL, '0', 185000, 117000.00, '2025-08-23 08:29:55', '2025-08-23 08:29:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk_racikans`
--

CREATE TABLE `produk_racikans` (
  `idproduk_racikan` bigint NOT NULL,
  `produk_idproduk` bigint DEFAULT NULL,
  `bahan_idbahan` bigint UNSIGNED DEFAULT NULL,
  `takaran` float DEFAULT NULL,
  `satuan` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `produk_racikans`
--

INSERT INTO `produk_racikans` (`idproduk_racikan`, `produk_idproduk`, `bahan_idbahan`, `takaran`, `satuan`, `created_at`, `updated_at`) VALUES
(12, 17, 7, 1, 'pcs', '2025-07-05 16:24:39', '2025-07-05 16:24:39'),
(13, 18, 7, NULL, NULL, '2025-07-09 01:23:25', '2025-07-09 01:23:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `retur_titipan`
--

CREATE TABLE `retur_titipan` (
  `idretur_titipan` bigint NOT NULL,
  `tanggal` date DEFAULT NULL,
  `supplier_idsupplier` bigint DEFAULT NULL,
  `produk_idproduk` bigint NOT NULL,
  `qty` float DEFAULT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0EMFbIvcgtVjluJxJAOL6KZ2qbVJc5lqMeTOBymo', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiM3ZqOUpqcVhtN1BlUHNSQzYyS0ZFeXh2ZDlFTmt3T1J6Z2VlM0lwWSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDIvbGFwb3Jhbi1wZW5kYXBhdGFuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1758859922),
('CUUo6SBmCLmGwjrH1yX143lzXCQD8mGhbleKnFK4', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoid2FydWJHVWdReFNXZkRtS0JMOEhhUmJkWklERW8yeDlreFdwSnBUaSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDIva2FzaXIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1758861084);

-- --------------------------------------------------------

--
-- Struktur dari tabel `suppliers`
--

CREATE TABLE `suppliers` (
  `idsupplier` bigint NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `kontak` varchar(255) DEFAULT NULL,
  `alamat` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `suppliers`
--

INSERT INTO `suppliers` (`idsupplier`, `nama`, `kontak`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 'Mba lini', '087245876564', 'Jln. Kimangun Sarkoro, Beji, Boyolangu, Tulungagung', NULL, '2025-07-05 06:38:25'),
(2, 'Susanti', '0897653214590', 'Gedung Tempo Scan Tower, Jl. HR. Rasuna Said Kav.3-4, Kel. Kuningan Timur, Kec. Setiabudi, JakSel		', NULL, '2025-06-29 17:31:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kasir'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1, 'Admin', 'admin123@gmail.com', NULL, '$2y$12$eB1NES4mCm3dJzNNetr5OecDSj3bG3Z5BaI1vrke6bv/oSs7rTqhm', NULL, '2025-06-27 08:51:51', '2025-06-27 08:51:51', 'kasir'),
(2, 'admin12', 'admin12@gmail.com', NULL, '$2y$12$oro5kCWyghuqTqObx/6HgOIB4xZUHTleO1x6dWA.auya2bDiNsGB2', NULL, '2025-06-28 01:31:04', '2025-06-28 01:31:04', 'kasir'),
(3, 'Ayu90', 'Ayu90@gmail.com', NULL, '$2y$12$we.p1v62bWbvsJNqqsVuCuQY2lXATSL4YHqFKdt9iD4Q.7nhhQ5Cq', NULL, '2025-06-30 06:22:52', '2025-06-30 06:22:52', 'kasir');

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
`idpenjualan` bigint
,`kode_penjualan` varchar(50)
,`customer_name` varchar(100)
,`tanggal` date
,`total` double
,`bayar` double
,`kembalian` double
,`user_id` bigint
,`penjualan_idpenjualan` bigint
,`produk_idproduk` bigint
,`qty` int
,`harga_jual` double
,`subtotal` double
,`nama_produk` varchar(255)
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

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_laporan_penjualan`  AS SELECT `p`.`idpenjualan` AS `idpenjualan`, `p`.`kode_penjualan` AS `kode_penjualan`, `p`.`customer_name` AS `customer_name`, `p`.`tanggal` AS `tanggal`, `p`.`total` AS `total`, `p`.`bayar` AS `bayar`, `p`.`kembalian` AS `kembalian`, `p`.`user_id` AS `user_id`, `d`.`penjualan_idpenjualan` AS `penjualan_idpenjualan`, `d`.`produk_idproduk` AS `produk_idproduk`, `d`.`qty` AS `qty`, `d`.`harga_jual` AS `harga_jual`, `d`.`subtotal` AS `subtotal`, `pr`.`nama` AS `nama_produk` FROM ((`penjualans` `p` join `penjualandtls` `d` on((`p`.`idpenjualan` = `d`.`penjualan_idpenjualan`))) join `produks` `pr` on((`d`.`produk_idproduk` = `pr`.`idproduk`))) ORDER BY `p`.`tanggal` DESC, `p`.`idpenjualan` ASC, `d`.`penjualan_idpenjualan` ASC ;

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
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

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
  ADD UNIQUE KEY `kode_penjualan` (`kode_penjualan`);

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
  ADD KEY `supplier_idsupplier` (`supplier_idsupplier`),
  ADD KEY `produk_idproduk` (`produk_idproduk`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
-- AUTO_INCREMENT untuk tabel `bahans`
--
ALTER TABLE `bahans`
  MODIFY `idbahan` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pembeliandtls`
--
ALTER TABLE `pembeliandtls`
  MODIFY `idpembeliandtl` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `pembelians`
--
ALTER TABLE `pembelians`
  MODIFY `idpembelian` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `penjualandtls`
--
ALTER TABLE `penjualandtls`
  MODIFY `idpenjualan` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT untuk tabel `penjualans`
--
ALTER TABLE `penjualans`
  MODIFY `idpenjualan` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT untuk tabel `produks`
--
ALTER TABLE `produks`
  MODIFY `idproduk` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `produk_racikans`
--
ALTER TABLE `produk_racikans`
  MODIFY `idproduk_racikan` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `retur_titipan`
--
ALTER TABLE `retur_titipan`
  MODIFY `idretur_titipan` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `idsupplier` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

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
-- Ketidakleluasaan untuk tabel `penjualandtls`
--
ALTER TABLE `penjualandtls`
  ADD CONSTRAINT `penjualandtls_ibfk_1` FOREIGN KEY (`penjualan_idpenjualan`) REFERENCES `penjualans` (`idpenjualan`),
  ADD CONSTRAINT `penjualandtls_ibfk_2` FOREIGN KEY (`produk_idproduk`) REFERENCES `produks` (`idproduk`);

--
-- Ketidakleluasaan untuk tabel `produks`
--
ALTER TABLE `produks`
  ADD CONSTRAINT `produks_ibfk_1` FOREIGN KEY (`supplier_idsupplier`) REFERENCES `suppliers` (`idsupplier`) ON DELETE SET NULL,
  ADD CONSTRAINT `produks_ibfk_2` FOREIGN KEY (`supplier_idsupplier`) REFERENCES `suppliers` (`idsupplier`);

--
-- Ketidakleluasaan untuk tabel `produk_racikans`
--
ALTER TABLE `produk_racikans`
  ADD CONSTRAINT `produk_racikans_ibfk_1` FOREIGN KEY (`produk_idproduk`) REFERENCES `produks` (`idproduk`) ON DELETE CASCADE,
  ADD CONSTRAINT `produk_racikans_ibfk_2` FOREIGN KEY (`bahan_idbahan`) REFERENCES `bahans` (`idbahan`);

--
-- Ketidakleluasaan untuk tabel `retur_titipan`
--
ALTER TABLE `retur_titipan`
  ADD CONSTRAINT `retur_titipan_ibfk_1` FOREIGN KEY (`supplier_idsupplier`) REFERENCES `suppliers` (`idsupplier`),
  ADD CONSTRAINT `retur_titipan_ibfk_2` FOREIGN KEY (`produk_idproduk`) REFERENCES `produks` (`idproduk`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

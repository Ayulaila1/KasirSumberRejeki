-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 05 Jul 2025 pada 06.02
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
(2, 'Syrup', 50000, 'ml', 'Racikan', NULL, '2025-07-03 05:50:56'),
(3, 'nasi', 12, 'porsi', 'Racikan', NULL, '2025-07-03 05:53:38'),
(4, 'Ayam', 0, 'potong', NULL, NULL, '2025-07-03 05:55:16'),
(6, 'Es Batu', 6, 'porsi', 'Lainnya', NULL, '2025-07-04 01:14:24'),
(7, 'Gorengan', 0, 'pcs', 'Titipan', NULL, '2025-07-04 05:55:49'),
(8, 'fresh milk', 250, 'porsi', 'Racikan', NULL, '2025-07-04 02:19:15');

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
('laravel_cache_da4b9237bacccdf19c0760cab7aec4a8359010b0', 'i:1;', 1751620552),
('laravel_cache_da4b9237bacccdf19c0760cab7aec4a8359010b0:timer', 'i:1751620552;', 1751620552);

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
(7, 2, 2, 10, 500, 56000, NULL, NULL, NULL),
(8, 3, 2, 70, 500, 96000, 0, NULL, '2025-07-02 11:47:20'),
(9, 4, 3, 4, 0, 5000, NULL, NULL, '2025-07-02 12:12:38'),
(10, 5, 4, 5, 1, 6000, NULL, NULL, NULL),
(11, 6, 3, 8, 1, 9000, NULL, NULL, NULL),
(12, 7, 4, 8, 1, 9000, NULL, NULL, NULL),
(13, 8, 6, 6, 1, 3000, NULL, NULL, NULL),
(15, 9, 7, 20, 1, 2000, NULL, NULL, NULL),
(16, 10, 8, 5, 50, 10000, NULL, NULL, NULL);

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
  `total_item` int DEFAULT '0',
  `total_hargabeli` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pembelians`
--

INSERT INTO `pembelians` (`idpembelian`, `status`, `tanggal`, `supplier_idsupplier`, `user_iduser`, `total_item`, `total_hargabeli`, `created_at`, `updated_at`) VALUES
(2, 'saved', '2025-07-02', 1, 2, 5, NULL, NULL, '2025-07-02 22:53:14'),
(3, 'saved', '2025-07-02', 1, 2, NULL, NULL, NULL, '2025-07-02 11:47:23'),
(4, 'saved', '2025-07-02', 2, 2, NULL, NULL, NULL, '2025-07-02 12:12:41'),
(5, 'unsaved', '2025-07-02', 2, 2, NULL, NULL, NULL, '2025-07-03 00:08:10'),
(6, 'saved', '2025-07-03', 2, 2, NULL, NULL, NULL, '2025-07-03 00:41:20'),
(7, 'unsaved', '2025-07-03', NULL, 2, NULL, NULL, NULL, '2025-07-03 05:55:16'),
(8, 'saved', '2025-07-04', NULL, 2, NULL, NULL, NULL, '2025-07-04 01:14:24'),
(9, 'saved', '2025-07-04', 1, 2, NULL, NULL, NULL, '2025-07-04 01:19:34'),
(10, 'saved', '2025-07-04', NULL, 2, NULL, NULL, NULL, '2025-07-04 02:19:15');

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

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualans`
--

CREATE TABLE `penjualans` (
  `idpenjualan` bigint NOT NULL,
  `kode_penjualan` varchar(50) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `total` double DEFAULT '0',
  `bayar` double DEFAULT NULL,
  `kembalian` double DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `tanggal_kedaluwarsa` date DEFAULT NULL,
  `satuan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

INSERT INTO `produks` (`idproduk`, `nama`, `image`, `supplier_idsupplier`, `jenisproduk`, `tanggal_kedaluwarsa`, `satuan`, `stok_minimum`, `is_titipan`, `harga_jual`, `harga_beli`, `created_at`, `updated_at`) VALUES
(3, 'Teh', 'produk20250629120517.png', 1, 'racikan', NULL, 'pcs', NULL, NULL, NULL, NULL, '2025-06-29 05:05:17', '2025-07-01 17:17:11'),
(8, 'Jus Arab', 'produk20250629233049.png', NULL, 'racikan', NULL, 'pcs', NULL, '1', NULL, NULL, '2025-06-29 16:30:49', '2025-07-01 00:10:45'),
(9, 'Jus Alpukat', 'produk20250701132045.png', 2, 'racikan', '2025-07-14', 'pcs', NULL, '1', 6700, 9000.00, '2025-07-01 06:20:45', '2025-07-01 16:31:09'),
(10, 'Orange', NULL, 1, NULL, '2025-07-02', 'pcs', NULL, NULL, NULL, NULL, '2025-07-01 16:30:52', '2025-07-01 16:30:52'),
(11, 'Kopi', 'produk20250702114937.png', 2, 'sachet', '2025-07-02', 'pcs', NULL, 'Titipan', 156000, 120000.00, '2025-07-02 04:49:37', '2025-07-02 04:49:37'),
(12, 'Tempe', NULL, 2, 'Titipan', NULL, 'pcs', NULL, NULL, NULL, NULL, '2025-07-03 01:10:20', '2025-07-03 01:10:20'),
(13, 'Roti O', NULL, 2, 'Titipan', '2025-07-03', 'pcs', NULL, '1', NULL, NULL, '2025-07-03 04:56:14', '2025-07-03 04:56:14'),
(14, 'Jus Jambu', NULL, 1, 'Racikan', NULL, 'pcs', NULL, '0', NULL, NULL, '2025-07-03 04:56:41', '2025-07-03 04:56:41'),
(15, 'Gorengan', 'produk20250704081620.png', 2, 'Titipan', '2025-07-04', 'pcs', NULL, '1', 1500, 2000.00, '2025-07-04 01:16:21', '2025-07-04 01:16:21'),
(16, 'Chocomalt', 'produk20250704091533.png', NULL, 'Racikan', NULL, 'pcs', NULL, '0', 12000, 10000.00, '2025-07-04 02:15:33', '2025-07-04 02:15:33');

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
(1, NULL, 2, 59, 'liter', '2025-07-01 06:14:07', '2025-07-01 15:49:00'),
(2, NULL, 2, 50, 'liter', '2025-07-01 06:14:52', '2025-07-01 06:14:52'),
(3, NULL, 2, 67, 'liter', '2025-07-01 06:24:11', '2025-07-01 06:24:11'),
(4, NULL, 2, 67, 'liter', '2025-07-01 16:18:53', '2025-07-01 16:18:53'),
(5, NULL, 3, NULL, NULL, '2025-07-03 01:13:00', '2025-07-03 01:13:00'),
(6, 8, 2, NULL, NULL, '2025-07-03 01:15:37', '2025-07-03 01:15:37'),
(8, 3, 6, 1, 'porsi', '2025-07-04 01:15:03', '2025-07-04 01:15:03'),
(9, 16, 8, 10, 'ml', '2025-07-04 02:16:11', '2025-07-04 02:16:11'),
(10, 16, 2, 5, 'ml', '2025-07-04 02:16:44', '2025-07-04 02:16:44'),
(11, 15, 7, 1, 'porsi', '2025-07-04 05:48:28', '2025-07-04 05:48:28');

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
('dkAWPkszVEbSVJXj9h1FotgBhNs4mcilDL6KkmtA', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNE96RTdDSlF5MXFQTkNoNm1sM3pvamhaVEhxeGZnUlhjU2RpYjBkTSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDEvYmFoYW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1751635592);

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
(1, 'Mba Rini', '087245876564', 'Jln. Kimangun Sarkoro, Beji, Boyolangu, Tulungagung', NULL, NULL),
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
  MODIFY `idpembeliandtl` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `pembelians`
--
ALTER TABLE `pembelians`
  MODIFY `idpembelian` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `penjualandtls`
--
ALTER TABLE `penjualandtls`
  MODIFY `idpenjualan` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `penjualans`
--
ALTER TABLE `penjualans`
  MODIFY `idpenjualan` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `produks`
--
ALTER TABLE `produks`
  MODIFY `idproduk` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `produk_racikans`
--
ALTER TABLE `produk_racikans`
  MODIFY `idproduk_racikan` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `retur_titipan`
--
ALTER TABLE `retur_titipan`
  MODIFY `idretur_titipan` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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

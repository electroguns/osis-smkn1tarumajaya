-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 28, 2026 at 12:09 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `m_osissmkn1tarumajaya`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_reports`
--

CREATE TABLE `activity_reports` (
  `id` bigint UNSIGNED NOT NULL,
  `activity_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity_date` date NOT NULL,
  `document_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('purpose','unpurpose') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'purpose',
  `status` enum('draft','purpose','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `division` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `arsips`
--

CREATE TABLE `arsips` (
  `id` bigint UNSIGNED NOT NULL,
  `tahun` year NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` text COLLATE utf8mb4_unicode_ci,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint UNSIGNED NOT NULL,
  `event_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `scanned_at` timestamp NOT NULL,
  `status` enum('hadir','terlambat','izin','sakit','alfa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'alfa',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `beritas`
--

CREATE TABLE `beritas` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` longtext COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `author_id` bigint UNSIGNED DEFAULT NULL,
  `divisi` enum('inti','humas','artmedia','olahraga','agama') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'humas',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `event_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `batas_waktu_absen` int NOT NULL DEFAULT '15',
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `is_geofencing` tinyint(1) NOT NULL DEFAULT '1',
  `reminder_sent` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `finances`
--

CREATE TABLE `finances` (
  `id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `divisi` enum('inti','humas','artmedia','olahraga','agama') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` enum('pemasukan','pengeluaran') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nominal` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
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
-- Table structure for table `job_batches`
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
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_05_07_140507_add_role_to_users_table', 1),
(5, '2026_05_10_112532_create_pendaftaran_osis_table', 1),
(6, '2026_05_11_012525_create_suaras_table', 1),
(7, '2026_05_11_140404_create_arsips_table', 1),
(8, '2026_05_15_000000_create_struktur_penguruses_table', 1),
(9, '2026_05_15_100000_add_divisi_to_struktur_penguruses_table', 1),
(10, '2026_05_15_120000_add_foto_to_struktur_penguruses_table', 1),
(11, '2026_05_16_000000_add_user_id_to_struktur_penguruses_table', 1),
(12, '2026_05_16_010000_drop_password_from_struktur_penguruses_table', 1),
(13, '2026_05_16_020000_cascade_delete_user_id_on_struktur_penguruses', 1),
(14, '2026_05_16_030000_expand_role_enum_in_users_table', 1),
(15, '2026_05_16_160727_update_role_column_in_users_table', 1),
(16, '2026_05_16_161404_drop_divisi_from_struktur_pengururses_table', 1),
(17, '2026_05_16_162021_merge_struktur_pengurus_into_users_table', 1),
(18, '2026_05_16_165538_drop_jabatan_from_users_table', 1),
(19, '2026_05_18_000000_create_vision_missions_table', 1),
(20, '2026_05_18_010000_update_vision_missions_table', 1),
(21, '2026_05_18_065250_add_status_to_pendaftaran_osis_table', 1),
(22, '2026_05_19_121809_add__analisiai__pendaftar', 1),
(23, '2026_05_19_122613_edit__analisiai__pendaftar', 1),
(24, '2026_05_23_141740_create_beritas_table', 1),
(25, '2026_05_24_094429_create_subscribers_table', 1),
(26, '2026_05_25_170255_create_events_table', 1),
(27, '2026_05_25_170312_add_qr_token_to_users_table', 1),
(28, '2026_05_25_170338_create_attendances_table', 1),
(29, '2026_05_27_085629_ubah_kolom_role_di_tabel_users', 1),
(30, '2026_06_02_121502_add_kolom_to_arsips_table', 1),
(31, '2026_06_02_122140_add_status_to_suaras_table', 1),
(32, '2026_06_02_122244_remove_status_from_arsips_table', 1),
(33, '2026_06_02_191805_add_rejection_reason_to_beritas_table', 1),
(34, '2026_06_02_221221_add_status_to_users_table', 1),
(35, '2026_06_07_214535_create_activity_reports_table', 1),
(36, '2026_06_07_215927_add_rejection_reason_to_activity_reports_table', 1),
(37, '2026_06_09_102132_update_status_enum_in_activity_reports', 1),
(38, '2026_06_11_223707_create_finances_table', 1),
(39, '2026_06_11_224654_alter_divisi_in_finances_table', 1),
(40, '2026_06_26_194041_add_divisi_to_beritas_table', 1),
(41, '2026_06_27_094005_add_batas_waktu_absen_to_events_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran_osis`
--

CREATE TABLE `pendaftaran_osis` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `divisi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `motivasi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `analisis_ai` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `hard_skills` json DEFAULT NULL,
  `soft_skills` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suaras`
--

CREATE TABLE `suaras` (
  `id` bigint UNSIGNED NOT NULL,
  `rating` int DEFAULT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `saran` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','on_progress','resolved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qr_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('ketua','wakil','sekretaris','bendahara','ketua_divisi_humas','ketua_divisi_agama','ketua_divisi_olahraga','ketua_divisi_artmedia','anggota_divisi_humas','anggota_divisi_agama','anggota_divisi_olahraga','anggota_divisi_artmedia') COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `qr_token`, `role`, `kelas`, `foto`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Ashley Iraola', 'ashleyiraola10@gmail.com', '2026-06-28 12:08:40', '$2y$12$HZOQ4c36WxxHZr0y.y7auuoD6X3jZjIm4379fSOf7z4sLKyjblg06', NULL, 'ketua', 'XI KIMIA A', NULL, NULL, '2026-06-28 12:08:41', '2026-06-28 12:08:41'),
(2, 'Zahratussita', 'zahratussita118@gmail.com', '2026-06-28 12:08:41', '$2y$12$5F0wPPsjiCvYnlOVqZlfTek5n9RZvBuTlEt0HxLkXTSQvXSILLwEG', NULL, 'wakil', 'XI KIMIA B', NULL, NULL, '2026-06-28 12:08:41', '2026-06-28 12:08:41'),
(3, 'Cantik Nur Anggraini Fitriyah', 'cacantik1599@gmail.com', '2026-06-28 12:08:41', '$2y$12$gyOVaSRFWjRj6d3SDe3dUOKsLLSdrMgeTCHkAfDJwZLrqxVA37QYS', NULL, 'sekretaris', 'XI TKJ B', NULL, NULL, '2026-06-28 12:08:41', '2026-06-28 12:08:41'),
(4, 'Sheila Dwi Raharti', 'sheiladwiraharti170309@gmail.com', '2026-06-28 12:08:41', '$2y$12$WiEZiJa3rms.3249Ld10fOXWHGSF2Z70cjrgD8g/wRQpUfTuDJWQG', NULL, 'bendahara', 'XI TKJ A', NULL, NULL, '2026-06-28 12:08:42', '2026-06-28 12:08:42'),
(5, 'Muhammad Baabul Multazam', 'bymax176@gmail.com', '2026-06-28 12:08:42', '$2y$12$BG7oeGE13ZixmTtUdF.hGO4BWDg6GCdx1I3cPWIxTEe38yzZjuN1W', NULL, 'ketua_divisi_humas', 'XI TKJ B', NULL, NULL, '2026-06-28 12:08:42', '2026-06-28 12:08:42'),
(6, 'Najwa Anisah', 'juwaaajuweeyy@gmail.com', '2026-06-28 12:08:42', '$2y$12$Sp/8CkdRN7uHrBxgNiqJNeqzXqbXcpu4KbbDvDw5MwetuwFNUgm8e', NULL, 'anggota_divisi_humas', 'XI TKJ B', NULL, NULL, '2026-06-28 12:08:42', '2026-06-28 12:08:42');

-- --------------------------------------------------------

--
-- Table structure for table `vision_missions`
--

CREATE TABLE `vision_missions` (
  `id` bigint UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_reports`
--
ALTER TABLE `activity_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_reports_user_id_foreign` (`user_id`);

--
-- Indexes for table `arsips`
--
ALTER TABLE `arsips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendances_event_id_foreign` (`event_id`),
  ADD KEY `attendances_user_id_foreign` (`user_id`);

--
-- Indexes for table `beritas`
--
ALTER TABLE `beritas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `beritas_slug_unique` (`slug`),
  ADD KEY `beritas_author_id_foreign` (`author_id`),
  ADD KEY `beritas_status_index` (`status`),
  ADD KEY `beritas_published_at_index` (`published_at`),
  ADD KEY `beritas_slug_index` (`slug`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `finances`
--
ALTER TABLE `finances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pendaftaran_osis`
--
ALTER TABLE `pendaftaran_osis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pendaftaran_osis_email_unique` (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `suaras`
--
ALTER TABLE `suaras`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscribers_email_unique` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_qr_token_unique` (`qr_token`);

--
-- Indexes for table `vision_missions`
--
ALTER TABLE `vision_missions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_reports`
--
ALTER TABLE `activity_reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `arsips`
--
ALTER TABLE `arsips`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `beritas`
--
ALTER TABLE `beritas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `finances`
--
ALTER TABLE `finances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `pendaftaran_osis`
--
ALTER TABLE `pendaftaran_osis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suaras`
--
ALTER TABLE `suaras`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vision_missions`
--
ALTER TABLE `vision_missions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_reports`
--
ALTER TABLE `activity_reports`
  ADD CONSTRAINT `activity_reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `beritas`
--
ALTER TABLE `beritas`
  ADD CONSTRAINT `beritas_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

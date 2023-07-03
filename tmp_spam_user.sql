-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2023 at 09:23 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aaw2`
--

-- --------------------------------------------------------

--
-- Table structure for table `tmp_spam_user`
--

CREATE TABLE `tmp_spam_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` bigint(20) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` int(11) DEFAULT NULL,
  `place_berth` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_berth` date DEFAULT NULL,
  `blood_group` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marital_status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `religion` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `education_id` int(11) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `village_id` bigint(20) DEFAULT NULL,
  `rt` int(11) DEFAULT NULL,
  `rw` int(11) DEFAULT NULL,
  `phone_number` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `whatsapp` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ktp` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` int(11) DEFAULT 0,
  `cby` int(1) DEFAULT NULL,
  `saved_nasdem` int(1) DEFAULT 0,
  `activate_token` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(10) DEFAULT 0,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `set_admin` enum('Y','N') COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `reason` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tmp_spam_user`
--
ALTER TABLE `tmp_spam_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `village_id` (`village_id`),
  ADD KEY `cby` (`cby`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_id_2` (`user_id`),
  ADD KEY `nik` (`nik`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tmp_spam_user`
--
ALTER TABLE `tmp_spam_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2026 at 10:00 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `heathcare_emr`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-kishore@gmail.com|127.0.0.1', 'i:1;', 1770099687),
('laravel-cache-kishore@gmail.com|127.0.0.1:timer', 'i:1770099687;', 1770099687),
('laravel-cache-limesh@gmail.com|127.0.0.1', 'i:2;', 1770014800),
('laravel-cache-limesh@gmail.com|127.0.0.1:timer', 'i:1770014800;', 1770014800),
('laravel-cache-nurse@emr.gmail.com|127.0.0.1', 'i:1;', 1770026028),
('laravel-cache-nurse@emr.gmail.com|127.0.0.1:timer', 'i:1770026028;', 1770026028),
('laravel-cache-priyanka@gmail.com|127.0.0.1', 'i:1;', 1770084029),
('laravel-cache-priyanka@gmail.com|127.0.0.1:timer', 'i:1770084029;', 1770084029);

-- --------------------------------------------------------

--
-- Table structure for table `data_access_permissions`
--

CREATE TABLE `data_access_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `record_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT 0,
  `can_edit` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_access_permissions`
--

INSERT INTO `data_access_permissions` (`id`, `record_id`, `user_id`, `can_view`, `can_edit`, `created_at`, `updated_at`) VALUES
(1, 2, 7, 1, 0, '2026-02-02 03:37:09', '2026-02-02 03:37:09'),
(2, 2, 4, 1, 0, '2026-02-02 03:37:21', '2026-02-02 03:37:21');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_01_23_000001_create_users_table', 1),
(2, '2024_01_23_000002_create_roles_table', 1),
(3, '2024_01_23_000003_add_role_id_to_users_table', 1),
(4, '2024_01_23_000004_create_permissions_table', 1),
(5, '2024_01_23_000005_create_role_permissions_table', 1),
(6, '2024_01_23_000006_create_patient_medical_records_table', 1),
(7, '2024_01_23_000007_create_data_access_permissions_table', 1),
(8, '2024_01_23_000008_create_password_reset_tokens_table', 1),
(9, '2024_01_23_000009_create_personal_access_tokens_table', 1),
(10, '2024_01_23_000010_create_failed_jobs_table', 1),
(11, '2024_01_31_add_patient_details_to_users_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_medical_records`
--

CREATE TABLE `patient_medical_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `medical_history` text DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `prescription` text DEFAULT NULL,
  `lab_results` text DEFAULT NULL,
  `blood_pressure` varchar(255) DEFAULT NULL,
  `temperature` varchar(255) DEFAULT NULL,
  `pulse_rate` varchar(255) DEFAULT NULL,
  `weight` varchar(255) DEFAULT NULL,
  `height` varchar(255) DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `visibility_level` enum('private','restricted','public') NOT NULL DEFAULT 'private',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patient_medical_records`
--

INSERT INTO `patient_medical_records` (`id`, `patient_id`, `doctor_id`, `medical_history`, `diagnosis`, `prescription`, `lab_results`, `blood_pressure`, `temperature`, `pulse_rate`, `weight`, `height`, `allergies`, `notes`, `visibility_level`, `created_at`, `updated_at`) VALUES
(1, 6, 2, 'nothing', NULL, NULL, NULL, '89', NULL, NULL, '90', '98', '99', '99', 'public', '2026-02-02 01:00:22', '2026-02-02 01:00:22'),
(2, 8, 1, 'nothing', NULL, NULL, 'brain disorder', '12', NULL, NULL, '23', '23', '23', '213', 'public', '2026-02-02 01:51:06', '2026-02-04 00:25:47'),
(3, 12, 11, 'nothing', NULL, NULL, NULL, '140/90', NULL, NULL, '45', '6', 'nothing', 'nothing', 'public', '2026-02-03 00:47:15', '2026-02-03 00:47:15'),
(4, 13, 1, '54', '454', '45', '54', '140/90', '55', '54', '45', '45', '45', '54', 'public', '2026-02-03 03:30:59', '2026-02-03 03:30:59'),
(5, 16, 2, 'nothing', NULL, NULL, NULL, '130/85', NULL, NULL, '24', '324', '2234', 'nothing', 'public', '2026-02-03 21:20:57', '2026-02-03 21:20:57'),
(6, 12, 20, '88', '88', '88', '88', 'df', '77', '8787', '8787', '878', '88', '88', 'public', '2026-02-04 03:06:49', '2026-02-04 03:06:49'),
(7, 21, 20, 'gjgjgj', NULL, NULL, NULL, 'uu', NULL, NULL, '88', '8778', '7878', '7878', 'public', '2026-02-04 03:08:28', '2026-02-04 03:08:28');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'View Medical Records', 'view-medical-records', '2026-02-02 00:56:14', '2026-02-02 00:56:14'),
(2, 'Edit Medical Records', 'edit-medical-records', '2026-02-02 00:56:14', '2026-02-02 00:56:14'),
(3, 'View Lab Results', 'view-lab-results', '2026-02-02 00:56:14', '2026-02-02 00:56:14'),
(4, 'Add Lab Results', 'add-lab-results', '2026-02-02 00:56:14', '2026-02-02 00:56:14'),
(5, 'View Prescriptions', 'view-prescriptions', '2026-02-02 00:56:14', '2026-02-02 00:56:14'),
(6, 'Add Prescriptions', 'add-prescriptions', '2026-02-02 00:56:14', '2026-02-02 00:56:14'),
(7, 'View Patient History', 'view-patient-history', '2026-02-02 00:56:14', '2026-02-02 00:56:14'),
(8, 'Manage Users', 'manage-users', '2026-02-02 00:56:14', '2026-02-02 00:56:14'),
(9, 'Manage Permissions', 'manage-permissions', '2026-02-02 00:56:14', '2026-02-02 00:56:14'),
(10, 'View All Medical Records', 'view-all-medical-records', '2026-02-02 00:56:14', '2026-02-02 00:56:14'),
(11, 'Edit All Medical Records', 'edit-all-medical-records', '2026-02-02 00:56:14', '2026-02-02 00:56:14'),
(12, 'Delete All Medical Records', 'delete-all-medical-records', '2026-02-02 00:56:14', '2026-02-02 00:56:14'),
(13, 'View Dashboard', 'view-dashboard', '2026-02-02 00:56:16', '2026-02-02 00:56:16'),
(14, 'Create Medical Records', 'create-medical-records', '2026-02-02 00:56:16', '2026-02-02 00:56:16'),
(15, 'Delete Medical Records', 'delete-medical-records', '2026-02-02 00:56:16', '2026-02-02 00:56:16'),
(16, 'Manage Medical Records Permissions', 'manage-medical-records-permissions', '2026-02-02 00:56:16', '2026-02-02 00:56:16'),
(17, 'Edit Lab Results', 'edit-lab-results', '2026-02-02 00:56:16', '2026-02-02 00:56:16'),
(18, 'Delete Lab Results', 'delete-lab-results', '2026-02-02 00:56:16', '2026-02-02 00:56:16'),
(19, 'Export Data', 'export-data', '2026-02-02 00:56:16', '2026-02-02 00:56:16'),
(20, 'View System Analytics', 'view-system-analytics', '2026-02-02 00:56:16', '2026-02-02 00:56:16'),
(21, 'Manage Data Access', 'manage-data-access', '2026-02-02 00:56:16', '2026-02-02 00:56:16');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'System Admin', 'system-admin', '2026-02-02 00:56:14', '2026-02-02 00:56:14'),
(2, 'Doctor', 'doctor', '2026-02-02 00:56:14', '2026-02-02 00:56:14'),
(3, 'Nurse', 'nurse', '2026-02-02 00:56:14', '2026-02-02 00:56:14'),
(4, 'Lab Technician', 'lab-technician', '2026-02-02 00:56:14', '2026-02-02 00:56:14'),
(5, 'Patient', 'patient', '2026-02-02 00:56:14', '2026-02-02 00:56:14'),
(7, 'manager', 'manager', '2026-02-03 04:55:22', '2026-02-03 04:55:22'),
(10, 'ui', 'teacher', '2026-02-03 05:57:55', '2026-02-03 23:37:05'),
(11, 'dummy', 'dummy-testing', '2026-02-04 00:29:06', '2026-02-04 00:29:06'),
(12, 'testing', 'testing', '2026-02-04 01:31:39', '2026-02-04 01:31:39'),
(13, 'security', 'security', '2026-02-04 02:52:37', '2026-02-04 02:52:37');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, 13, NULL, NULL),
(2, 1, 1, NULL, NULL),
(3, 1, 14, NULL, NULL),
(4, 1, 2, NULL, NULL),
(5, 1, 15, NULL, NULL),
(6, 1, 16, NULL, NULL),
(7, 1, 3, NULL, NULL),
(8, 1, 4, NULL, NULL),
(9, 1, 17, NULL, NULL),
(10, 1, 18, NULL, NULL),
(11, 1, 19, NULL, NULL),
(12, 1, 8, NULL, NULL),
(13, 1, 9, NULL, NULL),
(14, 1, 20, NULL, NULL),
(15, 1, 21, NULL, NULL),
(16, 2, 13, NULL, NULL),
(17, 2, 1, NULL, NULL),
(18, 2, 14, NULL, NULL),
(19, 2, 2, NULL, NULL),
(20, 2, 3, NULL, NULL),
(21, 2, 19, NULL, NULL),
(22, 3, 13, NULL, NULL),
(23, 3, 1, NULL, NULL),
(24, 3, 2, NULL, NULL),
(25, 3, 3, NULL, NULL),
(26, 3, 19, NULL, NULL),
(27, 4, 13, NULL, NULL),
(28, 4, 3, NULL, NULL),
(29, 4, 4, NULL, NULL),
(30, 4, 17, NULL, NULL),
(31, 4, 19, NULL, NULL),
(33, 5, 1, NULL, NULL),
(34, 5, 3, NULL, NULL),
(35, 2, 15, NULL, NULL),
(36, 2, 4, NULL, NULL),
(37, 2, 17, NULL, NULL),
(38, 2, 5, NULL, NULL),
(39, 2, 6, NULL, NULL),
(40, 2, 7, NULL, NULL),
(42, 3, 15, NULL, NULL),
(57, 5, 5, NULL, NULL),
(58, 7, 1, NULL, NULL),
(65, 10, 14, NULL, NULL),
(67, 10, 1, NULL, NULL),
(68, 10, 2, NULL, NULL),
(69, 10, 19, NULL, NULL),
(70, 11, 1, NULL, NULL),
(71, 11, 3, NULL, NULL),
(72, 11, 4, NULL, NULL),
(75, 12, 4, NULL, NULL),
(79, 12, 1, NULL, NULL),
(80, 12, 14, NULL, NULL),
(81, 12, 2, NULL, NULL),
(82, 12, 5, NULL, NULL),
(83, 12, 6, NULL, NULL),
(84, 12, 7, NULL, NULL),
(85, 7, 14, NULL, NULL),
(86, 7, 2, NULL, NULL),
(87, 7, 15, NULL, NULL),
(88, 13, 1, NULL, NULL),
(89, 13, 14, NULL, NULL),
(90, 13, 2, NULL, NULL),
(91, 13, 15, NULL, NULL),
(92, 13, 13, NULL, NULL),
(93, 13, 19, NULL, NULL),
(94, 1, 10, NULL, NULL),
(95, 5, 13, NULL, NULL),
(96, 13, 10, NULL, NULL),
(97, 13, 3, NULL, NULL),
(99, 13, 17, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1wbcfTknq4Oo1EAsZQDt0t1oNmIZ78TIa1X8onGo', NULL, '127.0.0.1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYzhLSmNVTnNnTU5vR3FxWnpOT2dPd1VjY3BOZ0UySkpRd29Bd2luRiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1754647239);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL DEFAULT 5,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `date_of_birth`, `gender`, `blood_group`, `address`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'System Admin', 'admin@emr.com', '2026-02-02 00:56:15', '$2y$12$sDHBMdfbRApCMNtoTxs5GOMpzod6yK7lIzwh/shk4qW5pcyRW6CbG', NULL, NULL, NULL, NULL, NULL, 'N5OJgC5frP7kSQ3OIwlHZZ1NfmHbxGfr1yiUYVJ9KpqpsnO2KAyXqpjFqVTt', '2026-02-02 00:56:15', '2026-02-02 00:56:15'),
(2, 2, 'Dr. John Smith', 'doctor@emr.com', '2026-02-02 00:56:15', '$2y$12$HNEH3sSSCGJFwySDmwGLZOWu08z3uUDB422bgFeOBxbIMrlYoakjC', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-02 00:56:15', '2026-02-02 00:56:15'),
(3, 3, 'Nurse Jane Williams', 'nurse@emr.com', '2026-02-02 00:56:15', '$2y$12$vcoaq0ZHYPjtr54lw21jJOWQohC4icmAuK4sy52Io9a8xH8W4q1Um', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-02 00:56:15', '2026-02-02 00:56:15'),
(4, 4, 'Lab Tech Mike Brown', 'lab@emr.com', '2026-02-02 00:56:16', '$2y$12$G2PD3Ba1aaGu2cr0.cplLuinAlcpK0ZqSJtZoxB7WevZL4m95CGo.', NULL, NULL, NULL, NULL, NULL, 'uPulIv2KDqlnw6S1hTGJZM0kZDh7bhX7xSBiH4LYqA2dmNDnVGRwrXquaqe3', '2026-02-02 00:56:16', '2026-02-02 00:56:16'),
(5, 5, 'Patient Sarah Johnson', 'patient@emr.com', '2026-02-02 00:56:16', '$2y$12$HKEFfOE345Rpzf1iwX.ee.YbDXY6Wlo1yaoNz8AGbGVxP/b3IqSWW', NULL, NULL, NULL, NULL, NULL, 'vz4hqaD8yfNw9JXZ8Da2bRyx4mm9mFzuEO3IDzMz6BxoWVEvLYPr2h3tL1Qq', '2026-02-02 00:56:16', '2026-02-02 00:56:16'),
(6, 5, 'limesh fulchand naikwar', 'limesh@gmail.com', NULL, '$2y$12$qH1oXTRMYvFNRB7ELR3veep1tngS2E4RabbzrDdHK2LteN7yyICBK', '(123) 456-7890', '2005-02-02', 'male', 'B+', 'deolapar', NULL, '2026-02-02 01:00:22', '2026-02-02 01:00:22'),
(7, 2, 'muskan', 'muskan@gmail.com', NULL, '$2y$12$EF/eqc6TX886s2Kc6/TyJ.1eDoNSK5HEPzaAwf6V2NpEtj.vL57NC', NULL, NULL, NULL, NULL, NULL, 'Y5Yv08SAcnI4SxYr7EcE56gUZ2YxCs26dgRWUjdbvDEh28hFyGiUbfFOKvsq', '2026-02-02 01:47:40', '2026-02-02 01:47:40'),
(8, 5, 'priyanka', 'priyanka@gmail.com', NULL, '$2y$12$OZqVCwGLq0xQKL7GoXk8p.qK0i92Rb7VmwqkaxdSJZY46c5C1vhwK', '(794) 615-4698', '2002-04-04', 'female', 'O+', 'khawasa', NULL, '2026-02-02 01:51:06', '2026-02-02 01:51:06'),
(9, 3, 'neha', 'neha@gmail.com', NULL, '$2y$12$B2xG6EH4hp2khC8KZNYIi.WFP9BABstW8HqiM7au9V4dDrg4hku4K', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-03 00:24:52', '2026-02-03 00:24:52'),
(10, 4, 'vishal', 'vishal@gmail.com', NULL, '$2y$12$DEtgNgni6XPGXJn8xZ0iWuGB7pYQwQtoyGS7lci0IPI1M0iUEAM2O', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-03 00:34:56', '2026-02-03 00:34:56'),
(11, 2, 'rambiraj', 'rambiraj@gmail.com', NULL, '$2y$12$H0jO06aumiO5pDXYGMe8euK0w45A4HRxVJdAJrZDuL0vd7JJQefFG', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-03 00:38:21', '2026-02-03 00:38:21'),
(12, 5, 'kishore', 'kishore@gmail.com', NULL, '$2y$12$sAQeoVsGTqfMogK1tL1dRe0CEVF5c8THLo/9Esxyx.7/DPPbLfgMq', '(741) 852-9630', '2015-02-13', 'male', 'O+', 'bhandara', NULL, '2026-02-03 00:47:15', '2026-02-03 00:47:15'),
(13, 5, 'vikash', 'vikash@gmail.com', NULL, '$2y$12$64E.5FYnOY.l9H.tVDIXyOUJi18arJwsQaHOMDf9lm4NmXOBhCnm2', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-03 03:29:39', '2026-02-03 03:29:39'),
(14, 7, 'dddddddd', 'dddddddd@gmail.com', NULL, '$2y$12$tUFpdR0yYD2hjpYs54hg8./XNq0HUbHExBstUSgyIpczH0AevIlaG', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-03 05:49:12', '2026-02-03 05:49:12'),
(15, 10, 'rakesh', 'rakesh@gmail.com', NULL, '$2y$12$PAn8.1ioIFEgdlShYyGCtuK8u1LKBzXF7HmSVJGJqUsQmbROf7Xb.', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-03 05:59:06', '2026-02-03 05:59:06'),
(16, 5, 'shayam', 'shayam@gmail.com', NULL, '$2y$12$3K1JsXMjY/MpmPueYEtFM.ED5ws1aNKardsM2vnjcAaJm2lZCy5ny', '(741) 852-9630', '2002-01-04', 'male', 'B-', 'tekanaka', NULL, '2026-02-03 21:20:57', '2026-02-03 21:20:57'),
(17, 10, 'jhon sena', 'jhon@gmail.com', NULL, '$2y$12$K7RlGjDtM5hQ09wLzHAlQu.NO4brPihdcKKVF8judS8JW0Nr3AP3m', NULL, NULL, NULL, NULL, NULL, 'ERS6o4d48HadGBbW1R8xgR0C2b7Mhyu7wbfX4fqUGYjSB9rnJns36Njyatvh', '2026-02-03 21:24:06', '2026-02-03 21:24:06'),
(18, 11, 'limesh fulchand naikwar', 'limesh1@gmail.com', NULL, '$2y$12$x5kuJxOa4qmovf1w5mZrzeeFg8EctMBvFPk8QcP.hAhie3GlVOdAO', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-04 00:40:53', '2026-02-04 00:40:53'),
(19, 12, 'tester', 'tester@gmail.com', NULL, '$2y$12$ir0jqut97vpJ8nuzKWslou6xBzwluAR25CsNvKsIXQ8Tle/tECIYO', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-04 01:33:20', '2026-02-04 01:33:20'),
(20, 13, 'security', 'security@gmail.com', NULL, '$2y$12$p7eGb78jES5YWkK2dUG4QuaHf3Lhr0HfHMOvg/CnRZMtmifwxCIG6', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-04 02:53:26', '2026-02-04 02:53:26'),
(21, 5, 'dfsd', 'rakesh1@gmail.com', NULL, '$2y$12$BVMpa7BhPiWeuL1rXC8wrOse36vCkdkouYDDHIuu.EVwS4/PxWYNG', '(123) 456-7890', '2023-02-04', 'male', 'O+', 'wew', NULL, '2026-02-04 03:08:28', '2026-02-04 03:08:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `data_access_permissions`
--
ALTER TABLE `data_access_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `data_access_permissions_record_id_user_id_unique` (`record_id`,`user_id`),
  ADD KEY `data_access_permissions_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `patient_medical_records`
--
ALTER TABLE `patient_medical_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_medical_records_patient_id_foreign` (`patient_id`),
  ADD KEY `patient_medical_records_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_slug_unique` (`slug`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_permissions_role_id_permission_id_unique` (`role_id`,`permission_id`),
  ADD KEY `role_permissions_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_access_permissions`
--
ALTER TABLE `data_access_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `patient_medical_records`
--
ALTER TABLE `patient_medical_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `data_access_permissions`
--
ALTER TABLE `data_access_permissions`
  ADD CONSTRAINT `data_access_permissions_record_id_foreign` FOREIGN KEY (`record_id`) REFERENCES `patient_medical_records` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `data_access_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patient_medical_records`
--
ALTER TABLE `patient_medical_records`
  ADD CONSTRAINT `patient_medical_records_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `patient_medical_records_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

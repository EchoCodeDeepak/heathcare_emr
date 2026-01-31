-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 31, 2026 at 10:49 AM
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
('laravel-cache-babu@gmail.com|127.0.0.1', 'i:1;', 1769839841),
('laravel-cache-babu@gmail.com|127.0.0.1:timer', 'i:1769839841;', 1769839841),
('laravel-cache-pooja@gmail.com|127.0.0.1', 'i:1;', 1769839871),
('laravel-cache-pooja@gmail.com|127.0.0.1:timer', 'i:1769839871;', 1769839871),
('laravel-cache-purvi@gmail.com|127.0.0.1', 'i:1;', 1769826866),
('laravel-cache-purvi@gmail.com|127.0.0.1:timer', 'i:1769826866;', 1769826866);

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
(1, 29, 1, 1, 1, '2026-01-31 00:16:17', '2026-01-31 00:16:17');

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
(11, '2024_01_31_add_patient_details_to_users_table', 2);

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
(16, 1, 2, 'Diabetes for 5 years', 'Type 2 Diabetes', 'Metformin 500mg', 'HbA1c: 7.2%', '130/85', '98.6', '72', '70', '170', 'None', 'Regular follow-up needed', '', '2026-01-30 13:13:53', '2026-01-30 13:13:53'),
(17, 2, 3, 'Hypertension', 'High Blood Pressure', 'Amlodipine 5mg', 'Cholesterol Normal', '140/90', '98.4', '75', '68', '168', 'Penicillin', 'Monitor BP daily', '', '2026-01-30 13:13:53', '2026-01-30 13:13:53'),
(18, 5, 2, 'Diabetes for 3 years', 'Type 2 Diabetes', 'Metformin 500mg', 'HbA1c: 7.1%', '130/85', '98.6', '72', '68', '16', 'None', 'Regular sugar monitoring', 'public', '2026-01-30 13:17:19', '2026-01-30 07:49:18'),
(19, 5, 2, 'High BP history', 'Hypertension', 'Amlodipine 5mg', 'Cholesterol Normal', '140/90', '98.4', '75', '70', '165', 'None', 'Reduce salt intake', 'public', '2026-01-30 13:17:19', '2026-01-30 20:45:30'),
(20, 6, 2, 'Asthma since childhood', 'Bronchial Asthma', 'Salbutamol Inhaler', 'Chest X-ray Clear', '120/80', '98.7', '78', '65', '170', 'Dust', 'Avoid allergens', '', '2026-01-30 13:17:19', '2026-01-30 13:17:19'),
(21, 6, 2, 'Seasonal fever', 'Viral Fever', 'Paracetamol', 'CBC Normal', '118/76', '99.1', '80', '64', '170', 'None', 'Rest and hydration', 'public', '2026-01-30 13:17:19', '2026-01-30 13:17:19'),
(22, 5, 2, 'Joint pain', 'Osteoarthritis', 'Calcium + Vitamin D', 'X-ray Mild Degeneration', '132/86', '98.2', '71', '69', '165', 'None', 'Daily exercise advised', 'public', '2026-01-30 13:17:19', '2026-01-30 20:45:52'),
(23, 6, 2, 'Gastric issues', 'Gastritis', 'Omeprazole', 'Endoscopy Mild Inflammation', '120/78', '98.0', '69', '62', '170', 'Spicy Food', 'Avoid oily food', 'public', '2026-01-30 13:17:19', '2026-01-30 20:46:06'),
(24, 5, 2, 'Fatigue complaint', 'Vitamin D Deficiency', 'Vitamin D Supplements', 'Vitamin D Low', '124/80', '98.3', '70', '67', '165', 'None', 'Sunlight exposure advised', 'public', '2026-01-30 13:17:19', '2026-01-30 13:17:19'),
(25, 8, 2, 'fsf', NULL, NULL, NULL, 'kjkjk', NULL, NULL, '23', '9', '9898', '99', 'public', '2026-01-30 19:42:17', '2026-01-30 19:42:17'),
(28, 11, 2, 'jai mata di', 'hi', 'hi', 'hi', '3244', NULL, NULL, '667', '7867', 'hi', '2003', 'public', '2026-01-30 20:39:13', '2026-01-30 20:42:03'),
(29, 13, 2, 'testing', 'edit check', 'edit check', 'edit check', '12', NULL, NULL, '23', '23', 'check permission', '24234', 'public', '2026-01-30 23:54:41', '2026-01-31 00:17:33'),
(30, 14, 1, 'bimar admin', 'check update', NULL, NULL, '768', NULL, NULL, '8778', '8', '87', '887', 'public', '2026-01-31 00:20:43', '2026-01-31 00:21:42'),
(31, 16, 2, 'wwww', NULL, NULL, NULL, 'ww', NULL, NULL, '89', '9898', '98', '98', 'public', '2026-01-31 01:08:31', '2026-01-31 01:08:31'),
(32, 17, 2, 'limesh', NULL, NULL, NULL, '989', NULL, NULL, '99', '99', '090', '090', 'public', '2026-01-31 02:59:55', '2026-01-31 02:59:55'),
(33, 18, 2, 'sdfsdf', NULL, NULL, NULL, 'sdfs', NULL, NULL, '88', '888', '887', '88', 'public', '2026-01-31 03:37:21', '2026-01-31 03:37:21'),
(34, 19, 2, 'nothing', NULL, NULL, NULL, '130/85', NULL, NULL, '67', '90', 'rrt', 'dubey', 'public', '2026-01-31 03:52:31', '2026-01-31 03:52:31'),
(35, 20, 2, 'kkk', NULL, NULL, NULL, 'kk', NULL, NULL, '9', '90', '90', '000', 'public', '2026-01-31 04:10:28', '2026-01-31 04:10:28'),
(36, 15, 2, 'nothing', 'nothing', 'nothing', 'nothing', '130/85', '23', '22', '23', '213', 'nothing', 'nothing', 'public', '2026-01-31 04:12:12', '2026-01-31 04:12:12');

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
(1, 'View Medical Records', 'view-medical-records', '2026-01-30 06:05:21', '2026-01-30 06:05:21'),
(2, 'Edit Medical Records', 'edit-medical-records', '2026-01-30 06:05:21', '2026-01-30 06:05:21'),
(3, 'View Lab Results', 'view-lab-results', '2026-01-30 06:05:21', '2026-01-30 06:05:21'),
(4, 'Add Lab Results', 'add-lab-results', '2026-01-30 06:05:21', '2026-01-30 06:05:21'),
(5, 'View Prescriptions', 'view-prescriptions', '2026-01-30 06:05:21', '2026-01-30 06:05:21'),
(6, 'Add Prescriptions', 'add-prescriptions', '2026-01-30 06:05:21', '2026-01-30 06:05:21'),
(7, 'View Patient History', 'view-patient-history', '2026-01-30 06:05:21', '2026-01-30 06:05:21'),
(8, 'Manage Users', 'manage-users', '2026-01-30 06:05:21', '2026-01-30 06:05:21'),
(9, 'Manage Permissions', 'manage-permissions', '2026-01-30 06:05:21', '2026-01-30 06:05:21'),
(10, 'View All Medical Records', 'view-all-medical-records', '2026-01-30 06:05:21', '2026-01-30 06:05:21'),
(11, 'Edit All Medical Records', 'edit-all-medical-records', '2026-01-30 06:05:21', '2026-01-30 06:05:21'),
(12, 'Delete All Medical Records', 'delete-all-medical-records', '2026-01-30 06:05:21', '2026-01-30 06:05:21');

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
(1, 'System Admin', 'system-admin', '2026-01-30 06:05:21', '2026-01-30 06:05:21'),
(2, 'Doctor', 'doctor', '2026-01-30 06:05:21', '2026-01-30 06:05:21'),
(3, 'Nurse', 'nurse', '2026-01-30 06:05:21', '2026-01-30 06:05:21'),
(4, 'Lab Technician', 'lab-technician', '2026-01-30 06:05:21', '2026-01-30 06:05:21'),
(5, 'Patient', 'patient', '2026-01-30 06:05:21', '2026-01-30 06:05:21');

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
(2, 2, 1, NULL, NULL),
(3, 2, 2, NULL, NULL),
(4, 2, 3, NULL, NULL),
(5, 2, 4, NULL, NULL),
(6, 2, 5, NULL, NULL),
(7, 2, 6, NULL, NULL),
(8, 2, 7, NULL, NULL),
(9, 3, 1, NULL, NULL),
(10, 4, 1, NULL, NULL),
(13, 5, 1, NULL, NULL),
(14, 3, 7, NULL, NULL),
(15, 4, 4, NULL, NULL),
(16, 4, 3, NULL, NULL),
(17, 5, 3, NULL, NULL),
(18, 5, 5, NULL, NULL),
(19, 5, 7, NULL, NULL),
(20, 2, 8, NULL, NULL),
(21, 2, 9, NULL, NULL),
(22, 3, 3, NULL, NULL),
(23, 3, 5, NULL, NULL);

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
('HeeInMTGnn26N08BKcsiA9GC0Eg8CIMnO4n0AlYJ', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiT3lPaUFrZGtCT3FRWXZKeHg0MHoyZVRKZ3YzMUZrQUgwNktGbjFwayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tZWRpY2FsLXJlY29yZHMvY3JlYXRlIjtzOjU6InJvdXRlIjtzOjIyOiJtZWRpY2FsLXJlY29yZHMuY3JlYXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3Njk4MzY5MTI7fX0=', 1769844501),
('ivfIsrWw2QvhFHpz8wwLIt15mjEFRkj0MDEqdRmv', 15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZ25OVGw1V0xIMFBub3lYVlQxRXYwTVFFdUlqOElZaVJVa0U5a01TbiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tZWRpY2FsLXJlY29yZHMiO3M6NToicm91dGUiO3M6MjE6Im1lZGljYWwtcmVjb3Jkcy5pbmRleCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE1O30=', 1769844204),
('VCN2NeLfodNT5HD3DEek7RaxV8XCdtIzcai2PbVs', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoidnZsZjlKRTdjV2hVUm5LUWgxRTZmWmdobFFTMjFQc3c4cVFMT29oNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tZWRpY2FsLXJlY29yZHMiO3M6NToicm91dGUiO3M6MjE6Im1lZGljYWwtcmVjb3Jkcy5pbmRleCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzY5ODQyODk3O319', 1769852534);

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
(1, 1, 'System Admin', 'admin@emr.com', '2026-01-30 06:08:20', '$2y$12$ef8CGJoQsZndyyGooTOCdeFB0pEecnjWKM1u9iyH9tvcajnbEJxUa', NULL, NULL, NULL, NULL, NULL, 'Gc0Caf1r4K4eX3rTYbsUCRZigc8u2rC9MyxmhwppGC1ROPNeB9zI62i6vadS', '2026-01-30 06:08:21', '2026-01-30 06:08:21'),
(2, 2, 'Dr. John Smith', 'doctor@emr.com', '2026-01-30 06:08:21', '$2y$12$7nEM/HqSj00yk8k7sPCpVu90PYg8Vfs.enLHVvBtX0.yyTtuR4lzS', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-30 06:08:21', '2026-01-30 06:08:21'),
(3, 3, 'Nurse Jane Williams', 'nurse@emr.com', '2026-01-30 06:08:21', '$2y$12$8.iIRghGKsQXTtD3XJuwGOp2nS7gftCKkaSJ6p1l94ixwf4CLbE.a', NULL, NULL, NULL, NULL, NULL, 's0YEiT4xlF0cnaemJ6OS2ILThUH8xxWcbzoof6nNEQucMH75w3ohrIxSlPqk', '2026-01-30 06:08:21', '2026-01-30 06:08:21'),
(4, 4, 'Lab Tech Mike Brown', 'lab@emr.com', '2026-01-30 06:08:22', '$2y$12$lCi6I62zjXJ14We6Nr.Q0.9WjnRqiZwRGTIQ38BzWsSv1PnfAtJEG', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-30 06:08:22', '2026-01-30 06:08:22'),
(5, 5, 'Patient Sarah Johnson', 'patient@emr.com', '2026-01-30 06:08:22', '$2y$12$3TJft3EJl7w1AjO72J.TRePQjVjm8aaXQc50S6eTpEY9Iea1Z6zEu', NULL, NULL, NULL, NULL, NULL, '6nQ9d0BeHtg4hex80uGyIBPXwwSZ4ZVNplba4JZ6nwdU1Iqw5Y3pssycQucg', '2026-01-30 06:08:22', '2026-01-30 06:08:22'),
(6, 5, 'khilesh', 'khilesh@gmail.com', NULL, '$2y$12$Uas2tmNqPF2i5BmyKT7H4.gSv1DPO/BUE2WUOuWMrMYCQXl5KDbT6', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-30 07:04:39', '2026-01-30 07:04:39'),
(7, 4, 'mahendra', 'mahendra@gmail.com', NULL, '$2y$12$wfnUc4k..X33Azt8akMEP.QLMntP2SLm/WEDXBaajFtwchL7z2pV.', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-30 07:05:33', '2026-01-30 07:05:33'),
(8, 5, 'purvi', 'purvi@gmail.com', NULL, '$2y$12$F3abvrwIhJY6V1WF/JbHAOZ1BH5HTDq/k8wVRquOjN5EeQ1EeikFC', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-30 19:42:16', '2026-01-30 19:42:16'),
(11, 5, 'aashu', 'aashu@gmail.com', NULL, '$2y$12$0uUrnnqN2vC/YRxYE4f4XuSjnoYjsm8jBmcaRhZ1VrKnzl61cki62', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-30 20:39:13', '2026-01-30 20:39:13'),
(12, 5, 'neha', 'neha@gmail.com', NULL, '$2y$12$kQ2MrHA/S.B5BMXSoReJ5uuIYTvl3CwFWIbHGe5hTIdneisS6zXVu', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-30 21:05:33', '2026-01-30 21:05:33'),
(13, 5, 'pooja', 'pooja@gmail.com', NULL, '$2y$12$KzrPfhwU9u6PWpzv2OMsK.fcHiTbPBd3RNZRJ0Jh9lM6JJ73azfUu', '(901) 298-3465', '2021-02-28', 'female', 'O-', 'nagpur', NULL, '2026-01-30 23:54:41', '2026-01-30 23:54:41'),
(14, 5, 'babu', 'babu@gmail.com', NULL, '$2y$12$kVGkoO7cJMB2xD0zgsizPOvMBwsWjc/rs8cKuSOHbMrLbpbG.b952', '(908) 912-1332', '2010-12-29', 'female', 'A+', 'kharbi nagpur', NULL, '2026-01-31 00:20:43', '2026-01-31 00:20:43'),
(15, 5, 'prakash', 'prakash@gmail.com', NULL, '$2y$12$a0XTg7KuVEZaIlvj.qbkiuE97UpiXkB7DSvvYdlwpkmoW.uwyIa0G', NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-31 00:42:14', '2026-01-31 00:42:14'),
(16, 5, 'wwww', 'wwww@gmail.com', NULL, '$2y$12$JvAAOztREbCtUUkRORvtsOiaSfVlqrOdU.aNsoel7r4VGCiPDQDX6', '(123) 456-7890', '2003-12-12', 'male', 'B+', 'universe', NULL, '2026-01-31 01:08:31', '2026-01-31 01:08:31'),
(17, 5, 'limbu', 'limbu@gmail.com', NULL, '$2y$12$jT2DEiEH6bPRWumwOWGGbehHpM.uzj0mVvTV.5RdAbex1SbOnFAXG', '(777) 777-4185', '2025-12-17', 'male', 'O+', 'deolapar', NULL, '2026-01-31 02:59:55', '2026-01-31 02:59:55'),
(18, 5, 'lalu', 'lalu@gmail.com', NULL, '$2y$12$PCMpZ2vBnGyeY3wxCLKGOOp0QKfoGJxgsAwRA.7e2BU6jxgunG2ue', '(874) 589-6544', '2003-07-07', 'male', 'B-', 'bahadurra', NULL, '2026-01-31 03:37:21', '2026-01-31 03:37:21'),
(19, 5, 'dubey', 'dubey@gmail.com', NULL, '$2y$12$GPWJPHmdzmKTmQSjUJ9XJOmH4c63vku1EQix7lbfupyZUdw.ayMPe', '(091) 243-9845', '2003-02-12', 'male', 'O+', 'bahadura', NULL, '2026-01-31 03:52:31', '2026-01-31 03:52:31'),
(20, 5, 'ppppp', 'ppppp@gmail.com', NULL, '$2y$12$r0mtCk5AbeRrpgsBTPW.0uWffZomjLphsKqHCH/0dgFjEYytnBdRS', '(129) 012-9033', '2003-02-22', 'male', 'O+', 'kkkkkk', NULL, '2026-01-31 04:10:28', '2026-01-31 04:10:28');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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

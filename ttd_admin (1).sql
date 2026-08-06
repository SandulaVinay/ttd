-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 02, 2026 at 01:21 PM
-- Server version: 8.0.46-0ubuntu0.24.04.3
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ttd_admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint UNSIGNED NOT NULL,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint UNSIGNED DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'default', 'created', 'App\\Models\\Revenue', 'created', 8, 'App\\Models\\User', 1, '{\"attributes\": {\"amount\": \"999.00\", \"source\": \"jhbjh\", \"remarks\": null, \"created_by\": 1, \"revenue_date\": \"2026-08-02\"}}', NULL, '2026-08-02 09:08:48', '2026-08-02 09:08:48');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `devotee_id` bigint UNSIGNED NOT NULL,
  `booking_type_id` bigint UNSIGNED NOT NULL,
  `booking_date` date NOT NULL,
  `preferred_date` date DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_no`, `devotee_id`, `booking_type_id`, `booking_date`, `preferred_date`, `status`, `remarks`, `created_by`, `created_at`, `updated_at`) VALUES
(9, 'SGB-KXDTLTFO', 6, 1, '2026-11-21', NULL, 'completed', NULL, 3, '2026-07-29 12:37:05', '2026-08-02 13:13:31'),
(10, 'SGB-OOCI8PSK', 4, 4, '2026-12-18', NULL, 'pending', NULL, 3, '2026-07-29 12:37:22', '2026-08-02 13:13:40'),
(11, 'SGB-RTEUJQGQ', 4, 3, '2026-11-13', NULL, 'pending', 'Booked By Tarun', 1, '2026-08-02 12:48:18', '2026-08-02 12:48:18'),
(12, 'SGB-RQIAZSKA', 4, 1, '2026-08-02', NULL, 'pending', NULL, 3, '2026-08-02 13:01:22', '2026-08-02 13:01:22');

-- --------------------------------------------------------

--
-- Table structure for table `booking_devotee`
--

CREATE TABLE `booking_devotee` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `devotee_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_devotee`
--

INSERT INTO `booking_devotee` (`id`, `booking_id`, `devotee_id`, `created_at`, `updated_at`) VALUES
(9, 9, 6, NULL, NULL),
(10, 9, 8, NULL, NULL),
(11, 9, 9, NULL, NULL),
(12, 9, 10, NULL, NULL),
(13, 10, 4, NULL, NULL),
(14, 10, 5, NULL, NULL),
(15, 11, 4, NULL, NULL),
(16, 11, 5, NULL, NULL),
(17, 12, 4, NULL, NULL),
(18, 12, 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_types`
--

CREATE TABLE `booking_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waiting_days` int NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_types`
--

INSERT INTO `booking_types` (`id`, `name`, `waiting_days`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Special Entry Darshan', 90, 'active', '2026-07-27 12:34:34', '2026-07-27 12:34:34'),
(2, 'Sarva Darshan', 0, 'active', '2026-07-27 12:34:34', '2026-07-27 12:34:34'),
(3, 'Accommodation', 30, 'active', '2026-07-27 12:34:34', '2026-07-27 12:34:34'),
(4, 'Virtual Seva', 0, 'active', '2026-07-27 12:34:34', '2026-07-27 12:34:34'),
(5, 'VIP Break Darshan', 180, 'active', '2026-07-27 12:34:34', '2026-07-27 12:34:34');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `devotees`
--

CREATE TABLE `devotees` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `is_head_of_family` tinyint(1) NOT NULL DEFAULT '0',
  `head_devotee_id` bigint UNSIGNED DEFAULT NULL,
  `preferred_booking_type_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` int NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aadhaar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pin_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gothram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `devotees`
--

INSERT INTO `devotees` (`id`, `user_id`, `is_head_of_family`, `head_devotee_id`, `preferred_booking_type_id`, `name`, `age`, `gender`, `aadhaar`, `email`, `phone`, `address`, `city`, `state`, `pin_code`, `gothram`, `photo`, `remarks`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 0, NULL, NULL, 'Tharun Marella', 26, 'Male', '772727272727', 'tarun@demo.com', '9374993027', '5-jsndklskdl kndkslknslkns\r\nskjkj skjkjs ksjj', 'Tirupati', '13230', '517501', 'pidepala', NULL, NULL, '2026-07-29 12:12:55', '2026-07-27 12:36:09', '2026-07-29 12:12:55'),
(2, NULL, 0, NULL, NULL, 'arun', 23, 'Male', '123456786543', 'arun@demo.com', '9374888027', '5-jsndklskdl kndkslknslkns\r\nskjkj skjkjs ksjj', 'Tirupati', '13230', '517501', 'pidepal', NULL, NULL, '2026-07-29 12:12:58', '2026-07-27 12:37:12', '2026-07-29 12:12:58'),
(3, NULL, 0, NULL, NULL, 'nikhil', 26, 'Male', '192837464738', 'nikhil@gmail.com', '9999999999', '5-76', 'Tirupati', '13230', '517501', 'pidepala', NULL, NULL, '2026-07-29 12:13:00', '2026-07-27 12:39:34', '2026-07-29 12:13:00'),
(4, NULL, 1, NULL, 1, 'balaji', 25, 'Male', '223455667896', 'sdgdfdh008@gmail.com', '4563256789', NULL, 'tirupati', 'andhra pradesh', '517501', 'pidepala', NULL, NULL, NULL, '2026-07-29 12:02:46', '2026-07-29 12:02:46'),
(5, NULL, 0, 4, 1, 'gowtham', 28, 'Male', '987654324567', 'jhjhjh88@gmail.com', '4563256788', '5-76', 'tirupati', 'andhra pradesh', '517501', 'pidepala', NULL, NULL, NULL, '2026-07-29 12:03:47', '2026-07-29 12:03:47'),
(6, NULL, 1, NULL, 4, 'nikhil sanjeev', 25, 'Male', '999999887654', 'ggghhgv008@gmail.com', '8765439876', NULL, 'tirupati', 'andhra pradesh', '517501', 'pidepala', NULL, NULL, NULL, '2026-07-29 12:16:01', '2026-07-29 12:16:01'),
(7, NULL, 0, 6, NULL, 'gowtham', 28, 'Male', '098765432567', NULL, '8765439876', NULL, 'tirupati', 'andhra pradesh', '517501', 'pidepala', NULL, NULL, '2026-07-29 12:21:03', '2026-07-29 12:20:46', '2026-07-29 12:21:03'),
(8, NULL, 0, 6, NULL, 'gowtham', 28, 'Male', '987654342567', NULL, '8765439876', NULL, 'tirupati', 'andhra pradesh', '517501', 'pidepala', NULL, NULL, NULL, '2026-07-29 12:22:47', '2026-07-29 12:22:47'),
(9, NULL, 0, 6, NULL, 'sisi', 25, 'Male', '222333444556', NULL, '8765439876', NULL, 'tirupati', 'andhra pradesh', '517501', 'pidepala', NULL, NULL, NULL, '2026-07-29 12:24:56', '2026-07-29 12:24:56'),
(10, NULL, 0, 6, NULL, 'tarun', 25, 'Male', '123455431222', NULL, '8765439876', NULL, 'tirupati', 'andhra pradesh', '517501', 'pidepala', NULL, NULL, NULL, '2026-07-29 12:32:39', '2026-07-29 12:32:39');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `investments`
--

CREATE TABLE `investments` (
  `id` bigint UNSIGNED NOT NULL,
  `investor_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `investment_date` date NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `investments`
--

INSERT INTO `investments` (`id`, `investor_name`, `amount`, `investment_date`, `remarks`, `status`, `created_at`, `updated_at`) VALUES
(1, 'balaji', 2000.00, '2026-08-02', NULL, 'completed', '2026-08-02 01:52:41', '2026-08-02 01:52:41'),
(2, 'balaji', 3000.00, '2026-06-10', NULL, 'active', '2026-08-02 01:54:12', '2026-08-02 01:54:12'),
(4, 'balaji', 2000.00, '2026-06-12', NULL, 'completed', '2026-08-02 01:55:08', '2026-08-02 01:55:08'),
(5, 'nikhil', 2000.00, '2026-07-03', NULL, 'completed', '2026-08-02 01:55:42', '2026-08-02 01:55:42'),
(6, 'sis', 2000.00, '2026-05-07', NULL, 'completed', '2026-08-02 01:56:01', '2026-08-02 01:56:01'),
(7, 'sis', 2000.00, '2026-08-02', NULL, 'completed', '2026-08-02 01:56:19', '2026-08-02 01:56:19');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
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
(4, '2026_07_27_173040_create_devotees_table', 1),
(5, '2026_07_27_173041_create_booking_types_table', 1),
(6, '2026_07_27_173042_create_bookings_table', 1),
(7, '2026_07_27_173043_create_phone_usages_table', 1),
(8, '2026_07_27_173044_create_investments_table', 1),
(9, '2026_07_27_173045_create_revenues_table', 1),
(10, '2026_07_27_173218_create_permission_tables', 1),
(11, '2026_07_27_181645_add_source_to_revenues_table', 2),
(12, '2026_07_29_154655_alter_phone_usages_table_for_new_module', 3),
(13, '2026_07_29_154656_create_seva_types_table', 3),
(14, '2026_07_29_154657_create_phone_usage_service_statuses_table', 3),
(15, '2026_07_29_154658_create_phone_usage_booking_histories_table', 3),
(16, '2026_07_29_172603_alter_devotees_table_add_family_fields', 4),
(17, '2026_07_29_172604_create_booking_devotee_table', 4),
(18, '2026_07_30_174605_add_user_id_to_devotees_table', 5),
(19, '2026_08_02_143727_create_activity_log_table', 6),
(20, '2026_08_02_143728_add_event_column_to_activity_log_table', 6),
(21, '2026_08_02_143729_add_batch_uuid_column_to_activity_log_table', 6),
(22, '2026_08_02_181104_add_production_indexes_to_tables', 7);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 3);

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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phone_usages`
--

CREATE TABLE `phone_usages` (
  `id` bigint UNSIGNED NOT NULL,
  `member_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phone_usages`
--

INSERT INTO `phone_usages` (`id`, `member_name`, `mobile_number`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'tarun', '8309339176', 'Active', NULL, '2026-07-29 10:53:46', '2026-07-29 10:53:46'),
(2, 'nikhil', '8309339177', 'Active', NULL, '2026-07-29 11:16:08', '2026-07-29 11:16:08'),
(3, 'sisu', '8309339166', 'Active', NULL, '2026-07-29 11:20:51', '2026-07-29 11:20:51');

-- --------------------------------------------------------

--
-- Table structure for table `phone_usage_booking_histories`
--

CREATE TABLE `phone_usage_booking_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `phone_usage_id` bigint UNSIGNED NOT NULL,
  `seva_type_id` bigint UNSIGNED NOT NULL,
  `booking_date` date NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phone_usage_booking_histories`
--

INSERT INTO `phone_usage_booking_histories` (`id`, `phone_usage_id`, `seva_type_id`, `booking_date`, `remarks`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 5, '2026-07-24', NULL, 1, '2026-07-29 11:10:14', '2026-07-29 11:10:14'),
(2, 1, 4, '2026-07-23', NULL, 1, '2026-07-29 11:11:07', '2026-07-29 11:11:07'),
(3, 3, 3, '2026-07-21', NULL, 1, '2026-07-29 11:21:14', '2026-07-29 11:21:14');

-- --------------------------------------------------------

--
-- Table structure for table `phone_usage_service_statuses`
--

CREATE TABLE `phone_usage_service_statuses` (
  `id` bigint UNSIGNED NOT NULL,
  `phone_usage_id` bigint UNSIGNED NOT NULL,
  `seva_type_id` bigint UNSIGNED NOT NULL,
  `last_booked_date` date DEFAULT NULL,
  `next_eligible_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phone_usage_service_statuses`
--

INSERT INTO `phone_usage_service_statuses` (`id`, `phone_usage_id`, `seva_type_id`, `last_booked_date`, `next_eligible_date`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-07-21', '2027-01-21', '2026-07-29 10:53:46', '2026-07-29 10:53:46'),
(2, 1, 2, '2026-05-21', '2026-09-21', '2026-07-29 10:53:46', '2026-07-29 10:53:46'),
(3, 1, 3, '2026-05-23', '2026-08-23', '2026-07-29 10:53:46', '2026-07-29 10:53:46'),
(4, 1, 4, '2026-07-23', '2026-10-23', '2026-07-29 10:53:46', '2026-07-29 11:11:07'),
(5, 1, 5, '2026-07-24', '2026-08-24', '2026-07-29 10:53:46', '2026-07-29 11:10:14'),
(6, 1, 6, NULL, NULL, '2026-07-29 10:53:46', '2026-07-29 10:53:46'),
(7, 1, 7, NULL, NULL, '2026-07-29 10:53:46', '2026-07-29 10:53:46'),
(8, 2, 1, '2026-06-29', '2026-12-29', '2026-07-29 11:16:08', '2026-07-29 11:16:08'),
(9, 2, 2, '2026-06-22', '2026-10-22', '2026-07-29 11:16:08', '2026-07-29 11:16:08'),
(10, 2, 3, '2026-07-21', '2026-10-21', '2026-07-29 11:16:08', '2026-07-29 11:16:08'),
(11, 2, 4, '2026-07-25', '2026-10-25', '2026-07-29 11:16:08', '2026-07-29 11:16:08'),
(12, 2, 5, '2026-07-24', '2026-08-24', '2026-07-29 11:16:08', '2026-07-29 11:16:08'),
(13, 2, 6, NULL, NULL, '2026-07-29 11:16:08', '2026-07-29 11:16:08'),
(14, 2, 7, '2026-07-25', '2026-08-25', '2026-07-29 11:16:08', '2026-07-29 11:16:08'),
(15, 3, 1, '2026-02-03', '2026-08-03', '2026-07-29 11:20:51', '2026-07-29 11:20:51'),
(16, 3, 2, '2026-04-22', '2026-08-22', '2026-07-29 11:20:51', '2026-07-29 11:20:51'),
(17, 3, 3, '2026-07-21', '2026-10-21', '2026-07-29 11:20:51', '2026-07-29 11:21:14'),
(18, 3, 4, '2026-06-21', '2026-09-21', '2026-07-29 11:20:51', '2026-07-29 11:20:51'),
(19, 3, 5, '2026-07-25', '2026-08-25', '2026-07-29 11:20:51', '2026-07-29 11:20:51'),
(20, 3, 6, NULL, NULL, '2026-07-29 11:20:51', '2026-07-29 11:20:51'),
(21, 3, 7, NULL, NULL, '2026-07-29 11:20:51', '2026-07-29 11:20:51');

-- --------------------------------------------------------

--
-- Table structure for table `revenues`
--

CREATE TABLE `revenues` (
  `id` bigint UNSIGNED NOT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `revenue_date` date NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `revenues`
--

INSERT INTO `revenues` (`id`, `source`, `amount`, `revenue_date`, `remarks`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'booking tickets commission', 8999.00, '2026-07-27', NULL, 1, '2026-07-27 12:49:08', '2026-07-27 12:49:08'),
(2, 'booking tickets commission', 5000.00, '2026-06-10', NULL, 1, '2026-07-27 12:49:46', '2026-07-27 12:49:46'),
(3, 'may month commison', 7000.00, '2026-07-27', NULL, 1, '2026-07-27 12:50:28', '2026-07-27 12:50:28'),
(4, 'feb month commison', 6000.00, '2026-02-05', NULL, 1, '2026-07-27 13:08:05', '2026-07-27 13:08:05'),
(5, 'mmm', 1000.00, '2026-08-02', NULL, 1, '2026-08-02 09:05:32', '2026-08-02 09:05:32'),
(6, 'jnkjnkj', 9999.00, '2026-08-02', NULL, 1, '2026-08-02 09:07:53', '2026-08-02 09:07:53'),
(7, 'jnkjnkj', 9999.00, '2026-08-02', NULL, 1, '2026-08-02 09:08:12', '2026-08-02 09:08:12'),
(8, 'jhbjh', 999.00, '2026-08-02', NULL, 1, '2026-08-02 09:08:48', '2026-08-02 09:08:48');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', '2026-07-27 12:48:11', '2026-07-27 12:48:11'),
(2, 'Operator', 'web', '2026-07-27 12:48:11', '2026-07-27 12:48:11'),
(3, 'User', 'web', '2026-07-30 12:12:47', '2026-07-30 12:12:47');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
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
-- Table structure for table `seva_types`
--

CREATE TABLE `seva_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cooldown_months` int NOT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seva_types`
--

INSERT INTO `seva_types` (`id`, `name`, `cooldown_months`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Arjitha Seva', 6, 1, 'Active', '2026-07-29 10:19:11', '2026-07-29 10:19:11'),
(2, 'Virtual Seva', 4, 2, 'Active', '2026-07-29 10:19:11', '2026-07-29 10:19:11'),
(3, 'Angapradakshanam', 3, 3, 'Active', '2026-07-29 10:19:11', '2026-07-29 10:19:11'),
(4, 'Senior Citizen Darshan', 3, 4, 'Active', '2026-07-29 10:19:11', '2026-07-29 10:19:11'),
(5, 'Special Entry Darshan (₹300)', 1, 5, 'Active', '2026-07-29 10:19:11', '2026-07-29 10:19:11'),
(6, 'Accommodation', 1, 6, 'Active', '2026-07-29 10:19:11', '2026-07-29 10:19:11'),
(7, 'Srinivasa Divyanugraha Homam', 1, 7, 'Active', '2026-07-29 10:19:11', '2026-07-29 10:19:11');

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
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role_id`, `phone`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'garuda booking', 'garuda008@gmail.com', NULL, '$2y$12$Plv48OtvWlZu7QDeuzXRXOH4C0l/7hY85ckZ7PwremBB8mA47fVdm', NULL, NULL, 'active', 'Bexf5abEeT7PRDb1ufL2KArHY791K1ICgKOylqb5L5ZrM8pb6dZRp8ZD9c7f', '2026-07-27 12:17:05', '2026-08-02 08:35:50'),
(3, 'sisu', 'sisu008@gmail.com', NULL, '$2y$12$KSBiCRSfHMETTX16O.iETeoZ6Zz.NMx.84AhzPRfBqnOSnMwi/dGO', NULL, NULL, 'active', 'Ren51fKGrg3WK9kQpv06CQ8yX3uAYH94kmfQDQsvadN9F0tamYsXNFaVoE7e', '2026-07-30 12:23:51', '2026-07-30 12:23:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookings_booking_no_unique` (`booking_no`),
  ADD KEY `bookings_devotee_id_foreign` (`devotee_id`),
  ADD KEY `bookings_booking_type_id_foreign` (`booking_type_id`),
  ADD KEY `bookings_created_by_foreign` (`created_by`),
  ADD KEY `bookings_booking_no_index` (`booking_no`),
  ADD KEY `bookings_booking_date_index` (`booking_date`),
  ADD KEY `bookings_status_index` (`status`);

--
-- Indexes for table `booking_devotee`
--
ALTER TABLE `booking_devotee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_devotee_booking_id_foreign` (`booking_id`),
  ADD KEY `booking_devotee_devotee_id_foreign` (`devotee_id`);

--
-- Indexes for table `booking_types`
--
ALTER TABLE `booking_types`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `devotees`
--
ALTER TABLE `devotees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `devotees_head_devotee_id_foreign` (`head_devotee_id`),
  ADD KEY `devotees_preferred_booking_type_id_foreign` (`preferred_booking_type_id`),
  ADD KEY `devotees_user_id_foreign` (`user_id`),
  ADD KEY `devotees_aadhaar_index` (`aadhaar`),
  ADD KEY `devotees_phone_index` (`phone`),
  ADD KEY `devotees_name_index` (`name`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `investments`
--
ALTER TABLE `investments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `investments_investor_name_index` (`investor_name`),
  ADD KEY `investments_investment_date_index` (`investment_date`);

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
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `phone_usages`
--
ALTER TABLE `phone_usages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone_usages_mobile_number_unique` (`mobile_number`),
  ADD KEY `phone_usages_mobile_number_index` (`mobile_number`),
  ADD KEY `phone_usages_status_index` (`status`);

--
-- Indexes for table `phone_usage_booking_histories`
--
ALTER TABLE `phone_usage_booking_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phone_usage_booking_histories_phone_usage_id_foreign` (`phone_usage_id`),
  ADD KEY `phone_usage_booking_histories_seva_type_id_foreign` (`seva_type_id`),
  ADD KEY `phone_usage_booking_histories_created_by_foreign` (`created_by`);

--
-- Indexes for table `phone_usage_service_statuses`
--
ALTER TABLE `phone_usage_service_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phone_usage_service_statuses_phone_usage_id_foreign` (`phone_usage_id`),
  ADD KEY `phone_usage_service_statuses_seva_type_id_foreign` (`seva_type_id`);

--
-- Indexes for table `revenues`
--
ALTER TABLE `revenues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `revenues_created_by_foreign` (`created_by`),
  ADD KEY `revenues_revenue_date_index` (`revenue_date`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `seva_types`
--
ALTER TABLE `seva_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `booking_devotee`
--
ALTER TABLE `booking_devotee`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `booking_types`
--
ALTER TABLE `booking_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `devotees`
--
ALTER TABLE `devotees`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investments`
--
ALTER TABLE `investments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phone_usages`
--
ALTER TABLE `phone_usages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `phone_usage_booking_histories`
--
ALTER TABLE `phone_usage_booking_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `phone_usage_service_statuses`
--
ALTER TABLE `phone_usage_service_statuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `revenues`
--
ALTER TABLE `revenues`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `seva_types`
--
ALTER TABLE `seva_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_booking_type_id_foreign` FOREIGN KEY (`booking_type_id`) REFERENCES `booking_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_devotee_id_foreign` FOREIGN KEY (`devotee_id`) REFERENCES `devotees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_devotee`
--
ALTER TABLE `booking_devotee`
  ADD CONSTRAINT `booking_devotee_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_devotee_devotee_id_foreign` FOREIGN KEY (`devotee_id`) REFERENCES `devotees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `devotees`
--
ALTER TABLE `devotees`
  ADD CONSTRAINT `devotees_head_devotee_id_foreign` FOREIGN KEY (`head_devotee_id`) REFERENCES `devotees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `devotees_preferred_booking_type_id_foreign` FOREIGN KEY (`preferred_booking_type_id`) REFERENCES `booking_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `devotees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `phone_usage_booking_histories`
--
ALTER TABLE `phone_usage_booking_histories`
  ADD CONSTRAINT `phone_usage_booking_histories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `phone_usage_booking_histories_phone_usage_id_foreign` FOREIGN KEY (`phone_usage_id`) REFERENCES `phone_usages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phone_usage_booking_histories_seva_type_id_foreign` FOREIGN KEY (`seva_type_id`) REFERENCES `seva_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `phone_usage_service_statuses`
--
ALTER TABLE `phone_usage_service_statuses`
  ADD CONSTRAINT `phone_usage_service_statuses_phone_usage_id_foreign` FOREIGN KEY (`phone_usage_id`) REFERENCES `phone_usages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phone_usage_service_statuses_seva_type_id_foreign` FOREIGN KEY (`seva_type_id`) REFERENCES `seva_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `revenues`
--
ALTER TABLE `revenues`
  ADD CONSTRAINT `revenues_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

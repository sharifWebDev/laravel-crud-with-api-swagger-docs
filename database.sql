-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 20, 2025 at 04:16 PM
-- Server version: 8.0.43-0ubuntu0.22.04.2
-- PHP Version: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `app_r7sg_reposetory_pattent_task`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `platform` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `api_keys`
--

INSERT INTO `api_keys` (`id`, `key`, `platform`, `is_active`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'abcd232', 'android', 1, NULL, '2025-11-19 09:35:30', '2025-11-19 09:35:30'),
(2, 'xyz2234', 'ios', 1, NULL, '2025-11-19 09:35:30', '2025-11-19 09:35:30');

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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `image`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'General Knowledge', 'Test your general knowledge with these quizzes', NULL, 1, 1, '2025-11-19 09:35:30', '2025-11-19 09:35:30');

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

--
-- Dumping data for table `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(1, 'e82c1dd6-f7f4-4bb2-8bfa-ae2dedbca568', 'database', 'default', '{\"uuid\":\"e82c1dd6-f7f4-4bb2-8bfa-ae2dedbca568\",\"displayName\":\"App\\\\Events\\\\ProfileUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:25:\\\"App\\\\Events\\\\ProfileUpdated\\\":2:{s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:13:\\\"updatedFields\\\";a:1:{i:0;s:12:\\\"phone_number\\\";}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1763654502,\"delay\":null}', 'Illuminate\\Broadcasting\\BroadcastException: Pusher error: cURL error 7: Failed to connect to 127.0.0.1 port 443 after 0 ms: Connection refused (see https://curl.haxx.se/libcurl/c/libcurl-errors.html) for https://127.0.0.1/apps/1945440/events?auth_key=f6e8402563e7c2abc666&auth_timestamp=1763654503&auth_version=1.0&body_md5=8352c53a8446c9ce2636bc1f767ae07b&auth_signature=eda512753a57be02ffd52319f51010643cfd547e02bbe272edcdd3674d339b7c. in /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Broadcasting/Broadcasters/PusherBroadcaster.php:163\nStack trace:\n#0 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Broadcasting/BroadcastEvent.php(100): Illuminate\\Broadcasting\\Broadcasters\\PusherBroadcaster->broadcast()\n#1 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Broadcasting\\BroadcastEvent->handle()\n#2 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::{closure:Illuminate\\Container\\BoundMethod::call():35}()\n#3 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#4 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#5 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#6 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(129): Illuminate\\Container\\Container->call()\n#7 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->{closure:Illuminate\\Bus\\Dispatcher::dispatchNow():126}()\n#8 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->{closure:Illuminate\\Pipeline\\Pipeline::prepareDestination():178}()\n#9 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(133): Illuminate\\Pipeline\\Pipeline->then()\n#10 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#11 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->{closure:Illuminate\\Queue\\CallQueuedHandler::dispatchThroughMiddleware():127}()\n#12 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->{closure:Illuminate\\Pipeline\\Pipeline::prepareDestination():178}()\n#13 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#14 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#15 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#16 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(451): Illuminate\\Queue\\Jobs\\Job->fire()\n#17 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(401): Illuminate\\Queue\\Worker->process()\n#18 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(187): Illuminate\\Queue\\Worker->runJob()\n#19 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#20 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#21 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#22 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::{closure:Illuminate\\Container\\BoundMethod::call():35}()\n#23 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#24 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#25 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#26 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#27 /var/www/reposetory_pattent_task/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#28 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#29 /var/www/reposetory_pattent_task/vendor/symfony/console/Application.php(1073): Illuminate\\Console\\Command->run()\n#30 /var/www/reposetory_pattent_task/vendor/symfony/console/Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand()\n#31 /var/www/reposetory_pattent_task/vendor/symfony/console/Application.php(195): Symfony\\Component\\Console\\Application->doRun()\n#32 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#33 /var/www/reposetory_pattent_task/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#34 /var/www/reposetory_pattent_task/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#35 {main}', '2025-11-20 10:01:43');

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
(4, '2025_08_12_183812_add_two_factor_columns_to_users_table', 1),
(5, '2025_08_12_183852_create_personal_access_tokens_table', 1),
(6, '2025_08_12_184013_create_teams_table', 1),
(7, '2025_08_12_184014_create_team_user_table', 1),
(8, '2025_08_12_184015_create_team_invitations_table', 1),
(9, '2025_11_19_095725_create_api_keys_table', 1),
(10, '2025_11_19_100044_create_otps_table', 1),
(11, '2025_11_19_100109_create_categories_table', 1),
(12, '2025_11_19_100134_create_quizzes_table', 1),
(13, '2025_11_19_100202_create_quistions_table', 1),
(14, '2025_11_19_100231_create_quiz_results_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `otps`
--

CREATE TABLE `otps` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `otp_code` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otps`
--

INSERT INTO `otps` (`id`, `user_id`, `otp_code`, `purpose`, `expires_at`, `is_used`, `created_at`, `updated_at`) VALUES
(4, 1, '308900', 'verification', '2025-11-20 06:17:24', 1, '2025-11-20 06:07:24', '2025-11-20 06:10:41'),
(7, 1, '403528', 'verification', '2025-11-20 08:05:32', 0, '2025-11-20 07:55:32', '2025-11-20 07:55:32');

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'android_auth_token', 'a3462f10599b2d20add96d63814ebbfa5dbaa1c71ba0629ad4e22ba09b1ad61e', '[\"*\"]', NULL, NULL, '2025-11-20 03:45:34', '2025-11-20 03:45:34'),
(2, 'App\\Models\\User', 1, 'android_auth_token', '94abb06b0ac2ed08f7bf59cce1378674fca50cdd9e510548d17d4387b47805a2', '[\"*\"]', NULL, NULL, '2025-11-20 03:46:21', '2025-11-20 03:46:21'),
(3, 'App\\Models\\User', 1, 'android_auth_token', 'cd973dfac453225cf4659031962960a3529eb9b82571da7e231aaf93171f265c', '[\"*\"]', NULL, NULL, '2025-11-20 03:49:43', '2025-11-20 03:49:43'),
(4, 'App\\Models\\User', 2, 'android_auth_token', '283f604c9df3fa0dba77d04ba2bd4861cfb86d2beec5257beb0430223be7eacb', '[\"*\"]', NULL, NULL, '2025-11-20 04:36:47', '2025-11-20 04:36:47'),
(5, 'App\\Models\\User', 3, 'android_auth_token', '055ed33ed6dddbaf14add7b2fa71ec7d5f8fbca5a66ed245cc6dff3277f68d6c', '[\"*\"]', NULL, NULL, '2025-11-20 04:39:37', '2025-11-20 04:39:37'),
(6, 'App\\Models\\User', 4, 'android_auth_token', '79c7bc61fdb62cf7723f1c327238cb2fe796b326f292e183a7b5c98efdf24f86', '[\"*\"]', NULL, NULL, '2025-11-20 04:40:24', '2025-11-20 04:40:24'),
(7, 'App\\Models\\User', 5, 'android_auth_token', '7d6b4596e4f7f943cfd818508b0625d6c119c84a6addf087136d4a33ea432445', '[\"*\"]', NULL, NULL, '2025-11-20 04:41:46', '2025-11-20 04:41:46'),
(8, 'App\\Models\\User', 1, 'android_auth_token', '368bab5a886e9c40a60850ac5c91be703e59fbd5c5520554378ce22b272400a4', '[\"*\"]', NULL, NULL, '2025-11-20 04:43:40', '2025-11-20 04:43:40'),
(9, 'App\\Models\\User', 2, 'android_auth_token', '59ef340c2f5d562207a8419ebbb498829c136db2aae3e0864e38abe8514607b2', '[\"*\"]', NULL, NULL, '2025-11-20 04:45:53', '2025-11-20 04:45:53'),
(10, 'App\\Models\\User', 2, 'android_auth_token', '1f7866675301ae0c17fb8de7e1aeec73be89c2b6005096cf661ac23b756e8c87', '[\"*\"]', NULL, NULL, '2025-11-20 04:45:55', '2025-11-20 04:45:55'),
(11, 'App\\Models\\User', 2, 'android_auth_token', '7c6cee76adba958416f983b1ce4bb90ab0815ea75908640fffc13d67673fe683', '[\"*\"]', NULL, NULL, '2025-11-20 04:45:57', '2025-11-20 04:45:57'),
(12, 'App\\Models\\User', 2, 'android_auth_token', 'a2f57af0f0df6b76e44e7e0b8ab58c71a1dbcd20bf053266f1d61690f9ddf509', '[\"*\"]', NULL, NULL, '2025-11-20 04:47:26', '2025-11-20 04:47:26'),
(13, 'App\\Models\\User', 2, 'android_auth_token', '9f9a5611d41f70dc76b4b16a7856ec870b614e920b1b27f63b8992133466364a', '[\"*\"]', NULL, NULL, '2025-11-20 04:47:33', '2025-11-20 04:47:33'),
(14, 'App\\Models\\User', 2, 'android_auth_token', '8853d4983aa9ec566a75993df8dddb879e0ccc8c9632fa5c6e92128342462636', '[\"*\"]', NULL, NULL, '2025-11-20 04:47:35', '2025-11-20 04:47:35'),
(15, 'App\\Models\\User', 2, 'android_auth_token', '3a46a1c1a32aa0200af9bd4e79a76867ba10bfc9f65cbd6d1752e00836365aee', '[\"*\"]', NULL, NULL, '2025-11-20 04:47:37', '2025-11-20 04:47:37'),
(16, 'App\\Models\\User', 2, 'android_auth_token', 'b81010f282f2b3bb8f159776dda399df925e3acaba2b721a60e8dba069239d52', '[\"*\"]', NULL, NULL, '2025-11-20 04:47:39', '2025-11-20 04:47:39'),
(17, 'App\\Models\\User', 2, 'android_auth_token', '1bdd8fd282eff4ead787cb9dc09534f597b4ce75be03501476e03d99390cce77', '[\"*\"]', NULL, NULL, '2025-11-20 04:47:40', '2025-11-20 04:47:40'),
(18, 'App\\Models\\User', 2, 'android_auth_token', 'd957db58a90f6866dc7dc3d24f0b9887189e90f1ab02879e1046d88dfb5183fb', '[\"*\"]', NULL, NULL, '2025-11-20 04:47:43', '2025-11-20 04:47:43'),
(19, 'App\\Models\\User', 2, 'android_auth_token', '5107002b320eeca9be60da3876ff6617a0159df098c0f4638ce6023784bc8e9e', '[\"*\"]', '2025-11-20 10:01:42', NULL, '2025-11-20 04:49:12', '2025-11-20 10:01:42');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` bigint UNSIGNED NOT NULL,
  `quiz_id` bigint UNSIGNED NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` json DEFAULT NULL COMMENT 'For multiple choice questions - array of choices',
  `correct_answer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `explanation` text COLLATE utf8mb4_unicode_ci COMMENT 'Explanation for the correct answer',
  `points` int NOT NULL DEFAULT '1' COMMENT 'Points awarded for correct answer',
  `time_limit` int DEFAULT NULL COMMENT 'Time limit for this question in seconds',
  `question_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'multiple_choice' COMMENT 'multiple_choice, true_false, short_answer, etc.',
  `sort_order` int NOT NULL DEFAULT '0' COMMENT 'Order of questions in quiz',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `metadata` json DEFAULT NULL COMMENT 'Additional question metadata',
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL for question image',
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL for question video',
  `audio_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL for question audio',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question`, `options`, `correct_answer`, `explanation`, `points`, `time_limit`, `question_type`, `sort_order`, `is_active`, `metadata`, `image_url`, `video_url`, `audio_url`, `created_at`, `updated_at`) VALUES
(1, 1, 'What is the capital of France?', '[\"London\", \"Berlin\", \"Paris\", \"Madrid\"]', 'Paris', NULL, 10, NULL, 'multiple_choice', 1, 1, NULL, NULL, NULL, NULL, '2025-11-19 09:35:30', '2025-11-19 09:35:30'),
(2, 1, 'Which planet is known as the Red Planet?', '[\"Venus\", \"Mars\", \"Jupiter\", \"Saturn\"]', 'Mars', NULL, 10, NULL, 'multiple_choice', 2, 1, NULL, NULL, NULL, NULL, '2025-11-19 09:35:30', '2025-11-19 09:35:30');

-- --------------------------------------------------------

--
-- Table structure for table `question_hints`
--

CREATE TABLE `question_hints` (
  `id` bigint UNSIGNED NOT NULL,
  `question_id` bigint UNSIGNED NOT NULL,
  `hint_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `points_deduction` int NOT NULL DEFAULT '0' COMMENT 'Points deducted when using this hint',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question_statistics`
--

CREATE TABLE `question_statistics` (
  `id` bigint UNSIGNED NOT NULL,
  `question_id` bigint UNSIGNED NOT NULL,
  `total_attempts` int NOT NULL DEFAULT '0',
  `correct_attempts` int NOT NULL DEFAULT '0',
  `incorrect_attempts` int NOT NULL DEFAULT '0',
  `average_time_taken` double NOT NULL DEFAULT '0' COMMENT 'Average time taken to answer in seconds',
  `difficulty_level` double NOT NULL DEFAULT '0' COMMENT 'Calculated difficulty level 0-1',
  `answer_distribution` json DEFAULT NULL COMMENT 'Distribution of answers chosen',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `question_statistics`
--

INSERT INTO `question_statistics` (`id`, `question_id`, `total_attempts`, `correct_attempts`, `incorrect_attempts`, `average_time_taken`, `difficulty_level`, `answer_distribution`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 0, 0, 0, 0, NULL, '2025-11-19 09:35:30', '2025-11-19 09:35:30'),
(2, 2, 0, 0, 0, 0, 0, NULL, '2025-11-19 09:35:30', '2025-11-19 09:35:30');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('multiple_choice','true_false','single_choice') COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_limit` int DEFAULT NULL,
  `points` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `category_id`, `title`, `description`, `type`, `time_limit`, `points`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Basic General Knowledge', 'Basic level quiz for General Knowledge', 'multiple_choice', 600, 100, 1, 1, '2025-11-19 09:35:30', '2025-11-19 09:35:30'),
(2, 1, 'Advanced General Knowledge', 'Advanced level quiz for General Knowledge', 'single_choice', 900, 200, 1, 2, '2025-11-19 09:35:30', '2025-11-19 09:35:30');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_results`
--

CREATE TABLE `quiz_results` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `quiz_id` bigint UNSIGNED NOT NULL,
  `score` int NOT NULL,
  `total_questions` int NOT NULL,
  `correct_answers` int NOT NULL,
  `time_taken` int NOT NULL,
  `answers` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_results`
--

INSERT INTO `quiz_results` (`id`, `user_id`, `quiz_id`, `score`, `total_questions`, `correct_answers`, `time_taken`, `answers`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 0, 2, 0, 120, '[{\"points\": 0, \"question\": \"What is the capital of France?\", \"is_correct\": false, \"explanation\": null, \"question_id\": 1, \"user_answer\": \"a\", \"correct_answer\": \"Paris\"}, {\"points\": 0, \"question\": \"Which planet is known as the Red Planet?\", \"is_correct\": false, \"explanation\": null, \"question_id\": 2, \"user_answer\": \"b\", \"correct_answer\": \"Mars\"}]', '2025-11-20 07:16:08', '2025-11-20 07:16:08'),
(2, 2, 1, 0, 2, 0, 120, '[{\"points\": 0, \"question\": \"What is the capital of France?\", \"is_correct\": false, \"explanation\": null, \"question_id\": 1, \"user_answer\": \"a\", \"correct_answer\": \"Paris\"}, {\"points\": 0, \"question\": \"Which planet is known as the Red Planet?\", \"is_correct\": false, \"explanation\": null, \"question_id\": 2, \"user_answer\": \"b\", \"correct_answer\": \"Mars\"}]', '2025-11-20 09:54:44', '2025-11-20 09:54:44');

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('BybP2j5A6iRdS8mC6wJeZtDn6PetSLpDNZHIDD3Y', NULL, '127.0.0.1', 'PostmanRuntime/7.49.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWkRBOUJNYWFjcXRYcm53TzhEUE9JdDk4WnJvWGRiWVBGWVlNVmFWSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1763575115),
('umwhvmMWGFZw6wa9qKUNR9D62qRPBs3hY8EA8BcB', 1, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiaW5RcDhvN3FNeGJPMnNpVk9MN2RpWEVsRVFPeU9mc0IzMmhiMGt6eiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vc3R1ZGVudHMvY3JlYXRlIjtzOjU6InJvdXRlIjtzOjIxOiJhZG1pbi5zdHVkZW50cy5jcmVhdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MjE6InBhc3N3b3JkX2hhc2hfc2FuY3R1bSI7czo2MDoiJDJ5JDEyJGpqelBOdzhmUXgweW5CUk1xSUNHeGU2ejNXLmg0bW9OVmtHbm1LUGdaVWhrS1Vzc2ROMmdxIjt9', 1763574023);

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_team` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_invitations`
--

CREATE TABLE `team_invitations` (
  `id` bigint UNSIGNED NOT NULL,
  `team_id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_user`
--

CREATE TABLE `team_user` (
  `id` bigint UNSIGNED NOT NULL,
  `team_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `unique_id` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `auth_type` enum('gmail','apple','email_pass') COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_country_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_img` text COLLATE utf8mb4_unicode_ci,
  `app_version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firebase_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `acc_status` enum('active','pending_deletion','deleted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `delete_requested_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `current_team_id` bigint UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `unique_id`, `full_name`, `email`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `auth_type`, `phone_country_code`, `phone_number`, `profile_img`, `app_version`, `ip_address`, `firebase_id`, `acc_status`, `delete_requested_at`, `email_verified_at`, `current_team_id`, `profile_photo_path`, `remember_token`, `last_login_at`, `created_at`, `updated_at`) VALUES
(1, '12345678', 'John Doe', 'admin@gmail.com', '$2y$12$jjzPNw8fQx0ynBRMqICGxe6z3W.h4moNVkGnmKPgZUhkKUssdN2gq', NULL, NULL, NULL, 'email_pass', '+91', '1234567890', 'https://example.com/profile.jpg', '1.0.0', '127.0.0.1', '1234567890', 'active', NULL, '2025-11-20 06:10:41', NULL, NULL, NULL, '2025-11-20 04:43:40', '2025-11-19 09:35:30', '2025-11-20 06:10:41'),
(2, '60839236', 'Asif1', 'user1@gmail.com', '$2y$12$7GbyeC4aj4rmusAXOYsT0uh86RZH10OB3QPe3GgZPMqV.i7cd2iga', NULL, NULL, NULL, 'email_pass', '+88', '0196567676554', NULL, NULL, '127.0.0.1', 'abcd343kejfrn', 'active', NULL, NULL, NULL, NULL, NULL, '2025-11-20 04:49:12', '2025-11-20 04:36:47', '2025-11-20 10:01:42'),
(3, '81611421', 'Asif1', 'user2@gmail.com', '$2y$12$RnTXJorDTXxt9C1hinKgFuvpbOgQ/t4rkK/XmZkbtxVoFXn5wtkq.', NULL, NULL, NULL, 'email_pass', NULL, NULL, NULL, NULL, '127.0.0.1', 'abcd343kejfrn', 'active', NULL, NULL, NULL, NULL, NULL, '2025-11-20 04:39:37', '2025-11-20 04:39:37', '2025-11-20 04:39:37'),
(4, '37773616', 'Asif1', 'user3@gmail.com', '$2y$12$j13ujo4RQBCX9TvZh708Geo22.6xTMg.VWiCGl1HHt4pQCnyI3Cb.', NULL, NULL, NULL, 'email_pass', NULL, NULL, NULL, NULL, '127.0.0.1', 'abcd343kejfrn', 'active', NULL, NULL, NULL, NULL, NULL, '2025-11-20 04:40:24', '2025-11-20 04:40:24', '2025-11-20 04:40:24'),
(5, '60439839', 'Asif1', 'user4@gmail.com', '$2y$12$DyMVMwU793qvdkAiA003O.WtPeLvx2VpazESPr.zg5QdAdPqvkfFa', NULL, NULL, NULL, 'email_pass', NULL, NULL, NULL, NULL, '127.0.0.1', 'abcd343kejfrn', 'active', NULL, NULL, NULL, NULL, NULL, '2025-11-20 04:41:46', '2025-11-20 04:41:46', '2025-11-20 04:41:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `api_keys_key_unique` (`key`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `otps`
--
ALTER TABLE `otps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `otps_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questions_quiz_id_is_active_index` (`quiz_id`,`is_active`),
  ADD KEY `questions_quiz_id_sort_order_index` (`quiz_id`,`sort_order`),
  ADD KEY `questions_is_active_index` (`is_active`);

--
-- Indexes for table `question_hints`
--
ALTER TABLE `question_hints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_hints_question_id_sort_order_index` (`question_id`,`sort_order`);

--
-- Indexes for table `question_statistics`
--
ALTER TABLE `question_statistics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `question_statistics_question_id_unique` (`question_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizzes_category_id_foreign` (`category_id`);

--
-- Indexes for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_results_user_id_foreign` (`user_id`),
  ADD KEY `quiz_results_quiz_id_foreign` (`quiz_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teams_user_id_index` (`user_id`);

--
-- Indexes for table `team_invitations`
--
ALTER TABLE `team_invitations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `team_invitations_team_id_email_unique` (`team_id`,`email`);

--
-- Indexes for table `team_user`
--
ALTER TABLE `team_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `team_user_team_id_user_id_unique` (`team_id`,`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_unique_id_unique` (`unique_id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `otps`
--
ALTER TABLE `otps`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `question_hints`
--
ALTER TABLE `question_hints`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `question_statistics`
--
ALTER TABLE `question_statistics`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team_invitations`
--
ALTER TABLE `team_invitations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team_user`
--
ALTER TABLE `team_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `otps`
--
ALTER TABLE `otps`
  ADD CONSTRAINT `otps_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `question_hints`
--
ALTER TABLE `question_hints`
  ADD CONSTRAINT `question_hints_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `question_statistics`
--
ALTER TABLE `question_statistics`
  ADD CONSTRAINT `question_statistics_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD CONSTRAINT `quiz_results_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_results_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `team_invitations`
--
ALTER TABLE `team_invitations`
  ADD CONSTRAINT `team_invitations_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

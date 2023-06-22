-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         11.1.0-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.3.0.6589
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para gamehub
CREATE DATABASE IF NOT EXISTS `gamehub` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `gamehub`;

-- Volcando estructura para tabla gamehub.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.categories: ~4 rows (aproximadamente)
DELETE FROM `categories`;
INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
	(1, 'matematicas', 'matematicas', NULL, NULL),
	(2, 'lenguaje', 'lenguaje', NULL, NULL),
	(3, 'ingles', 'ingles', NULL, NULL),
	(4, 'otros', 'otros', NULL, NULL);

-- Volcando estructura para tabla gamehub.entry_points
CREATE TABLE IF NOT EXISTS `entry_points` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `obfuscated` tinyint(1) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `experiment_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.entry_points: ~0 rows (aproximadamente)
DELETE FROM `entry_points`;

-- Volcando estructura para tabla gamehub.experiments
CREATE TABLE IF NOT EXISTS `experiments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('detenido','activo') NOT NULL DEFAULT 'detenido',
  `time_limit` bigint(20) DEFAULT NULL,
  `admin_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.experiments: ~1 rows (aproximadamente)
DELETE FROM `experiments`;
INSERT INTO `experiments` (`id`, `name`, `description`, `status`, `time_limit`, `admin_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'prueba', 'asd', 'activo', 100, 1, '2023-06-16 08:35:27', '2023-06-16 08:35:27', NULL);

-- Volcando estructura para tabla gamehub.experiment_user
CREATE TABLE IF NOT EXISTS `experiment_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `experiment_id` bigint(20) unsigned NOT NULL,
  `game_instance_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.experiment_user: ~5 rows (aproximadamente)
DELETE FROM `experiment_user`;
INSERT INTO `experiment_user` (`id`, `user_id`, `experiment_id`, `game_instance_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 3, '2023-06-16 11:15:22', '2023-06-16 11:15:22'),
	(2, 2, 1, 5, NULL, '2023-06-16 12:17:27'),
	(3, 4, 1, 5, NULL, '2023-06-16 12:17:27'),
	(4, 3, 1, 5, NULL, '2023-06-16 12:17:29'),
	(5, 5, 1, 5, NULL, '2023-06-16 12:17:30');

-- Volcando estructura para tabla gamehub.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.failed_jobs: ~0 rows (aproximadamente)
DELETE FROM `failed_jobs`;

-- Volcando estructura para tabla gamehub.games
CREATE TABLE IF NOT EXISTS `games` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `file` varchar(255) NOT NULL,
  `gm2game` tinyint(1) NOT NULL DEFAULT 0,
  `category_id` bigint(20) unsigned NOT NULL,
  `extra` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`extra`)),
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `games_category_id_foreign` (`category_id`),
  CONSTRAINT `games_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.games: ~4 rows (aproximadamente)
DELETE FROM `games`;
INSERT INTO `games` (`id`, `name`, `slug`, `description`, `file`, `gm2game`, `category_id`, `extra`, `user_id`, `created_at`, `updated_at`) VALUES
	(4, 'sio', 'sio', 'asd', '/uploads/games/sio', 1, 1, '"{\\"type\\":\\"GM2\\",\\"filename\\":\\"Demo SavingObject X\\"}"', 1, '2023-06-16 10:54:25', '2023-06-16 10:54:25'),
	(5, 'Tetris', 'tetris', 'asd', '/uploads/games/tetris', 0, 1, NULL, 1, '2023-06-16 10:57:22', '2023-06-16 10:57:22'),
	(6, 'Numeribirds', 'numeribirds', 'asd', '/uploads/games/numeribirds', 1, 1, '"{\\"type\\":\\"GM2\\",\\"filename\\":\\"Numeribirds\\"}"', 1, '2023-06-16 12:10:53', '2023-06-16 12:10:53'),
	(7, 'matecjas', 'matecjas', 'asd', '/uploads/games/matecjas', 1, 1, '"{\\"type\\":\\"GM2\\",\\"filename\\":\\"Matecajas - Fracciones\\"}"', 1, '2023-06-16 12:11:04', '2023-06-16 12:11:04');

-- Volcando estructura para tabla gamehub.game_badges
CREATE TABLE IF NOT EXISTS `game_badges` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `conditions` varchar(255) NOT NULL,
  `var` varchar(255) DEFAULT NULL,
  `var_to_ref` varchar(255) DEFAULT NULL,
  `target` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `game_id` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.game_badges: ~0 rows (aproximadamente)
DELETE FROM `game_badges`;

-- Volcando estructura para tabla gamehub.game_instances
CREATE TABLE IF NOT EXISTS `game_instances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `game_id` bigint(20) unsigned DEFAULT NULL,
  `experiment_id` bigint(20) unsigned NOT NULL,
  `enable_rewards` tinyint(1) NOT NULL DEFAULT 0,
  `enable_badges` tinyint(1) NOT NULL DEFAULT 0,
  `enable_performance_chart` tinyint(1) NOT NULL DEFAULT 0,
  `enable_leaderboard` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.game_instances: ~3 rows (aproximadamente)
DELETE FROM `game_instances`;
INSERT INTO `game_instances` (`id`, `name`, `slug`, `description`, `game_id`, `experiment_id`, `enable_rewards`, `enable_badges`, `enable_performance_chart`, `enable_leaderboard`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(3, 'saving object', 'saving-object', 'asd', 4, 1, 0, 0, 0, 0, '2023-06-16 10:37:00', '2023-06-16 15:26:55', NULL),
	(4, 'n', 'n', 'asd', 6, 1, 0, 0, 0, 0, '2023-06-16 12:11:25', '2023-06-16 12:11:25', NULL),
	(5, 'm', 'm', 'asd', 7, 1, 0, 0, 0, 0, '2023-06-16 12:11:30', '2023-06-16 12:11:30', NULL);

-- Volcando estructura para tabla gamehub.game_instance_exercises
CREATE TABLE IF NOT EXISTS `game_instance_exercises` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event` int(11) NOT NULL,
  `round` int(11) DEFAULT NULL,
  `exercise` text DEFAULT NULL,
  `user_response` text DEFAULT NULL,
  `response` text DEFAULT NULL,
  `time_start` timestamp NULL DEFAULT NULL,
  `time_end` timestamp NULL DEFAULT NULL,
  `extra` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`extra`)),
  `user_id` bigint(20) unsigned NOT NULL,
  `game_instance_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.game_instance_exercises: ~0 rows (aproximadamente)
DELETE FROM `game_instance_exercises`;

-- Volcando estructura para tabla gamehub.game_instance_parameters
CREATE TABLE IF NOT EXISTS `game_instance_parameters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(255) NOT NULL,
  `parameter_id` bigint(20) unsigned NOT NULL,
  `game_instance_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.game_instance_parameters: ~0 rows (aproximadamente)
DELETE FROM `game_instance_parameters`;

-- Volcando estructura para tabla gamehub.game_instance_scores
CREATE TABLE IF NOT EXISTS `game_instance_scores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `max_score` bigint(20) unsigned NOT NULL DEFAULT 0,
  `user_id` bigint(20) unsigned NOT NULL,
  `game_instance_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.game_instance_scores: ~0 rows (aproximadamente)
DELETE FROM `game_instance_scores`;

-- Volcando estructura para tabla gamehub.game_instance_times
CREATE TABLE IF NOT EXISTS `game_instance_times` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `remaining_time` int(11) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `game_instance_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.game_instance_times: ~0 rows (aproximadamente)
DELETE FROM `game_instance_times`;

-- Volcando estructura para tabla gamehub.game_instance_time_counter
CREATE TABLE IF NOT EXISTS `game_instance_time_counter` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time_used` bigint(20) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `game_instance_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.game_instance_time_counter: ~0 rows (aproximadamente)
DELETE FROM `game_instance_time_counter`;

-- Volcando estructura para tabla gamehub.grades
CREATE TABLE IF NOT EXISTS `grades` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `school_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.grades: ~0 rows (aproximadamente)
DELETE FROM `grades`;

-- Volcando estructura para tabla gamehub.media
CREATE TABLE IF NOT EXISTS `media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `collection_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `disk` varchar(255) NOT NULL,
  `conversions_disk` varchar(255) DEFAULT NULL,
  `size` bigint(20) unsigned NOT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`manipulations`)),
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`custom_properties`)),
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`generated_conversions`)),
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`responsive_images`)),
  `order_column` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.media: ~4 rows (aproximadamente)
DELETE FROM `media`;
INSERT INTO `media` (`id`, `model_type`, `model_id`, `uuid`, `collection_name`, `name`, `file_name`, `mime_type`, `disk`, `conversions_disk`, `size`, `manipulations`, `custom_properties`, `generated_conversions`, `responsive_images`, `order_column`, `created_at`, `updated_at`) VALUES
	(4, 'App\\Models\\Game', 4, '1f7c12bf-a7e3-441d-a6b2-f5dae7a69706', 'games', 'Demo SavingObject X', 'Demo-SavingObject-X.zip', 'application/zip', 'public', 'public', 623415, '[]', '[]', '[]', '[]', 1, '2023-06-16 10:54:25', '2023-06-16 10:54:25'),
	(5, 'App\\Models\\Game', 5, 'd6ed1c5f-c918-475a-9217-ae7fb021fd19', 'games', 'tetris', 'tetris.zip', 'application/zip', 'public', 'public', 4175, '[]', '[]', '[]', '[]', 1, '2023-06-16 10:57:22', '2023-06-16 10:57:22'),
	(6, 'App\\Models\\Game', 6, 'ac0b65bb-aca4-4e9f-8ad5-1144e61aa976', 'games', 'Numeribirds', 'Numeribirds.zip', 'application/zip', 'public', 'public', 4424934, '[]', '[]', '[]', '[]', 1, '2023-06-16 12:10:53', '2023-06-16 12:10:53'),
	(7, 'App\\Models\\Game', 7, '78e86368-1bbb-41f7-b805-d1535b65f23c', 'games', 'Matecajas Fixed', 'Matecajas-Fixed.zip', 'application/zip', 'public', 'public', 4556037, '[]', '[]', '[]', '[]', 1, '2023-06-16 12:11:04', '2023-06-16 12:11:04');

-- Volcando estructura para tabla gamehub.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.migrations: ~24 rows (aproximadamente)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(2, '2019_08_19_000000_create_failed_jobs_table', 1),
	(3, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(4, '2023_01_24_015458_create_schools_table', 1),
	(5, '2023_01_24_015629_create_grades_table', 1),
	(6, '2023_01_25_000000_create_users_table', 1),
	(7, '2023_04_03_030016_create_categories_table', 1),
	(8, '2023_04_03_030130_create_games_table', 1),
	(9, '2023_04_06_030904_create_media_table', 1),
	(10, '2023_04_07_013500_create_experiments_table', 1),
	(11, '2023_04_07_014357_create_game_instances_table', 1),
	(12, '2023_04_09_212719_create_game_instance_parameters_table', 1),
	(13, '2023_04_09_214120_create_parameters_table', 1),
	(14, '2023_05_19_182527_create_roles_table', 1),
	(15, '2023_05_26_195611_create_experiment_user_table', 1),
	(16, '2023_05_28_234308_create_entry_points_table', 1),
	(17, '2023_05_29_145355_create_surveys_table', 1),
	(18, '2023_06_12_202344_create_game_instance_scores', 1),
	(19, '2023_06_12_202413_create_game_instance_times', 1),
	(20, '2023_06_12_202437_create_game_instance_time_counter', 1),
	(21, '2023_06_12_202719_create_game_instance_exercises', 1),
	(22, '2023_06_13_224632_create_survey_responses_table', 1),
	(23, '2023_06_14_234319_create_game_badges', 1),
	(24, '2023_06_15_001023_create_user_game_badges', 1);

-- Volcando estructura para tabla gamehub.parameters
CREATE TABLE IF NOT EXISTS `parameters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` enum('int','float','string','boolean') NOT NULL,
  `game_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.parameters: ~0 rows (aproximadamente)
DELETE FROM `parameters`;

-- Volcando estructura para tabla gamehub.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.password_reset_tokens: ~0 rows (aproximadamente)
DELETE FROM `password_reset_tokens`;

-- Volcando estructura para tabla gamehub.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.personal_access_tokens: ~0 rows (aproximadamente)
DELETE FROM `personal_access_tokens`;

-- Volcando estructura para tabla gamehub.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.roles: ~2 rows (aproximadamente)
DELETE FROM `roles`;
INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'usuario', NULL, NULL),
	(2, 'administrador', NULL, NULL);

-- Volcando estructura para tabla gamehub.schools
CREATE TABLE IF NOT EXISTS `schools` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.schools: ~0 rows (aproximadamente)
DELETE FROM `schools`;

-- Volcando estructura para tabla gamehub.surveys
CREATE TABLE IF NOT EXISTS `surveys` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `stage` enum('pre','post') NOT NULL,
  `type` enum('test','survey') NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`body`)),
  `init_date` date NOT NULL,
  `end_date` date NOT NULL,
  `experiment_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.surveys: ~3 rows (aproximadamente)
DELETE FROM `surveys`;
INSERT INTO `surveys` (`id`, `name`, `description`, `stage`, `type`, `body`, `init_date`, `end_date`, `experiment_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
	(1, 'Encuesta de prueba', 'Encuesta de prueba', 'pre', 'survey', '"[{\\"question\\":\\"Jiji ji jaji\\",\\"type\\":\\"likert\\",\\"minText\\":\\"Totalmente en desacuerdo\\",\\"maxText\\":\\"Totalmente de acuerdo\\"},{\\"question\\":\\"Jujuy\\",\\"type\\":\\"likert\\",\\"minText\\":\\"Totalmente en desacuerdo\\",\\"maxText\\":\\"Totalmente de acuerdo\\"},{\\"question\\":\\"Girasol\\",\\"type\\":\\"likert\\",\\"minText\\":\\"Totalmente en desacuerdo\\",\\"maxText\\":\\"Totalmente de acuerdo\\"},{\\"question\\":\\"22\\",\\"type\\":\\"open\\"},{\\"question\\":\\"Yaaay\\",\\"type\\":\\"likert\\",\\"minText\\":\\"Totalmente en desacuerdo\\",\\"maxText\\":\\"Totalmente de acuerdo\\"},{\\"question\\":\\"Valorant\\",\\"type\\":\\"likert\\",\\"minText\\":\\"Totalmente en desacuerdo\\",\\"maxText\\":\\"Totalmente de acuerdo\\"},{\\"question\\":\\"Radianita\\",\\"type\\":\\"likert\\",\\"minText\\":\\"Totalmente en desacuerdo\\",\\"maxText\\":\\"Totalmente de acuerdo\\"}]"', '2023-06-16', '2023-06-22', 1, NULL, '2023-06-16 08:59:42', '2023-06-16 15:01:39'),
	(2, 'asd', 'asd', 'pre', 'survey', '"[{\\"question\\":\\"asd\\",\\"type\\":\\"likert\\",\\"minText\\":\\"asd\\",\\"maxText\\":\\"asd\\"}]"', '2023-06-16', '2023-06-16', 1, '2023-06-16 14:40:08', '2023-06-16 14:39:51', '2023-06-16 14:40:08'),
	(3, 'asdasd', 'asdasd', 'pre', 'test', '"[{\\"question\\":\\"1 2 3 o 4?\\",\\"type\\":\\"multi\\",\\"options\\":[\\"1\\",\\"2\\",\\"3\\",\\"4\\"],\\"answer\\":\\"0\\"},{\\"question\\":\\"1 2 3 o 4?\\",\\"type\\":\\"open\\",\\"answer\\":\\"4\\"}]"', '2023-06-16', '2023-06-16', 1, NULL, '2023-06-16 15:31:12', '2023-06-16 15:31:31');

-- Volcando estructura para tabla gamehub.survey_responses
CREATE TABLE IF NOT EXISTS `survey_responses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` enum('in progress','finished') DEFAULT 'in progress',
  `checkpoint` int(11) DEFAULT NULL COMMENT 'Last answered question',
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`body`)),
  `user_id` bigint(20) unsigned NOT NULL,
  `survey_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.survey_responses: ~2 rows (aproximadamente)
DELETE FROM `survey_responses`;
INSERT INTO `survey_responses` (`id`, `status`, `checkpoint`, `body`, `user_id`, `survey_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'finished', NULL, '"[{\\"question\\":\\"Jiji ji jaji\\",\\"type\\":\\"likert\\",\\"minText\\":\\"Totalmente en desacuerdo\\",\\"maxText\\":\\"Totalmente de acuerdo\\",\\"answer\\":\\"5\\"},{\\"question\\":\\"Jujuy\\",\\"type\\":\\"likert\\",\\"minText\\":\\"Totalmente en desacuerdo\\",\\"maxText\\":\\"Totalmente de acuerdo\\",\\"answer\\":\\"5\\"},{\\"question\\":\\"Girasol\\",\\"type\\":\\"likert\\",\\"minText\\":\\"Totalmente en desacuerdo\\",\\"maxText\\":\\"Totalmente de acuerdo\\",\\"answer\\":\\"5\\"},{\\"question\\":\\"22\\",\\"type\\":\\"open\\",\\"answer\\":\\"ay\\"},{\\"question\\":\\"Yaaay\\",\\"type\\":\\"likert\\",\\"minText\\":\\"Totalmente en desacuerdo\\",\\"maxText\\":\\"Totalmente de acuerdo\\",\\"answer\\":\\"5\\"},{\\"question\\":\\"Valorant\\",\\"type\\":\\"likert\\",\\"minText\\":\\"Totalmente en desacuerdo\\",\\"maxText\\":\\"Totalmente de acuerdo\\",\\"answer\\":\\"5\\"},{\\"question\\":\\"Radianita\\",\\"type\\":\\"likert\\",\\"minText\\":\\"Totalmente en desacuerdo\\",\\"maxText\\":\\"Totalmente de acuerdo\\",\\"answer\\":\\"5\\"}]"', 3, 1, '2023-06-16 15:14:06', '2023-06-16 15:14:06', NULL),
	(8, 'finished', NULL, '"[{\\"question\\":\\"1 2 3 o 4?\\",\\"type\\":\\"multi\\",\\"options\\":[\\"1\\",\\"2\\",\\"3\\",\\"4\\"],\\"answer\\":\\"0\\",\\"player_answer\\":\\"2\\"},{\\"question\\":\\"1 2 3 o 4?\\",\\"type\\":\\"open\\",\\"answer\\":\\"4\\",\\"player_answer\\":\\"4\\"}]"', 3, 3, '2023-06-16 16:45:29', '2023-06-16 16:45:29', NULL);

-- Volcando estructura para tabla gamehub.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `first_surname` varchar(255) DEFAULT NULL,
  `second_surname` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `grade_id` bigint(20) unsigned DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.users: ~5 rows (aproximadamente)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `first_surname`, `second_surname`, `type`, `email`, `email_verified_at`, `password`, `grade_id`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'admin', NULL, NULL, 'admin', 'admin@pucv.cl', '2023-06-16 08:33:55', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '832rPzZg9U5gTO6nVgxE6obUMi3AcLl2qiFuGN1xDkdRsbbwHU4mXzC8VMgB', '2023-06-16 08:33:55', '2023-06-16 08:33:55'),
	(2, 'tester', NULL, NULL, 'student', 'tester@pucv.cl', '2023-06-16 08:33:55', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'XRMkVxEXYe', '2023-06-16 08:33:55', '2023-06-16 08:33:55'),
	(3, 'estudiante1', NULL, NULL, 'student', 'e1@pucv.cl', '2023-06-16 08:33:55', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'twd3UIE7CWvO1MD9xFaFyE4XeO4si0Qn5QiBYZOrtqxHOQXjg76MqMxobn6p', '2023-06-16 08:33:55', '2023-06-16 08:33:55'),
	(4, 'estudiante2', NULL, NULL, 'student', 'e2@pucv.cl', '2023-06-16 08:33:55', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '8ttfgUSHww', '2023-06-16 08:33:55', '2023-06-16 08:33:55'),
	(5, 'estudiante3', NULL, NULL, 'student', 'e3@pucv.cl', '2023-06-16 08:33:56', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'Oidh2KEWVs', '2023-06-16 08:33:56', '2023-06-16 08:33:56');

-- Volcando estructura para tabla gamehub.user_game_badges
CREATE TABLE IF NOT EXISTS `user_game_badges` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `game_badge_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gamehub.user_game_badges: ~0 rows (aproximadamente)
DELETE FROM `user_game_badges`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

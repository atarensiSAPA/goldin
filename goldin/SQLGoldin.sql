-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para goldin
CREATE DATABASE IF NOT EXISTS `goldin` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `goldin`;

-- Volcando estructura para tabla goldin.clothes
CREATE TABLE IF NOT EXISTS `clothes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `units` int NOT NULL,
  `clothes_img` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `clothes_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla goldin.clothes: ~2 rows (aproximadamente)
INSERT IGNORE INTO `clothes` (`id`, `name`, `type`, `price`, `units`, `clothes_img`, `clothes_url`, `created_at`, `updated_at`) VALUES
	(1, 'Baby_shoes', 'Shoes', 80, 100, 'baby_shoes.png', 'https://th.bing.com/th/id/R.b37f855b242781d6604a11d89138465b?rik=1aLtpb6PYe2n8w&riu=http%3a%2f%2fwww.moonoloog.nl%2fwp-content%2fuploads%2f2016%2f06%2fbabykleding-520x400.jpg&ehk=kgD%2fYAqSQNYOTnbL2qnejMiWjjQ6wQb2kaeaa6ggiDo%3d&risl=&pid=ImgRaw&r=0', '2024-06-11 19:32:39', '2024-06-11 19:32:39'),
	(2, 'Girl_shoes', 'Shoes', 95, 96, 'girl_shoes.png', 'https://stockitaly24.com/cdn/shop/collections/Calzature.jpg?v=1654615481&width=1500', '2024-06-11 19:32:39', '2024-06-11 19:32:39'),
	(3, 'Black_shirt', 'Shirts', 1750, 10, 'black_shirt.webp', 'https://cdn.pixabay.com/photo/2016/12/06/09/30/blank-1886001_1280.png', '2024-06-11 19:32:39', '2024-06-11 19:32:39'),
	(4, 'Green_shirt', 'Shirts', 105, 66, 'green_shirt.webp', 'https://cdn.pixabay.com/photo/2016/03/31/19/21/clothes-1294933_1280.png', '2024-06-11 19:32:39', '2024-06-11 19:32:39'),
	(5, 'Red_hat', 'Hats', 170, 17, 'red_hat.webp', 'https://cdn.pixabay.com/photo/2016/04/01/11/32/hat-1300408_1280.png', '2024-06-11 19:32:39', '2024-06-11 19:32:39'),
	(6, 'Blue_jeans', 'Jeans', 120, 25, 'blue_jeans.webp', 'https://cdn.pixabay.com/photo/2016/03/31/19/24/clothes-1294974_1280.png', '2024-06-11 19:32:39', '2024-06-11 19:32:39'),
	(7, 'Green_socks', 'Socks', 246, 23, 'green_socks.webp', 'https://cdn.pixabay.com/photo/2017/01/31/23/04/clothes-2027993_1280.png', '2024-06-11 19:32:39', '2024-06-11 19:32:39');

-- Volcando estructura para tabla goldin.daily_boxes
CREATE TABLE IF NOT EXISTS `daily_boxes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `box_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost` int NOT NULL,
  `box_img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` int DEFAULT NULL,
  `available` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `daily_boxes_box_name_unique` (`box_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla goldin.daily_boxes: ~7 rows (aproximadamente)
INSERT IGNORE INTO `daily_boxes` (`id`, `box_name`, `cost`, `box_img`, `level`, `available`, `created_at`, `updated_at`) VALUES
	(1, 'Box_level_1', 0, 'level_1.png', 1, 1, '2024-06-11 19:32:39', '2024-06-11 19:32:39'),
	(2, 'Box_level_2', 0, 'level_2.png', 2, 1, '2024-06-11 19:32:39', '2024-06-11 19:32:39'),
	(3, 'Box_level_3', 0, 'level_3.png', 3, 1, '2024-06-11 19:32:39', '2024-06-11 19:32:39'),
	(4, 'Box_level_4', 0, 'level_4.png', 4, 1, '2024-06-11 19:32:39', '2024-06-11 19:32:39'),
	(5, 'Box_level_5', 0, 'level_5.png', 5, 1, '2024-06-11 19:32:39', '2024-06-11 19:32:39'),
	(6, 'Box_level_6', 0, 'level_6.png', 6, 1, '2024-06-11 19:32:39', '2024-06-11 19:32:39'),
	(7, 'Box_level_7', 0, 'level_7.png', 7, 1, '2024-06-11 19:32:39', '2024-06-11 19:32:39');

-- Volcando estructura para tabla goldin.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla goldin.failed_jobs: ~0 rows (aproximadamente)

-- Volcando estructura para tabla goldin.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla goldin.migrations: ~0 rows (aproximadamente)
INSERT IGNORE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(2, '2019_08_19_000000_create_failed_jobs_table', 1),
	(3, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(4, '2024_04_16_100218_create_sessions_table', 1),
	(5, '2024_04_18_000000_create_boxes_clothes_table', 1),
	(6, '2024_04_18_000000_data_boxes_clothes', 1),
	(7, '2024_04_18_000001_boxes_users_table', 1),
	(8, '2024_05_28_131927_update_foreign_keys_on_user_weapon_table', 1);

-- Volcando estructura para tabla goldin.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla goldin.password_reset_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla goldin.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



-- Volcando datos para la tabla goldin.purchase_history: ~0 rows (aproximadamente)

-- Volcando estructura para tabla goldin.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla goldin.sessions: ~0 rows (aproximadamente)

-- Volcando estructura para tabla goldin.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int NOT NULL DEFAULT '0',
  `coins` int NOT NULL DEFAULT '200',
  `level` int NOT NULL DEFAULT '1',
  `experience` int NOT NULL DEFAULT '0',
  `vip_expires_at` timestamp NULL DEFAULT NULL,
  `connected` tinyint(1) NOT NULL DEFAULT '0',
  `is_kicked` tinyint(1) NOT NULL DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `external_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `external_auth` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla goldin.users: ~0 rows (aproximadamente)
INSERT IGNORE INTO `users` (`id`, `name`, `email`, `role`, `coins`, `level`, `experience`, `vip_expires_at`, `connected`, `is_kicked`, `email_verified_at`, `password`, `avatar`, `external_id`, `external_auth`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'admin@sapalomera.cat', 2, 100, 1, 120, NULL, 0, 0, '2024-06-11 19:32:40', '$2y$12$5m8iUgCcgN0SnhhQKKbHaunP/YArQL8B/z3kah.H7CA6sVuTV6me.', NULL, NULL, NULL, NULL, '2024-06-11 19:32:40', '2024-06-11 19:32:40'),
	(2, 'angel', 'a.tarensi2@sapalomera.cat', 0, 800, 1, 220, NULL, 0, 0, '2024-06-11 19:32:40', '$2y$12$Dgfkmb4M756E3ezKXinXcuKifNDKXGwKbHKUguzzrkkF8mbaQH4s2', NULL, NULL, NULL, NULL, '2024-06-11 19:32:40', '2024-06-11 19:32:40'),
	(3, 'prova', 'prova@sapalomera.cat', 0, 1000000, 1, 10000, NULL, 0, 0, '2024-06-11 19:32:40', '$2y$12$HQ651tk1TWej20tdprixluu0MO4iuHMNgZjc4KH82G.5gT3fbVeJq', NULL, NULL, NULL, NULL, '2024-06-11 19:32:40', '2024-06-11 19:32:40');

-- Volcando estructura para tabla goldin.user_boxes
CREATE TABLE IF NOT EXISTS `user_boxes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `box_id` bigint unsigned NOT NULL,
  `last_opened_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_boxes_user_id_foreign` (`user_id`),
  KEY `user_boxes_box_id_foreign` (`box_id`),
  CONSTRAINT `user_boxes_box_id_foreign` FOREIGN KEY (`box_id`) REFERENCES `daily_boxes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_boxes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla goldin.user_boxes: ~0 rows (aproximadamente)

-- Volcando datos para la tabla goldin.personal_access_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla goldin.purchase_history
CREATE TABLE IF NOT EXISTS `purchase_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `clothes_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `price` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_history_user_id_foreign` (`user_id`),
  KEY `purchase_history_clothes_id_foreign` (`clothes_id`),
  CONSTRAINT `purchase_history_clothes_id_foreign` FOREIGN KEY (`clothes_id`) REFERENCES `clothes` (`id`),
  CONSTRAINT `purchase_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour rainbowlog
CREATE DATABASE IF NOT EXISTS `rainbowlog` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `rainbowlog`;

-- Listage de la structure de table rainbowlog. alert
CREATE TABLE IF NOT EXISTS `alert` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_alert` datetime NOT NULL,
  `experience_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_17FD46C146E90E27` (`experience_id`),
  KEY `IDX_17FD46C1A76ED395` (`user_id`),
  CONSTRAINT `FK_17FD46C146E90E27` FOREIGN KEY (`experience_id`) REFERENCES `experience` (`id`),
  CONSTRAINT `FK_17FD46C1A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.alert : ~0 rows (environ)

-- Listage de la structure de table rainbowlog. category
CREATE TABLE IF NOT EXISTS `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.category : ~0 rows (environ)

-- Listage de la structure de table rainbowlog. comment
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_comment` datetime NOT NULL,
  `experience_id` int NOT NULL,
  `consumer_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526C46E90E27` (`experience_id`),
  KEY `IDX_9474526C37FDBD6D` (`consumer_id`),
  CONSTRAINT `FK_9474526C37FDBD6D` FOREIGN KEY (`consumer_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_9474526C46E90E27` FOREIGN KEY (`experience_id`) REFERENCES `experience` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.comment : ~0 rows (environ)

-- Listage de la structure de table rainbowlog. contact
CREATE TABLE IF NOT EXISTS `contact` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date_message` datetime NOT NULL,
  `seen` tinyint(1) NOT NULL,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4C62E638F624B39D` (`sender_id`),
  KEY `IDX_4C62E638CD53EDB6` (`receiver_id`),
  CONSTRAINT `FK_4C62E638CD53EDB6` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_4C62E638F624B39D` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.contact : ~0 rows (environ)

-- Listage de la structure de table rainbowlog. evaluation
CREATE TABLE IF NOT EXISTS `evaluation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `rating` decimal(7,1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1323A575A76ED395` (`user_id`),
  CONSTRAINT `FK_1323A575A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.evaluation : ~0 rows (environ)

-- Listage de la structure de table rainbowlog. experience
CREATE TABLE IF NOT EXISTS `experience` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_creation` datetime NOT NULL,
  `near_town` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `counter_view` int DEFAULT NULL,
  `category_id` int NOT NULL,
  `publish_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_590C10312469DE2` (`category_id`),
  KEY `IDX_590C1038734ED60` (`publish_id`),
  CONSTRAINT `FK_590C10312469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `FK_590C1038734ED60` FOREIGN KEY (`publish_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.experience : ~0 rows (environ)

-- Listage de la structure de table rainbowlog. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.messenger_messages : ~0 rows (environ)

-- Listage de la structure de table rainbowlog. photo
CREATE TABLE IF NOT EXISTS `photo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `experience_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_14B7841846E90E27` (`experience_id`),
  CONSTRAINT `FK_14B7841846E90E27` FOREIGN KEY (`experience_id`) REFERENCES `experience` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.photo : ~0 rows (environ)

-- Listage de la structure de table rainbowlog. reset_password_request
CREATE TABLE IF NOT EXISTS `reset_password_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `selector` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_7CE748AA76ED395` (`user_id`),
  CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.reset_password_request : ~0 rows (environ)

-- Listage de la structure de table rainbowlog. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `pseudo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registration_date` datetime DEFAULT NULL,
  `last_login_date` datetime DEFAULT NULL,
  `author_infos` json DEFAULT NULL,
  `last_profil_edit_time` datetime DEFAULT NULL,
  `is_blocked` int NOT NULL,
  `new_messages` int NOT NULL,
  `rating` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.user : ~3 rows (environ)
INSERT IGNORE INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `pseudo`, `avatar`, `registration_date`, `last_login_date`, `author_infos`, `last_profil_edit_time`, `is_blocked`, `new_messages`, `rating`) VALUES
	(1, 'elena@sfr.fr', '["ROLE_USER"]', '$2y$13$YGjQWmI/ucf45ft1yKj4se2TlFQuXsTFTMR4dp3zo0j/PwUfHBrIS', 1, 'elena', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL),
	(6, 'jean@sfr.fr', '["ROLE_USER"]', '$2y$13$iRGeVSNjBDG5UAa.H5yqMOlpR7d3Ru1laUAeyOk6OKxgEkgWj.T1O', 1, 'jean', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL),
	(7, 'dog@sfr.fr', '["ROLE_USER"]', '$2y$13$wcrQK/6ftSxKzIf2qxlsB.h8/JXa/0Yu/vShTfITJ5R.44ibPMLCy', 0, 'dog', NULL, NULL, NULL, NULL, NULL, 0, 0, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

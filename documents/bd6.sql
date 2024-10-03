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
  `experience_id` int NOT NULL,
  `user_id` int NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_alert` datetime NOT NULL,
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
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.category : ~23 rows (environ)
INSERT INTO `category` (`id`, `name`) VALUES
	(1, 'Abstract'),
	(2, 'Architecture'),
	(3, 'Black and white'),
	(4, 'City'),
	(5, 'Digital art'),
	(6, 'Fragment'),
	(7, 'Glamour'),
	(8, 'Humor'),
	(9, 'Interior'),
	(10, 'Landscape'),
	(11, 'Machinery'),
	(12, 'Macro and close-up'),
	(13, 'Montage'),
	(14, 'Nature'),
	(15, 'Old time'),
	(16, 'Panaromic'),
	(17, 'Portrait'),
	(18, 'Reportage'),
	(19, 'Street'),
	(20, 'Sport'),
	(21, 'Still life'),
	(22, 'Travel'),
	(23, 'Underwater');

-- Listage de la structure de table rainbowlog. comment
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `text` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_comment` datetime NOT NULL,
  `experience_id` int NOT NULL,
  `consumer_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526C46E90E27` (`experience_id`),
  KEY `IDX_9474526C37FDBD6D` (`consumer_id`),
  CONSTRAINT `FK_9474526C37FDBD6D` FOREIGN KEY (`consumer_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_9474526C46E90E27` FOREIGN KEY (`experience_id`) REFERENCES `experience` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.comment : ~1 rows (environ)
INSERT INTO `comment` (`id`, `text`, `date_comment`, `experience_id`, `consumer_id`) VALUES
	(1, 'Very nice!', '2024-10-03 13:52:38', 11, 3);

-- Listage de la structure de table rainbowlog. contact
CREATE TABLE IF NOT EXISTS `contact` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `date_message` datetime NOT NULL,
  `seen` tinyint(1) NOT NULL,
  `text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `rating` decimal(7,1) DEFAULT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1323A575A76ED395` (`user_id`),
  CONSTRAINT `FK_1323A575A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.evaluation : ~0 rows (environ)

-- Listage de la structure de table rainbowlog. experience
CREATE TABLE IF NOT EXISTS `experience` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_creation` datetime NOT NULL,
  `near_town` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `counter_view` int DEFAULT NULL,
  `publish_id` int NOT NULL,
  `devices` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_590C10312469DE2` (`category_id`),
  KEY `IDX_590C1038734ED60` (`publish_id`),
  CONSTRAINT `FK_590C10312469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `FK_590C1038734ED60` FOREIGN KEY (`publish_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.experience : ~12 rows (environ)
INSERT INTO `experience` (`id`, `category_id`, `title`, `date_creation`, `near_town`, `description`, `counter_view`, `publish_id`, `devices`) VALUES
	(1, 14, 'Butterfly 1', '2024-10-03 14:38:45', 'Strasbourg', 'Photo of the butterfly 1 made in this summer', NULL, 3, 'Digital camera Nikon1 S1'),
	(2, 14, 'Butterfly 2', '2024-10-03 14:44:25', 'Strasbourg', 'Photo of the butterfly 2 made in this summer', NULL, 3, 'Digital camera  Nikon 1 S1'),
	(3, 14, 'Butterfly 3', '2024-10-03 14:45:55', 'Strasbourg', 'Photo of the butterfly 3 made in this summer', NULL, 3, 'Digital camera Nikon 1 S1'),
	(4, 14, 'Butterfly 4', '2024-10-03 14:48:15', 'Strasbourg', 'Photo of the butterfly 4 made in this summer', NULL, 3, 'Digital camera Nikon 1 S1'),
	(5, 14, 'Butterfly 5 ', '2024-10-03 14:51:12', 'Strasbourg', 'Photo of the butterfly 5 made in this summer', NULL, 3, 'Digital camera Nikon 1 S1'),
	(6, 14, 'Butterfly 6', '2024-10-03 14:52:59', 'Strasbourg', 'Photo of the butterfly 6 made in this summer', NULL, 3, 'Digital camera Nikon 1 S1'),
	(7, 14, 'Butterfly 7', '2024-10-03 14:57:03', 'Strasbourg', 'Photo of the butterfly 7 made in this summer', NULL, 3, 'Digital camera Nikon 1 S1'),
	(8, 14, 'Butterfly 8', '2024-10-03 14:58:27', 'Strasbourg', 'Photo of the butterfly 8 made in this summer', NULL, 3, 'Digital camera Nikon 1 S1'),
	(9, 14, 'Butterfly 9', '2024-10-03 14:59:29', 'Strasbourg', 'Photo of the butterfly 9 made in this summer', NULL, 3, 'Digital camera Nikon 1 S1'),
	(10, 14, 'Butterfly 10', '2024-10-03 15:00:36', 'Strasbourg', 'Photo of the butterfly 10 made in this summer', NULL, 3, 'Digital camera Nikon 1 S1'),
	(11, 14, 'Butterfly 11', '2024-10-03 15:04:25', 'Strasbourg', 'Photo of the butterfly 11 made in this summer', NULL, 3, 'Digital camera Nikon 1 S1'),
	(12, 14, 'Butterfly 12', '2024-10-03 15:05:29', 'Strasbourg', 'Photo of the butterfly 12 made in this summer', NULL, 3, 'Digital camera Nikon 1 S1');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `new_messages` int DEFAULT NULL,
  `last_profil_edit_time` datetime DEFAULT NULL,
  `is_blocked` tinyint(1) DEFAULT NULL,
  `rating` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.user : ~3 rows (environ)
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `pseudo`, `avatar`, `registration_date`, `last_login_date`, `author_infos`, `new_messages`, `last_profil_edit_time`, `is_blocked`, `rating`) VALUES
	(1, 'admin@rainbowlog.com', '["ROLE_ADMIN"]', '$2y$13$qio8vEFnypfctlY3MAdI3.fdkgUoGXSFpQvUVIlZdjh.8GBeuw6a.', 1, 'admin', NULL, NULL, NULL, NULL, NULL, '2024-09-30 14:35:11', 0, NULL),
	(2, 'anonyme_da22100502@domain.com', '["ROLE_USER"]', '$2y$13$qYzWaAIU7jVUCdXvz8WrHeDdULA3CJTYECsoHTCyy34jn6xUXhxfS', 1, 'Anonyme', NULL, NULL, NULL, NULL, NULL, '2024-09-30 14:35:04', 1, NULL),
	(3, 'elena@sfr.fr', '["ROLE_USER"]', '$2y$13$KErOwzCBVcHwx2NdZZu45ONw8ZPCLnuesLgST3Li18hpub/Rpb5h2', 1, 'Elena', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

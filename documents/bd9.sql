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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.alert : ~3 rows (environ)
INSERT INTO `alert` (`id`, `experience_id`, `user_id`, `type`, `text`, `date_alert`) VALUES
	(1, 5, 5, 'unacceptable', 'It needs to add some photo', '2024-10-11 15:21:35'),
	(2, 13, 3, 'other', 'It\'s not a photo, it\'s a picture!', '2024-10-11 15:38:34'),
	(3, 15, 9, 'other', 'It\'s too artificial photo', '2024-10-25 21:51:16');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.comment : ~4 rows (environ)
INSERT INTO `comment` (`id`, `text`, `date_comment`, `experience_id`, `consumer_id`) VALUES
	(1, 'Very nice!', '2024-10-03 13:52:38', 11, 3),
	(3, 'This is very splendid! I\'d like to know the used technique!', '2024-10-07 13:04:23', 13, 3),
	(4, 'I used the layer\'s superposition due to PHOTOSHOP', '2024-10-07 18:51:02', 13, 5),
	(6, 'This photo like a drawn picture', '2024-10-25 21:52:50', 15, 9);

-- Listage de la structure de table rainbowlog. contact
CREATE TABLE IF NOT EXISTS `contact` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `date_message` datetime NOT NULL,
  `seen` tinyint(1) DEFAULT NULL,
  `text` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4C62E638F624B39D` (`sender_id`),
  KEY `IDX_4C62E638CD53EDB6` (`receiver_id`),
  CONSTRAINT `FK_4C62E638CD53EDB6` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_4C62E638F624B39D` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.contact : ~14 rows (environ)
INSERT INTO `contact` (`id`, `sender_id`, `receiver_id`, `date_message`, `seen`, `text`) VALUES
	(1, 3, 5, '2024-10-07 22:14:55', 1, 'Hello, how do you do? I would like to receive one advice concerning the layer\'s superposition in Photoshop. The best regards. Elena'),
	(2, 5, 3, '2024-10-07 23:12:17', 1, 'Hello Elena! I \'d give the advice next week. I\'m very busy now. Best regards. Jean'),
	(3, 3, 5, '2024-10-07 23:19:25', 1, 'Thank you very much! Elena'),
	(5, 3, 5, '2024-10-11 13:02:18', 1, 'Hello Jean! I found a very useful book about special effects in PHOTOSHOP.'),
	(6, 5, 3, '2024-10-11 13:12:45', 1, 'Hello Elena, i\'m very interested by this new book, would you like to give me its referrences please?'),
	(7, 5, 3, '2024-10-11 13:20:16', 1, 'Hello Elena! I could give you some references of books on photography too.'),
	(8, 3, 5, '2024-10-11 13:27:16', 1, 'Please send me this list of books!'),
	(9, 5, 1, '2024-10-11 20:40:51', 1, 'Hello, would you like to publish the list of winners of last competition please?'),
	(10, 1, 9, '2024-10-11 21:03:36', 1, 'Hello Cat! Welcome to our community!'),
	(11, 1, 5, '2024-10-11 21:19:10', 1, 'Ok, I\'ll do it soon.'),
	(12, 5, 9, '2024-10-11 21:27:10', 1, 'Hello Cat!'),
	(13, 3, 7, '2024-10-11 21:43:31', 1, 'Hello Fish'),
	(14, 1, 9, '2024-10-12 18:11:37', 1, 'Hello Cat! I\'ll absent tomorrow'),
	(16, 5, 5, '2024-10-13 03:29:53', 1, 'I remember me to answer all questions of senders during this morning');

-- Listage de la structure de table rainbowlog. evaluation
CREATE TABLE IF NOT EXISTS `evaluation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `rating` decimal(7,1) DEFAULT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1323A575A76ED395` (`user_id`),
  CONSTRAINT `FK_1323A575A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.evaluation : ~19 rows (environ)
INSERT INTO `evaluation` (`id`, `rating`, `user_id`) VALUES
	(1, 4.0, 5),
	(2, 5.0, 5),
	(3, 5.0, 5),
	(4, 2.0, 5),
	(5, 4.0, 3),
	(6, 2.0, 7),
	(7, 4.0, 3),
	(8, 3.0, 1),
	(9, 2.0, 7),
	(10, 1.0, 6),
	(11, 2.0, 1),
	(12, 3.0, 3),
	(13, 5.0, 3),
	(14, 1.0, 1),
	(15, 5.0, 3),
	(16, 1.0, 3),
	(17, 4.0, 3),
	(18, 1.0, 3),
	(19, 4.0, 3);

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
  `devices` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_590C10312469DE2` (`category_id`),
  KEY `IDX_590C1038734ED60` (`publish_id`),
  CONSTRAINT `FK_590C10312469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `FK_590C1038734ED60` FOREIGN KEY (`publish_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.experience : ~25 rows (environ)
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
	(12, 14, 'Butterfly 12', '2024-10-03 15:05:29', 'Strasbourg', 'Photo of the butterfly 12 made in this summer', NULL, 3, 'Digital camera Nikon 1 S1'),
	(13, 1, 'Collage', '2024-10-07 10:47:23', 'STRASBOURG', 'I combined my photo with Photoshop effects', NULL, 5, 'Digital camera Canon DC40 and Photoshop'),
	(15, 1, 'Butterfly\'s collage', '2024-10-07 12:30:38', 'ROSSFELD', 'I mixed different technologies', NULL, 3, 'Digital camera Canon DC40 and Photoshop'),
	(16, 12, 'Butterfly 13', '2024-10-07 17:52:34', 'London', 'Photo was made in London\'s zoo', NULL, 3, 'Samsung Galaxy S24'),
	(17, 12, 'Butterfly 14', '2024-10-07 17:53:47', 'London', 'Photo was made in London\'s zoo', NULL, 3, 'Samsung Galaxy S24'),
	(18, 12, 'Butterfly 15', '2024-10-07 17:55:04', 'London', 'Photo was made in London\'s zoo', NULL, 3, 'Samsung Galaxy S24'),
	(19, 12, 'Butterfly 16', '2024-10-07 17:56:00', 'London', 'Photo was made in London\'s zoo', NULL, 3, 'Samsung Galaxy S24'),
	(20, 12, 'Butterfly 17', '2024-10-07 17:56:41', 'London', 'Photo was made in London\'s zoo', NULL, 3, 'Samsung Galaxy S24'),
	(21, 12, 'Butterfly 18', '2024-10-07 17:58:09', 'London', 'Photo was made in London\'s zoo', NULL, 3, 'Samsung Galaxy S24'),
	(22, 4, 'Evening', '2024-10-13 00:36:03', 'Budapest', 'Town in the winter', NULL, 7, 'Samsung Galaxy S24'),
	(23, 4, 'Evening 2', '2024-10-13 00:49:07', 'Budapest', 'View from the mountain', NULL, 7, 'Samsung Galaxy S24'),
	(24, 17, 'Picture 1', '2024-10-26 04:24:35', 'LILLE', 'Graphics', NULL, 3, 'Smartphone GALAXY'),
	(25, 17, 'Picture 2', '2024-10-26 04:26:51', 'LILLE', 'Graphics', NULL, 3, 'Smartphone GALAXY'),
	(26, 17, 'Picture 3', '2024-10-26 04:28:06', 'LILLE', 'Graphics', NULL, 3, 'Smartphone GALAXY');

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.photo : ~13 rows (environ)
INSERT INTO `photo` (`id`, `title`, `path`, `experience_id`) VALUES
	(1, 'Collage', '/img/6703bc3c3ffd8.webp', 13),
	(2, 'Butterfly\'s collage', '/img/6703d46ea5b32.webp', 15),
	(3, 'Butterfly 13', '/img/67041fe3826c2.webp', 16),
	(4, 'Butterfly 14', '/img/6704202b94501.webp', 17),
	(5, 'Butterfly 15', '/img/6704207849500.webp', 18),
	(6, 'Butterfly 16', '/img/670420b05da5f.webp', 19),
	(7, 'Butterfly 17', '/img/670420d9d705e.webp', 20),
	(8, 'Butterfly 18', '/img/67042131586ee.webp', 21),
	(9, 'Evening', '/img/670b15f3b13e2.jpg', 22),
	(10, 'Evening 2', '/img/670b18cc265fb.jpg', 23),
	(11, 'Picture 1', '/img/671c6f036873d.jpg', 24),
	(12, 'Picture 2', '/img/671c6f8b58473.webp', 25),
	(13, 'Picture 3', '/img/671c6fd710717.webp', 26);

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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table rainbowlog.user : ~8 rows (environ)
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `pseudo`, `avatar`, `registration_date`, `last_login_date`, `author_infos`, `new_messages`, `last_profil_edit_time`, `is_blocked`, `rating`) VALUES
	(1, 'admin@rainbowlog.com', '["ROLE_ADMIN"]', '$2y$13$T3lPaMjR7vNBgYdsc3b10umV72Vls9YDSR0pNrFU1u0SYp98TtIqG', 1, 'admin', NULL, NULL, NULL, NULL, 0, '2024-09-30 14:35:11', 0, NULL),
	(2, 'anonyme_636a9ca1ab@domain.com', '["ROLE_USER"]', '$2y$13$qYzWaAIU7jVUCdXvz8WrHeDdULA3CJTYECsoHTCyy34jn6xUXhxfS', 1, 'Anonyme', NULL, NULL, NULL, NULL, NULL, '2024-09-30 14:35:04', 1, NULL),
	(3, 'elena@sfr.fr', '["ROLE_USER"]', '$2y$13$KErOwzCBVcHwx2NdZZu45ONw8ZPCLnuesLgST3Li18hpub/Rpb5h2', 1, 'Elena', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL),
	(5, 'jean@sfr.fr', '["ROLE_USER"]', '$2y$13$4fuHftUFnPFFsaXdeohbe.QV5n/Lh/R6hXtlVq3Mze5fsy/Dse/Aa', 1, 'Jean', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
	(6, 'anonyme_c749348f7b@domain.com', '["ROLE_USER"]', '$2y$13$terHrC5sTEQkjJRyCqoAS.80x3MBcx8DV3yWBz9hOb7ebJo6BcQMe', 1, 'Anonyme', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL),
	(7, 'fish@sfr.fr', '["ROLE_USER"]', '$2y$13$4NjYfAnClAu.cR1VA1Qsf./rnK4tmT/fQuy/jm1WOIXbCxeWYjlAG', 1, 'Fish', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
	(8, 'anonyme_6cbcc62392@domain.com', '["ROLE_USER"]', '$2y$13$8/BF9MipE6e1e8ad7T.Ju.UMF0d2CsioSK5YorRi3e9q4OgxziHAm', 1, 'Anonyme', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL),
	(9, 'cat@sfr.fr', '["ROLE_USER"]', '$2y$13$5cPhjRuhyhQEdajMCkKPI.56Oi5hLGepLlT7bnqH6xfkIoJedYq6K', 1, 'Cat', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

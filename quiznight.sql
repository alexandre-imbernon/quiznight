-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 11 juin 2024 à 07:51
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Base de données : `quiznight`
USE quiznight;

-- Structure de la table `answers`
DROP TABLE IF EXISTS `answers`;
CREATE TABLE IF NOT EXISTS `answers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `question_id` int NOT NULL,
  `answer_text` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Déchargement des données de la table `answers`
INSERT INTO `answers` (`id`, `question_id`, `answer_text`, `is_correct`) VALUES
(38, 12, 'Samsung', 1),
(37, 12, 'Iphone', 0),
(36, 11, 'porcelaine', 0),
(35, 11, 'plastique', 0),
(34, 11, 'Bois', 1),
(33, 11, 'Fer', 0),
(29, 10, 'Rouge', 0),
(30, 10, 'Bleu', 1),
(13, 6, 'allemande', 1),
(14, 6, 'francaise', 0),
(15, 6, 'us', 0),
(16, 6, 'japoanaise', 0),
(31, 10, 'Blanc', 0),
(32, 10, 'Beige', 0),
(39, 12, 'Huawei', 0),
(40, 12, 'Nokia', 0);

-- Structure de la table `questions`
DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `question_text` text NOT NULL,
  `created_at` timestamp NOT NULL,
  `quiz_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Déchargement des données de la table `questions`
INSERT INTO `questions` (`id`, `user_id`, `question_text`, `created_at`, `quiz_id`) VALUES
(6, 9, 'Voiture', '0000-00-00 00:00:00', 7),
(10, 9, 'Quel couleur de coussin?', '0000-00-00 00:00:00', 15),
(11, 9, 'QUelle table ?', '0000-00-00 00:00:00', 16),
(12, 9, 'Quel est votre telephone ?', '0000-00-00 00:00:00', 17);

-- Structure de la table `quizzes`
DROP TABLE IF EXISTS `quizzes`;
CREATE TABLE IF NOT EXISTS `quizzes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_user` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Déchargement des données de la table `quizzes`
INSERT INTO `quizzes` (`id`, `user_id`, `title`, `created_at`) VALUES
(1, 7, 'Quiz Zelda', '2024-06-10 09:14:14'),
(2, 7, 'Quiz Pokemon', '2024-06-10 09:14:19'),
(3, 7, 'Quiz Pokemon', '2024-06-10 09:14:31'),
(4, 8, 'Quiz chat', '2024-06-10 09:15:42'),
(7, 9, 'Voiture', '2024-06-10 12:31:59'),
(15, 9, 'Coussin', '2024-06-10 13:40:23'),
(16, 9, 'Table', '2024-06-10 14:05:53'),
(17, 9, 'Telephone', '2024-06-11 07:08:39');

-- Structure de la table `users`
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `username_2` (`username`),
  UNIQUE KEY `username_3` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Déchargement des données de la table `users`
INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(8, 'Asmaa', '$2y$10$bjdsIpwIQW/WSCoye9TNy./n.skd8mcd.17yqh.KZw/s4DtjN3d1q', '2024-06-10 09:15:14'),
(7, 'Alex', '$2y$10$AnF4iJ4sJomWIy4HnQw6PuQhKc.kv3byxSodqbCdQgdmBtgLb4PgO', '2024-06-10 09:13:07'),
(9, 'Adel', '$2y$10$y3nU2ZnJkadZH4OROdLeC.SJ7Gy.rGIqqQ4aLIGuJ0AZbK05k8MmW', '2024-06-10 09:34:19'),
(10, 'momo', '$2y$10$f9JEC/RfK4sH23tw4ABI7Oo5CEi4IOX/2BoZcGBG3ZKx/fNekrwcq', '2024-06-10 13:09:01');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

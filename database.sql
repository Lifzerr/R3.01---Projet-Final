-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 09 oct. 2024 à 17:24
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mbourciez_pro`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `prix` decimal(10,2) NOT NULL,
  `imageId` int DEFAULT NULL,
  `categorieId` int DEFAULT NULL,
  `quantiteDispo` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `imageId` (`imageId`),
  KEY `categorieId` (`categorieId`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id`, `titre`, `description`, `prix`, `imageId`, `categorieId`, `quantiteDispo`) VALUES
(1, 'Casque M1', 'Casque utilisé par les forces américaines durant la Seconde Guerre mondiale.', 120.00, 1, 1, 10),
(2, 'Enigma', 'Machine de cryptage utilisée par les Allemands pour sécuriser leurs communications.', 500000.00, 2, 4, 5),
(3, 'Panzer IV', 'Char de combat allemand, modèle très utilisé sur le front de l Est.', 300000.00, 3, 3, 2),
(4, 'Grenade Manche', 'Grenade à main utilisée par les soldats durant la guerre.', 25.00, 4, 10, 15),
(5, 'Grenade Mk 2', 'Grenade à main standardisée des forces américaines.', 30.00, 5, 10, 20),
(6, 'Luger', 'Pistolet semi-automatique utilisé par l armée allemande.', 500.00, 6, 2, 8),
(7, 'Masque à Gaz', 'Masque utilisé pour protéger contre les gaz toxiques.', 80.00, 7, 4, 12),
(8, 'MG42', 'Mitrailleuse allemande utilisée pendant la Seconde Guerre mondiale.', 2000.00, 8, 2, 4),
(10, 'Tank Panzer', 'Tank de la Wehrmacht, célèbre pour sa robustesse.', 250000.00, 10, 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id`, `nom`) VALUES
(1, 'Vêtements'),
(2, 'Armes'),
(3, 'Véhicules'),
(4, 'Équipements'),
(5, 'Armes'),
(7, 'Uniformes'),
(9, 'Décorations'),
(10, 'Grenades');

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id` int NOT NULL AUTO_INCREMENT,
  `chemin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `chemin`, `alt`) VALUES
(1, 'images/casque_m1.jpg', 'Casque M1'),
(2, 'images/enigma.jpg', 'Machine Enigma'),
(3, 'images/panzer_iv.jpg', 'Char Panzer IV'),
(4, 'images/grenade_manche.jpg', 'Grenade Manche'),
(5, 'images/grenade_mk2.jpg', 'Grenade Mk 2'),
(6, 'images/luger.jpg', 'Luger'),
(7, 'images/masque_gaz.jpg', 'Masque à Gaz'),
(8, 'images/mg42.jpg', 'MG42'),
(9, 'images/sten.jpg', 'Sten'),
(10, 'images/tank_panzer.jpg', 'Tank Panzer');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `Article_ibfk_1` FOREIGN KEY (`imageId`) REFERENCES `image` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `Article_ibfk_2` FOREIGN KEY (`categorieId`) REFERENCES `categorie` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 19 oct. 2024 à 14:05
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
  `descriptionLongue` text COLLATE utf8mb4_unicode_ci,
  `prix` decimal(10,2) NOT NULL,
  `imageId` int DEFAULT NULL,
  `categorieId` int DEFAULT NULL,
  `quantiteDispo` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `imageId` (`imageId`),
  KEY `categorieId` (`categorieId`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id`, `titre`, `description`, `descriptionLongue`, `prix`, `imageId`, `categorieId`, `quantiteDispo`) VALUES
(1, 'Casque M1', 'Casque utilisé par les forces américaines durant la Seconde Guerre mondiale.', 'Casque M111, utilisé par les soldats américains durant la Seconde Guerre mondiale, ce casque a été un symbole de protection et de courage sur les champs de bataille. Conçu pour offrir une protection maximale contre les éclats d\'obus et les balles légères, il est aujourd\'hui un objet de collection prisé.', 150.00, 1, 2, 45),
(2, 'Enigma', 'Machine de cryptage utilisée par les Allemands pour sécuriser leurs communications.', 'L’Enigma est une machine de cryptage allemande, connue pour son rôle crucial dans la sécurité des communications durant la Seconde Guerre mondiale. Utilisée par l’armée et la marine, elle a été décryptée par les Alliés grâce aux efforts de mathématiciens comme Alan Turing, changeant le cours de la guerre.', 500000.00, 2, 4, 4),
(3, 'Panzer IV', 'Char de combat allemand, modèle très utilisé sur le front de l’Est.', 'Le Panzer IV était un char de combat polyvalent déployé par l\'armée allemande sur divers fronts, en particulier sur le front de l\'Est. Doté d’un blindage et d’une puissance de feu significatifs, il a joué un rôle clé dans de nombreuses batailles et reste l\'un des véhicules militaires les plus emblématiques de la guerre.', 300000.00, 3, 3, 0),
(4, 'Grenade Manche', 'Grenade à main utilisée par les soldats durant la guerre.', 'La grenade à manche, également connue sous le nom de \"Stielhandgranate\", était un outil crucial pour les soldats allemands. Conçue pour être lancée sur des cibles ennemies avec précision, elle est devenue un symbole des combats d\'infanterie durant la Seconde Guerre mondiale.', 25.00, 4, 7, 15),
(5, 'Grenade Mk 2', 'Grenade à main standardisée des forces américaines.', 'La grenade Mk 2, aussi surnommée \"pineapple\" pour sa forme, était l’équipement standard des forces américaines pendant la Seconde Guerre mondiale. Fiable et efficace, elle a été utilisée dans de nombreux théâtres d\'opération et reste un symbole historique des forces alliées.', 30.00, 5, 3, 20),
(7, 'Masque à Gaz', 'Masque utilisé pour protéger contre les gaz toxiques.', 'Le masque à gaz était un équipement de survie essentiel pour les soldats exposés aux gaz toxiques durant les combats de la Première et de la Seconde Guerre mondiale. Conçu pour protéger contre les agents chimiques, il est devenu un symbole de la guerre moderne et de la lutte pour la survie.', 80.00, 7, 4, 12),
(10, 'Tank Panzer', 'Tank de la Wehrmacht, célèbre pour sa robustesse.', 'Le Tank Panzer, modèle utilisé par les forces de la Wehrmacht, est réputé pour sa robustesse et sa puissance de feu. Ces tanks ont marqué l’histoire des combats blindés et restent des objets d’étude et de collection pour les passionnés de véhicules militaires.', 250000.00, 10, 3, 1),
(11, 'Thompson 1928', 'La Thompson 1928 est une mitraillette américaine des années 1920, célèbre pour sa cadence rapide et son usage durant la Prohibition.', 'La Thompson 1928, également connue sous le nom de \"Tommy Gun\", était une mitraillette emblématique des années 1920, utilisée tant par les forces armées américaines que par les gangsters de l’époque de la Prohibition. Sa cadence de tir rapide et son design unique en ont fait une arme mythique.', 75000.00, 11, 2, 3),
(32, 'Voiture d\'Adolf Hitler', 'Voiture officielle d\'Adolf Hitler, utilisée comme symbole de pouvoir durant la Seconde Guerre mondiale.', 'La voiture d\'Adolf Hitler, utilisée comme symbole de pouvoir durant le régime nazi, est un objet historique unique. Représentant le luxe et l’autorité, cette voiture est un témoignage des excès et de l’idéologie du pouvoir durant cette période sombre de l’histoire.', 6000000.00, 12, 3, 1),
(33, 'Yakovlev Yak-3', 'Avion utilisé par l\'armée russe durant la Seconde Guerre mondiale.', 'Le Yakovlev Yak-3 est un chasseur monoplace soviétique de la Seconde Guerre mondiale, conçu par le bureau d\'études Yakovlev. Mis en service en 1944, il était l’un des avions de chasse les plus légers et les plus maniables de son époque, ce qui le rendait particulièrement redoutable en combat aérien. Avec une vitesse maximale de près de 650 km/h et un armement composé de canons et de mitrailleuses, le Yak-3 excella sur le front de l’Est, dominant les chasseurs ennemis. Son efficacité, combinée à sa robustesse, en fit l’un des avions favoris des pilotes soviétiques, qui le surnommaient \"la petite merveille\".', 100000.00, 14, 3, 2),
(34, 'Tiger I', 'Le Panzerkampfwagen VI Tiger, connu sous le nom de Tiger I, un char allemand emblématique de la Seconde Guerre mondiale.', 'Le Panzerkampfwagen VI Tiger, ou Tiger I, était un char lourd de l’armée allemande utilisé à partir de 1942. Conçu pour surpasser les chars ennemis en termes de puissance de feu et de protection, il était équipé d’un canon de 88 mm, capable de détruire la plupart des blindés alliés à longue distance. Son blindage épais en faisait un adversaire redoutable sur le champ de bataille. Cependant, malgré sa puissance, le Tiger I souffrait de problèmes de fiabilité mécanique, ce qui limitait son efficacité opérationnelle. Aujourd\'hui, il reste un symbole emblématique de la puissance des forces blindées allemandes durant la guerre, et il est exposé dans plusieurs musées.', 250000.00, 15, 3, 6),
(35, 'M4 Sherman Tank', 'Le M4 Sherman est un char moyen américain qui a joué un rôle crucial pendant la Seconde Guerre mondiale, en particulier dans les campagnes en Europe et dans le Pacifique.', 'Le M4 Sherman a été introduit en 1942 et est rapidement devenu le char le plus produit pendant la guerre. Conçu pour être polyvalent, il a été utilisé dans divers rôles, y compris comme char d\'assaut, char de commandement et véhicule de transport de troupes. Doté d\'un blindage relativement épais et d\'un canon de 75 mm, le Sherman a été apprécié pour sa fiabilité et sa capacité à être réparé facilement sur le champ de bataille. Environ 50 000 unités ont été produites, et il a servi dans de nombreuses armées alliées. Les caractéristiques techniques incluent une vitesse maximale d\'environ 40 km/h, un poids d\'environ 30 tonnes et un équipage de cinq personnes.', 330000.00, 16, 3, 50);

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
(5, 'Uniformes'),
(6, 'Décorations'),
(7, 'Grenades');

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `chemin`, `alt`) VALUES
(1, 'images/Casque M1.jpg', 'Casque M1'),
(2, 'images/Enigma.jpg', 'Machine Enigma'),
(3, 'images/Panzer IV.jpg', 'Char Panzer IV'),
(4, 'images/Grenade Manche.jpg', 'Grenade Manche'),
(5, 'images/Grenade MK2.jpg', 'Grenade Mk 2'),
(7, 'images/Masque à Gaz.jpg', 'Masque à Gaz'),
(10, 'images/Tank Panzer.jpg', 'Tank Panzer'),
(11, 'images/Thompson 1928.jpg', 'Thompson 1928'),
(12, 'images/Sans titre.jpeg', 'Voiture du H'),
(13, 'images/yavkolev.jpeg', 'Yakovlev Yak-3'),
(14, 'images/yavkolev.jpeg', 'Yakovlev Yak-3. - Avion'),
(15, 'images/Tiger-I.jpg', 'Tiger I - Panzerkampfwagen VI Tiger'),
(16, 'images/sherman-m4.jpg', 'M4 Sherman Tank');

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

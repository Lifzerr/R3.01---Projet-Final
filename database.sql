
DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `id` decimal(10,0) NOT NULL,
  `titre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` decimal(6,0) NOT NULL,
  `qteDisp` decimal(10,0) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



INSERT INTO `article` (`id`, `titre`, `description`, `prix`, `qteDisp`) VALUES
(1, 'Casque M1', 'Casque utilisé par les forces américaines durant la Seconde Guerre mondiale.', 120, 15),
(2, 'Thompson M1928', 'Mitraillette automatique utilisée par les troupes américaines et britanniques.', 800, 5),
(3, 'Panzer IV', 'Char de combat allemand, modèle très utilisé sur le front de l\'Est.', 300000, 2),
(4, 'Enigma', 'Machine de cryptage utilisée par les Allemands pour sécuriser leurs communications.', 500000, 1),
(5, 'Spitfire Mk V', 'Avion de chasse britannique célèbre pour sa vitesse et sa maniabilité.', 999999, 3),
(6, 'Uniforme SS', 'Uniforme des troupes d\'élite allemandes, en excellent état.', 500, 10),
(7, 'Médaille de la Croix de Guerre', 'Décoration militaire française décernée pour bravoure.', 250, 20),
(8, 'Sten MK II', 'Pistolet mitrailleur britannique simple et efficace, utilisé pour des missions de résistance.', 400, 8),
(9, 'Carabine Mosin-Nagant', 'Fusil de précision soviétique, utilisé par les snipers du front de l\'Est.', 150, 12),
(10, 'Jeep Willys MB', 'Véhicule tout-terrain léger utilisé par les troupes alliées.', 10000, 4);



DROP TABLE IF EXISTS `comporter`;
CREATE TABLE IF NOT EXISTS `comporter` (
  `articleId` decimal(10,0) NOT NULL,
  `imageId` decimal(10,0) NOT NULL,
  PRIMARY KEY (`articleId`,`imageId`),
  KEY `fk_article` (`articleId`),
  KEY `fk_image` (`imageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;





DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id` decimal(10,0) NOT NULL,
  `chemin` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `articleId` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_article` (`articleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `image`
  ADD CONSTRAINT `fk_article` FOREIGN KEY (`articleId`) REFERENCES `article` (`id`);
COMMIT;



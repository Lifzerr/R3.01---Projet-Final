SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


--
-- Structure de la table `Image`
--

CREATE TABLE `Image` (
  `id` int(11) NOT NULL,
  `chemin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `Image`
--

INSERT INTO `Image` (`id`, `chemin`, `alt`) VALUES
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
-- Index pour les tables déchargées
--

--
-- Index pour la table `Image`
--
ALTER TABLE `Image`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Image`
--
ALTER TABLE `Image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;



--
-- Structure de la table `Categorie`
--

CREATE TABLE `Categorie` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `Categorie`
--

INSERT INTO `Categorie` (`id`, `nom`) VALUES
(1, 'Vêtements'),
(2, 'Armes'),
(3, 'Véhicules'),
(4, 'Équipements');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Categorie`
--
ALTER TABLE `Categorie`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Categorie`
--
ALTER TABLE `Categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;


--
-- Structure de la table `Article`
--

CREATE TABLE `Article` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `prix` decimal(10,2) NOT NULL,
  `imageId` int(11) DEFAULT NULL,
  `categorieId` int(11) DEFAULT NULL,
  `quantiteDispo` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `Article`
--

INSERT INTO `Article` (`id`, `titre`, `description`, `prix`, `imageId`, `categorieId`, `quantiteDispo`) VALUES
(1, 'Casque M1', 'Casque utilisé par les forces américaines durant la Seconde Guerre mondiale.', '120.00', 1, 1, 10),
(2, 'Enigma', 'Machine de cryptage utilisée par les Allemands pour sécuriser leurs communications.', '500000.00', 2, 4, 5),
(3, 'Panzer IV', 'Char de combat allemand, modèle très utilisé sur le front de l’Est.', '300000.00', 3, 3, 2),
(4, 'Grenade Manche', 'Grenade à main utilisée par les soldats durant la guerre.', '25.00', 3, NULL, 15),
(5, 'Grenade Mk 2', 'Grenade à main standardisée des forces américaines.', '30.00', 4, NULL, 20),
(6, 'Luger', 'Pistolet semi-automatique utilisé par l\'armée allemande.', '500.00', 5, NULL, 8),
(7, 'Masque à Gaz', 'Masque utilisé pour protéger contre les gaz toxiques.', '80.00', 6, NULL, 12),
(8, 'MG42', 'Mitrailleuse allemande utilisée pendant la Seconde Guerre mondiale.', '2000.00', 7, NULL, 4),
(10, 'Tank Panzer', 'Tank de la Wehrmacht, célèbre pour sa robustesse.', '250000.00', 10, NULL, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Article`
--
ALTER TABLE `Article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `imageId` (`imageId`),
  ADD KEY `categorieId` (`categorieId`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Article`
--
ALTER TABLE `Article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Article`
--
ALTER TABLE `Article`
  ADD CONSTRAINT `Article_ibfk_1` FOREIGN KEY (`imageId`) REFERENCES `Image` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `Article_ibfk_2` FOREIGN KEY (`categorieId`) REFERENCES `Categorie` (`id`) ON DELETE SET NULL;
COMMIT;
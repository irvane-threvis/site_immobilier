
CREATE DATABASE IF NOT EXISTS `immobilier_db`;
USE `immobilier_db`;


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";




DROP TABLE IF EXISTS `client_agent`;
CREATE TABLE IF NOT EXISTS `client_agent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  KEY `agent_id` (`agent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `client_agent`
--

INSERT INTO `client_agent` (`id`, `client_id`, `agent_id`, `created_at`) VALUES
(1, 3, 2, '2026-06-09 15:19:40'),
(2, 3, 2, '2026-06-09 16:00:25'),
(3, 3, 2, '2026-06-15 22:40:19'),
(4, 3, 2, '2026-06-16 00:13:23'),
(5, 3, 2, '2026-06-16 00:42:22'),
(6, 3, 2, '2026-06-16 00:47:26'),
(7, 6, 2, '2026-06-17 10:17:00');



--
-- Structure de la table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
CREATE TABLE IF NOT EXISTS `favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_favorite` (`client_id`,`property_id`),
  KEY `property_id` (`property_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Structure de la table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, 2, 'Le manager vous a affecté un nouveau client : irvane-threvis Tougouma', 1, '2026-06-16 00:47:26'),
(2, 2, 'Le manager vous a affecté un nouveau client : /l.kjmhngb .,mnhbev', 1, '2026-06-17 10:17:00');

-- --------------------------------------------------------

--
-- Structure de la table `properties`
--

DROP TABLE IF EXISTS `properties`;
CREATE TABLE IF NOT EXISTS `properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('terrain','villa','appartement','commerce','immeuble') COLLATE utf8mb4_unicode_ci NOT NULL,
  `usage_type` enum('residence','bureau','commerce','agriculture') COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_type` enum('location','vente') COLLATE utf8mb4_unicode_ci NOT NULL,
  `superficie` decimal(10,2) NOT NULL,
  `prix` decimal(15,2) NOT NULL,
  `ville` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` enum('owner','agency') COLLATE utf8mb4_unicode_ci DEFAULT 'owner',
  `status` enum('pending','published','refused','retired') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `bedrooms` int(11) DEFAULT '0',
  `bathrooms` int(11) DEFAULT '0',
  `garage` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `properties`
--

INSERT INTO `properties` (`id`, `owner_id`, `titre`, `type`, `usage_type`, `option_type`, `superficie`, `prix`, `ville`, `adresse`, `latitude`, `longitude`, `description`, `source`, `status`, `bedrooms`, `bathrooms`, `garage`, `created_at`) VALUES
(1, 4, 'Villa moderne Ouaga 2000', 'villa', 'residence', 'vente', '350.00', '85000000.00', 'Ouagadougou', 'Ouaga 2000, secteur 15', NULL, NULL, 'Villa R+1 climatis??e, jardin, garage 2 voitures, s??curit?? 24h.', 'owner', 'retired', 4, 3, 0, '2026-06-09 15:44:11'),
(2, 4, 'Appartement ?? louer Koulouba', 'appartement', 'residence', 'location', '95.00', '250000.00', 'Ouagadougou', 'Koulouba, av. Kwame Nkrumah', NULL, NULL, 'F3 standing, cuisine ??quip??e, balcon, proche administrations.', 'owner', 'retired', 3, 2, 0, '2026-06-09 15:44:11'),
(3, 4, 'Terrain viabilis?? Karpala', 'terrain', 'residence', 'vente', '500.00', '15000000.00', 'Ouagadougou', 'Karpala, lotissement Les Palmiers', NULL, NULL, 'Terrain cl??tur??, titre foncier, eau et ??lectricit?? disponibles.', 'owner', 'retired', 0, 0, 0, '2026-06-09 15:44:11'),
(4, 4, 'Maison ?? louer Gounghin', 'villa', 'residence', 'location', '120.00', '180000.00', 'Ouagadougou', 'Gounghin, pr??s du march??', NULL, NULL, 'Maison 3 chambres, cour, id??ale pour famille.', 'owner', 'retired', 3, 1, 0, '2026-06-09 15:44:11'),
(5, 4, 'Local commercial Zone 1', 'commerce', 'commerce', 'location', '80.00', '350000.00', 'Ouagadougou', 'Zone 1, face grande art??re', NULL, NULL, 'Boutique vitrine, forte passage, climatisation.', 'owner', 'retired', 0, 1, 0, '2026-06-09 15:44:11'),
(6, 4, 'Immeuble R+3 ?? vendre', 'immeuble', 'residence', 'vente', '600.00', '120000000.00', 'Bobo-Dioulasso', 'Sarfalao, centre-ville', NULL, NULL, 'Immeuble 6 appartements, revenus locatifs garantis.', 'owner', 'retired', 12, 8, 0, '2026-06-09 15:44:11'),
(7, 4, 'Appartement standing Bonheur', 'appartement', 'residence', 'location', '110.00', '320000.00', 'Ouagadougou', 'Bonheur Ville', NULL, NULL, 'F4 luxe, ascenseur, parking, gardien.', 'owner', 'retired', 4, 2, 0, '2026-06-09 15:44:11'),
(8, 4, 'Villa ?? louer Pissy', 'villa', 'residence', 'location', '200.00', '450000.00', 'Ouagadougou', 'Pissy, cit?? AN IV', NULL, NULL, 'Grande villa 5 chambres, piscine, jardin arbor??.', 'owner', 'retired', 5, 3, 0, '2026-06-09 15:44:11'),
(9, 4, 'Terrain agricole Bagr??', 'terrain', 'agriculture', 'vente', '2000.00', '8000000.00', 'Baga', 'P??rim??tre Bagr??', NULL, NULL, 'Terrain fertile, acc??s route, potentiel irrigation.', 'owner', 'retired', 0, 0, 0, '2026-06-09 15:44:11'),
(10, 4, 'Bureau ?? louer centre-ville', 'appartement', 'bureau', 'location', '65.00', '200000.00', 'Ouagadougou', 'Centre-ville, immeuble administratif', NULL, NULL, 'Open space am??nageable, fibre optique, s??curit??.', 'owner', 'retired', 0, 1, 0, '2026-06-09 15:44:11'),
(11, 5, 'fghjklo', 'immeuble', 'residence', 'vente', '3456.00', '234567.00', 'dvgdx,', 'agsdxkut', NULL, NULL, 'sgzjyk/;oug', 'owner', 'retired', 0, 0, 0, '2026-06-09 15:54:36'),
(12, 7, 'fghjklo', 'appartement', 'commerce', 'vente', '345635.00', '2345673.00', 'dvgdx,', 'th865432', NULL, NULL, 'fegral;l5kjhgqvm,iutfxsm,ur', 'owner', 'retired', 0, 0, 0, '2026-06-15 17:22:39'),
(13, 8, 'maison d\'habitation', 'villa', 'residence', 'vente', '500.00', '60000000.00', 'ouagadougou', '01bs4302', NULL, NULL, 'Magnifique villa de 3 chambres située dans un quartier calme et sécurisé de Ouagadougou. Dispose d\'un grand salon, une cuisine équipée, deux salles de bain et un spacieux jardin clôturé. Idéale pour une famille.', 'owner', 'retired', 0, 0, 0, '2026-06-15 19:53:46'),
(16, 9, '', 'villa', 'residence', 'vente', '0.00', '0.00', '', '', NULL, NULL, '', 'owner', 'refused', 0, 0, 0, '2026-06-15 20:20:00'),
(17, 10, '', 'villa', 'residence', 'vente', '0.00', '0.00', '', '', NULL, NULL, '', 'owner', 'refused', 0, 0, 0, '2026-06-15 20:45:28'),
(18, 10, '', 'villa', 'residence', 'vente', '0.00', '0.00', '', '', NULL, NULL, '', 'owner', 'refused', 0, 0, 0, '2026-06-15 20:59:38'),
(22, 11, 'vila_luxieux', 'villa', 'residence', 'vente', '700.00', '75435678.00', 'ouagadougou', 'fg6543', NULL, NULL, 'Maison de standing de 3pièces avec finitions haut de gamme. Carrelage de qualité, faux plafond, portail électrique et parking pour 2 véhicules. Quartier résidentiel paisible, proche des écoles et hôpitaux.', 'owner', 'retired', 0, 0, 0, '2026-06-15 22:35:38'),
(20, 11, 'duplex', 'villa', 'residence', 'vente', '1200.00', '8723456.00', 'koudougou', 'dfg', NULL, NULL, 'dfg', 'owner', 'retired', 0, 0, 0, '2026-06-15 22:18:22'),
(21, 11, 'studio', 'appartement', 'residence', 'location', '200.00', '30000.00', 'Kaya', 'fr456', NULL, NULL, 'Studio meublé entièrement rénové, idéal pour étudiant ou professionnel célibataire. Climatisé, sécurisé, avec accès wifi. Situé à proximité de l\'université et des grandes artères de la ville.', 'owner', 'retired', 0, 0, 0, '2026-06-15 22:30:29'),
(23, 12, 'vila_luxieux', 'villa', 'residence', 'vente', '700.00', '75435678.00', 'koudougou', 'hk45688', NULL, NULL, 'Magnifique villa de 2 chambres située dans un quartier calme et sécurisé de Ouagadougou. Dispose d\'un grand salon, une cuisine équipée, deux salles de bain et un spacieux jardin clôturé. Idéale pour une famille.', 'owner', 'retired', 0, 0, 0, '2026-06-15 22:48:53'),
(24, 13, 'vila_luxieux', 'villa', 'residence', 'vente', '700.00', '75435678.00', 'koudougou', 'fjg234', NULL, NULL, 'Magnifique villa de 4 chambres située dans un quartier calme et sécurisé de Ouagadougou. Dispose d\'un grand salon, une cuisine équipée, deux salles de bain et un spacieux jardin clôturé. Idéale pour une famille.', 'owner', 'retired', 0, 0, 0, '2026-06-15 22:51:39'),
(35, 14, 'domaine', 'villa', 'residence', 'vente', '68754.00', '4356789765.00', 'ouagadougou', 'df346', NULL, NULL, 'Maison de standing de 5 pièces avec finitions haut de gamme. Carrelage de qualité, faux plafond, portail électrique et parking pour 2 véhicules. Quartier résidentiel paisible, proche des écoles et hôpitaux.', 'owner', 'published', 0, 0, 0, '2026-06-17 14:27:50'),
(33, 14, 'duplex', 'villa', 'residence', 'vente', '23456.00', '23456789.00', 'bobo dioulasso', '23fc456', NULL, NULL, 'Magnifique villa de 4 chambres située dans un quartier calme et sécurisé de Ouagadougou. Dispose d\'un grand salon, une cuisine équipée, deux salles de bain et un spacieux jardin clôturé. Idéale pour une famille.', 'owner', 'published', 0, 0, 0, '2026-06-17 14:23:15'),
(34, 14, 'appartement', 'appartement', 'residence', 'vente', '68754.00', '4356789765.00', 'ouagadougou', 'fg45nn6', NULL, NULL, 'Bel appartement moderne de 2 chambres au cœur de la ville. Lumineux et bien ventilé, il comprend un salon, une cuisine ouverte et une salle de bain. Accès facile aux commerces et transports. Disponible immédiatement.', 'owner', 'published', 0, 0, 0, '2026-06-17 14:25:51'),
(30, 18, 'vila_luxieux', 'commerce', 'commerce', 'location', '200000.00', '2345678.00', 'KAYA', 'KARPALA', NULL, NULL, 'ETAGE', 'owner', 'retired', 0, 0, 0, '2026-06-17 10:04:29'),
(31, 19, 'GTHY', 'villa', 'residence', 'location', '23456.00', '23456789.00', 'bobo dioulasso', 'CV1234567', NULL, NULL, 'TYKLGRDK', 'owner', 'retired', 0, 0, 0, '2026-06-17 10:14:29'),
(32, 20, 'GTHY', 'villa', 'residence', 'vente', '23456.00', '23456789.00', 'bobo dioulasso', '35kjf', NULL, NULL, 'ew5si7fkfm', 'owner', 'retired', 0, 0, 0, '2026-06-17 10:19:55'),
(36, 14, 'studio', 'commerce', 'bureau', 'location', '3455.00', '32456789.00', 'Kaya', '23fg34', NULL, NULL, 'Studio meublé entièrement rénové, idéal pour étudiant ou professionnel célibataire. Climatisé, sécurisé, avec accès wifi. Situé à proximité de l\'université et des grandes artères de la ville.', 'owner', 'published', 0, 0, 0, '2026-06-17 14:30:14'),
(37, 14, 'Chalais', 'villa', 'residence', 'vente', '345678.00', '687654323563.00', 'ouagadougou', '2wr4566', NULL, NULL, 'Duplex moderne de 5 pièces réparties sur 2 niveaux. Terrasse au dernier étage avec vue dégagée, garage, cuisine américaine et salon de réception. Construction récente aux normes parasismiques.', 'owner', 'published', 0, 0, 0, '2026-06-17 14:33:02'),
(38, 22, 'location_villa', 'villa', 'commerce', 'location', '2345.00', '67865435.00', 'koudougou', '45dg3456', NULL, NULL, 'Grande villa meublée de 4 chambres avec piscine, groupe électrogène et système de sécurité. Parfaite pour une famille ou une expatrié. Disponible à la location mensuelle dans un quartier résidentiel haut standing.', 'owner', 'published', 0, 0, 0, '2026-06-17 14:36:26'),
(39, 23, 'bureau', 'immeuble', 'bureau', 'location', '34567.00', '23456789.00', 'dori', 'gjfj5694', NULL, NULL, 'Chambre salon propre et bien entretenue, idéale pour petit ménage ou personne seule. Eau et électricité incluses dans le loyer. Environnement calme, voisinage agréable. Disponible dès maintenant.', 'owner', 'published', 0, 0, 0, '2026-06-17 21:21:57'),
(40, 23, 'cite', 'villa', 'residence', 'location', '34556.00', '76567876543.00', 'banfora', '21sh4567', NULL, NULL, 'Maison de 2 chambres dans un quartier animé et bien desservi. Dispose d\'une grande cour, d\'un magasin en façade pouvant servir de commerce, et d\'un branchement eau/électricité régulier.', 'owner', 'published', 0, 0, 0, '2026-06-17 21:26:20'),
(41, 23, 'villa haut standing', 'villa', 'residence', 'location', '87654.00', '89432456.00', 'dedougou', '30wm567', NULL, NULL, 'Appartement de 2 chambres au 2ème étage d\'une résidence sécurisée. Gardien 24h/24, interphone, parking privé. Proche des grandes surfaces et des bureaux du centre-ville. Parfait investissement locatif.', 'owner', 'published', 0, 0, 0, '2026-06-17 21:32:24');




DROP TABLE IF EXISTS `property_images`;
CREATE TABLE IF NOT EXISTS `property_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `property_images`
--

INSERT INTO `property_images` (`id`, `property_id`, `image_path`) VALUES
(1, 11, '1781020476_0_Capture d\'écran 2026-04-20 201715.png'),
(2, 12, '1781544159_0_Capture d\'écran 2026-04-11 065704.png'),
(3, 12, '1781544159_1_Capture d\'écran 2026-04-14 084653.png'),
(4, 12, '1781544159_2_Capture d\'écran 2026-04-18 163110.png'),
(5, 12, '1781544159_3_Capture d\'écran 2026-04-20 200640.png'),
(6, 13, '1781553226_0_aaron-huber-G7sE2S4Lab4-unsplash.jpg'),
(7, 13, '1781553226_2_pixasquare-4ojhpgKpS68-unsplash.jpg'),
(8, 20, '1781561902_0_bilal-mansuri-vTj_dmFGB1Y-unsplash.jpg'),
(9, 21, '1781562629_0_bilal-mansuri-vTj_dmFGB1Y-unsplash.jpg'),
(10, 21, '1781562629_1_istockphoto-2209184711-612x612.webp'),
(11, 22, '1781562938_2_webaliser-_TPTXZd9mOo-unsplash.jpg'),
(12, 24, '1781563899_0_alejandra-cifre-gonzalez-ylyn5r4vxcA-unsplash.jpg'),
(13, 27, '1781567153_0_webaliser-_TPTXZd9mOo-unsplash.jpg'),
(14, 29, '1781569933_0_webaliser-_TPTXZd9mOo-unsplash.jpg'),
(15, 30, '1781690669_0_webaliser-_TPTXZd9mOo-unsplash.jpg'),
(16, 31, '1781691269_0_webaliser-_TPTXZd9mOo-unsplash.jpg'),
(17, 32, '1781691595_0_webaliser-_TPTXZd9mOo-unsplash.jpg'),
(18, 33, '1781706195_0_téléchargement (11).jpg'),
(19, 33, '1781706195_1_téléchargement (8).jpg'),
(20, 33, '1781706195_2_Modern Luxury Living Room with Marble Walls & Elegant Chandelier.jpg'),
(21, 33, '1781706195_3_téléchargement (2).jpg'),
(22, 34, '1781706351_0_téléchargement (11).jpg'),
(23, 34, '1781706351_1_téléchargement (7).jpg'),
(24, 34, '1781706351_2_Омоха Айзек — архитектурный дизайнер_художник из Лагоса, Нигерия.jpg'),
(25, 35, '1781706470_0_🏀🏡 Transformez votre jardin en véritable terrain de basket !.jpg'),
(26, 35, '1781706470_1_23 Modern Backyard Landscaping Ideas That Will Make You The Envy Of The Block.jpg'),
(27, 35, '1781706470_2_Closet design layout.jpg'),
(28, 35, '1781706470_3_modern, luxurious living room design.jpg'),
(29, 35, '1781706470_4_https___alfagost_ru_.jpg'),
(30, 36, '1781706614_0_téléchargement (4).jpg'),
(31, 36, '1781706614_1_téléchargement (8).jpg'),
(32, 37, '1781706782_0_Lush Backyard Retreat with Step-by-Step Elegance.jpg'),
(33, 37, '1781706782_1_Modern Luxury Living Room with Marble Walls & Elegant Chandelier.jpg'),
(34, 37, '1781706782_2_Salon avec 2 canapés _ conseils et astuces d\'aménagement - BCG.jpg'),
(35, 37, '1781706782_3_téléchargement (1).jpg'),
(36, 37, '1781706782_4_Terrain de basket 6x4m _ Terrain-basket_fr.jpg'),
(37, 38, '1781706986_0_Modern Backyard Garden Design Ideas _ Luxury Lawn Inspiration.jpg'),
(38, 38, '1781706986_1_Salon avec 2 canapés _ conseils et astuces d\'aménagement - BCG.jpg'),
(39, 38, '1781706986_2_téléchargement (3).jpg'),
(40, 38, '1781706986_3_téléchargement (9).jpg'),
(41, 39, '1781731317_0_téléchargement (5).jpg'),
(42, 40, '1781731580_0_Modern Backyard Garden Design Ideas _ Luxury Lawn Inspiration.jpg'),
(43, 40, '1781731580_1_téléchargement (3).jpg'),
(44, 41, '1781731944_0_Salon avec 2 canapés _ conseils et astuces d\'aménagement - BCG.jpg'),
(45, 41, '1781731944_1_téléchargement (1).jpg');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('client','bailleur','agent','manager') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `email`, `telephone`, `password`, `avatar`, `role`, `status`, `created_at`) VALUES
(1, 'Admin', 'Manager', 'manager@immofaso.bf', '70000000', '$2y$12$h0AQpPzojfSTbZVghGxf7eJVF49M7w.4fpdFJk.ii14tJl3TKdHMm', NULL, 'manager', 'active', '2026-06-09 13:03:51'),
(2, 'Kaboré', 'Agent', 'agent@immofaso.bf', '70111111', '$2y$12$8kbKNNR22fRWuEZ8pLq4keb/7VP6jXmYx6n3.uoUAfBbkm3JPTeWC', NULL, 'agent', 'active', '2026-06-09 13:19:34'),
(3, 'irvane-threvis', 'Tougouma', 'irvanethrevis8@gmail.com', '54006853', '$2y$10$7KqqmyhKsaGAK9wwc1K8RuWaGmE/lhmD5iD8eRSDuZlOwkgrzT8mO', NULL, 'client', 'active', '2026-06-09 15:16:31'),
(4, 'Ou??draogo', 'Moussa', 'bailleur@immofaso.bf', '70222222', '$2y$12$dWyCpANwzTuL5aTWulCGpeM7L.Qy5eJZKOFsQaIEJiCEbQaG1QkoO', NULL, 'bailleur', 'active', '2026-06-09 15:44:11'),
(5, 'irvane-threvis', 'Tougouma', 'iirvanethrevis8@gmail.com', '54006853', '$2y$10$7PpBAYAAFocpKFaaIgRsd.DIZZcuQNfHEp68vTAaqw5vZx6zdTwhq', NULL, 'bailleur', 'active', '2026-06-09 15:53:03'),
(6, '/l.kjmhngb', '.,mnhbev', 'irvasanethrevis8@gmail.com', '54006853', '$2y$10$UKMaut3T2Of.J18vb.pMJeeN9gg7H2xNQNHJZORlDzIEZedDEU4l6', NULL, 'client', 'active', '2026-06-09 20:42:24'),
(7, '/l.kjmhngb', '.,mnhbev', 'irvasadnethrevis8@gmail.com', '54006853', '$2y$10$qj2XFxwRdNmWLWs7PTAcgux5M2lDjYkziS06PF0uiLLoJxvYIk25a', NULL, 'bailleur', 'active', '2026-06-15 17:20:59'),
(8, 'victor', 'salam', 'irvaninethrevis8@gmail.com', '223456787654', '$2y$10$VsKba/Vl3BgE.KWhPx83zeDtKfgh0DPxvnDiTTJfhXwJJbax7daqa', NULL, 'bailleur', 'active', '2026-06-15 19:42:03'),
(9, 'irvane-threvis', 'Tougouma', 'ikd@gmail.com', '54006853', '$2y$10$w1K8O.XY6ygyGz.kvUxzne0SZ95sVccbeyWgIy.gdUiZ4R9ORtvqO', NULL, 'bailleur', 'active', '2026-06-15 20:14:35'),
(10, 'irvane-threvis', 'Tougouma', 'ddk@gmail.com', '54006853', '$2y$10$JCzgkGOLKcEh2sCK1XEexuG7NiaNrFoc6ipUUNh3JlZgvGtVwJ7wq', NULL, 'bailleur', 'active', '2026-06-15 20:43:35'),
(11, 'irvane-threvis', 'Tougouma', 'sdf@gmail.com', '54006853', '$2y$10$Al.mq5HD0yYZrHk0G5GN5.wU9B8QS4uVC6w4fLidPFVUhqFJg7Olu', NULL, 'bailleur', 'active', '2026-06-15 21:31:49'),
(12, 'irvane-threvis', 'Tougouma', 'asd@gmail.com', '54006853', '$2y$10$s0wJFAD9y6O/TYlWCVjIu./3cnNWvH8OYaFwgY5ysy2RHBUsSspoS', NULL, 'bailleur', 'active', '2026-06-15 22:44:56'),
(13, 'irvane-threvis', 'Tougouma', 'ashdfg@gmail.com', '54006853', '$2y$10$iY6BehBw2rbCWbp/LxBJ..H3kp9fom50/XJ.ve0qBHtzUrvk49XW6', NULL, 'bailleur', 'active', '2026-06-15 22:51:16'),
(14, 'irvane-threvis', 'Tougouma', 'sss@gmail.com', '54006853', '$2y$10$Zu8tsZNzM97U..KHLI3As.9StFOGXLCSO2GRJPIcDwNkC0VNgumWS', NULL, 'bailleur', 'active', '2026-06-15 23:32:49'),
(15, 'irvane-threvis', 'Tougouma', 'bvc@gmail.com', '54006853', '$2y$10$3CZdUoybpQnaB7XD1y2Ijuy2NXH7Wy1Hndx/gOIYV/A0MtQnKkAxm', NULL, 'client', 'active', '2026-06-15 23:58:54'),
(16, 'irvane-threvis', 'Tougouma', 'managesdr@immofaso.bf', '54006853', '$2y$10$mZ.nW76gz6wOG7qxfffjOOqquOraM3RHGAzRxcfqFh2osJBCibH1K', NULL, 'client', 'active', '2026-06-16 00:59:57'),
(17, 'dtfgutf', 'tfgjvugc', 'vgccf@gmail.com', '12345678', '$2y$10$Z4RyBDO3wTjDd3/aO7E.gOSaRXJU31Lm68J9aTkOkWNZadF9f1RGa', NULL, 'bailleur', 'active', '2026-06-17 09:58:49'),
(19, 'dcc', 'jhtb', 'gugSDh@gmail.com', '12345699', '$2y$10$cBB2rSRXBwjr4.Xo4UFhgelbf7jy4TsX6R51joPVThcojql5/1.JK', NULL, 'bailleur', 'active', '2026-06-17 10:13:15'),
(20, 'yjuyht', 'ewrtkyugjht', 'rkj@gmail.com', '32476890', '$2y$10$fWAModNxOaAN90A6Au5APeinQd8vNYHYIvDbcWHfzcYq4m8E/xp4y', NULL, 'bailleur', 'active', '2026-06-17 10:19:00'),
(21, 'yjuyht', 'ewrtkyugjht', 'asdfg@gmail.com', '32476890', '$2y$10$Y1OYv3IyHtxjDQJ1CBmRMuxogWvMAtvwvkF36j2xUq7HcqBO/0fqu', NULL, 'client', 'active', '2026-06-17 10:22:07'),
(22, 'yjuyht', 'ewrtkyugjht', 'sd@gmail.com', '32476890', '$2y$10$1UGhHXTDPmhak02sOd2egeV4qZtDSXpOFsfeK9v/o3gOPJqI1iZDC', NULL, 'bailleur', 'active', '2026-06-17 14:33:44'),
(23, 'ouedraogo', 'leon', 'qw@gmail.com', '567876', '$2y$10$QDxMKKkgk94FVho.CfYa3ONlrXEwpWSzdf8PN1d0nN2Ky8DzwDKIW', NULL, 'bailleur', 'active', '2026-06-17 21:19:20');

-- --------------------------------------------------------

--
-- Structure de la table `visit_requests`
--

DROP TABLE IF EXISTS `visit_requests`;
CREATE TABLE IF NOT EXISTS `visit_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `visit_date` date DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  KEY `client_id` (`client_id`),
  KEY `agent_id` (`agent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `visit_requests`
--

INSERT INTO `visit_requests` (`id`, `property_id`, `client_id`, `agent_id`, `visit_date`, `status`, `created_at`) VALUES
(1, 11, 6, NULL, NULL, 'approved', '2026-06-09 20:43:05'),
(2, 27, 15, NULL, NULL, 'approved', '2026-06-16 00:03:28'),
(3, 24, 15, NULL, NULL, 'approved', '2026-06-16 00:03:59'),
(4, 29, 16, NULL, NULL, 'approved', '2026-06-16 01:00:35');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=utf8mb4 */;
/*!40101 SET CHARACTER_SET_RESULTS=utf8mb4 */;
/*!40101 SET COLLATION_CONNECTION=utf8mb4_unicode_ci */;    
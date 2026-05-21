-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : jeu. 21 mai 2026 à 21:45
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `vite_gourmand`
--

-- --------------------------------------------------------

--
-- Structure de la table `allergene`
--

CREATE TABLE `allergene` (
  `allergene_id` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `allergene`
--

INSERT INTO `allergene` (`allergene_id`, `libelle`) VALUES
(1, 'Gluten'),
(2, 'Lait'),
(3, 'Oeufs'),
(4, 'Fruits à coque');

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `avis_id` int(11) NOT NULL,
  `note` int(11) NOT NULL,
  `description` text NOT NULL,
  `statut` varchar(50) NOT NULL DEFAULT 'en attente',
  `utilisateur_id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`avis_id`, `note`, `description`, `statut`, `utilisateur_id`, `commande_id`) VALUES
(1, 5, 'Magnifique service !!', 'valide', 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `commande_id` int(11) NOT NULL,
  `numero_commande` varchar(50) NOT NULL,
  `date_commande` date NOT NULL,
  `date_prestation` date NOT NULL,
  `heure_livraison` time NOT NULL,
  `lieu_prestation` varchar(255) NOT NULL,
  `adresse_prestation` varchar(255) NOT NULL,
  `prix_menu` decimal(10,2) NOT NULL,
  `prix_livraison` decimal(10,2) NOT NULL DEFAULT 0.00,
  `prix_total` decimal(10,2) NOT NULL,
  `nombre_personne` int(11) NOT NULL,
  `statut` varchar(50) NOT NULL DEFAULT 'en attente',
  `pret_materiel` tinyint(1) NOT NULL DEFAULT 0,
  `restitution_materiel` tinyint(1) NOT NULL DEFAULT 0,
  `motif_annulation` text DEFAULT NULL,
  `mode_contact_annulation` varchar(50) DEFAULT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`commande_id`, `numero_commande`, `date_commande`, `date_prestation`, `heure_livraison`, `lieu_prestation`, `adresse_prestation`, `prix_menu`, `prix_livraison`, `prix_total`, `nombre_personne`, `statut`, `pret_materiel`, `restitution_materiel`, `motif_annulation`, `mode_contact_annulation`, `utilisateur_id`, `menu_id`) VALUES
(2, 'CMD-1779329895', '2026-05-21', '2000-03-05', '04:27:00', 'Lyon', '69 Rue Paul Cazeneuve', 324.00, 5.00, 329.00, 12, 'en attente', 0, 0, NULL, NULL, 1, 3),
(3, 'CMD-1779370452', '2026-05-21', '3773-03-07', '07:37:00', 'Lyon', '69 Rue Paul Cazeneuve', 250.00, 5.00, 255.00, 10, 'en attente', 0, 0, NULL, NULL, 7, 1),
(4, 'CMD-1779372190', '2026-05-21', '0242-04-25', '05:43:00', 'Lyon', '69 Rue Paul Cazeneuve', 337.50, 5.00, 342.50, 15, 'en attente', 0, 0, NULL, NULL, 7, 1),
(5, 'CMD-1779372246', '2026-05-21', '4344-03-04', '04:44:00', 'Lyon', '69 Rue Paul Cazeneuve', 324.00, 5.00, 329.00, 12, 'en attente', 0, 0, NULL, NULL, 7, 3),
(6, 'CMD-1779372551', '2026-05-21', '2026-05-08', '20:13:00', 'Lyon', '69 Rue Paul Cazeneuve', 160.00, 5.00, 165.00, 8, 'en attente', 0, 0, NULL, NULL, 7, 2),
(7, 'CMD-1779373151', '2026-05-21', '2026-04-04', '16:22:00', 'Lyon', '69 Rue Paul Cazeneuve', 160.00, 5.00, 165.00, 8, 'en attente', 0, 0, NULL, NULL, 7, 2),
(8, 'CMD-1779374014', '2026-05-21', '2026-05-03', '16:35:00', 'Lyon', '69 Rue Paul Cazeneuve', 405.00, 5.00, 410.00, 18, 'en attente', 0, 0, NULL, NULL, 7, 1),
(9, 'CMD-1779374294', '2026-05-21', '2026-05-13', '18:38:00', 'Lyon', '69 Rue Paul Cazeneuve', 350.00, 5.00, 355.00, 14, 'en attente', 0, 0, NULL, NULL, 1, 1),
(10, 'CMD-1779374387', '2026-05-21', '2026-05-10', '19:39:00', 'Lyon', '69 Rue Paul Cazeneuve', 160.00, 5.00, 165.00, 8, 'en attente', 0, 0, NULL, NULL, 1, 2),
(11, 'CMD-1779374536', '2026-05-21', '2026-05-10', '19:45:00', 'Lyon', '69 Rue Paul Cazeneuve', 324.00, 5.00, 329.00, 12, 'en attente', 0, 0, NULL, NULL, 1, 3),
(12, 'CMD-1779374578', '2026-05-21', '2026-05-10', '19:45:00', 'Lyon', '69 Rue Paul Cazeneuve', 324.00, 5.00, 329.00, 12, 'en attente', 0, 0, NULL, NULL, 1, 3),
(13, 'CMD-1779374586', '2026-05-21', '2026-05-10', '19:45:00', 'Lyon', '69 Rue Paul Cazeneuve', 324.00, 5.00, 329.00, 12, 'en attente', 0, 0, NULL, NULL, 1, 3),
(14, 'CMD-1779374595', '2026-05-21', '2026-05-10', '19:45:00', 'Lyon', '69 Rue Paul Cazeneuve', 324.00, 5.00, 329.00, 12, 'en attente', 0, 0, NULL, NULL, 1, 3),
(15, 'CMD-1779375211', '2026-05-21', '2026-05-10', '19:45:00', 'Lyon', '69 Rue Paul Cazeneuve', 324.00, 5.00, 329.00, 12, 'en attente', 0, 0, NULL, NULL, 1, 3),
(16, 'CMD-1779375538', '2026-05-21', '2026-05-10', '19:45:00', 'Lyon', '69 Rue Paul Cazeneuve', 324.00, 5.00, 329.00, 12, 'en attente', 0, 0, NULL, NULL, 1, 3),
(17, 'CMD-1779375678', '2026-05-21', '2026-05-10', '19:45:00', 'Lyon', '69 Rue Paul Cazeneuve', 324.00, 5.00, 329.00, 12, 'en attente', 0, 0, NULL, NULL, 1, 3),
(18, 'CMD-1779375802', '2026-05-21', '2026-05-09', '20:06:00', 'Lyon', '69 Rue Paul Cazeneuve', 160.00, 5.00, 165.00, 8, 'en attente', 0, 0, NULL, NULL, 7, 2),
(19, 'CMD-1779375914', '2026-05-21', '2026-05-02', '17:09:00', 'Lyon', '69 Rue Paul Cazeneuve', 22500.00, 5.00, 22505.00, 1000, 'en attente', 0, 0, NULL, NULL, 7, 1),
(20, 'CMD-1779383188', '2026-05-21', '2026-05-03', '22:06:00', 'Lyon', '69 Rue Paul Cazeneuve', 337.50, 5.00, 342.50, 15, 'en attente', 0, 0, NULL, NULL, 9, 1),
(22, 'CMD-1779390067', '2026-05-21', '2026-05-08', '23:01:00', 'Lyon', '69 Rue Paul Cazeneuve', 360.00, 262.24, 622.24, 16, 'refusée', 0, 0, NULL, NULL, 1, 1),
(23, 'CMD-1779390070', '2026-05-21', '2026-05-08', '23:01:00', 'Lyon', '69 Rue Paul Cazeneuve', 360.00, 262.24, 622.24, 16, 'refusée', 0, 0, NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `horaire`
--

CREATE TABLE `horaire` (
  `horaire_id` int(11) NOT NULL,
  `jour` varchar(50) NOT NULL,
  `heure_ouverture` varchar(20) NOT NULL,
  `heure_fermeture` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `horaire`
--

INSERT INTO `horaire` (`horaire_id`, `jour`, `heure_ouverture`, `heure_fermeture`) VALUES
(1, 'Lundi', '09:00', '18:00'),
(2, 'Mardi', '09:00', '18:00'),
(3, 'Mercredi', '09:00', '18:00'),
(4, 'Jeudi', '09:00', '18:00'),
(5, 'Vendredi', '09:00', '18:00'),
(6, 'Samedi', '10:00', '17:00'),
(8, 'Dimanche', '09:00', '18:00');

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `nombre_personne_minimum` int(11) NOT NULL,
  `prix_par_personne` decimal(10,2) NOT NULL,
  `regime_id` int(11) NOT NULL,
  `theme_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `conditions_menu` text NOT NULL,
  `stock_disponible` int(11) NOT NULL DEFAULT 0,
  `image_principale` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `menu`
--

INSERT INTO `menu` (`menu_id`, `titre`, `nombre_personne_minimum`, `prix_par_personne`, `regime_id`, `theme_id`, `description`, `conditions_menu`, `stock_disponible`, `image_principale`) VALUES
(1, 'Menu Noël Festif', 10, 25.00, 3, 1, 'Menu festif complet pour les repas de fin d année.', 'Commande a passer au moins 7 jours avant la prestation.', 5, 'menu-noel.jpg'),
(2, 'Menu Classique', 8, 20.00, 3, 3, 'Menu traditionnel adapte aux repas familiaux et anniversaires.', 'Commande a passer au moins 3 jours avant la prestation.', 8, 'menu-classique.jpg'),
(3, 'Menu Vegan Evenement', 12, 27.00, 2, 4, 'Menu vegetal pour evenements modernes et responsables.', 'Commande a passer au moins 5 jours avant la prestation.', 4, 'menu-vegan.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `menu_plat`
--

CREATE TABLE `menu_plat` (
  `menu_id` int(11) NOT NULL,
  `plat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `plat`
--

CREATE TABLE `plat` (
  `plat_id` int(11) NOT NULL,
  `titre_plat` varchar(100) NOT NULL,
  `type_plat` enum('entree','plat','dessert') NOT NULL,
  `prix` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `plat_allergene`
--

CREATE TABLE `plat_allergene` (
  `plat_id` int(11) NOT NULL,
  `allergene_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `regime`
--

CREATE TABLE `regime` (
  `regime_id` int(11) NOT NULL,
  `libelle` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `regime`
--

INSERT INTO `regime` (`regime_id`, `libelle`) VALUES
(1, 'Vegetarien'),
(2, 'Vegan'),
(3, 'Classique');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `libelle` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`role_id`, `libelle`) VALUES
(1, 'administrateur'),
(2, 'employe'),
(3, 'utilisateur');

-- --------------------------------------------------------

--
-- Structure de la table `suivi_commande`
--

CREATE TABLE `suivi_commande` (
  `suivi_id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `statut` varchar(50) NOT NULL,
  `date_statut` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `suivi_commande`
--

INSERT INTO `suivi_commande` (`suivi_id`, `commande_id`, `statut`, `date_statut`) VALUES
(2, 2, 'en attente', '2026-05-21 04:18:15'),
(3, 2, 'terminée', '2026-05-21 04:51:23'),
(4, 2, 'en attente', '2026-05-21 04:51:47'),
(5, 2, 'en attente', '2026-05-21 04:51:50'),
(6, 2, 'terminée', '2026-05-21 04:51:54'),
(7, 3, 'en attente', '2026-05-21 15:34:12'),
(8, 4, 'en attente', '2026-05-21 16:03:10'),
(9, 5, 'en attente', '2026-05-21 16:04:06'),
(10, 6, 'en attente', '2026-05-21 16:09:11'),
(11, 7, 'en attente', '2026-05-21 16:19:11'),
(12, 8, 'en attente', '2026-05-21 16:33:34'),
(13, 9, 'en attente', '2026-05-21 16:38:14'),
(14, 10, 'en attente', '2026-05-21 16:39:47'),
(15, 11, 'en attente', '2026-05-21 16:42:16'),
(16, 12, 'en attente', '2026-05-21 16:42:58'),
(17, 13, 'en attente', '2026-05-21 16:43:06'),
(18, 14, 'en attente', '2026-05-21 16:43:15'),
(19, 15, 'en attente', '2026-05-21 16:53:31'),
(20, 16, 'en attente', '2026-05-21 16:58:58'),
(21, 17, 'en attente', '2026-05-21 17:01:18'),
(22, 18, 'en attente', '2026-05-21 17:03:22'),
(23, 19, 'en attente', '2026-05-21 17:05:14'),
(24, 20, 'en attente', '2026-05-21 19:06:28'),
(26, 2, 'en attente', '2026-05-21 19:11:28'),
(27, 22, 'en attente', '2026-05-21 21:01:07'),
(28, 23, 'en attente', '2026-05-21 21:01:10'),
(29, 22, 'en attente', '2026-05-21 21:16:12'),
(30, 23, 'refusée', '2026-05-21 21:17:39'),
(31, 22, 'refusée', '2026-05-21 21:17:44');

-- --------------------------------------------------------

--
-- Structure de la table `theme`
--

CREATE TABLE `theme` (
  `theme_id` int(11) NOT NULL,
  `libelle` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `theme`
--

INSERT INTO `theme` (`theme_id`, `libelle`) VALUES
(1, 'Noel'),
(2, 'Paques'),
(3, 'Classique'),
(4, 'Evenement');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `utilisateur_id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `ville` varchar(100) NOT NULL,
  `code_postal` varchar(5) NOT NULL,
  `pays` varchar(50) NOT NULL,
  `adresse_postale` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT 1,
  `role_id` int(11) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`utilisateur_id`, `nom`, `prenom`, `telephone`, `ville`, `code_postal`, `pays`, `adresse_postale`, `email`, `password`, `actif`, `role_id`, `reset_token`, `reset_token_expires_at`) VALUES
(1, 'Arfaoui', 'Aymen', '0626462802', 'Lyon', '69008', 'France', '69 rue Paul Cazeneuve', 'aymenarfaoui87@gmail.com', '$2y$10$TrCyisOytN.8HldGnHlJf.HQ5JYYJbKrsz3YACcxPf4p795Vv8SYe', 1, 2, NULL, NULL),
(2, 'Dupont', 'Alice', '0600000001', 'Bordeaux', '33000', 'France', '10 rue des Fleurs, Bordeaux', 'alice@vitegourmand.fr', 'motdepassehash1', 1, 3, NULL, NULL),
(3, 'Martin', 'Lucas', '0600000002', 'Bordeaux', '33000', 'France', '20 avenue de la Gare, Bordeaux', 'lucas@vitegourmand.fr', 'motdepassehash2', 1, 3, NULL, NULL),
(4, 'Bernard', 'Emma', '0600000003', 'Mérignac', '33000', 'France', '15 rue des Pins, Mérignac', 'emma@vitegourmand.fr', 'motdepassehash3', 1, 2, NULL, NULL),
(5, 'Petit', 'Hugo', '0600000004', 'Pessac', '', 'France', '8 boulevard Jean Jaurès, Pessac', 'hugo@vitegourmand.fr', 'motdepassehash4', 0, 2, NULL, NULL),
(6, 'Durand', 'Julie', '0600000005', 'Bordeaux', '33000', 'France', '5 rue Victor Hugo, Bordeaux', 'julie@vitegourmand.fr', 'motdepassehash5', 1, 1, NULL, NULL),
(7, 'Guermassi', 'Heykel', '0646892646', 'Lyon', '69200', 'France', '44 avenue Jules Guesde', 'heykel.guermassi@yahoo.fr', '$2y$10$RWWpgNCSZs9R6fMVoprR3.DFFvBgX8kdA3VqIRaAQNGgBdAkxZVKi', 1, 1, NULL, NULL),
(8, 'Harbal', 'Ayman', '0646284652', 'Lyon', '69001', 'France', '5 place de croix luzet', 'ayman.harbal@gmail.com', '$2y$10$J8xSa9hQIoeZl7nBVZHkXOleyMDqq.KHOwymKq0GCyQPSS4SXJqBS', 1, 2, NULL, NULL),
(9, 'aaaaaa', 'zefazfa', 'afazaefazfe', 'faezfeezffea', '', 'azefzaffez', 'ffezfaezeafez', 'aymnarfaoui87@gmail.com', '$2y$10$0DVMydh3lHi/nv1/K9I7o./6ASzGr5wP49A.Mijz9D6W5DNmXVcFa', 1, 2, NULL, NULL),
(10, 'Atmani', 'Léa', '0626462802', 'Poitiers', '83000', 'France', '14 rue Faidherbe', 'lea.atmani@icloud.fr', '$2y$10$ZkR4mbcGimKGw9YZbIfHNeA2g6h..FBYhjRK7pz/I2COjhpaTFIX.', 1, 3, NULL, NULL),
(11, 'Arfaoui', 'Mehdi', '0646668652', 'Paris', '75000', 'France', '15 Rue Faubourg', 'aymnnn69z@gmail.com', '$2y$10$hnr7OpOHKuF9LbDFMgJkbuWRq0CiDBd9MKnQVWai98OgdWxklurKC', 1, 3, NULL, NULL),
(12, 'Arfaoui', 'Mehdi', '0675279427', 'Paris', '75000', 'France', '15 Rue Faubourg', 'zazadedeni@gmail.com', '$2y$10$bGxxPpq438E0aFc0KcG5seUCmRXubXheYumFvVgGl.hIEXIFAv62C', 1, 3, 'f3d49380e72172d9e1c835cb7e8837ad67e85b3dfd5df78ed63dcdbb1c647017', '2026-05-21 21:08:01');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `allergene`
--
ALTER TABLE `allergene`
  ADD PRIMARY KEY (`allergene_id`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`avis_id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `commande_id` (`commande_id`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`commande_id`),
  ADD UNIQUE KEY `numero_commande` (`numero_commande`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Index pour la table `horaire`
--
ALTER TABLE `horaire`
  ADD PRIMARY KEY (`horaire_id`);

--
-- Index pour la table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `regime_id` (`regime_id`),
  ADD KEY `theme_id` (`theme_id`);

--
-- Index pour la table `menu_plat`
--
ALTER TABLE `menu_plat`
  ADD PRIMARY KEY (`menu_id`,`plat_id`),
  ADD KEY `plat_id` (`plat_id`);

--
-- Index pour la table `plat`
--
ALTER TABLE `plat`
  ADD PRIMARY KEY (`plat_id`);

--
-- Index pour la table `plat_allergene`
--
ALTER TABLE `plat_allergene`
  ADD PRIMARY KEY (`plat_id`,`allergene_id`),
  ADD KEY `allergene_id` (`allergene_id`);

--
-- Index pour la table `regime`
--
ALTER TABLE `regime`
  ADD PRIMARY KEY (`regime_id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Index pour la table `suivi_commande`
--
ALTER TABLE `suivi_commande`
  ADD PRIMARY KEY (`suivi_id`),
  ADD KEY `commande_id` (`commande_id`);

--
-- Index pour la table `theme`
--
ALTER TABLE `theme`
  ADD PRIMARY KEY (`theme_id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`utilisateur_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `allergene`
--
ALTER TABLE `allergene`
  MODIFY `allergene_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `avis_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `commande_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `horaire`
--
ALTER TABLE `horaire`
  MODIFY `horaire_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `plat`
--
ALTER TABLE `plat`
  MODIFY `plat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `regime`
--
ALTER TABLE `regime`
  MODIFY `regime_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `suivi_commande`
--
ALTER TABLE `suivi_commande`
  MODIFY `suivi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `theme`
--
ALTER TABLE `theme`
  MODIFY `theme_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `utilisateur_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`utilisateur_id`),
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`commande_id`);

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`utilisateur_id`),
  ADD CONSTRAINT `commande_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`);

--
-- Contraintes pour la table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`regime_id`) REFERENCES `regime` (`regime_id`),
  ADD CONSTRAINT `menu_ibfk_2` FOREIGN KEY (`theme_id`) REFERENCES `theme` (`theme_id`);

--
-- Contraintes pour la table `menu_plat`
--
ALTER TABLE `menu_plat`
  ADD CONSTRAINT `menu_plat_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_plat_ibfk_2` FOREIGN KEY (`plat_id`) REFERENCES `plat` (`plat_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `plat_allergene`
--
ALTER TABLE `plat_allergene`
  ADD CONSTRAINT `plat_allergene_ibfk_1` FOREIGN KEY (`plat_id`) REFERENCES `plat` (`plat_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `plat_allergene_ibfk_2` FOREIGN KEY (`allergene_id`) REFERENCES `allergene` (`allergene_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `suivi_commande`
--
ALTER TABLE `suivi_commande`
  ADD CONSTRAINT `suivi_commande_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`commande_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

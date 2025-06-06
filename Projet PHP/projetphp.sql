-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 28 avr. 2025 à 17:32
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projetphp`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  `prix` decimal(10,2) DEFAULT NULL,
  `ancien_prix` float DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `stock` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `nom`, `prix`, `ancien_prix`, `img`, `stock`) VALUES
(1, 'Grillz', 200.00, 300, 'produit1.jpg', 15),
(2, 'Parure collier et boucles', 1229.00, NULL, 'produit2.jpg', 14),
(3, 'Algerian Kabyle Jewels', 567.00, NULL, 'produit3.jpg', 23),
(4, 'Chic ensemble', 130.00, NULL, 'produit4.jpg', 12),
(5, 'Bagues', 29.00, 39, 'produit5.jpg', 45),
(6, 'Collier de perles', 209.00, 249, 'produit6.jpg', 93),
(7, 'Montre traditionnelle', 99.00, NULL, 'produit7.jpg', 53),
(8, 'Ensemble 2.0', 289.00, NULL, 'produit8.jpg', 35),
(9, 'DABADI Grillz', 169.00, NULL, 'produit9.jpg', 98),
(10, 'Ensemble collier+bagues street', 59.00, 99, 'produit10.jpg', 45),
(11, 'Bagues tradi', 49.00, NULL, 'produit11.jpg', 53),
(12, 'Ensemble classe', 349.00, NULL, 'produit12.jpg', 43),
(13, 'Kabyle moderne', 849.00, NULL, 'produit13.jpg', 46);

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
CREATE TABLE IF NOT EXISTS `commandes` (
  `id_commande` int NOT NULL AUTO_INCREMENT,
  `id_client` int NOT NULL,
  `date_commande` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('en attente','payée','expédiée','livrée','annulée') NOT NULL DEFAULT 'en attente',
  `montant_total` decimal(10,2) NOT NULL,
  `adresse_livraison` text NOT NULL,
  PRIMARY KEY (`id_commande`),
  KEY `id_client` (`id_client`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id_commande`, `id_client`, `date_commande`, `statut`, `montant_total`, `adresse_livraison`) VALUES
(1, 1, '2025-04-26 22:10:17', 'payée', 100.00, 'Adresse de test'),
(2, 1, '2025-04-26 22:14:47', 'payée', 100.00, 'Adresse de test'),
(3, 1, '2025-04-26 22:15:56', 'payée', 100.00, 'Adresse de test'),
(4, 1, '2025-04-26 22:16:05', 'payée', 100.00, 'Adresse de test'),
(5, 1, '2025-04-26 22:16:51', 'payée', 100.00, 'Adresse de test'),
(6, 1, '2025-04-26 22:21:52', 'payée', 100.00, 'Adresse de test'),
(7, 1, '2025-04-26 22:23:52', 'payée', 100.00, 'Adresse de test'),
(8, 1, '2025-04-27 00:17:33', 'payée', 100.00, 'Adresse de test'),
(9, 1, '2025-04-27 00:18:17', 'payée', 100.00, 'Adresse de test'),
(10, 1, '2025-04-27 00:18:57', 'payée', 100.00, 'Adresse de test'),
(11, 1, '2025-04-27 00:19:27', 'payée', 100.00, 'Adresse de test'),
(12, 1, '2025-04-27 00:55:13', 'payée', 100.00, 'Adresse de test'),
(13, 1, '2025-04-26 10:00:00', 'payée', 150.00, '123 Rue Principale, Ville'),
(14, 1, '2025-04-25 14:30:00', 'expédiée', 75.50, '456 Avenue Secondaire, Ville'),
(15, 1, '2025-04-24 09:15:00', 'livrée', 200.00, '789 Boulevard Tertiaire, Ville'),
(16, 1, '2025-04-27 02:23:06', 'payée', 100.00, 'Adresse de test'),
(17, 1, '2025-04-27 02:23:17', 'payée', 100.00, 'Adresse de test'),
(18, 1, '2025-04-27 02:31:29', 'payée', 100.00, 'Adresse de test'),
(19, 1, '2025-04-27 11:26:21', 'payée', 100.00, 'Adresse de test'),
(20, 1, '2025-04-28 16:07:47', 'payée', 100.00, 'Adresse de test'),
(21, 1, '2025-04-28 16:23:48', 'payée', 1.00, 'Adresse de test'),
(22, 1, '2025-04-28 16:25:03', 'payée', 1.00, 'Adresse de test'),
(23, 1, '2025-04-28 16:27:33', 'payée', 1.00, 'Adresse de test');

-- --------------------------------------------------------

--
-- Structure de la table `commandes_articles`
--

DROP TABLE IF EXISTS `commandes_articles`;
CREATE TABLE IF NOT EXISTS `commandes_articles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_commande` int NOT NULL,
  `id_produit` int NOT NULL,
  `quantite` int NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_commande` (`id_commande`),
  KEY `id_produit` (`id_produit`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `commandes_articles`
--

INSERT INTO `commandes_articles` (`id`, `id_commande`, `id_produit`, `quantite`, `prix_unitaire`) VALUES
(1, 1, 1, 1, 50.00),
(2, 1, 2, 2, 25.00),
(3, 2, 3, 1, 75.50),
(4, 3, 1, 1, 100.00),
(5, 3, 2, 1, 100.00);

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

DROP TABLE IF EXISTS `commentaires`;
CREATE TABLE IF NOT EXISTS `commentaires` (
  `id` int NOT NULL AUTO_INCREMENT,
  `article_id` int NOT NULL,
  `auteur` varchar(100) NOT NULL,
  `commentaire` text NOT NULL,
  `date_commentaire` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

DROP TABLE IF EXISTS `panier`;
CREATE TABLE IF NOT EXISTS `panier` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int DEFAULT NULL,
  `article_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `article_id` (`article_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `motDePasse` varchar(255) DEFAULT NULL,
  `points` int DEFAULT '0',
  `date_inscription` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `motDePasse`, `points`, `date_inscription`) VALUES
(1, 'koudedia', 'koudediiatiimbo@gmail.com', '$2y$10$8PAmSekupPQqGAfRxkM9ouUrtf9l4PIiRN5NNyV1UQprSgambIlnS', 0, '2025-04-28 18:31:56'),
(2, 'mamikou', 'koudediatimbo@icloud.com', '$2y$10$cfNpv6ThIcvx6HclWGBbuO89JOUdqdDLwWRmqu26PryccFme7MLdC', 0, '2025-04-28 18:31:56'),
(3, 'John Doe', 'john.doe@example.com', 'motdepasse_hashé', 0, '2025-04-28 18:31:56'),
(4, 'TIMBO', 'koudedia@gmail.fr', '$2y$10$nVfnQksHyrNKINct8TSaOeF8/dYYdX3X/9vRlpPrUNcdht8OAxg3O', 0, '2025-04-28 18:31:56'),
(5, 'Diany', 'dianykani@gmail.com', '$2y$10$GDm3wRr9UCYoUOFqn0UQu.telk6wLEqMpOJpVzMW58NjjLeLOGare', 0, '2025-04-28 18:31:56'),
(6, 'TIMBO', 'koudedi@gmail.fr', '$2y$10$LRtleo5dhWHuyglWSjBBCexjw2O8y1Q92CvLa9q43qMhZ.zwnMhd2', 0, '2025-04-28 18:31:56'),
(7, 'dianykou', 'dianykou@gmail.com', '$2y$10$5119NWNiZQfuXaibTVPUOOQV.1K157hj/gY5ofp0yLZfvIzq4A0yi', 0, '2025-04-28 19:04:45');

-- --------------------------------------------------------

--
-- Structure de la table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `id_article` int NOT NULL,
  `date_ajout` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_article` (`id_article`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

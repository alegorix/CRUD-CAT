-- phpMyAdmin SQL Dump
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `crud`
--
CREATE DATABASE IF NOT EXISTS `crud` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `crud`;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `liste`;
DROP TABLE IF EXISTS `categories`;

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_categorie` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`id`, `nom_categorie`) VALUES
(1, 'Vêtements'),
(2, 'High-Tech'),
(3, 'Accessoires');

-- --------------------------------------------------------

--
-- Structure de la table `liste`
--

CREATE TABLE IF NOT EXISTS `liste` (
  `id` int NOT NULL AUTO_INCREMENT,
  `produit` varchar(200) NOT NULL,
  `prix` varchar(20) NOT NULL,
  `nombre` int NOT NULL,
  `image_produit` varchar(300) NOT NULL,
  `actif` int NOT NULL,
  `categorie_id` int NULL,
  PRIMARY KEY (`id`),
  KEY `fk_liste_categorie` (`categorie_id`),
  CONSTRAINT `fk_liste_categorie` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `liste`
--

INSERT INTO `liste` (`id`, `produit`, `prix`, `nombre`, `image_produit`, `actif`, `categorie_id`) VALUES
(3, 'produit LOL', '244', 128, '200x200A.png', 0, NULL),
(4, 'TShirt Noir', '12', 34, '', 1, 1),
(9, 'TShirt Noir', '23', 4, '200x200A.png', 1, 1),
(10, 'Montre Connectée', '659', 12, '200x200XXXXXX.png', 0, 2);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

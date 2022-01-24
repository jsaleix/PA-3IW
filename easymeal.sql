-- phpMyAdmin SQL Dump
-- version 5.0.4deb2~bpo10+1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 27 juil. 2021 à 21:27
-- Version du serveur :  10.3.29-MariaDB-0+deb10u1
-- Version de PHP : 7.3.29-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `easymeal`
--

-- --------------------------------------------------------

--
-- Structure de la table `ag_Action`
--

CREATE TABLE `ag_Action` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `controller` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `filters` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `ag_Action`
--

INSERT INTO `ag_Action` (`id`, `name`, `controller`, `method`, `filters`) VALUES
(1, 'Display the posts', 'PostController', 'renderList', ''),
(4, 'Display the site informations', 'SiteController', 'render', NULL),
(5, 'Display the dishes', 'DishCategoryController', 'renderList', NULL),
(6, 'Display the menus', 'MenuController', 'renderMenus', NULL),
(7, 'Display specific menu', 'MenuController', 'renderMenuAction', 'menu'),
(8, 'Display a specific post', 'PostController', 'renderPostAction', 'post'),
(9, 'Take a reservation', 'BookingController', 'addBookingAction', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `ag_MailToken`
--

CREATE TABLE `ag_MailToken` (
  `id` int(11) NOT NULL,
  `token` varchar(256) NOT NULL,
  `userId` int(11) NOT NULL,
  `expiresDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `ag_Role`
--

CREATE TABLE `ag_Role` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `icon` varchar(255) NOT NULL,
  `isAdmin` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `ag_Role`
--

INSERT INTO `ag_Role` (`id`, `name`, `description`, `icon`, `isAdmin`) VALUES
(1, 'user', '', '', 0),
(2, 'admin', NULL, '/uploads/main/icons/roles/20210727_114738177658.png', 1);

-- --------------------------------------------------------

--
-- Structure de la table `ag_Site`
--

CREATE TABLE `ag_Site` (
  `id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT '/Assets/images/resto.jpeg',
  `creator` int(11) NOT NULL,
  `subDomain` varchar(45) NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `prefix` varchar(10) NOT NULL,
  `type` varchar(255) NOT NULL,
  `theme` varchar(80) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT 'Default',
  `styles` text CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `emailPro` varchar(120) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phoneNumber` varchar(15) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ag_User`
--

CREATE TABLE `ag_User` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(55) NOT NULL,
  `email` varchar(320) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `joinDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `isActive` tinyint(4) NOT NULL DEFAULT 0,
  `role` int(11) NOT NULL DEFAULT 0,
  `token` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ag_Whitelist`
--

CREATE TABLE `ag_Whitelist` (
  `idUser` int(11) NOT NULL,
  `idSite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `ag_Action`
--
ALTER TABLE `ag_Action`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ag_MailToken`
--
ALTER TABLE `ag_MailToken`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mail_user_fk` (`userId`);

--
-- Index pour la table `ag_Role`
--
ALTER TABLE `ag_Role`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ag_Site`
--
ALTER TABLE `ag_Site`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQUE_NAME` (`name`),
  ADD UNIQUE KEY `UNIQUE_DOMAIN` (`subDomain`),
  ADD UNIQUE KEY `UNIQUE_PREFIX` (`prefix`),
  ADD KEY `fk_site_user` (`creator`);

--
-- Index pour la table `ag_User`
--
ALTER TABLE `ag_User`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `ag_Whitelist`
--
ALTER TABLE `ag_Whitelist`
  ADD PRIMARY KEY (`idUser`,`idSite`),
  ADD KEY `fk_whitelist_site` (`idSite`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `ag_Action`
--
ALTER TABLE `ag_Action`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `ag_MailToken`
--
ALTER TABLE `ag_MailToken`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ag_Role`
--
ALTER TABLE `ag_Role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `ag_Site`
--
ALTER TABLE `ag_Site`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ag_User`
--
ALTER TABLE `ag_User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `ag_MailToken`
--
ALTER TABLE `ag_MailToken`
  ADD CONSTRAINT `mail_user_fk` FOREIGN KEY (`userId`) REFERENCES `ag_User` (`id`);

--
-- Contraintes pour la table `ag_Site`
--
ALTER TABLE `ag_Site`
  ADD CONSTRAINT `fk_site_user` FOREIGN KEY (`creator`) REFERENCES `ag_User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `ag_Whitelist`
--
ALTER TABLE `ag_Whitelist`
  ADD CONSTRAINT `fk_whitelist_site` FOREIGN KEY (`idSite`) REFERENCES `ag_Site` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_whitelist_user` FOREIGN KEY (`idUser`) REFERENCES `ag_User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

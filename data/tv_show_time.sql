-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 27, 2018 at 10:47 PM
-- Server version: 5.7.19
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tv_show_time`
--

-- --------------------------------------------------------

--
-- Table structure for table `badge`
--

DROP TABLE IF EXISTS `badge`;
CREATE TABLE IF NOT EXISTS `badge` (
  `idBadge` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`idBadge`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `badge`
--

INSERT INTO `badge` (`idBadge`, `nom`, `photo`, `description`) VALUES
(1, 'Spectateur', '', 'A regardé 10 épisodes d\'une série'),
(3, 'Gourmet', NULL, 'Suit 5 séries'),
(4, 'Fan', NULL, 'A regardé au total 50 épisodes');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `mail` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `salt` varchar(50) NOT NULL,
  `picture` varchar(200) NOT NULL DEFAULT 'https://cdn.opendemocracy.net/files/imagecache/wysiwyg_imageupload_lightbox_preset/wysiwyg_imageupload/557099/123_11.jpg',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `mail`, `password`, `salt`, `picture`) VALUES
(1, 'admin', 'admin@admin.fr', '9e95dca6bfafbffb3b9ae0b3a27df91a3819bd53e6bea7d5e883afb54e8e43fd', 'E1F53135E559C253', 'https://cdn.opendemocracy.net/files/imagecache/wysiwyg_imageupload_lightbox_preset/wysiwyg_imageupload/557099/123_11.jpg'),
(2, 'user', 'user@user.fr', '3863f65c6609ece2964e1e4c52a4338771b9438ced88bffbb705de0bbce2dca5', '84B03D034B409D4E', 'https://cdn.opendemocracy.net/files/imagecache/wysiwyg_imageupload_lightbox_preset/wysiwyg_imageupload/557099/123_11.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurbadge`
--

DROP TABLE IF EXISTS `utilisateurbadge`;
CREATE TABLE IF NOT EXISTS `utilisateurbadge` (
  `idUtilisateur` int(11) NOT NULL,
  `idBadge` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `utilisateurbadge`
--

INSERT INTO `utilisateurbadge` (`idUtilisateur`, `idBadge`) VALUES
(1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurepisodeserie`
--

DROP TABLE IF EXISTS `utilisateurepisodeserie`;
CREATE TABLE IF NOT EXISTS `utilisateurepisodeserie` (
  `idUtilisateur` int(11) NOT NULL,
  `idSerie` varchar(50) NOT NULL,
  `idSaison` int(11) NOT NULL,
  `idEpisode` int(11) NOT NULL,
  `note` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `utilisateurepisodeserie`
--

INSERT INTO `utilisateurepisodeserie` (`idUtilisateur`, `idSerie`, `idSaison`, `idEpisode`, `note`) VALUES
(1, 'stranger-things', 1, 1, 0),
(1, 'the-walking-dead', 1, 1, 0),
(1, 'game-of-thrones', 1, 1, 0),
(1, 'game-of-thrones', 1, 4, 0),
(1, 'the-walking-dead', 1, 2, 0),
(1, 'game-of-thrones', 1, 2, 0),
(1, 'game-of-thrones', 1, 3, 0),
(1, 'stranger-things', 1, 2, 0),
(1, 'orphan-black', 1, 1, 0),
(1, 'orphan-black', 1, 2, 0),
(1, 'stranger-things', 1, 2, 0),
(1, 'orphan-black', 1, 3, 0),
(1, 'orphan-black', 1, 4, 0),
(1, 'orphan-black', 1, 5, 0),
(1, 'stranger-things', 1, 2, 0),
(1, 'stranger-things', 1, 3, 0),
(1, 'stranger-things', 1, 4, 0),
(1, 'stranger-things', 1, 5, 0),
(1, 'stranger-things', 1, 6, 0),
(1, 'game-of-thrones', 1, 5, 0),
(1, 'game-of-thrones', 1, 6, 0),
(1, 'game-of-thrones', 1, 7, 0),
(1, 'game-of-thrones', 1, 8, 0);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurserie`
--

DROP TABLE IF EXISTS `utilisateurserie`;
CREATE TABLE IF NOT EXISTS `utilisateurserie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUtilisateur` int(11) NOT NULL,
  `idSerie` varchar(50) NOT NULL,
  `episodesRestants` int(11) DEFAULT NULL,
  `episodesVus` int(11) NOT NULL DEFAULT '0',
  `note` int(11) DEFAULT NULL,
  `favoris` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `utilisateurserie`
--

INSERT INTO `utilisateurserie` (`id`, `idUtilisateur`, `idSerie`, `episodesRestants`, `episodesVus`, `note`, `favoris`) VALUES
(2, 1, 'game-of-thrones', NULL, 8, NULL, 1),
(15, 1, 'doctor-who-2005', NULL, 0, NULL, 0),
(12, 2, 'friends', NULL, 0, NULL, 0),
(13, 1, 'breaking-bad', NULL, 0, NULL, 0),
(20, 1, 'stranger-things', NULL, 8, 5, 0),
(19, 1, 'sherlock', NULL, 0, NULL, 0),
(24, 1, 'how-i-met-your-mother', NULL, 0, NULL, 0),
(27, 1, 'lost-2004', NULL, 0, NULL, 0),
(29, 1, 'orphan-black', NULL, 5, NULL, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

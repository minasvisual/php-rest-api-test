-- Adminer 4.7.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';

DROP TABLE IF EXISTS `drink_counter`;
CREATE TABLE `drink_counter` (
  `iddrink` int(11) NOT NULL AUTO_INCREMENT,
  `drink_counter` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  PRIMARY KEY (`iddrink`),
  UNIQUE KEY `iduser` (`iduser`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `iduser` int(11) NOT NULL AUTO_INCREMENT,
  `email` char(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` char(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` char(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`iduser`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- 2020-05-22 01:16:48

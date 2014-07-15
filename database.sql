-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 15, 2014 at 08:18 AM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `hthssecretsanta`
--
CREATE DATABASE IF NOT EXISTS `hthssecretsanta` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `hthssecretsanta`;

-- --------------------------------------------------------

--
-- Table structure for table `allowed_emails`
--

CREATE TABLE IF NOT EXISTS `allowed_emails` (
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `globalvars`
--

CREATE TABLE IF NOT EXISTS `globalvars` (
  `firstyear` smallint(4) NOT NULL COMMENT 'The first year that data exists for',
  `registration` tinyint(1) NOT NULL,
  `maxgroups` int(2) NOT NULL,
  UNIQUE KEY `firstyear` (`firstyear`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='secretsanta global variables';

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `code` varchar(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(150) NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '1',
  `leaveable` tinyint(1) NOT NULL DEFAULT '1',
  `deleteable` tinyint(1) NOT NULL DEFAULT '1',
  `year` smallint(4) NOT NULL,
  PRIMARY KEY (`code`,`year`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups_owner`
--

CREATE TABLE IF NOT EXISTS `groups_owner` (
  `code` varchar(4) NOT NULL,
  `owner` int(10) NOT NULL,
  `year` smallint(4) NOT NULL,
  PRIMARY KEY (`code`,`owner`,`year`),
  KEY `owner` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups_template`
--

CREATE TABLE IF NOT EXISTS `groups_template` (
  `code` varchar(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(150) NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='public template groups';

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message_id` int(10) NOT NULL AUTO_INCREMENT,
  `from_user_id` int(10) NOT NULL,
  `to_user_id` int(10) NOT NULL,
  `year` smallint(4) NOT NULL,
  `group_code` varchar(4) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`message_id`),
  KEY `to_user_id` (`to_user_id`),
  KEY `from_user_id` (`from_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pairs`
--

CREATE TABLE IF NOT EXISTS `pairs` (
  `code` varchar(4) NOT NULL,
  `give` int(10) NOT NULL,
  `receive` int(10) NOT NULL,
  `year` smallint(4) NOT NULL,
  PRIMARY KEY (`code`,`give`,`receive`,`year`),
  KEY `give` (`give`),
  KEY `receive` (`receive`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='group pairings';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pubkey` text NOT NULL,
  `privkey` text NOT NULL,
  `year_join` smallint(4) NOT NULL,
  `class` tinyint(4) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=117 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(10) NOT NULL,
  `code` varchar(10) NOT NULL,
  `year` smallint(4) NOT NULL,
  PRIMARY KEY (`id`,`code`,`year`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `groups_owner`
--
ALTER TABLE `groups_owner`
ADD CONSTRAINT `groups_owner_ibfk_1` FOREIGN KEY (`code`) REFERENCES `groups` (`code`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `groups_owner_ibfk_2` FOREIGN KEY (`owner`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pairs`
--
ALTER TABLE `pairs`
ADD CONSTRAINT `pairs_ibfk_1` FOREIGN KEY (`code`) REFERENCES `groups` (`code`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `pairs_ibfk_2` FOREIGN KEY (`give`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `pairs_ibfk_3` FOREIGN KEY (`receive`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_groups`
--
ALTER TABLE `users_groups`
ADD CONSTRAINT `users_groups_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `users_groups_ibfk_2` FOREIGN KEY (`code`) REFERENCES `groups` (`code`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;

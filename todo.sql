-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 18, 2013 at 02:06 PM
-- Server version: 5.0.51
-- PHP Version: 5.3.3-7+squeeze15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `todo`
--

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL auto_increment,
  `descr` text NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=86 ;

-- --------------------------------------------------------

--
-- Table structure for table `todo`
--

CREATE TABLE IF NOT EXISTS `todo` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `position` int(8) unsigned NOT NULL default '0',
  `text` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `dt_added` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `status` enum('active','done','pendingdelete','deleted') collate utf8_unicode_ci NOT NULL default 'active',
  `category_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `position` (`position`),
  KEY `active` (`status`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=148 ;

-- --------------------------------------------------------

--
-- Table structure for table `todocat`
--

CREATE TABLE IF NOT EXISTS `todocat` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `position` int(8) unsigned NOT NULL default '0',
  `text` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `dt_added` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `status` enum('active','done','pendingdelete','deleted') collate utf8_unicode_ci NOT NULL default 'active',
  PRIMARY KEY  (`id`),
  KEY `position` (`position`),
  KEY `active` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;


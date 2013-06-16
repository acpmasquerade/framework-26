-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 16, 2013 at 09:54 PM
-- Server version: 5.5.31
-- PHP Version: 5.3.10-1ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `framework26`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','archived') NOT NULL DEFAULT 'active',
  `type` enum('error','warning','info') NOT NULL DEFAULT 'info',
  `visibility` enum('public','user') NOT NULL DEFAULT 'public',
  `visibility_object_id` varchar(25) DEFAULT NULL COMMENT 'user_id incase of visibility type as user',
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `message`, `created`, `status`, `type`, `visibility`, `visibility_object_id`, `modified`) VALUES
(1, 'Welcome to the Framework26', '2013-06-03 05:00:15', 'active', 'info', 'public', NULL, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(40) NOT NULL,
  `phone` varchar(18) NOT NULL,
  `user_role` enum('user','admin') NOT NULL DEFAULT 'user',
  `status` enum('active','inactive','deleted','pending') NOT NULL DEFAULT 'active',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `phone`, `user_role`, `status`, `created`) VALUES
(33, 'admin', '2c1c87fffb1478a877698f382d3951bc', 'info@acpmasquerade.com', '', 'admin', 'active', '2013-06-14 07:01:44');

-- --------------------------------------------------------

--
-- Table structure for table `users_meta`
--

CREATE TABLE IF NOT EXISTS `users_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` mediumtext NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

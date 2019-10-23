-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2019 at 09:44 PM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cases`
--
CREATE DATABASE IF NOT EXISTS `cases` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cases`;

-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

DROP TABLE IF EXISTS `cases`;
CREATE TABLE `cases` (
  `case_id` int(11) NOT NULL,
  `case_descr` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `case_date` date NOT NULL,
  `case_code` tinyint(2) DEFAULT NULL,
  `status_id` tinyint(2) DEFAULT '1',
  `case_type_id` int(11) NOT NULL,
  `is_active` tinyint(2) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `cases`:
--   `case_type_id`
--       `cases_types` -> `case_type_id`
--   `status_id`
--       `cases_statuses` -> `status_id`
--

--
-- Dumping data for table `cases`
--

INSERT INTO `cases` (`case_id`, `case_descr`, `case_date`, `case_code`, `status_id`, `case_type_id`, `is_active`) VALUES
(1, 'Case 2015', '2014-09-20', 4, 1, 4, 1),
(2, 'Case 2016', '2016-05-20', 3, 1, 1, 1),
(3, 'Case 2014', '2014-05-20', 4, 1, 3, 1),
(4, 'Case 2017', '2017-05-20', 2, 1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cases_items`
--

DROP TABLE IF EXISTS `cases_items`;
CREATE TABLE `cases_items` (
  `case_item_id` int(11) NOT NULL,
  `case_item_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `case_item_type_id` int(2) NOT NULL,
  `case_item_code` tinyint(2) DEFAULT NULL,
  `case_item_color` int(11) DEFAULT NULL,
  `ref_id` int(11) DEFAULT NULL,
  `case_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `cases_items`:
--   `case_id`
--       `cases` -> `case_id`
--

--
-- Dumping data for table `cases_items`
--

INSERT INTO `cases_items` (`case_item_id`, `case_item_name`, `case_item_type_id`, `case_item_code`, `case_item_color`, `ref_id`, `case_id`) VALUES
(1, 'Case Item A ', 0, 1, NULL, 1, 1),
(2, 'Case Item B', 0, 2, NULL, 1, 1),
(3, 'Case Item C', 0, 3, NULL, 1, 1),
(4, 'Case Item D', 0, 4, NULL, 1, 1),
(5, 'Case Item E', 0, 5, NULL, 1, 1),
(6, 'Case Item F', 0, 6, NULL, 1, 1),
(7, 'Case Item G', 0, 7, NULL, 1, 1),
(8, 'Case Item H', 0, 8, NULL, 1, 1),
(9, 'Case Item I', 0, 9, NULL, 1, 1),
(10, 'Case Item J', 0, 10, NULL, 1, 1),
(11, 'Case Item K ', 0, 11, NULL, 1, 1),
(12, 'Case Item L ', 0, 12, NULL, 1, 1),
(13, 'Case Item M', 0, 13, NULL, 1, 1),
(14, 'Case Item N', 0, 14, NULL, 1, 1),
(15, 'Case Item O', 0, 15, NULL, 1, 1),
(16, 'Case Item P', 0, 16, NULL, 1, 1),
(17, 'Case Item Q', 0, 17, NULL, 1, 1),
(18, 'Case Item R', 0, 18, NULL, 1, 1),
(19, 'Case Item S', 0, 19, NULL, 1, 1),
(20, 'Case Item T', 0, 20, NULL, 1, 1),
(21, 'Case Item U', 0, 21, NULL, 1, 1),
(22, 'Case Item V', 0, 22, NULL, 1, 1),
(24, 'AAA Case Item A', 3, 1, NULL, 9167, 2),
(25, 'AAA Case Item B', 3, 2, NULL, 9167, 2),
(26, 'BBB Case Item A', 3, 1, NULL, 9168, 2),
(27, 'BBB Case Item B', 3, 2, NULL, 9168, 2),
(28, 'BBB Case Item C', 3, 3, NULL, 9168, 2),
(32, 'Case Item W ', 0, 23, NULL, 1, 1),
(33, 'Case Item X', 0, 24, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cases_items_votes`
--

DROP TABLE IF EXISTS `cases_items_votes`;
CREATE TABLE `cases_items_votes` (
  `case_id` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `municipality_id` int(11) NOT NULL,
  `case_item_id` int(11) NOT NULL,
  `votes` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `case_region_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `cases_items_votes`:
--   `case_item_id`
--       `cases_items` -> `case_item_id`
--   `case_id`
--       `cases` -> `case_id`
--   `case_region_id`
--       `cases_regions` -> `case_region_id`
--   `municipality_id`
--       `municipalities` -> `municipality_id`
--   `station_id`
--       `stations` -> `station_id`
--

--
-- Dumping data for table `cases_items_votes`
--

INSERT INTO `cases_items_votes` (`case_id`, `station_id`, `municipality_id`, `case_item_id`, `votes`, `user_id`, `created_at`, `updated_at`, `case_region_id`) VALUES
(1, 1, 9186, 1, 1, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 1, 1, 1, '2019-10-07 16:26:48', '2019-10-07 16:26:48', 9),
(1, 1, 9186, 2, 1, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 2, 1, 1, '2019-10-07 16:26:48', '2019-10-07 16:26:48', 9),
(1, 1, 9186, 3, 1, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 3, 1, 1, '2019-10-07 16:26:48', '2019-10-07 16:26:48', 9),
(1, 1, 9186, 4, 1, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 4, 1, 1, '2019-10-07 16:26:48', '2019-10-07 16:26:48', 9),
(1, 1, 9186, 5, 1, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 5, 1, 1, '2019-10-07 16:26:48', '2019-10-07 16:26:48', 9),
(1, 1, 9186, 6, 1, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 6, 0, 1, '2019-10-07 16:26:48', '2019-10-07 16:26:48', 9),
(1, 1, 9186, 7, 1, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 7, 0, 1, '2019-10-07 16:26:48', '2019-10-07 16:26:48', 9),
(1, 1, 9186, 8, 1, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 8, 0, 1, '2019-10-07 16:26:48', '2019-10-07 16:26:48', 9),
(1, 1, 9186, 9, 2, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 9, 0, 1, '2019-10-07 16:26:48', '2019-10-07 16:26:48', 9),
(1, 1, 9186, 10, 0, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 10, 0, 1, '2019-10-07 16:26:48', '2019-10-07 16:26:48', 9),
(1, 1, 9186, 11, 0, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 11, 0, 1, '2019-10-07 16:26:48', '2019-10-07 16:26:48', 9),
(1, 1, 9186, 12, 0, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 12, 0, 1, '2019-10-07 16:26:49', '2019-10-07 16:26:49', 9),
(1, 1, 9186, 13, 0, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 13, 0, 1, '2019-10-07 16:26:49', '2019-10-07 16:26:49', 9),
(1, 1, 9186, 14, 0, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 14, 0, 1, '2019-10-07 16:26:49', '2019-10-07 16:26:49', 9),
(1, 1, 9186, 15, 0, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 15, 0, 1, '2019-10-07 16:26:49', '2019-10-07 16:26:49', 9),
(1, 1, 9186, 16, 0, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 16, 0, 1, '2019-10-07 16:26:49', '2019-10-07 16:26:49', 9),
(1, 1, 9186, 17, 0, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 17, 0, 1, '2019-10-07 16:26:49', '2019-10-07 16:26:49', 9),
(1, 1, 9186, 18, 0, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 18, 0, 1, '2019-10-07 16:26:49', '2019-10-07 16:26:49', 9),
(1, 1, 9186, 19, 0, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 19, 0, 1, '2019-10-07 16:26:49', '2019-10-07 16:26:49', 9),
(1, 1, 9186, 20, 0, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 20, 0, 1, '2019-10-07 16:26:49', '2019-10-07 16:26:49', 9),
(1, 1, 9186, 21, 0, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 21, 0, 1, '2019-10-07 16:26:49', '2019-10-07 16:26:49', 9),
(1, 1, 9186, 22, 0, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 22, 0, 1, '2019-10-07 16:26:49', '2019-10-07 16:26:49', 9),
(1, 1, 9186, 32, 0, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 32, 0, 1, '2019-10-07 16:26:49', '2019-10-07 16:26:49', 9),
(1, 1, 9186, 33, 0, 3, '2019-10-22 17:33:31', '2019-10-22 14:33:31', 5),
(1, 1, 9203, 33, 0, 1, '2019-10-07 16:26:49', '2019-10-07 16:26:49', 9),
(1, 2, 9186, 1, 1, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 1, 1, 1, '2019-10-12 05:05:50', '2019-10-12 05:05:50', 9),
(1, 2, 9186, 2, 1, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 2, 1, 1, '2019-10-12 05:05:50', '2019-10-12 05:05:50', 9),
(1, 2, 9186, 3, 1, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 3, 1, 1, '2019-10-12 05:05:50', '2019-10-12 05:05:50', 9),
(1, 2, 9186, 4, 1, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 4, 1, 1, '2019-10-12 05:05:50', '2019-10-12 05:05:50', 9),
(1, 2, 9186, 5, 1, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 5, 1, 1, '2019-10-12 05:05:50', '2019-10-12 05:05:50', 9),
(1, 2, 9186, 6, 0, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 6, 0, 1, '2019-10-12 05:05:50', '2019-10-12 05:05:50', 9),
(1, 2, 9186, 7, 0, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 7, 0, 1, '2019-10-12 05:05:50', '2019-10-12 05:05:50', 9),
(1, 2, 9186, 8, 0, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 8, 0, 1, '2019-10-12 05:05:50', '2019-10-12 05:05:50', 9),
(1, 2, 9186, 9, 0, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 9, 0, 1, '2019-10-12 05:05:50', '2019-10-12 05:05:50', 9),
(1, 2, 9186, 10, 0, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 10, 0, 1, '2019-10-12 05:05:50', '2019-10-12 05:05:50', 9),
(1, 2, 9186, 11, 0, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 11, 0, 1, '2019-10-12 05:05:50', '2019-10-12 05:05:50', 9),
(1, 2, 9186, 12, 0, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 12, 0, 1, '2019-10-12 05:05:50', '2019-10-12 05:05:50', 9),
(1, 2, 9186, 13, 0, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 13, 0, 1, '2019-10-12 05:05:50', '2019-10-12 05:05:50', 9),
(1, 2, 9186, 14, 0, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 14, 0, 1, '2019-10-12 05:05:50', '2019-10-12 05:05:50', 9),
(1, 2, 9186, 15, 0, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 15, 0, 1, '2019-10-12 05:05:50', '2019-10-12 05:05:50', 9),
(1, 2, 9186, 16, 0, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 16, 0, 1, '2019-10-12 05:05:50', '2019-10-12 05:05:50', 9),
(1, 2, 9186, 17, 0, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 17, 0, 1, '2019-10-12 05:05:50', '2019-10-12 05:05:50', 9),
(1, 2, 9186, 18, 0, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 18, 0, 1, '2019-10-12 05:05:51', '2019-10-12 05:05:51', 9),
(1, 2, 9186, 19, 0, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 19, 0, 1, '2019-10-12 05:05:51', '2019-10-12 05:05:51', 9),
(1, 2, 9186, 20, 0, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 20, 0, 1, '2019-10-12 05:05:51', '2019-10-12 05:05:51', 9),
(1, 2, 9186, 21, 0, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 21, 0, 1, '2019-10-12 05:05:51', '2019-10-12 05:05:51', 9),
(1, 2, 9186, 22, 0, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 22, 0, 1, '2019-10-12 05:05:51', '2019-10-12 05:05:51', 9),
(1, 2, 9186, 32, 0, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 32, 0, 1, '2019-10-12 05:05:51', '2019-10-12 05:05:51', 9),
(1, 2, 9186, 33, 0, 2, '2019-10-07 08:04:25', '2019-10-07 08:04:25', 5),
(1, 2, 9203, 33, 0, 1, '2019-10-12 05:05:51', '2019-10-12 05:05:51', 9),
(1, 14, 9168, 1, 1, 1, '2019-10-07 16:24:22', '2019-10-07 16:24:22', 6),
(1, 14, 9168, 2, 1, 1, '2019-10-07 16:24:22', '2019-10-07 16:24:22', 6),
(1, 14, 9168, 3, 1, 1, '2019-10-07 16:24:22', '2019-10-07 16:24:22', 6),
(1, 14, 9168, 4, 1, 1, '2019-10-07 16:24:22', '2019-10-07 16:24:22', 6),
(1, 14, 9168, 5, 0, 1, '2019-10-07 16:24:22', '2019-10-07 16:24:22', 6),
(1, 14, 9168, 6, 0, 1, '2019-10-07 16:24:22', '2019-10-07 16:24:22', 6),
(1, 14, 9168, 7, 0, 1, '2019-10-07 16:24:22', '2019-10-07 16:24:22', 6),
(1, 14, 9168, 8, 0, 1, '2019-10-07 16:24:22', '2019-10-07 16:24:22', 6),
(1, 14, 9168, 9, 0, 1, '2019-10-07 16:24:22', '2019-10-07 16:24:22', 6),
(1, 14, 9168, 10, 0, 1, '2019-10-07 16:24:22', '2019-10-07 16:24:22', 6),
(1, 14, 9168, 11, 0, 1, '2019-10-07 16:24:22', '2019-10-07 16:24:22', 6),
(1, 14, 9168, 12, 0, 1, '2019-10-07 16:24:23', '2019-10-07 16:24:23', 6),
(1, 14, 9168, 13, 0, 1, '2019-10-07 16:24:23', '2019-10-07 16:24:23', 6),
(1, 14, 9168, 14, 0, 1, '2019-10-07 16:24:23', '2019-10-07 16:24:23', 6),
(1, 14, 9168, 15, 0, 1, '2019-10-07 16:24:23', '2019-10-07 16:24:23', 6),
(1, 14, 9168, 16, 0, 1, '2019-10-07 16:24:23', '2019-10-07 16:24:23', 6),
(1, 14, 9168, 17, 0, 1, '2019-10-07 16:24:23', '2019-10-07 16:24:23', 6),
(1, 14, 9168, 18, 0, 1, '2019-10-07 16:24:23', '2019-10-07 16:24:23', 6),
(1, 14, 9168, 19, 0, 1, '2019-10-07 16:24:23', '2019-10-07 16:24:23', 6),
(1, 14, 9168, 20, 0, 1, '2019-10-07 16:24:23', '2019-10-07 16:24:23', 6),
(1, 14, 9168, 21, 0, 1, '2019-10-07 16:24:23', '2019-10-07 16:24:23', 6),
(1, 14, 9168, 22, 0, 1, '2019-10-07 16:24:23', '2019-10-07 16:24:23', 6),
(1, 14, 9168, 32, 0, 1, '2019-10-07 16:24:23', '2019-10-07 16:24:23', 6),
(1, 14, 9168, 33, 0, 1, '2019-10-07 16:24:23', '2019-10-07 16:24:23', 6);

-- --------------------------------------------------------

--
-- Table structure for table `cases_regions`
--

DROP TABLE IF EXISTS `cases_regions`;
CREATE TABLE `cases_regions` (
  `case_id` int(11) NOT NULL,
  `case_region_id` int(11) NOT NULL,
  `case_region_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region_id` int(11) DEFAULT NULL,
  `station_id_from` int(11) DEFAULT NULL,
  `station_id_to` int(11) DEFAULT NULL,
  `case_region_user_group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `cases_regions`:
--   `case_id`
--       `cases` -> `case_id`
--   `region_id`
--       `regions` -> `region_id`
--   `case_region_user_group_id`
--       `cases_regions_users_groups` -> `group_id`
--

--
-- Dumping data for table `cases_regions`
--

INSERT INTO `cases_regions` (`case_id`, `case_region_id`, `case_region_name`, `region_id`, `station_id_from`, `station_id_to`, `case_region_user_group_id`) VALUES
(1, 5, 'Case Region A', 1, 1, 6, 1),
(1, 6, 'Case Region B', 1, 1, 100, 2),
(1, 8, 'Case Region C', 1, 1, 5, 3),
(1, 9, 'Case Region D', 1, 1, 8, 4),
(2, 5, 'Case Region A', 1, 1, 6, 1),
(2, 6, 'Case Region B', 1, 1, 17, 2),
(2, 8, 'Case Region C', 1, 1, 5, 3),
(2, 9, 'Case Region D', 1, 1, 8, 4),
(3, 5, 'Case Region A', 1, 1, 6, NULL),
(3, 6, 'Case Region B', 1, 1, 17, NULL),
(3, 8, 'Case Region C', 1, 1, 5, NULL),
(3, 9, 'Case Region D', 1, 1, 8, NULL),
(4, 5, 'Case Region A', 1, 1, 6, NULL),
(4, 6, 'Case Region B', 1, 1, 17, NULL),
(4, 8, 'Case Region C', 1, 1, 5, NULL),
(4, 9, 'Case Region D', 1, 1, 8, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cases_regions_users`
--

DROP TABLE IF EXISTS `cases_regions_users`;
CREATE TABLE `cases_regions_users` (
  `user_id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `case_region_id` int(11) NOT NULL,
  `station_id_from` int(11) DEFAULT NULL,
  `station_id_to` int(11) DEFAULT NULL,
  `case_region_user_group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `cases_regions_users`:
--   `case_id`
--       `cases` -> `case_id`
--   `case_region_id`
--       `cases_regions` -> `case_region_id`
--   `case_region_user_group_id`
--       `cases_regions_users_groups` -> `group_id`
--   `user_id`
--       `users` -> `id`
--

--
-- Dumping data for table `cases_regions_users`
--

INSERT INTO `cases_regions_users` (`user_id`, `case_id`, `case_region_id`, `station_id_from`, `station_id_to`, `case_region_user_group_id`) VALUES
(2, 1, 5, NULL, NULL, 2),
(2, 1, 8, NULL, NULL, 3),
(2, 2, 5, NULL, NULL, 1),
(3, 1, 5, 1, 6, 2),
(4, 1, 5, 1, 20, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cases_regions_users_groups`
--

DROP TABLE IF EXISTS `cases_regions_users_groups`;
CREATE TABLE `cases_regions_users_groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `cases_regions_users_groups`:
--

--
-- Dumping data for table `cases_regions_users_groups`
--

INSERT INTO `cases_regions_users_groups` (`group_id`, `group_name`) VALUES
(1, 'Register(s) of Case Region A'),
(2, 'Register(s) of Case Region B'),
(3, 'Register(s) of Case Region C'),
(4, 'Register(s) of Case Region D');

-- --------------------------------------------------------

--
-- Table structure for table `cases_statuses`
--

DROP TABLE IF EXISTS `cases_statuses`;
CREATE TABLE `cases_statuses` (
  `status_id` tinyint(4) NOT NULL,
  `status_descr` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `cases_statuses`:
--

--
-- Dumping data for table `cases_statuses`
--

INSERT INTO `cases_statuses` (`status_id`, `status_descr`) VALUES
(1, 'Enabled'),
(2, 'Disabled');

-- --------------------------------------------------------

--
-- Table structure for table `cases_types`
--

DROP TABLE IF EXISTS `cases_types`;
CREATE TABLE `cases_types` (
  `case_type_id` int(11) NOT NULL,
  `case_type_descr` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `cases_types`:
--

--
-- Dumping data for table `cases_types`
--

INSERT INTO `cases_types` (`case_type_id`, `case_type_descr`) VALUES
(1, 'First Case'),
(2, 'Second Case'),
(3, 'Third Case'),
(4, 'Fourth Case');

-- --------------------------------------------------------

--
-- Stand-in structure for view `cases_view`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `cases_view`;
CREATE TABLE `cases_view` (
`id` int(11)
,`case_descr` varchar(45)
,`case_date` date
,`case_year` int(4)
,`is_active` tinyint(2)
,`is_active_str` varchar(1)
,`case_type_id` int(11)
,`case_type_id_str` varchar(5)
,`case_type_descr` varchar(30)
);

-- --------------------------------------------------------

--
-- Table structure for table `municipalities`
--

DROP TABLE IF EXISTS `municipalities`;
CREATE TABLE `municipalities` (
  `municipality_id` int(11) NOT NULL,
  `municipality_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regional_division_id` int(11) DEFAULT NULL,
  `case_region_id` int(11)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `municipalities`:
--   `case_region_id`
--       `cases_regions` -> `case_region_id`
--

--
-- Dumping data for table `municipalities`
--

INSERT INTO `municipalities` (`municipality_id`, `municipality_name`, `regional_division_id`, `case_region_id`) VALUES
(9167, 'Municipality A', 1, 6),
(9168, 'Municipality B', 1, 6),
(9169, 'Municipality C', 1, 6),
(9179, 'Municipality D', 2, 6),
(9181, 'Municipality E', 2, 6),
(9183, 'Municipality F', 2, 6),
(9186, 'Municipality G', 3, 5),
(9203, 'Municipality H', 5, 9),
(9204, 'Municipality J', 5, 9),
(9205, 'Municipality I', 5, 8),
(9207, 'Municipality K', 6, 8),
(9208, 'Municipality L', 6, 8);

-- --------------------------------------------------------

--
-- Table structure for table `municipal_districts`
--

DROP TABLE IF EXISTS `municipal_districts`;
CREATE TABLE `municipal_districts` (
  `municipal_district_id` int(11) NOT NULL,
  `municipal_districts_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `municipal_division_id` int(11) NOT NULL,
  `municipality_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `municipal_districts`:
--

-- --------------------------------------------------------

--
-- Table structure for table `municipal_divisions`
--

DROP TABLE IF EXISTS `municipal_divisions`;
CREATE TABLE `municipal_divisions` (
  `municipal_division_id` int(11) NOT NULL,
  `municipal_division_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `municipality_id` int(11) NOT NULL,
  `municipal_division_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `municipal_divisions`:
--

--
-- Dumping data for table `municipal_divisions`
--

INSERT INTO `municipal_divisions` (`municipal_division_id`, `municipal_division_name`, `municipality_id`, `municipal_division_code`) VALUES
(2, 'Α division', 9167, '0102'),
(5, 'B Division', 9186, '0105'),
(6, 'C Division 1', 9181, '0106'),
(10, 'D division 1', 9169, '0110'),
(13, 'E division', 9196, '0113'),
(18, 'F division A', 9191, '0118'),
(39, 'G Division ', 9183, '0139'),
(45, 'H Division', 9185, '0145'),
(46, 'J Division', 9178, '0146'),
(58, 'I Division', 9216, '0310'),
(60, 'K Division', 9216, '0312'),
(61, 'L Division', 9216, '0313'),
(77, 'M Division', 9220, '0329'),
(94, 'N Division', 9228, '1102'),
(96, 'O division', 9229, '1104'),
(107, 'P division', 9208, '4003'),
(112, 'Q Division', 9203, '4008'),
(115, 'R Division', 9204, '4011'),
(116, 'Y Division', 9205, '4012');

-- --------------------------------------------------------

--
-- Table structure for table `regional_divisions`
--

DROP TABLE IF EXISTS `regional_divisions`;
CREATE TABLE `regional_divisions` (
  `regional_division_id` int(11) NOT NULL,
  `regional_division_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `regional_divisions`:
--   `region_id`
--       `regions` -> `region_id`
--

--
-- Dumping data for table `regional_divisions`
--

INSERT INTO `regional_divisions` (`regional_division_id`, `regional_division_name`, `region_id`) VALUES
(1, 'Regional Division Aaa 1', 1),
(2, 'Regional Division Aaa 2', 1),
(3, 'Regional Division Aaa 3', 1),
(4, 'Regional Division Aaa 4', 1),
(5, 'Regional Division Bbb', 1),
(6, 'Regional Division Ccc', 1),
(7, 'Regional Division Ddd', 1),
(8, 'Regional Division Eee', 1);

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

DROP TABLE IF EXISTS `regions`;
CREATE TABLE `regions` (
  `region_id` int(11) NOT NULL,
  `region_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `regions`:
--

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`region_id`, `region_name`, `region_code`) VALUES
(1, 'Region ABC', '5');

-- --------------------------------------------------------

--
-- Table structure for table `stations`
--

DROP TABLE IF EXISTS `stations`;
CREATE TABLE `stations` (
  `station_id` int(11) NOT NULL,
  `station_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `station_address` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `case_region_id` int(11) NOT NULL,
  `case_district_id` int(11) NOT NULL,
  `municipal_division_id` int(11) NOT NULL,
  `municipality_id` int(11) NOT NULL,
  `station_officer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `stations`:
--   `case_region_id`
--       `cases_regions` -> `case_region_id`
--

--
-- Dumping data for table `stations`
--

INSERT INTO `stations` (`station_id`, `station_name`, `station_address`, `case_region_id`, `case_district_id`, `municipal_division_id`, `municipality_id`, `station_officer_id`) VALUES
(1, '1 Station', NULL, 5, 151, 5, 9186, 1),
(1, '1 Station', NULL, 6, 1, 2, 9167, 2),
(1, '170 Station', NULL, 8, 339, 116, 9205, NULL),
(1, '203 Station', NULL, 9, 305, 112, 9203, NULL),
(2, '2 Station', NULL, 5, 151, 5, 9186, 1),
(2, '2 Station', NULL, 6, 1, 2, 9167, 2),
(2, '2 Station', NULL, 8, 335, 116, 9205, NULL),
(2, '2 Station', NULL, 9, 282, 110, 9203, NULL),
(3, '205 Station', NULL, 5, 180, 5, 9186, NULL),
(3, '173 Station', NULL, 6, 11, 10, 9169, NULL),
(3, '172 Station', NULL, 8, 339, 116, 9205, NULL),
(3, '204 Station', NULL, 9, 305, 112, 9203, NULL),
(4, '203 Station', NULL, 5, 180, 5, 9186, NULL),
(4, '170 Station', NULL, 6, 11, 10, 9169, NULL),
(4, '153 Station', NULL, 8, 352, 116, 9205, NULL),
(4, '205 Station', NULL, 9, 305, 112, 9203, NULL),
(5, '172 Station', NULL, 5, 175, 5, 9186, NULL),
(5, '175 Station', NULL, 6, 11, 10, 9169, NULL),
(5, '203 Station', NULL, 8, 336, 116, 9205, NULL),
(5, '172 Station', NULL, 9, 302, 112, 9203, NULL),
(6, '170 Station', NULL, 5, 175, 5, 9186, NULL),
(6, '160 Station', NULL, 6, 11, 10, 9169, NULL),
(6, '170 Station', NULL, 9, 302, 112, 9203, NULL),
(7, '158 Station', NULL, 6, 11, 10, 9169, NULL),
(7, '153 Station', NULL, 9, 301, 112, 9203, NULL),
(8, '176 Station', NULL, 6, 11, 10, 9169, NULL),
(8, '206 Station', NULL, 9, 305, 112, 9203, NULL),
(9, '172 Station', NULL, 6, 11, 10, 9169, NULL),
(10, '143 Station', NULL, 6, 10, 8, 9168, NULL),
(11, '134 Station', NULL, 6, 10, 8, 9168, NULL),
(12, '131 Station', NULL, 6, 10, 8, 9168, NULL),
(13, '120 Station', NULL, 6, 9, 8, 9168, NULL),
(14, '109 Station', NULL, 6, 8, 8, 9168, NULL),
(15, '31 Station', NULL, 6, 2, 2, 9167, NULL),
(16, '30 Station', NULL, 6, 2, 2, 9167, NULL),
(17, '29 Station', NULL, 6, 2, 2, 9167, NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `stations_not_registered_view`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `stations_not_registered_view`;
CREATE TABLE `stations_not_registered_view` (
`user_id` int(11)
,`case_id` int(11)
,`status_id` int(2)
,`case_region_id` int(11)
,`station_id` int(11)
,`municipality_id` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `stations_officers`
--

DROP TABLE IF EXISTS `stations_officers`;
CREATE TABLE `stations_officers` (
  `station_officer_id` int(11) NOT NULL,
  `station_officer_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone1` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone2` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `stations_officers`:
--

--
-- Dumping data for table `stations_officers`
--

INSERT INTO `stations_officers` (`station_officer_id`, `station_officer_name`, `phone1`, `phone2`) VALUES
(1, 'Station Officer Aaaa', '222 3343 655', '231 5433 644'),
(2, 'Station Officer Abcdef', '123 4567 9876', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stations_statuses`
--

DROP TABLE IF EXISTS `stations_statuses`;
CREATE TABLE `stations_statuses` (
  `status_id` tinyint(4) NOT NULL,
  `status_descr` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `stations_statuses`:
--

--
-- Dumping data for table `stations_statuses`
--

INSERT INTO `stations_statuses` (`status_id`, `status_descr`) VALUES
(-1, 'Not Registered'),
(1, 'Problematic'),
(2, 'Registered/To Send'),
(3, 'Sent'),
(4, 'Edited/Ready to Send'),
(5, 'Sent/Problematic'),
(6, 'Deleted');

-- --------------------------------------------------------


--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_id` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `case_region_user_group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `users`:
--   `group_id`
--       `user_groups` -> `group_id`
--

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `group_id`, `created_at`, `updated_at`, `email`, `password`, `remember_token`, `case_region_user_group_id`) VALUES
(1, 'Administrator', 1, '2019-10-22 20:24:31', '2019-10-22 20:24:31', 'admin@gmail.com', '$2y$10$SSEH/IpHaXh.kVGEtTH6Uuuo9XwMTHYL8Gd0GGxwoz/f3zjs/YPXq', '6PpiYz3HJatGwalgdeXDwobjHTChAqMdFaigfd8t0xTrX8FMWT7jASGSFew1', NULL),
(2, 'Register A', 3, '2019-10-22 19:27:43', '2019-10-22 19:27:43', 'register@gmail.com', '$2y$10$SSEH/IpHaXh.kVGEtTH6Uuuo9XwMTHYL8Gd0GGxwoz/f3zjs/YPXq', 'CRwwKBh3jnKuQgpijhqokJQLZRiHvq0kwzLE5EsQvVT0u9t4zAwjYBnm8F6X', 3),
(3, 'Supervisor A', 2, '2019-10-22 19:50:43', '2019-10-22 19:50:43', 'supervisor@gmail.com', '$2y$10$SSEH/IpHaXh.kVGEtTH6Uuuo9XwMTHYL8Gd0GGxwoz/f3zjs/YPXq', 'dtyKFj2fhi3iarPtIse0toXFguOcqm7BKNV5tyIiqFvrLkfvL1Gd0EwZ4fjR', NULL),
(4, 'Register B', 3, '2019-10-21 20:18:13', '2019-10-21 20:18:13', 'register2@gmail.com', '$2y$10$SSEH/IpHaXh.kVGEtTH6Uuuo9XwMTHYL8Gd0GGxwoz/f3zjs/YPXq', 'Sj1qnwA1GqSK0WYYLGr94rR0lInAWwFPb7gxxtXpK0ldIvrX1bXqxZW22ww7', 3);

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

DROP TABLE IF EXISTS `user_groups`;
CREATE TABLE `user_groups` (
  `group_id` tinyint(4) NOT NULL,
  `group_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `user_groups`:
--

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`group_id`, `group_name`) VALUES
(1, 'Administrator'),
(2, 'Supervisor'),
(3, 'Register'),
(4, 'Guest');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
CREATE TABLE `votes` (
  `case_id` int(11) NOT NULL,
  `case_region_id` int(11) DEFAULT NULL,
  `municipality_id` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `registered` int(11) DEFAULT NULL,
  `voted` int(11) DEFAULT NULL,
  `votes_invalid` int(11) DEFAULT NULL,
  `votes_blank` int(11) DEFAULT NULL,
  `votes_invalid_blank` int(11) DEFAULT NULL,
  `votes_valid` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `status_id` tinyint(4) NOT NULL,
  `send_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `locked_at` timestamp NULL DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `votes`:
--   `status_id`
--       `stations_statuses` -> `status_id`
--   `user_id`
--       `users` -> `id`
--

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`case_id`, `case_region_id`, `municipality_id`, `station_id`, `registered`, `voted`, `votes_invalid`, `votes_blank`, `votes_invalid_blank`, `votes_valid`, `user_id`, `status_id`, `send_date`, `created_at`, `updated_at`, `locked_at`, `locked_by`) VALUES
(1, 5, 9186, 1, 30, 20, 10, 0, 10, 10, 3, 2, NULL, '2019-10-05 19:26:37', '2019-10-22 14:33:31', NULL, NULL),
(1, 9, 9203, 1, 20, 10, 2, 3, 5, 5, 1, 2, NULL, '2019-10-07 16:26:48', '2019-10-07 16:26:48', NULL, NULL),
(1, 5, 9186, 2, 20, 10, 5, 0, 5, 5, 2, 2, NULL, '2019-10-07 08:04:24', '2019-10-07 08:04:24', NULL, NULL),
(1, 9, 9203, 2, 10, 8, 2, 1, 3, 5, 1, 2, NULL, '2019-10-12 05:05:50', '2019-10-12 05:05:50', NULL, NULL),
(1, 5, 9186, 4, 30, 10, 10, 0, 10, 10, 2, 1, NULL, '2019-10-13 15:59:16', '2019-10-13 15:59:16', NULL, NULL),
(1, 6, 9168, 14, 10, 5, 1, 0, 1, 4, 1, 2, NULL, '2019-10-07 16:24:22', '2019-10-07 16:24:22', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure for view `cases_view`
--


CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cases_view`  AS  select `e`.`case_id` AS `id`,`e`.`case_descr` AS `case_descr`,`e`.`case_date` AS `case_date`,year(`e`.`case_date`) AS `case_year`,`e`.`is_active` AS `is_active`,cast(`e`.`is_active` as char(1) charset utf8mb4) AS `is_active_str`,`e`.`case_type_id` AS `case_type_id`,cast(`e`.`case_type_id` as char(5) charset utf8mb4) AS `case_type_id_str`,`et`.`case_type_descr` AS `case_type_descr` from (`cases` `e` join `cases_types` `et` on((`e`.`case_type_id` = `et`.`case_type_id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `stations_not_registered_view`
--


CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `stations_not_registered_view`  AS  select `u`.`id` AS `user_id`,`c`.`case_id` AS `case_id`,-(1) AS `status_id`,`r`.`case_region_id` AS `case_region_id`,`s`.`station_id` AS `station_id`,`s`.`municipality_id` AS `municipality_id` from ((((`cases` `c` join `cases_regions` `r` on((`c`.`case_id` = `r`.`case_id`))) join `cases_regions_users` `ru` on(((`ru`.`case_id` = `r`.`case_id`) and (`ru`.`case_region_id` = `r`.`case_region_id`)))) join `users` `u` on((`u`.`id` = `ru`.`user_id`))) join `stations` `s` on((`r`.`case_region_id` = `s`.`case_region_id`))) where ((((`ru`.`station_id_from` is not null) and (`ru`.`station_id_to` is not null) and (`s`.`station_id` between `ru`.`station_id_from` and `ru`.`station_id_to`)) or (isnull(`ru`.`station_id_from`) and isnull(`ru`.`station_id_to`) and (`s`.`station_id` between `r`.`station_id_from` and `r`.`station_id_to`))) and (not(exists(select 1 from `votes` `v` where ((`v`.`case_id` = `c`.`case_id`) and (`v`.`municipality_id` = `s`.`municipality_id`) and (`v`.`station_id` = `s`.`station_id`)))))) ;

-- --------------------------------------------------------

--
-- Structure for view `stations_view`
--


CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `stations_view`  AS  select `users`.`id` AS `user_id`,`case_id` AS `case_id`,ifnull(`stations_statuses`.`status_id`,-(1)) AS `status_id`,`stations`.`station_id` AS `station_id`,`stations`.`municipality_id` AS `municipality_id` from ((((((`cases` join `cases_regions`) join `users`) join `stations` on((`stations`.`case_region_id` = `cases_regions`.`case_region_id`))) join `cases_regions_users` on(((`case_id` = `cases_regions_users`.`case_id`) and (`users`.`id` = `cases_regions_users`.`user_id`) and (`cases_regions`.`case_region_id` = `cases_regions_users`.`case_region_id`)))) left join `votes` on(((`case_id` = `votes`.`case_id`) and (`stations`.`station_id` = `votes`.`station_id`) and (`stations`.`municipality_id` = `votes`.`municipality_id`)))) left join `stations_statuses` on((`votes`.`status_id` = `stations_statuses`.`status_id`))) where (`stations`.`station_id` between `cases_regions_users`.`station_id_from` and `cases_regions_users`.`station_id_to`) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`case_id`),
  ADD KEY `idx_cases_case_type_id` (`case_type_id`),
  ADD KEY `idx_cases_status_id` (`status_id`);

--
-- Indexes for table `cases_items`
--
ALTER TABLE `cases_items`
  ADD PRIMARY KEY (`case_item_id`),
  ADD KEY `idx_cases_items_case_id` (`case_id`);

--
-- Indexes for table `cases_items_votes`
--
ALTER TABLE `cases_items_votes`
  ADD PRIMARY KEY (`case_id`,`station_id`,`case_item_id`,`municipality_id`),
  ADD KEY `idx_cases_items_votes_case_region_id` (`case_region_id`),
  ADD KEY `idx_cases_items_votes_station_id` (`station_id`),
  ADD KEY `idx_cases_items_votes_case_item_id` (`case_item_id`),
  ADD KEY `idx_cases_items_votes_case_id` (`case_id`),
  ADD KEY `fk_cases_items_votes_municipality_id` (`municipality_id`);

--
-- Indexes for table `cases_regions`
--
ALTER TABLE `cases_regions`
  ADD PRIMARY KEY (`case_id`,`case_region_id`),
  ADD KEY `idx_cases_regions_case_id_case_region_id` (`case_id`,`case_region_id`),
  ADD KEY `idx_cases_regions_case_id` (`case_id`),
  ADD KEY `idx_cases_regions_case_region_id` (`case_region_id`),
  ADD KEY `idx_cases_regions_user_group_id` (`case_region_user_group_id`),
  ADD KEY `idx_cases_regions_station_id_from` (`station_id_from`),
  ADD KEY `idx_cases_regions_station_id_to` (`station_id_to`),
  ADD KEY `idx_cases_regions_region_id` (`region_id`);

--
-- Indexes for table `cases_regions_users`
--
ALTER TABLE `cases_regions_users`
  ADD PRIMARY KEY (`user_id`,`case_id`,`case_region_id`),
  ADD KEY `idx_cases_regions_users_case_region_id` (`case_region_id`),
  ADD KEY `idx_cases_regions_users_user_id` (`user_id`),
  ADD KEY `idx_cases_regions_users_case_id` (`case_id`),
  ADD KEY `idx_cases_regions_users_group_id` (`case_region_user_group_id`);

--
-- Indexes for table `cases_regions_users_groups`
--
ALTER TABLE `cases_regions_users_groups`
  ADD PRIMARY KEY (`group_id`),
  ADD UNIQUE KEY `idx_cases_regions_users_groups_group_id` (`group_id`);

--
-- Indexes for table `cases_statuses`
--
ALTER TABLE `cases_statuses`
  ADD PRIMARY KEY (`status_id`),
  ADD UNIQUE KEY `idx_cases_statuses_status_id` (`status_id`);

--
-- Indexes for table `cases_types`
--
ALTER TABLE `cases_types`
  ADD PRIMARY KEY (`case_type_id`),
  ADD UNIQUE KEY `idx_cases_types_case_type_id` (`case_type_id`);

--
-- Indexes for table `municipalities`
--
ALTER TABLE `municipalities`
  ADD PRIMARY KEY (`municipality_id`),
  ADD UNIQUE KEY `idx_municipalities_municipality_id` (`municipality_id`),
  ADD KEY `idx_municipalities_regional_division_id` (`regional_division_id`),
  ADD KEY `idx_municipalities_cases_region_id` (`case_region_id`);

--
-- Indexes for table `municipal_divisions`
--
ALTER TABLE `municipal_divisions`
  ADD PRIMARY KEY (`municipal_division_id`,`municipality_id`),
  ADD KEY `idx_municipal_divisions_code` (`municipal_division_code`),
  ADD KEY `idx_municipal_divisions_municipality` (`municipality_id`);

--
-- Indexes for table `regional_divisions`
--
ALTER TABLE `regional_divisions`
  ADD PRIMARY KEY (`regional_division_id`),
  ADD KEY `idx_regional_divisions_region` (`region_id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`region_id`),
  ADD UNIQUE KEY `idx_regions_region_id` (`region_id`);

--
-- Indexes for table `stations`
--
ALTER TABLE `stations`
  ADD PRIMARY KEY (`station_id`,`case_region_id`),
  ADD KEY `idx_stations_case_region_id` (`case_region_id`),
  ADD KEY `idx_stations_case_distict_id` (`case_district_id`),
  ADD KEY `idx_stations_municipality_id` (`municipality_id`),
  ADD KEY `idx_stations_station_officer_id` (`station_officer_id`),
  ADD KEY `idx_stations_municipal_division_id` (`municipal_division_id`);

--
-- Indexes for table `stations_officers`
--
ALTER TABLE `stations_officers`
  ADD PRIMARY KEY (`station_officer_id`);

--
-- Indexes for table `stations_statuses`
--
ALTER TABLE `stations_statuses`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_users_group_id` (`group_id`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`case_id`,`station_id`,`municipality_id`),
  ADD KEY `fk_votes_case_idx` (`case_id`),
  ADD KEY `fk_votes_users_idx` (`user_id`),
  ADD KEY `fk_votes_cases_regions_idx` (`case_region_id`),
  ADD KEY `fk_votes_station_idx` (`station_id`,`case_region_id`),
  ADD KEY `fk_votes_status_id` (`status_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cases`
--
ALTER TABLE `cases`
  ADD CONSTRAINT `fk_cases_case_type_id` FOREIGN KEY (`case_type_id`) REFERENCES `cases_types` (`case_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cases_status_id` FOREIGN KEY (`status_id`) REFERENCES `cases_statuses` (`status_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cases_items`
--
ALTER TABLE `cases_items`
  ADD CONSTRAINT `fk_cases_items_case_id` FOREIGN KEY (`case_id`) REFERENCES `cases` (`case_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cases_items_votes`
--
ALTER TABLE `cases_items_votes`
  ADD CONSTRAINT `fk_cases_items_votes_cae_item_id` FOREIGN KEY (`case_item_id`) REFERENCES `cases_items` (`case_item_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cases_items_votes_case_id` FOREIGN KEY (`case_id`) REFERENCES `cases` (`case_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cases_items_votes_case_region_id` FOREIGN KEY (`case_region_id`) REFERENCES `cases_regions` (`case_region_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cases_items_votes_municipality_id` FOREIGN KEY (`municipality_id`) REFERENCES `municipalities` (`municipality_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cases_items_votes_station_id` FOREIGN KEY (`station_id`) REFERENCES `stations` (`station_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cases_regions`
--
ALTER TABLE `cases_regions`
  ADD CONSTRAINT `fk_cases_regions_case_id` FOREIGN KEY (`case_id`) REFERENCES `cases` (`case_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cases_regions_region_id` FOREIGN KEY (`region_id`) REFERENCES `regions` (`region_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cases_regions_user_group_id` FOREIGN KEY (`case_region_user_group_id`) REFERENCES `cases_regions_users_groups` (`group_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cases_regions_users`
--
ALTER TABLE `cases_regions_users`
  ADD CONSTRAINT `fk_cases_regions_users_case_id` FOREIGN KEY (`case_id`) REFERENCES `cases` (`case_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cases_regions_users_cases_region_id` FOREIGN KEY (`case_region_id`) REFERENCES `cases_regions` (`case_region_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cases_regions_users_group_id` FOREIGN KEY (`case_region_user_group_id`) REFERENCES `cases_regions_users_groups` (`group_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cases_regions_users_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `municipalities`
--
ALTER TABLE `municipalities`
  ADD CONSTRAINT `fk_municipalities_case_region_id` FOREIGN KEY (`case_region_id`) REFERENCES `cases_regions` (`case_region_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `regional_divisions`
--
ALTER TABLE `regional_divisions`
  ADD CONSTRAINT `fk_regional_divisions_region_id` FOREIGN KEY (`region_id`) REFERENCES `regions` (`region_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `stations`
--
ALTER TABLE `stations`
  ADD CONSTRAINT `fk_stations_case_region_id` FOREIGN KEY (`case_region_id`) REFERENCES `cases_regions` (`case_region_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_group_id` FOREIGN KEY (`group_id`) REFERENCES `user_groups` (`group_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `fk_votes_status_id` FOREIGN KEY (`status_id`) REFERENCES `stations_statuses` (`status_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_votes_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

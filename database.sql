-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2024 at 12:31 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tcg_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$uTwEQY5qZMRdj6OnyPZbhejf71EXwX4DCOJjrWWC.1hsEq7GKHmcO', '2024-09-27 12:57:53');

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_filename` varchar(255) DEFAULT NULL,
  `product_details` text DEFAULT NULL,
  `rarity` varchar(50) DEFAULT NULL,
  `card_number` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `card_type` varchar(100) DEFAULT NULL,
  `cost` varchar(50) DEFAULT NULL,
  `power` varchar(50) DEFAULT NULL,
  `subtype` varchar(255) DEFAULT NULL,
  `attribute` varchar(100) DEFAULT NULL,
  `artist` varchar(100) DEFAULT NULL,
  `set_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`id`, `name`, `image_filename`, `product_details`, `rarity`, `card_number`, `color`, `card_type`, `cost`, `power`, `subtype`, `attribute`, `artist`, `set_id`, `created_at`, `updated_at`) VALUES
(1, 'Jack', '1.jpg', 'This Character gains +4 cost.\r\n[Activate: Main] You may rest this Character: Draw 1 card and trash 1 card from your hand. Then, K.O. up to 1 of your opponents Characters with a cost of 3 or less.', 'SR', 'OP08-084', 'Black', 'Character', '7', '8000', 'Animal Kingdom Pirates', 'Slash', 'Yuu Shimotsuki', 38, '2024-09-26 01:11:54', '2024-09-27 09:22:16'),
(2, 'Nami', '2.jpg', '[On Play] You may trash 1 card with a [Trigger] from your hand: K.O. up to 1 of your opponents Characters with a cost of 5 or less. Then, if you have 3 or less cards in your hand, draw 1 card.\r\n[Trigger] Activate this cards [On Play] effect.', 'SR', 'OP08-106', 'Yellow', 'Character', '5', '5000', 'Straw Hat Crew Egghead', 'Special', 'Sunohara', 38, '2024-09-26 01:11:54', '2024-09-27 09:24:55'),
(3, 'Black Maria', '3.jpg', '[Activate:Main] [Once Per Turn] If you have no other [Black Maria] Characters, add up to 5 DON!! cards from your DON!! deck and rest them. Then, at the end of this turn, return DON!! cards from your field to your DON!! deck until you have the same number of DON!! cards on your field as your opponent.', 'SR', 'OP08-074', 'Purple', 'Character', '3', '2000', 'Animal Kingdom Pirates', 'Special', 'BISAI', 38, '2024-09-26 01:11:54', '2024-09-27 09:29:44'),
(4, 'Carrot', '4.jpg', '[On Play]/[When Attacking] Up to 1 of your opponents rested Characters with a cost of 7 or less will not become active in your opponents next Refresh Phase.', 'SR', 'OP08-023', 'Green', 'Character', '5', '6000', 'Minks', 'Special', 'Hashimoto', 38, '2024-09-26 01:11:54', '2024-09-27 09:29:34'),
(5, 'Charlotte Linlin', '5.jpg', 'Product Details\r\n[On Play] DON!! 1, You may trash 1 card from your hand: Add up to 1 card from the top of your deck to the top of your Life cards. Then, add up to 1 of your opponents Characters with a cost of 6 or less to the top or bottom of your opponents Life cards face-up.', 'SR', 'OP08-069', 'Purple', 'Character', '9', '9000', 'Big Mom Pirates Former Rocks Pirates', 'Special', 'Nijihayashi', 38, '2024-09-26 01:11:54', '2024-09-27 09:29:23'),
(6, 'Tony Tony.Chopper', '6.jpg', '[Your Turn] [On Play]/[When Attacking] Look at 5 cards from the top of your deck and play up to 1 [Animal] type Character card with 4000 power or less rested. Then, place the rest at the bottom of your deck in any order.', 'SR', 'OP08-007', 'Red', 'Character', '3', '5000', 'Animal Straw Hat Crew Drum Kingdom', 'Strike', 'Akihiro MIYANO', 38, '2024-09-26 01:11:54', '2024-09-27 09:29:07'),
(7, 'Jewelry Bonney', '7.jpg', '[DON!! x1] [Your Turn] [Once Per Turn] When a card is removed from your opponents Life cards, draw 2 cards and trash 1 card from your hand.\r\n[Trigger] Draw 2 cards and trash 1 card from your hand.', 'SR', 'OP08-105', 'Yellow', 'Character', '3', '4000', 'Bonney Pirates Egghead', 'Special', 'Daisuke Enoshima', 38, '2024-09-26 01:11:54', '2024-09-27 09:28:54'),
(8, 'Kaido', '8.jpg', '[Activate:Main] [Once Per Turn] You may trash 1 card from your hand: If this Character was played on this turn, trash up to 1 of your opponents Characters with a cost of 7 or less. Then, your opponent trashes 1 card from their hand.', 'SR', 'OP08-079', 'Black', 'Character', '9', '9000', 'Animal Kingdom Pirates Former Rocks Pirates', 'Strike', 'Nijihayashi', 38, '2024-09-26 01:11:54', '2024-09-27 09:28:41'),
(9, 'Whos.Who', '9.jpg', '[On Play] You may trash 1 card from your hand: K.O. up to 1 of your opponents Characters with a cost of 3 or less.\r\n[Trigger] K.O. up to 1 of your opponents Characters with a cost of 3 or less.', 'R', 'OP08-091', 'Black', 'Character', '5', '5000', 'Animal Kingdom Pirates Former CP9', 'Slash', 'Morechand', 38, '2024-09-26 01:11:54', '2024-09-27 09:28:28'),
(10, 'DON!! Card (Alternate Art)', '10.jpg', 'Your Turn +1000', 'DON!!', 'None', 'None', 'DON!!', 'None', 'None', 'None', 'None', 'None', 38, '2024-09-26 01:11:54', '2024-09-27 09:28:19'),
(11, 'S-Snake', '11.jpg', '[On Play] Up to 1 of your opponents Characters with a cost of 6 or less other than [Monkey.D.Luffy] cannot attack until the end of your opponents next turn.\r\n[Trigger] Activate this cards [On Play] effect.', 'SR', 'OP08-112', 'Yellow', 'Character', '5', '6000', 'Egghead Seraphim', 'Special', 'SHIE NANAHARA', 38, '2024-09-26 01:11:54', '2024-09-27 09:28:11'),
(12, 'Electrical Luna', '12.jpg', '[Main] All of your opponents rested Characters with a cost of 7 or less will not become active in your opponents next Refresh Phase.\r\n[Trigger] Rest up to 1 of your opponents Characters.', 'R', 'OP08-036', 'Green', 'Event', '3', 'None', 'Minks', 'None', 'None', 38, '2024-09-26 01:11:54', '2024-09-27 09:28:01'),
(13, 'The Earth Will Not Lose!', '13.jpg', 'Counter] If your Leader has the {Shandian Warrior} type, up to 1 of your Leader or Character cards gains +3000 power during this battle. Then, play up to 1 [Upper Yard] from your hand.\r\n[Trigger] Draw 2 cards and trash 1 card from your hand.', 'R', 'OP08-115', 'Yellow', 'Event', '1', 'None', 'Sky Island Shandian Warrior', 'None', 'None', 38, '2024-09-26 01:11:54', '2024-09-27 09:27:51'),
(14, 'Wyper', '14.jpg', '[On Play] Look at 5 cards from the top of your deck; reveal up to 1 [Upper Yard] and add it to your hand. Then, place the rest at the bottom of your deck in any order and play up to 1 [Upper Yard] from your hand.', 'R', 'OP08-110', 'Yellow', 'Character', '4', '5000', 'Sky Island Shandian Warrior', 'Ranged', 'Hatori Kyoka', 38, '2024-09-26 01:11:54', '2024-09-27 09:27:40'),
(15, 'Dr.Kureha', '15.jpg', '[On Play] Look at 4 cards from the top of your deck; reveal up to 1 [Tony Tony.Chopper] or [Drum Kingdom] type card other than [Dr.Kureha] and add it to your hand. Then, place the rest at the bottom of your deck in any order.', 'R', 'OP08-015', 'Red', 'Character', '1', '2000', 'Drum Kingdom', 'Wisdom', 'yuu', 38, '2024-09-26 01:11:54', '2024-09-27 09:27:28'),
(16, 'Charlotte Pudding', '16.jpg', '[Your Turn] [Once Per Turn] When a DON!! card on your field is returned to your DON!! deck, add up to 1 DON!! card from your DON!! deck and rest it.', 'R', 'OP08-067', 'Purple', 'Character', '3', '4000', 'Big Mom Pirates', 'Wisdom', 'Yuu Shimotsuki', 38, '2024-09-26 01:11:54', '2024-09-27 09:27:18'),
(17, 'Mont Blanc Noland', '17.jpg', '[On Play] If your Leader has the [Shandian Warrior] type and you have a [Kalgara] Character, add up to 1 card from the top of your deck to the top of your Life cards.', 'R', 'OP08-109', 'Yellow', 'Character', '5', '6000', 'Jaya Botanist', 'Slash', 'Moopic', 38, '2024-09-26 01:11:54', '2024-09-27 09:27:10'),
(18, 'Silvers Rayleigh (Parallel) (Manga)', '18.jpg', 'Dr. Kureha and Dr. Hiriluk from the Drum Kingdom Arc, and Carrot and Wanda from the Zou Arc appear! Plus, the Whitebeard Pirates appear as blue, Big Mom Pirates as purple, and Animal Kingdom Pirates as black, opening up a host of new strategies!', 'SEC', 'OP08-118', 'Red', 'Character', '8', '8000', 'Former Roger Pirates', 'Slash', 'Eiichiro Oda', 38, '2024-09-26 01:11:54', '2024-09-29 18:11:39'),
(19, 'Charlotte Katakuri', '19.jpg', '[On Play] You may turn 1 card from the top of your Life cards face-down: Add up to 1 DON!! card from your DON!! deck and set it as active.', 'SR', 'OP08-063', 'Purple', 'Character', '6', '7000', 'Big Mom Pirates', 'Strike', 'Koushi Rokushiro', 38, '2024-09-26 01:11:54', '2024-09-27 09:26:30'),
(20, 'Pedro', '20.jpg', '[Blocker] (After your opponent declares an attack, you may rest this card to make it the new target of the attack.)', 'R', 'OP08-030', 'Green', 'Character', '4', '5000', 'Minks', 'Slash', 'Hatori Kyoka', 38, '2024-09-26 01:11:54', '2024-09-27 09:26:18'),
(21, 'Edward.Newgate', '21.jpg', 'Product Details\r\n[On Play] If your Leaders type includes \"Whitebeard Piratess\" and you have 2 or less Life cards, select all of your opponents Characters on their field. Until the end of your opponents next turn, none of the selected Characters can attack unless your opponent trashes 2 cards from their hand whenever they attack.', 'SR', 'OP08-043', 'Blue', 'Character', '10', '12000', 'The Four Emperors Whitebeard Pirates', 'Special', 'Hayaken-sarena', 38, '2024-09-26 01:11:54', '2024-09-27 09:26:08'),
(22, 'Silvers Rayleigh', '22.jpg', '[On Play] Select up to 2 of your opponents Characters, and give 1 Character 3000 power and the other 2000 power until the end of your opponents next turn. Then, K.O. up to 1 of your opponents Characters with 3000 power or less.', 'SEC', 'OP08-118', 'Purple', 'Character', '8', '8000', 'Former Roger Pirates', 'Slash', 'AKIRA EGAWA', 38, '2024-09-26 01:11:54', '2024-09-27 09:25:53'),
(23, 'Conquest of the Sea', '23.jpg', '[Main] DON!! 2 (You may return the specified number of DON!! cards from your field to your DON!! deck.): If your Leader has the [Animal Kingdom Pirates] or [Big Mom Pirates] type, K.O. up to 2 of your opponents Characters with a cost of 6 or less.', 'R', 'OP08-077', 'Purple', 'Event', '6', 'None', 'Animal Kingdom Pirates', 'None', 'None', 38, '2024-09-26 01:11:54', '2024-09-27 09:25:38'),
(24, 'Hiking Bear', '24.jpg', '[DON!! x1] [Activate: Main] [Once Per Turn] Up to 1 of your [Animal] type Characters other than this Character gains +1000 power during this turn.', 'UC', 'OP08-010', 'Red', 'Character', '3', '3000', 'Animal Drum Kingdom', 'Wisdom', 'COGA', 38, '2024-09-26 01:11:54', '2024-09-29 19:12:01'),
(25, 'Kirby', 'kirby-png.png', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', 38, '2024-09-29 19:09:37', '2024-09-30 17:39:32');

-- --------------------------------------------------------

--
-- Table structure for table `card_listings`
--

CREATE TABLE `card_listings` (
  `id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `price` decimal(10,2) DEFAULT NULL,
  `shipping` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `card_listings`
--

INSERT INTO `card_listings` (`id`, `card_id`, `store_id`, `quantity`, `price`, `shipping`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 50.00, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:53:09'),
(2, 2, 1, 1, 17.95, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(3, 3, 1, 12, 19.95, 5.00, '2024-09-29 17:23:43', '2024-09-30 17:39:09'),
(4, 4, 1, 1, 9.95, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(5, 5, 1, 1, 12.95, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(6, 6, 1, 1, 7.95, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(7, 7, 1, 1, 6.95, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(8, 8, 1, 1, 4.95, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(9, 9, 1, 1, 2.95, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(10, 10, 1, 1, 2.95, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(11, 11, 1, 1, 4.95, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(12, 12, 1, 1, 2.95, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(13, 13, 1, 1, 2.95, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(14, 14, 1, 1, 2.95, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(15, 15, 1, 3, 2.95, 5.00, '2024-09-29 17:23:43', '2024-09-30 16:26:44'),
(16, 16, 1, 1, 2.95, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(17, 17, 1, 1, 19.95, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(18, 19, 1, 1, 2.95, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(19, 20, 1, 1, 2.95, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(20, 21, 1, 1, 3.95, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(21, 22, 1, 1, 49.95, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(22, 23, 1, 1, 2.95, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(23, 24, 1, 1, 2.95, 5.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(24, 1, 2, 1, 19.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(25, 2, 2, 1, 17.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(26, 3, 2, 2, 30.00, 8.00, '2024-09-29 17:23:43', '2024-09-30 17:40:21'),
(27, 4, 2, 1, 9.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(28, 5, 2, 1, 12.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(29, 6, 2, 1, 7.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(30, 7, 2, 1, 6.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(31, 8, 2, 1, 4.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(32, 9, 2, 1, 2.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(33, 10, 2, 1, 2.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(34, 11, 2, 1, 4.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(35, 12, 2, 1, 2.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(36, 13, 2, 1, 2.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(37, 14, 2, 1, 2.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(38, 15, 2, 1, 2.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(39, 16, 2, 1, 2.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(40, 17, 2, 1, 19.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(41, 19, 2, 1, 2.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(42, 20, 2, 1, 2.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(43, 21, 2, 1, 3.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(44, 22, 2, 1, 49.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(45, 23, 2, 1, 2.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(46, 24, 2, 1, 2.95, 8.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(47, 1, 3, 1, 19.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(48, 2, 3, 1, 17.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(49, 3, 3, 1, 20.00, 2.00, '2024-09-29 17:23:43', '2024-09-29 18:47:40'),
(50, 4, 3, 1, 9.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(51, 5, 3, 1, 12.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(52, 6, 3, 1, 7.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(53, 7, 3, 1, 6.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(54, 8, 3, 1, 4.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(55, 9, 3, 1, 2.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(56, 10, 3, 1, 2.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(57, 11, 3, 1, 4.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(58, 12, 3, 1, 2.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(59, 13, 3, 1, 2.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(60, 14, 3, 1, 2.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(61, 15, 3, 1, 2.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(62, 16, 3, 1, 2.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(63, 17, 3, 1, 19.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(64, 19, 3, 1, 2.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(65, 20, 3, 1, 2.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(66, 21, 3, 1, 3.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(67, 22, 3, 1, 49.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(68, 23, 3, 1, 2.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(69, 24, 3, 1, 2.95, 2.00, '2024-09-29 17:23:43', '2024-09-29 17:44:01'),
(70, 18, 2, 3, 122.00, 3.00, '2024-10-02 10:21:36', '2024-10-02 10:21:36');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Magic', '2024-09-26 01:11:54', '2024-09-26 01:11:54'),
(2, 'Yu-Gi-Oh!', '2024-09-26 01:11:54', '2024-09-26 01:11:54'),
(3, 'Pokémon', '2024-09-26 01:11:54', '2024-09-26 01:11:54'),
(4, 'Disney Lorcana', '2024-09-26 01:11:54', '2024-09-26 01:11:54'),
(5, 'One Piece', '2024-09-26 01:11:54', '2024-09-26 01:11:54'),
(6, 'Digimon', '2024-09-26 01:11:54', '2024-09-26 01:11:54'),
(7, 'Flesh and Blood', '2024-09-26 01:11:54', '2024-09-26 01:11:54');

-- --------------------------------------------------------

--
-- Table structure for table `sets`
--

CREATE TABLE `sets` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `release_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sets`
--

INSERT INTO `sets` (`id`, `game_id`, `name`, `release_date`, `created_at`, `updated_at`) VALUES
(1, 1, 'Mystery Booster 2', '2024-03-15', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(2, 1, 'Duskmourn: House of Horror', '2024-04-19', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(3, 1, 'Bloomburrow', '2024-05-17', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(4, 1, 'Universes Beyond: Assassin\'s Creed', '2024-06-21', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(5, 1, 'Modern Horizons 3', '2024-07-19', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(6, 1, 'Outlaws of Thunder Junction', '2024-08-16', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(7, 1, 'Universes Beyond: Fallout', '2024-09-20', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(8, 1, 'Murders at Karlov Manor', '2024-10-18', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(9, 1, 'Ravnica Remastered', '2024-11-15', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(10, 1, 'Secret Lair Drop Series', '2024-12-13', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(11, 2, 'Quarter Century Bonanza', '2024-03-22', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(12, 2, 'Rage of the Abyss', '2024-04-12', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(13, 2, '25th Anniversary Tin: Dueling Mirrors', '2024-05-03', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(14, 2, 'Retro Pack (2020 Date Reprint)', '2024-05-24', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(15, 2, 'Light of Destruction (2020 Date Reprint)', '2024-06-14', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(16, 2, 'The Infinite Forbidden', '2024-07-05', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(17, 2, 'Battles of Legend: Terminal Revenge', '2024-07-26', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(18, 2, '25th Anniversary Rarity Collection II', '2024-08-16', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(19, 2, 'Legacy of Destruction', '2024-09-06', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(20, 2, 'Speed Duel GX: Midterm Destruction', '2024-09-27', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(21, 3, 'SV07: Stellar Crown', '2024-06-14', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(22, 3, 'SV: Shrouded Fable', '2024-05-24', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(23, 3, 'SV06: Twilight Masquerade', '2024-04-05', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(24, 3, 'SV05: Temporal Forces', '2024-03-22', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(25, 3, 'SV: Paldean Fates', '2024-01-26', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(26, 3, 'SV04: Paradox Rift', '2023-11-03', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(27, 3, 'SV: Scarlet & Violet 151', '2023-09-22', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(28, 3, 'McDonald\'s Promos 2023', '2023-08-15', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(29, 3, 'SV03: Obsidian Flames', '2023-08-11', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(30, 3, 'SV02: Paldea Evolved', '2023-06-30', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(31, 4, 'Azurite Sea', '2024-08-09', '2024-10-01 11:31:15', '2024-10-01 11:31:15'),
(32, 4, 'Shimmering Skies', '2024-05-17', '2024-10-01 11:31:15', '2024-10-01 11:31:15'),
(33, 4, 'Ursula\'s Return', '2024-02-23', '2024-10-01 11:31:15', '2024-10-01 11:31:15'),
(34, 4, 'Into the Inklands', '2023-11-17', '2024-10-01 11:31:15', '2024-10-01 11:31:15'),
(35, 4, 'Rise of the Floodborn', '2023-12-01', '2024-10-01 11:31:15', '2024-10-01 11:31:15'),
(36, 4, 'The First Chapter', '2023-08-18', '2024-10-01 11:31:15', '2024-10-01 11:31:15'),
(37, 4, 'D23 Promos', '2022-09-09', '2024-10-01 11:31:15', '2024-10-01 11:31:15'),
(38, 5, 'Two Legends', '2024-02-01', '2024-10-01 11:30:42', '2024-10-01 11:30:42'),
(39, 5, 'Starter Deck 14: 3D2Y', '2024-03-01', '2024-10-01 11:31:03', '2024-10-01 11:31:03'),
(40, 5, '500 Years in the Future', '2024-04-01', '2024-10-01 11:31:03', '2024-10-01 11:31:03'),
(41, 5, 'Extra Booster: Memorial Collection', '2024-05-01', '2024-10-01 11:31:03', '2024-10-01 11:31:03'),
(42, 5, 'Ultra Deck: The Three Brothers', '2024-06-01', '2024-10-01 11:31:03', '2024-10-01 11:31:03'),
(43, 5, 'Wings of the Captain', '2024-07-01', '2024-10-01 11:31:03', '2024-10-01 11:31:03'),
(44, 5, 'Starter Deck 12: Zoro and Sanji', '2024-08-01', '2024-10-01 11:31:03', '2024-10-01 11:31:03'),
(45, 5, 'Starter Deck 11: Uta', '2024-09-01', '2024-10-01 11:31:03', '2024-10-01 11:31:03'),
(46, 5, 'Awakening of the New Era', '2024-10-01', '2024-10-01 11:31:03', '2024-10-01 11:31:03'),
(47, 5, 'Ultra Deck: The Three Captains', '2024-11-01', '2024-10-01 11:31:03', '2024-10-01 11:31:03'),
(48, 6, 'Digimon LIBERATOR', '2024-06-28', '2024-10-01 11:31:22', '2024-10-01 11:31:22'),
(49, 6, 'Secret Crisis', '2024-05-24', '2024-10-01 11:31:22', '2024-10-01 11:31:22'),
(50, 6, 'Infernal Ascension', '2024-04-26', '2024-10-01 11:31:22', '2024-10-01 11:31:22'),
(51, 6, 'Beginning Observer', '2024-03-22', '2024-10-01 11:31:22', '2024-10-01 11:31:22'),
(52, 6, 'Starter Deck 17: Double Typhoon Advanced Deck Set', '2024-02-23', '2024-10-01 11:31:22', '2024-10-01 11:31:22'),
(53, 6, 'Exceed Apocalypse', '2024-01-26', '2024-10-01 11:31:22', '2024-10-01 11:31:22'),
(54, 6, 'Animal Colosseum', '2023-12-22', '2024-10-01 11:31:22', '2024-10-01 11:31:22'),
(55, 6, 'Blast Ace', '2023-11-24', '2024-10-01 11:31:22', '2024-10-01 11:31:22'),
(56, 6, 'Starter Deck 16: Wolf of Friendship', '2023-10-27', '2024-10-01 11:31:22', '2024-10-01 11:31:22'),
(57, 6, 'Starter Deck 15: Dragon of Courage', '2023-10-27', '2024-10-01 11:31:22', '2024-10-01 11:31:22'),
(58, 7, 'Rosetta', '2024-06-21', '2024-10-01 11:31:29', '2024-10-01 11:31:29'),
(59, 7, '1st Strike', '2024-05-17', '2024-10-01 11:31:29', '2024-10-01 11:31:29'),
(60, 7, 'Armory Deck: Azalea', '2024-04-19', '2024-10-01 11:31:29', '2024-10-01 11:31:29'),
(61, 7, 'Armory Deck: Boltyn', '2024-04-19', '2024-10-01 11:31:29', '2024-10-01 11:31:29'),
(62, 7, 'Part the Mistveil', '2024-03-15', '2024-10-01 11:31:29', '2024-10-01 11:31:29'),
(63, 7, 'Armory Deck: Kayo', '2024-02-16', '2024-10-01 11:31:29', '2024-10-01 11:31:29'),
(64, 7, 'Heavy Hitters', '2024-01-19', '2024-10-01 11:31:29', '2024-10-01 11:31:29'),
(65, 7, 'Bright Lights', '2023-12-01', '2024-10-01 11:31:29', '2024-10-01 11:31:29'),
(66, 7, 'Round the Table: TCCxLSS', '2023-11-03', '2024-10-01 11:31:29', '2024-10-01 11:31:29'),
(67, 7, 'Dusk till Dawn', '2023-09-29', '2024-10-01 11:31:29', '2024-10-01 11:31:29');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `name`, `password`, `created_at`, `updated_at`) VALUES
(1, 'SYGO', '$2y$10$V/UxlneiXoUW0fZ7u3X3yOYGuCZjRoS.TfZFzmjKfd6xdfZxD/iK6', '2024-09-29 17:36:44', '2024-09-29 17:39:58'),
(2, 'Pro-PlayGames', '$2y$10$V/UxlneiXoUW0fZ7u3X3yOYGuCZjRoS.TfZFzmjKfd6xdfZxD/iK6', '2024-09-29 17:36:44', '2024-09-29 17:39:58'),
(3, 'NinjaFinds', '$2y$10$V/UxlneiXoUW0fZ7u3X3yOYGuCZjRoS.TfZFzmjKfd6xdfZxD/iK6', '2024-09-29 17:36:44', '2024-09-29 17:39:58'),
(4, 'CollectorSellers', '$2y$10$V/UxlneiXoUW0fZ7u3X3yOYGuCZjRoS.TfZFzmjKfd6xdfZxD/iK6', '2024-09-29 17:36:44', '2024-09-29 17:39:58'),
(5, 'Sullys Abode', '$2y$10$V/UxlneiXoUW0fZ7u3X3yOYGuCZjRoS.TfZFzmjKfd6xdfZxD/iK6', '2024-09-29 17:36:44', '2024-09-29 17:39:58'),
(6, 'PokeJLR', '$2y$10$V/UxlneiXoUW0fZ7u3X3yOYGuCZjRoS.TfZFzmjKfd6xdfZxD/iK6', '2024-09-29 17:36:44', '2024-09-29 17:39:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `set_id` (`set_id`);

--
-- Indexes for table `card_listings`
--
ALTER TABLE `card_listings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `card_id` (`card_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sets`
--
ALTER TABLE `sets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `card_listings`
--
ALTER TABLE `card_listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sets`
--
ALTER TABLE `sets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `cards_ibfk_1` FOREIGN KEY (`set_id`) REFERENCES `sets` (`id`);

--
-- Constraints for table `card_listings`
--
ALTER TABLE `card_listings`
  ADD CONSTRAINT `card_listings_ibfk_1` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`),
  ADD CONSTRAINT `card_listings_ibfk_3` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`);

--
-- Constraints for table `sets`
--
ALTER TABLE `sets`
  ADD CONSTRAINT `sets_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

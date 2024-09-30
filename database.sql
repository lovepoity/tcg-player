-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2024 at 08:17 PM
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
(1, 'Jack', '1.jpg', 'This Character gains +4 cost.\r\n[Activate: Main] You may rest this Character: Draw 1 card and trash 1 card from your hand. Then, K.O. up to 1 of your opponents Characters with a cost of 3 or less.', 'SR', 'OP08-084', 'Black', 'Character', '7', '8000', 'Animal Kingdom Pirates', 'Slash', 'Yuu Shimotsuki', 1, '2024-09-26 01:11:54', '2024-09-27 09:22:16'),
(2, 'Nami', '2.jpg', '[On Play] You may trash 1 card with a [Trigger] from your hand: K.O. up to 1 of your opponents Characters with a cost of 5 or less. Then, if you have 3 or less cards in your hand, draw 1 card.\r\n[Trigger] Activate this cards [On Play] effect.', 'SR', 'OP08-106', 'Yellow', 'Character', '5', '5000', 'Straw Hat Crew Egghead', 'Special', 'Sunohara', 1, '2024-09-26 01:11:54', '2024-09-27 09:24:55'),
(3, 'Black Maria', '3.jpg', '[Activate:Main] [Once Per Turn] If you have no other [Black Maria] Characters, add up to 5 DON!! cards from your DON!! deck and rest them. Then, at the end of this turn, return DON!! cards from your field to your DON!! deck until you have the same number of DON!! cards on your field as your opponent.', 'SR', 'OP08-074', 'Purple', 'Character', '3', '2000', 'Animal Kingdom Pirates', 'Special', 'BISAI', 1, '2024-09-26 01:11:54', '2024-09-27 09:29:44'),
(4, 'Carrot', '4.jpg', '[On Play]/[When Attacking] Up to 1 of your opponents rested Characters with a cost of 7 or less will not become active in your opponents next Refresh Phase.', 'SR', 'OP08-023', 'Green', 'Character', '5', '6000', 'Minks', 'Special', 'Hashimoto', 1, '2024-09-26 01:11:54', '2024-09-27 09:29:34'),
(5, 'Charlotte Linlin', '5.jpg', 'Product Details\r\n[On Play] DON!! 1, You may trash 1 card from your hand: Add up to 1 card from the top of your deck to the top of your Life cards. Then, add up to 1 of your opponents Characters with a cost of 6 or less to the top or bottom of your opponents Life cards face-up.', 'SR', 'OP08-069', 'Purple', 'Character', '9', '9000', 'Big Mom Pirates Former Rocks Pirates', 'Special', 'Nijihayashi', 1, '2024-09-26 01:11:54', '2024-09-27 09:29:23'),
(6, 'Tony Tony.Chopper', '6.jpg', '[Your Turn] [On Play]/[When Attacking] Look at 5 cards from the top of your deck and play up to 1 [Animal] type Character card with 4000 power or less rested. Then, place the rest at the bottom of your deck in any order.', 'SR', 'OP08-007', 'Red', 'Character', '3', '5000', 'Animal Straw Hat Crew Drum Kingdom', 'Strike', 'Akihiro MIYANO', 1, '2024-09-26 01:11:54', '2024-09-27 09:29:07'),
(7, 'Jewelry Bonney', '7.jpg', '[DON!! x1] [Your Turn] [Once Per Turn] When a card is removed from your opponents Life cards, draw 2 cards and trash 1 card from your hand.\r\n[Trigger] Draw 2 cards and trash 1 card from your hand.', 'SR', 'OP08-105', 'Yellow', 'Character', '3', '4000', 'Bonney Pirates Egghead', 'Special', 'Daisuke Enoshima', 1, '2024-09-26 01:11:54', '2024-09-27 09:28:54'),
(8, 'Kaido', '8.jpg', '[Activate:Main] [Once Per Turn] You may trash 1 card from your hand: If this Character was played on this turn, trash up to 1 of your opponents Characters with a cost of 7 or less. Then, your opponent trashes 1 card from their hand.', 'SR', 'OP08-079', 'Black', 'Character', '9', '9000', 'Animal Kingdom Pirates Former Rocks Pirates', 'Strike', 'Nijihayashi', 1, '2024-09-26 01:11:54', '2024-09-27 09:28:41'),
(9, 'Whos.Who', '9.jpg', '[On Play] You may trash 1 card from your hand: K.O. up to 1 of your opponents Characters with a cost of 3 or less.\r\n[Trigger] K.O. up to 1 of your opponents Characters with a cost of 3 or less.', 'R', 'OP08-091', 'Black', 'Character', '5', '5000', 'Animal Kingdom Pirates Former CP9', 'Slash', 'Morechand', 1, '2024-09-26 01:11:54', '2024-09-27 09:28:28'),
(10, 'DON!! Card (Alternate Art)', '10.jpg', 'Your Turn +1000', 'DON!!', 'None', 'None', 'DON!!', 'None', 'None', 'None', 'None', 'None', 1, '2024-09-26 01:11:54', '2024-09-27 09:28:19'),
(11, 'S-Snake', '11.jpg', '[On Play] Up to 1 of your opponents Characters with a cost of 6 or less other than [Monkey.D.Luffy] cannot attack until the end of your opponents next turn.\r\n[Trigger] Activate this cards [On Play] effect.', 'SR', 'OP08-112', 'Yellow', 'Character', '5', '6000', 'Egghead Seraphim', 'Special', 'SHIE NANAHARA', 1, '2024-09-26 01:11:54', '2024-09-27 09:28:11'),
(12, 'Electrical Luna', '12.jpg', '[Main] All of your opponents rested Characters with a cost of 7 or less will not become active in your opponents next Refresh Phase.\r\n[Trigger] Rest up to 1 of your opponents Characters.', 'R', 'OP08-036', 'Green', 'Event', '3', 'None', 'Minks', 'None', 'None', 1, '2024-09-26 01:11:54', '2024-09-27 09:28:01'),
(13, 'The Earth Will Not Lose!', '13.jpg', 'Counter] If your Leader has the {Shandian Warrior} type, up to 1 of your Leader or Character cards gains +3000 power during this battle. Then, play up to 1 [Upper Yard] from your hand.\r\n[Trigger] Draw 2 cards and trash 1 card from your hand.', 'R', 'OP08-115', 'Yellow', 'Event', '1', 'None', 'Sky Island Shandian Warrior', 'None', 'None', 1, '2024-09-26 01:11:54', '2024-09-27 09:27:51'),
(14, 'Wyper', '14.jpg', '[On Play] Look at 5 cards from the top of your deck; reveal up to 1 [Upper Yard] and add it to your hand. Then, place the rest at the bottom of your deck in any order and play up to 1 [Upper Yard] from your hand.', 'R', 'OP08-110', 'Yellow', 'Character', '4', '5000', 'Sky Island Shandian Warrior', 'Ranged', 'Hatori Kyoka', 1, '2024-09-26 01:11:54', '2024-09-27 09:27:40'),
(15, 'Dr.Kureha', '15.jpg', '[On Play] Look at 4 cards from the top of your deck; reveal up to 1 [Tony Tony.Chopper] or [Drum Kingdom] type card other than [Dr.Kureha] and add it to your hand. Then, place the rest at the bottom of your deck in any order.', 'R', 'OP08-015', 'Red', 'Character', '1', '2000', 'Drum Kingdom', 'Wisdom', 'yuu', 1, '2024-09-26 01:11:54', '2024-09-27 09:27:28'),
(16, 'Charlotte Pudding', '16.jpg', '[Your Turn] [Once Per Turn] When a DON!! card on your field is returned to your DON!! deck, add up to 1 DON!! card from your DON!! deck and rest it.', 'R', 'OP08-067', 'Purple', 'Character', '3', '4000', 'Big Mom Pirates', 'Wisdom', 'Yuu Shimotsuki', 1, '2024-09-26 01:11:54', '2024-09-27 09:27:18'),
(17, 'Mont Blanc Noland', '17.jpg', '[On Play] If your Leader has the [Shandian Warrior] type and you have a [Kalgara] Character, add up to 1 card from the top of your deck to the top of your Life cards.', 'R', 'OP08-109', 'Yellow', 'Character', '5', '6000', 'Jaya Botanist', 'Slash', 'Moopic', 1, '2024-09-26 01:11:54', '2024-09-27 09:27:10'),
(18, 'Silvers Rayleigh (Parallel) (Manga)', '18.jpg', 'Dr. Kureha and Dr. Hiriluk from the Drum Kingdom Arc, and Carrot and Wanda from the Zou Arc appear! Plus, the Whitebeard Pirates appear as blue, Big Mom Pirates as purple, and Animal Kingdom Pirates as black, opening up a host of new strategies!', 'SEC', 'OP08-118', 'Red', 'Character', '8', '8000', 'Former Roger Pirates', 'Slash', 'Eiichiro Oda', 1, '2024-09-26 01:11:54', '2024-09-29 18:11:39'),
(19, 'Charlotte Katakuri', '19.jpg', '[On Play] You may turn 1 card from the top of your Life cards face-down: Add up to 1 DON!! card from your DON!! deck and set it as active.', 'SR', 'OP08-063', 'Purple', 'Character', '6', '7000', 'Big Mom Pirates', 'Strike', 'Koushi Rokushiro', 1, '2024-09-26 01:11:54', '2024-09-27 09:26:30'),
(20, 'Pedro', '20.jpg', '[Blocker] (After your opponent declares an attack, you may rest this card to make it the new target of the attack.)', 'R', 'OP08-030', 'Green', 'Character', '4', '5000', 'Minks', 'Slash', 'Hatori Kyoka', 1, '2024-09-26 01:11:54', '2024-09-27 09:26:18'),
(21, 'Edward.Newgate', '21.jpg', 'Product Details\r\n[On Play] If your Leaders type includes \"Whitebeard Piratess\" and you have 2 or less Life cards, select all of your opponents Characters on their field. Until the end of your opponents next turn, none of the selected Characters can attack unless your opponent trashes 2 cards from their hand whenever they attack.', 'SR', 'OP08-043', 'Blue', 'Character', '10', '12000', 'The Four Emperors Whitebeard Pirates', 'Special', 'Hayaken-sarena', 1, '2024-09-26 01:11:54', '2024-09-27 09:26:08'),
(22, 'Silvers Rayleigh', '22.jpg', '[On Play] Select up to 2 of your opponents Characters, and give 1 Character 3000 power and the other 2000 power until the end of your opponents next turn. Then, K.O. up to 1 of your opponents Characters with 3000 power or less.', 'SEC', 'OP08-118', 'Purple', 'Character', '8', '8000', 'Former Roger Pirates', 'Slash', 'AKIRA EGAWA', 1, '2024-09-26 01:11:54', '2024-09-27 09:25:53'),
(23, 'Conquest of the Sea', '23.jpg', '[Main] DON!! 2 (You may return the specified number of DON!! cards from your field to your DON!! deck.): If your Leader has the [Animal Kingdom Pirates] or [Big Mom Pirates] type, K.O. up to 2 of your opponents Characters with a cost of 6 or less.', 'R', 'OP08-077', 'Purple', 'Event', '6', 'None', 'Animal Kingdom Pirates', 'None', 'None', 1, '2024-09-26 01:11:54', '2024-09-27 09:25:38'),
(24, 'Hiking Bear', '24.jpg', '[DON!! x1] [Activate: Main] [Once Per Turn] Up to 1 of your [Animal] type Characters other than this Character gains +1000 power during this turn.', 'UC', 'OP08-010', 'Red', 'Character', '3', '3000', 'Animal Drum Kingdom', 'Wisdom', 'COGA', 1, '2024-09-26 01:11:54', '2024-09-27 09:25:17');

-- --------------------------------------------------------

--
-- Table structure for table `card_listings`
--

CREATE TABLE `card_listings` (
  `id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  `listing_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `store_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `card_listings`
--

INSERT INTO `card_listings` (`id`, `card_id`, `listing_id`, `quantity`, `price`, `created_at`, `updated_at`, `store_id`) VALUES
(217, 1, 1, 1, 50.00, '2024-09-29 17:23:43', '2024-09-29 17:53:09', 1),
(218, 2, 1, 1, 17.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(219, 3, 1, 1, 19.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(220, 4, 1, 1, 9.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(221, 5, 1, 1, 12.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(222, 6, 1, 1, 7.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(223, 7, 1, 1, 6.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(224, 8, 1, 1, 4.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(225, 9, 1, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(226, 10, 1, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(227, 11, 1, 1, 4.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(228, 12, 1, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(229, 13, 1, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(230, 14, 1, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(231, 15, 1, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(232, 16, 1, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(233, 17, 1, 1, 19.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(234, 19, 1, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(235, 20, 1, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(236, 21, 1, 1, 3.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(237, 22, 1, 1, 49.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(238, 23, 1, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(239, 24, 1, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 1),
(240, 1, 2, 1, 19.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(241, 2, 2, 1, 17.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(242, 3, 2, 1, 19.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(243, 4, 2, 1, 9.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(244, 5, 2, 1, 12.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(245, 6, 2, 1, 7.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(246, 7, 2, 1, 6.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(247, 8, 2, 1, 4.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(248, 9, 2, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(249, 10, 2, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(250, 11, 2, 1, 4.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(251, 12, 2, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(252, 13, 2, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(253, 14, 2, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(254, 15, 2, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(255, 16, 2, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(256, 17, 2, 1, 19.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(257, 19, 2, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(258, 20, 2, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(259, 21, 2, 1, 3.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(260, 22, 2, 1, 49.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(261, 23, 2, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(262, 24, 2, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 2),
(263, 1, 3, 1, 19.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(264, 2, 3, 1, 17.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(265, 3, 3, 1, 19.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(266, 4, 3, 1, 9.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(267, 5, 3, 1, 12.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(268, 6, 3, 1, 7.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(269, 7, 3, 1, 6.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(270, 8, 3, 1, 4.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(271, 9, 3, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(272, 10, 3, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(273, 11, 3, 1, 4.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(274, 12, 3, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(275, 13, 3, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(276, 14, 3, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(277, 15, 3, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(278, 16, 3, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(279, 17, 3, 1, 19.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(280, 19, 3, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(281, 20, 3, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(282, 21, 3, 1, 3.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(283, 22, 3, 1, 49.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(284, 23, 3, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3),
(285, 24, 3, 1, 2.95, '2024-09-29 17:23:43', '2024-09-29 17:44:01', 3);

-- --------------------------------------------------------

--
-- Table structure for table `listings`
--

CREATE TABLE `listings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `listings`
--

INSERT INTO `listings` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'SYGO', '2024-09-28 04:00:00', '2024-09-28 04:00:00'),
(2, 'Pro-PlayGames', '2024-09-28 04:01:00', '2024-09-28 04:01:00'),
(3, 'NinjaFinds', '2024-09-28 04:02:00', '2024-09-28 04:02:00');

-- --------------------------------------------------------

--
-- Table structure for table `sets`
--

CREATE TABLE `sets` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `release_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sets`
--

INSERT INTO `sets` (`id`, `name`, `release_date`) VALUES
(1, 'Two Legends', '2024-02-01');

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
(3, 'NinjaFinds', '$2y$10$V/UxlneiXoUW0fZ7u3X3yOYGuCZjRoS.TfZFzmjKfd6xdfZxD/iK6', '2024-09-29 17:36:44', '2024-09-29 17:39:58');

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
  ADD KEY `fk_set` (`set_id`);

--
-- Indexes for table `card_listings`
--
ALTER TABLE `card_listings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `card_listing_unique` (`card_id`,`listing_id`),
  ADD KEY `fk_card_listings_listing` (`listing_id`),
  ADD KEY `fk_store` (`store_id`);

--
-- Indexes for table `listings`
--
ALTER TABLE `listings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sets`
--
ALTER TABLE `sets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `card_listings`
--
ALTER TABLE `card_listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=289;

--
-- AUTO_INCREMENT for table `listings`
--
ALTER TABLE `listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sets`
--
ALTER TABLE `sets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `fk_set` FOREIGN KEY (`set_id`) REFERENCES `sets` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `card_listings`
--
ALTER TABLE `card_listings`
  ADD CONSTRAINT `fk_card_listings_card` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_card_listings_listing` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_store` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

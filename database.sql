-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2024 at 02:54 PM
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
  `price` decimal(10,2) DEFAULT NULL,
  `set_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`id`, `name`, `image_filename`, `product_details`, `rarity`, `card_number`, `color`, `card_type`, `cost`, `power`, `subtype`, `attribute`, `artist`, `price`, `set_id`, `created_at`, `updated_at`) VALUES
(1, 'Jack', '1.jpg', 'This Character gains +4 cost.\r\n[Activate: Main] You may rest this Character: Draw 1 card and trash 1 card from your hand. Then, K.O. up to 1 of your opponents Characters with a cost of 3 or less.', 'SR', 'OP08-084', 'Black', 'Character', '7', '8000', 'Animal Kingdom Pirates', 'Slash', 'Yuu Shimotsuki', 19.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(2, 'Nami', '2.jpg', '[On Play] You may trash 1 card with a [Trigger] from your hand: K.O. up to 1 of your opponents Characters with a cost of 5 or less. Then, if you have 3 or less cards in your hand, draw 1 card.\r\n[Trigger] Activate this cards [On Play] effect.', 'SR', 'OP08-106', 'Yellow', 'Character', '5', '5000', 'Straw Hat Crew Egghead', 'Special', 'Sunohara', 17.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(3, 'Black Maria', '3.jpg', '[Activate:Main] [Once Per Turn] If you have no other [Black Maria] Characters, add up to 5 DON!! cards from your DON!! deck and rest them. Then, at the end of this turn, return DON!! cards from your field to your DON!! deck until you have the same number of DON!! cards on your field as your opponent.', 'SR', 'OP08-074', 'Purple', 'Character', '3', '2000', 'Animal Kingdom Pirates', 'Special', 'BISAI', 19.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(4, 'Carrot', '4.jpg', '[On Play]/[When Attacking] Up to 1 of your opponents rested Characters with a cost of 7 or less will not become active in your opponents next Refresh Phase.', 'SR', 'OP08-023', 'Green', 'Character', '5', '6000', 'Minks', 'Special', 'Hashimoto', 9.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(5, 'Charlotte Linlin', '5.jpg', 'Product Details\r\n[On Play] DON!! 1, You may trash 1 card from your hand: Add up to 1 card from the top of your deck to the top of your Life cards. Then, add up to 1 of your opponents Characters with a cost of 6 or less to the top or bottom of your opponents Life cards face-up.', 'SR', 'OP08-069', 'Purple', 'Character', '9', '9000', 'Big Mom Pirates Former Rocks Pirates', 'Special', 'Nijihayashi', 12.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(6, 'Tony Tony.Chopper', '6.jpg', '[Your Turn] [On Play]/[When Attacking] Look at 5 cards from the top of your deck and play up to 1 [Animal] type Character card with 4000 power or less rested. Then, place the rest at the bottom of your deck in any order.', 'SR', 'OP08-007', 'Red', 'Character', '3', '5000', 'Animal Straw Hat Crew Drum Kingdom', 'Strike', 'Akihiro MIYANO', 7.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(7, 'Jewelry Bonney', '7.jpg', '[DON!! x1] [Your Turn] [Once Per Turn] When a card is removed from your opponents Life cards, draw 2 cards and trash 1 card from your hand.\r\n[Trigger] Draw 2 cards and trash 1 card from your hand.', 'SR', 'OP08-105', 'Yellow', 'Character', '3', '4000', 'Bonney Pirates Egghead', 'Special', 'Daisuke Enoshima', 6.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(8, 'Kaido', '8.jpg', '[Activate:Main] [Once Per Turn] You may trash 1 card from your hand: If this Character was played on this turn, trash up to 1 of your opponents Characters with a cost of 7 or less. Then, your opponent trashes 1 card from their hand.', 'SR', 'OP08-079', 'Black', 'Character', '9', '9000', 'Animal Kingdom Pirates Former Rocks Pirates', 'Strike', 'Nijihayashi', 4.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(9, 'Whos.Who', '9.jpg', '[On Play] You may trash 1 card from your hand: K.O. up to 1 of your opponents Characters with a cost of 3 or less.\r\n[Trigger] K.O. up to 1 of your opponents Characters with a cost of 3 or less.', 'R', 'OP08-091', 'Black', 'Character', '5', '5000', 'Animal Kingdom Pirates Former CP9', 'Slash', 'Morechand', 2.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(10, 'DON!! Card (Alternate Art)', '10.jpg', 'Your Turn +1000', 'DON!!', 'None', 'None', 'DON!!', 'None', 'None', 'None', 'None', 'None', 2.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(11, 'S-Snake', '11.jpg', '[On Play] Up to 1 of your opponents Characters with a cost of 6 or less other than [Monkey.D.Luffy] cannot attack until the end of your opponents next turn.\r\n[Trigger] Activate this cards [On Play] effect.', 'SR', 'OP08-112', 'Yellow', 'Character', '5', '6000', 'Egghead Seraphim', 'Special', 'SHIE NANAHARA', 4.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(12, 'Electrical Luna', '12.jpg', '[Main] All of your opponents rested Characters with a cost of 7 or less will not become active in your opponents next Refresh Phase.\r\n[Trigger] Rest up to 1 of your opponents Characters.', 'R', 'OP08-036', 'Green', 'Event', '3', 'None', 'Minks', 'None', 'None', 2.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(13, 'The Earth Will Not Lose!', '13.jpg', 'Counter] If your Leader has the {Shandian Warrior} type, up to 1 of your Leader or Character cards gains +3000 power during this battle. Then, play up to 1 [Upper Yard] from your hand.\r\n[Trigger] Draw 2 cards and trash 1 card from your hand.', 'R', 'OP08-115', 'Yellow', 'Event', '1', 'None', 'Sky Island Shandian Warrior', 'None', 'None', 2.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(14, 'Wyper', '14.jpg', '[On Play] Look at 5 cards from the top of your deck; reveal up to 1 [Upper Yard] and add it to your hand. Then, place the rest at the bottom of your deck in any order and play up to 1 [Upper Yard] from your hand.', 'R', 'OP08-110', 'Yellow', 'Character', '4', '5000', 'Sky Island Shandian Warrior', 'Ranged', 'Hatori Kyoka', 2.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(15, 'Dr.Kureha', '15.jpg', '[On Play] Look at 4 cards from the top of your deck; reveal up to 1 [Tony Tony.Chopper] or [Drum Kingdom] type card other than [Dr.Kureha] and add it to your hand. Then, place the rest at the bottom of your deck in any order.', 'R', 'OP08-015', 'Red', 'Character', '1', '2000', 'Drum Kingdom', 'Wisdom', 'yuu', 2.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(16, 'Charlotte Pudding', '16.jpg', '[Your Turn] [Once Per Turn] When a DON!! card on your field is returned to your DON!! deck, add up to 1 DON!! card from your DON!! deck and rest it.', 'R', 'OP08-067', 'Purple', 'Character', '3', '4000', 'Big Mom Pirates', 'Wisdom', 'Yuu Shimotsuki', 2.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(17, 'Mont Blanc Noland', '17.jpg', '[On Play] If your Leader has the [Shandian Warrior] type and you have a [Kalgara] Character, add up to 1 card from the top of your deck to the top of your Life cards.', 'R', 'OP08-109', 'Yellow', 'Character', '5', '6000', 'Jaya Botanist', 'Slash', 'Moopic', 19.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(18, 'Two Legends', '18.jpg', 'Dr. Kureha and Dr. Hiriluk from the Drum Kingdom Arc, and Carrot and Wanda from the Zou Arc appear! Plus, the Whitebeard Pirates appear as blue, Big Mom Pirates as purple, and Animal Kingdom Pirates as black, opening up a host of new strategies!', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 10.00, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(19, 'Charlotte Katakuri', '19.jpg', '[On Play] You may turn 1 card from the top of your Life cards face-down: Add up to 1 DON!! card from your DON!! deck and set it as active.', 'SR', 'OP08-063', 'Purple', 'Character', '6', '7000', 'Big Mom Pirates', 'Strike', 'Koushi Rokushiro', 2.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(20, 'Pedro', '20.jpg', '[Blocker] (After your opponent declares an attack, you may rest this card to make it the new target of the attack.)', 'R', 'OP08-030', 'Green', 'Character', '4', '5000', 'Minks', 'Slash', 'Hatori Kyoka', 2.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(21, 'Edward.Newgate', '21.jpg', 'Product Details\r\n[On Play] If your Leaders type includes \"Whitebeard Piratess\" and you have 2 or less Life cards, select all of your opponents Characters on their field. Until the end of your opponents next turn, none of the selected Characters can attack unless your opponent trashes 2 cards from their hand whenever they attack.', 'SR', 'OP08-043', 'Blue', 'Character', '10', '12000', 'The Four Emperors Whitebeard Pirates', 'Special', 'Hayaken-sarena', 3.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(22, 'Silvers Rayleigh', '22.jpg', '[On Play] Select up to 2 of your opponents Characters, and give 1 Character 3000 power and the other 2000 power until the end of your opponents next turn. Then, K.O. up to 1 of your opponents Characters with 3000 power or less.', 'SEC', 'OP08-118', 'Purple', 'Character', '8', '8000', 'Former Roger Pirates', 'Slash', 'AKIRA EGAWA', 49.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(23, 'Conquest of the Sea', '23.jpg', '[Main] DON!! 2 (You may return the specified number of DON!! cards from your field to your DON!! deck.): If your Leader has the [Animal Kingdom Pirates] or [Big Mom Pirates] type, K.O. up to 2 of your opponents Characters with a cost of 6 or less.', 'R', 'OP08-077', 'Purple', 'Event', '6', 'None', 'Animal Kingdom Pirates', 'None', 'None', 2.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54'),
(24, 'Hiking Bear', '24.jpg', '[DON!! x1] [Activate: Main] [Once Per Turn] Up to 1 of your [Animal] type Characters other than this Character gains +1000 power during this turn.', 'UC', 'OP08-010', 'Red', 'Character', '3', '3000', 'Animal Drum Kingdom', 'Wisdom', 'COGA', 2.95, 1, '2024-09-26 15:11:54', '2024-09-26 15:11:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

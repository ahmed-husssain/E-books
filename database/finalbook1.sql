-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2025 at 12:07 AM
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
-- Database: `finalbook1`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `BookID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Author` varchar(100) DEFAULT NULL,
  `Category` int(11) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT 0.00,
  `PDFPath` varchar(255) DEFAULT NULL,
  `HasHardCopy` tinyint(1) DEFAULT 1,
  `HasCD` tinyint(1) DEFAULT 0,
  `Stock` int(11) DEFAULT 0,
  `CreatedAt` datetime DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`BookID`, `Title`, `Author`, `Category`, `Price`, `PDFPath`, `HasHardCopy`, `HasCD`, `Stock`, `CreatedAt`, `image`) VALUES
(1, 'Twenty Thousand Leagues Under the Sea', 'Jules Verne', 10, 23.00, '2. Twenty Thousand Leagues Under the Seas Author Jules Verne (1).pdf', 1, 0, 58, '2025-07-16 22:17:35', 'Twenty Thousand Leagues Under the Seas Author Jules Verne.avif'),
(2, 'The Time Machine', 'H.G Wells', 10, 2.00, '1. The Time Machine Author H. G. Wells (1).pdf', 1, 1, 108, '2025-07-16 22:19:21', 'the time machine AuthorH.G Wells.jpg'),
(3, 'The Lost World', 'Arthur Conan Doyle', 10, 99.00, '3. The Lost World Author Arthur Conan Doyle (1).pdf', 0, 0, 0, '2025-07-16 22:20:54', 'The Lost World Author Arthur Conan Doyle.jpg'),
(4, 'The Blazing World', 'Margaret Cavendish', 10, 0.00, '7. The Blazing World Author Margaret Cavendish (1).pdf', 0, 0, 0, '2025-07-16 22:21:25', 'The Blazing World Author Margaret Cavendish.jpg'),
(5, 'Frankenstein', 'Mary Shelley', 10, 0.00, '10820-frankenstein-mary-shelley.pdf', 0, 0, 0, '2025-07-16 22:22:04', 'frankenstein  Author mary shelley.jpg'),
(6, 'Self-Help', 'Samuel Smiles', 6, 22.00, '1. Self-Help Author Samuel Smiles (1).pdf', 1, 1, 12, '2025-07-16 22:24:24', 'Self-Help Author Samuel Smiles.jpg'),
(7, 'The Complete Guide to Self-Care', 'A. Battista', 6, 55.00, '2. The Complete Guide to Self-Care Author A. Battista.pdf', 0, 1, 45, '2025-07-16 22:25:23', 'The Complete Guide to Self-Care Author A. Battista.jfif'),
(8, 'Self-Care Planning Guide For Individuals', 'Healing Trust', 6, 0.00, '3. Self-Care Planning Guide For Individuals Author Healing Trust.pdf', 0, 0, 0, '2025-07-16 22:27:58', 'the selfcare planner.jpg'),
(9, 'Self-Care Guide', 'Mind Peace', 6, 33.00, '4. Self-Care Guide Author Mind Peace (1).pdf', 0, 1, 111, '2025-07-16 22:32:37', 'Self-CareGuideAuthorMindPeace-ezgif.com-webp-to-jpg-converter.jpg'),
(10, 'My Self-Care Plan', 'NHS', 6, 0.00, '6. My self-care plan Author NHS (1).pdf', 0, 0, 0, '2025-07-16 22:52:00', 'Myself-careplan-ezgif.com-webp-to-jpg-converter.jpg'),
(11, 'The Night Land', 'William Hope Hodgson', 7, 66.00, '06. The Night Land author William Hope Hodgson (1).pdf', 1, 1, 55, '2025-07-16 22:53:29', 'The Night Land author William Hope Hodgson.jpg'),
(12, 'The Wonderful Wizard of Oz', 'L. Frank Baum', 7, 0.00, '07. The Wonderful Wizard of Oz author L. Frank Baum (1).pdf', 0, 0, 0, '2025-07-16 22:54:34', 'The Wonderful Wizard of Oz author L. Frank Baum.jpg'),
(13, 'The Adventure of Pinocchio', 'C. Collodi', 7, 120.00, '11. The Adventure of Pinocchio, C. Collodi.pdf', 0, 1, 113, '2025-07-16 22:55:13', 'The Adventure of Pinocchio, C. Collodi.jpg'),
(14, 'Snow White And Yhe Seven Dwarfs', 'Jacob Grimm And Wilhelm Grimm', 7, 99.00, '16. Snow White and the Seven Dwarfs author Jacob Grimm and Wilhelm Grimm (1).pdf', 0, 0, 0, '2025-07-16 22:57:11', 'SnowWhiteandtheSevenDwarfsauthorJacobGrimmandWilhelmGrimm-ezgif.com-webp-to-jpg-converter.jpg'),
(15, 'Alice\'s Adventures in Wonderland', 'Lewis Carroll', 7, 0.00, 'Alice\'s Adventures in Wonderland, Lewis Carroll.jpg', 0, 0, 0, '2025-07-16 22:57:41', 'Alice\'s Adventures in Wonderland, Lewis Carroll.jpg'),
(16, 'The Hound of the Baskervilles', 'Conan Doyle', 5, 0.00, 'ELEMENTARY-A-Conan-Doyle-The-Hound-of-the-Baskervilles.pdf', 0, 0, 0, '2025-07-16 22:59:01', 'The hound of the baskervilles.jpg'),
(17, 'The Book of Nature', 'Ruskin Bond', 5, 55.00, 'ruskin-bond-nature.pdf', 1, 0, 22, '2025-07-16 22:59:29', 'The Book of Nature.jpg'),
(18, 'The Mystery Of The Sea', ' 3: The mystery of the sea Author ', 5, 67.00, 'the mystery of the sea Author William Heinemann.pdf', 1, 1, 10, '2025-07-16 23:00:27', 'the mystery of the sea Author William Heinemann.jfif'),
(19, 'The Wind In The Willows', 'kenneth crossword clue', 5, 67.80, 'The wild in the wiloows.pdf', 0, 0, 0, '2025-07-16 23:06:34', 'Wind-in-the-Willows.jpg'),
(20, 'The Silent Patient', 'Alex Michaelides', 5, 56.00, 'The-Silent-Patient-first-chaper.pdf', 1, 1, 59, '2025-07-16 23:07:17', 'the silent  patient.jpg'),
(21, 'ABE-THE-SERVICE-DOG', 'T-Albert', 3, 0.00, 'ABE-THE-SERVICE-DOG   By T-Albert.pdf', 0, 0, 0, '2025-07-16 23:07:47', 'ABE-THE-SERVICE-DOG   By T-Albert.jpg'),
(22, 'CAPTAIN-FANTASTIC', 'T-Albert', 3, 55.00, 'CAPTAIN-FANTASTIC  By T-Albert.pdf', 0, 0, 0, '2025-07-16 23:08:12', 'CAPTAIN-FANTASTIC  By T-Albert.jpg'),
(23, 'DOING MY CHORES', 'T-Albert', 3, 0.00, 'DOING MY CHORES  By T-Albert.pdf', 0, 0, 0, '2025-07-16 23:08:39', 'DOING MY CHORES  By T-Albert.jfif'),
(24, 'GINGER-THE-GIRAFFE', 'T-Albert', 3, 0.00, 'GINGER-THE-GIRAFFE  By T-Albert.pdf', 0, 0, 0, '2025-07-16 23:08:59', 'GINGER-THE-GIRAFFE  By T-Albert.jpg'),
(25, 'HAMMY-THE-HAMSTER', 'T-Albert', 3, 0.00, 'HAMMY-THE-HAMSTER By T-Albert.pdf', 0, 0, 0, '2025-07-16 23:09:17', 'HAMMY-THE-HAMSTER By T-Albert.jpg'),
(26, 'HIDE-AND-SEEK', 'T-Albert', 3, 0.00, 'HIDE-AND-SEEK  By T-Albert.pdf', 0, 0, 0, '2025-07-16 23:09:47', 'HIDE-AND-SEEK  By T-Albert.jpg'),
(27, 'TOOTH FAIRY', 'T-Albert', 3, 0.00, 'TOOTH-FAIRY By T-Albert.pdf', 0, 0, 0, '2025-07-16 23:10:06', 'TOOTH FAIRY By T-Albert.jpg'),
(28, 'SUNNY-MEADOWS-WOODLAND-SCHOOL', 'T-Albert', 3, 0.00, 'SUNNY-MEADOWS-WOODLAND-SCHOOL- By T-Albert.pdf', 0, 0, 0, '2025-07-16 23:10:58', 'SUNNY-MEADOWS-WOODLAND-SCHOOL- By T-Albert.jpg'),
(29, '1984', 'george orwell', 1, 44.00, '1984 AUTHOR = george orwell.pdf', 1, 1, 22, '2025-07-16 23:13:43', '1984AUTHORgeorgeorwell-ezgif.com-webp-to-jpg-converter.jpg'),
(30, 'THE RAY', 'JD Salinger', 1, 43.00, 'THE RAY AUTHOR =JD Salinger.pdf', 1, 1, 34, '2025-07-16 23:14:25', 'THE RAY AUTHOR JD Salinger.jfif'),
(31, 'The Story of Doctor Dolittle', 'Hugh Lofting', 1, 34.00, 'The Story of Doctor Dolittle  AUTHOR  Hugh Lofting.pdf', 1, 1, 234, '2025-07-16 23:14:57', 'The Story of Doctor Dolittle  AUTHOR  Hugh Lofting.jpg'),
(32, 'The Alchemist', 'Paulo-Coelho', 2, 35.00, 'The-Alchemist AUTHOR = Paulo-Coelho.pdf', 1, 1, 342, '2025-07-16 23:15:51', 'The Alchemist AUTHOR Paulo-Coelho.jpg'),
(33, 'Bakht Novel', 'Mehrunisa', 1, 34.00, 'Bakht Novel By Mehrulnisa Shahmeer.pdf', 0, 1, 23, '2025-07-16 23:16:32', 'bakht novel by mehrunisa.png'),
(34, 'AMAEBAIL', 'UMERA AHMED', 1, 43.00, 'AMAEBAIL BY UMERA AHMED.pdf', 0, 1, 55, '2025-07-16 23:16:54', 'AMAEBAIL BY UMERA AHMED.jfif'),
(35, 'Baab E Dehar', 'Mehrulnisa Shahmeer', 1, 23.00, 'Baab E Dehar By Mehrulnisa Shahmeer (Complete update pdf).pdf', 1, 0, 21, '2025-07-16 23:17:16', 'Baab E Dehar By Mehrulnisa Shahmeer.jpg'),
(36, 'Jannat ke pattay', 'Nimra Ahmed', 1, 12.00, 'jannat ke pattay by nimra ahmed.pdf', 1, 1, 33, '2025-07-16 23:17:53', 'Jannat ke pattay By Nimra Ahmed.jfif'),
(37, 'Junoon E Ulfat', 'Mehwish Ali', 1, 12.00, 'Junoon E Ulfat By Mehwish Ali.pdf', 1, 1, 33, '2025-07-16 23:18:29', 'Junoon E Ulfat By Mehwish Ali.jpg'),
(38, 'A Different Vision', 'Peter lindbergh', 8, 54.00, 'a_different_vision_on_fashion_photography-BY-peter_lindbergh.pdf', 1, 0, 44, '2025-07-16 23:20:33', 'a_different_vision_on_fashion_photography-BY-peter_lindbergh-ezgif.com-webp-to-jpg-converter.jpg'),
(39, 'Big Magic', 'Elizabeth Gilber', 8, 23.00, 'big-magic-by Elizabeth Gilber.pdf', 1, 0, 31, '2025-07-16 23:21:05', 'big-magic-by Elizabeth Gilber.jpg'),
(40, 'On Photography', 'Susan Sontag', 8, 12.00, 'On Photography by Susan Sontag.pdf', 1, 0, 20, '2025-07-16 23:22:48', 'On Photography by Susan Sontag.jpg'),
(41, 'Sahih Al-Bukhari Vol. 1', 'Dr. Muhammad  Muhsin Khan', 9, 0.00, 'Sahih al-Bukhari Vol. 1 - 1-875.pdf', 1, 1, 1000, '2025-07-17 00:18:36', 'WhatsApp Image 2025-07-16 at 23.56.21_9df74c97.jpg'),
(42, 'Essential English', 'C.E ECKERSLEY', 2, 12.00, 'Essential-English Author C.E ECKERSLEY.pdf', 1, 1, 322, '2025-07-17 00:22:45', 'Essential-English Author C.E ECKERSLEY.jpg'),
(43, 'Educational Psychology', 'Martin L .Tombari', 2, 55.00, 'Educational Psychology Author Martin L. Tombari.pdf', 1, 0, 9, '2025-07-17 00:23:30', 'Educational Psychology Author Martin L. Tombari.jpg'),
(44, 'Pir E Kamil', 'UMERA AHMED', 1, 10.00, 'Pir e Kamil By Umera Ahmed.pdf', 0, 0, -1, '2025-07-17 00:24:49', 'Pir e Kamil By Umera Ahmed.png'),
(45, 'Stories Of The Prophets', 'Ibn Kathir', 9, 0.00, 'Stories Of The Prophets By Ibn Kathir.pdf', 0, 0, 0, '2025-07-17 00:26:49', 'Prophets.jpg'),
(46, 'The History Of Pakistan', 'Iftikhar H. Malik', 4, 55.00, 'The history of pakistan.pdf', 1, 1, 77, '2025-07-17 17:19:27', 'The history of pakistan.png');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoryID`, `CategoryName`) VALUES
(1, 'Fiction'),
(2, 'Education'),
(3, 'Children'),
(4, 'History'),
(5, 'Mystery'),
(6, 'Self-Help'),
(7, 'Fantasy'),
(8, 'Photography'),
(9, 'Islamic'),
(10, 'Sci-Fi');

-- --------------------------------------------------------

--
-- Table structure for table `competitions`
--

CREATE TABLE `competitions` (
  `CompetitionID` int(11) NOT NULL,
  `Type` varchar(20) DEFAULT NULL CHECK (`Type` in ('Story','Essay')),
  `Title` varchar(100) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `Prize` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `competitions`
--

INSERT INTO `competitions` (`CompetitionID`, `Type`, `Title`, `Description`, `StartDate`, `EndDate`, `Prize`) VALUES
(3, 'Story', 'Short Story Contest', 'Write a captivating short story under 1000 words. Theme: Courage.', '2025-07-05', '2025-07-20', 'Rs. 5,000 and publication'),
(4, 'Essay', 'Independence Day Essay', 'Essay on the importance of freedom and national pride.', '2025-08-01', '2025-08-14', 'Medal & Certificate'),
(5, 'Story', 'Fantasy Writing Challenge', 'Create an original fantasy world in a short story.', '2025-06-15', '2025-06-30', 'Rs. 3,000'),
(6, 'Essay', 'Environmental Awareness Essay', 'Write an essay about climate change and sustainable living.', '2025-06-01', '2025-06-25', 'Gift Hamper'),
(7, 'Story', 'AI Writing Contest 2024', 'A challenge where participants wrote short stories using AI tools. Winners have been announced.', '2024-03-01', '2024-03-31', 'No Prize'),
(8, 'Story', 'Summer Reading Adventure', 'Write a captivating short story inspired by summer themes and adventures.', '2025-06-15', '2025-07-30', '$1,500 USD'),
(9, 'Story', 'ABCD', 'ASd', '2025-01-30', '2025-03-30', '10Rs'),
(10, 'Story', 'Short Story Contest 2.0', 'No desc', '2025-06-30', '2026-07-30', 'Rs. 5,000 and publication');

-- --------------------------------------------------------

--
-- Table structure for table `developers`
--

CREATE TABLE `developers` (
  `name` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `developers`
--

INSERT INTO `developers` (`name`, `title`, `description`) VALUES
('Muhammad Ali', 'Team Leader', NULL),
('Syed Ahmed', 'Team Member', NULL),
('Muhammad Wahaj', 'Team Member', NULL),
('Hafiza Anzalna', 'Team Member', NULL),
('Alishba Iqbal', 'Team Member', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `FeedbackID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Subject` varchar(50) DEFAULT NULL,
  `Message` text NOT NULL,
  `SubmittedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`FeedbackID`, `Name`, `Email`, `Subject`, `Message`, `SubmittedAt`) VALUES
(1, 'Array', 'Array', 'Array', 'Array', '2025-07-05 16:04:17'),
(2, 'asd', 'asdd@gmail.com', 'order', 'asd', '2025-07-05 16:06:38'),
(3, 'asd123', 'asdd@asd.asd', 'order', 'asdasd', '2025-07-05 16:07:58'),
(4, 'ii', 'roaraxyt@gmail.com', 'order', 'asdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasd', '2025-07-05 16:08:37'),
(5, 'ii', 'roaraxyt@gmail.com', 'order', 'asdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasd', '2025-07-05 16:10:32'),
(6, 'Admin', 'asdd@asd.asd', 'order', 'asdasd', '2025-07-05 16:12:48'),
(7, 'Admin', 'asdd@asd.asd', 'order', 'asdasd', '2025-07-05 16:13:42'),
(8, 'Admin', 'asdd@asd.asd', 'order', 'asdasd', '2025-07-05 16:13:54'),
(9, 'Admin', 'asdd@asd.asd', 'order', 'asdasd', '2025-07-05 16:13:58'),
(10, 'Roarax', 'roaraxyt@gmail.com', 'order', 'ASdasdasdsad', '2025-07-18 05:57:28');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `BookID` int(11) DEFAULT NULL,
  `Format` varchar(20) DEFAULT NULL CHECK (`Format` in ('PDF','CD','HardCopy')),
  `Quantity` int(11) DEFAULT 1,
  `ShippingCharge` decimal(10,2) DEFAULT 0.00,
  `TotalAmount` decimal(10,2) DEFAULT NULL,
  `OrderDate` datetime DEFAULT current_timestamp(),
  `PaymentStatus` varchar(20) DEFAULT 'Pending' CHECK (`PaymentStatus` in ('Pending','Paid')),
  `DeliveryAddress` varchar(255) DEFAULT NULL,
  `PaymentMethod` varchar(20) NOT NULL DEFAULT 'CreditCard' CHECK (`PaymentMethod` in ('CreditCard','COD','DD','Cheque')),
  `Confirmation` tinyint(1) NOT NULL DEFAULT 0,
  `confirmation_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `UserID`, `BookID`, `Format`, `Quantity`, `ShippingCharge`, `TotalAmount`, `OrderDate`, `PaymentStatus`, `DeliveryAddress`, `PaymentMethod`, `Confirmation`, `confirmation_token`) VALUES
(12, 14, 16, 'PDF', 1, 0.00, 0.00, '2025-07-17 02:29:47', 'Paid', 'N/A (PDF)', 'CreditCard', 1, NULL),
(13, 14, 44, 'HardCopy', 1, 5.99, 10.00, '2025-07-17 02:50:27', 'Pending', 'Bufferzone, Karachi, Pakistan, Karachi, asd 21333', 'COD', 1, NULL),
(14, 14, 44, 'PDF', 1, 0.00, 10.00, '2025-07-17 03:03:23', 'Paid', NULL, 'CreditCard', 1, NULL),
(15, 14, 44, 'PDF', 1, 0.00, 10.00, '2025-07-17 03:09:41', 'Paid', NULL, 'CreditCard', 1, NULL),
(16, 14, 41, 'PDF', 1, 0.00, 0.00, '2025-07-17 03:11:01', 'Paid', 'N/A (PDF)', 'CreditCard', 1, NULL),
(17, 14, 40, 'PDF', 1, 0.00, 12.00, '2025-07-17 03:11:36', 'Paid', NULL, 'CreditCard', 1, NULL),
(18, 14, 40, 'HardCopy', 1, 5.99, 12.00, '2025-07-17 03:12:15', 'Pending', 'Bufferzone, Karachi, Pakistan, Karachi, asd 75850 ', 'COD', 1, NULL),
(19, 72, 39, 'HardCopy', 1, 5.99, 23.00, '2025-07-17 03:44:43', 'Pending', 'Bufferzone, Karachi, Pakistan, Karachi, asd 21333', 'DD', 0, 'ca67755a8415ae94cbebabfd02c2cf89'),
(20, 72, 40, 'HardCopy', 1, 5.99, 12.00, '2025-07-17 03:53:18', 'Pending', 'Bufferzone, Karachi, Pakistan, Karachi, asd 21333', 'COD', 1, NULL),
(21, 72, 44, 'PDF', 1, 0.00, 10.00, '2025-07-17 04:05:37', 'Paid', NULL, 'CreditCard', 1, NULL),
(22, 72, 43, 'HardCopy', 1, 5.99, 55.00, '2025-07-17 04:06:48', 'Paid', 'Bufferzone, Karachi, Pakistan, Karachi, asd 21333', 'CreditCard', 1, NULL),
(23, 72, 43, 'HardCopy', 1, 5.99, 55.00, '2025-07-17 04:07:26', 'Pending', 'Bufferzone, Karachi, Pakistan, Karachi, asasd 21333', 'COD', 0, 'c6c37fd9a88580febdbfc6148fa91b6a'),
(24, 72, 38, 'HardCopy', 1, 5.99, 54.00, '2025-07-17 04:15:11', 'Pending', 'Bufferzone, Karachi, Pakistan, Karachi, asd 21333', 'Cheque', 0, '938030199e2aad9e377e896ce6ca9ee5'),
(25, 72, 20, 'HardCopy', 1, 5.99, 56.00, '2025-07-17 04:19:20', 'Pending', 'Bufferzone, Karachi, Pakistan, Karachi, asd 21333', 'DD', 0, '4ac94a29dd960491ba2edc42b183a4ce'),
(26, 72, 2, 'HardCopy', 1, 5.99, 2.00, '2025-07-17 04:22:20', 'Pending', 'Bufferzone, Karachi, Pakistan, Karachi, asd 21333', 'Cheque', 1, NULL),
(27, 72, 32, 'HardCopy', 1, 5.99, 35.00, '2025-07-17 04:29:37', 'Paid', 'Bufferzone, Karachi, Pakistan, Karachi, asd 21333', 'CreditCard', 1, NULL),
(28, 72, 43, 'PDF', 1, 0.00, 55.00, '2025-07-17 04:39:02', 'Paid', NULL, 'CreditCard', 1, NULL),
(29, 72, 3, 'PDF', 1, 0.00, 99.00, '2025-07-17 04:40:51', 'Paid', NULL, 'CreditCard', 1, NULL),
(30, 72, 17, 'HardCopy', 1, 5.99, 55.00, '2025-07-17 04:41:51', 'Paid', 'Bufferzone, Karachi, Pakistan, Karachi, asd 21333', 'CreditCard', 1, NULL),
(31, 81, 27, 'PDF', 1, 0.00, 0.00, '2025-07-18 05:25:51', 'Paid', 'N/A (PDF)', 'CreditCard', 0, NULL),
(32, 82, 45, 'PDF', 1, 0.00, 0.00, '2025-07-18 05:57:54', 'Paid', 'N/A (PDF)', 'CreditCard', 0, NULL),
(33, 82, 43, 'PDF', 1, 0.00, 55.00, '2025-07-18 05:59:15', 'Paid', NULL, 'CreditCard', 1, NULL),
(34, 82, 32, 'CD', 2, 5.99, 70.00, '2025-07-18 06:01:09', 'Pending', 'Bufferzone, Karachi, Pakistan, Karachi, Bufferzone 75850', 'COD', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `SubmissionID` int(11) NOT NULL,
  `CompetitionID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Submission` text DEFAULT NULL,
  `SubmissionDate` datetime DEFAULT current_timestamp(),
  `IsWinner` tinyint(1) DEFAULT 0,
  `TimeLimitExceeded` tinyint(1) DEFAULT 0,
  `StartTime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`SubmissionID`, `CompetitionID`, `UserID`, `Submission`, `SubmissionDate`, `IsWinner`, `TimeLimitExceeded`, `StartTime`) VALUES
(5, 8, 66, '$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);\r\n$alreadySubmitted = true;\r\ninclude \'submission_email.php\';\r\nsendSubmissionEmail($email);', '2025-07-05 17:27:49', 0, 0, NULL),
(6, 3, 66, 'a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a', '2025-07-05 17:35:28', 0, 0, NULL),
(7, 3, 14, 'asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd', '2025-07-12 02:08:16', 0, 0, NULL),
(8, 10, 14, 'asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd', '2025-07-12 02:17:43', 0, 0, NULL),
(9, 8, 14, NULL, '2025-07-12 02:27:58', 0, 0, '2025-07-11 23:27:58'),
(10, 3, 14, NULL, '2025-07-12 02:29:42', 0, 0, '2025-07-11 23:29:42'),
(11, 10, 14, NULL, '2025-07-12 02:29:46', 0, 0, '2025-07-11 23:29:46'),
(12, 3, 15, NULL, '2025-07-12 02:36:55', 0, 0, '2025-07-11 23:36:55'),
(13, 10, 15, NULL, '2025-07-12 02:38:44', 0, 0, '2025-07-11 23:38:44'),
(14, 8, 15, NULL, '2025-07-12 02:40:09', 0, 0, '2025-07-11 23:40:09'),
(15, 3, 13, NULL, '2025-07-12 02:43:16', 0, 0, '2025-07-11 23:43:16'),
(16, 10, 13, NULL, '2025-07-12 02:45:27', 0, 0, '2025-07-11 23:45:27'),
(17, 8, 13, NULL, '2025-07-12 02:47:49', 0, 0, '2025-07-11 23:47:49'),
(18, 3, 41, NULL, '2025-07-12 02:48:21', 0, 0, '2025-07-11 23:48:21'),
(19, 10, 41, NULL, '2025-07-12 02:57:17', 0, 0, '2025-07-11 23:57:17'),
(20, 8, 41, NULL, '2025-07-12 02:57:42', 0, 0, '2025-07-11 23:57:42'),
(21, 3, 3, NULL, '2025-07-12 03:07:59', 0, 0, '2025-07-12 00:07:59'),
(22, 10, 3, NULL, '2025-07-12 03:08:05', 0, 0, '2025-07-12 00:08:05'),
(23, 8, 3, NULL, '2025-07-12 03:08:29', 0, 0, '2025-07-12 00:08:29'),
(24, 3, 4, NULL, '2025-07-12 03:09:21', 0, 0, '2025-07-12 00:09:21'),
(25, 10, 4, NULL, '2025-07-12 03:10:54', 0, 0, '2025-07-12 00:10:54'),
(26, 8, 4, NULL, '2025-07-12 03:12:00', 0, 0, '2025-07-12 00:12:00'),
(27, 3, 5, NULL, '2025-07-12 03:13:56', 0, 0, '2025-07-12 00:13:56'),
(28, 10, 5, NULL, '2025-07-12 03:16:28', 0, 0, '2025-07-12 00:16:28'),
(29, 3, 9, NULL, '2025-07-12 19:37:14', 0, 0, '2025-07-12 16:37:14'),
(30, 3, 68, NULL, '2025-07-13 23:00:23', 0, 0, '2025-07-13 20:00:23'),
(31, 10, 68, NULL, '2025-07-13 23:02:49', 0, 0, '2025-07-13 20:02:49'),
(32, 8, 68, NULL, '2025-07-13 23:04:09', 0, 0, '2025-07-13 20:04:09'),
(33, 3, 7, NULL, '2025-07-13 23:07:21', 0, 0, '2025-07-13 20:07:21'),
(34, 10, 7, NULL, '2025-07-13 23:08:22', 0, 0, '2025-07-13 20:08:22'),
(35, 8, 7, NULL, '2025-07-13 23:11:46', 0, 0, '2025-07-13 20:11:46'),
(36, 3, 6, NULL, '2025-07-13 23:14:10', 0, 0, '2025-07-13 20:14:10'),
(37, 3, 69, NULL, '2025-07-13 23:15:47', 0, 0, '2025-07-13 20:15:47'),
(38, 10, 69, NULL, '2025-07-13 23:18:28', 0, 0, '2025-07-13 20:18:28'),
(39, 8, 69, NULL, '2025-07-13 23:21:45', 0, 0, '2025-07-13 20:21:45'),
(40, 3, 70, NULL, '2025-07-13 23:23:43', 0, 0, '2025-07-13 20:23:43'),
(41, 10, 70, NULL, '2025-07-13 23:28:51', 0, 0, '2025-07-13 20:28:51'),
(42, 8, 70, NULL, '2025-07-13 23:32:36', 0, 0, '2025-07-13 20:32:36'),
(43, 3, 71, NULL, '2025-07-13 23:35:51', 0, 0, '2025-07-13 20:35:51'),
(44, 10, 71, NULL, '2025-07-13 23:39:12', 0, 0, '2025-07-13 20:39:12'),
(45, 8, 71, 'asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd ', '2025-07-13 23:42:05', 0, 0, '2025-07-13 20:42:05'),
(46, 8, 71, 'asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd ', '2025-07-13 23:42:05', 0, 0, '2025-07-13 20:42:05'),
(47, 3, 72, 'asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd asd ', '2025-07-13 23:43:52', 0, 0, '2025-07-13 20:43:52'),
(48, 10, 72, 'asd ad d asd ad d asd ad d asd ad d asd ad d asd ad d asd ad d asd ad d asd ad d asd ad d asd ad d asd ad d asd ad d asd ad d asd ad d asd ad d asd ad d asd ad d asd ad d asd ad d asd ad d asd ad d ', '2025-07-13 23:47:11', 0, 0, '2025-07-13 20:47:11'),
(49, 3, 81, NULL, '2025-07-18 05:34:46', 0, 0, '2025-07-18 02:34:46'),
(50, 10, 81, NULL, '2025-07-18 05:41:50', 0, 0, '2025-07-18 02:41:50'),
(51, 8, 81, NULL, '2025-07-18 05:43:48', 0, 0, '2025-07-18 02:43:48'),
(52, 3, 52, NULL, '2025-07-18 05:44:45', 0, 0, '2025-07-18 02:44:45'),
(53, 3, 82, NULL, '2025-07-18 05:55:40', 0, 0, '2025-07-18 02:55:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `FullName` varchar(100) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `State` varchar(50) DEFAULT NULL,
  `ZipCode` varchar(10) DEFAULT NULL,
  `PhoneNumber` varchar(15) DEFAULT NULL,
  `Verified` tinyint(1) DEFAULT 0,
  `DateRegistered` datetime DEFAULT current_timestamp(),
  `Role` varchar(20) NOT NULL DEFAULT 'User' CHECK (`Role` in ('User','Admin')),
  `token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `PasswordHash`, `Email`, `FullName`, `City`, `State`, `ZipCode`, `PhoneNumber`, `Verified`, `DateRegistered`, `Role`, `token`) VALUES
(3, 'alice01', '123', 'alice@example.com', NULL, 'New York', NULL, NULL, NULL, 1, '2025-06-01 09:00:00', 'User', NULL),
(4, 'bobbyB', 'asd', 'bob@example.com', NULL, 'Los Angeles', NULL, NULL, NULL, 1, '2025-06-02 10:30:00', 'User', NULL),
(5, 'carlaC', '', 'carla@example.com', NULL, 'Miami', NULL, NULL, NULL, 0, '2025-06-03 11:15:00', 'User', NULL),
(6, 'daveD', 'a', 'dave@example.com', NULL, 'Chicago', NULL, NULL, NULL, 0, '2025-06-04 12:00:00', 'User', NULL),
(7, 'eva_writer', 'a', 'eva@example.com', NULL, 'Houston', NULL, NULL, NULL, 1, '2025-06-05 13:00:00', 'User', NULL),
(8, 'frankF', '', 'frank@example.com', NULL, 'Austin', NULL, NULL, NULL, 0, '2025-06-06 14:20:00', 'User', NULL),
(9, 'gigi_gina', '', 'gina@example.com', NULL, 'Phoenix', NULL, NULL, NULL, 1, '2025-06-07 09:45:00', 'User', NULL),
(10, 'hankH', '', 'hank@example.com', NULL, 'San Diego', NULL, NULL, NULL, 1, '2025-06-08 15:45:00', 'User', NULL),
(11, 'ireneWrites', '', 'irene@example.com', NULL, 'Dallas', NULL, NULL, NULL, 1, '2025-06-09 16:30:00', 'User', NULL),
(12, 'jackJ', '', 'jack@example.com', NULL, 'Seattle', NULL, NULL, NULL, 0, '2025-06-10 17:00:00', 'User', NULL),
(13, 'Ali', 'abc123', 'Abc@gmail.com', NULL, NULL, NULL, NULL, NULL, 0, '2025-06-29 19:49:53', 'User', NULL),
(14, 'Admin', 'Admin123', 'Admin@gmail.com', 'asd asdas', 'Karachi', 'asd', '75850 ', '123123123', 1, '2025-06-29 20:02:30', 'Admin', NULL),
(15, 'I dont have a full name', 'asd123', 'roaraxyt@gmail.comasd', NULL, NULL, NULL, NULL, NULL, 0, '2025-06-29 20:48:25', 'User', NULL),
(16, 'asd', 'asd123asd', '', NULL, NULL, NULL, NULL, NULL, 0, '2025-06-29 20:48:38', 'User', NULL),
(17, 'idgaf', 'asd123', 'asd@gmail.com', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 03:35:57', 'User', NULL),
(19, 'Roarax', 'asd123', 'roaraxxd@gmail.com', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 04:08:01', 'User', 'ac4277324154526ba8dc0ce44c6df2f5'),
(22, 'ABCDEFG', 'asd123', '...', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 04:14:17', 'User', 'd3775972a504ae7b09bb8dc1584aca89'),
(41, 'asd1', 'asd123', 'roaraxshorts@gmail.com', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 04:39:47', 'User', '6b00c6bc6910ccc29f3f3663eb7d48cd'),
(42, 'asd12', 'asd123', 'roarax@gmail.com', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 04:41:46', 'User', '68f9a276e72b950afc26261640c320c8'),
(44, 'asd1123123', 'asd123', 'l', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 04:42:59', 'User', '84836b7956866ec13aba6d607add882f'),
(46, 'asd1asdasdsad', 'asd123', '@gmail.com', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 04:43:54', 'User', '174a621ffc6e9f0d48167219bb7837d7'),
(47, 'asd1asdasdsadasdasd', 'asd123', 'fg', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 04:45:16', 'User', '84f389d634e997c89382dad5460b470f'),
(50, 'asdasdadsad', 'asd123', 'roara', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 04:45:59', 'User', '27d9e51c14edf931ecdd553b8c4ccdc3'),
(51, 'ad', 'asd123', 'asd', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 04:46:36', 'User', '635ef92df0044f61777c3dcd332f7f46'),
(52, 'add', 'asd123', 'roarax123@gmail.comasd', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 04:48:42', 'User', '58abff1cafdcac12a3f6174f41121783'),
(53, 'avcd', 'asd123', 'r', NULL, NULL, NULL, NULL, NULL, 1, '2025-07-01 04:49:53', 'User', '546c4deb2af69ad732f4e4ede74da88f'),
(54, 'asdasd', 'asdasd', 'asdasdsadsada@asd.com', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 04:57:00', 'User', 'b1bccc2863263ca7b9c6d63a761322e0'),
(55, 'ABCDEFGY', 'asd123', 'roarax123@gmail.comasdasda', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 05:01:01', 'User', '7cc3fea8619888570cc811691891218c'),
(56, 'aewq', 'asd123', 'roarax123@gmail.coma', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 05:04:44', 'User', 'a378f8c6264a704a5f31a23efeab9887'),
(57, 'ghj', 'asd123', 'roarax123@gmail', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 05:07:18', 'User', '5110271b41870941076033488f8398ab'),
(58, 'asdddd', 'asd123', 'roarax123@gmail.com', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 05:10:43', 'User', '94fa298ad05d894438590667fbf9e2b7'),
(59, 'user123', 'asd123', 'user123@gmail.com', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 19:41:07', 'User', NULL),
(60, 'wahaj', 'asd123', 'wahaj.aptech34@gmail.com', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-01 19:44:46', 'User', 'e9dc8bf199e6e183159f42393241d915'),
(65, 'Roarax', '$2y$10$SfTZHg0J8bSglGA23RvR6uqK4PxTY2OgfjeJwyPIpKqYEUiZ5yC9q', 'dsadsadsa', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-05 16:28:19', 'User', '58b9ce05baac9e41429c62b98218d84b'),
(66, 'ABCDEFG', 'asdasdasd', 'roaraxyt@gmail.coma', 'asd', 'asd', 'asd', NULL, '0123456789', 1, '2025-07-05 16:31:50', 'User', NULL),
(67, 'Roarax', 'asd123', 'roaraxyt@gmail.comasddd', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-13 22:38:35', 'User', '369dd90808873c00d40575690a85f45e'),
(68, 'roarax', 'asd123', 'roaraxyt@gmail.comdddaaz', NULL, NULL, NULL, NULL, NULL, 1, '2025-07-13 22:45:46', 'User', NULL),
(69, 'Roarax', 'asd123', 'roaraxyt@gmail.comadasdas', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-13 23:15:22', 'User', '1fe1bdca121f2d392a7649c6f4fe2ed8'),
(70, 'Roarax', 'asd123', 'roaraxyt@gmail.comasdasdsadsadas', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-13 23:23:32', 'User', 'ab3c459e6980710cdb263b3acbc8e799'),
(71, 'Roarax', 'asd123', 'asdsadasdasdasdasdasdasdasd', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-13 23:35:40', 'User', 'bf1ec223c29c91944f7044ed36ee4c0f'),
(72, 'Roarax', 'asd123asd', 'roaraxyt@gmail.comm', 'asd asd', 'Karachi', 'asd', '21333', '12312313213', 0, '2025-07-13 23:43:17', 'User', '0f72dc8af5cf0eb47f1eef175d554138'),
(73, 'Roarax', 'asd123@A', 'roaraxyt@gmail.comdd', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-18 04:27:23', 'User', 'cc0c420c11a7096837b772594e2c7833'),
(74, 'Roarax', 'asd123@A', 'roaraxyt@gmail.comas', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-18 04:28:11', 'User', '78ebaa8a58cd4c83375e2bba17b04eb2'),
(75, 'Roarax', './Config/db.php', 'roaraxyt@gmail.comasz', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-18 04:31:26', 'User', '6efb8a256ae82d10a03e030a143e48b5'),
(76, 'Roarax', 'asd123@A', 'roaraxyt@gmail.comsd', 'ASD', '', '', NULL, '', 1, '2025-07-18 04:37:46', 'User', NULL),
(77, 'Roarax', 'asd123@A', 'roaraxyt@gmail.comaaz', 'ABCD', '', '', NULL, '', 0, '2025-07-18 04:53:09', 'User', 'b1aea269441816cb635726297c3b87f2'),
(78, 'Roarax', 'asd123@A', 'roaraxyt@gmail.comdsadasd', 'abcd', 'Karachi', '', NULL, '', 0, '2025-07-18 05:00:18', 'User', '5c7e2e2af667fe38ffbb98482d89498b'),
(79, 'Roarax', 'asd123', 'roaraxyt@gmail.comasdasdasdasdasdas', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-18 05:05:59', 'User', 'bb8a633e563abdecec1b38cf72a20eb0'),
(80, 'Roarax', 'asd123', 'roaraxyt@gmail.comasdasdsadsa', NULL, NULL, NULL, NULL, NULL, 1, '2025-07-18 05:07:25', 'User', NULL),
(81, 'Roarax', 'asd123@A', 'asdfadfasdf', 'Ali', 'Karachi', '', NULL, '', 1, '2025-07-18 05:11:22', 'User', NULL),
(82, 'Roarax', 'asd123', 'roaraxyt@gmail.com123', 'Muhammad Ali Jamal', 'Karachi', 'Bufferzone', '75850', '03002355460', 1, '2025-07-18 05:53:15', 'User', NULL),
(83, 'AdminUser', 'asd123', 'AdminUser@gmail.com', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-18 06:09:54', 'Admin', NULL),
(84, 'Roarax', 'asd123', 'roaraxali@Gmail.com', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-23 01:31:41', 'User', '09c08fe5d187180b70ff9c6da623ce1a'),
(85, 'Roarax', 'asd123', 'raoraxyt@gmail.com', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-23 01:44:40', 'User', '91a5b731a210acee5ed066166b7d244a'),
(86, 'Roarax', 'asd123', 'roaraxyt@gmail.com32', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-23 01:49:55', 'User', '2266cbc8e036d1071f569b290af1de7c'),
(87, 'Roarax', 'asd123', 'roaraxyt@gmail.comffff', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-23 01:50:25', 'User', '43c64790e982655f276bf300569521f2'),
(88, 'Roarax', 'asd123', 'roaraxyt@gmail.comd', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-23 01:50:56', 'User', '92b11187d2baeff1d545136749112fcc'),
(89, 'Roarax', 'asd123', 'roaraxyt@gmail.comdddd', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-23 01:52:50', 'User', '4544a40f14fc36bebae456bf9630ee66'),
(90, 'Roarax', 'asd123', 'roaraxyt@gmail.comddzxc', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-23 01:53:38', 'User', '03778a97293bbf6905d7225ea7cc3201'),
(91, 'Roarax', 'asd123', 'roaraxyt@gmail.com444', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-23 01:54:32', 'User', '0cc3498a267d145df266cd9551ec3d7c'),
(92, 'Roarax', 'asd123', 'roaraxyt@gmail.com4545', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-23 01:55:11', 'User', 'c4994628ea5ad9840dbbda60904b3ace'),
(93, 'Roarax', 'asd123', 'roaraxyt@gmail.com', NULL, NULL, NULL, NULL, NULL, 0, '2025-07-23 01:55:59', 'User', '84dff7cfe93c93bc9e3d0e5f26ff934c');

-- --------------------------------------------------------

--
-- Table structure for table `winners`
--

CREATE TABLE `winners` (
  `WinnerID` int(11) NOT NULL,
  `CompetitionID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Position` varchar(10) NOT NULL CHECK (`Position` in ('1st','2nd','3rd')),
  `AwardedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `winners`
--

INSERT INTO `winners` (`WinnerID`, `CompetitionID`, `UserID`, `Position`, `AwardedAt`) VALUES
(16, 3, 3, '1st', '2025-06-20 10:00:00'),
(17, 4, 4, '2nd', '2025-06-20 10:00:00'),
(18, 4, 5, '3rd', '2025-06-20 10:00:00'),
(19, 3, 6, '3rd', '2025-06-20 10:00:00'),
(20, 5, 8, '1st', '2025-06-21 14:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`BookID`),
  ADD KEY `Category` (`Category`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `competitions`
--
ALTER TABLE `competitions`
  ADD PRIMARY KEY (`CompetitionID`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`FeedbackID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `BookID` (`BookID`),
  ADD KEY `orders_ibfk_1` (`UserID`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`SubmissionID`),
  ADD KEY `submissions_ibfk_2` (`UserID`),
  ADD KEY `submissions_ibfk_1` (`CompetitionID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `winners`
--
ALTER TABLE `winners`
  ADD PRIMARY KEY (`WinnerID`),
  ADD KEY `CompetitionID` (`CompetitionID`),
  ADD KEY `UserID` (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `BookID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `competitions`
--
ALTER TABLE `competitions`
  MODIFY `CompetitionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `FeedbackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `SubmissionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `winners`
--
ALTER TABLE `winners`
  MODIFY `WinnerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `Category` FOREIGN KEY (`Category`) REFERENCES `categories` (`CategoryID`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`BookID`) REFERENCES `books` (`BookID`) ON DELETE CASCADE;

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`CompetitionID`) REFERENCES `competitions` (`CompetitionID`) ON DELETE CASCADE,
  ADD CONSTRAINT `submissions_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `winners`
--
ALTER TABLE `winners`
  ADD CONSTRAINT `winners_ibfk_1` FOREIGN KEY (`CompetitionID`) REFERENCES `competitions` (`CompetitionID`) ON DELETE CASCADE,
  ADD CONSTRAINT `winners_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

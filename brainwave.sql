-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2025 at 09:28 PM
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
-- Database: `brainwave`
--

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

CREATE TABLE `badges` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `badge_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `earned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `result_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `badges`
--

INSERT INTO `badges` (`id`, `user_id`, `badge_name`, `description`, `earned_at`, `result_id`) VALUES
(13, 6, 'Silver Star', 'Scored 1/2 in History', '2025-09-13 19:03:13', 3),
(14, 6, 'Bronze Star', 'Scored 1/3 in Literature', '2025-09-13 19:03:13', 4),
(15, 6, 'Gold Star', 'Scored 3/3 in Sociology', '2025-09-13 19:03:13', 5),
(22, 1, 'Silver Star', 'Scored 1/2 in Demo Test', '2025-09-13 19:06:24', 1),
(23, 1, 'Bronze Star', 'Scored 1/3 in Chemistry Demo', '2025-09-13 19:06:24', 2);

-- --------------------------------------------------------

--
-- Table structure for table `forum_comments`
--

CREATE TABLE `forum_comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_comments`
--

INSERT INTO `forum_comments` (`id`, `post_id`, `user_id`, `content`, `created_at`) VALUES
(1, 1, 3, 'Newton’s First Law states that an object stays at rest or in uniform motion unless acted upon by a force.', '2025-09-13 19:18:28'),
(3, 1, 1, 'The Second Law explains how force, mass, and acceleration are related.', '2025-09-13 19:18:28'),
(6, 2, 1, 'Always balance atoms on both sides, and check the electrons in redox reactions.', '2025-09-13 19:18:28'),
(8, 3, 3, 'You can use integration by parts for ∫ x^2 sin(x) dx.', '2025-09-13 19:18:28'),
(10, 4, 1, 'Light-dependent reactions occur in the thylakoid membrane and produce ATP and NADPH.', '2025-09-13 19:18:28'),
(11, 5, 3, 'The Treaty of Versailles and rise of totalitarian regimes were key political causes.', '2025-09-13 19:18:28');

-- --------------------------------------------------------

--
-- Table structure for table `forum_posts`
--

CREATE TABLE `forum_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `unit` enum('Unit A','Unit B','Unit C') NOT NULL,
  `subject` enum('Physics','Chemistry','Biology','Mathematics','Business Studies','Accounting','Economics','History','Literature','Sociology') NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_posts`
--

INSERT INTO `forum_posts` (`id`, `user_id`, `unit`, `subject`, `title`, `content`, `created_at`) VALUES
(1, 1, 'Unit A', 'Physics', 'Question about Newton’s Laws', 'Can someone explain the difference between Newton’s First and Second Law?', '2025-09-13 19:12:38'),
(2, 3, 'Unit B', 'Chemistry', 'Balancing chemical equations', 'I am confused about balancing redox reactions. Any tips?', '2025-09-13 19:12:38'),
(3, 1, 'Unit C', 'Mathematics', 'Integral problem', 'Can anyone help me solve ∫ x^2 sin(x) dx?', '2025-09-13 19:12:38'),
(4, 3, 'Unit A', 'Biology', 'Photosynthesis process', 'I want a simple explanation of the light-dependent reactions in photosynthesis.', '2025-09-13 19:12:38'),
(5, 1, 'Unit B', 'History', 'World War II causes', 'What were the main political causes of World War II?', '2025-09-13 19:12:38');

-- --------------------------------------------------------

--
-- Table structure for table `live_classes`
--

CREATE TABLE `live_classes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `unit` enum('Unit A','Unit B','Unit C') NOT NULL,
  `subject` enum('Physics','Chemistry','Biology','Mathematics','Business Studies','Accounting','Economics','History','Literature','Sociology') NOT NULL,
  `tutor_id` int(11) DEFAULT NULL,
  `scheduled_at` datetime NOT NULL,
  `link` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `study_materials`
--

CREATE TABLE `study_materials` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` enum('ebook','video','article') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `unit` enum('Unit A','Unit B','Unit C') NOT NULL,
  `subject` enum('Physics','Chemistry','Biology','Mathematics','Business Studies','Accounting','Economics','History','Literature','Sociology') NOT NULL,
  `difficulty` enum('easy','medium','hard') DEFAULT NULL,
  `uploaded_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `study_materials`
--

INSERT INTO `study_materials` (`id`, `title`, `type`, `file_path`, `unit`, `subject`, `difficulty`, `uploaded_by`) VALUES
(1, 'Exercise Book', 'ebook', 'Uploads/687743f93cde2.pdf', 'Unit C', 'Literature', 'easy', 2),
(2, 'Video File', 'video', 'Uploads/68774a44d52f6.mp4', 'Unit A', 'Physics', 'easy', 2),
(3, 'Video 2', 'video', 'Uploads/68775c3c638b9.mp4', 'Unit A', 'Physics', 'easy', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `unit` enum('Unit A','Unit B','Unit C') NOT NULL,
  `subject` enum('Physics','Chemistry','Biology','Mathematics','Business Studies','Accounting','Economics','History','Literature','Sociology') NOT NULL,
  `difficulty` enum('easy','medium','hard') DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`id`, `title`, `unit`, `subject`, `difficulty`, `duration`, `created_by`) VALUES
(1, 'Demo Test', 'Unit A', 'Physics', 'easy', 10, 2),
(2, 'Chemistry Demo', 'Unit A', 'Chemistry', 'easy', 4, 2),
(3, 'MCQ ', 'Unit A', 'Physics', 'medium', 5, 2),
(4, 'Biology Test', 'Unit A', 'Biology', 'easy', 20, 2),
(5, 'Business Studies', 'Unit B', 'Business Studies', 'easy', 20, 2),
(6, 'Accounting', 'Unit B', 'Accounting', 'easy', 20, 2),
(7, 'Economics', 'Unit B', 'Economics', 'easy', 20, 2),
(8, 'History', 'Unit C', 'History', 'easy', 10, 2),
(9, 'Literature', 'Unit C', 'Literature', 'easy', 4, 2),
(10, 'Sociology', 'Unit C', 'Sociology', 'easy', 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `test_questions`
--

CREATE TABLE `test_questions` (
  `id` int(11) NOT NULL,
  `test_id` int(11) DEFAULT NULL,
  `question_text` text NOT NULL,
  `option_a` varchar(255) DEFAULT NULL,
  `option_b` varchar(255) DEFAULT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL,
  `correct_answer` enum('A','B','C','D') NOT NULL,
  `explanation` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_questions`
--

INSERT INTO `test_questions` (`id`, `test_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `explanation`) VALUES
(1, 1, 'What is the S.I. unit of \\\'Force\\\'?', 'Joule', 'Newton', 'Watt', 'Pascal', 'B', 'The SI unit of force is the Newton (N), defined as the force required to accelerate a 1 kg mass by 1 m/s². Joule is the unit of energy, Watt is the unit of power, and Pascal is the unit of pressure.'),
(2, 1, 'If an object is moving at a constant velocity, what is the net force acting on it?\\r\\n', 'Equal to the object’s weight', 'Zero', 'Proportional to its speed', 'In the direction of motion', 'B', 'According to Newton’s First Law, an object moving at constant velocity experiences no net force (all forces are balanced). If the net force were non-zero, the object would accelerate.'),
(3, 2, 'Which of the following is an example of a chemical change?', 'Melting of ice ', 'Dissolving sugar in water ', 'Burning wood', 'Evaporation of alcohol ', 'C', 'Burning wood involves the rearrangement of atoms and the formation of new substances (ash, gases), which is a chemical change. Melting and dissolving are physical changes, while evaporation is a change in state. '),
(4, 2, 'The most electronegative element in the periodic table is:', 'Sodium', 'Fluorine', 'Oxygen', 'Chlorine', 'B', 'Electronegativity generally increases across a period (from left to right) and decreases down a group. Fluorine is the most electronegative element due to its small size and high effective nuclear charge. '),
(5, 2, 'Which of the following is a strong acid?', 'Acetic acid ', 'Hydrochloric acid', 'Carbonic acid', 'Sulfurous acid', 'B', ' Strong acids are those that completely dissociate into ions in water. Hydrochloric acid (HCl) is a strong acid, while acetic acid, carbonic acid, and sulfurous acid are weak acids.'),
(6, 3, ' শব্দ তরঙ্গ কোন ধরনের তরঙ্গ?\r\n ', ' অনুদৈর্ঘ্য তরঙ্গ\r\n ', 'আনুপ্রস্থ তরঙ্গ\r\n ', 'তড়িৎচুম্বকীয় তরঙ্গ\r\n ', 'আলোক তরঙ্গ\r\n\r\n ', 'A', 'উত্তর:  অনুদৈর্ঘ্য তরঙ্গ'),
(7, 3, 'একটি বস্তুর উপর যদি কোন বল প্রয়োগ না হয় তবে বস্তুটি—', 'স্থির থাকবে অথবা সমবেগে চলতে থাকবে', 'সর্বদা স্থির থাকবে\r\n ', 'সর্বদা ত্বরণ লাভ করবে', 'সর্বদা ত্বরণ লাভ করবে', 'A', 'স্থির থাকবে অথবা সমবেগে চলতে থাকবে (নিউটনের প্রথম সূত্র)।'),
(8, 3, 'যদি একটি বস্তুর ভর দ্বিগুণ করা হয় এবং বেগ অপরিবর্তিত থাকে তবে তার গতিশক্তি—\r\n ', 'অর্ধেক হবে ', 'দ্বিগুণ হবে', 'চারগুণ হবে\r\n ', 'অপরিবর্তিত থাকবে\r\n\r\n ', 'B', ' দ্বিগুণ হবে'),
(9, 3, 'কোন তরঙ্গের মধ্যে কণার দোলনের দিক ও তরঙ্গের প্রসারণের দিক একই হলে তাকে কী বলে?\r\n ', 'আনুপ্রস্থ তরঙ্গ\r\n ', 'তড়িৎচুম্বকীয় তরঙ্গ\r\n ', 'অনুদৈর্ঘ্য তরঙ্গ', 'অস্থির তরঙ্গ\r\n\r\n ', 'C', 'অনুদৈর্ঘ্য তরঙ্গ'),
(10, 4, 'মানবদেহে রক্ত পরিশোধনের কাজ করে কোন অঙ্গ?', 'হৃদপিণ্ড', 'কিডনি', 'ফুসফুস  ', 'পাকস্থলী', 'B', 'কিডনি'),
(11, 4, 'মানুষের দেহে রক্ত সঞ্চালন তত্ত্ব প্রথম কে আবিষ্কার করেন?', 'গ্রেগর মেন্ডেল', 'চার্লস ডারউইন', 'উইলিয়াম হার্ভে  ', 'লামার্ক', 'C', '১৬২৮ সালে উইলিয়াম হার্ভে প্রথম রক্ত সঞ্চালন প্রক্রিয়ার সঠিক ব্যাখ্যা দেন।'),
(12, 4, 'মানুষের চোখে রেটিনার কোন অংশে আলোক-সংবেদনশীল কোষ থাকে?', 'কর্নিয়া  ', 'লেন্স  ', 'ফোভিয়া  ', 'অপটিক নার্ভ     ', 'C', 'রেটিনার রড সেল অল্প আলোতে আর কোন সেল উজ্জ্বল আলো ও রঙ বুঝতে সাহায্য করে।'),
(13, 5, 'ব্যবসার প্রধান লক্ষ্য কী? ', 'সামাজিক সেবা প্রদান  ', 'মুনাফা অর্জন  ', 'কর্মসংস্থান সৃষ্টি  ', 'পণ্য উৎপাদন', 'B', 'ব্যবসার মূল উদ্দেশ্য হলো মুনাফা অর্জন; তবে সামাজিক দায়িত্ব পালন ও কর্মসংস্থান সৃষ্টিও গুরুত্বপূর্ণ।'),
(14, 5, 'ব্যবসার মূল উপাদানগুলির মধ্যে কোনটি অন্তর্ভুক্ত নয়?\\r\\n ', 'উৎপাদন  ', 'বিনিয়োগ  ', 'ক্রীড়া  ', 'বন্টন', 'C', 'ব্যবসার উপাদান হলো উৎপাদন, বন্টন, বিনিয়োগ ইত্যাদি; ক্রীড়া ব্যবসার মৌলিক উপাদান নয়।'),
(15, 6, 'হিসাববিজ্ঞানের মূল সমীকরণ হলো— ', 'সম্পদ = মূলধন – দায়  ', 'সম্পদ = দায় – মূলধন  ', 'সম্পদ = দায় + মূলধন  ', 'দায় = সম্পদ + মূলধন', 'C', 'হিসাববিজ্ঞানের বেসিক রুল হলো: ব্যবসার মোট সম্পদ ব্যবসার মালিকের মূলধন ও বাইরের লোকের ঋণ (দায়) দ্বারা গঠিত।'),
(16, 6, 'ব্যালেন্স শীটে দায়পত্র বলতে কী বোঝায়? ', 'ব্যবসার সম্পদ  ', 'ব্যবসার ঋণ ও দায়  ', 'ব্যবসার আয়  ', 'ব্যবসার খরচ', 'B', 'দায়পত্র (Liabilities) হলো ব্যবসার বাইরের লোকদের কাছে ঋণ বা পাওনা, যেমন— ব্যাংক ঋণ, সরবরাহকারীর দেনা ইত্যাদি।'),
(17, 7, 'অর্থনীতির জনক (Father of Economics) কে? ', ' কার্ল মার্ক্স  ', 'অ্যাডাম স্মিথ  ', 'ডেভিড রিকার্ডো  ', ' আলফ্রেড মার্শাল', 'B', 'অ্যাডাম স্মিথ ১৭৭৬ সালে “The Wealth of Nations” গ্রন্থ প্রকাশ করেন, তাই তাঁকে আধুনিক অর্থনীতির জনক বলা হয়।'),
(18, 7, 'অর্থনীতিতে Opportunity Cost বলতে কী বোঝায়?   ', ' সেরা বিকল্পের মূল্য  ', 'উৎপাদন ব্যয়  ', 'করের হার  ', 'মজুরি', 'A', 'Opportunity Cost হলো সেই সেরা বিকল্প যা ত্যাগ করতে হয় অন্য কোনো বিকল্প বেছে নিলে।'),
(19, 8, 'মোহেনজোদাড়ো সভ্যতা কোন নদীর তীরে গড়ে উঠেছিল? ', 'নীল নদ  ', ' সিন্ধু নদ  ', 'টাইগ্রিস নদী  ', 'গঙ্গা নদী', 'B', 'মোহেনজোদাড়ো ছিল প্রাচীন সিন্ধু সভ্যতার অন্যতম নগরী।'),
(20, 8, '\\\"Divide and Rule\\\" নীতি কোন ঔপনিবেশিক শক্তি প্রবর্তন করেছিল?  ', 'ডাচ ', 'ফরাসি  ', ' ব্রিটিশ  ', 'পর্তুগিজ', 'C', 'ভারতীয় সমাজে বিভাজন সৃষ্টি করে শাসন সহজ করতে ব্রিটিশরা এই নীতি গ্রহণ করে।'),
(21, 9, 'বাংলা সাহিত্যের প্রথম উপন্যাস কোনটি? ', ' আনন্দমঠ  ', 'আলালের ঘরের দুলাল  ', 'দেবদাস', 'দুর্গেশনন্দিনী', 'B', 'প্যারীচাঁদ মিত্র রচিত আলালের ঘরের দুলাল (১৮৫৭) বাংলা সাহিত্যের প্রথম উপন্যাস।'),
(22, 9, '“তুমি রবীন্দ্রনাথ ঠাকুর নও, আমি কাজী নজরুল ইসলাম” — উক্তিটি কার?\\r\\n ', 'সেলিম আল দীন  ', ' সেলিনা হোসেন', 'সেলিম আলি', 'সেলিম আল দীন', 'A', 'নাট্যকার সেলিম আল দীন বাংলা নাট্যসাহিত্যে নতুন ধারা সৃষ্টি করেছিলেন এবং সাহিত্যের বিদ্রোহী মনোভাবের সাথে কাজী নজরুলকে তুলনা করেছিলেন।'),
(23, 10, '“সমাজবিজ্ঞানের জনক” হিসেবে কাকে অভিহিত করা হয়? ', 'কার্ল মার্ক্স  ', 'অগাস্ট কমটে', 'ম্যাক্স ওয়েবার ', 'এমিল দুর্খাইম', 'B', 'অগাস্ট কমটে সমাজবিজ্ঞানের প্রতিষ্ঠাতা হিসেবে পরিচিত।'),
(24, 10, 'কার্ল মার্ক্সের মতে সমাজে প্রধান দ্বন্দ্ব সৃষ্টি হয়— ', 'ধর্মীয় পার্থক্য থেকে  ', 'ভাষাগত পার্থক্য থেকে ', 'শ্রেণি বিভাজন থেকে', 'রাজনৈতিক মতভেদ থেকে', 'C', 'কার্ল মার্ক্স বলেন, বুর্জোয়া (পুঁজিপতি) ও প্রলেতারিয়েত (শ্রমিক শ্রেণি)-এর মধ্যে দ্বন্দ্ব সমাজ পরিবর্তনের মূল চালিকা শক্তি।'),
(25, 10, 'কার্ল মার্ক্সের মতে সমাজে প্রধান দ্বন্দ্ব সৃষ্টি হয়— ', 'ধর্মীয় পার্থক্য থেকে  ', 'ভাষাগত পার্থক্য থেকে ', 'শ্রেণি বিভাজন থেকে', 'রাজনৈতিক মতভেদ থেকে', 'C', 'কার্ল মার্ক্স বলেন, বুর্জোয়া (পুঁজিপতি) ও প্রলেতারিয়েত (শ্রমিক শ্রেণি)-এর মধ্যে দ্বন্দ্ব সমাজ পরিবর্তনের মূল চালিকা শক্তি।'),
(26, 9, '“তুমি রবীন্দ্রনাথ ঠাকুর নও, আমি কাজী নজরুল ইসলাম” — উক্তিটি কার? ', 'সেলিম আল দীন  ', ' সেলিনা হোসেন', 'সেলিম আলি', 'সেলিম আল দীন', 'A', 'নাট্যকার সেলিম আল দীন বাংলা নাট্যসাহিত্যে নতুন ধারা সৃষ্টি করেছিলেন এবং সাহিত্যের বিদ্রোহী মনোভাবের সাথে কাজী নজরুলকে তুলনা করেছিলেন।');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','admin','tutor') DEFAULT 'student',
  `unit` enum('Unit A','Unit B','Unit C') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `unit`, `created_at`) VALUES
(1, 'testStudent1', 'student1@gmail.com', '$2y$10$bekByBXTcyBBb7oRYq3dG.RCY6MHEVZeKH8Ar8UWKIsVjbwtGMuGq', 'student', 'Unit A', '2025-07-16 05:51:31'),
(2, 'testTutor1', 'tutor1@gmail.com', '$2y$10$FEQXp3Ua3mqyO6wtjZ6GbuZNI3KGJWn6K/vP6PLbKoCbGWciCADlS', 'tutor', NULL, '2025-07-16 06:07:42'),
(3, 'student2', 'student2@gmail.com', '$2y$10$ueesz7KXpPokiG0SUoWeIOyumsUWakFx3.UQuFeKDldtbv/vmxnVW', 'student', 'Unit B', '2025-09-11 14:32:55'),
(4, 'Tutor2', 'tutor2@gmail.com', '$2y$10$j9aaFC4K0jfpQNqA3c3AUO3sjy3A5hxap7s0RKw5x7BEHjmCixbk2', 'tutor', NULL, '2025-09-11 14:33:45'),
(5, 'Tamim', 'rafitamim@gmail.com', '$2y$10$4OC119o12xxAlUJIt91EJ.z2Szm0vjjGarCiG8tCSrpIl4HBIRX2C', 'tutor', NULL, '2025-09-12 02:55:52'),
(6, 'Fatima', 'fatima123@gmail.com', '$2y$10$atg5wz.mYk5W19fxsA5aUuQO6uBxAyYHuGyVc9t97Rhd.TYC917oS', 'student', 'Unit C', '2025-09-13 18:45:04');

-- --------------------------------------------------------

--
-- Table structure for table `user_progress`
--

CREATE TABLE `user_progress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `unit` enum('Unit A','Unit B','Unit C') NOT NULL,
  `subject` enum('Physics','Chemistry','Biology','Mathematics','Business Studies','Accounting','Economics','History','Literature','Sociology') NOT NULL,
  `score` int(11) DEFAULT NULL,
  `total_attempts` int(11) DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_progress`
--

INSERT INTO `user_progress` (`id`, `user_id`, `unit`, `subject`, `score`, `total_attempts`, `last_updated`) VALUES
(1, 1, 'Unit A', 'Physics', 1, 1, '2025-07-27 15:22:09'),
(2, 1, 'Unit A', 'Chemistry', 1, 1, '2025-08-13 09:01:47'),
(3, 6, 'Unit C', 'History', 1, 1, '2025-09-13 18:48:41'),
(4, 6, 'Unit C', 'Literature', 1, 1, '2025-09-13 18:50:29'),
(5, 6, 'Unit C', 'Sociology', 3, 1, '2025-09-13 18:51:46');

-- --------------------------------------------------------

--
-- Table structure for table `user_results`
--

CREATE TABLE `user_results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `test_id` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `total_questions` int(11) DEFAULT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_results`
--

INSERT INTO `user_results` (`id`, `user_id`, `test_id`, `score`, `total_questions`, `completed_at`) VALUES
(1, 1, 1, 1, 2, '2025-07-27 15:22:09'),
(2, 1, 2, 1, 3, '2025-08-13 09:01:47'),
(3, 6, 8, 1, 2, '2025-09-13 18:48:41'),
(4, 6, 9, 1, 3, '2025-09-13 18:50:29'),
(5, 6, 10, 3, 3, '2025-09-13 18:51:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_result` (`user_id`,`result_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `forum_comments`
--
ALTER TABLE `forum_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `live_classes`
--
ALTER TABLE `live_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tutor_id` (`tutor_id`);

--
-- Indexes for table `study_materials`
--
ALTER TABLE `study_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `test_questions`
--
ALTER TABLE `test_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_id` (`test_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_results`
--
ALTER TABLE `user_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `test_id` (`test_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `forum_comments`
--
ALTER TABLE `forum_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `forum_posts`
--
ALTER TABLE `forum_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `live_classes`
--
ALTER TABLE `live_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `study_materials`
--
ALTER TABLE `study_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `test_questions`
--
ALTER TABLE `test_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_progress`
--
ALTER TABLE `user_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_results`
--
ALTER TABLE `user_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `badges`
--
ALTER TABLE `badges`
  ADD CONSTRAINT `badges_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `forum_comments`
--
ALTER TABLE `forum_comments`
  ADD CONSTRAINT `forum_comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`),
  ADD CONSTRAINT `forum_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD CONSTRAINT `forum_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `live_classes`
--
ALTER TABLE `live_classes`
  ADD CONSTRAINT `live_classes_ibfk_1` FOREIGN KEY (`tutor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `study_materials`
--
ALTER TABLE `study_materials`
  ADD CONSTRAINT `study_materials_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `tests`
--
ALTER TABLE `tests`
  ADD CONSTRAINT `tests_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `test_questions`
--
ALTER TABLE `test_questions`
  ADD CONSTRAINT `test_questions_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`);

--
-- Constraints for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD CONSTRAINT `user_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_results`
--
ALTER TABLE `user_results`
  ADD CONSTRAINT `user_results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_results_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

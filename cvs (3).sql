-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 26, 2026 at 07:32 PM
-- Server version: 8.0.45-0ubuntu0.22.04.1
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dg250118283_AstonCV`
--

-- --------------------------------------------------------

--
-- Table structure for table `cvs`
--

CREATE TABLE `cvs` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keyprogramming` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `education` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `URLlinks` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cvs`
--

INSERT INTO `cvs` (`id`, `name`, `email`, `password`, `keyprogramming`, `profile`, `education`, `URLlinks`) VALUES
(2, 'Daniel Moore', 'daniel.moore.web@gmail.com', '$2y$10$TDYR5/OI5MYcAeF9eCqNIeAeWSXqAT1K865DBOVbndtpx5sAvJbBK', 'JavaScript', 'A front-end focused developer who enjoys building responsive and user-friendly web applications with modern JavaScript.', 'University of Cambridge, BSc Software Engineering', 'https://github.com/danielmoore-web'),
(3, 'Priya Patel', 'priyapatel63@gmail.com', '$2y$10$HAdTpS7Ff2P3d1/vbM8eue2v8E8Wz0YH2oisJf0ClMlMLoRR2/aqG', 'Java', 'A detail-oriented programmer with experience in object-oriented programming and developing reliable applications in Java.', 'University of Birmingham, BSc Computer Science', 'https://github.com/priyapatel-code'),
(4, 'Ethan Walker', 'ethan.walker.sql@gmail.com', '$2y$10$ZKyHUvvDSNrxEVPoWWwKJeDMoMAmHMY0h2TTHxWNc0cfcnrzRFVzy', 'SQL', 'A student with a growing interest in databases, data modelling and writing efficient SQL queries for data-driven systems.', 'University of Manchester, BSc Data Science', 'https://github.com/ethanwalker-data'),
(6, 'Lucas Green', 'lucas.green25@outlook.com', '$2y$10$81B3wdr3gFXUGrQlGwQmIOL.tVv38XFgdZQXS8FtqM0S1yRSz6peW', 'C++', 'A logical and organised developer interested in developing backend applications, backend development and clean coding practices in C++.', 'Imperial College London, BSc Software Engineering', 'https://github.com/lucasgreen-cs'),
(7, 'Hannah Scott', 'hannah.scott.cpp@gmail.com', '$2y$10$jMUrXoDA8XDM3KpVahPV4.rPSEq8DL1IAIYYac.ycQAMVR6LZEvOm', 'C#', 'A problem-solving focused programmer with an interest in algorithms, systems programming and efficient coding in C#.', 'University of Nottingham, BSc Computer Science', 'https://github.com/hannahscott-c#'),
(8, 'Aisha Khan', 'aisha.khan@gmail.com', '$2y$10$mm2AxggNx4oLjy5HIv7zyeVQ8N1Sck5X0kDBBWptJF.rKRQJ4mxFy', 'Python', 'A motivated junior data analyst with a strong interest in data analysis, backend systems and problem solving using Python.', 'Aston University, BSc Artifical Intelligence & Data Science', 'https://github.com/aishakhan-dev'),
(9, 'Sophia Ahmed', 'sophia.ahmed.php@outlook.com', '$2y$10$mkYo0E7qG0g4HihCHsbscuM96CrGpvtUi8O9/eos/U4QBaGquH/Y.', 'PHP', 'An aspiring full-stack developer with a passion for building secure and dynamic web applications using PHP and MySQL.', 'University of Wolverhampton, BSc Web Development', 'https://github.com/sophia-ahmed-php');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cvs`
--
ALTER TABLE `cvs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cvs`
--
ALTER TABLE `cvs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2019 m. Kov 17 d. 19:22
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dd_pauliaus`
--

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `events`
--

CREATE TABLE `events` (
  `ID` int(11) NOT NULL,
  `Title` varchar(255) COLLATE utf32_unicode_520_ci NOT NULL,
  `Text` text COLLATE utf32_unicode_520_ci NOT NULL,
  `Author` char(40) COLLATE utf32_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_520_ci;

--
-- Sukurta duomenų kopija lentelei `events`
--

INSERT INTO `events` (`ID`, `Title`, `Text`, `Author`) VALUES
(1, 'First event', 'Welcome to this new page', 'paulius'),
(5, 'Thank you ', ' Well, this has been a ride. Certainly better doing this than other homework.', 'paulius'),
(7, 'Blog ', 'This is a beginning of a blog. Nice', 'paulius'),
(8, 'Update ', 'Nenoriu daryti algoritmÅ³...', 'admin');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `username` char(40) COLLATE utf32_unicode_520_ci NOT NULL,
  `password_hash` char(255) COLLATE utf32_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_520_ci;

--
-- Sukurta duomenų kopija lentelei `users`
--

INSERT INTO `users` (`ID`, `username`, `password_hash`) VALUES
(1, 'admin', '$2y$10$bceyQCmuAvdAxgkPbm0TvuJt99LX/z9nHR9knisOkhX2F2xT8CX0u'),
(2, 'paulius', '$2y$10$oqIVgtIKxFesOfl4S/ZMCOEdU8u7VsQOmb4mWPRPv6FdyEwiRUXJa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Author` (`Author`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Apribojimai eksportuotom lentelėm
--

--
-- Apribojimai lentelei `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`Author`) REFERENCES `users` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2025 at 07:00 PM
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
-- Database: `library_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `available_status` enum('available','borrowed') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `author`, `category`, `available_status`, `created_at`) VALUES
(1, 'Introduction to PHP', 'frederic nt', 'Programming', '', '2025-11-05 16:56:59'),
(2, 'Web Development Basics', 'gaga ooh', 'Web Development', '', '2025-11-05 16:56:59'),
(3, 'Database Design', 'Michael bro', 'Database', '', '2025-11-05 16:56:59'),
(4, 'JavaScript Essentials', 'Emily Davis', 'Programming', 'available', '2025-11-05 16:56:59'),
(5, 'CSS Mastery', 'David mn', 'Web Development', 'available', '2025-11-05 16:56:59');

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_books`
--

CREATE TABLE `borrowed_books` (
  `borrow_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrow_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` enum('borrowed','returned') DEFAULT 'borrowed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowed_books`
--

INSERT INTO `borrowed_books` (`borrow_id`, `student_id`, `book_id`, `borrow_date`, `return_date`, `status`) VALUES
(1, 3, 2, '2025-11-05', '2025-11-19', 'borrowed'),
(2, 3, 3, '2025-11-05', '2025-11-19', 'borrowed'),
(3, 3, 1, '2025-11-05', '2025-11-19', 'borrowed');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `role` enum('student','admin') DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `student_id`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@library.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADM001', 'admin', '2025-11-05 16:56:04'),
(2, 'vaz', 'john@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'STU001', 'student', '2025-11-05 16:56:04'),
(3, 'NIYOMUNGERI Anitha', 'ntawukuriryayofrederic817@gmail.com', '$2y$10$BuDUlMcDAHv9rvmPsJZbv.3GAjim8eu2LTFNlPdVXeJMnenmGZwrq', '24RP12663', 'student', '2025-11-05 17:06:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD PRIMARY KEY (`borrow_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  MODIFY `borrow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD CONSTRAINT `borrowed_books_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `borrowed_books_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

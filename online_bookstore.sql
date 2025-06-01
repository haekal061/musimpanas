-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2025 at 04:07 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `online_bookstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `publisher` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `author`, `publisher`, `price`, `description`, `cover_image`) VALUES
(1, 'Madilog', 'Iljas Hussein', 'Penerbit Andi', 125000, 'buku ini merupakan suatu karya dari seorang terkenal atau pahlawan sebenarnya ', '1748236743_51099226.jpg'),
(2, 'Dasar Pemrograman Python 3', 'Iljas Hussein', 'Penerbit Andi', 32000, 'buku untuk belajar python ', '1748236889_Dasar_Pemrograman_Python_3_1.avif'),
(3, 'hgffjhgkjhlk', 'kakakakak', 'asep sepp', 134000, ' n jk jnkjnkjniubnjinium,miohuiomkmiomomoi', '1748411033_1748395758_IMG_20210312_124638.jpg'),
(4, 'hgffjhgkjhlk', 'kakakakak', 'asep sepp', 134000, 'ljh jbknlkniuygfuihoihoinkjniuhiuhi', '1748411059_1748395916_j9qrm58sxuu7jyi62hhkhz.jpg'),
(5, 'Sejarah Dunia Yang Disembunyikan', 'Jonathan Black', 'Jonathan Black', 123456, 'Dalam buku kontroversial yang sangat tersohor ini, Jonathan Black mengupas secara tajam penelusurannya yang brilian tentang misteri sejarah dunia.', '1748746253_dunia baru.jpg'),
(6, 'Lancar Java dan Javascript', 'Elex Media Komputindo', 'Elex Media Komputindo', 123456, 'Java dan Javascript adalah bahasa pemrograman yang terus berkembang. Java dapat digunakan untuk membuat aplikasi lintas platform baik untuk komputer maupun Android. Sedangkan Javascript merupakan bahasa pemrograman untuk pengembangan website yang terus dipelajari hingga kini.', '1748746458_9786230005633_Cov_Lancar_Ja.jpg'),
(7, 'Masquerade Hotel', ' Keigo Higashino', 'Gramedia Pustaka Utama', 123456, 'Terjadi beberapa pembunuhan misterius di Tokyo yang diduga pihak kepolisian sebagai kasus pembunuhan berantai. Belum diketahui siapa tersangkanya dan siapa target berikutnya. Satu-satunya hal yang terungkap dari kode yang ditinggalkan pelaku adalah pembunuhan ', '1748746604_hotel.jpg'),
(8, 'IQRO', 'As\'ad Humam', 'Balai Litbang LPTQ', 123456, 'buku teks yang digunakan komunitas Muslim di Indonesia dan Malaysia untuk belajar membaca huruf-huruf Arab dan melafalkan bahasa tersebut. ', '1748746813_iqro.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `ebooks`
--

CREATE TABLE `ebooks` (
  `ebook_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ebooks`
--

INSERT INTO `ebooks` (`ebook_id`, `title`, `description`, `file`) VALUES
(2, 'logika Fuzzy', 'y8hiujiohimlk,;.,jnbnjm', '2020, B. Santoso, AIS Azis, Zohrahayaty, Machine Learning & Reasoning Fuzzy Logic, Deepublish.pdf'),
(3, 'gommom', 'ydjb,mb,kbkjh', 'Narrative_of_the_Life_of_Frederick_Dougl.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `ebook_files`
--

CREATE TABLE `ebook_files` (
  `ebook_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','employee') DEFAULT 'employee',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `order_date` date NOT NULL,
  `status` enum('pending','confirmed','shipped','completed') DEFAULT 'pending',
  `payment_proof` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `book_id`, `order_date`, `status`, `payment_proof`) VALUES
(1, 12, 6, '2025-06-01', 'completed', 'payment_proofs/1748754864_iqro.jpg'),
(2, 13, 6, '2025-06-01', 'completed', 'payment_proofs/1748756223_Bukti-Transfer.jpg'),
(3, 12, 7, '2025-06-01', 'completed', 'payment_proofs/1748784412_Bukti-Transfer.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('unverified','verified') DEFAULT 'unverified',
  `proof_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `subscription_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` enum('active','expired') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`subscription_id`, `user_id`, `start_date`, `end_date`, `status`) VALUES
(1, 1, '2025-05-20 07:00:00', '2025-06-20 07:00:00', 'active'),
(2, 12, '2025-05-31 16:00:00', '2025-06-30 16:00:00', 'active'),
(3, 13, '2025-05-31 16:00:00', '2025-06-30 16:00:00', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_requests`
--

CREATE TABLE `subscription_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `request_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `proof_file` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscription_requests`
--

INSERT INTO `subscription_requests` (`id`, `user_id`, `request_date`, `description`, `proof_file`, `status`) VALUES
(1, 1, '2025-05-20', 'sudah transfer', 'uploads/1747731258_51099226.jpg', 'approved'),
(2, 12, '2025-06-01', '50000', 'uploads/1748754928_iqro.jpg', 'approved'),
(3, 13, '2025-06-01', '50000', 'uploads/1748756303_Bukti-Transfer.jpg', 'approved'),
(4, 13, '2025-06-01', '50.000', 'uploads/1748785240_Bukti-Transfer.jpg', 'rejected');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin','employee') DEFAULT 'user',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(1, 'riswan', 'ahmadriswan1718@gmail.com', '$2y$10$mz8TOzXU70Mw74pEOUQYXe4NUS/HIrGpyWoSJx2rj/nelwPSuOC6O', 'user', 'active', '2025-05-18 04:35:49'),
(5, 'karyawan2', 'karyawan2@example.com', '$2y$10$98360ej7hWMl0S21I0IqR.Om8apdrEaulhaJ1BV4TvTjT2FBKegKG', 'employee', 'active', '2025-05-19 06:07:28'),
(6, 'admin1', 'admin1@example.com', '$2y$10$j0m/XKiyFzHVL5jXRR66xus1lJ391xj62nnra9EboHC3DrY4NKCW.', 'admin', 'active', '2025-05-19 06:48:18'),
(11, 'asep', 'asep12@gmail.com', '$2y$10$r854WiqlGDP8BJ/He.PL0.tH2CJO2RYqnVFR7bLzOocVBJyZRfsbm', 'user', 'active', '2025-06-01 04:29:05'),
(12, 'pais', 'pais12@gamil.com', '$2y$10$x8VQ665n4Bc0E/U9WaWP7OyCfJ80SO0Cbwr622eER1qERai7vJA1S', 'user', 'active', '2025-06-01 05:11:57'),
(13, 'ARUL', 'arul12@gmail.com', '$2y$10$1e6ES7tTQvEsulhL/U0Uku/Y3SDtGHrwe4PDfuyIk2nCKlVa9/j.G', 'user', 'active', '2025-06-01 05:34:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `ebooks`
--
ALTER TABLE `ebooks`
  ADD PRIMARY KEY (`ebook_id`);

--
-- Indexes for table `ebook_files`
--
ALTER TABLE `ebook_files`
  ADD PRIMARY KEY (`ebook_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`subscription_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `subscription_requests`
--
ALTER TABLE `subscription_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ebooks`
--
ALTER TABLE `ebooks`
  MODIFY `ebook_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ebook_files`
--
ALTER TABLE `ebook_files`
  MODIFY `ebook_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subscription_requests`
--
ALTER TABLE `subscription_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `subscription_requests`
--
ALTER TABLE `subscription_requests`
  ADD CONSTRAINT `subscription_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

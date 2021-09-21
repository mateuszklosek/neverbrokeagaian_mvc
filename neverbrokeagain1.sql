-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 20 Wrz 2021, 21:04
-- Wersja serwera: 10.4.19-MariaDB
-- Wersja PHP: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `neverbrokeagain1`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `expense_category_assigned_to_user_id` int(11) UNSIGNED NOT NULL,
  `payment_method_assigned_to_user_id` int(11) UNSIGNED NOT NULL,
  `amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `date_of_expense` date NOT NULL,
  `expense_comment` varchar(100) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `expenses`
--

INSERT INTO `expenses` (`id`, `user_id`, `expense_category_assigned_to_user_id`, `payment_method_assigned_to_user_id`, `amount`, `date_of_expense`, `expense_comment`) VALUES
(4, 44, 27, 10, '100.00', '2021-09-18', 'Narkotyki'),
(5, 20, 1, 1, '3000.00', '2021-09-18', 'sadsa'),
(6, 20, 4, 2, '2000.00', '2021-09-18', ''),
(7, 48, 28, 15, '100.00', '2021-08-20', 'Bilet miesięczny'),
(8, 48, 39, 16, '200.00', '2021-08-16', 'Prezent dla Gościny'),
(9, 48, 31, 17, '1200.00', '2021-09-20', ''),
(10, 48, 31, 16, '1200.00', '2021-08-02', ''),
(11, 48, 28, 15, '120.00', '2021-09-20', 'Bilet miesięczny'),
(12, 48, 30, 16, '300.00', '2021-09-20', 'Zakupy na 2 tygodnie');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expenses_category_assigned_to_users`
--

CREATE TABLE `expenses_category_assigned_to_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `expenses_category_assigned_to_users`
--

INSERT INTO `expenses_category_assigned_to_users` (`id`, `user_id`, `name`) VALUES
(1, 20, 'Transport'),
(2, 20, 'Książki'),
(3, 20, 'Jedzenie'),
(4, 20, 'Mieszkanie'),
(13, 20, 'Inne'),
(14, 20, 'Pies'),
(15, 44, 'Transport'),
(16, 44, 'Książki'),
(17, 44, 'Jedzenie'),
(18, 44, 'Czynsz'),
(19, 44, 'Telefon'),
(20, 44, 'Zdrowie'),
(21, 44, 'Ubrania'),
(22, 44, 'Higiena'),
(23, 44, 'Dzieci'),
(24, 44, 'Rekraacja'),
(25, 44, 'Wycieczki'),
(26, 44, 'Prezenty'),
(27, 44, 'Inne'),
(28, 48, 'Transport'),
(29, 48, 'Książki'),
(30, 48, 'Jedzenie'),
(31, 48, 'Czynsz'),
(32, 48, 'Telefon'),
(33, 48, 'Zdrowie'),
(34, 48, 'Ubrania'),
(35, 48, 'Higiena'),
(36, 48, 'Dzieci'),
(37, 48, 'Rekraacja'),
(38, 48, 'Wycieczki'),
(39, 48, 'Prezenty'),
(40, 48, 'Inne');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expenses_category_default`
--

CREATE TABLE `expenses_category_default` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `expenses_category_default`
--

INSERT INTO `expenses_category_default` (`id`, `name`) VALUES
(1, 'Transport'),
(2, 'Książki'),
(3, 'Jedzenie'),
(4, 'Czynsz'),
(5, 'Telefon'),
(6, 'Zdrowie'),
(7, 'Ubrania'),
(8, 'Higiena'),
(9, 'Dzieci'),
(10, 'Rekraacja'),
(11, 'Wycieczki'),
(12, 'Prezenty'),
(13, 'Inne');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `incomes`
--

CREATE TABLE `incomes` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `income_category_assigned_to_user_id` int(11) UNSIGNED NOT NULL,
  `amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `date_of_income` date NOT NULL,
  `income_comment` varchar(100) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `incomes`
--

INSERT INTO `incomes` (`id`, `user_id`, `income_category_assigned_to_user_id`, `amount`, `date_of_income`, `income_comment`) VALUES
(5, 44, 13, '1200.00', '2021-09-18', 'FlaktGroup'),
(6, 20, 3, '2000.00', '2021-09-18', ''),
(7, 20, 5, '2999.99', '2021-09-17', ''),
(8, 48, 21, '3000.00', '2021-08-12', 'Biedronka'),
(9, 48, 21, '3000.00', '2021-09-20', ''),
(10, 48, 23, '159.90', '2021-09-20', 'Sprzedaż butów');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `incomes_category_assigned_to_users`
--

CREATE TABLE `incomes_category_assigned_to_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `incomes_category_assigned_to_users`
--

INSERT INTO `incomes_category_assigned_to_users` (`id`, `user_id`, `name`) VALUES
(2, 20, 'Inne'),
(3, 20, 'Kieszonkowe'),
(5, 20, 'Krypto'),
(8, 20, 'Allegro'),
(9, 43, 'Wypłata'),
(10, 43, 'Lokata'),
(11, 43, 'Allegro'),
(12, 43, 'Inne'),
(13, 44, 'Wypłata'),
(14, 44, 'Lokata'),
(15, 44, 'Allegro'),
(16, 44, 'Inne'),
(17, 44, 'Wypłata'),
(18, 44, 'Lokata'),
(19, 44, 'Allegro'),
(20, 44, 'Inne'),
(21, 48, 'Wypłata'),
(22, 48, 'Lokata'),
(23, 48, 'Allegro'),
(24, 48, 'Inne');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `incomes_category_default`
--

CREATE TABLE `incomes_category_default` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `incomes_category_default`
--

INSERT INTO `incomes_category_default` (`id`, `name`) VALUES
(1, 'Wypłata'),
(2, 'Lokata'),
(3, 'Allegro'),
(4, 'Inne');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `payment_methods_assigned_to_users`
--

CREATE TABLE `payment_methods_assigned_to_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `payment_methods_assigned_to_users`
--

INSERT INTO `payment_methods_assigned_to_users` (`id`, `user_id`, `name`) VALUES
(1, 20, 'Gotówka'),
(2, 20, 'Karta debetowa'),
(3, 20, 'Karta kredytowa'),
(5, 20, 'Inne'),
(8, 20, 'Blik'),
(9, 44, 'Inne'),
(10, 44, 'Gotówka'),
(11, 44, 'Karta debetowa'),
(12, 44, 'Karta kredytowa'),
(13, 44, 'Inne'),
(14, 48, 'Inne'),
(15, 48, 'Gotówka'),
(16, 48, 'Karta debetowa'),
(17, 48, 'Karta kredytowa'),
(18, 48, 'Inne');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `payment_methods_default`
--

CREATE TABLE `payment_methods_default` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `payment_methods_default`
--

INSERT INTO `payment_methods_default` (`id`, `name`) VALUES
(0, 'Inne'),
(1, 'Gotówka'),
(2, 'Karta debetowa'),
(3, 'Karta kredytowa'),
(4, 'Inne');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `remembered_logins`
--

CREATE TABLE `remembered_logins` (
  `token_hash` varchar(64) COLLATE utf8mb4_polish_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_polish_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_polish_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_polish_ci NOT NULL,
  `password_reset_hash` varchar(64) COLLATE utf8mb4_polish_ci DEFAULT NULL,
  `password_reset_expire` datetime DEFAULT NULL,
  `activation_hash` varchar(64) COLLATE utf8mb4_polish_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `password_reset_hash`, `password_reset_expire`, `activation_hash`, `is_active`) VALUES
(1, 'Dave', 'demo@dave.io', '$2y$10$BvnDtHymdFxGz34U8i4dZOyVfhDFPbr0Do7cB1f/mtYiWEhBiswJ.', NULL, NULL, NULL, 0),
(7, 'Mati', 'mati1@mati.pl', '$2y$10$kWWWTu3qd8P7JOKc6YsxOel4dZs6APBxwgIqtHqbzH1ttQl2zQ47O', NULL, NULL, NULL, 0),
(15, 'Mati', 'mati@mati.pl', '$2y$10$IkD/JULoDl.dBJwr2PB74OUc5fZTDjHUJcxl6uKrMCRLTN9z8m9li', '93e65572acbaa68c52a16aebe2f977fb5e153c546b84d331fd11ad11dc141d69', '2011-09-01 12:17:47', NULL, 0),
(20, 'Mati', 'klosekmateusz@gmail.com', '$2y$10$ZjQDVNM7Blmq77PgvUMdJ.3TVO0jBKCvQzITix9YK5nLSvRO9gMa2', NULL, NULL, NULL, 1),
(48, 'Gość', 'gosc@gosc.pl', '$2y$10$.M10qpXKKepN5cPnhnVNvumyby7cqvRDgxTB2BNk8b8WT3jqiK7Um', NULL, NULL, NULL, 1);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `expenses_category_assigned_to_users`
--
ALTER TABLE `expenses_category_assigned_to_users`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `expenses_category_default`
--
ALTER TABLE `expenses_category_default`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `incomes_category_assigned_to_users`
--
ALTER TABLE `incomes_category_assigned_to_users`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `incomes_category_default`
--
ALTER TABLE `incomes_category_default`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `payment_methods_assigned_to_users`
--
ALTER TABLE `payment_methods_assigned_to_users`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `payment_methods_default`
--
ALTER TABLE `payment_methods_default`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `remembered_logins`
--
ALTER TABLE `remembered_logins`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `password_reset_hash` (`password_reset_hash`),
  ADD UNIQUE KEY `activation_hash` (`activation_hash`),
  ADD KEY `name` (`name`) USING BTREE;

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT dla tabeli `expenses_category_assigned_to_users`
--
ALTER TABLE `expenses_category_assigned_to_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT dla tabeli `incomes`
--
ALTER TABLE `incomes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT dla tabeli `incomes_category_assigned_to_users`
--
ALTER TABLE `incomes_category_assigned_to_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT dla tabeli `payment_methods_assigned_to_users`
--
ALTER TABLE `payment_methods_assigned_to_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

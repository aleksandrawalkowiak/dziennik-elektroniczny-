-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sty 03, 2026 at 09:05 AM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dziennik_elektroniczny`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `klasy`
--

CREATE TABLE `klasy` (
  `id_klasy` int(11) NOT NULL,
  `nazwa` varchar(30) NOT NULL,
  `profil` varchar(50) DEFAULT NULL,
  `id_wychowawcy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `klasy`
--

INSERT INTO `klasy` (`id_klasy`, `nazwa`, `profil`, `id_wychowawcy`) VALUES
(1, '1A', 'matematyczno-informatyczny', 1),
(2, '2B', 'humanistyczny', 2),
(3, '3C', 'biologiczno-chemiczny', 1),
(4, '1B', 'językowy', 2),
(5, '2A', 'matematyczny', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `lekcje`
--

CREATE TABLE `lekcje` (
  `id_lekcji` int(11) NOT NULL,
  `id_przedmiotu` int(11) DEFAULT NULL,
  `id_klasy` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `temat` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lekcje`
--

INSERT INTO `lekcje` (`id_lekcji`, `id_przedmiotu`, `id_klasy`, `data`, `temat`) VALUES
(5, 1, 1, '2025-10-20', 'Algebra – równania liniowe'),
(6, 2, 1, '2025-10-21', 'Podstawy programowania w Pythonie'),
(7, 3, 2, '2025-10-20', 'Gramatyka – części mowy'),
(8, 4, 2, '2025-10-21', 'Historia Polski – rozbiory'),
(9, 5, 3, '2025-10-22', 'Budowa komórki'),
(10, 6, 3, '2025-10-23', 'Reakcje chemiczne – wprowadzenie'),
(11, 7, 4, '2025-10-22', 'Present Simple – ćwiczenia'),
(12, 8, 4, '2025-10-23', 'Mapa polityczna Europy'),
(13, 9, 5, '2025-10-22', 'Ruch jednostajny'),
(14, 1, 5, '2025-10-23', 'Funkcje – podstawy'),
(15, 12, 4, '2025-11-19', 'Sprawdzian zaliczeniowy- znajomość nut'),
(16, 13, 1, '2025-11-14', 'Zaliczenie z budowy karmnika');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `nauczyciele`
--

CREATE TABLE `nauczyciele` (
  `id_nauczyciela` int(11) NOT NULL,
  `imie` varchar(50) NOT NULL,
  `nazwisko` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `login` varchar(100) DEFAULT NULL,
  `haslo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nauczyciele`
--

INSERT INTO `nauczyciele` (`id_nauczyciela`, `imie`, `nazwisko`, `email`, `login`, `haslo`) VALUES
(1, 'Anna', 'Kowalska', 'anna.kowalska@szkola.pl', 'akowalska', 'haslo1'),
(2, 'Marek', 'Nowak', 'marek.nowak@szkola.pl', 'mnowak', 'haslo2'),
(3, 'Antonina', 'Kowal', 'antonina.kowal@szkola.pl', 'akowal', 'haslo8'),
(4, 'Mariusz', 'Nowacki', 'mariusz.nowacki@szkola.pl', 'mnowacki', 'haslo6'),
(5, 'Milena', 'Matczak', 'milena.matczak@szkola.pl', 'mmatczak', 'haslo7'),
(6, 'Andrzej', 'Grzyb', 'andrzej.grzyb@szkola.pl', 'agrzyb', 'haslo9'),
(7, 'Ewa', 'Zielińska', 'ewa.zielinska@szkola.pl', 'ezielinska', 'haslo3'),
(8, 'Tomasz', 'Lewandowski', 'tomasz.lewandowski@szkola.pl', 'tlewandowski', 'haslo4'),
(9, 'Karolina', 'Maj', 'karolina.maj@szkola.pl', 'kmaj', 'haslo5');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `obecnosci`
--

CREATE TABLE `obecnosci` (
  `id_obecnosci` int(11) NOT NULL,
  `id_ucznia` int(11) DEFAULT NULL,
  `id_lekcji` int(11) DEFAULT NULL,
  `status` enum('obecny','nieobecny','spóźniony','usprawiedliwiony') DEFAULT 'obecny',
  `data` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `obecnosci`
--

INSERT INTO `obecnosci` (`id_obecnosci`, `id_ucznia`, `id_lekcji`, `status`, `data`) VALUES
(16, 1, 5, 'obecny', '2025-10-22'),
(17, 2, 5, 'spóźniony', '2025-10-21'),
(18, 3, 6, 'obecny', '2025-10-21'),
(19, 4, 7, 'obecny', '2025-10-22'),
(20, 5, 6, 'nieobecny', '2025-10-21'),
(21, 6, 5, 'obecny', '2025-10-20'),
(22, 7, 9, 'obecny', '2025-10-22'),
(23, 8, 9, 'nieobecny', '2025-10-23'),
(24, 9, 11, 'spóźniony', '2025-10-22'),
(25, 10, 11, 'obecny', '2025-10-22'),
(26, 11, 13, 'obecny', '2025-10-23'),
(27, 12, 14, 'nieobecny', '2025-10-23'),
(28, 10, 15, 'obecny', '2025-11-19'),
(29, 4, 16, 'obecny', '2025-11-14');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `oceny`
--

CREATE TABLE `oceny` (
  `id_oceny` int(11) NOT NULL,
  `id_ucznia` int(11) DEFAULT NULL,
  `id_przedmiotu` int(11) DEFAULT NULL,
  `ocena` decimal(3,1) DEFAULT NULL,
  `waga` int(11) DEFAULT NULL,
  `opis` varchar(100) DEFAULT NULL,
  `data` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oceny`
--

INSERT INTO `oceny` (`id_oceny`, `id_ucznia`, `id_przedmiotu`, `ocena`, `waga`, `opis`, `data`) VALUES
(1, 1, 1, 4.5, 2, 'Sprawdzian – algebra', '2025-10-20'),
(2, 2, 2, 5.0, 1, 'Zadanie domowe – Python', '2025-10-21'),
(3, 3, 3, 4.0, 2, 'Kartkówka – gramatyka', '2025-10-20'),
(4, 7, 5, 5.0, 2, 'Kartkówka – budowa komórki', '2025-10-22'),
(5, 8, 6, 3.5, 1, 'Zadanie domowe – reakcje chemiczne', '2025-10-23'),
(6, 9, 7, 4.0, 1, 'Ćwiczenia – Present Simple', '2025-10-22'),
(7, 10, 8, 5.0, 2, 'Sprawdzian – Europa', '2025-10-23'),
(8, 11, 9, 4.5, 2, 'Test – ruch jednostajny', '2025-10-22'),
(9, 12, 1, 3.0, 1, 'Praca klasowa – funkcje', '2025-10-23'),
(10, 4, 5, 3.5, 2, 'Sprawdzian- anatomia', '2026-01-02'),
(11, 13, 2, 5.5, 4, 'Projekt zaliczeniowy', '2026-01-02'),
(12, 10, 12, 4.5, 2, 'Sprawdzian- znajomość nut', '2026-01-03'),
(13, 4, 13, 3.0, 1, 'Budowa karmnika', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `przedmioty`
--

CREATE TABLE `przedmioty` (
  `id_przedmiotu` int(11) NOT NULL,
  `nazwa` varchar(50) NOT NULL,
  `id_nauczyciela` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `przedmioty`
--

INSERT INTO `przedmioty` (`id_przedmiotu`, `nazwa`, `id_nauczyciela`) VALUES
(1, 'Matematyka', 1),
(2, 'Informatyka', 1),
(3, 'Polski', 2),
(4, 'Historia', 2),
(5, 'Biologia', 7),
(6, 'Chemia', 7),
(7, 'Język angielski', 9),
(8, 'Geografia', 8),
(9, 'Fizyka', 1),
(10, 'Wiedza o społeczeństwie', 3),
(11, 'Plastyka', 4),
(12, 'Muzyka', 5),
(13, 'Technika', 6);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uczniowie`
--

CREATE TABLE `uczniowie` (
  `id_ucznia` int(11) NOT NULL,
  `imie` varchar(50) NOT NULL,
  `nazwisko` varchar(50) NOT NULL,
  `data_urodzenia` date DEFAULT NULL,
  `id_klasy` int(11) DEFAULT NULL,
  `adres_email` varchar(50) DEFAULT NULL,
  `login` varchar(100) DEFAULT NULL,
  `haslo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uczniowie`
--

INSERT INTO `uczniowie` (`id_ucznia`, `imie`, `nazwisko`, `data_urodzenia`, `id_klasy`, `adres_email`, `login`, `haslo`) VALUES
(1, 'Jan', 'Nowak', '2008-05-12', 1, 'jan.nowak@gmail.com', 'jnowak', '1234'),
(2, 'Ola', 'Wiśniewska', '2008-09-20', 1, 'ola.wisniewska@gmail.com', 'owisniewska', 'abcd'),
(3, 'Kamil', 'Kaczmarek', '2007-12-02', 2, 'kamil.kaczmarek@gmail.com', 'kkaczmarek', 'qwerty'),
(4, 'Mikołaj', 'Adamczak', '2007-05-12', 1, 'mikolaj.adamczak@gmail.com', 'madamczak', '4567'),
(5, 'Oliwia', 'Wabińska', '2006-09-19', 1, 'oliwia.wabinska@gmail.com', 'owabinska', 'defg'),
(6, 'Maurycy', 'Grzemski', '2005-12-02', 2, 'maurycy.grzemski@gmail.com', 'mgrzemski', '7890'),
(7, 'Julia', 'Kowal', '2008-03-11', 3, 'julia.kowal@gmail.com', 'jkowal', 'pass1'),
(8, 'Piotr', 'Lis', '2007-07-22', 3, 'piotr.lis@gmail.com', 'plis', 'pass2'),
(9, 'Natalia', 'Sikora', '2008-11-05', 4, 'natalia.sikora@gmail.com', 'nsikora', 'pass3'),
(10, 'Adam', 'Barański', '2007-02-14', 4, 'adam.baranski@gmail.com', 'abaranski', 'pass4'),
(11, 'Kinga', 'Kubiak', '2006-10-30', 5, 'kinga.kubiak@gmail.com', 'kkubiak', 'pass5'),
(12, 'Tomasz', 'Ratajczak', '2006-04-18', 5, 'tomasz.ratajczak@gmail.com', 'tratajczak', 'pass6'),
(13, 'Zuzanna', 'Pawlak', '2008-01-15', 1, 'zuzanna.pawlak@gmail.com', 'zpawlak', 'pass13'),
(14, 'Filip', 'Domański', '2008-04-22', 1, 'filip.domanski@gmail.com', 'fdomanski', 'pass14'),
(15, 'Maja', 'Szulc', '2008-06-30', 1, 'maja.szulc@gmail.com', 'mszulc', 'pass15'),
(16, 'Jakub', 'Walczak', '2008-09-11', 1, 'jakub.walczak@gmail.com', 'jwalczak', 'pass16'),
(17, 'Kacper', 'Michalak', '2007-02-19', 2, 'kacper.michalak@gmail.com', 'kmichalak', 'pass17'),
(18, 'Lena', 'Szymczak', '2007-05-25', 2, 'lena.szymczak@gmail.com', 'lszymczak', 'pass18'),
(19, 'Bartosz', 'Kopeć', '2007-08-14', 2, 'bartosz.kopec@gmail.com', 'bkopec', 'pass19'),
(20, 'Emilia', 'Krawczyk', '2007-12-03', 2, 'emilia.krawczyk@gmail.com', 'ekrawczyk', 'pass20'),
(21, 'Oskar', 'Jasiński', '2006-03-10', 3, 'oskar.jasinski@gmail.com', 'ojasinski', 'pass21'),
(22, 'Alicja', 'Bednarek', '2006-07-21', 3, 'alicja.bednarek@gmail.com', 'abednarek', 'pass22'),
(23, 'Dominik', 'Czajka', '2006-10-09', 3, 'dominik.czajka@gmail.com', 'dcajka', 'pass23'),
(24, 'Wiktoria', 'Kopecka', '2006-11-28', 3, 'wiktoria.kopecka@gmail.com', 'wkopecka', 'pass24'),
(25, 'Igor', 'Stępień', '2008-02-17', 4, 'igor.stepien@gmail.com', 'istepien', 'pass25'),
(26, 'Helena', 'Kaczor', '2008-05-29', 4, 'helena.kaczor@gmail.com', 'hkaczor', 'pass26'),
(27, 'Michał', 'Kurek', '2008-08-08', 4, 'michal.kurek@gmail.com', 'mkurek', 'pass27'),
(28, 'Laura', 'Kopecka', '2008-10-19', 4, 'laura.kopecka@gmail.com', 'lkopecka', 'pass28'),
(29, 'Patryk', 'Nowicki', '2006-01-22', 5, 'patryk.nowicki@gmail.com', 'pnowicki', 'pass29'),
(30, 'Karolina', 'Wrona', '2006-04-11', 5, 'karolina.wrona@gmail.com', 'kwrona', 'pass30'),
(31, 'Sebastian', 'Kruk', '2006-06-27', 5, 'sebastian.kruk@gmail.com', 'skruk', 'pass31'),
(32, 'Magdalena', 'Lisowska', '2006-09-15', 5, 'magdalena.lisowska@gmail.com', 'mlisowska', 'pass32');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uwagi`
--

CREATE TABLE `uwagi` (
  `id_uwagi` int(11) NOT NULL,
  `id_ucznia` int(11) DEFAULT NULL,
  `id_nauczyciela` int(11) DEFAULT NULL,
  `tresc` text DEFAULT NULL,
  `data` date DEFAULT NULL,
  `typ` enum('uwaga','pochwala') NOT NULL DEFAULT 'uwaga'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uwagi`
--

INSERT INTO `uwagi` (`id_uwagi`, `id_ucznia`, `id_nauczyciela`, `tresc`, `data`, `typ`) VALUES
(1, 2, 1, 'Ola spóźniła się na lekcję informatyki', '2025-10-21', 'uwaga'),
(2, 3, 2, 'Kamil nie odrobił pracy domowej z historii', '2025-10-21', 'uwaga'),
(3, 7, 7, 'Julia bardzo aktywnie pracowała na lekcji biologii', '2025-10-22', 'pochwala'),
(4, 8, 7, 'Piotr przeszkadzał podczas zajęć chemii', '2025-10-23', 'uwaga'),
(5, 9, 9, 'Natalia świetnie wykonała prezentację z angielskiego', '2025-10-22', 'pochwala'),
(6, 10, 8, 'Adam nie przyniósł zeszytu na geografię', '2025-10-23', 'uwaga'),
(7, 11, 1, 'Kinga bardzo dobrze rozwiązywała zadania z fizyki', '2025-10-22', 'pochwala'),
(8, 12, 1, 'Tomasz nie przygotował się do lekcji matematyki', '2025-10-23', 'uwaga'),
(9, 10, 5, 'Adam chętnie pomaga innym uczniom w nauce', '2026-01-03', 'pochwala'),
(11, 4, 6, 'Mikołaj ściągał na zaliczeniu', '2026-01-03', 'uwaga');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `klasy`
--
ALTER TABLE `klasy`
  ADD PRIMARY KEY (`id_klasy`),
  ADD KEY `id_wychowawcy` (`id_wychowawcy`);

--
-- Indeksy dla tabeli `lekcje`
--
ALTER TABLE `lekcje`
  ADD PRIMARY KEY (`id_lekcji`),
  ADD KEY `id_przedmiotu` (`id_przedmiotu`),
  ADD KEY `id_klasy` (`id_klasy`);

--
-- Indeksy dla tabeli `nauczyciele`
--
ALTER TABLE `nauczyciele`
  ADD PRIMARY KEY (`id_nauczyciela`);

--
-- Indeksy dla tabeli `obecnosci`
--
ALTER TABLE `obecnosci`
  ADD PRIMARY KEY (`id_obecnosci`),
  ADD KEY `id_ucznia` (`id_ucznia`),
  ADD KEY `id_lekcji` (`id_lekcji`);

--
-- Indeksy dla tabeli `oceny`
--
ALTER TABLE `oceny`
  ADD PRIMARY KEY (`id_oceny`),
  ADD KEY `id_ucznia` (`id_ucznia`),
  ADD KEY `id_przedmiotu` (`id_przedmiotu`);

--
-- Indeksy dla tabeli `przedmioty`
--
ALTER TABLE `przedmioty`
  ADD PRIMARY KEY (`id_przedmiotu`),
  ADD KEY `id_nauczyciela` (`id_nauczyciela`);

--
-- Indeksy dla tabeli `uczniowie`
--
ALTER TABLE `uczniowie`
  ADD PRIMARY KEY (`id_ucznia`);

--
-- Indeksy dla tabeli `uwagi`
--
ALTER TABLE `uwagi`
  ADD PRIMARY KEY (`id_uwagi`),
  ADD KEY `id_ucznia` (`id_ucznia`),
  ADD KEY `id_nauczyciela` (`id_nauczyciela`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `klasy`
--
ALTER TABLE `klasy`
  MODIFY `id_klasy` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `lekcje`
--
ALTER TABLE `lekcje`
  MODIFY `id_lekcji` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `nauczyciele`
--
ALTER TABLE `nauczyciele`
  MODIFY `id_nauczyciela` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `obecnosci`
--
ALTER TABLE `obecnosci`
  MODIFY `id_obecnosci` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `oceny`
--
ALTER TABLE `oceny`
  MODIFY `id_oceny` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `przedmioty`
--
ALTER TABLE `przedmioty`
  MODIFY `id_przedmiotu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `uczniowie`
--
ALTER TABLE `uczniowie`
  MODIFY `id_ucznia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `uwagi`
--
ALTER TABLE `uwagi`
  MODIFY `id_uwagi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `klasy`
--
ALTER TABLE `klasy`
  ADD CONSTRAINT `klasy_ibfk_1` FOREIGN KEY (`id_wychowawcy`) REFERENCES `nauczyciele` (`id_nauczyciela`);

--
-- Constraints for table `lekcje`
--
ALTER TABLE `lekcje`
  ADD CONSTRAINT `lekcje_ibfk_1` FOREIGN KEY (`id_przedmiotu`) REFERENCES `przedmioty` (`id_przedmiotu`),
  ADD CONSTRAINT `lekcje_ibfk_2` FOREIGN KEY (`id_klasy`) REFERENCES `klasy` (`id_klasy`);

--
-- Constraints for table `obecnosci`
--
ALTER TABLE `obecnosci`
  ADD CONSTRAINT `obecnosci_ibfk_1` FOREIGN KEY (`id_ucznia`) REFERENCES `uczniowie` (`id_ucznia`),
  ADD CONSTRAINT `obecnosci_ibfk_2` FOREIGN KEY (`id_lekcji`) REFERENCES `lekcje` (`id_lekcji`);

--
-- Constraints for table `oceny`
--
ALTER TABLE `oceny`
  ADD CONSTRAINT `oceny_ibfk_1` FOREIGN KEY (`id_ucznia`) REFERENCES `uczniowie` (`id_ucznia`),
  ADD CONSTRAINT `oceny_ibfk_2` FOREIGN KEY (`id_przedmiotu`) REFERENCES `przedmioty` (`id_przedmiotu`);

--
-- Constraints for table `przedmioty`
--
ALTER TABLE `przedmioty`
  ADD CONSTRAINT `przedmioty_ibfk_1` FOREIGN KEY (`id_nauczyciela`) REFERENCES `nauczyciele` (`id_nauczyciela`);

--
-- Constraints for table `uwagi`
--
ALTER TABLE `uwagi`
  ADD CONSTRAINT `uwagi_ibfk_1` FOREIGN KEY (`id_ucznia`) REFERENCES `uczniowie` (`id_ucznia`),
  ADD CONSTRAINT `uwagi_ibfk_2` FOREIGN KEY (`id_nauczyciela`) REFERENCES `nauczyciele` (`id_nauczyciela`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

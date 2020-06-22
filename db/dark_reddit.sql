-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22-Jun-2020 às 15:52
-- Versão do servidor: 10.4.11-MariaDB
-- versão do PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `dark_reddit`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `comments`
--

CREATE TABLE `comments` (
  `Id` int(11) NOT NULL,
  `User_Id` int(11) NOT NULL,
  `Votes_Id` int(11) NOT NULL,
  `Post_Id` int(11) NOT NULL,
  `Text` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `comments`
--

INSERT INTO `comments` (`Id`, `User_Id`, `Votes_Id`, `Post_Id`, `Text`) VALUES
(20, 3, 37, 2, 'gggggg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `post`
--

CREATE TABLE `post` (
  `Id` int(11) NOT NULL,
  `User_Id` int(11) NOT NULL,
  `Title` varchar(150) NOT NULL,
  `Description` varchar(5000) NOT NULL,
  `Votes_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `post`
--

INSERT INTO `post` (`Id`, `User_Id`, `Title`, `Description`, `Votes_Id`) VALUES
(2, 2, 'test_Title', 'test_Description', 8),
(3, 2, 'test', 'test', 9),
(5, 2, 'Title', 'Description', 10),
(6, 2, 'ytuytuytuyt', 'ytuytuytuyt66666', 11),
(7, 2, 'uyiuyiu', 'iuyipooi', 12),
(8, 2, 'yyyyyy', 'yyyyyy', 13),
(9, 2, 'kkkkkkk', 'kkkkkkk', 14),
(10, 2, 'uuuuu', 'uuuuu', 15),
(11, 2, 'rrrrr', 'rrrrr', 16),
(12, 2, 'uiuo', 'uiuo', 17),
(13, 3, 'test', 'itle', 18),
(14, 3, 'rrrrrrrrrrr', 'rrrrrrrrrrrrr', 19),
(15, 3, 'jjjjjjjjjjj', 'jjjjjjjjjjj', 20);

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

CREATE TABLE `user` (
  `Id` int(11) NOT NULL,
  `Username` varchar(60) NOT NULL,
  `Password` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `user`
--

INSERT INTO `user` (`Id`, `Username`, `Password`) VALUES
(1, 'admin', 'admin'),
(2, 'user', 'user'),
(3, 'teste', '$2y$10$/lRq6rGOoof13hzlw/9aIuVoTmvME9RZeqcSoOl6hcrR.tckM/Qsi'),
(4, 'admin2', '$2y$10$lAca0VFkg2RM3V598glf5OvoA2Fy1sUGGO2ruDVjahY8ZwzopH/Jy');

-- --------------------------------------------------------

--
-- Estrutura da tabela `uservote`
--

CREATE TABLE `uservote` (
  `Id` int(11) NOT NULL,
  `User_Id` int(11) NOT NULL,
  `Vote_Id` int(11) NOT NULL,
  `VoteType` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `uservote`
--

INSERT INTO `uservote` (`Id`, `User_Id`, `Vote_Id`, `VoteType`) VALUES
(15, 3, 10, 0),
(33, 3, 8, 0),
(46, 3, 12, 0),
(53, 3, 11, 1),
(62, 3, 9, 0),
(63, 3, 14, 0),
(64, 3, 37, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `votes`
--

CREATE TABLE `votes` (
  `Id` int(11) NOT NULL,
  `User_Id` int(11) NOT NULL,
  `Up` int(11) NOT NULL,
  `Down` int(11) NOT NULL,
  `Modifying` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `votes`
--

INSERT INTO `votes` (`Id`, `User_Id`, `Up`, `Down`, `Modifying`) VALUES
(1, 1, 5, 2, 0),
(2, 2, 78, 31, 0),
(3, 2, 0, 0, 0),
(4, 2, 0, 0, 0),
(5, 2, 0, 0, 0),
(6, 2, 0, 0, 0),
(7, 2, 0, 0, 0),
(8, 2, 12, 11, 0),
(9, 2, 55, 55, 0),
(10, 2, 1, 1, 0),
(11, 2, 1, 1, 0),
(12, 2, 1, 1, 0),
(13, 2, 0, 3, 0),
(14, 2, 6, 6, 0),
(15, 2, 0, 0, 0),
(16, 2, 0, 0, 0),
(17, 2, 45, 2, 0),
(18, 3, 0, 0, 0),
(19, 3, 0, 0, 0),
(20, 3, 0, 0, 0),
(21, 3, 0, 0, 1),
(22, 3, 0, 0, 1),
(23, 3, 0, 0, 1),
(24, 3, 0, 0, 1),
(25, 3, 0, 0, 1),
(26, 3, 0, 0, 1),
(27, 3, 0, 0, 1),
(28, 3, 0, 0, 1),
(29, 3, 0, 0, 1),
(30, 3, 0, 0, 1),
(31, 3, 0, 0, 1),
(32, 3, 0, 0, 1),
(33, 3, 0, 0, 1),
(34, 3, 0, 0, 1),
(35, 3, 58, 57, 0),
(36, 3, 1, 0, 0),
(37, 3, 0, 0, 0),
(38, 3, 0, 0, 1),
(39, 3, 0, 0, 1),
(40, 3, 0, 0, 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `User_fk_comments` (`User_Id`),
  ADD KEY `Votes_fk_comments` (`Votes_Id`),
  ADD KEY `Post_fk_comments` (`Post_Id`);

--
-- Índices para tabela `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Votes_fk_post` (`Votes_Id`),
  ADD KEY `User_fk_post` (`User_Id`) USING BTREE;

--
-- Índices para tabela `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`Id`);

--
-- Índices para tabela `uservote`
--
ALTER TABLE `uservote`
  ADD PRIMARY KEY (`Id`);

--
-- Índices para tabela `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `comments`
--
ALTER TABLE `comments`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `post`
--
ALTER TABLE `post`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `user`
--
ALTER TABLE `user`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `uservote`
--
ALTER TABLE `uservote`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT de tabela `votes`
--
ALTER TABLE `votes`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `Post_fk_comments` FOREIGN KEY (`Post_Id`) REFERENCES `post` (`Id`),
  ADD CONSTRAINT `User_fk_comments` FOREIGN KEY (`User_Id`) REFERENCES `user` (`Id`),
  ADD CONSTRAINT `Votes_fk_comments` FOREIGN KEY (`Votes_Id`) REFERENCES `votes` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `User_fk_post` FOREIGN KEY (`User_Id`) REFERENCES `user` (`Id`),
  ADD CONSTRAINT `Votes_fk_post` FOREIGN KEY (`Votes_Id`) REFERENCES `votes` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 23 2022 г., 16:58
-- Версия сервера: 5.7.29-log
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `api_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `author`
--

CREATE TABLE `author` (
  `id` int(11) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `lastname` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Авторы';

--
-- Дамп данных таблицы `author`
--

INSERT INTO `author` (`id`, `surname`, `name`, `lastname`) VALUES
(1, 'Абасов', 'Али', ''),
(2, 'Бабаев', 'Агаддин', ''),
(3, 'Вавжкевич', 'Марек', ''),
(4, 'Гаал', 'Арон', ''),
(5, 'Дериглазов', 'Анатолий', 'Николаевич'),
(6, 'Евдокимова', 'Светлана', 'Борисовна'),
(7, 'Яблонская', 'Елена', '');

-- --------------------------------------------------------

--
-- Структура таблицы `magazine`
--

CREATE TABLE `magazine` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL COMMENT 'Название',
  `description` varchar(500) DEFAULT NULL COMMENT 'Описание',
  `images` varchar(255) NOT NULL COMMENT 'Изображения',
  `date_public` date NOT NULL COMMENT 'Дата публикации',
  `id_author` int(11) NOT NULL COMMENT 'Автор'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `magazine`
--

INSERT INTO `magazine` (`id`, `title`, `description`, `images`, `date_public`, `id_author`) VALUES
(1, ' 	Рецензия на книгу стихов Бориса Кутенкова «Жили-боли»', 'Поначалу мне показалось, что название второго стихотворного сборника Бориса Кутенкова «Жили-боли» тяжело воспринимается на слух, хотя и отдаленно рифмуется по смыслу с его первой книгой стихов «Пазлы расстояний» (М.: 2009), ведь «жили-боли» — тоже в некотором роде пазлов.', '', '2012-05-20', 7),
(2, ' 	Мистицизм в поэзии Низами Гянджеви', '«Сокровищница тайн» - собрание всего того загадочного, на что человечество, на каком бы этапе своего развития ни находилось, ответов не имеет. Так чем же в этом случае является творчество Низами: сокровищницей вопросов или ответов?\r\nЕго родина - вся доступная знанию планета, его творчество - всё пространство-время мировой культуры..', '', '2021-12-01', 1),
(3, 'Журнал', 'фывыфвыф', '222', '2022-09-22', 5),
(13, '212456', 'lolll', 'fe048969e1a3bd5d9bf3e3ab2e802d23', '2022-09-23', 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `magazine`
--
ALTER TABLE `magazine`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `author`
--
ALTER TABLE `author`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `magazine`
--
ALTER TABLE `magazine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

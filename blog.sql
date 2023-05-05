-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 05 mai 2023 à 12:03
-- Version du serveur : 8.0.30
-- Version de PHP : 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `subtitle` text COLLATE utf8mb4_general_ci,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `author` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `subtitle`, `content`, `author`, `created_at`, `updated_at`) VALUES
(10, 'Les tendances actuelles en matière de développement web', 'Les technologies et les pratiques qui façonnent le développement web aujourd\'hui !!', 'Le développement web est un domaine en constante évolution, avec de nouvelles technologies, pratiques et tendances émergentes tout le temps. \r\nPour rester à la pointe de ce domaine en constante évolution, les développeurs web doivent être conscients des tendances actuelles et des technologies émergentes qui peuvent changer la façon dont nous construisons des sites web. \r\nDans cet article, nous allons passer en revue les tendances actuelles en matière de développement web, y compris l\'IA, la réalité virtuelle, le développement de PWA, le développement de sites web statiques et le développement de sites web sans code', 'admin', '2023-04-07 14:29:56', '2023-05-05 09:30:00'),
(11, 'Les meilleures pratiques pour la conception de sites web performants', 'Comment optimiser la vitesse, la sécurité et l\'expérience utilisateur de votre site web', 'La performance d\'un site web est essentielle pour garantir une bonne expérience utilisateur, ainsi que pour améliorer le classement du site dans les résultats de recherche. \r\nPour améliorer les performances d\'un site web, il existe de nombreuses bonnes pratiques de conception à suivre, notamment l\'optimisation des images, la réduction des demandes HTTP, la mise en cache du contenu, l\'utilisation de CDN et bien plus encore. \r\nDans cet article, nous allons examiner en détail ces bonnes pratiques et expliquer comment les appliquer pour créer un site web rapide, sûr et facile à utiliser.', 'admin', '2023-04-07 14:30:53', '2023-04-07 14:30:53'),
(12, 'Le développement de sites web accessibles', 'Comment garantir que votre site web est accessible à tous les utilisateurs, y compris les personnes handicapées', 'L\'accessibilité est un aspect important du développement web, qui permet de garantir que tous les utilisateurs, y compris ceux qui ont des besoins spéciaux, peuvent accéder facilement à un site web. \r\nPour garantir que votre site web est accessible, il est important de suivre les bonnes pratiques de conception, telles que l\'utilisation de balises alt pour les images, la prise en charge des contrôles de navigation au clavier et l\'utilisation de couleurs appropriées pour les personnes daltoniennes. \r\nDans cet article, nous allons examiner en détail les bonnes pratiques à suivre pour garantir l\'accessibilité de votre site web.', 'admin', '2023-04-07 14:31:23', '2023-04-07 14:31:23');

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `blog_post_id` int NOT NULL,
  `author` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `is_validated` tinyint(1) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_rejected` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `blog_post_id`, `author`, `content`, `is_validated`, `date`, `is_rejected`) VALUES
(7, 10, 'test', 'J&#039;ai vraiment apprécié cet article. Les informations présentées étaient intéressantes et bien expliquées. \r\n\r\nMerci de partager votre expertise avec nous !!', 1, '2023-04-28 09:42:35', 0),
(8, 10, 'test', 'Cette article n\'a aucun sens ! ', 0, '2023-04-07 14:46:05', 1),
(10, 10, 'test2', 'Je n&#039;avais jamais pensé à ce sujet de cette manière auparavant. \r\nVotre article..', 1, '2023-04-14 11:47:30', 0),
(12, 10, 'test', 'T&#039;es ou \r\narticle', 0, '2023-04-14 10:06:01', 1),
(14, 11, 'admin', 'test\r\nfd', 1, '2023-04-28 13:44:39', 0);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `pwd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_validated` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isAdmin` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `pwd`, `is_validated`, `created_at`, `isAdmin`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$v3mAGAy9qNYmjLXv3CLo2uNAIbtbq1EU31xYMW7x4h0Q0yjsYoy5G', 0, '2023-03-31 13:43:17', 1),
(5, 'test', 'test@gmail.com', '$2y$10$y1tXB9c14G5X0GpY0g/41ufFKe9fnoBwAbxOpwqGj4vEQ6LEi9Tmq', 0, '2023-03-31 13:46:37', 0),
(6, 'test2', 'test2@gmail.com', '$2y$10$VuSG5RvLyhPTHjoK4GvXMOmV0ybVd8RenfARp.lTiTZDG1JAukdce', 0, '2023-03-31 17:41:48', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_post_id` (`blog_post_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`blog_post_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

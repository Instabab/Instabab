-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Client :  localhost:8889
-- Généré le :  Mer 09 Novembre 2016 à 00:54
-- Version du serveur :  5.5.42
-- Version de PHP :  7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `instabab`
--

-- --------------------------------------------------------

--
-- Structure de la table `activations`
--

CREATE TABLE `activations` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `completed` tinyint(4) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `activations`
--

INSERT INTO `activations` (`id`, `user_id`, `code`, `completed`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'DFqOQaenC5pwvS3OC0FNnY8x9DpugJIX', 1, '2016-10-12 07:21:56', '2016-10-12 07:21:56', '2016-10-12 07:21:56'),
(2, 5, 'hNDa2gZrjSO3X4IPeLBOTBNsuAYgrclY', 1, '2016-10-19 08:59:51', '2016-10-19 08:59:51', '2016-10-19 08:59:51'),
(3, 6, 'av7L4kipJfV4j41OTRn5xLjwgvJcFkr8', 1, '2016-10-19 09:00:57', '2016-10-19 09:00:57', '2016-10-19 09:00:57'),
(4, 9, 'gfMyxptQKMV7KquMRAeXeMPXlkdFi6pV', 1, '2016-11-08 14:54:00', '2016-11-08 14:54:00', '2016-11-08 14:54:00');

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `value` smallint(6) NOT NULL,
  `id_photo` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `notes`
--

INSERT INTO `notes` (`id`, `value`, `id_photo`, `id_user`) VALUES
(1, 1, 1, 9),
(2, -1, 1, 9),
(3, 1, 2, 9),
(4, 1, 2, 9);

-- --------------------------------------------------------

--
-- Structure de la table `persistences`
--

CREATE TABLE `persistences` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `persistences`
--

INSERT INTO `persistences` (`id`, `user_id`, `code`, `created_at`, `updated_at`) VALUES
(1, 7, 'H3nu4ZJfr289y9aMuCzJ24SYOF7AGhJK', '2016-10-19 09:05:48', '2016-10-19 09:05:48'),
(2, 7, 'LhB5qnTJXjqFgAxFQW0477gPvIKSjr5p', '2016-10-19 09:06:25', '2016-10-19 09:06:25'),
(3, 7, 'vgoRZMpsqnmU5sSUDtgwXCTpsbihxwM8', '2016-10-19 09:06:53', '2016-10-19 09:06:53'),
(4, 7, 'DB6w7xs4kc2sisBNqclfOhPxhizUlPB0', '2016-10-19 09:15:52', '2016-10-19 09:15:52'),
(5, 7, 'ioiiOs0fNb3amIG8WoOQRREJUGY8pRDP', '2016-10-19 09:16:27', '2016-10-19 09:16:27'),
(6, 7, 'AeFcQYoBiy2UG9kI39VUI6FNDZiGIZCL', '2016-10-19 09:16:44', '2016-10-19 09:16:44'),
(7, 8, 'jG4lxndB2Vkspm1N40S2ekwjWbALmqgY', '2016-11-08 10:54:19', '2016-11-08 10:54:19'),
(8, 8, '0mTfXJJn9BP5fKJyTBgwhaH7tNP2GENc', '2016-11-08 13:02:04', '2016-11-08 13:02:04'),
(9, 8, '3yrnvhgGfrXjJP1VJuopUtSuCaAsbCKe', '2016-11-08 13:12:25', '2016-11-08 13:12:25'),
(10, 8, 'BUUuJCaawln9MU6UlnRbS24XAF1DjV8i', '2016-11-08 13:35:19', '2016-11-08 13:35:19'),
(11, 8, 'rW300GYDsiVMO9NGPAOKzMRromXQO9I7', '2016-11-08 14:25:18', '2016-11-08 14:25:18'),
(13, 9, 'iZscc9ZVjrVFaFqAx72dg51YtECbYSoK', '2016-11-08 14:55:56', '2016-11-08 14:55:56'),
(14, 9, 'WED1aKtWRMG2PGpnpe7Vi6Ih844xiX38', '2016-11-08 16:41:53', '2016-11-08 16:41:53'),
(16, 9, 'XD5BOU3Ch2M04BX8VTR6GlY8OTRv028I', '2016-11-08 23:02:49', '2016-11-08 23:02:49');

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE `photo` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `photo` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_place` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `photo`
--

INSERT INTO `photo` (`id`, `message`, `photo`, `date`, `id_place`, `id_user`) VALUES
(1, 'J''adore les kebabs. :D', '/images/pictures/t1.jpg', '2016-11-07 23:00:00', 1, 9),
(2, 'Celui là est excellent.', '/images/pictures/t2.jpg', '2016-11-07 23:00:00', 1, 9);

-- --------------------------------------------------------

--
-- Structure de la table `place`
--

CREATE TABLE `place` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `place`
--

INSERT INTO `place` (`id`, `name`, `address`) VALUES
(1, 'Au p''tit Kebab', '21 rue des Avignons');

-- --------------------------------------------------------

--
-- Structure de la table `reminders`
--

CREATE TABLE `reminders` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `completed` tinyint(4) NOT NULL DEFAULT '0',
  `completed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `roles`
--

INSERT INTO `roles` (`id`, `slug`, `name`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Admin', '{"user.create":true,"user.update":true,"user.delete":true}', '2016-10-19 07:49:02', '2016-10-19 07:49:02');

-- --------------------------------------------------------

--
-- Structure de la table `role_users`
--

CREATE TABLE `role_users` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tagsphotos`
--

CREATE TABLE `tagsphotos` (
  `id_photo` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `throttle`
--

CREATE TABLE `throttle` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `throttle`
--

INSERT INTO `throttle` (`id`, `user_id`, `type`, `ip`, `created_at`, `updated_at`) VALUES
(3, 0, 'global', NULL, '2016-10-19 08:36:34', '2016-10-19 08:36:34');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci NOT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `permissions`, `last_login`, `first_name`, `last_name`, `created_at`, `updated_at`, `avatar`, `description`) VALUES
(1, 'admin@admin.com', '$2y$10$PmrQyzHJK21emFXs5aRhseLSVwfjrXIICYUUCUq91.g6iDQaWhyBy', '', NULL, 'Yudi', 'Purwanto', '2016-10-12 05:21:56', '2016-10-12 05:21:56', '', ''),
(9, 'quentin.claudel@icloud.com', '$2y$10$n5mMynXsDXVbDof4RQTVvuYzgsqhPElUzsA1yHjVQAfvhnjMfG7tG', '', '2016-11-08 23:02:49', 'Quentin', 'Claudel', '2016-11-08 14:54:00', '2016-11-08 23:42:57', '/images/profiles/quentin.jpg', 'I have a dream of kebab. :)');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `activations`
--
ALTER TABLE `activations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `activations_user_id_unique` (`user_id`);

--
-- Index pour la table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `persistences`
--
ALTER TABLE `persistences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `persistences_code_unique` (`code`);

--
-- Index pour la table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `place`
--
ALTER TABLE `place`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Index pour la table `role_users`
--
ALTER TABLE `role_users`
  ADD PRIMARY KEY (`user_id`,`role_id`);

--
-- Index pour la table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tagsphotos`
--
ALTER TABLE `tagsphotos`
  ADD PRIMARY KEY (`id_photo`,`id_tag`);

--
-- Index pour la table `throttle`
--
ALTER TABLE `throttle`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `throttle_user_id_unique` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_first_name_index` (`first_name`),
  ADD KEY `users_last_name_index` (`last_name`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `activations`
--
ALTER TABLE `activations`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `persistences`
--
ALTER TABLE `persistences`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pour la table `photo`
--
ALTER TABLE `photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `place`
--
ALTER TABLE `place`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `throttle`
--
ALTER TABLE `throttle`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 06 mai 2021 à 13:14
-- Version du serveur :  10.4.17-MariaDB
-- Version de PHP : 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `heuresupp`
--

-- --------------------------------------------------------

--
-- Structure de la table `matiere`
--

CREATE TABLE `matiere` (
  `nummat` varchar(25) NOT NULL,
  `designation` varchar(25) NOT NULL,
  `nbheure` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `matiere`
--

INSERT INTO `matiere` (`nummat`, `designation`, `nbheure`) VALUES
('mat1', 'ALGO', 150),
('mat2', 'LANGAGE C', 150),
('matiere_1', 'ANGLAIS', 150);

-- --------------------------------------------------------

--
-- Structure de la table `professeur`
--

CREATE TABLE `professeur` (
  `matricule` varchar(25) NOT NULL,
  `nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `professeur`
--

INSERT INTO `professeur` (`matricule`, `nom`) VALUES
('prof_1', 'RASONAMPOIZINA'),
('prof_3', 'RAZAKA');

-- --------------------------------------------------------

--
-- Structure de la table `volumehoraire`
--

CREATE TABLE `volumehoraire` (
  `id` int(11) NOT NULL,
  `matricule` varchar(25) NOT NULL,
  `nummat` varchar(25) NOT NULL,
  `tauxhoraire` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `volumehoraire`
--

INSERT INTO `volumehoraire` (`id`, `matricule`, `nummat`, `tauxhoraire`) VALUES
(14, 'prof_1', 'matiere_1', 12000);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `matiere`
--
ALTER TABLE `matiere`
  ADD PRIMARY KEY (`nummat`);

--
-- Index pour la table `professeur`
--
ALTER TABLE `professeur`
  ADD PRIMARY KEY (`matricule`);

--
-- Index pour la table `volumehoraire`
--
ALTER TABLE `volumehoraire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matricule_prof` (`matricule`),
  ADD KEY `nummat_matiere` (`nummat`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `volumehoraire`
--
ALTER TABLE `volumehoraire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `volumehoraire`
--
ALTER TABLE `volumehoraire`
  ADD CONSTRAINT `matricule_prof` FOREIGN KEY (`matricule`) REFERENCES `professeur` (`matricule`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nummat_matiere` FOREIGN KEY (`nummat`) REFERENCES `matiere` (`nummat`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

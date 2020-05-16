/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Listage de la structure de la base pour genumsi
CREATE DATABASE IF NOT EXISTS `genumsi` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `genumsi`;

-- Listage de la structure de la table genumsi. domaines
CREATE TABLE IF NOT EXISTS `domaines` (
  `num_domaine` int(11) NOT NULL,
  `domaine` varchar(100) DEFAULT NULL,
  `niveau` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`num_domaine`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40000 ALTER TABLE `domaines` DISABLE KEYS */;
REPLACE INTO `domaines` (`num_domaine`, `domaine`, `niveau`) VALUES
	(1, 'Histoire de l\'informatique (Première)', '1'),
	(2, 'Représentation de données : Types et valeurs de base (Première)', '1'),
	(3, 'Représentation de données : Types construits (Première)', '1'),
	(4, 'Traitement de données en tables (Première)', '1'),
	(5, 'Interactions entre l’homme et la machine sur le Web (Première)', '1'),
	(6, 'Architectures matérielles et systèmes d’exploitation (Première)', '1'),
	(7, 'Langages et programmation (Première)', '1'),
	(8, 'Algorithmique (Première)', '1'),
	(11, 'Histoire de l\'informatique (Terminale)', 'T'),
	(12, 'Structures de données (Terminale)', 'T'),
	(13, 'Bases de données (Terminale)', 'T'),
	(14, 'Architectures matérielles, systèmes d’exploitation et réseaux (Terminale)', 'T'),
	(15, 'Langages et programmation (Terminale)', 'T'),
	(16, 'Algorithmique (Terminale)', 'T');
/*!40000 ALTER TABLE `domaines` ENABLE KEYS */;

-- Listage de la structure de la table genumsi. informations_admin
CREATE TABLE IF NOT EXISTS `informations_admin` (
  `visites` int(10) unsigned DEFAULT 0,
  `qcms` int(10) unsigned DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40000 ALTER TABLE `informations_admin` DISABLE KEYS */;
REPLACE INTO `informations_admin` (`visites`, `qcms`) VALUES
	(0, 0);
/*!40000 ALTER TABLE `informations_admin` ENABLE KEYS */;

-- Listage de la structure de la table genumsi. questions

CREATE TABLE IF NOT EXISTS `questions` (
  `num_question` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(1000) DEFAULT NULL,
  `reponseA` varchar(500) DEFAULT NULL,
  `reponseB` varchar(500) DEFAULT NULL,
  `reponseC` varchar(500) DEFAULT NULL,
  `reponseD` varchar(500) DEFAULT NULL,
  `bonne_reponse` varchar(1) DEFAULT NULL,
  `num_domaine` int(11) DEFAULT NULL,
  `num_sous_domaine` int(11) DEFAULT NULL,
  `niveau` char(50) DEFAULT NULL,
  `num_util` int(11) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `date_ajout` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `auteur` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`num_question`),
  KEY `FK_domaine` (`num_domaine`),
  KEY `FK_num_util` (`num_util`),
  KEY `FK_qu_ss_dom` (`num_sous_domaine`),
  CONSTRAINT `FK_domaine` FOREIGN KEY (`num_domaine`) REFERENCES `domaines` (`num_domaine`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_num_util` FOREIGN KEY (`num_util`) REFERENCES `utilisateurs` (`num_util`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_qu_ss_dom` FOREIGN KEY (`num_sous_domaine`) REFERENCES `sous_domaines` (`num_sous_domaine`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8;

-- Listage de la structure de la table genumsi. resultats
CREATE TABLE IF NOT EXISTS `resultats` (
  `id_reponse` int(11) NOT NULL AUTO_INCREMENT,
  `nom_eleve` varchar(50) DEFAULT NULL,
  `prenom_eleve` varchar(50) DEFAULT NULL,
  `classe_eleve` varchar(50) DEFAULT NULL,
  `num_prof` int(11) DEFAULT NULL,
  `note_qcm` float DEFAULT NULL,
  `cle_qcm` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_reponse`),
  KEY `FK_num_prof` (`num_prof`),
  CONSTRAINT `FK_num_prof` FOREIGN KEY (`num_prof`) REFERENCES `utilisateurs` (`num_util`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Listage de la structure de la table genumsi. sous_domaines
CREATE TABLE IF NOT EXISTS `sous_domaines` (
  `num_sous_domaine` int(11) NOT NULL AUTO_INCREMENT,
  `sous_domaine` varchar(500) DEFAULT NULL,
  `num_domaine` int(11) DEFAULT NULL,
  PRIMARY KEY (`num_sous_domaine`),
  KEY `FK_domaine` (`num_domaine`),
  CONSTRAINT `FK_sous_domaine` FOREIGN KEY (`num_domaine`) REFERENCES `domaines` (`num_domaine`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40000 ALTER TABLE `sous_domaines` DISABLE KEYS */;
REPLACE INTO `sous_domaines` (`num_sous_domaine`, `sous_domaine`, `num_domaine`) VALUES
	(1, 'Généralités', 1),
	(2, 'Entier positif', 2),
	(3, 'Entier relatif', 2),
	(4, 'Nombre flottant', 2),
	(5, 'Booléen', 2),
	(6, 'Texte', 2),
	(7, 'Autres', 2),
	(8, 'p-uplet', 3),
	(9, 'Dictionnaire,p-uplets nommés', 3),
	(10, 'Tableau indexé', 3),
	(11, 'Tableau en compréhension', 3),
	(12, 'Tableau bi-dimentionnel', 3),
	(13, 'Autres', 3),
	(14, 'Indexation de table', 4),
	(15, 'Recherche dans une table', 4),
	(16, 'Tri d\'une table', 4),
	(17, 'Fusion de tables', 4),
	(18, 'Autres', 4),
	(19, 'IHM Web', 5),
	(20, 'Interactions client-serveur', 5),
	(21, 'Formulaire Web', 5),
	(22, 'Autres', 5),
	(23, 'Architecture Von Neumann', 6),
	(24, 'Réseau informatique', 6),
	(25, 'Système d\'exploitation', 6),
	(26, 'Périphériques E/S', 6),
	(27, 'Autres', 6),
	(28, 'Constructions élémentaires', 7),
	(29, 'Diversité des langages', 7),
	(30, 'Spécification', 7),
	(31, 'Mise au point des programmes', 7),
	(32, 'Utilisation de Bibliothèques', 7),
	(33, 'Autres', 7),
	(34, 'Parcours séquentiel d\'un tableau', 8),
	(35, 'Tri par insertion/sélection', 8),
	(36, 'Algorithme KNN', 8),
	(37, 'Recherche Dichotomique', 8),
	(38, 'Algorithmes Gloutons', 8),
	(39, 'Autres', 8),
	(40, 'Généralités', 11),
	(41, 'Interface et implémentation', 12),
	(42, 'Programmation objet', 12),
	(43, 'Listes, piles, files, dictionnaires', 12),
	(44, 'Arbres', 12),
	(45, 'Graphes', 12),
	(46, 'Autres', 12),
	(47, 'Modèle relationel', 13),
	(48, 'BDD relationnelles', 13),
	(49, 'SGBD', 13),
	(50, 'SQL', 13),
	(51, 'Autres', 13),
	(52, 'Composants intégrés', 14),
	(53, 'Gestion des processus et ressources', 14),
	(54, 'Protocoles de routage', 14),
	(55, 'Sécurisation des communications', 14),
	(56, 'Autres', 14),
	(57, 'Calculabilité, Décidabilité', 15),
	(58, 'Récursivité', 15),
	(59, 'Modularité', 15),
	(60, 'Paradigmes', 15),
	(61, 'Mise au point', 15),
	(62, 'Autres', 15),
	(63, 'Arbres', 16),
	(64, 'Graphes', 16),
	(65, 'Diviser pour régner', 16),
	(66, 'Programmation dynamique', 16),
	(67, 'Recherche textuelle', 16),
	(68, 'Autres', 16);
/*!40000 ALTER TABLE `sous_domaines` ENABLE KEYS */;

-- Listage de la structure de la table genumsi. utilisateurs
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `num_util` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `mdp` varchar(255) DEFAULT NULL,
  `identifiant` varchar(50) DEFAULT NULL,
  `qualite` varchar(50) DEFAULT NULL,
  `id_utilisateur` varchar(50) DEFAULT NULL,
  `mail` varchar(50) DEFAULT NULL,
  `derniere_connexion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `verification` char(255) DEFAULT NULL,
  PRIMARY KEY (`num_util`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Listage des données de la table genumsi.utilisateurs : ~1 rows (environ)
/*!40000 ALTER TABLE `utilisateurs` DISABLE KEYS */;
INSERT INTO `utilisateurs` (`num_util`, `nom`, `prenom`, `mdp`, `identifiant`, `qualite`, `id_utilisateur`, `mail`, `derniere_connexion`, `verification`) VALUES
	(1, 'Prof', 'NSI', '$2y$10$EXsUOZSW32bsIaINFXAg9.U8QWagL6un4JOIT3NUiIof/CIAfKVKW', 'prof', 'profnsi', 'profnsi', 'profnsi@ac-rennes.fr', '2019-01-01 00:00:00', 'ok');
/*!40000 ALTER TABLE `utilisateurs` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

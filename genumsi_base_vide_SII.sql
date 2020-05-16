/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE=`NO_AUTO_VALUE_ON_ZERO` */;


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
	(1, "Ingénierie Système","1 CPGE"),
	(2, "Systèmes linéaires continus invariants", "1 CPGE"),
	(3, "Systèmes combinatoires", "1 CPGE"),
	(4, "Système à événements discrets", "1 CPGE"),
	(5, "Ingénierie électrique en régime continu", "1 CPGE"),
	(6, "Fonction Acquérir", "1 CPGE"),
	(7, "Hacheurs - MCC", "1 CPGE"),
	(8, "Cinématique", "1 CPGE"),
	(9, "Statique", "1 CPGE"),
	(10, "Produits - Matériaux - Procédés", "1 CPGE"),	
	(11, "Outils de calcul", "1 CPGE"),
	(12, "Algorithmique", "1 CPGE"),
	(13, "Ingénierie numérique", "1 CPGE"),
	(14, "Bases de données", "1 CPGE"),
	(15, "Conception des systèmes", "1 CPGE"),
	(16, "Ingénierie électrique en régime sinusoïdal", "2 CPGE"),
	(17, "Correction des systèmes asservis", "2 CPGE"),
	(18, "Chaînes de solides", "2 CPGE"),
	(19, "Dynamique", "2 CPGE"),
	(20, "Énergétique", "2 CPGE"),
	(21, "Résistance des matériaux", "2 CPGE"),
	(22, "Conception des systèmes", "2 CPGE");
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
	(1, "Diagramme des exigences", 1),
	(2, "Diagramme des blocs internes", 1),
	(3, "Diagramme de définition de blocs", 1),
	(4, "Chaine fonctionnelle", 1),
	(5, "Performance des systèmes", 2),
	(6, "Transformée de Laplace", 2),
	(7, "Systèmes du premier ordre", 2),
	(8, "Systèmes du deuxième ordre", 2),
	(9, "Réponses fréquentielles", 2),
	(10, "Diagrammes d\`états", 4),
	(11, "Chronogrammes", 4);
/*!40000 ALTER TABLE `sous_domaines` ENABLE KEYS */;

-- Listage de la structure de la table genumsi. utilisateurs
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `nom` varchar(50) DEFAULT NULL,
  `num_util` int(11) NOT NULL AUTO_INCREMENT,
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
	(1, `Prof`, `NSI`, `$2y$10$EXsUOZSW32bsIaINFXAg9.U8QWagL6un4JOIT3NUiIof/CIAfKVKW`, `prof`, `profnsi`, `profnsi`, `profnsi@ac-rennes.fr`, `2019-01-01 00:00:00`, `ok`);
/*!40000 ALTER TABLE `utilisateurs` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, ``) */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

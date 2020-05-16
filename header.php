<?php include("config.php"); ?>
<header class="header">
    <div id="header-logo-titre">
        <a href='check-accueil.php'><img src="image/logo-genumsi.png" alt="logo GeNumSI" id='logo-header' /></a>
        <h1 id='h1-header'>Créez votre QCM de <?= $GENUMMATIERE ?> avec <?= $GENUMNAME ?> !</h1>
    </div>
    <?php
    if (isset($bdd) and isset($_SESSION['connecte']) and $_SESSION['connecte'] == true) :
        $req = $bdd->prepare('SELECT prenom, nom, identifiant, mail FROM utilisateurs WHERE num_util = ?');
        $req->execute(array($_SESSION['num_util']));
        $coordonnees = $req->fetch();
        ?>
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src='image/logo_id.png' width='20'><?= $coordonnees['identifiant'] ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                    <div class="dropdown-item"><?= $coordonnees['nom'] ?> <?= $coordonnees['prenom'] ?></div>
                    <div class="dropdown-item"><?= $coordonnees['mail'] ?></div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="deconnexion.php">Déconnexion</a>

                </div>
            </li>
        </ul>

    <?php
    endif;
    ?>
   </header>
<div id="conteneur-general">
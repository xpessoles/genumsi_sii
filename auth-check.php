<?php

session_start();

if (!isset($_POST['mdp']) or !isset($_POST['login']) or $_POST['mdp'] == NULL or $_POST['login'] == NULL) {

    echo 'Vous n\'avez pas fourni toutes les infos';
} else {

    /* Connexion à la base de données */
    include("connexionbdd.php");

    /* Récupération des infos de l'élève */
    $req = $bdd->prepare('SELECT * FROM utilisateurs WHERE identifiant = ?');
    $req->execute(array($_POST['login']));
    $utilisateur = $req->fetch();

    /* On teste si le mdp proposé correspond à celui enregistré et si celui-ci est bien renseigné */
    /* Cas où cela ne focntionne pas */
    if (!isset($utilisateur) or $utilisateur['verification'] != 'ok' or !password_verify($_POST['mdp'], $utilisateur['mdp'])) {
        echo 'Vos informations ne correspondent pas';
    } else {

        $_SESSION['num_util'] = $utilisateur['num_util'];
        $_SESSION['connecte'] = true;
        $_SESSION['qualite'] = $utilisateur['qualite'];
        $req = $bdd->prepare('UPDATE utilisateurs SET derniere_connexion = NOW() WHERE identifiant = ?');
        $req->execute(array($_POST['login']));

        if ($_SESSION['qualite'] == "prof") {
            $req_increment = $bdd->prepare('UPDATE informations_admin SET visites = visites + 1 WHERE 1');
            $req_increment->execute();
        }

        echo "success";
    }
}

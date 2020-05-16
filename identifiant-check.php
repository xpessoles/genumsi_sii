<?php

session_start();

if (!isset($_POST['identifiant']) or $_POST['identifiant'] == NULL) {

    echo 'Veuillez-saisir un identifiant';
} else {

    /* Connexion à la base de données */
    include("connexionbdd.php");

    /* Récupération des infos de l'utilisateur */
    $req = $bdd->prepare('SELECT count(*) as nb FROM utilisateurs WHERE identifiant = ?');
    $req->execute(array($_POST['identifiant']));
    $utilisateur = $req->fetch();

    /* On teste si le login existe déjà */
    /* Cas où cela ne focntionne pas */
    if ($utilisateur['nb'] > 0) {
        echo "fail";
    } else {
        echo "success";
    }
}

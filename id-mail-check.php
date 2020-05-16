<?php

session_start();

if (!isset($_POST['identifiant']) or $_POST['identifiant'] == NULL or !isset($_POST['mail']) or $_POST['mail'] == NULL) {

    echo 'Veuillez saisir toutes les informations';
} else {

    /* Connexion à la base de données */
    include("connexionbdd.php");

    /* Récupération des infos de l'utilisateur */
    $req = $bdd->prepare('SELECT num_util, mail FROM utilisateurs WHERE identifiant = ?');
    $req->execute(array($_POST['identifiant']));
    $mail = $req->fetch();

    /* Cas où cela ne focntionne pas */
    if ($mail['mail'] == $_POST['mail']) {
        $_SESSION['num_util']  = $mail['num_util'];
        echo "success";
    } else {
        echo "L'email saisi ne correspond pas à celui saisi lors de la création du compte";
    }
}

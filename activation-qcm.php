<?php

session_start();

if (!isset($_POST['hash']) or !isset($_POST['hash'])) {

    echo 'fail';
} else {

    /* Connexion à la base de données */
    include("connexionbdd.php");

    /* Récupération des infos de l'élève */
    $req = $bdd->prepare('UPDATE qcms SET actif = 1 WHERE hash_qcm = ?');
    $req->execute(array($_POST['hash']));

    echo "success";
}
?>
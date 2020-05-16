<?php

session_start();

if (!isset($_POST['num_question']) or is_null($_POST['num_question'])) {

    echo '<p>Vous n\'avez pas fourni toutes les infos</p>';
} else {


    /* Connexion à la base de données */
    include("connexionbdd.php");

    /* Récupération des infos de l'élève */
    $req = $bdd->prepare('DELETE FROM questions WHERE num_question = ?');
    $req->execute(array($_POST['num_question']));

    echo "success";
}

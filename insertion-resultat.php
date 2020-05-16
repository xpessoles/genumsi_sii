<?php

session_start();

if (
    !isset($_POST['note_qcm']) or is_null($_POST['note_qcm']) or
    !isset($_POST['id_reponse']) or is_null($_POST['id_reponse'] or
    !isset($_POST['reponses']) or is_null($_POST['reponses'])
    )
) {

    echo "loupé";
    die();
} else {

    /* Connexion à la base de données */
    include("connexionbdd.php");

    /* Insertion du résultat */
    $req = $bdd->prepare('UPDATE resultats SET note_qcm = ?, reponses = ? WHERE id_reponse = ?');
    $req->execute(array(
        $_POST['note_qcm'],
        $_POST['reponses'],
        $_POST['id_reponse']
    ));
}

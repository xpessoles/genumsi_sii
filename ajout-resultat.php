<?php
if (
    !isset($_POST['nom_eleve']) or is_null($_POST['nom_eleve']) or
    !isset($_POST['prenom_eleve']) or is_null($_POST['prenom_eleve']) or
    !isset($_POST['classe_eleve']) or is_null($_POST['classe_eleve']) or
    !isset($_POST['num_prof']) or is_null($_POST['num_prof']) or
    !isset($_POST['cle_qcm']) or is_null($_POST['cle_qcm'])
) {
    echo "fail";
} else {

    /* Connexion à la base de données */
    include("connexionbdd.php");

    /* Insertion du résultat */
    /* clé unique pour identifier ce résultat */
    $unique = "qcmencours" . md5(microtime(TRUE) * 100000);
    $req = $bdd->prepare('INSERT INTO resultats (nom_eleve, prenom_eleve, classe_eleve, num_prof, note_qcm, cle_qcm, reponses) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $req->execute(array(
        $_POST['nom_eleve'],
        $_POST['prenom_eleve'],
        $_POST['classe_eleve'],
        base64_decode(urldecode($_POST['num_prof'])),
        -1,
        base64_decode(urldecode($_POST['cle_qcm'])),
        $unique
    ));

    $req_id = $bdd->prepare('SELECT id_reponse AS id FROM resultats WHERE nom_eleve= ? AND num_prof = ? AND reponses = ? LIMIT 1');
    $req_id->execute((array(
        $_POST['nom_eleve'],
        base64_decode(urldecode($_POST['num_prof'])),
        $unique
    )));
    $id = $req_id->fetch();

    echo $id['id'];
}

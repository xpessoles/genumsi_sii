<?php
session_start();

if (empty($_SESSION) or $_SESSION['connecte'] != true) :
    echo "Vous ne devriez pas être ici : <a href='index.php'>Retour</a>";
else :

    include('connexionbdd.php');

    // L'utilisateur actuel est-il un admin ?
    $req_admin = $bdd->prepare('SELECT qualite FROM utilisateurs WHERE num_util = ?');
    $req_admin->execute(array($_SESSION['num_util']));
    $admin = $req_admin->fetch();

    $resultats = array();

    if ($admin['qualite'] == 'prof') {
        $resultats = $bdd->prepare('SELECT date, nom_eleve, cle_qcm, note_qcm, reponses
            FROM resultats
            WHERE num_prof = ?');
        $resultats->execute(array($_SESSION['num_util']));
    } else if ($admin['qualite'] == 'admin') {
        $resultats = $bdd->prepare('SELECT date, num_prof, nom_eleve, cle_qcm, note_qcm, reponses
            FROM resultats
            WHERE 1');
        $resultats->execute();
    }

    $resultats = $resultats->fetchAll(PDO::FETCH_ASSOC);

    $filename = 'export_resultats.csv';

    // Création du fichier
    $file = fopen($filename, "w");

    if ($admin['qualite'] == 'prof') {
        fwrite($file, "Date,Nom,Clé du QCM,Note,Réponses\n");
    } else if ($admin['qualite'] == 'admin') {
        fwrite($file, "Date,ID Prof,Nom,Clé du QCM,Note,Réponses\n");
    }

    foreach ($resultats as $resultat) {
        fputcsv($file, $resultat);
    }

    fclose($file);

    // Téléchargement
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=export_resultats.csv");
    header("Content-Type: text/plain; ");

    flush();
    readfile($filename);

    // On efface le fichier côté serveur
    unlink($filename);
    exit();

endif;

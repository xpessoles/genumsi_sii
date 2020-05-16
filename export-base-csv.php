<?php
session_start();

if (empty($_SESSION) or $_SESSION['connecte'] != true) :
    echo "Vous ne devriez pas être ici : <a href='index.php'>Retour</a>";
else :

    include('connexionbdd.php');

    $texte_req = 'SELECT num_question, question, reponseA, reponseB, reponseC, reponseD, bonne_reponse, domaines.domaine, sous_domaines.sous_domaine, image, utilisateurs.identifiant
        FROM questions
        INNER JOIN domaines ON questions.num_domaine = domaines.num_domaine
        INNER JOIN sous_domaines ON questions.num_sous_domaine = sous_domaines.num_sous_domaine
        INNER JOIN utilisateurs ON questions.num_util = utilisateurs.num_util
        ORDER BY questions.num_question';
    $questions = $bdd->prepare($texte_req);
    $questions->execute();

    $filename = 'export_questions.csv';

    $total_output = "";

    // Création du fichier
    $file = fopen($filename, "w");

    fwrite($file, "numero de la question, question, reponseA, reponseB, reponseC, reponseD, bonne_reponse, domaine, sous_domaine, image, auteur\n");

    while ($row = $questions->fetch(PDO::FETCH_ASSOC)) {
        foreach ($row as $key => $value) {
            $row[$key] = str_replace('"', "'", $row[$key]);
            $row[$key] = str_replace("\r\n", "<br>", $row[$key]);
            $row[$key] = str_replace(chr(9), "    ", $row[$key]);
        }
        $output = '"' . implode('","', $row) . '"' . "\n";
        $output = str_replace('""', "NULL", $output);
        $total_output .= $output;
    }
    fwrite($file, $total_output);

    fclose($file);

    // Téléchargement
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=export_questions_csv.csv");
    header("Content-Type: text/plain; ");

    flush();
    readfile($filename);

    // On efface le fichier côté serveur
    unlink($filename);
    exit();

endif;

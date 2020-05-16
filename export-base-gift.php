<?php
    session_start();

    if (empty($_SESSION) or $_SESSION['connecte'] != true) :
        echo "Vous ne devriez pas être ici : <a href='index.php'>Retour</a>";
    else :

        include('connexionbdd.php');

        function string_replace($s) {
            $texte = str_replace("~", "\~", $s);
            $texte = str_replace(":", "\:", $texte);
            $texte = str_replace("=", "\=", $texte);
            $texte = str_replace("#", "\#", $texte);
            $texte = str_replace("{", "\{", $texte);
            $texte = str_replace("}", "\}", $texte);
            $texte = str_replace("->", "&rarr;", $texte);
            $texte = trim($texte);

            return $texte;
        }

        $texte_req = 'SELECT *
        FROM questions
        INNER JOIN domaines ON questions.num_domaine = domaines.num_domaine
        INNER JOIN sous_domaines ON questions.num_sous_domaine = sous_domaines.num_sous_domaine
        ORDER BY questions.num_question';
        $questions = $bdd -> prepare($texte_req);
        $questions -> execute();

        $filename = 'export_questions.txt';

        // Création du fichier
        $file = fopen($filename,"w");

        foreach ($questions as $question){
            fwrite($file,"\n");
            fwrite($file, "// Numéro de la question : " . $question['num_question'] . "\n");
            fwrite($file, "// Domaine : " . $question['domaine'] . "\n");
            fwrite($file, "// Sous-domaine : " . $question['sous_domaine'] . "\n");
            
            
            $texte_question = string_replace($question['question']);
            $texte_repA = string_replace($question['reponseA']);
            $texte_repB = string_replace($question['reponseB']);
            $texte_repC = string_replace($question['reponseC']);
            $texte_repD = string_replace($question['reponseD']);
            
            if ($question['image'] == NULL) {
                fwrite($file,"[html]" . $texte_question . " {\n");
                } else {
                    fwrite($file,"[html]" . $texte_question . " ");
                    $texte_image = string_replace($question['image']);
                    fwrite($file, "<img class\='img-question' src\='image_questions/" . $texte_image . "'> {\n");
            }

            if ($question['bonne_reponse'] == 'A') {
                fwrite($file,"=[html] " . $texte_repA . "\n");
            } else {
                fwrite($file,"~[html] " . $texte_repA . "\n");
            }

            if ($question['bonne_reponse'] == 'B') {
                fwrite($file,"=[html] " . $texte_repB . "\n");
            } else {
                fwrite($file,"~[html] " . $texte_repB . "\n");
            }

            if ($question['bonne_reponse'] == 'C') {
                fwrite($file,"=[html] " . $texte_repC . "\n");
            } else {
                fwrite($file,"~[html] " . $texte_repC . "\n");
            }

            if ($question['bonne_reponse'] == 'D') {
                fwrite($file,"=[html] " . $texte_repD . "\n");
            } else {
                fwrite($file,"~[html] " . $texte_repD . "\n");
            }

            fwrite($file,"}\n");


        }

        fclose($file);

        // Téléchargement
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=export_questions_gift.txt");
        header("Content-Type: text/plain; ");

        flush();
        readfile($filename);

        // On efface le fichier côté serveur
        unlink($filename);
        exit();

    endif;

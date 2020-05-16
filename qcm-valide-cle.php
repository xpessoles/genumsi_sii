<?php session_start();
include("config.php");
include("head.php");

if (empty($_SESSION) or $_SESSION['connecte'] != true) :
    include("header.php");
    echo "Vous ne devriez pas être ici : <a href='index.php'>Retour</a>";
else :

    include("connexionbdd.php");
    include("header.php");
    include("nav.php");
    include("phpqrcode/qrlib.php");

    include("url-qcm.php");

    $req_increment = $bdd->prepare('UPDATE informations_admin SET qcms = qcms + 1 WHERE 1');
    $req_increment->execute();

    if (preg_match("/([0-9]+;)*[0-9]+/", $_POST['cle']) != 1) {

        echo "<br><p>Votre clé est invalide</p>";
        echo "<p>Veuillez réessayyer en tapant une clé du type <code>12</code> ou <code>12;31</code></p>";
        echo "<a href='selection-parliste.php'>Sélection</a>";
    } else {
        $num_questions = explode(';', $_POST['cle']);
        $tab_requete = "";
        $tab_requete_tiret = "";
        foreach ($num_questions as $num) {
            $tab_requete = $tab_requete . '(' . $num . "),";
            $tab_requete_tiret = $tab_requete_tiret . $num . "-";
        }

        $tab_requete = substr($tab_requete, 0, -1);
        $tab_requete_tiret = substr($tab_requete_tiret, 0, -1);

        // Récupération de toutes les questions qui ne seraient pas dans la base.
        //Pour cela on utilise une table temporaire qui contient la liste des questions du QCM : $tab_resuete
        $texte_req = 'CREATE TEMPORARY TABLE myquestionslist (idq INT)';
        $questions = $bdd->prepare($texte_req);
        $questions->execute();

        $texte_req = 'INSERT INTO myquestionslist (idq) VALUES ' . $tab_requete;
        $questions = $bdd->prepare($texte_req);
        $questions->execute();

        // Récupération de toutes les questions qui ne sont pas dans la base.
        $texte_req = 'SELECT idq FROM myquestionslist LEFT JOIN questions ON questions.num_question = idq WHERE questions.num_question IS NULL';
        $questions = $bdd->prepare($texte_req);
        $questions->execute();
        $listerrors = $questions->fetchAll();

        if (count($listerrors) > 0) {
            echo "<br><p>Votre clé comporte des questions qui ne sont pas dans la base : </p> <p><strong>";
            $txt = "";
            foreach ($listerrors as $numq) {
                $txt .= $numq['idq'] . ' - ';
            }
            $txt = substr($txt, 0, -3);
            echo $txt;
?>
            </strong></p>
            <form method="post" action="selection-parliste.php">
                <input type="hidden" name="cle" value="<?= $_POST['cle'] ?>">
                <button class='btn btn-info' type='submit'>Retour vers la sélection</button>
            </form>
            <?php
        } else {

            $num_questions_unique = array_unique($num_questions);

            if (count($num_questions) - count($num_questions_unique)) {
                echo "<p>Votre clé comporte les doublons suivants : </p><p><strong>";

                $occurence_array = array();
                foreach ($num_questions as $key => $value) {
                    if (isset($occurence_array[$value]))
                        $occurence_array[$value] += 1;
                    else
                        $occurence_array[$value] = 1;
                }
                $txt = "";
                foreach ($occurence_array as $num_question => $n) {
                    if ($n > 1) $txt .= $num_question . " - ";
                }
                $txt = substr($txt, 0, -3);
                echo $txt;
            ?>
                </strong></p>
                <form method="post" action="selection-parliste.php">
                    <input type="hidden" name="cle" value="<?= $_POST['cle'] ?>">
                    <button class='btn btn-info' type='submit'>Retour vers la sélection</button>
                </form>
            <?php

            } else {

                $cle64 = base64_encode($_POST['cle']);
            ?>

                <h1 class='h1-qcm'>Validation du QCM</h1>

                <p>Votre QCM comporte les questions de numéros suivants (dans la base données) :</p>

                <ul>
                    <li><?php echo $tab_requete_tiret ?></li>
                </ul>

                <h2>Voici un aperçu de celui-ci. Afin d'alléger la page, les réponses ne sont pas affichées.</h2>

                <?php
                //=====================================QMC inséré
                // Création de la chaîne de caractère (num_question, num_question...) nécessaire
                // à la requête SQL
                $tab_requete = "(";
                foreach ($num_questions as $num) {
                    $tab_requete = $tab_requete . $num . ",";
                }

                $tab_requete = substr($tab_requete, 0, -1) . ")";


                // Récupération de toutes les questions correspondants aux numéros du GET
                $texte_req = 'SELECT * FROM questions INNER JOIN domaines ON questions.num_domaine = domaines.num_domaine WHERE num_question IN ' . $tab_requete . ' ORDER BY domaines.num_domaine, num_question ';
                $questions = $bdd->prepare($texte_req);
                $questions->execute();

                $domaine_precedent = '';

                $numero_q = 0;

                ?>

                <section class='qcm'>
                    <h1 class='h1-qcm'>QCM de <?= $GENUMNAME ?></h1>

                    <?php
                    // Génération du code html du QCM
                    while ($question = $questions->fetch()) :
                        if ($question['domaine'] != $domaine_precedent) :

                    ?>
                            <h2 class='h2-domaine'><?= $question['domaine'] ?></h2>
                        <?php
                            $domaine_precedent = $question['domaine'];
                        endif;
                        $numero_q++;
                        ?>
                        <li>
                            <b>Question n°<?= $numero_q ?> :</b>

                            <?= $question['question'] ?>

                            <?php
                            if (!is_null($question['image'])) :
                            ?>

                                <img class='img-question' src="image_questions/<?= $question['image'] ?>">

                            <?php endif; ?>


                            <div class='reponse-qcm-valide'>
                                <p class='reponses-affiche'>Réponses :</p>
                                <ul type='A' class='reponses-cachees'>
                                    <li class='reponse'><?= $question['reponseA'] ?></li>
                                    <li class='reponse'><?= $question['reponseB'] ?></li>
                                    <li class='reponse'><?= $question['reponseC'] ?></li>
                                    <li class='reponse'><?= $question['reponseD'] ?></li>
                                </ul>
                            </div>
                        </li>
                        <br>
                    <?php endwhile ?>

                </section>

                <?php include("validation-qcm.php") ?>


<?php
            }
        }
    }
endif;

include("footer.php")
?>

</body>
<script>
    $("document").ready(function() {
        render_md_math();
    })
</script>

</html>
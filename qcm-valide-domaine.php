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

    include "phpqrcode/qrlib.php";

    include("url-qcm.php");


    $req_increment = $bdd->prepare('UPDATE informations_admin SET qcms = qcms + 1 WHERE 1');
    $req_increment->execute();
    // Tableau contenant le nombre questions de chaque domaine (indexé par le domaine)
    $tab_questions_domaine = array();
    $i = 1;
    foreach ($_POST as $key => $quest_par_theme) {
        $tab_questions_domaine[substr($key,  5)] = $quest_par_theme;
        $i++;
    }

    $cle = '';

    foreach ($tab_questions_domaine as $key => $nb) {
        if ($nb > 0) {
            $req_quest = $bdd->prepare("SELECT * FROM questions WHERE num_domaine = ?");
            $req_quest->execute(array($key));

            $tab = $req_quest->fetchAll();

            if ($nb == 1) {
                $cles_aleatoires = array(array_rand($tab, $nb));
            } else if ($nb > 1) {
                $cles_aleatoires = array_rand($tab, $nb);
            }

            foreach ($cles_aleatoires as $cle_aleatoire) {
                $cle .= $tab[$cle_aleatoire]['num_question'] . ';';
            }
        }
    }
    $cle = substr($cle, 0, -1);

    $num_questions = explode(';', $cle);

    $cle64 = base64_encode($cle);

?>

    <h1 class='h1-qcm'>Validation du QCM</h1>

    <p>Votre QCM comporte les questions numéros (dans la base données) :</p>

    <ul>

        <?php foreach ($num_questions as $n) : ?>
            <li><?= $n ?></li>

        <?php endforeach ?>

    </ul>

    <div class='choix-qcm'>

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
            <h1 class='h1-qcm'>QCM avec <?= $GENUMNAME ?></h1>

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

        <?php ?>
        <?php include("validation-qcm.php") ?>

    </div>
<?php
endif;

include("footer.php")
?>

</body>
<script>
    $('document').ready(function() {
        render_md_math()
    })
</script>

</html>
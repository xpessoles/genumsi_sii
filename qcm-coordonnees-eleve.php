<?php
 include("config.php");
 include("head.php") 
?>

<div id="conteneur-general">

    <?php
    // Connexion à la base de données avec PDO
    include("connexionbdd.php");

    // Récupération de la clé du QCM (au format num_question;num_question...)
    $cle = base64_decode($_GET['cle']);

    // Création de la chaîne de caractère (num_question, num_question...) nécessaire
    // à la requête SQL
    $num_questions = explode(';', $cle);
    $ordres_rep = array();

    $tab_requete = "(";
    $rang = 0;
    foreach ($num_questions as $num) {
        $tab_requete = $tab_requete . $num . ",";
        $ordres_rep[$rang] = "ABCD";
        $rang++;
    }
    $tab_requete = substr($tab_requete, 0, -1) . ")";

    ?>

    <section class='qcm'>
        <h1 class='h1-qcm'>QCM avec <?= $GENUMNAME ?></h1>
        <h4>
            Une bonne réponse rapporte 3 points. Une mauvaise retire 1 point. Une absence de réponse n'est pas pénalisée.
        </h4>
        <form method='POST' action='correction.php'>
            <table>
                <tr>
                    <td>Nom : </td>
                    <td><input type="text" name="nom_eleve" size="30" required></td>
                </tr>
                <tr>
                    <td>Prénom : </td>
                    <td><input type="text" name="prenom_eleve" size="30" required></td>
                </tr>
                <tr>
                    <td>Classe : </td>
                    <td><input type="text" name="classe_eleve" size="10" required></td>
                </tr>
            </table>

            <?php
            // Récupération de toutes les domaines correspondants aux questions du GET
            $texte_req = 'SELECT questions.num_domaine FROM questions INNER JOIN domaines ON questions.num_domaine = domaines.num_domaine WHERE num_question IN ' . $tab_requete . '  GROUP BY domaines.num_domaine';
            $domaines = $bdd->prepare($texte_req);
            $domaines->execute();

            $domaines = $domaines->fetchAll(PDO::FETCH_ASSOC);

            $domaine_precedent = '';

            $numero_q = 1;

            $cle = '';
            $num_q = 0;

            foreach ($domaines as $domaine) :


                // Récupération de toutes les questions correspondants aux numéros du GET
                $texte_req = 'SELECT * FROM questions INNER JOIN domaines ON questions.num_domaine = domaines.num_domaine  WHERE num_question IN ' . $tab_requete . ' AND questions.num_domaine = ? ORDER BY num_question';
                $questions = $bdd->prepare($texte_req);
                $questions->execute(array($domaine['num_domaine']));

                $questions = $questions->fetchAll();

                shuffle($questions);

                foreach ($questions as $question) :
                    if ($question['domaine'] != $domaine_precedent) :
                        ?>
                        <div class='col-md-12'>
                            <h2 class='h2-domaine'><?= $question['domaine'] ?></h2>
                        </div>
            <?php

                        $domaine_precedent = $question['domaine'];
                    endif;

                    $cle .= $question['num_question'] . ';';
                    $ordres_rep[$num_q] = str_shuffle($ordres_rep[$num_q]);
                    $num_q++;

                    include("question.php");
                endforeach;
            endforeach;
            $cle = substr($cle, 0, -1);
            ?>

            <input type="hidden" value="<?= $_GET['cle'] ?>" name="cle" />
            <input type="hidden" value="<?= $_GET['p'] ?>" name="p" />
            <button class='btn btn-info' id='envoi-reponse'>Envoyer les réponses</button>

        </form>

    </section>

</div>

</body>
<script>

    $('document').ready(function(){
        render_md_math()
    })
</script>

</html>
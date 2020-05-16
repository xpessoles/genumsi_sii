<?php
include("config.php");
include("head-print.php");
?>

<div id="conteneur-general">

    <?php
    // Connexion à la base de données avec PDO
    include("connexionbdd.php");

    // Récupération de la clé du QCM (au format num_question;num_question...)
    $cle = base64_decode($_GET['cle']);
    $points_bonne_reponse = base64_decode($_GET['b']);
    $points_mauvaise_reponse = base64_decode($_GET['m']);

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
        <h1 class='qcm-title'>QCM avec <?= $GENUMNAME ?></h1>
        <br />
        <p class='qcm-student-identification'>
            Nom : &lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;
            &nbsp;&nbsp;&nbsp;Prénom : &lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;&lowbar;
            &nbsp;&nbsp;&nbsp;Classe : &lowbar;&lowbar;&lowbar;&lowbar;&lowbar;
        </p>
        <p class='qcm-notation'>Une bonne réponse rapporte <?= $points_bonne_reponse ?> point(s). Une mauvaise retire <?= $points_mauvaise_reponse ?> point(s).</p>
        <p class='qcm-notation'> Une absence de réponse n'est pas pénalisée.</p>

        <?php
        // Récupération de toutes les domaines correspondants aux questions du GET
        $texte_req = 'SELECT questions.num_domaine FROM questions INNER JOIN domaines ON questions.num_domaine = domaines.num_domaine WHERE num_question IN ' . $tab_requete . '  GROUP BY domaines.num_domaine';
        $domaines = $bdd->prepare($texte_req);
        $domaines->execute();

        $domaines = $domaines->fetchAll(PDO::FETCH_ASSOC);

        $domaine_precedent = '';

        $numero_q = 1;

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
                    <h2 class='domaine-name'><?= $question['domaine'] ?></h2>

        <?php

                    $domaine_precedent = $question['domaine'];
                endif;

                $ordres_rep[$num_q] = str_shuffle($ordres_rep[$num_q]);
                $num_q++;

                include("question-print.php");
            endforeach;
        endforeach;
        ?>

    </section>

</div>

<div class="footer">
    <p>Généré par GeNumSI - CC-BY-SA </p>
</div>

</body>

<script>
    $('document').ready(function() {
        render_md_math()
    })
</script>

</html>
<?php session_start();

include("head.php");
include("header.php");

//if (empty($_SESSION) or $_SESSION['connecte'] != true) :
//    echo "Vous ne devriez pas être ici : <a href='index.php'>Retour</a>";
//else :
include("connexionbdd.php");

?>

<?php
// Connexion à la base de données avec PDO

// Récupération de la clé du QCM (au format num_question;num_question...)
$cle = base64_decode($_GET['cle']);
// Création de la chaîne de caractère (num_question, num_question...) nécessaire
// à la requête SQL
$num_questions = explode(';', $cle);

$tab_requete = "(";
foreach ($num_questions as $num) {
    $tab_requete = $tab_requete . $num . ",";
}
$tab_requete = substr($tab_requete, 0, strlen($tab_requete) - 1) . ")";

// Récupération de toutes les questions correspondants aux numéros du GET
$texte_req = 'SELECT num_question, question, reponseA, reponseB, reponseC, reponseD, bonne_reponse, image FROM questions INNER JOIN domaines ON questions.num_domaine = domaines.num_domaine  WHERE num_question IN ' . $tab_requete . '  ORDER BY domaines.num_domaine, num_question ';
$questions = $bdd->prepare($texte_req);
$questions->execute();

$reponses = $questions->fetchAll();

?>
<section class='qcm'>
    <h1 class='h1-qcm'>Corrigé</h1>

    <ul>
        <?php
        $numero_q = 1;
        foreach ($reponses as $reponse) :
        ?>
            <li>
                <b>Question n°<?= $numero_q ?>:</b>
                <?php $numero_q++; ?>
                <?= $reponse['question'] ?>

                <?php
                if (!is_null($reponse['image'])) :
                ?>

                    <img class='img-question' src="image_questions/<?= $reponse['image'] ?>">

                <?php endif; ?>

                <h3>Bonne réponse :</h3>

                <div class='input-group'>
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="radio" name="reponse<?= $reponse['bonne_reponse'] ?>" checked disabled>
                        </div>
                    </div>
                    <span class='form-control'><?= $reponse['reponse' . $reponse['bonne_reponse']] ?></span>
                </div>

                <br>
            </li>
        <?php
        endforeach;
        ?>
    </ul>
</section>

<?php
//endif;
include("footer.php")
?>

</body>
<script>
    $('document').ready(function() {
        render_md_math()
    })
</script>

</html>
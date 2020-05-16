<?php session_start();

include("head.php");


if (empty($_SESSION) or $_SESSION['connecte'] != true) :
    echo "Vous ne devriez pas être ici : <a href='index.php'>Retour</a>";
else :
    include('connexionbdd.php');
    include("header.php");
    include("nav.php");


    /* Ajout de la question dans la base */
    $dom_ss_dom = explode('-', $_POST['num_domaine_sous_domaine']);

    require_once 'htmlpurifier-4.12.0-lite/library/HTMLPurifier.auto.php';
    
    $purifier = new HTMLPurifier();

    $req_ajout = $bdd->prepare("INSERT INTO questions (question, reponseA, reponseB, reponseC, reponseD, bonne_reponse, num_domaine, num_sous_domaine, num_util) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $req_ajout->execute(array(
        $purifier->purify($_POST['question']),
        $purifier->purify($_POST['reponseA']),
        $purifier->purify($_POST['reponseB']),
        $purifier->purify($_POST['reponseC']),
        $purifier->purify($_POST['reponseD']),
        $_POST['bonne_reponse'],
        $dom_ss_dom[0],
        $dom_ss_dom[1],
        $_SESSION['num_util']
    ));

    $statut = "";
    /* Récupération du numéro de la question pour nommer l'image*/
    $req_id = $bdd->prepare("SELECT num_question FROM questions WHERE num_util = ? ORDER BY date_ajout DESC LIMIT 1");
    $req_id->execute(array($_SESSION['num_util']));

    $id = $req_id->fetch();

    /* Chargement de l'image si son nom est non vide */
    if ($_FILES['file']['name'] != "") {

        $filename = $id['num_question'] . "_" . $_FILES['file']['name'];

        /* Emplacement du fichier */
        $location = "image_questions/" . $filename;
        $imageFileType = pathinfo($location, PATHINFO_EXTENSION);

        /* Confirmation du type d'image */
        $valid_extensions = array("jpg", "jpeg", "png", "gif");

        if (!in_array(strtolower($imageFileType), $valid_extensions)) {
            $statut = "erreur_format";
        } else if ($_FILES['file']['size'] > 300000) {
            $statut = 'erreur_taille';
        } else if (strlen($filename) > 50) {
            $statut = 'erreur_nom';
        } else {
            /* Chargement de l'image */
            if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
                $statut = 'success';
                $sql = "UPDATE questions SET image = '" . $filename . "' WHERE num_question = " . $id['num_question'];
                $update_img = $bdd->prepare($sql);
                $update_img->execute();
            } else {
                $statut = 'erreur_chargement';
            }
        }
        //echo $statut;
    }

    ?>

    <h1 class='h1-qcm'>Confirmation de l'ajout</h1>

    <p>Votre question a été ajoutée dans la base à la référence #<?= $id['num_question'] ?></p>

    <?php
        if ($statut == 'erreur_format') {
            echo "<p>Par contre le format de l'image était incorrect<p>";
        } else if ($statut == 'erreur_taille') {
            echo "<p>Par contre l'image était trop lourde<p>";
        } else if ($statut == 'erreur_chargement') {
            echo "<p>Par contre il y a eu un problème lors du chargement de l'image<p>";
        } else if ($statut == 'erreur_nom') {
            echo "<p>Par contre le nom de l'image était trop long. Il faut la renommer<p>";
        }
        ?>

<?php
        $req_domaine = $bdd->prepare("SELECT domaine FROM domaines WHERE num_domaine = " . $dom_ss_dom[0]);
        $req_domaine -> execute();
        $domaine = $req_domaine -> fetch();

        $req_sous_domaine = $bdd->prepare("SELECT sous_domaine FROM sous_domaines WHERE num_sous_domaine = " . $dom_ss_dom[1]);
        $req_sous_domaine -> execute();
        $sous_domaine = $req_sous_domaine -> fetch();
        ?>

    <p>Elle fait partie du domaine "<b><?= $domaine['domaine'] ?></b>" et du sous-domaine "<b><?= $sous_domaine['sous_domaine'] ?></b>" </p>

  

    <p>Voici son rendu :</p>
    <div class='div-rendu'>
        <?php
            $numero_q = 1;

            $req_q = $bdd->prepare("SELECT * FROM questions WHERE num_question = ?");
            $req_q->execute(array($id['num_question']));

            $question = $req_q->fetch();
            ?>
        <div>
            <?= $question['question'] ?>
        </div>
        <?php

            if (!is_null($question['image'])) :
                ?>

            <img class='img-question' src="image_questions/<?= $question['image'] ?>">

        <?php endif; ?>

        <div class='input-group reponse-selection-<?php if ($question['bonne_reponse'] == 'A') : echo 'bonne';
                                                                else : echo "mauvaise";
                                                                endif; ?>'>
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input type="radio" disabled>
                </div>
            </div>
            <span class='form-control'><?= $question['reponseA'] ?></span>
        </div>
       
        <br>
        <br>

        <div class='input-group reponse-selection-<?php if ($question['bonne_reponse'] == 'B') : echo 'bonne';
                                                                else : echo "mauvaise";
                                                                endif; ?>'>
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input type="radio" disabled>
                </div>
            </div>
            <span class='form-control'><?= $question['reponseB'] ?></span>
        </div>

        <br>

        <div class='input-group reponse-selection-<?php if ($question['bonne_reponse'] == 'C') : echo 'bonne';
                                                                else : echo "mauvaise";
                                                                endif; ?>'>
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input type="radio" disabled>
                </div>
            </div>
            <span class='form-control'><?= $question['reponseC'] ?></span>
        </div>

        <br>

        <div class='input-group reponse-selection-<?php if ($question['bonne_reponse'] == 'D') : echo 'bonne';
                                                                else : echo "mauvaise";
                                                                endif; ?>'>
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input type="radio" disabled>
                </div>
            </div>
            <span class='form-control'><?= $question['reponseD'] ?></span>
        </div>

        <br>

        <div class='input-group reponse-selection-mauvaise'>
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input type="radio" disabled checked>
                </div>
            </div>
            <span class='form-control'>Je ne sais pas...</span>
        </div>

        <br>
    </div>
    <p>Cliquer ci-dessous pour la modifier</p>
    <form method="post" action="modif-question.php">
        <input type="hidden" name="num_question" value="<?= $id['num_question'] ?>">
        <button class='btn btn-info'>Modifier cette question</button>
    </form>

    <p>Cliquer ci-dessous pour effectuer un nouvel ajout</p>

    <form method="post" action="ajout.php">
        <button class='btn btn-info'>Nouvel Ajout</button>
    </form>

<?php
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
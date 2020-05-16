<?php session_start();

include("head.php");


if (empty($_SESSION) or $_SESSION['connecte'] != true) :
    include("header.php");
    echo "Vous ne devriez pas être ici : <a href='index.php'>Retour</a>";
else :
    include('connexionbdd.php');
    include("header.php");
    include("nav.php");


    /* Ajout de la question dans la base */
    $dom_ss_dom = explode('-', $_POST['num_domaine_sous_domaine']);

    require_once 'htmlpurifier-4.12.0-lite/library/HTMLPurifier.auto.php';

    $purifier = new HTMLPurifier();

    /* Ajout de la question dans la base */
    $req_ajout = $bdd->prepare("UPDATE questions SET question = ?, reponseA = ?, reponseB = ?, reponseC = ?, reponseD = ?, bonne_reponse = ?, num_domaine = ?, num_sous_domaine = ?, date_ajout = CURRENT_TIMESTAMP WHERE num_question = ?");
    $req_ajout->execute(array(
        $purifier->purify($_POST['question']),
        $purifier->purify($_POST['reponseA']),
        $purifier->purify($_POST['reponseB']),
        $purifier->purify($_POST['reponseC']),
        $purifier->purify($_POST['reponseD']),
        $_POST['bonne_reponse'],
        $dom_ss_dom[0],
        $dom_ss_dom[1],
        $_POST['num_question']
    ));

    /* Chargement de l'image si son nom est non vide */
    $statut = "";

    if ($_FILES['file']['name'] != "") {


        $filename = $_POST['num_question'] . "_" . $_FILES['file']['name'];

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
                $sql = "UPDATE questions SET image = '" . $filename . "' WHERE num_question = " . $_POST['num_question'];
                $update_img = $bdd->prepare($sql);
                $update_img->execute();
            } else {
                $statut = 'erreur_chargement';
            }
        }
        echo $statut;
    }

?>

    <h1 class='h1-qcm'>Confirmation de la modification</h1>

    <p>Votre question a été modifiée dans la base à la référence #<?= $_POST['num_question'] ?></p>

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
    $req_domaine->execute();
    $domaine = $req_domaine->fetch();

    $req_sous_domaine = $bdd->prepare("SELECT sous_domaine FROM sous_domaines WHERE num_sous_domaine = " . $dom_ss_dom[1]);
    $req_sous_domaine->execute();
    $sous_domaine = $req_sous_domaine->fetch();
    ?>

    <p>Elle fait partie du domaine "<b><?= $domaine['domaine'] ?></b>" et du sous-domaine "<b><?= $sous_domaine['sous_domaine'] ?></b>" </p>

    <p>Voici son rendu :</p>
    <div class='div-rendu'>

        <?php
        $req_q = $bdd->prepare("SELECT * FROM questions WHERE num_question = ?");
        $req_q->execute(array($_POST['num_question']));

        $question = $req_q->fetch();
        ?>
        <?= $question['question'] ?>

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

        <div class='input-group'>
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input type="radio" disabled>
                </div>
            </div>
            <span class='form-control'>Je ne sais pas...</span>
        </div>
    </div>
    <br>

    <form method="post" action="modif-question.php">
        <input type="hidden" name="num_question" value="<?= $_POST['num_question'] ?>">
        <button class='btn btn-info'>Modifier à nouveau cette question</button>
    </form>

    <form method="post" action="modification.php">
        <button class='btn btn-info'>Modifier une nouvelle question</button>
    </form>

    <a href="modification.php#modif-button<?= $_POST['num_question'] ?>">
        <button class='btn btn-info'>Retourner dans la liste des questions</button>
    </a>
<?php
endif;
include("footer.php")
?>

</body>
<script>
    $('document').ready(function() {
        render_md_math();
    })
</script>

</html>
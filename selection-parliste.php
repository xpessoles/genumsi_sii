<?php session_start();

include("head.php");


if (empty($_SESSION) or $_SESSION['connecte'] != true) :
    include("header.php");
    echo "Vous ne devriez pas être ici : <a href='index.php'>Retour</a>";
else :
    include('connexionbdd.php');
    include("header.php");
    include("nav.php");

    $txt_cle = "";
    if (!empty($_POST) and !is_null($_POST['cle'])) {
        $txt_cle = 'value="' . $_POST['cle'] . '"';
    } else {
        $txt_cle = 'placeholder="#question;#question"';
    }

    ?>


    <h1 class='h1-qcm'>Création à partir d'une liste</h1>

    <h4>Vous pouvez indiquer ici la liste des questions du qcm que vous souhaitez générer</h4>

    <form class="form-group" method="post" action="qcm-valide-cle.php">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">Clé du QCM</span>
            </div>
            <input type="text" class="form-control" <?= $txt_cle ?> aria-label="Clé" aria-describedby="basic-addon1" pattern="([0-9]+;)*[0-9]+" name="cle">
        </div>
        <button class='btn btn-info btn-valide'>Visualiser le QCM</button>
    </form>


<?php
endif;
?>
<div id="bas"></div>

<?php include("footer.php") ?>

</body>



</html>
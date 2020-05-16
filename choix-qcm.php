<?php session_start();

include("head.php");

if (empty($_SESSION) or $_SESSION['connecte'] != true) :
    include("header.php");
    echo "Vous ne devriez pas être ici : <a href='index.php'>Retour</a>";
else :

    if (
        !isset($_POST['hash']) or is_null($_POST['hash'])
    ) {

        echo '<p>Vous n\'avez pas fourni toutes les infos</p>';
    } else {

        include("connexionbdd.php");
        include("header.php");
        include("nav.php");
        include("phpqrcode/qrlib.php");

        include("url-qcm.php");

        $req_qcm = $bdd->prepare('SELECT * FROM qcms WHERE hash_qcm = ?');
        $req_qcm->execute(array($_POST['hash']));

        $qcm = $req_qcm -> fetch();

        ?>

        <h1 class='h1-qcm'>Partager le QCM</h1>

        <div class="validation-qcm">

            <p>Vous pouvez accéder au QCM en cliquant ci-dessous : </p>
            <a href="<?= url("qcm.php") . "?h=" . $_POST['hash'] ?>" target="_blank">
                <button class='btn btn-info btn-valide' type='button' id="btn-qcm">Accéder au QCM en ligne</button>
            </a>

            <form type='get' action='qcm-print.php' class='form-valide' target="_blank">
                <input type="hidden" name='cle' value='<?= base64_encode($qcm['cle_qcm']) ?>'>
                <input type="hidden" name='b' value="<?= base64_encode($qcm['points_plus']) ?>" id="hidden-print-b-bas">
                <input type="hidden" name='m' value="<?= base64_encode($qcm['points_moins']) ?>" id="hidden-print-m-bas">
                <button class='btn btn-info btn-valide' type='submit'>Accéder au QCM complet à imprimer</button>
            </form>

            <form type='get' action='qcm-corrige.php' target="_blank" class='form-valide'>
                <input type="hidden" name='cle' value='<?= base64_encode($qcm['cle_qcm']) ?>'>
                <button class='btn btn-info btn-valide' type='submit'>Accéder au corrigé complet</button>
            </form>

            <p class='p-lien-qcm'>Le partager avec vos élèves à l'aide de ce lien :
                <input type='text' class='lien-qcm' id='lien-qcm-bas' value='<?= url("qcm.php") . "?h=" . $_POST['hash'] ?>' onclick='this.select() '>
            </p>

            <p class='p-lien-qcm'>Partager le corrigé avec vos élèves à l'aide de ce lien :
                <input type='text' class='lien-qcm-corrige' id='lien-qcm-corrige' value='<?= url("qcm-corrige.php") ?>?cle=<?= base64_encode($qcm['cle_qcm']) ?>' onclick='this.select() '>
            </p>

            <p class='p-lien-qcm'>Générer un QR-code pour le partager :</p>
            <form method="post" action="qcm-qrcode.php" id="form-qrcode-bas" target="_blank">
                <input type="hidden" name='hash' value='<?= $_POST['hash'] ?>'>
                <button class='btn btn-info btn-valide' type='submit' id="btn-qrcode-bas">Générer le qrcode</button>
            </form>
        </div>




<?php

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
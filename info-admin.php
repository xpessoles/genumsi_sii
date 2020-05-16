<?php session_start();
include("head.php");

if (empty($_SESSION) or $_SESSION['connecte'] != true) :
    include("header.php");
    echo "Vous ne devriez pas être ici : <a href='index.php'>Retour</a>";
else :
    include('connexionbdd.php');
    include("header.php");
    include("nav.php");
    ?>

    <h1 class='h1-qcm'>Informations pour les administrateurs</h1>

    <table class="table table-hover table-bordered table-striped" style="width:50%; margin: 2vh auto 2vh auto;">
        <?php
            $req = $bdd->prepare("SELECT visites AS nb FROM informations_admin WHERE 1");
            $req->execute();
            $r = $req->fetch();
            ?>
        <tr>
            <td>Nombre de visite depuis l'ouverture du site</td>
            <td><?= $r['nb'] ?></td>
        </tr>

        <?php
            $req = $bdd->prepare("SELECT count(*) AS nb FROM utilisateurs WHERE 1");
            $req->execute();
            $r = $req->fetch();
            ?>
        <tr>
            <td>Nombre d'utilisateurs dans la base</td>
            <td><?= $r['nb'] ?></td>
        </tr>

        <?php
            $req = $bdd->prepare("SELECT count(*) AS nb FROM questions WHERE 1");
            $req->execute();
            $r = $req->fetch();
            ?>
        <tr>
            <td>Nombre de questions dans la base</td>
            <td><?= $r['nb'] ?></td>
        </tr>

        <?php
            $req = $bdd->prepare("SELECT qcms AS nb FROM informations_admin WHERE 1");
            $req->execute();
            $r = $req->fetch();
            ?>
        <tr>
            <td>Nombre de qcms générés</td>
            <td><?= $r['nb'] ?></td>
        </tr>

        <?php
            $req = $bdd->prepare("SELECT count(*) AS nb FROM questions INNER JOIN domaines ON questions.num_domaine = domaines.num_domaine WHERE domaines.niveau = '1'");
            $req->execute();
            $r = $req->fetch();
            ?>
        <tr>
            <td>Nombre de questions de 1ère dans la base</td>
            <td><?= $r['nb'] ?></td>
        </tr>

        <?php
            $req = $bdd->prepare("SELECT count(*) AS nb FROM questions INNER JOIN domaines ON questions.num_domaine = domaines.num_domaine WHERE domaines.niveau = 'T'");
            $req->execute();
            $r = $req->fetch();
            ?>
        <tr>
            <td>Nombre de questions de Terminale dans la base</td>
            <td><?= $r['nb'] ?></td>
        </tr>

        <?php
            $req = $bdd->prepare("SELECT count(*) AS nb FROM resultats WHERE 1");
            $req->execute();
            $r = $req->fetch();
            ?>
        <tr>
            <td>Nombre de résultats individuels d'élèves</td>
            <td><?= $r['nb'] ?></td>
        </tr>


    </table>
<?php
endif;
?>

<?php include("footer.php") ?>

</body>

<script>
    function lien(niveau) {
        $('#hidden-nav').val(niveau);
        $('#form-nav').submit();
    }
</script>


</html>
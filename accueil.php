<?php session_start();
include("config.php");
include("head.php");

if (empty($_SESSION) or $_SESSION['connecte'] != true) :
    include("header.php");
    echo "Vous ne devriez pas être ici : <a href='index.php'>Retour</a>";
else :
    include('connexionbdd.php');
    include("header.php");
    include("nav.php");
    ?>

    <h1 class='h1-qcm'>Accueil</h1>

    <p>
        Bienvenue sur le site <b>Genumsi</b>
    </p>

    <p>Ce site vous permet de générer des QCM de <?= $GENUMMATIERE ?> en choisissant les questions de façon aléatoire ou selon vos envies !</p>

    <p>L'un des objectifs du site est de centraliser les questions de tous les collègues qui le souhaitent.</p>

    <p>Alors n'hésitez pas à <a href="ajout.php" style='font-weight:bold;color:purple;'>contribuer</a> et à ajouter des questions !</p>

    <p>
        Vous pouvez donc :
    </p>
    <ul id="liste-accueil">
        <li>Créer des qcm pour vos élèves
            <form action="selection-niveau.php" method='POST' id='form-nav'>
                <input hidden value="1" name='niveau' id='hidden-nav'>
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="link" href="javascript:lien('1');">Aléatoirement - Niveau 1ère</a>
                    </li>
                    <li class="nav-item">
                        <a class="link" href="javascript:lien('T');">Aléatoirement - Niveau Terminale</a>
                    </li>
                    <li><a class="link" href="selection-manuelle.php">Par sélection manuelle</a></li>
                    <li><a class="link" href="selection-parliste.php">A partir d'une liste</a></li>
                </ul>
            </form>
        </li>
        <li>
            Consulter les résultats de vos élèves
            <ul class="navbar-nav mr-auto">
                <li><a class="link" href="resultats.php">Résultats</a></li>

            </ul>
        </li>
        <li>Ajouter, modifier ou exporter des questions de la base
            <ul class="navbar-nav mr-auto">
                <li><a class="link" href="ajout.php">Ajout</a></li>
                <li><a class="link" href="modification.php">Modification</a></li>
                <li><a class="link" href="exports.php">Export</a></li>
            </ul>
        </li>
    </ul>

    <div id="fil">
    <p>A ce jour, <b><i>GeNumSI</i></b> c'est :</p>
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
            $req = $bdd->prepare("SELECT COUNT(*) as nb FROM questions WHERE date_ajout BETWEEN DATE_SUB(NOW(), INTERVAL 8 DAY) AND NOW()");
            $req->execute();
            $r = $req->fetch();
            ?>
        <tr>
            <td>Nombre de questions ajoutées depuis une semaine</td>
            <td><?= $r['nb'] ?></td>
        </tr>

        <?php
            $req = $bdd->prepare("SELECT COUNT(*) as nb FROM questions WHERE date_ajout BETWEEN DATE_SUB(NOW(), INTERVAL 32 DAY) AND NOW()");
            $req->execute();
            $r = $req->fetch();
            ?>
        <tr>
            <td>Nombre de questions ajoutées depuis un mois</td>
            <td><?= $r['nb'] ?></td>
        </tr>

    </table>
    </div>

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
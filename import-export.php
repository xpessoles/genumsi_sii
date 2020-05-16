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
    <h1 class='h1-qcm'>Import / Export</h1>

    <h2>Exporter les questions de la base.</h2>
    <p>Le menu <strong>Opérations sur les questions/exports</strong> vous permet d'exporter
        la base de toutes les questions présentent dans la base de données de genumsi dans différents formats,
        ceci afin de vous permettrent si besoin de les importer dans un autre logiciel de QCM.</p>
    <p>Si vous avez des besoins d'exports particuliers à ajouter n'hésitez pas à nous contacter.</p>

<?php
endif;
?>

<?php include("footer.php") ?>

</body>

</html>
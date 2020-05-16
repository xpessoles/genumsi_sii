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
    <h1 class='h1-qcm'>Nous joindre</h1>

    <p>Vous pouvez nous joindre pour :</p>
    <ul>
        <li>Signaler une erreur dans une question (notez que si vous êtes l'auteur de la question vos pouvez la <a class='link' href="modification.php">modifier</a></li>
        <li>Signaler un dysfonctionnement</li>
        <li>Proposer une amélioration</li>
    </ul>

    <p>Nos mails :</p>
    <ul>
    <li><a href="mailto:christophe.beasse@ac-rennes.fr">christophe.beasse@ac-rennes.fr</a></li>
    <li><a href="mailto:nicolas.reveret@ac-rennes.fr">nicolas.reveret@ac-rennes.fr</a></li>
    </ul>

<?php
endif;
?>

<?php include("footer.php") ?>

</body>

</html>
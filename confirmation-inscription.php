<?php session_start();
include("head.php");

include('connexionbdd.php');
include("header.php");

if (isset($_GET['k']) && !is_null($_GET['k'])) :

    /* Mise à jour de  la base */
    $req_ajout = $bdd->prepare("UPDATE utilisateurs SET verification = 'ok' WHERE verification = ?");
    $req_ajout->execute(array($_GET['k']));
    ?>

    <p>Votre compte a bien été confirmé. Vous pouvez désormais vous connecter</p>

    <a href="index.php">Retour à l'identification</a>

<?php
endif;
include("footer.php")
?>

</body>

</html>
<?php
session_start();

if (empty($_SESSION) or $_SESSION['connecte'] != true) :
    include("header.php");
    echo "Vous ne devriez pas être ici : <a href='index.php'>Retour</a>";
else :

    session_destroy();
    include('head.php');
    include("header.php");

    ?>

    <h4>
        Vous êtes déconnecté
    </h4>

    <a href="index.php">Identification</a>

<?php
endif;
?>
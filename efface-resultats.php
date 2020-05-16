<?php
    session_start();

    if (empty($_SESSION) or $_SESSION['connecte'] != true) :
        echo "Vous ne devriez pas être ici : <a href='index.php'>Retour</a>";
    else :

        include('connexionbdd.php');

        $resultats = $bdd -> prepare('DELETE FROM resultats WHERE num_prof = ?');
        $resultats -> execute(array($_SESSION['num_util']));

    header ("location: resultats.php");

    endif;

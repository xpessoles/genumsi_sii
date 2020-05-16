<?php
    session_start();

    if (isset($_SESSION) and $_SESSION['connecte'] == true) :
        header ("location: accueil.php");
    else :
        header ("location: index.php");
    endif;

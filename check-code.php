<?php
    session_start();
    include("connexionbdd.php");

    if (!is_null($_POST['p']) and isset($_POST['p'])) {
        
        $req = $bdd -> prepare("SELECT identifiant FROM utilisateurs WHERE num_util = ?");
        $req -> execute(array(base64_decode($_POST['p'])));
        $nom = $req -> fetch();

        if ($nom['identifiant'] == $_POST['code']) {
            unset($_SESSION['premier_chargement']);
            echo "success";
        } else {
            echo "fail";
        }
    }
?>
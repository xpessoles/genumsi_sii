<?php session_start();

include("head.php");
include('connexionbdd.php');
include("header.php");


if (isset($_POST['mdp']) && !is_null($_POST['mdp'])) {

    $req_chgt = $bdd->prepare("UPDATE utilisateurs SET mdp = ? WHERE num_util = ?");
    $req_chgt->execute(array(password_hash($_POST['mdp'], PASSWORD_DEFAULT), $_SESSION['num_util']));

    ?>
    <p>Votre mot de passe a été modifié</p>

    <a href="index.php">Retour à l'identification</a>

<?php

} else {
    ?>
    <p>Il y a eu un problème... Merci de réessayer</p>

    <a href="mdp-perdu.php">Mot de passe perdu ?</a>

<?php

}
include("footer.php")
?>

</body>

</html>
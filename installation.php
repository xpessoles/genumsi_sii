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
    <h1 class='h1-qcm'>Installation</h1>

<?php
endif;
?>

<?php include("footer.php") ?>

</body>

</html>
<?php session_start();

include("head.php");

if (empty($_SESSION) or $_SESSION['connecte'] != true) :
    include("header.php");
    echo "Vous ne devriez pas être ici : <a href='index.php'>Retour</a>";
else :

    include("connexionbdd.php");
    include("header.php");
    include("nav.php");
    include("phpqrcode/qrlib.php");

    include("url-qcm.php");

    $content = url("qcm.php") . "?h=" . $_POST['hash'];

    $filename = 'image_qrcode/qrcode.png';

    $errorCorrectionLevel = 'H';

    $matrixPointSize = 4;

    QRcode::png($content, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

    ?>
    <p class='p-lien-qcm'>Le QR-code à partager :</p>
    <img class='qrcode' src="image_qrcode/qrcode.png" alt="" />


<?php

endif;

include("footer.php")
?>

</body>

</html>
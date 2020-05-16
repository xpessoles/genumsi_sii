<?php session_start();

if (empty($_SESSION) or $_SESSION['connecte'] != true) :
    include("header.php");
    echo "Vous ne devriez pas être ici : <a href='index.php'>Retour</a>";
else :

    if (
        !isset($_POST['cle']) or is_null($_POST['cle'])
        or  !isset($_POST['p']) or is_null($_POST['p'])
        or !isset($_POST['b']) or is_null($_POST['b'])
        or !isset($_POST['m']) or is_null($_POST['m'])
        or !isset($_POST['t']) or is_null($_POST['t'])
        
    ) {
        echo 'fail';
    } else {
        include("connexionbdd.php");
        include("url-qcm.php");

        $texte_hash = $_POST['p'] . $_POST['cle'] . time();
        $hash_qcm = md5($texte_hash);

        $req_increment = $bdd->prepare('UPDATE informations_admin SET qcms = qcms + 1 WHERE 1');
        $req_increment->execute();

        if ($_POST['d'] === "") {
        $req_hash = $bdd->prepare('INSERT INTO qcms (hash_qcm, cle_qcm, num_prof, points_plus, points_moins, actif, lien_qcm, triche) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ');
        $err = $req_hash->execute(
            array(
                $hash_qcm,
                base64_decode($_POST['cle']),
                base64_decode($_POST['p']),
                base64_decode($_POST['b']),
                base64_decode($_POST['m']),
                1,
                url("qcm.php") . "?h=" . $hash_qcm,
                $_POST['t']
            )
            );
            
        echo $hash_qcm;
        } else {
            // Nettoyage du descriptif saisi par l'utilisateur
            require_once 'htmlpurifier-4.12.0-lite/library/HTMLPurifier.auto.php';
            $purifier = new HTMLPurifier();

            $req_hash = $bdd->prepare('INSERT INTO qcms (hash_qcm, cle_qcm, num_prof, points_plus, points_moins, actif, lien_qcm, triche, descriptif_qcm) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ');
            $err = $req_hash->execute(
                array(
                    $hash_qcm,
                    base64_decode($_POST['cle']),
                    base64_decode($_POST['p']),
                    base64_decode($_POST['b']),
                    base64_decode($_POST['m']),
                    1,
                    url("qcm.php") . "?h=" . $hash_qcm,
                    $_POST['t'],
                    trim($purifier->purify($_POST['d']))
                )
            );
        echo $hash_qcm;
        }
    }
endif;

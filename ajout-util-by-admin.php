<?php session_start();
include("head.php");

include('connexionbdd.php');
include("header.php");

function url()
{
    if (isset($_SERVER['HTTPS'])) {
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    } else {
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['SERVER_NAME'] . substr($_SERVER['SCRIPT_NAME'], 0, -23) . "confirmation-inscription.php";
}

if (
    isset($_POST['nom']) && !is_null($_POST['nom'])
    && $_POST['prenom'] && !is_null($_POST['prenom'])
    && $_POST['identifiant'] && !is_null($_POST['identifiant'])
    && $_POST['mdp'] && !is_null($_POST['mdp'])
    && $_POST['email'] && !is_null($_POST['email'])
) {

    if (preg_match("/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/", $_POST['email']) != 1) {
        echo "<p>Votre email n'a pas été reconnu comme un mail valide</p>";
    } else {

        $_req_id = $bdd->prepare("SELECT count(*) AS nb FROM utilisateurs WHERE identififiant = ?");
        $_req_id->execute(array($_POST['identifiant']));
        $nb = $_req_id->fetch();

        if ($nb['nb'] > 0) {
            echo "<p>Votre login existe déjà dans la base</p>";
        } else {
            $verification = md5(microtime(TRUE) * 100000);
            /* Ajout de la question dans la base */
            $req_ajout = $bdd->prepare("INSERT INTO utilisateurs (nom, prenom, identifiant, mdp, qualite, id_utilisateur, mail, verification) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $req_ajout->execute(array(
                $_POST['nom'],
                $_POST['prenom'],
                $_POST['identifiant'],
                password_hash($_POST['mdp'], PASSWORD_DEFAULT),
                "prof",
                $_POST['identifiant'],
                $_POST['email'],
                $verification
            ));


            $to = $_POST['email'];

            // Sujet
            $subject = "Confirmation de l'inscription à GeNumSI";

            // message
            $message = "
        <html>

        <head>
            <title>Confirmation de l\'inscription à GeNumSI</title>
        </head>

        <body>
            <p>Votre inscription a été prise en compte.</p>
            <p>Merci de suivre le lien ci-dessous afin de la confirmer :</p>
            <a href='" . url() . "?k=" . $verification . "' style='text-decoration:underline;'>Confirmation de l'inscription</a>
        </body>

        </html>
        ";
            $message = wordwrap($message, 70, "\r\n");
            $message = str_replace("\n.", "\n..", $message);


            // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=utf-8';

            // En-têtes additionnels
            $headers[] = 'To: ' . $_POST['email'];
            $headers[] = 'From: no-reply@genumsi.inria.fr';
            $headers[] = 'X-Mailer: PHP/' . phpversion();

            // Envoi
            mail($to, '=?utf-8?B?' . base64_encode($subject) . '?=', $message, implode("\r\n", $headers));
            ?>

            <p>Ce compte a été créé</p>

            <p>Le collègue en question va recevoir un mail de confirmation.</p>

            <p>Il doit suivre le lien du mail afin de finaliser son inscription</p>
    <?php

            }
        }
    } else {
        ?>
    <p>Votre compte n'a pas été créé, vous n'avez sans doute pas fourni toutes les informations...</p>
    <br>
    <p>Merci de réessayer</p>

    <a href="nouvel-utilisateur-by-admin.php">Nouvel utilisateur ?</a>

<?php

}

include("footer.php")
?>

</body>

</html>
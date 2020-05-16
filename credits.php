<?php session_start();
include("head.php");

if (empty($_SESSION) or $_SESSION['connecte'] != true) :
    include("header.php");
    ?>
    <h1 class='h1-qcm'>A propos</h1>

    <h2>Qui sommes-nous ?</h2>
    <br>
    <p>Genumsi c'est au départ un groupe d'enseignants bretons réunis lors de la formation du Master "<i>Enseigner la Spécialité Numérique et Sciences Informatiques</i>" auquel s'est adjoint un collègue formateur !</p>

    <p>Dans le détail, nous sommes :</p>
    <ul>
        <li>Marina Basova</li>
        <li>Chistophe Béasse (christophe.beasse@ac-rennes.fr)</li>
        <li>Arezki Benoufella</li>
        <li>Aude Durand</li>
        <li>Loïc L'Aminot</li>
        <li>David Planchet</li>
        <li>Sébastien Poirier</li>
        <li>Nicolas Revéret (nicolas.reveret@ac-rennes.fr)</li>
    </ul>

    <br>
    <h2>Crédits</h2>
    <br>

    <p>Ce site et tout son contenu (y compris les questions de la base de données et les qcm générés) est mis à disposition selon les termes de la <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Licence Creative Commons Attribution - Pas d’Utilisation Commerciale - Partage dans les Mêmes Conditions 4.0 International</a>.</p>

    <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Licence Creative Commons" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a>

    <br>
    <br>
    <h2>Ressources externes</h2>
    <br>

    <p>Ce site utilise plusieurs ressources référencées ci-dessous :</p>

    <ul>
        <li><a class='link' href='https://getbootstrap.com/'>Bootstrap :</a> styles css rapides</li>
        <li><a class='link' href='https://fancyapps.com/fancybox/3//'>Fancybox :</a> affichage des images</li>
        <li><a class='link' href='http://phpqrcode.sourceforge.net/'>PHPQrCode :</a> génération des liens QR-Code</li>
        <li>Logo :
            <ul>
                <li>
                    <a class='link' href='https://svgsilh.com/image/1294844.html'>Engrenage</a>
                </li>
                <li>
                    <a class='link' href='https://pixabay.com/fr/illustrations/liste-de-v%C3%A9rification-test-v%C3%A9rifier-1402461/'>Formulaire</a>
                </li>
            </ul>
        </li>

    </ul>

<?php
else :
    include('connexionbdd.php');
    include("header.php");
    include("nav.php");


    ?>

    <h1 class='h1-qcm'>A propos</h1>

    <h2>Qui sommes-nous ?</h2>
    <br>
    <p>Genumsi c'est au départ un groupe d'enseignants bretons réunis lors de la formation du Master "<i>Enseigner la Spécialité Numérique et Sciences Informatiques</i>" auquel s'est adjoint un collègue formateur !</p>

    <p>Dans le détail, nous sommes :</p>
    <ul>
        <li>Marina Basova</li>
        <li>Chistophe Béasse</li>
        <li>Arezki Benoufella</li>
        <li>Aude Durand</li>
        <li>Loïc L'Aminot</li>
        <li>David Planchet</li>
        <li>Sébastien Poirier</li>
        <li>Nicolas Revéret</li>
    </ul>

    <br>
    <h2>Crédits</h2>
    <br>

    <p>Ce site et tout son contenu (y compris les questions de la base de données et les qcm générés) est mis à disposition selon les termes de la <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Licence Creative Commons Attribution - Pas d’Utilisation Commerciale - Partage dans les Mêmes Conditions 4.0 International</a>.</p>

    <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Licence Creative Commons" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a>

    <br>
    <br>
    <h2>Ressources externes</h2>
    <br>

    <p>Ce site utilise plusieurs ressources référencées ci-dessous :</p>

    <ul>
        <li><a class='link' href='https://getbootstrap.com/'>Bootstrap :</a> styles css rapides</li>
        <li><a class='link' href='https://fancyapps.com/fancybox/3//'>Fancybox :</a> affichage des images</li>
        <li><a class='link' href='http://phpqrcode.sourceforge.net/'>PHPQrCode :</a> génération des liens QR-Code</li>
        <li>Logo :
            <ul>
                <li>
                    <a class='link' href='https://svgsilh.com/image/1294844.html'>Engrenage</a>
                </li>
                <li>
                    <a class='link' href='https://pixabay.com/fr/illustrations/liste-de-v%C3%A9rification-test-v%C3%A9rifier-1402461/'>Formulaire</a>
                </li>
            </ul>
        </li>

    </ul>

<?php
endif;
?>

<?php include("footer.php") ?>

</body>

</html>
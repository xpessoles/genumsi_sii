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

    <h1 class='h1-qcm'>Exports</h1>

    <p>
        Sur cette page vous pouvez :
    </p>
    <ul>
        <li>
            Exporter la base de questions au format <a href="https://docs.moodle.org/2x/fr/Format_GIFT">GIFT</a> :
            <ul class="navbar-nav mr-auto">
                <li><a class="link" href="export-base-gift.php">Export de la base en GIFT</a></li>
            </ul>
        </li>
        <br>
        <li>
            <p>Exporter la base de questions en sql. Ce fichier contient des requêtes sql qui pourront être exécutées afin d'importer les questions dans une table <code>questions</code> qui sera créée à cette occasion</p>
            <p>L'éxécution de ces requêtes pourra entraîner la disparition de vos questions sur le serveur local...</p>
            <ul class="navbar-nav mr-auto">
                <li><a class="link" href="export-questions-sql.php">Export des questions en sql</a></li>
            </ul>
        </li>
        <br>
        <li>
            Exporter la base de questions au format CSV avec :
            <ul>
                <li>entêtes sur la première ligne</li>
                <li>des virgules en séparateur</li>
                <li>encodé en utf-8</li>
            </ul>
            <ul class="navbar-nav mr-auto">
                <li><a class="link" href="export-base-csv.php">Export des questions en CSV</a></li>
            </ul>
        </li>
        <br>
        <li>
            Exporter les images correspondantes dans une archive :
            <ul class="navbar-nav mr-auto">
                <li><a class="link" href="export-images.php">Export des images</a></li>
            </ul>
        </li>
    </ul>
<?php
endif;
?>

<?php include("footer.php") ?>

</body>

</html>
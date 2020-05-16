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

    <h1 class='h1-qcm'>Données personnelles</h1>

    <p>Pour assurer son fonctionnement, ce site reccueille différentes informations :</p>

    <ul>
        <li><i>Données des élèves :</i>
            <p>Les élèves sont repérés dans la base de données par l'identifiant qu'ils saisissent lors de la réalisation d'un qcm</p>
            <p>Cette valeur est fournie par le professeur et est anonyme.</p>
            <p>Ainsi, <b>aucune donnée personnelle</b> d'élève n'est stockée sur ce site</p>
        </li>
        <li><i>Données des enseignants :</i>
            <p>Les informations sur les enseignants inscrits sur le site sont les suivantes :
                <ul>
                    <li>Nom et prénom</li>
                    <li>Identifiant de connexion</li>
                    <li>Mot de passe (hashé)</li>
                    <li>Informations techniques (dernière connexion...)</li>
                </ul>
            <p>Ces information sont utiles au fonctionnement du site et permettent par exemple de modifier ses propres questions et pas celles des collègues</p>        </li>
        <li><i>Données des questions :</i>
                <p>Outre leurs données textuelles, les questions sont associées à un profil d'enseignant rédacteur</p>
    </li>
    </ul>

    <p><b>Aucune de ces informations</b> n'est utilisée à d'autre fin que celle du fonctionnement du site</p>
    <p>Toute personne souhaitant accéder à ses données ou les supprimer peut prendre contact avec les administrateurs du site</p>

<?php
endif;
?>

<?php include("footer.php") ?>

</body>

</html>
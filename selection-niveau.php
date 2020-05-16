<?php session_start();

include("head.php");

if (empty($_SESSION) or $_SESSION['connecte'] != true) :
    include("header.php");
    echo "Vous ne devriez pas être ici : <a href='index.php'>Retour</a>";
else :
    include('connexionbdd.php');
    include("header.php");
    include("nav.php");


    // Récupération de toutes les questions correspondants aux numéros du GET
    $texte_req = 'SELECT domaine, questions.num_domaine, count(questions.num_domaine) AS nb_domaine FROM questions INNER JOIN domaines ON questions.num_domaine = domaines.num_domaine WHERE domaines.niveau = ? GROUP BY domaines.num_domaine ORDER BY domaines.num_domaine';
    $domaines = $bdd->prepare($texte_req);
    $domaines->execute(array($_POST['niveau']));

    $texte_req = 'SELECT domaines.domaine, sous_domaine, questions.num_sous_domaine, count(questions.num_sous_domaine) AS nb_sous_domaine
                        FROM questions
                        INNER JOIN sous_domaines ON questions.num_sous_domaine = sous_domaines.num_sous_domaine
                        INNER JOIN domaines ON sous_domaines.num_domaine = domaines.num_domaine
                        WHERE domaines.niveau = ?
                        GROUP BY sous_domaines.num_sous_domaine';
    $sous_domaines = $bdd->prepare($texte_req);
    $sous_domaines->execute(array($_POST['niveau']));


    if ($_POST['niveau'] == '1') {
        echo "<h1 class='h1-qcm'>Niveau Première</h1>";
    } else {
        echo "<h1 class='h1-qcm'>Niveau Terminale</h1>";
    }

    ?>

    <h3>Vous pouvez ici créer un QCM en sélectionnant les questions par rubrique du programme <b>OU</b> par contenu de rubrique</h3>
    <br>
    <p>Sélectionnez le nombre de questions souhaité pour chaque <b>rubrique</b></p>

    <form class='qcm' method='post' action='qcm-valide-domaine.php' onsubmit="testDomaines(event)">
        <?php while ($domaine = $domaines->fetch()) : ?>

            <div class='input-group-perso'>
                <div class='domaine-selection'><b>
                        <?= $domaine['domaine'] ?></b><br>(nombre maximal de questions : <?= $domaine['nb_domaine'] ?>)
                </div>
                <div class="number-input">
                    <button onclick="this.parentNode.querySelector('input[type=number]').stepDown()" type='button' class='plus-moins'></button>
                    <input class="custom-select-perso input-domaine" min="0" max='<?= $domaine['nb_domaine'] ?>' value="0" type="number" id='input<?= $domaine['num_domaine'] ?>' name='theme<?= $domaine['num_domaine'] ?>'>
                    <button onclick="this.parentNode.querySelector('input[type=number]').stepUp()" type='button' class="plus plus-moins"></button>
                </div>
            </div>


        <?php endwhile; ?>

        <button class='btn btn-info' type='submit'>Générer le QCM selon les rubriques</button>
    </form>
    <br>
    <p>Sélectionnez le nombre de questions souhaité pour chaque <b>contenu de rubrique</b></p>

    <form class='qcm' method='post' action='qcm-valide-sous-domaine.php' onsubmit="testSousDomaines(event)">
        <?php while ($sous_domaine = $sous_domaines->fetch()) : ?>

            <div class='input-group-perso'>
                <div class='domaine-selection'><b>
                        <?= $sous_domaine['domaine'] ?> - <?= $sous_domaine['sous_domaine'] ?></b><br>(nombre maximal de questions : <?= $sous_domaine['nb_sous_domaine'] ?>)
                </div>
                <div class="number-input">
                    <button onclick="this.parentNode.querySelector('input[type=number]').stepDown()" type='button' class='plus-moins'></button>
                    <input class="custom-select-perso input-sous-domaine number_inp" min="0" max='<?= $sous_domaine['nb_sous_domaine'] ?>' value="0" type="number" id='input<?= $sous_domaine['num_sous_domaine'] ?>' name='theme<?= $sous_domaine['num_sous_domaine'] ?>'>
                    <button onclick="this.parentNode.querySelector('input[type=number]').stepUp()" type='button' class="plus plus-moins"></button>
                </div>
            </div>

        <?php endwhile; ?>

        <button class='btn btn-info' type='submit'>Générer le QCM selon les contenus de rubriques</button>
    </form>

<?php
endif;
?>

<?php include("footer.php") ?>

</body>

<script>
    function testDomaines(event) {
        let somme = 0;

        $('.input-domaine').each(function() {
            somme += parseInt(this.value);
        })

        if (somme == 0) {
            alert("Vous devez sélectionner au moins une question !");
            event.preventDefault();
        }

        return true;
    }

    function testSousDomaines(event) {
        let somme = 0;

        $('.input-sous-domaine').each(function() {
            somme += parseInt(this.value);
            console.log(somme);
        })

        if (somme == 0) {
            alert("Vous devez sélectionner au moins une question !");
            event.preventDefault();
        }

        return true;
    }

    $('document').ready(function(){
        render_md_math();
    })
</script>

</html>
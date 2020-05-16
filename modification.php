<?php session_start();

include("head.php");


if (empty($_SESSION) or $_SESSION['connecte'] != true) :
    include("header.php");
    echo "Vous ne devriez pas être ici : <a href='index.php'>Retour</a>";
else :

    include('connexionbdd.php');
    include("header.php");
    include("nav.php");

    // L'utilisateur actuel est-il un admin ?
    $texte_req = "";
    if ($_SESSION['qualite'] == 'prof') {
        $texte_req = 'SELECT *, DATE_FORMAT(questions.date_ajout, "%d-%m-%Y") AS date_aj, utilisateurs.nom, utilisateurs.prenom
            FROM questions
            INNER JOIN domaines ON questions.num_domaine = domaines.num_domaine
            INNER JOIN utilisateurs ON questions.num_util = utilisateurs.num_util
            INNER JOIN sous_domaines ON questions.num_sous_domaine = sous_domaines.num_sous_domaine
            WHERE questions.num_util = ' . $_SESSION['num_util'] . '
            ORDER BY num_question';
    } else if ($_SESSION['qualite'] == 'admin') {
        $texte_req = 'SELECT *,DATE_FORMAT(questions.date_ajout, "%d-%m-%Y") AS date_aj, utilisateurs.nom, utilisateurs.prenom
            FROM questions
            INNER JOIN domaines ON questions.num_domaine = domaines.num_domaine
            INNER JOIN utilisateurs ON questions.num_util = utilisateurs.num_util
            INNER JOIN sous_domaines ON questions.num_sous_domaine = sous_domaines.num_sous_domaine
            ORDER BY num_question';
    }

    $req_domaines = $bdd->prepare($texte_req);
    $req_domaines->execute();

    $questions = $req_domaines->fetchAll();

?>
    <a href=""><img src="image/down.png" alt="flèche du bas" id="fleche-bas-selection"></a>
    <a href=""><img src="image/up.png" alt="flèche du haut" id="fleche-haut-selection"></a>

    <h1 class='h1-qcm'>Modification de questions</h1>

    <p>Vous pouvez ici sélectionner la question que vous souhaitez modifier</p>

    <p>Seules les questions dont vous êtes l'auteur sont affichées</p>

    <p>Si vous connaissez le numéro de la question dans la base, vous pouvez l'indiquer ci-dessous :</p>

    <form action="modif-question.php" method="post">
        <select name="num_question" class="custom-select" required>
            <option value="">Choisir une question...</option>
            <?php foreach ($questions as $question) : ?>
                <option value="<?= $question['num_question'] ?>"><?= $question['num_question'] ?></option>
            <?php endforeach ?>
        </select>
        <button class='btn btn-info btn-valide'>Modifier cette question</button>
    </form>

    <p>Vous pouvez visualiser les questions afin de sélectionner celle qui vous intéresse :</p>

    <form method="">
        <select name="ordre" class="custom-select" required onchange="changeOrdre('')">
            <option value="questions.num_question ASC" selected>Trier par numéro de question (croissant)</option>
            <option value="questions.num_question DESC">Trier par numéro de question (décroissant)</option>
            <option value="questions.date_ajout ASC">Trier par date d'ajout (croissant)</option>
            <option value="questions.date_ajout DESC">Trier par date d'ajout (décroissant)</option>
            <option value="questions.num_domaine">Trier par domaine</option>
            <option value="domaines.niveau, questions.num_domaine">Trier par niveau (Première / Terminale)</option>
        </select>
    </form>

    <div class="form-group" id="questions-display">

    </div>
<?php
endif;
?>

<?php include("footer.php") ?>

</body>

<script>
    $('document').ready(function() {

        let anchorValue = "";
        let url = document.location;
        let strippedUrl = url.toString().split("#");
        if (strippedUrl.length > 1) {
            anchorValue = strippedUrl[1];
        }
        

        $('#fleche-bas-selection').click(function() {
            $('html, body').animate({
                scrollTop: $(document).height()
            }, 'slow');
            return false;
        });

        $('#fleche-1ere-selection').click(function() {
            $('html, body').animate({
                scrollTop: $('#niveau1').offset().top
            }, 'slow');
            return false;
        });

        $('#fleche-Term-selection').click(function() {
            $('html, body').animate({
                scrollTop: $('#niveauT').offset().top
            }, 'slow');
            return false;
        });

        $('#fleche-haut-selection').click(function() {
            $('html, body').animate({
                scrollTop: 0
            }, 'slow');
            return false;
        });

        $('.btn-suppr').click(function() {
            $('#confirmation-suppression' + this.id.substring(12)).css("display", "block");
        })

        $('.refus-suppr').click(function() {
            $('#confirmation-suppression' + this.id.substring(11)).css("display", "none");
        })

        $('.confirmation-suppr').click(function() {
            let datas = {
                num_question: this.id.substring(10)
            };
            $.post('suppr-question.php',
                datas,
                function(data) {
                    if (data == "success") {
                        window.location.href = 'modification.php'
                    } else {
                        console.log(data);
                    }
                },
                'text'
            )

        })

        changeOrdre(anchorValue);
        anchorValue = "";

    })

    function changeOrdre(anchor) {
        let datas = {
            ordre: $('[name=ordre]').val()
        };

        $.ajax({
            method: "POST",
            url: 'classement-quest-modif.php',
            data: datas
        }).done(function(data) {
            if (data != "error") {
                $('#questions-display').html(data);
                render_md_math();
                if (anchor != "") {
                    location.hash = "#" + anchor;
                }
            }
        })

        //render_md_math();

    }
</script>

</html>
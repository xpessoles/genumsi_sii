<?php

session_start();

if (!isset($_POST['ordre']) or is_null($_POST['ordre'])) {

    echo '<p>Vous n\'avez pas fourni toutes les infos</p>';
} else {

    /* Connexion à la base de données */
    include("connexionbdd.php");

    // L'utilisateur actuel est-il un admin ?
    $texte_req = "";
    if ($_SESSION['qualite'] == 'prof') {
        $texte_req = 'SELECT *, DATE_FORMAT(questions.date_ajout, "%d-%m-%Y") AS date_aj, utilisateurs.nom, utilisateurs.prenom
            FROM questions
            INNER JOIN domaines ON questions.num_domaine = domaines.num_domaine
            INNER JOIN utilisateurs ON questions.num_util = utilisateurs.num_util
            INNER JOIN sous_domaines ON questions.num_sous_domaine = sous_domaines.num_sous_domaine
            WHERE questions.num_util = ' . $_SESSION['num_util'] . '
            ORDER BY ' . $_POST['ordre'];
    } else if ($_SESSION['qualite'] == 'admin') {
        $texte_req = 'SELECT *,DATE_FORMAT(questions.date_ajout, "%d-%m-%Y") AS date_aj, utilisateurs.nom, utilisateurs.prenom
            FROM questions
            INNER JOIN domaines ON questions.num_domaine = domaines.num_domaine
            INNER JOIN utilisateurs ON questions.num_util = utilisateurs.num_util
            INNER JOIN sous_domaines ON questions.num_sous_domaine = sous_domaines.num_sous_domaine
            ORDER BY ' . $_POST['ordre'];
    }

    $req_domaines = $bdd->prepare($texte_req);
    $req_domaines->execute();

    $questions = $req_domaines->fetchAll();
?>

    <?php
    foreach ($questions as $question) :
    ?>
        <form action="modif-question.php" method="post">

            <div class='inp-group-selection'>
                <div class='input-group'>
                    <input type='hidden' name='num_question' value="<?= $question['num_question'] ?>">
                    <input type="text" class='form-control' value="Question n°<?= $question['num_question'] ?> - <?= $question['domaine'] ?> - <?= $question['sous_domaine'] ?> - (Auteur :  <?= $question['prenom'] ?> <?= $question['nom'] ?>, Ajout : <?= $question['date_aj'] ?>)" disabled>
                </div>

                <div class='question-selection'>
                    <div>
                        <?= $question['question'] ?>
                    </div>
                    <?php
                    if (!is_null($question['image'])) :
                    ?>

                        <a class='a-question' id="image_question_<?= $question['num_question'] ?>" href="image_questions/<?= $question['image'] ?>">
                            <img class='img-question' src="image_questions/<?= $question['image'] ?>">
                        </a>

                    <?php endif; ?>

                    <div class='reponse-qcm-valide'>
                        <p class='reponses-selection'>Réponses :</p>
                        <ul type='A' class=''>
                            <li class='reponse-selection-<?php if ($question['bonne_reponse'] == 'A') : echo 'bonne';
                                                            else : echo "mauvaise";
                                                            endif; ?>'><?= $question['reponseA'] ?></li>
                            <li class='reponse-selection-<?php if ($question['bonne_reponse'] == 'B') : echo 'bonne';
                                                            else : echo "mauvaise";
                                                            endif; ?>'><?= $question['reponseB'] ?></li>
                            <li class='reponse-selection-<?php if ($question['bonne_reponse'] == 'C') : echo 'bonne';
                                                            else : echo "mauvaise";
                                                            endif; ?>'><?= $question['reponseC'] ?></li>
                            <li class='reponse-selection-<?php if ($question['bonne_reponse'] == 'D') : echo 'bonne';
                                                            else : echo "mauvaise";
                                                            endif; ?>'><?= $question['reponseD'] ?></li>

                        </ul>
                    </div>
                </div>
            </div>

            <button class='btn btn-info btn-valide' id='modif-button<?= $question['num_question'] ?>'>Modifier cette question</button>

            <?php if ($_SESSION['qualite'] == 'admin') : ?>
                <button class='btn btn-info btn-valide btn-suppr' type='button' id='suppr-button<?= $question['num_question'] ?>'>Supprimer cette question</button>

                <div class='confirmation-suppression' id='confirmation-suppression<?= $question['num_question'] ?>' style='display:none;'>
                    <h3>Etes-vous sûr de vouloir supprimer cette question ?</h3>
                    <div id='boutons-suppr'>
                        <button class='btn btn-success btn-valide refus-suppr' type='button' id='refus-suppr<?= $question['num_question'] ?>'>Non</button>
                        <button class='btn btn-danger btn-valide confirmation-suppr' type='button' id='conf-suppr<?= $question['num_question'] ?>'>Oui</button>
                    </div>
                </div>
            <?php endif; ?>

        </form>
<?php
    endforeach;
};
?>

<script>
    $("document").ready(function() {
        $(".a-question").each(function() {
            $("#" + this.id).fancybox({
                helpers: {
                    title: {
                        type: 'inside'
                    }
                }
            })
        })

        <?php if ($_SESSION['qualite'] == 'admin') : ?>

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
        <?php endif; ?>

        //render_md_math();

    })
</script>
<?php

session_start();

if (!isset($_POST['requete']) or is_null($_POST['requete'])) {
    echo '<p>Vous n\'avez pas fourni toutes les infos</p>';
} else {
    /* Connexion à la base de données */
    include("connexionbdd.php");

    $tab_requete = explode(" ", $_POST['requete']);

    $texte_requete = 'SELECT *,  DATE_FORMAT(questions.date_ajout, "%d-%m-%Y") AS date_aj, utilisateurs.nom, utilisateurs.prenom FROM questions INNER JOIN domaines ON questions.num_domaine = domaines.num_domaine INNER JOIN sous_domaines ON questions.num_sous_domaine = sous_domaines.num_sous_domaine INNER JOIN utilisateurs ON questions.num_util = utilisateurs.num_util WHERE ';

    foreach ($tab_requete as $mot) {
        $texte_requete .= "question LIKE '%" . $mot . "%' AND ";
    }

    $texte_requete = substr($texte_requete, 0, -4);

    $req_questions = $bdd->prepare($texte_requete);
    $req_questions->execute();

    while ($question = $req_questions->fetch()) :
        ?>
        <div class='inp-group-selection'>
            <div class='input-group'>
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <input class='checkbox-question' type="checkbox" aria-label="Choisir cette question" id='<?= $question['num_question'] ?>'>
                    </div>
                </div>
                <input type="text" class='form-control' value="Question n°<?= $question['num_question'] ?> - <?= $question['domaine'] ?> - <?= $question['sous_domaine'] ?> - (Auteur :  <?= $question['prenom'] ?> <?= $question['nom'] ?>, Ajout : <?= $question['date_aj'] ?>)" disabled>
            </div>

            <div class='question-selection'>
                <?= $question['question'];
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

<?php endwhile;
}
?>
<script>
    $(".a-question").each(function() {
        $("#" + this.id).fancybox({
            helpers: {
                title: {
                    type: 'inside'
                }
            }
        })
    })

    $('.checkbox-question').change(function() {
        let id_question = this.id;
        if (this.checked) {
            nb_questions++;
            if (nb_questions == 1) {
                texte_cle = "" + this.id;
            } else {
                texte_cle += ";" + this.id;
            }
        } else {
            nb_questions--;
            let questions = texte_cle.split(";");
            texte_cle = "";
            questions.forEach(function(valeur, indice) {
                if (valeur != id_question) {
                    texte_cle += valeur + ";";
                }
            })
            texte_cle = texte_cle.substr(0, texte_cle.length - 1);
        }

        if (!nb_questions) {
            $('#bas').html("<b>Vous n'avez choisi aucune question...</b>");
        } else if (nb_questions == 1) {
            $('#bas').html("<p><b>Vous avez choisi " + nb_questions + " question. La clé de votre QCM est " + texte_cle + "<b></p>");
        } else {
            $('#bas').html("<p><b>Vous avez choisi " + nb_questions + " questions. La clé de votre QCM est " + texte_cle + "</b></p>");
        }

        render_md_math()
    })
</script>
<?php session_start();
include("head.php");

?>
<div id="conteneur-general">
    <?php
    // Connexion à la base de données avec PDO
    include("connexionbdd.php");


    // Récupération de la clé du QCM (au format num_question;num_question...)
    $cle = base64_decode(urldecode($_POST['cle']));
    // Création de la chaîne de caractère (num_question, num_question...) nécessaire
    // à la requête SQL
    $num_questions = explode(';', $cle);

    // Création de la note (3 points si réponse correcte, -1 si faux, 0 si absence de réponse)
    $note = 0;
    $total_note = 3 * count($num_questions);

    // Affichage du corrigé
    ?>
    <section class='qcm'>
        <h1 class='h1-qcm'>Corrigé</h1>

        <table>
            <tr>
                <td>Nom : </td>
                <td><input type="text" name="nom_eleve" size="30" disabled value="<?= $_POST['nom_eleve'] ?>"></td>
            </tr>
            <tr>
                <td>Prénom : </td>
                <td><input type="text" name="prenom_eleve" size="30" disabled value="<?= $_POST['prenom_eleve'] ?>"></td>
            </tr>
            <tr>
                <td>Classe : </td>
                <td><input type="text" name="classe_eleve" size="10" disabled value="<?= $_POST['classe_eleve'] ?>"></td>
            </tr>
        </table>

        <ul>
            <?php
            $i = 1;
            foreach ($num_questions as $num) {
                $texte_req = 'SELECT num_question, reponseA, reponseB, reponseC, reponseD, bonne_reponse FROM questions INNER JOIN domaines ON questions.num_domaine = domaines.num_domaine  WHERE num_question = ' . $num;
                $question = $bdd->prepare($texte_req);
                $question->execute();

                $reponse = $question->fetch();

                if ($_POST['reponse' . $reponse['num_question']] == "none") {
                    ?>
                    <li>
                        <p>Pour la question n°<?= $i ?> vous n'avez pas répondu</p>

                    <?php
                        } else {
                            ?>
                    <li>
                        <p>Pour la question n°<?= $i ?> vous avez répondu :</p>
                        <div class='input-group'>
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input type="radio" disabled checked>
                                </div>
                            </div>
                            <span class='form-control'><?= $reponse['reponse' . $_POST['reponse' . $reponse['num_question']]] ?></span>
                        </div>

                        <br>
                    <?php
                        }
                        ?>
                    <p>La bonne réponse était :</p>

                    <div class='input-group'>
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <input type="radio" disabled checked>
                            </div>
                        </div>
                        <span class='form-control'><?= $reponse['reponse' . $reponse['bonne_reponse']] ?></span>
                    </div>

                    <br>
                    </li>

                <?php

                    // Calcul de la note
                    if ($_POST['reponse' . $reponse['num_question']] != "none") {
                        if ($_POST['reponse' . $reponse['num_question']] == $reponse['bonne_reponse']) {
                            echo "<b>Bien joué !</b><br><br>";
                            $note += 3;
                        } else {
                            echo "<b>C'est dommage...</b><br><br>";
                            $note -= 1;
                        }
                    } else {
                        echo "<b>Vous n'avez pas perdu de point</b><br><br>";
                    }

                    $i++;
                }
                $note = max(0, $note);
                $note_sur_20 = round($note / $total_note * 20, 2);
                ?>
        </ul>
        <h2>Vous avez donc obtenu <?= $note ?> sur <?= $total_note ?> points</h2>
        <h2>Soit <?= $note_sur_20 ?> sur 20</h2>

    </section>
</div>

</body>

<script>
    $(document).ready(function() {
        let datas = {
            nom_eleve: $('[name=nom_eleve]').val(),
            prenom_eleve: $('[name=prenom_eleve]').val(),
            classe_eleve: $('[name=classe_eleve]').val(),
            num_prof: "<?= $_POST['p'] ?>",
            note_qcm: <?= $note_sur_20 ?>,
            cle_qcm: "<?= $_POST['cle'] ?>",
        }

        $.post("insertion-resultat.php", datas);

    })
</script>

</html>
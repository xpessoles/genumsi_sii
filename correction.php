<?php session_start();
include("head.php");

// Début error log
error_log("correction.php");
error_log("Identifiant: " . $_POST['nom_eleve']);
$ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
error_log("IP_addr : " . $ip);
// Fin error log

?>
<div id="conteneur-general">
    <?php
    // Connexion à la base de données avec PDO
    include("connexionbdd.php");

    unset($_SESSION['premier_chargement']);

    // Récupération de la clé du QCM (au format num_question;num_question...)
    $cle = base64_decode($_POST['cle']);
    // Création de la chaîne de caractère (num_question, num_question...) nécessaire
    // à la requête SQL
    $num_questions = explode(';', $cle);

    // Création de la note (3 points si réponse correcte, -1 si faux, 0 si absence de réponse)
    $points = 0;
    $points_bon = $_POST['b'];
    $points_mauvais = -$_POST['m'];
    $total_points = $points_bon * count($num_questions);
    $i = 1;

    $points_domaine = 0;
    $nb_reponse_bonne_domaine = 0;
    $nb_reponse_fausse_domaine = 0;
    $nb_reponse_nesaispas_domaine = 0;

    $nb_reponse_bonne_total = 0;
    $nb_reponse_fausse_total = 0;
    $nb_reponse_nesaispas_total = 0;

    $num_current_domaine = 0;
    $nom_current_domaine = "---";
    $table_stat_qcm = "<table class='table-stat-qcm table table-hover table-bordered table-striped'><tr><th>Domaine</th><th>Bonne réponse</th><th>Mauvaise réponse</th><th>Je ne sais pas</th><th>Points</th></tr>";

    $texte_reponses = "";

    // Affichage du corrigé
    ?>
    <section class='qcm'>
        <h1 class='h1-qcm'>Corrigé</h1>

        <h2>Votre identifiant : <b><?= $_POST['nom_eleve'] ?></b></h2>
        <h2 id="note-qcm"></h2>
        <h4>Une bonne réponse rapporte <?= $points_bon ?> point(s). Une mauvaise retire <?= -$points_mauvais ?> point(s)</h4>
        <h2>Résultats par domaines :</h2>
        <div id="stat-qcm"></div>
        <p>Pour chaque colonne <strong>Bonne Réponse</strong>, <strong>Mauvaise réponse</strong> et <strong>Je ne sais pas</strong> est indiqué le nombre de questions associées.</p>
        <br>

        <ul>
            <?php
            foreach ($num_questions as $num) {

                $texte_reponses .= $num . ";";

            ?>

                <?php
                $texte_req = 'SELECT num_question, questions.num_domaine,domaines.domaine, question, reponseA, reponseB, reponseC, reponseD, bonne_reponse, image FROM questions INNER JOIN domaines ON questions.num_domaine = domaines.num_domaine  WHERE num_question = ' . $num;
                $question = $bdd->prepare($texte_req);
                $question->execute();

                $reponse = $question->fetch();

                if ($reponse['num_domaine'] != $num_current_domaine) {
                    if ($num_current_domaine != 0) {
                        $points_domaine = max(0, $points_domaine);
                        $table_stat_qcm = $table_stat_qcm . "<tr><td>" . $nom_current_domaine . "</td><td>" . $nb_reponse_bonne_domaine . "</td><td>" . $nb_reponse_fausse_domaine . "</td><td>" . $nb_reponse_nesaispas_domaine . "</td><td>" . $points_domaine . "</td></tr>";
                        $points += $points_domaine;
                    }
                    $num_current_domaine = $reponse['num_domaine'];
                    $nom_current_domaine = $reponse['domaine'];
                    $points_domaine = 0;

                    $nb_reponse_bonne_total += $nb_reponse_bonne_domaine;
                    $nb_reponse_fausse_total += $nb_reponse_fausse_domaine;
                    $nb_reponse_nesaispas_total += $nb_reponse_nesaispas_domaine;

                    $nb_reponse_bonne_domaine = 0;
                    $nb_reponse_fausse_domaine = 0;
                    $nb_reponse_nesaispas_domaine = 0;
                    echo "<h2>" . $nom_current_domaine . "</h2>";
                }
                ?>
                <div class="correction-question">
                    <li>
                        <p>Pour la question n°<?= $i ?> :</p>
                        <?php
                        if ($_POST['reponse' . $reponse['num_question']] == "none") {
                            $texte_reponses .= "0;";

                        ?>
                            <div class="texte-question">
                                <?= $reponse['question'] ?>
                                <?php
                                if (!is_null($reponse['image'])) :
                                ?>

                                    <a class='a-question' id="image_question_<?= $reponse['num_question'] ?>" href="image_questions/<?= $reponse['image'] ?>">
                                        <img class='img-question' src="image_questions/<?= $reponse['image'] ?>">
                                    </a>

                                <?php endif; ?>
                            </div>
                            <p>vous n'avez pas répondu</p>

                        <?php
                        } else {

                            $texte_reponses .= $_POST['reponse' . $reponse['num_question']] . ";";

                        ?>
                            <div class="texte-question">
                                <?= $reponse['question'] ?>
                                <?php
                                if (!is_null($reponse['image'])) :
                                ?>

                                    <a class='a-question' id="image_question_<?= $reponse['num_question'] ?>" href="image_questions/<?= $reponse['image'] ?>">
                                        <img class='img-question' src="image_questions/<?= $reponse['image'] ?>">
                                    </a>

                                <?php endif; ?>
                            </div>
                            <p>vous avez répondu :</p>
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
                            echo "<b>Bien joué !</b>";
                            $points_domaine += $points_bon;
                            $nb_reponse_bonne_domaine += 1;
                        } else {
                            echo "<b>C'est dommage...</b>";
                            $points_domaine += $points_mauvais;
                            $nb_reponse_fausse_domaine += 1;
                        }
                    } else {
                        $nb_reponse_nesaispas_domaine += 1;
                        echo "<b>Vous n'avez pas perdu de point</b>";
                    }

                    $i++;
                    ?>
                </div>
            <?php
            }
            $points_domaine = max(0, $points_domaine);
            $table_stat_qcm = $table_stat_qcm . "<tr><td>" . $nom_current_domaine . "</td><td>" . $nb_reponse_bonne_domaine . "</td><td>" . $nb_reponse_fausse_domaine . "</td><td>" . $nb_reponse_nesaispas_domaine . "</td><td>" . $points_domaine . "</td></tr>";
            $nb_reponse_bonne_total += $nb_reponse_bonne_domaine;
            $nb_reponse_fausse_total += $nb_reponse_fausse_domaine;
            $nb_reponse_nesaispas_total += $nb_reponse_nesaispas_domaine;
            $nb_reponse_total =  $nb_reponse_bonne_total + $nb_reponse_fausse_total + $nb_reponse_nesaispas_total;
            $table_stat_qcm = $table_stat_qcm . "<tr><td> <strong>Récapitulatif global </strong></td><td><strong>" . $nb_reponse_bonne_total . "</strong></td><td><strong>" . $nb_reponse_fausse_total . "</strong></td><td><strong>" . $nb_reponse_nesaispas_total . "</strong></td><td><strong>" . $total_points . "</strong></td></tr>";

            $table_stat_qcm = $table_stat_qcm . "</table>";
            $table_stat_qcm = $table_stat_qcm . "Ce QCM comporte un total de " . $nb_reponse_total . " questions.";

            $points += $points_domaine;
            $note_sur_20 = round($points / $total_points * 20, 2);

            $texte_reponses = substr($texte_reponses, 0, -1);

            // Récupération IP
            function get_ip_address(){
                foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
                    if (array_key_exists($key, $_SERVER) === true){
                        foreach (explode(',', $_SERVER[$key]) as $ip){
                            $ip = trim($ip); // just to be safe
            
                            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                                return $ip;
                            }
                        }
                    }
                }
            }

            $ip_source = get_ip_address();
            $source = hash('haval256,5', $cle . $ip_source);

            // Ajout d'une entrée dans la table résultat	
            $req = $bdd->prepare('INSERT INTO resultats (nom_eleve,prenom_eleve, classe_eleve, num_prof, note_qcm, cle_qcm, reponses, origine) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $req->execute(array(
                $_POST['nom_eleve'],
                "prenom",
                "classe",
                $_POST['num_prof'],
                $note_sur_20,
                $cle,
                $texte_reponses,
                $source
            ));
            //

            ?>
        </ul>

    </section>
</div>

</body>

<script>
    $(document).ready(function() {
        $('#note-qcm').html("<p>Vous avez obtenu : <b><?= $points ?> sur <?= $total_points ?> points</b></p><p>Soit : <b><?= $note_sur_20 ?> sur 20</b></p>");
        $('#stat-qcm').html("<?= $table_stat_qcm ?>");

        $(".a-question").each(function() {
            $("#" + this.id).fancybox({
                helpers: {
                    title: {
                        type: 'inside'
                    }
                }
            })
        })

        render_md_math()

    })
</script>

</html>
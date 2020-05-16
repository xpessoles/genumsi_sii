<?php session_start();
include("config.php");

if (!isset($_SESSION['premier_chargement'])) :
    $_SESSION['premier_chargement'] = true;
else :
    $_SESSION['premier_chargement'] = false;
endif;

include("head.php");

?>

<div id='bandeau-qcm'>
    Votre identifiant : <?= $_POST['i'] ?>
</div>

<div id="conteneur-general">

    <?php
    // Connexion à la base de données avec PDO
    include("connexionbdd.php");

    // Récupération des informations du qcm
    $req_qcm = $bdd->prepare('SELECT * FROM qcms WHERE hash_qcm = ?');
    $req_qcm->execute([$_POST['h']]);

    $reponse = $req_qcm->fetch();


    // Affichage du qcm
    if (count($reponse) > 0 and $reponse['actif'] == 1) :

        // Récupération de la clé du QCM (au format num_question;num_question...)
        $cle = $reponse['cle_qcm'];
        $points_bonne_reponse = $reponse['points_plus'];
        $points_mauvaise_reponse = $reponse['points_moins'];

        // Création de la chaîne de caractère (num_question, num_question...) nécessaire
        // à la requête SQL
        $num_questions = explode(';', $cle);
        $ordres_rep = array();

        $tab_requete = "(";
        $rang = 0;
        foreach ($num_questions as $num) :
            $tab_requete = $tab_requete . $num . ",";
            $ordres_rep[$rang] = "ABCD";
            $rang++;
        endforeach;
        $tab_requete = substr($tab_requete, 0, -1) . ")";

        if ($reponse['triche'] == 1) : ?>
            <div style="display: none;" id="changement-page">
                <h2>Vous avez quitté ou rechargé la page du QCM</h2>
                <h3>Veuillez attendre votre professeur</h3>

                <div class="input-box">
                    <div id="fake-input">&nbsp;</div>
                    <input type="text" id="code-prof" autocomplete="off">
                    <div class='reponse-ajax' id='id-reponse-qcm'>&nbsp;</div>
                </div>
            </div>
            <a data-fancybox data-src="#changement-page" data-modal="true" href="changement-page" class="btn btn-primary" id='lien-changement'></a>

        <?php endif ?>


        <section class='qcm'>
            <h1 class='h1-qcm'>QCM de <?= $GENUMNAME ?></h1>
            <h4>
                <p>Une bonne réponse rapporte <?= $points_bonne_reponse ?> point(s). Une mauvaise retire <?= $points_mauvaise_reponse ?> point(s)</p>
                <p>Une absence de réponse n'est pas pénalisée</p>
                <p>Les points sont comptabilisés par domaine</p>
            </h4>
            <form method='POST' action='correction.php' id="formulaire">

                <?php
                // Récupération de toutes les domaines correspondants aux questions du POST
                $texte_req = 'SELECT questions.num_domaine FROM questions INNER JOIN domaines ON questions.num_domaine = domaines.num_domaine WHERE num_question IN ' . $tab_requete . '  GROUP BY domaines.num_domaine';
                $domaines = $bdd->prepare($texte_req);
                $domaines->execute();

                $domaines = $domaines->fetchAll(PDO::FETCH_ASSOC);

                $domaine_precedent = '';

                $numero_q = 1;

                $cle = '';
                $num_q = 0;

                foreach ($domaines as $domaine) :

                    // Récupération de toutes les questions correspondants aux numéros du POST
                    $texte_req = 'SELECT * FROM questions INNER JOIN domaines ON questions.num_domaine = domaines.num_domaine  WHERE num_question IN ' . $tab_requete . ' AND questions.num_domaine = ? ORDER BY num_question';
                    $questions = $bdd->prepare($texte_req);
                    $questions->execute(array($domaine['num_domaine']));

                    $questions = $questions->fetchAll();

                    shuffle($questions);

                    foreach ($questions as $question) :
                        if ($question['domaine'] != $domaine_precedent) :
                ?>
                            <div class='col-md-12'>
                                <h2 class='h2-domaine'><?= $question['domaine'] ?></h2>
                            </div>

                <?php
                            $domaine_precedent = $question['domaine'];
                        endif;

                        $cle .= $question['num_question'] . ';';
                        $ordres_rep[$num_q] = str_shuffle($ordres_rep[$num_q]);
                        $num_q++;

                        include("question.php");
                    endforeach;
                endforeach;

                $cle = substr($cle, 0, -1);
                ?>

                <input type="hidden" name="nom_eleve" size="30" value="<?= $_POST['i'] ?>" required>
                <input type="hidden" name="prenom_eleve" size="30" value="prenom" required>
                <input type="hidden" name="classe_eleve" size="10" value="classe" required>
                <input type="hidden" name="b" size="10" value="<?= $points_bonne_reponse ?>">
                <input type="hidden" name="m" size="10" value="<?= $points_mauvaise_reponse ?>">
                <input type="hidden" name="cle" value="<?= base64_encode($reponse['cle_qcm']) ?>">
                <input type="hidden" name="num_prof" value="<?= $reponse['num_prof'] ?>">

                <div id="confirmation_envoi_qcm">
                    <div class='input-group'>
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <input type="checkbox" name="pret_a_envoyer" id="pret_a_envoyer">
                            </div>
                        </div>
                        <span class='form-control'>Je confirme avoir terminé mon QCM</span>
                    </div>
                    <button class='btn btn-warning' id='envoi-reponse' type='button'>Envoyer les réponses</button>
                </div>
            </form>

        </section>


    <?php else :
        echo "<br><br><br><br>";
        echo "Le QCM ne peux pas être complété pour l'instant, il a été bloqué par votre professeur";
    endif
    ?>

</div>

</body>

<script>
    $('document').ready(function() {

        // Empêche la frappe d'entrée sur le bouton de poster le qcm
        $("#envoi-reponse").on('keydown', function(event) {
            if (event.key == "Enter") event.preventDefault();
        });

        // Empêche la frappe d'entrée dans le formulaire de poster le qcm
        $("#formulaire").on('keydown', function(event) {
            if (event.key == "Enter") event.preventDefault();
        });

        render_md_math()

        <?php if ($reponse['triche'] == 1) : ?>
            // Désormais on intercepte les évènements de blur...
            $(window).blur(function() {
                changement_onglet = true;
            })

            // ... et de focus
            $(window).focus(function() {
                if (changement_onglet) {
                    $("#lien-changement").fancybox().trigger('click');
                    changement_onglet = false;
                }
            })
        <?php endif; ?>

        let changement_onglet = false;

        <?php if (isset($_SESSION['premier_chargement']) and !$_SESSION['premier_chargement'] and $reponse['triche'] == 1) : ?>
            // La page est rechargée (vérification côté serveur)
            $("#lien-changement").fancybox().trigger('click');
        <?php endif ?>

        <?php if ($reponse['triche'] == 1) : ?>
            // La page est rechargée (vérification côté client)
            if (performance.navigation.type > 0) {
                $("#lien-changement").fancybox().trigger('click');
            } else {
                $("#lien-fancy").fancybox().trigger('click');
            }
        <?php else : ?>
            $("#lien-fancy").fancybox().trigger('click');
        <?php endif ?>

        <?php if ($reponse['triche'] == 1) : ?>
            // Gestion du mdp saisi par le prof
            $('#code-prof').on('keyup', function(e) {
                $('#id-reponse-qcm').html("");
                $('#fake-input').html("");
                if (e.keyCode == 13) {
                    let datas = {
                        code: $('#code-prof').val(),
                        p: "<?= base64_encode($reponse['num_prof']) ?>"
                    }
                    // on vide le code prof
                    $('#code-prof').val("");

                    $.post("check-code.php", datas, function(data) {
                        if (data == "success") {
                            changement_onglet = false
                            $.fancybox.close();
                            if ($('[name=nom_eleve]').val() == "") {
                                $("#lien-fancy").fancybox().trigger('click');
                            }
                        } else {
                            $('#id-reponse-qcm').html("Le mot de passe est incorrect");
                        }
                    })
                } else {
                    inputValue = $('#code-prof').val();
                    numChars = inputValue.length;
                    showText = "";

                    for (i = 0; i < numChars; i++) {
                        showText += "&#8226;";
                    }
                    $('#fake-input').html(showText);
                }
            });

        <?php endif ?>

        // Confirmation de l'enoi du qcm avec une checkbox 
        $('#envoi-reponse').click(function() {
            // si la valeur du champ prenom est non vide

            if ($("#pret_a_envoyer").is(':checked')) {
                // les données sont ok, on peut envoyer le formulaire    
                $("#formulaire").submit();
            } else {
                // sinon on affiche un message
                alert("Vous devez valider votre envoi en cochant la case :\n\n\"Je confirme avoir terminé mon QCM\"");
                // et on indique de ne pas envoyer le formulaire
                return false;
            }
        })
    })
</script>

</html>
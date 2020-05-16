<?php session_start();
include("head.php");

if (empty($_SESSION) or $_SESSION['connecte'] != true) :
    include("header.php");
    echo "Vous ne devriez pas être ici : <a href='index.php'>Retour</a>";
else :
    include('connexionbdd.php');
    include("header.php");
    include("nav.php");

    $req_domaines = $bdd->prepare("SELECT * FROM sous_domaines INNER JOIN domaines  ON domaines.num_domaine = sous_domaines.num_domaine ORDER BY sous_domaines.num_domaine,sous_domaines.num_sous_domaine");
    $req_domaines->execute();
    $domaines = $req_domaines->fetchAll();

?>

<?php if ($GENUMMATIERE == "NSI") : ?>
    <div style="display: none;" id="mise_en_garde">
        <center>
            <h3>Pour des raisons de confidentialité, merci de <br><br><b>ne pas saisir de questions provenant de la banque de sujet nationale</b></h3>
        </center>
    </div>

    <a data-fancybox data-src="#mise_en_garde" data-modal="false" href="mise_en_garde" class="btn btn-primary" id='lien-fancy'></a>
<?php endif; ?>	

    <h1 class='h1-qcm'>Ajout de question</h1>

    <p>Vous pouvez dans cette page saisir une nouvelle question. Il suffit de compléter tous les champs proposés.</p>

    <p>Vous pouvez utiliser du <code>html</code> dans les champs de saisie puis cliquer sur le bouton <strong>Voir la question</strong> afin de voir le rendu. Votre saisie sera automatiquement nettoyée.</p>

    <p>Utiliser les balises <code>&lt;pre&gt;&lt;code&gt;...&lt;/code&gt;&lt;/pre&gt;</code> afin de délimiter du code</p>

    <p>Vous pouvez indiquer le langage utilisé dans les balises de code et ainsi mettre en forme son rendu en faisant :</p>
    <p>    <code>&lt;pre&gt;&lt;code class="html"&gt;...&lt;/code&gt;&lt;/pre&gt;</code> ou 
        <code>&lt;pre&gt;&lt;code class="python"&gt;...&lt;/code&gt;&lt;/pre&gt;</code>
    </p>

    <p>Si vous souhaitez insérer du code <code>html</code> en tant que "texte" d'une questions, échappez les balises avec <code><?= htmlentities("&lt;") ?></code>
        et <code><?= htmlentities("&gt;") ?></code></p>

    <p>Vous pouvez aussi saisir du <a href="https://fr.wikipedia.org/wiki/Markdown">Markdown</a>. Pour ce faire tapez le au sein d'une balise de classe <code>md</code></p>

    <p>Enfin, vous pouvez saisir des \(maths\) en utilisant la syntaxe de base de <a href="https://www.mathjax.org/">MathJax</a> (avec les <code>\( \)</code>)</p>

    <p style="color:red;">Avant d'ajouter une question assurez vous que celle-ci n'est pas déjà présente dans la base.</p>
    <p style="color:red;">Pour cela il vous suffit d'aller dans le menu <strong>Créer un QCM/Rechercher une question</strong> et de faire une recherche
        sur quelques mots clés bien choisis.</p>


    <form id='formulaire-ajout' method='post' action='verification-ajout.php' enctype="multipart/form-data">
        <section class='saisie'>

            <p class='instruction'>Saisir ci-dessous le code html de votre question</p>

            <textarea rows="20" cols="50" id='inp-question' class='inp' name="question" form_id='formulaire-ajout' onclick="selection(this,0)">
<div class="md">## Un titre en Markdown !</div>
<br>
<p>La complexité du tri par sélection est en \(O(n^2)\)</p>
<p>On a saisi le code suivant :</p>
<pre><code class="html">
n = 5
s = 0
while n>=0:
    s = s+n
    n = n-1
</code></pre>
Que contient la variable s à la fin de l’exécution de ce script ?
</textarea>

        </section>

        <section class='saisie'>

            <p class='instruction'>Saisir ci-dessous le code html de votre réponse A</p>

            <textarea rows="10" cols="50" id='inp-repA' class='inp-rep' name="reponseA" form_id='formulaire-ajout' onclick="selection(this,1)">Saisir le code de la réponse A</textarea>

        </section>


        <section class='saisie'>

            <p class='instruction'>Saisir ci-dessous le code html de votre réponse B</p>

            <textarea rows="10" cols="50" id='inp-repB' class='inp-rep' name="reponseB" form_id='formulaire-ajout' onclick="selection(this,2)">Saisir le code de la réponse B</textarea>

        </section>


        <section class='saisie'>

            <p class='instruction'>Saisir ci-dessous le code html de votre réponse C</p>

            <textarea rows="10" cols="50" id='inp-repC' class='inp-rep' name="reponseC" form_id='formulaire-ajout' onclick="selection(this,3)">Saisir le code de la réponse C</textarea>


        </section>


        <section class='saisie'>

            <p class='instruction'>Saisir ci-dessous le code html de votre réponse D</p>

            <textarea rows="10" cols="50" id='inp-repD' class='inp-rep' name="reponseD" form_id='formulaire-ajout' onclick="selection(this,4)">Saisir le code de la réponse D</textarea>

        </section>

        <section class='saisie'>

            <p class='instruction'>Charger une image (si besoin)</p>
            <p class='instruction'>Seuls les formats jpg, jpeg et png sont acceptés</p>
            <p class='instruction'>Le fichier doit faire moins de 300 ko</p>

            <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
                    <input type="file" accept=".jpg, .jpeg, .png, image/*" class="custom-file-input" type="file" name="file" id="file">
                    <label class="custom-file-label" id="file-label" for="inputGroupFile01">Choisir un fichier...</label>
                </div>
            </div>

        </section>

        <section class='saisie'>

            <p class='instruction'>Indiquer quelle est la bonne réponse</p>

            <select name="bonne_reponse" class="custom-select" required>
                <option value="" id="bonne_rep_g" selected>Choisir une bonne réponse...</option>
                <option value="A" id="rep_A_g">A</option>
                <option value="B" id="rep_B_g">B</option>
                <option value="C" id="rep_C_g">C</option>
                <option value="D" id="rep_D_g">D</option>
            </select>

        </section>

        <section class='saisie'>

            <p class='instruction'>Indiquer le domaine concerné</p>

            <select name="num_domaine_sous_domaine" class="custom-select" required>

                <option value="">Choisir un domaine...</option>
                <?php foreach ($domaines as $domaine) : ?>
                    <option value="<?= $domaine['num_domaine'] . "-" . $domaine['num_sous_domaine'] ?>"><?= $domaine['domaine'] . " - " . $domaine['sous_domaine'] ?></option>
                <?php endforeach ?>
            </select>

        </section>

        <section class='saisie'>

            <button class='btn btn-info' type='button' onclick='rendu()' id="rendu_g">Voir la question</button>

            <div id='div-rendu' class='div-rendu'>
                <p id='rendu-q'>
                    Saisir le code de la question
                </p>

                <img id="rendu-img" class='img-question' src="#" alt="Image à insérer" />

                <div class='input-group rendu-reponse' id='div-rendu-repA'>
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="radio" disabled>
                        </div>
                    </div>
                    <span class='form-control' id='rendu-repA'>Reponse A</span>
                </div>

                <br>

                <div class='input-group rendu-reponse' id='div-rendu-repB'>
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="radio" disabled>
                        </div>
                    </div>
                    <span class='form-control' id='rendu-repB'>Reponse B</span>
                </div>

                <br>

                <div class='input-group rendu-reponse' id='div-rendu-repC'>
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="radio" disabled>
                        </div>
                    </div>
                    <span class='form-control' id='rendu-repC'>Reponse C</span>
                </div>

                <br>

                <div class='input-group rendu-reponse' id='div-rendu-repD'>
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="radio" disabled>
                        </div>
                    </div>
                    <span class='form-control' id='rendu-repD'>Reponse D</span>
                </div>

                <br>

                <div class='input-group rendu-reponse'>
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="radio" disabled>
                        </div>
                    </div>
                    <span class='form-control'>Je ne sais pas...</span>
                </div>

                <br>
            </div>

        </section>

        <button class='btn btn-info' type='submit' id="insertion_g">Insérer la question dans la base</button>


    </form>
<?php
endif;
include("footer.php");
?>

</body>

<script>
    function rendu() {

        let question = $('#inp-question').val();
        let repA = $('#inp-repA').val();
        let repB = $('#inp-repB').val();
        let repC = $('#inp-repC').val();
        let repD = $('#inp-repD').val();

        let datas = {
            question: question,
            repA: repA,
            repB: repB,
            repC: repC,
            repD: repD
        };

        $.post('purification.php',
            datas,
            function(data) {
                if (data != "error") {
                    let cleans = data.split("---purification---");
                    question = cleans[0];
                    repA = cleans[1];
                    repB = cleans[2];
                    repC = cleans[3];
                    repD = cleans[4];
                    $('#inp-question').val(question);
                    $('#inp-repA').val(repA);
                    $('#inp-repB').val(repB);
                    $('#inp-repC').val(repC);
                    $('#inp-repD').val(repD);

                    let bonne_rep = $('[name=bonne_reponse]').val();
                    let divs_rep = $('.rendu-reponse');
                    divs_rep.removeClass('rendu-bonne-reponse');
                    let div_bonne_rep = $('#div-rendu-rep' + bonne_rep);
                    div_bonne_rep.addClass('rendu-bonne-reponse');

                    let rendu_q = $('#rendu-q');
                    let rendu_repA = $('#rendu-repA');
                    let rendu_repB = $('#rendu-repB');
                    let rendu_repC = $('#rendu-repC');
                    let rendu_repD = $('#rendu-repD');

                    rendu_q.html(question)
                    rendu_repA.html(repA)
                    rendu_repB.html(repB)
                    rendu_repC.html(repC)
                    rendu_repD.html(repD)

                    let input = document.getElementById('file');


                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            $('#rendu-img').attr('src', e.target.result);
                        }

                        reader.readAsDataURL(input.files[0]);
                    }

                    render_md_math()
                }
            },
            'text'
        )

    }

    $("document").ready(function() {
        $("#lien-fancy").fancybox().trigger('click');

        rendu();

        $("#file").change(function() {
            $('#file-label').html($('#file').val().split(/(\\|\/)/g).pop())
        });
    });

    function getExtension(filename) {
        var parts = filename.split('.');
        return parts[parts.length - 1];
    }

    function isImage(filename) {
        var ext = getExtension(filename);
        switch (ext.toLowerCase()) {
            case 'jpg':
            case 'jpeg':
            case 'png':
                return true;
        }
        return false;
    }

    function checkSize(f) {
        if (f[0].files[0].size > 300000) {
            return false;
        }
        return true;
    }


    $(function() {
        $('#file').change(function() {
            function failValidation(msg) {
                alert(msg);
                $('#file-label').html('Choisir un fichier');
                file.val("");
                return false;
            }

            var file = $('#file');
            if (!isImage(file.val())) {
                return failValidation('Choisir un fichier jpg, jpeg ou png');
            } else if (!checkSize(file)) {
                return failValidation('Le fichier doit faire moins de 300 ko');
            }

            return false;
        });

    })

    first_selection = [true, true, true, true, true]

    function selection(elt, index) {
        if (first_selection[index]) {
            first_selection[index] = false
            elt.focus();
            elt.select();
        }
    }
</script>

</html>
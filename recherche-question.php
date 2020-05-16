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
    <h1 class='h1-qcm'>Recherche de questions</h1>

    <p>Vous pouvez ici rechercher des questions par mots clé</p>
    <p>Si une question vous intéresse vous pouvez noter son numéro et l'intégrer dans un QCM en sélectionnant par liste par exemple</p>
    <br>
    <p>A ce stade la fonction de recherche est rudimentaire. Elle cherhche les questions contenant <b>tous</b> les mots saisis dans votre requête</p>


    <div class="form-group">
        <label for="usr"><b>Mot(s)-clé(s):</b></label>
        <input type="text" class="form-control" name="requete">
        <button id="btn-requete" class="btn btn-info">Lancer la recherche</button>
    </div>

    <div id="questions-display"></div>


<?php
endif;
?>

<?php include("footer.php") ?>

</body>

<script>
    function post_requete() {
        let datas = {
            requete: $('[name=requete]').val()
        };

        $.post('requete-question.php',
            datas,
            function(data) {
                $('#questions-display').html(data);
                render_md_math()
            },
            'text'
        )
        
    }


    $('document').ready(function() {
        $('#btn-requete').click(post_requete);

        $('[name=requete]').keypress(function(e) {
            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                post_requete()
            }
        })

        render_md_math()
    })
</script>

</html>
<?php session_start();

include("head.php");
include("header.php");
?>

<h2 class='titre-identification'>Bienvenue sur le site Genumsi</h2>
<div id='colonnes-index'>
    <div>
        <fieldset class="form-group fieldset-identification">
            <legend>Authentification</legend>
            <table cellspacing="0" cellpadding="5">
                <tr>
                    <td class='auth_td auth_td_texte'>
                        Login :
                    </td>
                    <td class='auth_td'>
                        <input type="text" name="login" id="login_g" />
                    </td>
                </tr>
                <tr>
                    <td class='auth_td auth_td_texte'>
                        Mot de passe :
                    </td>
                    <td class='auth_td'>
                        <input type="password" name="mdp" id="mdp_g"/>
                    </td>
                </tr>
            </table>
            <button class='btn btn-info' id='bouton-envoi'>Valider</button>
            <div class='reponse-ajax' id='id-reponse'>
            </div>
        </fieldset>


        <p>
            <button class='btn btn-info' type="button" onclick="location.href = 'nouvel-utilisateur.php';">Nouvel utilisateur ?</button>
        </p>
        <p>
            <button class='btn btn-info' type="button" onclick="location.href = 'mdp-perdu.php';">Mot de passe oublié ?</button>
        </p>

    </div>
    <img src="image/logo-accueil-genumsi.jpg">
</div>

<?php include("footer.php") ?>

</body>
<script>
    function post_datas() {

        let datas = {
            login: $('[name=login]').val(),
            mdp: $('[name=mdp]').val()
        };

        $.post('auth-check.php',
            datas,
            function(data) {
                if (data == "success") {
                    window.location.href = 'accueil.php'
                } else {
                    $('#id-reponse').html(data);
                }
            },
            'text'
        )
    }

    $('document').ready(function() {

        $("[name=mdp], [name=login]").on('keypress', function(e) {
            if (e.keyCode === 13) {
                post_datas();
            }
        });


        $('#bouton-envoi').on("click", post_datas);

    })
</script>

</html>
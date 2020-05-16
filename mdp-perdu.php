<?php session_start();

include("head.php");
include("header.php") ?>

<h2 class='titre-identification'>Récupération du mot de passe</h2>

<p>
    Merci de compléter les informations ci-dessous afin de vérifier votre identité
</p>

<fieldset class="form-group fieldset-identification">
    <legend>Informations de récupération</legend>
    <form id="form" method="post" action="chgt-mdp.php">

        <table cellspacing="0" cellpadding="5">
            <tr>
                <td class='auth_td auth_td_texte'>
                    Identifiant/login :
                </td>
                <td class='auth_td'>
                    <input type="text" name="identifiant" required />
                </td>
                <td class='reponse-ajax' id='id-reponse'>
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td class='auth_td auth_td_texte'>
                    Mail utilisé lors de l'inscription :
                </td>
                <td class='auth_td'>
                    <input type="email" name="email" required />
                </td>
                <td class='reponse-ajax' id='mail-reponse'>
                    &nbsp;
                </td>
            </tr>
        </table>
        <button class='btn btn-info' type="button">Modifier son mot de passe</button>
    </form>
</fieldset>

<?php include("footer.php") ?>

</body>

<script>
    function check_id() {

        $('#id-reponse').html("");

        let datas = {
            identifiant: $('[name=identifiant]').val(),
        };

        $.post('identifiant-check.php',
            datas,
            function(data) {
                if (data == "success") {
                    $('#id-reponse').html("cet identifiant n'existe pas dans la base... ");
                }
            },
            'text'
        )

        return $('#id-reponse').html() == ""
    }

    function check_datas() {
        let datas = {
            identifiant: $('[name=identifiant]').val(),
            mail: $('[name=email]').val()
        };

        $('#mail-reponse').html("");

        $.post('id-mail-check.php',
            datas,
            function(data) {
                if (data == "success") {
                    $('#form').submit();
                } else {
                    $('#mail-reponse').html(data);
                }
            },
            'text'
        )
    }

    function post_datas() {
        //event.preventDefault();
        if (check_id()) {
            check_datas()
        }
    }

    $('document').ready(function() {

        $("[name=identifiant]").change(check_id);

        $('button').click(post_datas);

    })
</script>

</html>
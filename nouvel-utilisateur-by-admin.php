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
    <h2 class='titre-identification'>Créer un compte sans mail académique (by admin)</h2>

    <p>
        Veuillez renseigner les informations suivantes du collègue à inscrire
    </p>

    <p>
        A l'issue de ce formulaire <b>un email de validation de l'inscription sera envoyé sur la boîte mail du collègue</b></p>

    <fieldset class="form-group fieldset-identification">
        <legend>Informations personnelles</legend>
        <form id="form" method="post" action="ajout-util-by-admin.php">

            <table cellspacing="0" cellpadding="5">
                <tr>
                    <td class='auth_td auth_td_texte'>
                        Nom :
                    </td>
                    <td class='auth_td'>
                        <input type="text" name="nom" required />
                    </td>
                </tr>
                <tr>
                    <td class='auth_td auth_td_texte'>
                        Prénom :
                    </td>
                    <td class='auth_td'>
                        <input type="text" name="prenom" required />
                    </td>
                </tr>
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
                        Mail :
                    </td>
                    <td class='auth_td'>
                        <input type="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required />
                    </td>
                </tr>
                <tr>
                    <td class='auth_td auth_td_texte'>
                        Mot de passe :
                    </td>
                    <td class='auth_td'>
                        <input type="password" name="mdp" required />
                    </td>
                </tr>
                <tr>
                    <td class='auth_td auth_td_texte'>
                        Confirmation du mot de passe :
                    </td>
                    <td class='auth_td'>
                        <input type="password" name="mdp-bis" required />
                    </td>
                    <td class='reponse-ajax' id='mdps-reponse'>
                        &nbsp;
                    </td>
                </tr>
            </table>
            <button class='btn btn-info' type="button">Inscrire ce collègue</button>
        </form>
    </fieldset>

<?php
endif;
include("footer.php")
?>

</body>

<script>
    let bon_id = false;

    function check_id() {

        $('#id-reponse').html("");

        let datas = {
            identifiant: $('[name=identifiant]').val(),
        };

        $.post('identifiant-check.php',
            datas,
            function(data) {
                if (data != "success") {
                    $('#id-reponse').html("cet identifiant est déjà utilisé... ");
                    bon_id = false;
                    return "error";
                } else if (data == "success") {
                    bon_id = true;
                    return "success";
                }
            },
            'text'
        )

        return bon_id
    }

    function check_mdps() {
        $('#mdps-reponse').html("");

        let mdp = $('[name=mdp]').val();
        let mdp_bis = $('[name=mdp-bis]').val();

        if (mdp != mdp_bis) {
            $('#mdps-reponse').html("Les deux mots de passes sont différents");
            return false;
        }

        return true;
    }

    function check_fields() {
        let plein = true
        $('input').each(function() {
            if ($(this).val() == '') {
                plein = false;
                //$(this).css('border', 'solid 1px red') ;
            }
        })
        if (!plein) {
            alert("Des champs ne sont pas remplis");
        }
        return plein
    }

    function post_datas() {
        //event.preventDefault();
        if (check_fields() && check_id() && check_mdps()) {
            $('#form').submit();
        }
    }

    $('document').ready(function() {

        $("[name=identifiant]").change(check_id);

        $("[name=mdp-bis]").change(check_mdps);

        $('button').click(post_datas);

    })
</script>

</html>
<?php session_start();
include("head.php");
include("header.php");
?>

<h2 class='titre-identification'>Changement du mot de passe</h2>

<fieldset class="form-group fieldset-identification">
    <legend>Nouveau mot de passe</legend>
    <form class="form" id="form" method="post" action="chgt-mdp-bdd.php">

        <table cellspacing="0" cellpadding="5">
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
        <button class='btn btn-info' type="button">Changer le mot de passe</button>
    </form>
</fieldset>

<?php include("footer.php") ?>

</body>

<script>
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

    function post_datas() {
        if (check_mdps()) {
            $('#form').submit();
        }
    }

    $('document').ready(function() {

        $("[name=mdp-bis]").change(check_mdps);

        $('button').click(post_datas);

    })
</script>

</html>
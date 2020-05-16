<div class="validation-qcm">
    <p>Avant de le partager, vous devez :</p>
    <ul>
        <li><b>Décider des points à attribuer</b></li>
        <p>Pour rappel la notation se fait par domaine : pour chacun d'entre eux, une note est calculée
            en tenant compte des points ajoutés ou soustraits (une absence de réponse n'est pas pénalisée)</p>
        <p>La note attribuée à un domaine ne peut pas être négative</p>
        <p>La note totale au QCM est égale à la somme des notes par domaines</p>
        <li><b>Décider d'activer ou non le mode triche</b></li>
        <p>Dans ce mode, le site détecte si l'élève change d'onglet (pour chercher une réponse sur le net ?) ou ouvre une nouvelle fenêtre (une console python ?)</p>
        <p>Dans ce cas, une fenêtre l'invitant à appeler le professeur est affichée</p>
        <p>Vous devez alors saisir un mot de passe afin de relancer le QCM. Il s'agit de votre identifiant : <b><?= $coordonnees['identifiant'] ?></b></p>
        <li><b>Ajouter un court descriptif qui vous permettra de le retrouver plus facilement parmi vos qcms</b></li>
    </ul>
    <br>
    <br>
    <ul>
        <li>
            <p>Pour une <b>bonne</b> réponse :</p>
            <div class='input-group-points'>
                <div class="number-input">
                    <span class="plus-moins-span" id="span-moins-b-bas">-</span>
                    <input class="custom-select-perso number-inp" min="0" value="3" step="0.5" type="number" id='b-bas'>
                    <span class="plus-moins-span" id="span-plus-b-bas">+</span>
                </div>
            </div>
            <p>Pour une <b>mauvaise</b> réponse (ces points seront soustraits) :</p>
            <div class='input-group-points'>
                <div class="number-input">
                    <span class="plus-moins-span" id="span-moins-m-bas">-</span>
                    <input class="custom-select-perso number-inp" min="0" value="1" step="0.5" type="number" id='m-bas'>
                    <span class="plus-moins-span" id="span-plus-m-bas">+</span>
                </div>
            </div>
        </li>
        <br>
        <li>
            <div class='input-group'>
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <input type="checkbox" name="triche" value="1" id="triche">
                    </div>
                </div>
                <span class='form-control'>Activer le mode <i>Triche</i></span>
            </div>
        </li>
        <br>
        <li>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Descriptif</span>
                </div>
                <textarea class="form-control" aria-label="Descriptif" maxlength="500" name="descriptif" placeholder="(Facultatif) Description du qcm"></textarea>
            </div>
        </li>
    </ul>
    <p>Si ce qcm vous plaît vous pouvez l'ajouter à vos QCM's et le partager en cliquant ci-dessous : </p>
    <form method='POST' action='choix-qcm.php' class='form-valide' id="choix-qcm" data>
        <input type="hidden" name='hash' value="f" id="hidden-hash">
        <button class='btn btn-info btn-valide' type="button" id="btn-qcm">Ajouter et Partager</button>
    </form>

    <input type="hidden" name='b' value="<?= base64_encode("3") ?>" id="hidden-b-bas">
    <input type="hidden" name='m' value="<?= base64_encode("1") ?>" id="hidden-m-bas">
</div>


<script>
    $('document').ready(function() {

        $('#btn-qcm').click(function() {

            let triche = 0;

            if ($("#triche").is(':checked')) {
                triche = 1
            }

            let datas = {
                cle: "<?= $cle64 ?>",
                p: "<?= base64_encode($_SESSION['num_util']) ?>",
                b: $("#hidden-b-bas").val(),
                m: $("#hidden-m-bas").val(),
                t: triche,
                d: $('[name="descriptif"]').val()
            }

            $.post("insertion-qcm.php",
                datas,
                function(data) {
                    if (data != "fail") {
                        $('#hidden-hash').val(data.substr(0, 32));
                        $("#choix-qcm").submit();
                    }
                }, 'text'
            );


        })

        //Boutons du bas
        $('#span-moins-b-bas').click(function() {
            this.parentNode.querySelector('input[type=number]').stepDown();
            let bon = window.btoa("" + $('#b-bas').val());
            let mauvais = window.btoa("" + $('#m-bas').val());
            $('#hidden-b-bas').val(bon);
            $('#hidden-print-b-bas').val(bon);
        })

        $('#span-plus-b-bas').click(function() {
            this.parentNode.querySelector('input[type=number]').stepUp();
            let bon = window.btoa("" + $('#b-bas').val());
            let mauvais = window.btoa("" + $('#m-bas').val());
            $('#hidden-b-bas').val(bon);
            $('#hidden-print-b-bas').val(bon);
        })

        $('#span-moins-m-bas').click(function() {
            this.parentNode.querySelector('input[type=number]').stepDown();
            let bon = window.btoa("" + $('#b-bas').val());
            let mauvais = window.btoa("" + $('#m-bas').val());
            $('#hidden-m-bas').val(mauvais);
            $('#hidden-print-m-bas').val(mauvais);
        })

        $('#span-plus-m-bas').click(function() {
            this.parentNode.querySelector('input[type=number]').stepUp();
            let bon = window.btoa("" + $('#b-bas').val());
            let mauvais = window.btoa("" + $('#m-bas').val());
            $('#hidden-m-bas').val(mauvais);
            $('#hidden-print-m-bas').val(mauvais);
        })

        $('#b-bas, #m-bas').change(function() {
            let bon = window.btoa("" + $('#b-bas').val());
            let mauvais = window.btoa("" + $('#m-bas').val());
            $('#hidden-m-bas').val(mauvais);
            $('#hidden-print-m-bas').val(mauvais);
        })

        render_md_math();

    })
</script>
<?php session_start();

include("head.php");


if (empty($_SESSION) or $_SESSION['connecte'] != true) :
    include("header.php");
    echo "Vous ne devriez pas être ici : <a href='index.php'>Retour</a>";
else :
    include('connexionbdd.php');
    include("header.php");
    include("nav.php");

    $txt_cle = "";
    if (!empty($_POST) and !is_null($_POST['cle'])) {
        $txt_cle = $_POST['cle'];
    } else {
        $txt_cle = "#question;#question";
    }

    ?>
    <a href=""><img src="image/down.png" alt="flèche du bas" id="fleche-bas-selection"></a>
    <a href=""><img src="image/up.png" alt="flèche du haut" id="fleche-haut-selection"></a>

    <h1 class='h1-qcm'>Sélection manuelle Maths niveaux 6, 5 , 4 et 3</h1>

    <h4>Vous pouvez sélectionner ci-dessous les questions qui vous intéressent.</h4>
    <p>N'hésitez pas à appliquer un filtre pour n'afficher que les questions correspondant aux rubriques/contenus souhaités.</p>

    <button class='btn btn-info' type='button' id='bouton-filtre'>Afficher/Masquer les filtres</button>

    <form id='filtre-selection' method='post'>

        <div class='input-group'>
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input class="check-filtre-niveau" id='check-niveau-3eme' type="checkbox" name="filtre_niveau[]" value=" domaines.niveau = '3' " checked>
                </div>
            </div>
            <input type="text" class='form-control' value="3ème" disabled>
        </div>
		
		<div class='input-group'>
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input class="check-filtre-niveau" id='check-niveau-4eme' type="checkbox" name="filtre_niveau[]" value=" domaines.niveau = '4' " checked>
                </div>
            </div>
            <input type="text" class='form-control' value="4ème" disabled>
        </div>		

        <div class='input-group'>
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input class="check-filtre-niveau" id='check-niveau-5eme' type="checkbox" name="filtre_niveau[]" value=" domaines.niveau = '5' " checked>
                </div>
            </div>
            <input type="text" class='form-control' value="5ème" disabled>
        </div>

        <div class='input-group'>
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input class="check-filtre-niveau" id='check-niveau-6eme' type="checkbox" name="filtre_niveau[]" value=" domaines.niveau = '6' " checked>
                </div>
            </div>
            <input type="text" class='form-control' value="6ème" disabled>
        </div>		

        <br>
        <div class='input-group'>
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input id="uncheck-domaine-3eme" type="checkbox" checked>
                </div>
            </div>
            <input type="text" class='form-control' value="Cocher / Décocher les domaines de 3ème" disabled>
        </div>
        <br>
        <div id="filtres-3eme">

            <?php
                $req_domaines = $bdd->prepare("SELECT num_domaine, domaine FROM domaines WHERE niveau = '3'");
                $req_domaines->execute();
                while ($domaine = $req_domaines->fetch()) :
                    ?>
                <div class='input-group'>
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input class="check-filtre-domaine check-domaine-3eme" type="checkbox" name="filtre_domaine[]" value=" domaines.num_domaine = <?= $domaine['num_domaine'] ?> " id="<?= $domaine['num_domaine'] ?>" checked>
                        </div>
                    </div>
                    <input type="text" class='form-control' value="<?= $domaine['domaine'] ?>" disabled>
                    <button class='btn btn-primary bouton-affiche-filtre-sous-domaine' type='button' id='<?= $domaine['num_domaine'] ?>'>Afficher/Masquer les sous-domaines</button>
                </div>
                <div class='filtres-sous-domaines' id='filtres-sous-domaine-<?= $domaine['num_domaine'] ?>'>
                    <?php
                            $req_sous_domaines = $bdd->prepare("SELECT num_sous_domaine, sous_domaine FROM sous_domaines WHERE num_domaine = ?");
                            $req_sous_domaines->execute(array($domaine['num_domaine']));
                            while ($sous_domaine = $req_sous_domaines->fetch()) :
                                ?>
                        <div class='input-group'>
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input class="check-filtre-sous-domaine check-sous-domaine-3eme" type="checkbox" name="filtre_sous_domaine[]" value=" sous_domaines.num_sous_domaine = <?= $sous_domaine['num_sous_domaine'] ?> " checked>
                                </div>
                            </div>
                            <input type="text" class='form-control' value="<?= $sous_domaine['sous_domaine'] ?>" disabled>
                        </div>

                    <?php
                            endwhile;
                            ?>
                </div>
                <br>

            <?php
                endwhile;
                ?>
        </div>
		
        <br>
        <div class='input-group'>
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input id="uncheck-domaine-4eme" type="checkbox" checked>
                </div>
            </div>
            <input type="text" class='form-control' value="Cocher / Décocher les domaines de 4ème" disabled>
        </div>
        <br>
        <div id="filtres-4eme">

            <?php
                $req_domaines = $bdd->prepare("SELECT num_domaine, domaine FROM domaines WHERE niveau = '4'");
                $req_domaines->execute();
                while ($domaine = $req_domaines->fetch()) :
                    ?>
                <div class='input-group'>
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input class="check-filtre-domaine check-domaine-4eme" type="checkbox" name="filtre_domaine[]" value=" domaines.num_domaine = <?= $domaine['num_domaine'] ?> " id="<?= $domaine['num_domaine'] ?>" checked>
                        </div>
                    </div>
                    <input type="text" class='form-control' value="<?= $domaine['domaine'] ?>" disabled>
                    <button class='btn btn-primary bouton-affiche-filtre-sous-domaine' type='button' id='<?= $domaine['num_domaine'] ?>'>Afficher/Masquer les sous-domaines</button>
                </div>
                <div class='filtres-sous-domaines' id='filtres-sous-domaine-<?= $domaine['num_domaine'] ?>'>
                    <?php
                            $req_sous_domaines = $bdd->prepare("SELECT num_sous_domaine, sous_domaine FROM sous_domaines WHERE num_domaine = ?");
                            $req_sous_domaines->execute(array($domaine['num_domaine']));
                            while ($sous_domaine = $req_sous_domaines->fetch()) :
                                ?>
                        <div class='input-group'>
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input class="check-filtre-sous-domaine check-sous-domaine-4eme" type="checkbox" name="filtre_sous_domaine[]" value=" sous_domaines.num_sous_domaine = <?= $sous_domaine['num_sous_domaine'] ?> " checked>
                                </div>
                            </div>
                            <input type="text" class='form-control' value="<?= $sous_domaine['sous_domaine'] ?>" disabled>
                        </div>

                    <?php
                            endwhile;
                            ?>
                </div>
                <br>

            <?php
                endwhile;
                ?>
        </div>		
        <br>
        <div class='input-group'>
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input id="uncheck-domaine-5eme" type="checkbox" checked>
                </div>
            </div>
            <input type="text" class='form-control' value="Cocher / Décocher les domaines de 5ème" disabled>
        </div>

        <br>
        <div id="filtres-5eme">

            <?php
                $req_domaines = $bdd->prepare("SELECT num_domaine, domaine FROM domaines WHERE niveau = '5'");
                $req_domaines->execute();
                while ($domaine = $req_domaines->fetch()) :
                    ?>
                <div class='input-group'>
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input class="check-filtre-domaine check-domaine-5eme" type="checkbox" name="filtres[]" value=" domaines.num_domaine = <?= $domaine['num_domaine'] ?> " id="<?= $domaine['num_domaine'] ?>" checked>
                        </div>
                    </div>
                    <input type="text" class='form-control' value="<?= $domaine['domaine'] ?>" disabled>
                    <button class='btn btn-primary bouton-affiche-filtre-sous-domaine' type='button' id='<?= $domaine['num_domaine'] ?>'>Afficher/Masquer les sous-domaines</button>
                </div>
                <div class='filtres-sous-domaines' id='filtres-sous-domaine-<?= $domaine['num_domaine'] ?>'>
                    <?php
                            $req_sous_domaines = $bdd->prepare("SELECT num_sous_domaine, sous_domaine FROM sous_domaines WHERE num_domaine = ?");
                            $req_sous_domaines->execute(array($domaine['num_domaine']));
                            while ($sous_domaine = $req_sous_domaines->fetch()) :
                                ?>
                        <div class='input-group'>
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input class="check-filtre-sous-domaine check-sous-domaine-5eme" type="checkbox" name="filtre_sous_domaine[]" value=" sous_domaines.num_sous_domaine = <?= $sous_domaine['num_sous_domaine'] ?> " checked>
                                </div>
                            </div>
                            <input type="text" class='form-control' value="<?= $sous_domaine['sous_domaine'] ?>" disabled>
                        </div>

                    <?php
                            endwhile;
                            ?>
                </div>
                <br>

            <?php
                endwhile;
                ?>
        </div>
		
        <br>
        <div class='input-group'>
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input id="uncheck-domaine-6eme" type="checkbox" checked>
                </div>
            </div>
            <input type="text" class='form-control' value="Cocher / Décocher les domaines de 6ème" disabled>
        </div>
        <br>
        <div id="filtres-6eme">

            <?php
                $req_domaines = $bdd->prepare("SELECT num_domaine, domaine FROM domaines WHERE niveau = '6'");
                $req_domaines->execute();
                while ($domaine = $req_domaines->fetch()) :
                    ?>
                <div class='input-group'>
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input class="check-filtre-domaine check-domaine-6eme" type="checkbox" name="filtre_domaine[]" value=" domaines.num_domaine = <?= $domaine['num_domaine'] ?> " id="<?= $domaine['num_domaine'] ?>" checked>
                        </div>
                    </div>
                    <input type="text" class='form-control' value="<?= $domaine['domaine'] ?>" disabled>
                    <button class='btn btn-primary bouton-affiche-filtre-sous-domaine' type='button' id='<?= $domaine['num_domaine'] ?>'>Afficher/Masquer les sous-domaines</button>
                </div>
                <div class='filtres-sous-domaines' id='filtres-sous-domaine-<?= $domaine['num_domaine'] ?>'>
                    <?php
                            $req_sous_domaines = $bdd->prepare("SELECT num_sous_domaine, sous_domaine FROM sous_domaines WHERE num_domaine = ?");
                            $req_sous_domaines->execute(array($domaine['num_domaine']));
                            while ($sous_domaine = $req_sous_domaines->fetch()) :
                                ?>
                        <div class='input-group'>
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input class="check-filtre-sous-domaine check-sous-domaine-6eme" type="checkbox" name="filtre_sous_domaine[]" value=" sous_domaines.num_sous_domaine = <?= $sous_domaine['num_sous_domaine'] ?> " checked>
                                </div>
                            </div>
                            <input type="text" class='form-control' value="<?= $sous_domaine['sous_domaine'] ?>" disabled>
                        </div>

                    <?php
                            endwhile;
                            ?>
                </div>
                <br>

            <?php
                endwhile;
                ?>
        </div>		
        <br>
        <select name="ordre" class="custom-select" required>
            <option value="questions.num_question ASC" selected>Trier par numéro de question (croissant)</option>
            <option value="questions.num_question DESC">Trier par numéro de question (décroissant)</option>
            <option value="questions.date_ajout ASC">Trier par date d'ajout (croissant)</option>
            <option value="questions.date_ajout DESC">Trier par date d'ajout (décroissant)</option>
            <option value="questions.num_domaine">Trier par domaine</option>
            <option value="questions.num_util">Trier par auteur</option>
            <option value="domaines.niveau, questions.num_domaine">Trier par niveau (3ème / 5ème)</option>
        </select>

        <button class='btn btn-info' type='button' id='poste-filtres'>Appliquer les filtres</button>
        <button class='btn btn-secondary' type='button' id='bouton-masque-filtre'>Masquer les filtres</button>

    </form>

    <form method="post" id="form-cle" action="qcm-valide-cle.php">
        <input type="hidden" name="cle" val="" id="input-cle"></input>
        <div class="form-group">
            <div id='questions-display'>
            </div>
        </div>

        <div id="bas">
            <b>Vous n'avez choisi aucune question...</b>
        </div>
        <br>
        <button class='btn btn-primary btn-valide' type='button' id='qcm-visu'>Visualiser le QCM</button>

    </form>


<?php
endif;
?>

<?php include("footer.php") ?>

</body>

<script>
    let filtre_affiche = true;
    let nb_questions = 0;
    let texte_cle = "";

    function testValeurs() {

        let somme = 0;
        let cle = "";

        $('.checkbox-question').each(function() {
            if (this.checked) {
                somme += 1;
                cle += this.id + ";"
            }
        })

        cle = cle.substr(0, cle.length - 1);

        if (somme == 0) {
            alert("Vous devez sélectionner au moins une question !");
            event.preventDefault();
        } else {
            $('#input-cle').val(cle);
            $('#form-cle').submit();
        }

    }

    function changeOrdre() {

        $('#bas').html("<b>Vous n'avez choisi aucune question...</b>");
        texte_cle = "";
        nb_questions = 0;

        let datas = {
            ordre: $('[name=ordre]').val(),
            'filtre_niveau[]': [],
            'filtre_domaine[]': [],
            'filtre_sous_domaine[]': [],
        };

        $(".check-filtre-niveau:checked").each(function() {
            datas['filtre_niveau[]'].push($(this).val());
        });

        $(".check-filtre-domaine:checked").each(function() {
            datas['filtre_domaine[]'].push($(this).val());
        });

        $(".check-filtre-sous-domaine:checked").each(function() {
            datas['filtre_sous_domaine[]'].push($(this).val());
        });

        $.post('classement-quest-selection.php',
            datas,
            function(data) {
                $('#questions-display').html(data);
            },
            'text'
        )

        render_md_math();

    }

    $('document').ready(function() {

        $('#qcm-visu').click(testValeurs);

        $('#fleche-bas-selection').click(function() {
            $('html, body').animate({
                scrollTop: $(document).height()
            }, 'slow');
            return false;
        });

        $('#fleche-haut-selection').click(function() {
            $('html, body').animate({
                scrollTop: 0
            }, 'slow');
            return false;
        });

        $('#bouton-filtre').click(function() {
            if (!filtre_affiche) {
                $('#filtre-selection').css('display', 'block');
                filtre_affiche = true;
            } else {
                $('#filtre-selection').css('display', 'none');
                filtre_affiche = false;
            }
        })

        $('.bouton-affiche-filtre-sous-domaine').click(function() {
            if ($('#filtres-sous-domaine-' + this.id).css('display') == 'none') {
                $('#filtres-sous-domaine-' + this.id).css('display', 'block');
            } else {
                $('#filtres-sous-domaine-' + this.id).css('display', 'none');
            }
        })

        $('#bouton-masque-filtre').click(function() {
            $('#filtre-selection').css('display', 'none');
            filtre_affiche = false;
        })

        $('#poste-filtres').click(changeOrdre);

        $('#check-niveau-3eme').change(function() {
            if (this.checked) {
                $(".check-domaine-3eme").each(function() {
                    this.checked = true;
                    $(this).removeAttr('disabled');
                });
                $(".check-sous-domaine-3eme").each(function() {
                    this.checked = true;
                    $(this).removeAttr('disabled');
                });
                $('#uncheck-domaine-3eme').removeAttr('disabled');
            } else {
                $(".check-domaine-3eme").each(function() {
                    this.checked = false;
                    $(this).attr('disabled', true);
                });
                $(".check-sous-domaine-3eme").each(function() {
                    this.checked = false;
                    $(this).attr('disabled', true);
                });
                $('#uncheck-domaine-3eme').attr('disabled', true);
            }
        })
		
        $('#check-niveau-4eme').change(function() {
            if (this.checked) {
                $(".check-domaine-4eme").each(function() {
                    this.checked = true;
                    $(this).removeAttr('disabled');
                });
                $(".check-sous-domaine-4eme").each(function() {
                    this.checked = true;
                    $(this).removeAttr('disabled');
                });
                $('#uncheck-domaine-4eme').removeAttr('disabled');
            } else {
                $(".check-domaine-4eme").each(function() {
                    this.checked = false;
                    $(this).attr('disabled', true);
                });
                $(".check-sous-domaine-4eme").each(function() {
                    this.checked = false;
                    $(this).attr('disabled', true);
                });
                $('#uncheck-domaine-4eme').attr('disabled', true);
            }
        })		

        $('#check-niveau-5eme').change(function() {
            if (this.checked) {
                $(".check-domaine-5eme").each(function() {
                    this.checked = true;
                    $(this).removeAttr('disabled');
                });
                $(".check-sous-domaine-5eme").each(function() {
                    this.checked = true;
                    $(this).removeAttr('disabled');
                });
                $('#uncheck-domaine-5eme').removeAttr('disabled');

            } else {
                $(".check-domaine-5eme").each(function() {
                    this.checked = false;
                    $(this).attr('disabled', true);
                });
                $(".check-sous-domaine-5eme").each(function() {
                    this.checked = false;
                    $(this).attr('disabled', true);
                });
                $('#uncheck-domaine-5eme').attr('disabled', true);
            }
        })
		
        $('#poste-filtres').click(changeOrdre);

        $('#check-niveau-6eme').change(function() {
            if (this.checked) {
                $(".check-domaine-6eme").each(function() {
                    this.checked = true;
                    $(this).removeAttr('disabled');
                });
                $(".check-sous-domaine-6eme").each(function() {
                    this.checked = true;
                    $(this).removeAttr('disabled');
                });
                $('#uncheck-domaine-6eme').removeAttr('disabled');
            } else {
                $(".check-domaine-6eme").each(function() {
                    this.checked = false;
                    $(this).attr('disabled', true);
                });
                $(".check-sous-domaine-6eme").each(function() {
                    this.checked = false;
                    $(this).attr('disabled', true);
                });
                $('#uncheck-domaine-6eme').attr('disabled', true);
            }
        })		

        $('#uncheck-domaine-3eme').change(function() {
            if (this.checked) {
                $(".check-domaine-3eme").each(function() {
                    this.checked = true;
                });
                $(".check-sous-domaine-3eme").each(function() {
                    this.checked = true;
                });
            } else {
                $(".check-domaine-3eme").each(function() {
                    this.checked = false;
                });
                $(".check-sous-domaine-3eme").each(function() {
                    this.checked = false;
                });
            }
        })
		
        $('#uncheck-domaine-4eme').change(function() {
            if (this.checked) {
                $(".check-domaine-4eme").each(function() {
                    this.checked = true;
                });
                $(".check-sous-domaine-4eme").each(function() {
                    this.checked = true;
                });
            } else {
                $(".check-domaine-4eme").each(function() {
                    this.checked = false;
                });
                $(".check-sous-domaine-4eme").each(function() {
                    this.checked = false;
                });
            }
        })		

        $('#uncheck-domaine-5eme').change(function() {
            if (this.checked) {
                $(".check-domaine-5eme").each(function() {
                    this.checked = true;
                });
                $(".check-sous-domaine-5eme").each(function() {
                    this.checked = true;
                });
            } else {
                $(".check-domaine-5eme").each(function() {
                    this.checked = false;
                });
                $(".check-sous-domaine-5eme").each(function() {
                    this.checked = false;
                });
            }
        })
		
       $('#uncheck-domaine-6eme').change(function() {
            if (this.checked) {
                $(".check-domaine-6eme").each(function() {
                    this.checked = true;
                });
                $(".check-sous-domaine-6eme").each(function() {
                    this.checked = true;
                });
            } else {
                $(".check-domaine-6eme").each(function() {
                    this.checked = false;
                });
                $(".check-sous-domaine-6eme").each(function() {
                    this.checked = false;
                });
            }
        })		

        $('.check-filtre-domaine').change(function() {
            console.log(("#filtres-sous-domaine-" + this.id));
            console.log($("#filtres-sous-domaine-" + this.id));
            console.log($("#filtres-sous-domaine-" + this.id + " :checkbox"));
            if (this.checked) {
                $("#filtres-sous-domaine-" + this.id + "  :checkbox").each(function() {
                    this.checked = true;
                    $(this).removeAttr('disabled');
                });
            } else {
                $("#filtres-sous-domaine-" + this.id + "  :checkbox").each(function() {
                    $(this).attr('disabled', true);
                });
            }
        })

        //changeOrdre();
    })
</script>

</html>
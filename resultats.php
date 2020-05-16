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

    <h1 class='h1-qcm'>Résultats de vos élèves</h1>

    <p>
        Sur cette page vous pouvez :
    </p>
    <ul>
        <li>
            Exporter les résultats de vos élèves au format CSV avec :
            <ul>
                <li>entêtes sur la première ligne</li>
                <li>des virgules en séparateur</li>
                <li>encodé en utf-8</li>
            </ul>
            <ul class="navbar-nav mr-auto">
                <li><a class="link" href="export-resultats.php">Export des résultats en CSV</a></li>
            </ul>
        </li>
        <li>
            <p>Effacer ces résultats de la table. <b>Attention, cette opération est irréversible, assurez-vous d'avoir bien récupéré vos résultats auparavant</b></p>
            <button class='btn btn-info btn-valide btn-suppr' type='button' id='bouton-effacer-resultats'>Effacer les résultats</button>

            <div class='confirmation-suppression' id='confirmation-suppression' style='display:none;'>
                <h3>
                    <p>Etes-vous sûr de vouloir supprimer les résultats ?</p>
                    <p>Cette opération est irréversible</p>
                </h3>
                <div id='boutons-suppr'>
                    <button class='btn btn-success btn-valide refus-suppr' type='button' id='refus-suppr-resultats'>Non</button>
                    <button class='btn btn-danger btn-valide confirmation-suppr' type='button' id='conf-suppr-resultats'>Oui</button>
                </div>
            </div>
        </li>
    </ul>
    <br>
    <h2>Voici les résultats de vos élèves contenus dans la base.</h2>
    <p>Les <i>Réponses au QCM</i> reprennent les réponses de l'élève 
    (A, B, C et D) aux questions posées en respectant la numérotation de la base de données</p>

    <table class="table table-hover table-bordered table-striped" id='tableau-resultats'>
        <thead class="thead-light">
            <tr>
                <?php if ($_SESSION['qualite'] == 'admin') {
                ?>
                    <th><a href="javascript:sortTable(0, 'T')">ID Professeur</a></td>
                    <th><a href="javascript:sortTable(1, 'D', 'dMyhms')">Date</a></td>
                    <th><a href="javascript:sortTable(2, 'T')">ID Elève</a></td>
                    <th><a href="javascript:sortTable(3, 'T')">Source</a></td>
                    <th><a href="javascript:sortTable(4, 'N')">Note sur 20</a></td>
                    <th><a href="javascript:sortTable(5, 'T')">Clé du QCM</a></td>
                    <th><a href="javascript:sortTable(6, 'T')">Réponses au QCM</a></td>
                    <?php
                } else {
                    ?>

                    <th><a href="javascript:sortTable(0, 'D','dMyhms')">Date</a></td>
                    <th><a href="javascript:sortTable(1, 'T')">ID Elève</a></td>
                    <th><a href="javascript:sortTable(2, 'N')">Note sur 20</a></td>
                    <th><a href="javascript:sortTable(3, 'T')">Clé du QCM</a></td>
                    <th><a href="javascript:sortTable(4, 'T')">Réponses au QCM</a></td>

                    <?php
                }
                    ?>

            </tr>
        </thead>
        <tbody>
            <?php

            $resultats = array();

            if ($_SESSION['qualite'] == 'prof') {
                $resultats = $bdd->prepare('SELECT * FROM resultats WHERE num_prof= ?');
                $resultats->execute(array($_SESSION['num_util']));
            } else if ($_SESSION['qualite'] == 'admin') {
                $resultats = $bdd->prepare('SELECT *, utilisateurs.identifiant FROM resultats INNER JOIN utilisateurs ON resultats.num_prof = utilisateurs.num_util WHERE 1');
                $resultats->execute();
            }

            $resultats = $resultats->fetchAll();

            foreach ($resultats as $r) :
                $date = date_create($r['date']);
            ?>
                <tr>
                    <?php if ($_SESSION['qualite'] == 'admin') : ?>
                        <td><?= $r['identifiant'] ?></td>
                    <?php endif; ?>
                    <td><?= date_format($date, 'd/m/Y H:i:s') ?></td>
                    <td><?= $r['nom_eleve'] ?></td>
                    <?php if ($_SESSION['qualite'] == 'admin') : ?>
                        <td><?= $r['origine'] ?></td>
                    <?php endif; ?><td><?php if ($r['note_qcm'] == -1) {
                        echo "QCM en cours";
                    } else {
                        echo $r['note_qcm'];
                    }?>
                    </td>
                    <td><?= $r['cle_qcm'] ?></td>
                    <td>
                    <?php if ($r['note_qcm'] == -1) {
                        echo "QCM en cours";
                    } else {
                        echo $r['reponses'];
                    }
                    ?>
                    </td>
                </tr>

            <?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>

<?php include("footer.php") ?>

</body>

<script>
    $('#bouton-effacer-resultats').click(function() {
        $('#confirmation-suppression').css("display", "block");
    })

    $('#refus-suppr-resultats').click(function() {
        $('#confirmation-suppression').css("display", "none");
    })

    $('#conf-suppr-resultats').click(function() {
        window.location.href = 'efface-resultats.php';
    })

    var TableIDvalue = "tableau-resultats";

    //////////////////////////////////////
    var TableLastSortedColumn = -1;

    function sortTable() {
        var sortColumn = parseInt(arguments[0]);
        var type = arguments.length > 1 ? arguments[1] : 'T';
        var dateformat = arguments.length > 2 ? arguments[2] : '';
        var table = document.getElementById(TableIDvalue);
        var tbody = table.getElementsByTagName("tbody")[0];
        var rows = tbody.getElementsByTagName("tr");
        var arrayOfRows = new Array();

        type = type.toUpperCase();

        for (var i = 0, len = rows.length; i < len; i++) {
            arrayOfRows[i] = new Object;
            arrayOfRows[i].oldIndex = i;
            var celltext = rows[i].getElementsByTagName("td")[sortColumn].innerHTML.replace(/<[^>]*>/g, "");
            if (type == 'D') {
                arrayOfRows[i].value = GetDateSortingKey(dateformat, celltext);
            } else {
                var re = type == "N" ? /[^\.\-\+\d]/g : /[^a-zA-Z0-9]/g;
                arrayOfRows[i].value = celltext.replace(re, "").substr(0, 25).toLowerCase();
            }
        }
        if (sortColumn == TableLastSortedColumn) {
            arrayOfRows.reverse();
        } else {
            TableLastSortedColumn = sortColumn;
            switch (type) {
                case "N":
                    arrayOfRows.sort(CompareRowOfNumbers);
                    break;
                case "D":
                    arrayOfRows.sort(CompareRowOfNumbers);
                    break;
                default:
                    arrayOfRows.sort(CompareRowOfText);
            }
        }
        var newTableBody = document.createElement("tbody");
        for (var i = 0, len = arrayOfRows.length; i < len; i++) {
            newTableBody.appendChild(rows[arrayOfRows[i].oldIndex].cloneNode(true));
        }
        table.replaceChild(newTableBody, tbody);
    }

    function CompareRowOfText(a, b) {
        var aval = a.value;
        var bval = b.value;
        return (aval == bval ? 0 : (aval > bval ? 1 : -1));
    }

    function CompareRowOfNumbers(a, b) {
        var aval = /\d/.test(a.value) ? parseFloat(a.value) : 0;
        var bval = /\d/.test(b.value) ? parseFloat(b.value) : 0;
        return (aval - bval);
    }

    function GetDateSortingKey(format, text) {
        if (format.length < 1) {
            return "";
        }

        text = text.toLowerCase();
        text = text.replace(/^[^a-z0-9]*/, "");
        text = text.replace(/[^a-z0-9]*$/, "");


        if (text.length < 1) {
            return "";
        }
        text = text.replace(/[^a-z0-9]+/g, ",");
        var date = text.split(",");

        if (date.length < 3) {
            return "";
        }
        var d = 0,
            M = 0,
            y = 0,
            h = 0,
            m = 0,
            s = 0;
        for (var i = 0; i < format.length; i++) {
            var ts = format.substr(i, 1);
            if (ts == "d") {
                d = date[i];
            } else if (ts == "M") {
                M = date[i];
            } else if (ts == "y") {
                y = date[i];
            } else if (ts == "h") {
                h = date[i];
            } else if (ts == "m") {
                m = date[i];
            } else if (ts == "s") {
                s = date[i];
            }
        }

        d = d.replace(/^0/, "");
        if (d < 10) {
            d = "0" + d;
        }

        M = M.replace(/^0/, "");
        if (M < 10) {
            M = "0" + M;
        }

        h = h.replace(/^0/, "");
        if (h < 10) {
            h = "0" + h;
        }

        m = m.replace(/^0/, "");
        if (m < 10) {
            m = "0" + m;
        }

        s = s.replace(/^0/, "");
        if (s < 10) {
            s = "0" + s;
        }

        y = parseInt(y);
        if (y < 100) {
            y = parseInt(y) + 2000;
        }

        return "" + String(y) + "" + String(M) + "" + String(d) + "" + "" + String(h) + "" + String(m) + "" + String(s);
    }
</script>

</html>
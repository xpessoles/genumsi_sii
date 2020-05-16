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

    <h1 class='h1-qcm'>Vos QCM's</h1>

    <p>
        Sur cette page vous pouvez :
    </p>
    <ul>
        <li>Visualiser les QCM's que vous avez créé</li>
        <li>Les activer ou désactiver (afin de permettre aux élèves de répondre ou non)</li>
        <li>Les supprimer de la base de données si vous ne pensez plus les réutiliser</li>
    </ul>

    <br>
    <h2>Voici vos QCM's contenus dans la base :</h2>

    <table class="table table-hover table-bordered table-striped" id='tableau-qcms'>
        <thead class="thead-light">
            <tr>
                <?php if ($_SESSION['qualite'] == 'admin') {
                ?>
                    <th><a href="javascript:sortTable(0, 'D','dMyhms')">Date de création</a></td>
                    <th><a href="javascript:sortTable(1, 'T')">ID Professeur</a></td>
                    <th><a href="javascript:sortTable(2, 'T')">Points</a></td>
                    <th><a href="javascript:sortTable(3, 'T')">Triche</a></td>
                    <th><a href="javascript:sortTable(4, 'T')">Actif</a></td>
                    <th><a href="javascript:sortTable(5, 'T')">Clé du QCM</a></td>
                    <th><a href="javascript:sortTable(6, 'T')">Descriptif</a></td>
                    <th>Actions</th>
                    <th>Partager</td>
                    <?php
                } else {
                    ?>
                    <th><a href="javascript:sortTable(0, 'D','dMyhms')">Date de création</a></td>
                    <th><a href="javascript:sortTable(1, 'T')">Points</a></td>
                    <th><a href="javascript:sortTable(2, 'T')">Triche</a></td>
                    <th><a href="javascript:sortTable(3, 'T')">Actif</a></td>
                    <th><a href="javascript:sortTable(4, 'T')">Clé du QCM</a></td>
                    <th><a href="javascript:sortTable(5, 'T')">Descriptif</a></td>
                    <th>Actions</th>
                    <th>Partager</td>
                    <?php
                }
                    ?>

            </tr>
        </thead>
        <tbody>
            <?php

            $resultats = array();

            if ($_SESSION['qualite'] == 'prof') {
                $resultats = $bdd->prepare('SELECT * FROM qcms WHERE num_prof= ?');
                $resultats->execute(array($_SESSION['num_util']));
            } else if ($_SESSION['qualite'] == 'admin') {
                $resultats = $bdd->prepare('SELECT *, utilisateurs.identifiant FROM qcms INNER JOIN utilisateurs ON qcms.num_prof = utilisateurs.num_util WHERE 1');
                $resultats->execute();
            }

            $resultats = $resultats->fetchAll();

            foreach ($resultats as $r) :
                $date = date_create($r['date_qcm']);
            ?>
                <tr>
                    <td><?= date_format($date, 'd/m/Y H:i:s') ?></td>
                    <?php if ($_SESSION['qualite'] == 'admin') : ?>
                        <td><?= $r['identifiant'] ?></td>
                    <?php endif; ?>
                    <td>+ <?= $r['points_plus'] ?> / - <?= $r['points_moins'] ?></td>
                    <?php
                    if ($r['triche'] == 1) {
                        echo "<td>Oui</td>";
                    } else {
                        echo "<td>Non</td>";
                    }
                    ?>
                    <?php
                    if ($r['actif'] == 0) {
                        echo "<td>Non</td>";
                    } else {
                        echo "<td>Oui</td>";
                    }
                    ?>
                    <td><?= $r['cle_qcm'] ?></td>
                    <?php
                    if (is_null($r['descriptif_qcm'])) {
                        echo "<td><i>Pas de descriptif</i></td>";
                    } else {
                        echo "<td>" . $r['descriptif_qcm'] . "</td>";
                    }
                    ?>
                    <td>
                        <button class="btn btn-primary btn_act" id="<?= $r['hash_qcm'] ?>" style="display:block;margin:2px">Activer</button>
                        <button class="btn btn-secondary btn_des" id="<?= $r['hash_qcm'] ?>" style="display:block;margin:2px">Désactiver</button>
                        <button class="btn btn-danger btn_sup" id="<?= $r['hash_qcm'] ?>" style="display:block;margin:2px">Supprimer</button>
                    </td>
                    <td>
                        <form method='POST' action='choix-qcm.php' class='form-valide'>
                            <input type="hidden" name='hash' value="<?= $r['hash_qcm'] ?>" id="hidden-hash">
                            <button class='btn btn-info btn-valide' id="btn-qcm">Partager</button>
                        </form>
                    </td>
                </tr>

            <?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>

<?php include("footer.php") ?>

</body>

<script>
    $('document').ready(function() {
        initialisation();
    })

    function initialisation() {
        $('.btn_act').click(function() {
            let datas = {
                hash: $(this).attr('id')
            }

            $.post('activation-qcm.php',
                datas,
                function(data) {
                    window.location.reload();
                },
                'text'
            )
        })

        $('.btn_des').click(function() {
            let datas = {
                hash: $(this).attr('id')
            }

            $.post('desactivation-qcm.php',
                datas,
                function(data) {
                    window.location.reload();
                },
                'text'
            )
        })

        $('.btn_sup').click(function() {
            let datas = {
                hash: $(this).attr('id')
            }

            $.post('suppression-qcm.php',
                datas,
                function(data) {
                    window.location.reload();
                },
                'text'
            )
        })
    }

    //////////////////////////////////////
    var TableIDvalue = "tableau-qcms";

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

        initialisation();
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
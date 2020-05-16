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

  <h1 class='h1-qcm'>Liste des utilisateurs</h1>

  <h2>Voici les utilisateurs contenus dans la base :</h2>

  <table class="table table-hover table-bordered table-striped" id='tableau-util'>
    <thead class="thead-light">
      <tr>
        <th><a href="javascript:sortTable(0, 'T')">Nom</a></th>
        <th><a href="javascript:sortTable(1, 'T')">Prénom</a></th>
        <th><a href="javascript:sortTable(2, 'T')">Identifiant</a></th>
        <th><a href="javascript:sortTable(3, 'T')">Mail</a></th>
        <th><a href="javascript:sortTable(4, 'T')">Compte vérifié</a></th>
        <th><a href="javascript:sortTable(5, 'T')">Qualité</a></th>
        <th><a href="javascript:sortTable(6, 'D','dMyhms')">Dernière connexion</a></th>
        <th><a href="javascript:sortTable(7, 'N')">Nombre de questions rédigées</a></th>
      </tr>
    </thead>
    <tbody>
      <?php

        $utilisateurs = $bdd->prepare('SELECT num_util, nom, prenom, identifiant, mail, verification, qualite, derniere_connexion FROM utilisateurs');
        $utilisateurs->execute();


        while ($r = $utilisateurs->fetch()) :
          $date = date_create($r['derniere_connexion']);
          ?>
        <tr>
          <td><?= $r['nom'] ?></td>
          <td><?= $r['prenom'] ?></td>
          <td><?= $r['identifiant'] ?></td>
          <td><?= $r['mail'] ?></td>
          <?php
              if ($r['verification'] == "ok") {
                echo '<td>Oui</td>';
              } else {
                echo '<td>Non</td>';
              }
              ?>
          <td><?= $r['qualite'] ?></td>
          <td><?= date_format($date, 'd/m/Y H:i:s') ?></td>

          <?php
              $nb_questions = $bdd->prepare('SELECT count(*) as nb FROM questions WHERE num_util = ?');
              $nb_questions->execute(array($r['num_util']));
              $nb_questions = $nb_questions->fetch();
              ?>
          <td><?= $nb_questions['nb'] ?></td>
        </tr>

      <?php endwhile; ?>
    </tbody>
  </table>

<?php endif; ?>

<?php include("footer.php") ?>

</body>

<script>
  var TableIDvalue = "tableau-util";

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
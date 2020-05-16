<?php session_start();

include("config.php");

include("head.php");

?>

<header class="header">
  <div id="header-logo-titre">
    <a href='check-accueil.php'><img src="image/logo-genumsi.png" alt="logo GeNumSI" id='logo-header' /></a>
    <h1 id='h1-header'>QCM avec <?= $GENUMNAME ?> </h1>
  </div>
</header>

<div id="conteneur-general">
  <h2>Merci de saisir votre <b>numéro</b> d'identification</h2>
  <br />
  <form action="qcm2.php" onsubmit="return valider()" method="POST" name="formSaisie">
    <h3>Identifiant : <input type="number" name="i" /></h3>
    <input type="hidden" value="<?= $_GET['h'] ?>" name="h" />
    <br />
    <p><input type="submit" value="Accéder au QCM" class="btn btn-info"></p>
  </form>
  <br />
  <hr>
  <h3>A lire si vous utilisez <?= $GENUMNAME ?> pour la première fois</h3>
  <br>
  <p>Votre numéro d'identification est le numéro qui vous a été attribué par votre enseignant.</p>
  <p>Une fois que votre QCM est chargé vous devez répondre aux questions puis le valider avec le bouton [envoyer les réponses] qui se trouve au bas de la page.</p>
  <p>Une fois le QCM chargé, évitez de sortir de votre navigateur en cours de réponse.</p>
  <p>Une fois le QCM terminé une correction vous sera proposée.</p>
  <p>Si vous avez la moindre difficulté technique, contacter votre enseignant.</p>
</div>
</body>


<script type="text/javascript">
  //<![CDATA[

  function valider() {
    // si la valeur du champ prenom est non vide
    let num_identification = parseInt($('[name ="i"]').val());

    if (!isNaN(num_identification)) {
      // les données sont ok, on peut envoyer le formulaire    
      return true;
    } else {
      // sinon on affiche un message
      alert("Vous devez saisir un numéro d'identification valide");
      // et on indique de ne pas envoyer le formulaire
      return false;
    }
  }

  //]]>
</script>

</html>
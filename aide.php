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
    <h1 class='h1-qcm'>Prise en main rapide de GeNumSI</h1>

    <p>Dans GeNumSi un QCM est défini par :</p>
    <ul>
    <li>une <strong>clé</strong> qui correspond tout simplement à la liste des numéros de questions qui le compose.</li>
    <li> l'<strong>identifiant</strong> de l'enseignant qui l'a créé.</li>
    </ul>
    <h2>1) Générer son premier QCM</h2>
    <p>Pour générer votre QCM plusieurs options s'offrent à vous :</p>
    <img src="image_aide/creerQCM1.png" alt="Menu Créer un QCM">
    <ul>
        <li><strong>Niveau 1ère</strong> et <strong>Niveau Terminale</strong> vous permettent la génération automatique de QCM.
            Pour chacun de ces niveaux vous pouvez sélectionner le nombre de questions souhaitées par rubriques ou par sous-rubriques.
            Le générateur sélectionnera aléatoirement le nombre de questions souhaitées dans la base de questions.</li>
        <li> <strong>Par sélection manuelle</strong> permet de sélectionner une à une les questions que vous souhaitez.
              Vous pouvez filtrer les questions suivants différents critères.</li>
        <li><strong>A partir d'une liste</strong> permet de créer un QCM à partir d'une liste de numéros de questions (clé du QCM).</li>
        <li><strong>Clé en main</strong> Vous propose des clés de QCM prédéfinis comme par exemple les sujets zéros.</li>
        <li><strong>Rechercher une qestion</strong> permet de rechercher des questions par mots clés.</li>                
    </ul>
    <p>Pour chacune de ces options vous pouvez générer votre QCM dans différents formats:</p>
    <ul>
        <li>Une page Web permettant une évaluation/correction automatique du QCM.</li>
        <li>Un format HTML facilement convertible en PDF ou éditable avec OpenOffice.</li>
        <li>Un format HTML du corrigé du QCM.</li>
    </ul>
    <p>Une fois les questions choisies, vous pouvez <strong>Visualiser le QCM</strong> et choisir la notation souhaitée.</p>    
    <img src="image_aide/visuQCM.png" alt="Visualisation du QCM">

    <p>Vous pouvez également décider d'activer ou non le <strong>mode triche</strong></p>
    <img src="image_aide/modeTriche.png" alt="mode triche">

    <p>Dans ce mode, le site détecte si l'élève change d'onglet (pour chercher une réponse sur le net ?) ou ouvre une nouvelle fenêtre (une console python ?).
       Dans ce cas, une fenêtre l'invitant à appeler le professeur est affichée. 
       Vous devez alors saisir un mot de passe afin de relancer le QCM. Il s'agit de votre identifiant GeNumSI.</p>

    <p>Une fois tout ces réglages terminés, il ne vous reste plus qu'à partager votre QCM et l'ajouter dans votre liste de QCM.</p>
    <img src="image_aide/ajouterPartager.png" alt="ajout et partage"> 

    <h2>2) Partage/Diffusion des QCM</h2>

    <p>Vous remarquerez l'URL de votre QCM, qui a un format semblable à l'exemple ci-dessous :</p>
    <p><code>https://genumsi.inria.fr/qcm.php?h=fce5a2d12caf8424e82bfa198cdd8f1a</code></p>
    <p>Le hash unique du QCM est inclus dans l'URL.</p>
    <p>Du coup si vous souhaitez évaluer vos élèves avec ce QCM il vous suffit de récupérer cette URL et de l'ajouter dans une page Web
        avec la balise <strong>&lt;a&gt;</strong> :</p>
    <pre><code>&lt;a target="_blank"   title="QCM" href="https://genumsi.inria.fr/qcm.php?h=fce5a2d12caf8424e82bfa198cdd8f1a"&gt;QCM sujet zéro&lt;/a&gt;</code></pre>
    <p>Voilà ce que cela donne intégré dans cette page d'aide : <a target="_blank" title="QCM" href=https://genumsi.inria.fr/qcm.php?h=fce5a2d12caf8424e82bfa198cdd8f1a">QCM sujet zéro</a></p>
    <p>Vous pouvez également générer le QR-Code pour accéder directement au QCM depuis une tablette ou un smartphone.</p>
    
    <p>Lors du paramétrage d'un QCM, vous pouvez décider d'activer ou non le mode "Triche". S'il est activé, ce mode détecte la perte de <i>focus</i> par la page du QCM.</p>
    <p>Cet évènement peut se produire si l'on change d'onglet dans le navigateur ou si l'on ouvre une autre application (on peut imaginer un élève ouvrant une page Wikipedia pour chercher une réponse).</p>
    <p>Dans ce cas, une fenêtre bloque la poursuite du QCM et invite l'élève à appeler son professeur. Le mot de passe demandé n'est autre que votre <b>identifiant</b> (tel qu'indiqué en haut de cette page).</p>
    
    <h4>Quelques remarques :</h4>
    <ul>
        <li>Pour chaque génération les questions sont mélangées aléatoirement dans chacune des rubriques
            et pour chacune des questions les réponses sont également mélangées aléatoirement.
            Ainsi si 2 élèves lancent le QCM, ils auront les mêmes questions, mais dans un ordre différent et avec des réponses dans un ordre différent.</li>
        <li>De même, si vous générez la version à imprimer d'un QCM il vous suffit de réactualiser la page pour obtenir une version avec les questions et les réponses dans un ordre différent.</li>
    </ul>
    <h2>3) Gestion de votre liste de QCM.</h2>
    <p>Dans le menu "Vos QCM's" vous retrouvez tous les QCM que vous avez générés. Pour chacun d'entre eux vous pouvez décider de les activer, désactiver ou de les supprimer.</p>
    <p>Les QCM sont activés par défaut, mais vous pouvez décider de les désactiver temporairement pour les activer à nouveau juste avant l'évaluation des élèves.</p>
    <p>Par exemple, si vous avez plusieurs groupes, vous pouvez désactiver le QCM juste après l'évaluation du groupe 1 pour ne le réactiver qu'au moment d'évaluer le groupe 2.
    Cela évite que le premier groupe diffuse le lien vrs le QCM aux élèves du groupe suivant ;)</p>
    <p>En cliquant sur le bouton partage, vous accéder à nouveau à la page donnant accès à l'URL de votre QCM.</p>
    <p>Enfin si vous souhaitez désactiver ou activer le mode triche pour un QCM, vous devez en générer un nouveau à partir de la clé du QCM en sélecionnant le mode souhaité.</p>

    <h2>4) Evaluation des élèves en ligne.</h2>
    <p>Un élève qui lance le QCM doit s'identifier :</p>    
    <img src="image_aide/lancementQCM.png" alt="Lancement QCM">
    <p>Ce numéro d'identification doi être attribué à l'élève dans le respect du RGPD.</p>
    <p>En cours d'évaluation vous pouvez vérifier le numéro d'identification saisie par l'élève dans la partie supérieure du navigateur.</p>
    <img src="image_aide/bandeauQCM.png" alt="bandeau QCM">   
    <p>Une fois le QCM terminé l'élève peut vous appelez pour que vous releviez sa note.</p>
    <p>Il peut consulter ses résultats par domaines (Bonne réponse, Mauvaise réponse, je ne sais pas). Et accéder au corrigé.</p>
    <p>Les résultats sont également consultables dans le menu :<strong>Résultats des élèves</strong>.</p>
    <h2>5) Ajout de vos questions</h2>
    <p style="color:red;">Avant d'ajouter une question assurez vous que celle-ci n'est pas déjà présente dans la base.
    Pour cela il vous suffit d'aller dans le menu <strong>Créer un QCM/Rechercher une question</strong> et de faire une recherche
    sur quelques mots clés bien choisis.</p>
    <p>L'ajout de question se fait via le menu :</p>
    <img src="image_aide/operation_question1.png" alt="Menu Opération sur les questions">
    <p>Sélectionné <strong>Ajout</strong>, vous obtenez alors le formulaire de saisie suivant :</p>
    <img src="image_aide/ajout_question1.png" alt="Formulaire Ajout Question">
    <p>Comme vous pouvez le voir dans cet exemple vous pouvez saisir du code HTML. Voici quelques exemples :</p>
    <p>Pour le code Python vous pouvez utiliser la balise <strong>&lt;code&gt;</strong> pour un affichage "inline" :</p>
    <img src="image_aide/tag_code.png" alt="balise code"><br /><br>
    <p>Ainsi que la balise <strong>&lt;pre&gt;</strong> pour un affichage "block":</p>
    <img src="image_aide/tag_pre.png" alt="balise code"><br><br>
    <p>Pour les tables de vérités vous pouvez utiliser la classe <strong>table_verite</strong>: </p>
    <img src="image_aide/table_verite.png" alt="style table verite"><br><br>
    <p>Vous pouvez également adjoindre une image à votre question. Celle-ci ne doit pas excéder 300 Ko et être au format jpg, jpeg ou png.</p>
    <img src="image_aide/ajout_question2.png" alt="Formulaire Ajout Question"><br><br>
    <p>Reste à définir la bonne réponse, ainsi que la rubrique/sous-rubrique correspondant à votre question :</p>
    <img src="image_aide/ajout_question3.png" alt="Formulaire Ajout Question"><br><br>
    <p>Vous pouvez alors visualiser le rendu de votre question (en vert la bonne réponse).</p>
    <img src="image_aide/ajout_question4.png" alt="Formulaire Ajout Question">
    <p>Si le résultat vous convient il vous suffit alors de cliquer sur le bouton permettant d'insérer votre question dans la base :</p>
    <img src="image_aide/ajout_question5.png" alt="Formulaire Ajout Question">

<?php
endif;
?>

<?php include("footer.php") ?>

</body>

</html>
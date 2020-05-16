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

    <h1 class='h1-qcm'>Quelques QCM clé en main</h1>
	
	<p>Pour chaque QCM proposé sur cette page, vous pouvez :</p>
	<ul>
	<li>Sélectionner puis copier la liste des numéros de questions pour ensuite l'utiliser via le menu <strong>[Créer un QCM/ A partir d'une liste]</strong></li>
	<li>Générer directement le QCM correspondant en cliquant sur le bouton de génération correspondant.</li>
    </ul>
	<br>

    <ul>
        <li>
            <h3>QCM synthèse sur Espace (Aires,Volumes,Sections)</h3>
            <p>1;19;20;26;27;28;29;30;31;32</p>
            <form method="post" action="qcm-valide-cle.php">
			<input type="hidden"  
			value="1;19;20;26;27;28;29;30;31;32" 
			name="cle" >
			<button class='btn btn-info'>QCM synthèse sur Espace (Aires,Volumes,Sections)</button>
			</form>
		</li>
        <br>
        <li>
            <h3>QCM exo Boule pétanque</h3>
            <p>40;41;42</p>
            <form method="post" action="qcm-valide-cle.php">
			<input type="hidden"  
			value="40;41;42" 
			name="cle" >
			<button class='btn btn-info'>QCM exo Boule pétanque</button>
			</form>			
        </li>
		
        <li>
            <h3>QCM exo Pyramide du Louvre</h3>
            <p>43;44</p>
            <form method="post" action="qcm-valide-cle.php">
			<input type="hidden"  
			value="43;44" 
			name="cle" >
			<button class='btn btn-info'>QCM exo Boule pétanque</button>
			</form>			
        </li>

		<li>
            <h3>QCM exo Cornets de frites</h3>
            <p>45;46</p>
            <form method="post" action="qcm-valide-cle.php">
			<input type="hidden"  
			value="45;46" 
			name="cle" >
			<button class='btn btn-info'>QCM exo Boule pétanque</button>
			</form>			
        </li>
		
    </ul>

<?php
endif;
?>

<?php include("footer.php") ?>

</body>

</html>



 <nav class="navbar navbar-expand-sm">
     <button class="navbar-toggler" type="button" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
     </button>
     <div class="navbar" id="navbarNavDropdown">
         <ul class="navbar-nav">
             <li class="nav-item active">
                 <a class="nav-link" href="accueil.php">Accueil</a>
             </li>
             <li class="nav-item dropdown">
                 <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     Créer un QCM
                 </a>
                 <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                     <form action="selection-niveau.php" method='POST' id='form-nav'>
                         <input hidden value="1" name='niveau' id='hidden-nav'>
                         <a class="dropdown-item" href="javascript:lien('1');">Aléatoire niveau 1ère</a>
                         <a class="dropdown-item" href="javascript:lien('T');">Aléatoire niveau Terminale</a>
                     </form>
                     <a class="dropdown-item" href="selection-manuelle.php">Par sélection manuelle</a>
                     <a class="dropdown-item" href="selection-parliste.php">A partir d'une liste</a>
                     <a class="dropdown-item" href="cle-en-main.php">Clé en main</a>                     
                     <a class="dropdown-item" href="recherche-question.php">Rechercher une question</a>                     
                 </div>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="resultats.php">Résultats des élèves</a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="qcms-utilisateurs.php">Vos QCM's</a>
             </li>
             <li class="nav-item dropdown">
                 <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     Opérations sur les questions
                 </a>
                 <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                     <a class="dropdown-item" href="ajout.php">Ajout</a>
                     <a class="dropdown-item" href="modification.php">Modification</a>
                     <a class="dropdown-item" href="exports.php">Exports</a>
                 </div>
             </li>
             <li class="nav-item dropdown">
                 <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     Aide
                 </a>
                 <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                     <a class="dropdown-item" href="aide.php">Prise en main rapide</a>
                     <a class="dropdown-item" href="import-export.php">Export des questions</a>
                     <a class="dropdown-item" href="donnees-personnelles.php">Données personnelles</a>
                     <a class="dropdown-item" href="report.php">Nous contacter</a>
                     <a class="dropdown-item" href="credits.php">A propos</a>
                 </div>
             </li>

             <?php
                // L'utilisateur actuel est-il un admin ?
                if ($_SESSION['qualite'] == 'admin') : ?>
                 <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         Informations Administrateurs
                     </a>
                     <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                         <a class="dropdown-item" href="info-admin.php">Résumé</a>
                         <a class="dropdown-item" href="info-util.php">Utilisateurs</a>
                         <a class="dropdown-item" href="nouvel-utilisateur-by-admin.php">Création compte sans mail académique</a>                         
                     </div>
                 </li>
             <?php endif; ?>
         </ul>
     </div>
 </nav>

 <script>
     function lien(niveau) {
         $('#hidden-nav').val(niveau);
         $('#form-nav').submit();
     }
 </script>
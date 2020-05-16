<?php
    session_start();

    if (empty($_SESSION) or $_SESSION['connecte'] != true) :
        echo "Vous ne devriez pas être ici : <a href='index.php'>Retour</a>";
    else :

        include('connexionbdd.php');

        $zip = new ZipArchive();
        $filename = "./images.zip";

        if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
            exit("Erreur lors de la création du fichier <$filename>\n");
        }

          $dir = 'image_questions/';

          // Création du zip
          if ($dh = opendir($dir)){
               while (($file = readdir($dh)) !== false){
                 // Parcours des fichiers
                 if (is_file($dir.$file)) {
                    if($file != '' && $file != '.' && $file != '..'){
                       $zip->addFile($dir.$file);
                    }
                 }
               }
              closedir($dh);
          }

          $zip->close();

    // Download Created Zip file
        $filename = "images.zip";

      if (file_exists($filename)) {
         header('Content-Type: application/zip');
         header('Content-Disposition: attachment; filename="'.basename($filename).'"');
         header('Content-Length: ' . filesize($filename));

         flush();
         readfile($filename);
         // On efface le zip côté serveur
         unlink($filename);

       }

    endif;

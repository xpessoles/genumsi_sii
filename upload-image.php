<?php
// Include the database configuration file
include 'connexionbdd.php';
/* Getting file name */
$filename = $_FILES['file']['name'];

/* Location */
$location = "image/questions/".$filename;
$uploadOk = 1;
$imageFileType = pathinfo($location,PATHINFO_EXTENSION);

/* Valid Extensions */
$valid_extensions = array("jpg","jpeg","png","gif");
/* Check file extension */
if( !in_array(strtolower($imageFileType),$valid_extensions) ) {
   $uploadOk = 0;
}

if($uploadOk == 0){
   echo 'erreur_format';
}else{
   /* Upload file */
   if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
      echo 'success';
   }else{
      echo 'erreur';
   }
}

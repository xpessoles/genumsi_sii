<?php 
include("config.php"); 
if ($GENUMMATIERE == "NSI") :
         include("cle-en-main-nsi.php"); 
	  else:
	     include("cle-en-main-maths.php"); 
      endif; 
?>	

</html>
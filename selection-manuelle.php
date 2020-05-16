<?php 
include("config.php"); 
if ($GENUMMATIERE == "NSI") :
         include("selection-manuelle-nsi.php"); 
	  else:
	     include("selection-manuelle-maths.php"); 
      endif; 
?>	

</html>
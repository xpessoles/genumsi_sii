<?php
if (!isset($_POST['question']) or 
!isset($_POST['repA']) or
!isset($_POST['repB']) or
!isset($_POST['repC']) or
!isset($_POST['repD']) or 
is_null($_POST['question']) or 
is_null($_POST['repA']) or
is_null($_POST['repB']) or
is_null($_POST['repC']) or
is_null($_POST['repD'])) {
    
    echo 'error';
    
} else {
    
    require_once 'htmlpurifier-4.12.0-lite/library/HTMLPurifier.auto.php';
    
    $purifier = new HTMLPurifier();
    $q = $purifier->purify($_POST['question']);
    $repA = $purifier->purify($_POST['repA']);
    $repB = $purifier->purify($_POST['repB']);
    $repC = $purifier->purify($_POST['repC']);
    $repD = $purifier->purify($_POST['repD']);

    $str = $q . "---purification---" . $repA . "---purification---" . $repB . "---purification---" . $repC . "---purification---" . $repD;

    echo $str;

}
    ?>
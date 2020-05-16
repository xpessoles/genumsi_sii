<?php
function url($file_name){
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        }
        else{
            $protocol = 'http';
        }

        $tab= explode("/", $_SERVER['SCRIPT_NAME']);

        $longeur_script = strlen(end($tab))+1;

        return $protocol . "://" . $_SERVER['SERVER_NAME']. substr($_SERVER['SCRIPT_NAME'],0, -$longeur_script) . "/"  . $file_name;
}

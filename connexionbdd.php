<?php
// Connexion à la base de données   
try {
    $bdd = new PDO('mysql:host=localhost;dbname=genumsi;charset=utf8', 'root', 'toto');
} catch (Exception $e) {
    die('Erreur : '.$e->getMessage());
}

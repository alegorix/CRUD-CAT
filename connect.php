<?php
try{
    // Connexion à la base
    $db = new PDO('mysql:host=localhost;dbname=crud;charset=utf8mb4', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e){
    echo 'Erreur : '. $e->getMessage();
    die();
}

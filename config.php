<?php
// config.php

// Configuration base de données
$host = 'localhost';
$dbname = 'gestion_bhelmar';
$username = 'root';
$password = ''; // à adapter si tu as un mot de passe

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Mode d'erreur : exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>

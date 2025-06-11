<?php
$host = 'localhost';
$dbname = 'gestion_bhelmar'; // Remplace par le nom exact de ta base
$user = 'root';
$pass = ''; // Mot de passe vide par défaut sous XAMPP

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

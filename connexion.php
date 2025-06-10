<?php
// connexion.php
$host = 'localhost';
$dbname = 'gestion_bhelmar'; // Mets ici le nom de ta base
$user = 'root'; // ou ton user MySQL
$pass = ''; // mot de passe vide sur XAMPP

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
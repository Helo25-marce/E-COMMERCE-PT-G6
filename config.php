<?php
$host = 'localhost';
$db = 'gestion_bhelmar';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}
?>

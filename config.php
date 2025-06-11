<?php
$host = 'localhost';
$db = 'gestion_bhelmar';
$user = 'root';
$pass = '';

try {
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) throw new Exception("Connexion échouée : " . $conn->connect_error);
} catch (Exception $e) {
    die($e->getMessage());
}
?>

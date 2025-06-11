<?php
session_start();
if ($_SESSION['role'] != 'livreur') {
    header('Location: connexion.php'); // Redirection si l'utilisateur n'est pas livreur
}
?>

<h1>Tableau de bord Livreur</h1>
<a href="livraisons_en_cours.php">Livraisons en cours</a>
<a href="historique_livraisons.php">Historique des livraisons</a>

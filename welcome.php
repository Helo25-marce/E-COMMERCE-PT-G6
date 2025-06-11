<?php
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header("Location: login.php");
    exit();
}
?>
<h1>Bienvenue <?= htmlspecialchars($_SESSION['utilisateur']['prenom']) ?> !</h1>
<ul>
    <li><a href="indexboutique.php">Voir les boutiques</a></li>
    <li><a href="profile.php">Mon profil</a></li>
    <li><a href="panier.php">Voir mon panier</a></li>
    <li><a href="logout.php">Se déconnecter</a></li>
</ul>

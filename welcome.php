<?php
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header("Location: login.php");
    exit();
}
$user = $_SESSION['utilisateur'];
?>

<h1>Bienvenue <?= htmlspecialchars($user['prenom']) ?> !</h1>
<p><a href="logout.php">Se déconnecter</a></p>
<p><a href="panier.php">Voir le panier</a></p>
<p><a href="profile.php">Mon Profil</a></p>

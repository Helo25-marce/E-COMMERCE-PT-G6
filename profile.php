<?php
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header("Location: login.php");
    exit();
}
$user = $_SESSION['utilisateur'];
?>
<h2>Profil</h2>
<ul>
    <li>Nom : <?= htmlspecialchars($user['nom']) ?></li>
    <li>Prénom : <?= htmlspecialchars($user['prenom']) ?></li>
    <li>Email : <?= htmlspecialchars($user['email']) ?></li>
    <li>Téléphone : <?= htmlspecialchars($user['telephone']) ?></li>
    <li>Adresse : <?= htmlspecialchars($user['adresse']) ?></li>
</ul>
<a href="welcome.php">Retour</a>

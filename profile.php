<?php
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header("Location: login.php");
    exit();
}
$user = $_SESSION['utilisateur'];
?>
<h2>Mon Profil</h2>
<p>Nom : <?= $user['nom'] ?></p>
<p>Prénom : <?= $user['prenom'] ?></p>
<p>Email : <?= $user['email'] ?></p>
<p>Téléphone : <?= $user['telephone'] ?></p>
<p>Adresse : <?= $user['adresse'] ?></p>
<a href="welcome.php">Retour à l'accueil</a>

<?php
session_start();
if (!isset($_SESSION['utilisateur_id']) || $_SESSION['utilisateur_role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Bienvenue Admin <?= htmlspecialchars($_SESSION['utilisateur_nom']) ?></h1>
    <a href="gestion_utilisateurs.php">Gestion des utilisateurs</a>
    <a href="gestion_produits.php">Gestion des produits</a>
    <a href="gestion_commandes.php">Gestion des commandes</a>
    <div class="text-center mt-4">
        <a href="../logout.php" class="btn btn-danger">Déconnexion</a>
    </div>
</div>
</body>
</html>



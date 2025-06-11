<?php
session_start();
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Wel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <i class="fas fa-home me-2"></i>BHELMAR
            </a>
            <div class="d-flex align-items-center">
                <span class="navbar-text text-white me-3">
                    Connecté en tant que <?= htmlspecialchars($_SESSION['utilisateur_nom']) ?>
                </span>
                <a href="logout.php" class="btn btn-outline-light">Déconnexion</a>
            </div>
        </div>
    </nav>
    <div class="container mt-5 text-center welcome-box">
        <h1 class="mb-3">Bienvenue <?= htmlspecialchars($_SESSION['utilisateur_nom']) ?> !</h1>
        <p class="lead">Votre rôle : <?= htmlspecialchars($_SESSION['utilisateur_role']) ?></p>
        <p>Email : <?= htmlspecialchars($_SESSION['utilisateur_email']) ?></p>
        <a href="index.php" class="btn btn-accent mt-3">
            <i class="fas fa-store me-1"></i>Retour à l'accueil
        </a>
    </div>
</body>
</html>

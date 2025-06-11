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
    <link rel="stylesheet" href="Style.css">
</head>
<body>
    <div class="welcome-box">
        <h1>Bienvenue <?= htmlspecialchars($_SESSION['utilisateur_nom']) ?> !</h1>
        <p>Votre rôle : <?= htmlspecialchars($_SESSION['utilisateur_role']) ?></p>
        <p>Email : <?= htmlspecialchars($_SESSION['utilisateur_email']) ?></p>
        <a href="logout.php" class="btn btn-danger">Déconnexion</a>
    </div>
</body>
</html>

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
    <style>
        body {
            background: linear-gradient(135deg, #74ebd5, #9face6);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .welcome-box {
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        .welcome-box h1 {
            font-size: 2em;
            color: #2c3e50;
        }
        .welcome-box p {
            margin: 10px 0;
            font-size: 1.1em;
        }
        .welcome-box .btn {
            margin: 10px 5px;
        }
    </style>
</head>
<body>
    <div class="welcome-box">
        <h1>Bienvenue <?= htmlspecialchars($_SESSION['utilisateur_nom']) ?> !</h1>
        <p><strong>Rôle :</strong> <?= htmlspecialchars($_SESSION['utilisateur_role']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($_SESSION['utilisateur_email']) ?></p>
        <div class="mt-4">
            <a href="index.php" class="btn btn-success">Aller à l'accueil</a>
            <a href="logout.php" class="btn btn-danger">Déconnexion</a>
        </div>
    </div>
</body>
</html>

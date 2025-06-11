<?php
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header("Location: login.php");
    exit();
}
$user = $_SESSION['utilisateur'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mon Profil - BHELMAR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: url('hero.jpg') center/cover no-repeat;
      position: relative;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      color: #fff;
      min-height: 100vh;
    }
    body::before {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.6);
      z-index: 0;
    }
    .container {
      position: relative;
      z-index: 1;
      max-width: 600px;
      margin-top: 5rem;
      background-color: rgba(255, 255, 255, 0.1);
      padding: 2rem;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(255,255,255,0.2);
    }
    h2 {
      color: #f39c12;
      text-align: center;
      margin-bottom: 2rem;
    }
    p {
      font-size: 1.1rem;
      margin-bottom: .75rem;
    }
    a.btn-retour {
      display: block;
      margin-top: 2rem;
      background-color: #e67e22;
      color: white;
      padding: .6rem 1.2rem;
      text-align: center;
      text-decoration: none;
      border-radius: 8px;
    }
    a.btn-retour:hover {
      background-color: #d35400;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Mon Profil</h2>
    <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom']) ?></p>
    <p><strong>Prénom :</strong> <?= htmlspecialchars($user['prenom']) ?></p>
    <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Téléphone :</strong> <?= htmlspecialchars($user['telephone']) ?></p>
    <p><strong>Adresse :</strong> <?= htmlspecialchars($user['adresse']) ?></p>
    <a href="index.php" class="btn-retour">Retour à l'accueil</a>
  </div>
</body>
</html>

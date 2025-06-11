<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../connexion.php");
    exit;
}

// Statistiques
$total_utilisateurs = $pdo->query("SELECT COUNT(*) FROM utilisateur")->fetchColumn();
$total_commandes = $pdo->query("SELECT COUNT(*) FROM commandes")->fetchColumn();
$total_produits = $pdo->query("SELECT COUNT(*) FROM produits")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Admin - Tableau de bord</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-4">
    <h1 class="mb-4">Tableau de bord administrateur</h1>

    <div class="row">
      <div class="col-md-4">
        <div class="card text-bg-primary mb-3">
          <div class="card-body">
            <h5 class="card-title">Utilisateurs</h5>
            <p class="card-text fs-4"><?= $total_utilisateurs ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-bg-success mb-3">
          <div class="card-body">
            <h5 class="card-title">Commandes</h5>
            <p class="card-text fs-4"><?= $total_commandes ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-bg-warning mb-3">
          <div class="card-body">
            <h5 class="card-title">Produits</h5>
            <p class="card-text fs-4"><?= $total_produits ?></p>
          </div>
        </div>
      </div>
    </div>

    <nav class="nav mt-4">
      <a class="nav-link" href="utilisateurs.php">Gérer les utilisateurs</a>
      <a class="nav-link" href="produits.php">Gérer les produits</a>
      <a class="nav-link" href="commandes.php">Gérer les commandes</a>
      <a class="nav-link" href="parametres.php">Paramètres</a>
      <a class="nav-link text-danger" href="logout.php">Déconnexion</a>
    </nav>
  </div>
</body>
</html>

<?php
// === admin_dashboard.php ===
session_start();
require_once 'config.php';

// Vérification de session admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: alog.php");
    exit();
}

// Statistiques
$stmt_clients = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'client'");
$total_clients = $stmt_clients->fetchColumn();

$stmt_livreurs = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'livreur'");
$total_livreurs = $stmt_livreurs->fetchColumn();

$stmt = $pdo->query("SELECT id, nom, email FROM utilisateurs WHERE role = 'livreur'");
$livreurs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tableau de bord Admin - BHELMAR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #ecf0f1;
      font-family: 'Segoe UI', sans-serif;
    }
    .navbar {
      background-color: #2c3e50;
    }
    .navbar-brand {
      color: #ecf0f1 !important;
      font-weight: bold;
    }
    .card {
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      border-radius: 10px;
    }
    .card-title {
      color: #34495e;
    }
    table th {
      background-color: #2980b9;
      color: white;
    }
    table td, table th {
      vertical-align: middle;
    }
    .btn-danger {
      font-size: 0.9rem;
    }
    .btn-primary {
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">BHELMAR - ADMIN</a>
  </div>
</nav>
<div class="container">
  <h2 class="mb-4 text-center">Tableau de bord Administrateur</h2>

  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card text-bg-light">
        <div class="card-body">
          <h5 class="card-title">Total Clients</h5>
          <p class="card-text display-6"><?= $total_clients ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card text-bg-light">
        <div class="card-body">
          <h5 class="card-title">Total Livreurs</h5>
          <p class="card-text display-6"><?= $total_livreurs ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <span>Liste des Livreurs</span>
      <a href="inscription_livreur.php" class="btn btn-light btn-sm">Ajouter un livreur</a>
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($livreurs as $livreur): ?>
          <tr>
            <td><?= $livreur['id'] ?></td>
            <td><?= htmlspecialchars($livreur['nom']) ?></td>
            <td><?= htmlspecialchars($livreur['email']) ?></td>
            <td><a href="supprimer_livreur.php?id=<?= $livreur['id'] ?>" class="btn btn-danger btn-sm">Supprimer</a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>

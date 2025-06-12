<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: alog.php");
    exit();
}

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
  <title>Admin - Tableau de bord</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f4f6f9;
      font-family: 'Segoe UI', sans-serif;
    }
    .navbar {
      background-color: #2c3e50;
    }
    .navbar-brand {
      color: white !important;
    }
    .card {
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .card-header {
      font-weight: bold;
    }
    .btn-logout {
      background-color: #e74c3c;
      color: white;
    }
    .btn-logout:hover {
      background-color: #c0392b;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">BHELMAR | Admin</a>
    <a href="logout.php" class="btn btn-logout btn-sm">Déconnexion</a>
  </div>
</nav>

<div class="container">
  <h2 class="mb-4 text-center">Tableau de bord Administrateur</h2>
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card text-center p-3">
        <h5>Total Clients</h5>
        <h3><?= $total_clients ?></h3>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card text-center p-3">
        <h5>Total Livreurs</h5>
        <h3><?= $total_livreurs ?></h3>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      Liste des Livreurs
      <a href="inscription_livreur.php" class="btn btn-light btn-sm">Ajouter un livreur</a>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-hover">
        <thead class="table-dark">
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
            <td>
              <a href="supprimer_livreur.php?id=<?= $livreur['id'] ?>" class="btn btn-danger btn-sm">Supprimer</a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>

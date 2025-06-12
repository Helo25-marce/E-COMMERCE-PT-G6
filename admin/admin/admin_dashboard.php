<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: alog.php");
    exit;
}

$stmt_clients = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'client'");
$total_clients = $stmt_clients->fetchColumn();

$stmt_livreurs = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'livreur'");
$total_livreurs = $stmt_livreurs->fetchColumn();

$stmt = $pdo->query("SELECT id_utilisateur, nom, email FROM utilisateurs WHERE role = 'livreur'");
$livreurs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Admin - Tableau de bord</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg bg-dark navbar-dark px-3">
  <a class="navbar-brand" href="#">BHELMAR | Admin</a>
  <a href="logout.php" class="btn btn-outline-light ms-auto">Déconnexion</a>
</nav>
<div class="container mt-5">
  <h2 class="mb-4 text-center">Tableau de bord Administrateur</h2>
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card text-center p-4">
        <h5>Total Clients</h5>
        <h2><?= $total_clients ?></h2>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card text-center p-4">
        <h5>Total Livreurs</h5>
        <h2><?= $total_livreurs ?></h2>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between">
      <span>Liste des Livreurs</span>
      <a href="inscription_livreur.php" class="btn btn-light btn-sm">Ajouter</a>
    </div>
    <div class="card-body p-0">
      <table class="table table-striped mb-0">
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
            <td><?= $livreur['id_utilisateur'] ?></td>
            <td><?= htmlspecialchars($livreur['nom']) ?></td>
            <td><?= htmlspecialchars($livreur['email']) ?></td>
            <td>
              <a href="supprimer_livreur.php?id=<?= $livreur['id_utilisateur'] ?>" class="btn btn-danger btn-sm">Supprimer</a>
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

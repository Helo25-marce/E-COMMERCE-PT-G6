<?php
// === livreur_dashboard.php ===
session_start();
require_once 'config.php';

if (!isset($_SESSION['livreur_id'])) {
    header('Location: login_roles.php');
    exit();
}

$livreur_id = $_SESSION['livreur_id'];

// Exemple : récupérer les commandes du livreur
$stmt = $pdo->prepare("SELECT * FROM commandes WHERE livreur_id = ?");
$stmt->execute([$livreur_id]);
$commandes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tableau de bord Livreur</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #ecf0f1; font-family: 'Segoe UI', sans-serif; }
    .container { margin-top: 40px; }
    .dashboard-card {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    h2 { color: #2c3e50; }
  </style>
</head>
<body>
<div class="container">
  <div class="dashboard-card">
    <h2>Bienvenue Livreur #<?= htmlspecialchars($livreur_id) ?> 🚚</h2>
    <p>Voici vos commandes :</p>
    <table class="table table-bordered table-striped mt-3">
      <thead>
        <tr>
          <th>Commande ID</th>
          <th>Client</th>
          <th>Adresse</th>
          <th>Statut</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($commandes as $cmd): ?>
        <tr>
          <td><?= $cmd['id'] ?></td>
          <td><?= htmlspecialchars($cmd['client_nom']) ?></td>
          <td><?= htmlspecialchars($cmd['adresse']) ?></td>
          <td><?= htmlspecialchars($cmd['statut']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (count($commandes) === 0): ?>
        <tr><td colspan="4" class="text-center">Aucune commande assignée.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    <a href="logout.php" class="btn btn-secondary">Se déconnecter</a>
  </div>
</div>
</body>
</html>

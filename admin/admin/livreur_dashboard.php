<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['livreur_id'])) {
    header("Location: livreur_login.php");
    exit;
}

$livreur_id = $_SESSION['livreur_id'];

// Récupérer les commandes du livreur
$stmt = $pdo->prepare("SELECT * FROM commandes WHERE id_livreur = ?");
$stmt->execute([$livreur_id]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les notifications
$notifStmt = $pdo->prepare("SELECT * FROM notifications WHERE id_utilisateur = ? ORDER BY envoye_le DESC");
$notifStmt->execute([$livreur_id]);
$notifications = $notifStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tableau de bord Livreur</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
        background: #f8f9fa;
        font-family: 'Segoe UI', sans-serif;
    }
    .navbar {
        background-color: #2c3e50;
    }
    .navbar-brand, .navbar-brand small {
        color: #ecf0f1 !important;
    }
    .dashboard-header {
        padding: 20px;
        background-color: #2980b9;
        color: white;
        margin-bottom: 20px;
        text-align: center;
    }
    .section-title {
        margin-top: 20px;
        margin-bottom: 10px;
        font-weight: bold;
    }
    .card {
        margin-bottom: 15px;
    }
    .notification {
        background: #fff3cd;
        padding: 10px 15px;
        border-left: 5px solid #ffc107;
        margin-bottom: 10px;
        border-radius: 5px;
    }
    footer {
        background-color: #2c3e50;
        color: white;
        padding: 10px;
        text-align: center;
        margin-top: 40px;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="../images.h/logobon.jpg" alt="Logo" style="height: 40px; margin-right: 10px;">
      <div>
        <div>BHELMAR</div>
        <small>Tous à domicile</small>
      </div>
    </a>
    <div class="ms-auto">
      <a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </div>
  </div>
</nav>

<div class="dashboard-header">
  <h2>Bienvenue Livreur</h2>
</div>

<div class="container">
  <h4 class="section-title">📦 Mes Livraisons</h4>
  <?php if (count($commandes) === 0): ?>
    <div class="alert alert-info">Aucune commande à livrer.</div>
  <?php else: ?>
    <?php foreach ($commandes as $cmd): ?>
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Commande #<?= $cmd['id_commande'] ?> - <?= $cmd['statut'] ?></h5>
          <p class="card-text">
            <strong>Date :</strong> <?= $cmd['date_commande'] ?><br>
            <strong>Adresse :</strong> <?= $cmd['adresse_livraison'] ?><br>
            <strong>Créneau :</strong> <?= $cmd['creneau_livraison'] ?><br>
            <strong>Total :</strong> <?= number_format($cmd['total'], 0, ',', ' ') ?> FCFA
          </p>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <h4 class="section-title">🔔 Mes Notifications</h4>
  <?php if (count($notifications) === 0): ?>
    <div class="alert alert-secondary">Aucune notification reçue.</div>
  <?php else: ?>
    <?php foreach ($notifications as $n): ?>
      <div class="notification">
        <strong><?= htmlspecialchars($n['type_notification']) ?> :</strong>
        <?= htmlspecialchars($n['contenu']) ?>
        <div class="text-muted" style="font-size: 12px;">Envoyé le <?= $n['envoye_le'] ?></div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<footer>
  &copy; <?= date('Y') ?> BHELMAR - Tous droits réservés
</footer>

</body>
</html>

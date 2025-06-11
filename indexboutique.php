<?php
// === indexboutique.php ===
session_start();
require_once 'config.php';

try {
    $stmt = $pdo->query("SELECT id, nom, logo FROM boutiques");
    $boutiques = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors du chargement des boutiques : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Liste des Boutiques - BHELMAR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f9f9f9;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
    }
    .navbar {
      background-color: #d35400;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .navbar-brand img {
      height: 40px;
    }
    .navbar-brand span {
      color: white;
      font-size: 1.5rem;
      font-weight: bold;
      margin-left: .5rem;
    }
    .container {
      margin-top: 50px;
    }
    .card-boutique {
      border: none;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .card-boutique:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .card-boutique img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }
    .card-boutique .card-body {
      background: white;
      padding: 1.25rem;
    }
    .card-title {
      color: #d35400;
      font-weight: bold;
    }
    .btn-details {
      background: #e67e22;
      color: white;
      border: none;
      transition: background 0.3s ease;
    }
    .btn-details:hover {
      background: #cf711f;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="logo.jpg" alt="Logo BHELMAR">
      <span>BHELMAR</span>
    </a>
  </div>
</nav>

<div class="container">
  <h2 class="text-center my-4" style="color:#d35400;">Nos Boutiques</h2>
  <div class="row g-4">
    <?php foreach ($boutiques as $b): ?>
    <div class="col-md-4">
      <div class="card card-boutique">
        <img src="images/<?= htmlspecialchars($b['logo']) ?>" alt="<?= htmlspecialchars($b['nom']) ?>">
        <div class="card-body text-center">
          <h5 class="card-title"><?= htmlspecialchars($b['nom']) ?></h5>
          <a href="detailB.php?id=<?= $b['id'] ?>" class="btn btn-details">Voir Détails</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

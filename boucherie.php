<?php
// === boucherie.php ===
session_start();
require_once 'config.php';
$type = 'Boucherie';
$stmt = $pdo->prepare("SELECT id, nom, logo FROM boutiques WHERE nom LIKE ?");
$stmt->execute(["%$type%"]);
$boutiques = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Boucheries - BHELMAR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #fff;
      font-family: 'Segoe UI', sans-serif;
    }
    .navbar {
      background: #c0392b;
    }
    .navbar-brand {
      color: #fff !important;
    }
    .container {
      padding: 2rem;
    }
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      margin-bottom: 1.5rem;
      transition: transform .2s;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .card-img-top {
      height: 200px;
      object-fit: cover;
      border-top-left-radius: 12px;
      border-top-right-radius: 12px;
    }
    .card-title {
      color: #c0392b;
      font-weight: bold;
    }
    .btn-back {
      display: inline-block;
      margin-bottom: 1rem;
      color: #c0392b;
      text-decoration: none;
      font-weight: bold;
    }
    .btn-detail {
      background: #c0392b;
      color: #fff;
      border: none;
      padding: .5rem 1rem;
      border-radius: 8px;
    }
    .btn-detail:hover {
      background: #96281b;
    }
  </style>
</head>
<body>
<nav class="navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="categories.php">← Catégories</a>
  </div>
</nav>
<div class="container">
  <h2 class="mb-4 text-center">Boucheries locales</h2>
  <!-- Bouton retour -->
  <a href="categories.php" class="btn-back">Retour aux catégories</a> 
  <div class="row g-4">
    <?php if (empty($boutiques)): ?>
      <p>Aucune boucherie trouvée.</p>
    <?php endif; ?>
    <?php foreach ($boutiques as $b): ?>
      <div class="col-md-4">
        <div class="card">
          <img src="images/<?= htmlspecialchars($b['logo']) ?>" class="card-img-top" alt="">
          <div class="card-body text-center">
            <h5 class="card-title"><?= htmlspecialchars($b['nom']) ?></h5>
            <a href="detailB.php?id=<?= $b['id'] ?>" class="btn-detail">Voir détails</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>

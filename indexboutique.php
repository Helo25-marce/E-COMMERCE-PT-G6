<!-- === indexboutique.php === -->
<?php
session_start();
require_once 'config.php';

try {
    $stmt = $pdo->query("SELECT id, nom, categorie FROM boutiques");
    $boutiques = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors du chargement des boutiques : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Boutiques - BHELMAR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
    }
    .container {
        margin-top: 50px;
    }
    .card {
        margin-bottom: 20px;
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .card-title {
        color: #007bff;
        font-weight: bold;
    }
  </style>
</head>
<body>
<div class="container">
  <h2 class="text-center mb-5">Liste des Boutiques</h2>
  <div class="row">
    <?php foreach ($boutiques as $b): ?>
      <div class="col-md-4">
        <div class="card p-3">
          <h5 class="card-title"><?= htmlspecialchars($b['nom']) ?></h5>
          <p class="text-muted">Catégorie: <?= htmlspecialchars($b['categorie']) ?></p>
          <a href="detailB.php?id=<?= $b['id'] ?>" class="btn btn-primary">Voir détails</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>


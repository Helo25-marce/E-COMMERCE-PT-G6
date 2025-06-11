<!-- === detailB.php === -->
<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    die("ID boutique non fourni.");
}

$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM produits WHERE boutique_id = ?");
$stmt->execute([$id]);
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Détails Boutique</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .container { margin-top: 50px; }
    .card { margin-bottom: 20px; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
  </style>
</head>
<body>
<div class="container">
  <h2 class="text-center mb-4">Produits de la boutique</h2>
  <div class="row">
    <?php foreach ($produits as $p): ?>
      <div class="col-md-4">
        <div class="card p-3">
          <h5 class="card-title"><?= htmlspecialchars($p['nom']) ?></h5>
          <p><?= number_format($p['prix'], 0, ',', ' ') ?> FCFA</p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>

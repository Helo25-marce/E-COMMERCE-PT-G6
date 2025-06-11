<?php
session_start();
require_once 'config.php';

$q = $_GET['q'] ?? '';
$categorie = $_GET['categorie'] ?? '';

// Construction de la requête
$sql = "SELECT * FROM produits WHERE nom LIKE ?";
$params = ["%$q%"];
if ($categorie !== '') {
    $sql .= " AND categorie = ?";
    $params[] = $categorie;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Résultats de recherche - BHELMAR</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
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
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .navbar-brand {
      color: #fff !important;
      font-weight: bold;
    }
    .container {
      margin-top: 2rem;
      margin-bottom: 2rem;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.05);
      transition: transform .2s, box-shadow .2s;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    }
    .card-img-top {
      height: 180px;
      object-fit: cover;
      border-top-left-radius: 12px;
      border-top-right-radius: 12px;
    }
    .btn-detail {
      background-color: #e67e22;
      color: #fff;
      border:none;
    }
    .btn-detail:hover {
      background-color: #cf711f;
    }
    .search-info {
      margin-bottom: 1rem;
      color: #555;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">BHELMAR</a>
    </div>
  </nav>

  <div class="container">
    <p class="search-info">
      Résultats pour <strong>"<?= htmlspecialchars($q) ?>"</strong>
      <?= $categorie ? "dans <strong>" . htmlspecialchars($categorie) . "</strong>" : '' ?> :
    </p>
    <?php if (empty($produits)): ?>
      <div class="alert alert-warning text-center">Aucun produit trouvé.</div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($produits as $p): ?>
          <div class="col-md-4">
            <div class="card">
              <img src="images/<?= htmlspecialchars($p['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['nom']) ?>">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($p['nom']) ?></h5>
                <p class="card-text"><?= htmlspecialchars(substr($p['description'], 0, 80)) ?>…</p>
                <p class="fw-bold"><?= number_format($p['prix'], 0, ',', ' ') ?> FCFA</p>
                <a href="detailB.php?id=<?= $p['id_produit'] ?>" class="btn btn-detail">Voir détails</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

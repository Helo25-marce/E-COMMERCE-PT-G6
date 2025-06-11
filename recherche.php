
<?php
// === recherche.php ===
require_once 'config.php';
$q = $_GET['q'] ?? '';
$categorie = $_GET['categorie'] ?? '';

$sql = "SELECT * FROM produits WHERE nom LIKE ?";
$params = ["%$q%"];
if (!empty($categorie)) {
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
  <title>Résultats de recherche</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
  <div class="container">
    <h3>Résultats pour "<?= htmlspecialchars($q) ?>" <?= $categorie ? "dans $categorie" : '' ?> :</h3>
    <div class="row">
      <?php foreach ($produits as $p): ?>
        <div class="col-md-4">
          <div class="card mb-3">
            <img src="images/<?= $p['image_url'] ?>" class="card-img-top">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($p['nom']) ?></h5>
              <p><?= htmlspecialchars($p['description']) ?></p>
              <a href="detailB.php?id=<?= $p['id_produit'] ?>" class="btn btn-outline-primary">Voir détails</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>


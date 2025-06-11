<?php
// === detailB.php ===
require_once 'config.php';
if (!isset($_GET['id'])) die("Aucun produit sélectionné.");
$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM produits WHERE id_produit = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch();
if (!$produit) die("Produit introuvable.");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Détail produit</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
  <div class="container">
    <h2><?= htmlspecialchars($produit['nom']) ?></h2>
    <img src="images/<?= htmlspecialchars($produit['image_url']) ?>" width="300" class="mb-3">
    <p><strong>Prix :</strong> <?= $produit['prix'] ?> FCFA</p>
    <p><?= htmlspecialchars($produit['description']) ?></p>
    <a href="indexboutique.php" class="btn btn-secondary">Retour</a>
  </div>
</body>
</html>

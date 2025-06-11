<?php
require_once 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: login.php');
    exit;
}

// Vérifier que l'ID est fourni
if (!isset($_GET['id'])) {
    die("Aucun produit sélectionné.");
}
$id = (int)$_GET['id'];

// Récupérer les détails de la boutique à partir de l'ID
$stmt = $pdo->prepare("SELECT * FROM boutiques WHERE id = ?");
$stmt->execute([$id]);
$boutique = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$boutique) {
    die("Boutique introuvable.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Détail Boucherie - BHELMAR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #fff; font-family: 'Segoe UI', sans-serif; }
    .navbar { background: #c0392b; }
    .navbar-brand { color: #fff !important; }
    .content { max-width: 800px; margin: 4rem auto; background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15); }
    .product-img { width: 100%; max-width: 400px; border-radius: 10px; margin-bottom: 1.5rem; }
    .btn-back { display: inline-block; margin-top: 2rem; background: #e74c3c; color: #fff; padding: .6rem 1.2rem; border-radius: 8px; text-decoration: none; }
    .btn-back:hover { background: #c0392b; }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">← Accueil</a>
    </div>
  </nav>

  <div class="content text-center">
    <h2><?= htmlspecialchars($boutique['nom']) ?></h2>
    <img src="images/<?= htmlspecialchars($boutique['logo']) ?>" alt="<?= htmlspecialchars($boutique['nom']) ?>" class="product-img">
    <p><?= nl2br(htmlspecialchars($boutique['description'])) ?></p>
    <a href="indexboutique.php" class="btn-back">Retour aux boucheries</a>
  </div>

</body>
</html>

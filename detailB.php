<?php
// === detailB.php ===
require_once 'config.php';
session_start();

// Vérifier que l'ID est fourni et l'utilisateur connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: login.php');
    exit;
}
if (!isset($_GET['id'])) {
    die("Aucun produit sélectionné.");
}
$id = (int)$_GET['id'];

// Récupérer le produit
$stmt = $pdo->prepare("SELECT * FROM produits WHERE id_produit = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$produit) {
    die("Produit introuvable.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Détail Produit - BHELMAR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background: url('hero.jpg') center/cover no-repeat;
      position: relative;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      color: #333;
      min-height: 100vh;
    }
    body::before {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(0,0,0,0.5);
      z-index: 0;
    }
    .navbar, .content, footer {
      position: relative;
      z-index: 1;
    }
    .navbar {
      background: #1c1c1e;
      padding: .75rem 2rem;
    }
    .navbar-brand img {
      height: 40px;
    }
    .navbar-brand span {
      color: #fff;
      font-size: 1.25rem;
      margin-left: .5rem;
    }
    .content {
      max-width: 800px;
      margin: 4rem auto;
      background: #fff;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    .content h2 {
      color: #e67e22;
      margin-bottom: 1rem;
      text-align: center;
    }
    .product-img {
      width: 100%;
      max-width: 400px;
      border-radius: 10px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
      margin-bottom: 1.5rem;
    }
    .info {
      font-size: 1.1rem;
      margin-bottom: 1rem;
    }
    .btn-back {
      display: inline-block;
      margin-top: 2rem;
      background: #e74c3c;
      color: #fff;
      padding: .6rem 1.2rem;
      border-radius: 8px;
      text-decoration: none;
      transition: background 0.3s ease;
    }
    .btn-back:hover {
      background: #c0392b;
    }
    footer {
      background: #1c1c1e;
      color: #ccc;
      text-align: center;
      padding: 1rem;
      margin-top: 4rem;
    }
  </style>
</head>
<body>
  <!-- Barre de navigation -->
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="logo.jpg" alt="Logo BHELMAR">
        <span>BHELMAR</span>
      </a>
      <div class="ms-auto">
        <a href="index.php" class="btn btn-light">Accueil</a>
      </div>
    </div>
  </nav>

  <!-- Contenu principal -->
  <div class="content text-center">
    <h2><?= htmlspecialchars($produit['nom']) ?></h2>
    <img src="images/<?= htmlspecialchars($produit['image_url']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>" class="product-img">
    <p class="info"><strong>Prix :</strong> <?= number_format($produit['prix'], 0, ',', ' ') ?> FCFA</p>
    <p class="info"><?= nl2br(htmlspecialchars($produit['description'])) ?></p>
    <form action="panierB.php" method="post" class="mt-4">
        <input type="hidden" name="id_produit" value="<?= $produit['id_produit'] ?>">
        <label for="quantite">Quantité :</label>
        <input type="number" id="quantite" name="quantite" value="1" min="1" class="form-control mb-2" style="max-width:100px; margin:auto;">
        <button type="submit" class="btn btn-access me-3"><i class="fas fa-cart-plus me-1"></i>Ajouter au panier</button>
    </form>
    <a href="indexboutique.php" class="btn-back"><i class="fas fa-chevron-left me-1"></i>Retour</a>
  </div>

  <!-- Pied de page -->
  <footer>
    <p>&copy; 2025 BHELMAR – Tous à domicile</p>
  </footer>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

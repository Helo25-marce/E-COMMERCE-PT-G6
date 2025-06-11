<?php
session_start();
require_once 'config.php';

// Vérifier connexion
if (!isset($_SESSION['utilisateur'])) {
    header('Location: login.php');
    exit;
}
$user = $_SESSION['utilisateur'];

// Initialiser panier
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Actions panier
if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
    unset($_SESSION['panier'][$_GET['id']]);
    $_SESSION['panier'] = array_values($_SESSION['panier']);
    header('Location: panierB.php'); exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['maj_quantite'])) {
    $id = $_POST['id_produit'];
    $q = max(1, (int)$_POST['quantite']);
    if (isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id]['quantite'] = $q;
    }
    header('Location: panierB.php'); exit;
}

// Construction liste produits
$liste = [];
$total = 0;
foreach ($_SESSION['panier'] as $idx => $item) {
    $stmt = $pdo->prepare('SELECT * FROM produits WHERE id_produit = ?');
    $stmt->execute([$item['id']]);
    if ($p = $stmt->fetch()) {
        $p['quantite'] = $item['quantite'];
        $p['sous_total'] = $p['prix'] * $p['quantite'];
        $liste[$idx] = $p;
        $total += $p['sous_total'];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mon Panier - BHELMAR</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {background:#f5f7fa; font-family:'Segoe UI',sans-serif;}
    .navbar {background:#1c1c1e;}
    .navbar-brand img {height:40px;}
    .navbar-brand span {color:#fff; margin-left:.5rem; font-size:1.2rem;}
    .container {margin-top:2rem;}
    table th, table td {vertical-align:middle;}
    .qte-input {width:60px;}
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="logo.jpg" alt="Logo">
      <span>BHELMAR</span>
    </a>
    <div class="ms-auto d-flex align-items-center">
      <span class="text-white me-3">Bonjour <?= htmlspecialchars($user['nom']) ?></span>
      <a href="logout.php" class="btn btn-outline-light me-3">Déconnexion</a>
    </div>
  </div>
</nav>
<div class="container">
  <h1>🛒 Votre Panier</h1>
  <?php if (empty($liste)): ?>
    <div class="alert alert-info">Votre panier est vide.</div>
  <?php else: ?>
    <table class="table table-striped bg-white">
      <thead><tr>
        <th>Produit</th><th>Image</th><th>Quantité</th><th>Prix</th><th>Sous-total</th><th>Action</th>
      </tr></thead>
      <tbody>
      <?php foreach ($liste as $idx => $p): ?>
        <tr>
          <td><?= htmlspecialchars($p['nom']) ?></td>
          <td><img src="images/<?= htmlspecialchars($p['image_url']) ?>" width="60"></td>
          <td>
            <form method="post" class="d-inline">
              <input type="hidden" name="id_produit" value="<?= $idx ?>">
              <input type="hidden" name="maj_quantite" value="1">
              <input type="number" name="quantite" value="<?= $p['quantite'] ?>" min="1" class="qte-input me-1">
              <button class="btn btn-sm btn-outline-secondary">✏️</button>
            </form>
          </td>
          <td><?= number_format($p['prix'],0,',',' ') ?> FCFA</td>
          <td><?= number_format($p['sous_total'],0,',',' ') ?> FCFA</td>
          <td><a href="?action=supprimer&id=<?= $idx ?>" class="btn btn-sm btn-outline-danger">🗑️</a></td>
        </tr>
      <?php endforeach; ?>
      <tr><td colspan="4" class="text-end"><strong>Total :</strong></td><td colspan="2"><?= number_format($total,0,',',' ') ?> FCFA</td></tr>
      </tbody>
    </table>
    <div class="d-flex justify-content-between mt-4">
      <a href="index.php" class="btn btn-secondary">🏠 Accueil</a>
      <a href="checkoutB.php" class="btn btn-primary">Passer à la commande</a>
    </div>
  <?php endif; ?>
</div>
</body>
</html>

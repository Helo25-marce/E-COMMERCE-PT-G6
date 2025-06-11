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
    header('Location: panierB.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['maj_quantite'])) {
    $id = $_POST['id_produit'];
    $q = max(1, (int)$_POST['quantite']);
    if (isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id]['quantite'] = $q;
    }
    header('Location: panierB.php');
    exit;
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
    .container { max-width: 950px; margin: 40px auto; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
    th, td { padding: 13px 8px; text-align: center; }
    th { background: #f3f6fb; font-size: 17px; }
    tr:nth-child(even) { background: #f8fbff; }
    td img { width: 65px; height: 65px; border-radius: 10px; object-fit: cover; box-shadow: 0 2px 10px #dde; }
    .qte-input { width: 55px; padding: 6px 4px; text-align: center; border-radius: 7px; border: 1px solid #ddd; }
    .btn-update, .btn-delete, .btn-main, .btn-commande {
        padding: 8px 15px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; transition: 0.2s;
    }
    .btn-update { background: #0080ff; color: #fff; }
    .btn-update:hover { background: #0058b3; }
    .btn-delete { background: #ffe5e5; color: #d11b1b; border: 1px solid #ffbbbb; }
    .btn-delete:hover { background: #ffd6d6; }
    .btn-commande { background: linear-gradient(90deg, #44c5c7, #0080ff 80%); color: #fff; margin-left: 10px; }
    .btn-main { background: #eee; color: #355; border: 1px solid #cbe; }
    .btn-main:hover { background: #d9f2fa; }
    .total-row td { font-size: 18px; font-weight: bold; color: #0080ff; }
  </style>
</head>
<body>
<div class="container">
  <h1 class="mb-4 text-center text-primary">🛒 Votre Panier</h1>
  <?php if (empty($liste)): ?>
    <div class="alert alert-info text-center">Votre panier est vide.</div>
  <?php else: ?>
    <table class="table table-borderless bg-white rounded shadow-sm">
      <thead>
        <tr>
          <th>Produit</th>
          <th>Image</th>
          <th>Quantité</th>
          <th>Prix</th>
          <th>Sous-total</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($liste as $idx => $p): ?>
        <tr>
          <td><?= htmlspecialchars($p['nom']) ?></td>
          <td><img src="images/<?= htmlspecialchars($p['image_url']) ?>" alt="" /></td>
          <td>
            <form method="post" class="d-inline">
              <input type="hidden" name="id_produit" value="<?= $idx ?>" />
              <input type="hidden" name="maj_quantite" value="1" />
              <input class="qte-input" type="number" name="quantite" min="1" value="<?= $p['quantite'] ?>" />
              <button class="btn-update" title="Mettre à jour">✏️</button>
            </form>
          </td>
          <td><?= number_format($p['prix'], 0, ',', ' ') ?> FCFA</td>
          <td><?= number_format($p['sous_total'], 0, ',', ' ') ?> FCFA</td>
          <td><a href="?action=supprimer&id=<?= $idx ?>" class="btn-delete" title="Supprimer">❌</a></td>
        </tr>
        <?php endforeach; ?>
        <tr class="total-row">
          <td colspan="4" class="text-end">TOTAL</td>
          <td colspan="2"><?= number_format($total, 0, ',', ' ') ?> FCFA</td>
        </tr>
      </tbody>
    </table>
    <div class="d-flex justify-content-end">
      <a href="index.php" class="btn-main"><i class="fas fa-home me-1"></i> Continuer mes achats</a>
      <a href="checkoutB.php" class="btn-commande"><i class="fas fa-credit-card me-1"></i> Passer à la commande</a>
    </div>
  <?php endif; ?>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
</body>
</html>

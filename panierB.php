<?php
session_start();
require_once("config.php");

if (!isset($_SESSION['utilisateur'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['panier'])) $_SESSION['panier'] = [];

if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
    unset($_SESSION['panier'][$_GET['id']]);
    $_SESSION['panier'] = array_values($_SESSION['panier']);
    header('Location: panierB.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['maj_quantite'])) {
    $id = $_POST['id_produit'];
    $qte = max(1, intval($_POST['quantite']));
    if (isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id]['quantite'] = $qte;
    }
    header('Location: panierB.php');
    exit;
}

$liste = [];
$total = 0;

if (!empty($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $index => $item) {
        $stmt = $conn->prepare("SELECT * FROM produits WHERE nom = ?");
        $stmt->bind_param("s", $item['produit']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $row['quantite'] = $item['quantite'];
            $row['sous_total'] = $item['quantite'] * $row['prix'];
            $liste[$index] = $row;
            $total += $row['sous_total'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mon Panier - BHELMAR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: url('hero.jpg') center/cover no-repeat;
      font-family: 'Segoe UI', sans-serif;
      color: #fff;
      min-height: 100vh;
      position: relative;
    }
    body::before {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.7);
      z-index: 0;
    }
    .container {
      position: relative;
      z-index: 1;
      margin-top: 3rem;
      background: rgba(255, 255, 255, 0.05);
      padding: 2rem;
      border-radius: 12px;
      backdrop-filter: blur(5px);
    }
    h1 {
      text-align: center;
      margin-bottom: 2rem;
      color: #f39c12;
    }
    table {
      width: 100%;
      color: #fff;
      background-color: rgba(0, 0, 0, 0.2);
    }
    table th, table td {
      padding: 10px;
      text-align: center;
      vertical-align: middle;
    }
    .btn-update, .btn-delete {
      background-color: transparent;
      border: none;
      color: white;
      font-size: 1.2rem;
    }
    .btn-main, .btn-commande {
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: bold;
    }
    .btn-main {
      background-color: #3498db;
      color: white;
    }
    .btn-commande {
      background-color: #2ecc71;
      color: white;
    }
    .btn-main:hover { background-color: #2980b9; }
    .btn-commande:hover { background-color: #27ae60; }
    .qte-input {
      width: 60px;
      text-align: center;
      border-radius: 4px;
      border: none;
      padding: 4px;
    }
    img {
      border-radius: 5px;
    }
  </style>
</head>
<body>
<div class="container">
  <h1>🛒 Votre Panier</h1>
  <?php if (empty($liste)): ?>
    <p class="text-center">Votre panier est vide.</p>
  <?php else: ?>
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
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
        <?php foreach ($liste as $id => $p): ?>
        <tr>
          <td><?= htmlspecialchars($p['nom']) ?></td>
          <td><img src="images/<?= htmlspecialchars($p['image_url']) ?>" width="65"></td>
          <td>
            <form method="post">
              <input type="hidden" name="id_produit" value="<?= $id ?>">
              <input type="number" name="quantite" min="1" value="<?= $p['quantite'] ?>" class="qte-input">
              <input type="hidden" name="maj_quantite" value="1">
              <button type="submit" class="btn-update">📝</button>
            </form>
          </td>
          <td><?= number_format($p['prix'], 0, ',', ' ') ?> FCFA</td>
          <td><?= number_format($p['sous_total'], 0, ',', ' ') ?> FCFA</td>
          <td><a href="?action=supprimer&id=<?= $id ?>" class="btn-delete">❌</a></td>
        </tr>
        <?php endforeach; ?>
        <tr class="table-info">
          <td colspan="4" class="text-end"><strong>Total :</strong></td>
          <td colspan="2"><strong><?= number_format($total, 0, ',', ' ') ?> FCFA</strong></td>
        </tr>
      </tbody>
    </table>
    <div class="d-flex justify-content-end gap-3">
      <a href="indexboutique.php" class="btn-main">🛍️ Continuer les achats</a>
      <a href="checkoutB.php" class="btn-commande">🧾 Passer à la commande</a>
    </div>
  <?php endif; ?>
</div>
</body>
</html>

<?php
session_start();

// Initialisation du panier
$panier = $_SESSION['panier'] ?? [];

// Calcul du total
$total = 0;
foreach ($panier as $item) {
    $total += $item['quantite'] * $item['prix'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mon Panier - BHELMAR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #fdf6e3;
      font-family: 'Segoe UI', sans-serif;
    }
    .navbar {
      background: #d35400;
      padding: 15px 30px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .navbar-brand {
      display: flex;
      align-items: center;
      color: white;
      font-weight: bold;
      font-size: 22px;
    }
    .navbar-brand img {
      height: 40px;
      margin-right: 10px;
    }
    .btn-retour {
      margin-top: 20px;
      display: inline-block;
      padding: 10px 20px;
      background-color: #d35400;
      color: white;
      border-radius: 5px;
      text-decoration: none;
    }
    .btn-retour:hover {
      background-color: #b84300;
    }
    .table th, .table td {
      vertical-align: middle;
    }
    .total {
      font-size: 20px;
      font-weight: bold;
      text-align: right;
    }
    .btn-commande {
      background: #27ae60;
      color: white;
      font-weight: 600;
      border: none;
    }
    .btn-commande:hover {
      background: #1e8449;
    }
  </style>
</head>
<body>

<nav class="navbar">
  <a class="navbar-brand" href="#">
    <img src="images.h/logobon.jpg" alt="Logo BHELMAR">
    BHELMAR - Mon Panier
  </a>
</nav>

<div class="container mt-5">
  <h2 class="mb-4 text-center">🛒 Mon panier</h2>

  <?php if (!empty($panier)): ?>
    <table class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>Image</th>
          <th>Nom</th>
          <th>Prix Unitaire</th>
          <th>Quantité</th>
          <th>Sous-total</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($panier as $item): ?>
        <tr>
          <td><img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['nom']) ?>" width="70" height="70"></td>
          <td><?= htmlspecialchars($item['nom']) ?></td>
          <td><?= number_format($item['prix'], 0) ?> FCFA</td>
          <td><?= $item['quantite'] ?></td>
          <td><strong><?= number_format($item['prix'] * $item['quantite'], 0) ?> FCFA</strong></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <p class="total">Total : <?= number_format($total, 0) ?> FCFA</p>

    <div class="d-flex justify-content-end mt-4">
      <a href="commande.php" class="btn btn-commande me-3">Valider la commande</a>
    </div>
  <?php else: ?>
    <div class="alert alert-warning text-center">
      Votre panier est vide pour le moment.
    </div>
  <?php endif; ?>

  <div class="text-center mt-4">
    <a href="categories.php" class="btn-retour">← Retour au catalogue</a>
  </div>
</div>

</body>
</html>

<?php
require_once 'config.php';
session_start();

$stmt = $pdo->prepare("SELECT * FROM produits WHERE id_categorie = ?");
$stmt->execute([3]); // Pharmacie
$produits = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>BHELMAR - Pharmacie</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8faff;
      font-family: 'Segoe UI', sans-serif;
    }
    .navbar {
      background: #0984e3;
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
    .btn-catalogue, .btn-panier {
      color: #fff;
      background: #2d3436;
      border: none;
      padding: 6px 15px;
      border-radius: 5px;
      text-decoration: none;
      margin-left: 10px;
    }
    .btn-catalogue:hover, .btn-panier:hover {
      background: #636e72;
    }
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.08);
      transition: transform 0.3s ease;
      position: relative;
    }
    .card:hover {
      transform: scale(1.03);
    }
    .card-img-top {
      height: 200px;
      object-fit: cover;
      border-top-left-radius: 12px;
      border-top-right-radius: 12px;
    }
    .btn-custom {
      background-color: #0984e3;
      color: white;
      font-weight: 600;
    }
    .btn-custom:hover {
      background-color: #0767c5;
    }
    .badge-promo {
      position: absolute;
      top: 15px;
      right: 15px;
      background: #ffeaa7;
      color: #2d3436;
      padding: 5px 10px;
      border-radius: 8px;
      font-weight: bold;
      font-size: 13px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
  </style>
</head>
<body>

<nav class="navbar">
  <a class="navbar-brand" href="#">
    <img src="images.h/logobon.jpg" alt="Logo BHELMAR">
    BHELMAR - Tous à domicile
  </a>
  <a href="categories.php" class="btn-catalogue">&larr; Catalogue</a>
  <a href="panierB.php" class="btn-panier">🛒 Mon panier</a>
</nav>

<div class="container mt-4">
  <h2 class="text-center mb-4">Produits Pharmaceutiques</h2>
  <div class="row" id="liste-produits">
    <?php if (!empty($produits)): ?>
      <?php foreach ($produits as $produit): ?>
        <div class="col-md-4 mb-4">
          <div class="card">
            <?php if ($produit['est_promo'] == 1): ?>
              <div class="badge-promo">Promo</div>
            <?php endif; ?>
            <img src="<?= htmlspecialchars($produit['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($produit['nom']) ?>">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($produit['nom']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($produit['description']) ?></p>
              <p><strong><?= number_format($produit['prix'], 0) ?> FCFA</strong> / <?= $produit['unite'] ?></p>
              <div class="d-flex justify-content-between">
                <a href="detailB.php?id=<?= $produit['id_produit'] ?>" class="btn btn-outline-secondary btn-sm">Voir détail</a>
                <button class="btn btn-custom btn-sm ajouter-panier" 
                        data-id="<?= $produit['id_produit'] ?>"
                        data-nom="<?= htmlspecialchars($produit['nom']) ?>"
                        data-prix="<?= $produit['prix'] ?>">
                        Ajouter au panier
                </button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12 text-center">
        <p class="text-danger">Aucun produit disponible en pharmacie pour l'instant.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<script>
  document.querySelectorAll('.ajouter-panier').forEach(button => {
    button.addEventListener('click', () => {
      const id = button.dataset.id;
      const nom = button.dataset.nom;
      const prix = button.dataset.prix;

      const formData = new FormData();
      formData.append('produit_id', id);
      formData.append('produit_nom', nom);
      formData.append('produit_prix', prix);
      formData.append('quantite', 1);

      fetch('panierB.php', {
        method: 'POST',
        body: formData
      }).then(res => res.text()).then(data => {
        alert("Produit ajouté au panier avec succès !");
      }).catch(err => {
        alert("Erreur lors de l'ajout au panier.");
      });
    });
  });
</script>

</body>
</html>

<?php
session_start();
require_once 'config.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    $_SESSION['utilisateur_id'] = 0; // ou une valeur par défaut
}


// Vérifier que l'ID est fourni
if (!isset($_GET['id'])) {
    die("Aucun produit sélectionné.");
}

$id = (int) $_GET['id'];

// Récupérer les détails du produit
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
  <title>Détail du produit</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
    .container { max-width: 800px; margin-top: 50px; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    h2 { color: #2c3e50; }
    .btn-ajouter { background-color: #28a745; color: white; font-weight: bold; }
    .btn-ajouter:hover { background-color: #218838; }
  </style>
</head>
<body>

<div class="container">
  <h2 class="mb-4"><?= htmlspecialchars($produit['nom']) ?></h2>
  <p><strong>Prix :</strong> <?= number_format($produit['prix'], 0) ?> FCFA</p>
  <p><strong>Description :</strong> <?= htmlspecialchars($produit['description']) ?></p>

  <form id="ajoutPanierForm">
    <input type="hidden" name="produit_id" value="<?= $produit['id_produit'] ?>">
    <input type="hidden" name="produit_nom" value="<?= $produit['nom'] ?>">
    <input type="hidden" name="produit_prix" value="<?= $produit['prix'] ?>">

    <div class="mb-3">
      <label for="quantite" class="form-label">Quantité</label>
      <input type="number" class="form-control" name="quantite" id="quantite" value="1" min="1" required>
    </div>

    <button type="submit" class="btn btn-ajouter w-100">🛒 Ajouter au panier</button>
  </form>

  <div id="panier-message" class="text-success mt-3 fw-bold" style="display:none;"></div>
  <a href="panierB.php" class="btn btn-outline-primary mt-3 w-100">🧾 Voir mon panier</a>
</div>

<!-- Script AJAX -->
<script>
  document.getElementById('ajoutPanierForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const message = document.getElementById('panier-message');
    const data = new FormData(form);

    fetch('ajout_panier.php', {
      method: 'POST',
      body: data
    })
    .then(res => res.text())
    .then(result => {
      message.innerHTML = '✅ Produit ajouté au panier !';
      message.style.display = 'block';
    })
    .catch(() => {
      message.innerHTML = '❌ Une erreur est survenue.';
      message.style.display = 'block';
    });
  });
</script>

</body>
</html>

<?php
include('connexion.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT p.*, b.nom AS boutique_nom FROM produits p JOIN boutiques b ON p.boutique_id = b.id WHERE p.id_produit = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    echo "Produit introuvable.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail produit - <?= htmlspecialchars($produit['nom']) ?></title>
    <link rel="stylesheet" href="inscription.css">
</head>
<body>
<div class="container">
    <h2><?= htmlspecialchars($produit['nom']) ?></h2>
    <p><b>Boutique :</b> <?= htmlspecialchars($produit['boutique_nom']) ?></p>
    <img src="images/<?= htmlspecialchars($produit['image_url']) ?>" style="width: 100%; max-width: 400px; border-radius: 10px; margin-bottom: 15px;">
    <p><?= htmlspecialchars($produit['description']) ?></p>
    <p><b>Prix :</b> <?= htmlspecialchars($produit['prix']) ?> FCFA</p>
    <p><b>Stock :</b> <?= htmlspecialchars($produit['stock']) ?></p>
    <a href="indexboutique.php" class="btn-detail">Retour au catalogue</a>
</div>
</body>
</html>
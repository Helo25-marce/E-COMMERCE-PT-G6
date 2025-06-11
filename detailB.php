<?php
session_start();
include('connexion.php');

// Gérer l'ajout au panier depuis la page détail
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produit'])) {
    $id = $_POST['id_produit'];
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $image = $_POST['image_url'];
    $quantite = intval($_POST['quantite']);
    if (!isset($_SESSION['panier'])) $_SESSION['panier'] = [];
    if (isset($_SESSION['panier'][$id])) $_SESSION['panier'][$id]['quantite'] += $quantite;
    else $_SESSION['panier'][$id] = [
        'nom' => $nom,
        'prix' => $prix,
        'image' => $image,
        'quantite' => $quantite
    ];
    header("Location: panierB.php");
    exit;
}

// Affichage du produit
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $conn->prepare("SELECT * FROM produits WHERE id = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$produit) { echo "Produit introuvable"; exit; }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Détail - <?= htmlspecialchars($produit['nom']) ?></title>
    <link rel="stylesheet" href="StyleB.css">
</head>
<body>
<div class="detail-container">
    <img src="images/<?= htmlspecialchars($produit['image_url']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>" class="img-detail">
    <div>
        <h2><?= htmlspecialchars($produit['nom']) ?></h2>
        <p><?= htmlspecialchars($produit['description']) ?></p>
        <b><?= htmlspecialchars($produit['prix']) ?> FCFA</b>
        <form method="post" action="">
            <input type="hidden" name="id_produit" value="<?= $produit['id'] ?>">
            <input type="hidden" name="nom" value="<?= htmlspecialchars($produit['nom']) ?>">
            <input type="hidden" name="prix" value="<?= $produit['prix'] ?>">
            <input type="hidden" name="image_url" value="<?= htmlspecialchars($produit['image_url']) ?>">
            <input type="number" name="quantite" value="1" min="1" style="width:60px;">
            <button type="submit" class="ajouter">Ajouter au panier</button>
        </form>
        <a href="panierB.php" class="btn">Voir le panier</a>
        <a href="indexboutique.php" class="btn" style="background:#eee;color:#222;">Retour au catalogue</a>
    </div>
</div>
</body>
</html>



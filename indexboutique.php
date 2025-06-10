<?php
session_start();
include('connexion.php');

// Boutiques
$boutiques = $conn->query("SELECT id, nom, logo FROM boutiques")->fetchAll(PDO::FETCH_ASSOC);
$boutique_id = isset($_GET['boutique_id']) ? intval($_GET['boutique_id']) : 0;
$tri = $_GET['tri'] ?? '';
// Sélection de la boutique choisie
$boutique_selected = null;
foreach ($boutiques as $b) {
    if ($b['id'] == $boutique_id) $boutique_selected = $b;
}

// Produits filtrés
$query = "SELECT * FROM produits" . ($boutique_id ? " WHERE boutique_id = $boutique_id" : "");
if ($tri == 'nouveaux') $query .= " ORDER BY date_ajout DESC";
elseif ($tri == 'promo') $query .= ($boutique_id ? " AND" : " WHERE") . " est_promo = 1";
elseif ($tri == 'prix') $query .= " ORDER BY prix ASC";
$produits = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Ajout au panier (POST)
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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catalogue des Boutiques</title>
    <link rel="stylesheet" href="StyleB.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="container">

    <!-- Barre de filtre -->
    <form method="get" class="filter-bar">
        <label>Boutique :</label>
        <select name="boutique_id" onchange="this.form.submit()">
            <option value="0">Toutes les boutiques</option>
            <?php foreach ($boutiques as $b): ?>
                <option value="<?= $b['id'] ?>" <?= ($boutique_id == $b['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($b['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label>Trier par :</label>
        <select name="tri" onchange="this.form.submit()">
            <option value="">---</option>
            <option value="prix" <?= ($tri == 'prix') ? 'selected' : '' ?>>Prix croissant</option>
            <option value="nouveaux" <?= ($tri == 'nouveaux') ? 'selected' : '' ?>>Nouveaux</option>
            <option value="promo" <?= ($tri == 'promo') ? 'selected' : '' ?>>Promotions</option>
        </select>
        <a href="panierB.php" class="btn" style="margin-left:auto;">Voir le panier</a>
    </form>

    <?php if ($boutique_selected): ?>
        <div class="header-boutique">
            <img src="image/<?= htmlspecialchars($boutique_selected['logo']) ?>" alt="logo" class="logo">
            <span class="nom-boutique"><?= htmlspecialchars($boutique_selected['nom']) ?></span>
        </div>
    <?php endif; ?>

    <!-- Catalogue produits centré -->
    <div class="catalogue" style="justify-content:center;">
        <?php if (empty($produits)): ?>
            <p>Aucun produit trouvé.</p>
        <?php endif; ?>
        <?php foreach ($produits as $produit): ?>
            <div class="produit">
                <?php if ($produit['est_promo']) echo "<span class='badge promo'>PROMO</span>"; ?>
                <?php if (strtotime($produit['date_ajout']) > strtotime('-30 days')) echo "<span class='badge new'>NOUVEAU</span>"; ?>
                <a href="detailB.php?id=<?= $produit['id'] ?>">
                    <img src="images/<?= htmlspecialchars($produit['image_url']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>" style="aspect-ratio:1.1/1;border-radius:18px;object-fit:cover;">
                </a>
                <h4><?= htmlspecialchars($produit['nom']) ?></h4>
                <p><?= htmlspecialchars($produit['description']) ?></p>
                <b><?= htmlspecialchars($produit['prix']) ?> FCFA</b>
                <form method="post" style="margin-top:10px;">
                    <input type="hidden" name="id_produit" value="<?= $produit['id'] ?>">
                    <input type="hidden" name="nom" value="<?= htmlspecialchars($produit['nom']) ?>">
                    <input type="hidden" name="prix" value="<?= $produit['prix'] ?>">
                    <input type="hidden" name="image_url" value="<?= htmlspecialchars($produit['image_url']) ?>">
                    <input type="number" name="quantite" value="1" min="1" style="width:50px;">
                    <button type="submit" class="ajouter">Ajouter au panier</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
<?php
session_start();
require_once("config.php"); // Connexion PDO via $conn

// 1. Récupération des boutiques
$boutiques = [];
$stmt = $conn->query("SELECT id, nom, logo FROM boutiques");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $boutiques[] = $row;
}

// 2. Boutique sélectionnée
$boutique_id = isset($_GET['boutique_id']) ? intval($_GET['boutique_id']) : 0;
$boutique_selected = null;
foreach ($boutiques as $b) {
    if ($b['id'] == $boutique_id) {
        $boutique_selected = $b;
        break;
    }
}

// 3. Tri des produits
$tri = $_GET['tri'] ?? 'recent';
$orderBy = match($tri) {
    'prix_asc' => 'prix ASC',
    'prix_desc' => 'prix DESC',
    default => 'date_ajout DESC'
};

// 4. Récupération des produits de la boutique
$produits = [];
if ($boutique_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM produits WHERE boutique_id = :id ORDER BY $orderBy");
    $stmt->execute(['id' => $boutique_id]);
} else {
    $stmt = $conn->query("SELECT * FROM produits ORDER BY $orderBy");
}
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catalogue des Boutiques</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="StyleB.css">
</head>
<body>
<div class="container">
    <!-- Filtrage des boutiques -->
    <form method="get">
        <label><b>Choisir une boutique :</b></label>
        <select name="boutique_id" onchange="this.form.submit()">
            <option value="0">-- Toutes les boutiques --</option>
            <?php foreach ($boutiques as $b): ?>
                <option value="<?= $b['id'] ?>" <?= $boutique_id == $b['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($b['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label><b>Trier par :</b></label>
        <select name="tri" onchange="this.form.submit()">
            <option value="recent" <?= $tri === 'recent' ? 'selected' : '' ?>>Nouveaux</option>
            <option value="prix_asc" <?= $tri === 'prix_asc' ? 'selected' : '' ?>>Prix croissant</option>
            <option value="prix_desc" <?= $tri === 'prix_desc' ? 'selected' : '' ?>>Prix décroissant</option>
        </select>
    </form>

    <!-- Logo boutique -->
    <?php if ($boutique_selected): ?>
        <div class="logo-boutique">
            <img src="image/<?= htmlspecialchars($boutique_selected['logo']) ?>" alt="<?= htmlspecialchars($boutique_selected['nom']) ?>">
            <h2><?= htmlspecialchars($boutique_selected['nom']) ?></h2>
        </div>
    <?php endif; ?>

    <!-- Affichage des produits -->
    <div class="catalogue-produits">
        <?php if (empty($produits)): ?>
            <p>Aucun produit trouvé.</p>
        <?php endif; ?>
        <?php foreach ($produits as $produit): ?>
            <div class="card-produit">
                <span class="badge"><?= $produit['est_promo'] ? 'Promo' : 'Nouveau' ?></span>
                <img src="images/<?= htmlspecialchars($produit['image_url']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>">
                <h4><?= htmlspecialchars($produit['nom']) ?></h4>
                <p><?= htmlspecialchars($produit['description']) ?></p>
                <b><?= number_format($produit['prix'], 0, ',', ' ') ?> FCFA</b>

                <?php
                    $stock = intval($produit['stock']);
                    if ($stock <= 0) {
                        $classe = "rupture"; $texte = "En rupture de stock";
                    } elseif ($stock <= 5) {
                        $classe = "limite"; $texte = "Stock limité";
                    } else {
                        $classe = "en-stock"; $texte = "En stock";
                    }
                ?>
                <div class="stock <?= $classe ?>"><?= $texte ?></div>

                <form method="POST" action="panier.php">
                    <input type="hidden" name="produit" value="<?= $produit['nom'] ?>">
                    <input type="hidden" name="prix" value="<?= $produit['prix'] ?>">
                    <input type="number" name="quantite" value="1" min="1">
                    <button type="submit">Ajouter au panier</button>
                </form>

                <a href="detailB.php?id=<?= $produit['id_produit'] ?>" class="btn-detail">Voir détails</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>

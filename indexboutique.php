<?php
include('connexion.php');

// 1. Récupérer toutes les boutiques
$boutiques = $conn->query("SELECT id, nom, logo FROM boutiques")->fetchAll(PDO::FETCH_ASSOC);

// 2. Quelle boutique sélectionnée ?
$boutique_id = isset($_GET['boutique_id']) ? intval($_GET['boutique_id']) : 0;
$boutique_selected = null;
if ($boutique_id > 0) {
    foreach ($boutiques as $b) {
        if ($b['id'] == $boutique_id) $boutique_selected = $b;
    }
}

// 3. Gestion du tri
$tri = isset($_GET['tri']) ? $_GET['tri'] : 'recent';
$orderBy = "date_ajout DESC";
if ($tri === 'prix_asc') $orderBy = "prix ASC";
elseif ($tri === 'prix_desc') $orderBy = "prix DESC";

// 4. Récupérer les produits
if ($boutique_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM produits WHERE boutique_id = ? ORDER BY $orderBy");
    $stmt->execute([$boutique_id]);
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
    <link rel="stylesheet" href="Style.css">
</head>
<body>
<div class="container">
    <!-- Sélecteur de boutique + tri -->
    <form method="get">
        <label for="boutique_id"><b>Choisir une boutique :</b></label>
        <select name="boutique_id" id="boutique_id" onchange="this.form.submit()">
            <option value="0">-- Toutes les boutiques --</option>
            <?php foreach ($boutiques as $b): ?>
                <option value="<?= $b['id'] ?>" <?= ($boutique_id == $b['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($b['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="tri"><b>Trier par :</b></label>
        <select name="tri" id="tri" onchange="this.form.submit()">
            <option value="recent" <?= ($tri === 'recent') ? 'selected' : '' ?>>Nouveaux</option>
            <option value="prix_asc" <?= ($tri === 'prix_asc') ? 'selected' : '' ?>>Prix croissant</option>
            <option value="prix_desc" <?= ($tri === 'prix_desc') ? 'selected' : '' ?>>Prix décroissant</option>
        </select>
    </form>

    <!-- Logo boutique -->
    <?php if ($boutique_selected): ?>
        <div class="logo-boutique">
            <img src="image/<?= htmlspecialchars($boutique_selected['logo']) ?>" alt="<?= htmlspecialchars($boutique_selected['nom']) ?>">
            <h2><?= htmlspecialchars($boutique_selected['nom']) ?></h2>
        </div>
    <?php endif; ?>

    <!-- Catalogue -->
    <div class="catalogue-produits">
        <?php if (empty($produits)): ?>
            <p>Aucun produit pour cette boutique.</p>
        <?php endif; ?>
        <?php foreach ($produits as $produit): ?>
            <div class="card-produit">
                <!-- Badge dynamique -->
                <span class="badge"><?= $produit['est_promo'] ? 'Promo' : 'Nouveau' ?></span>

                <!-- Image produit -->
                <img src="images/<?= htmlspecialchars($produit['image_url']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>">

                <!-- Informations -->
                <h4><?= htmlspecialchars($produit['nom']) ?></h4>
                <p><?= htmlspecialchars($produit['description']) ?></p>
                <b><?= htmlspecialchars($produit['prix']) ?> FCFA</b>

                <!-- Stock -->
                <?php
                    $stock = intval($produit['stock']);
                    if ($stock <= 0) {
                        $classe = "rupture";
                        $texte = "En rupture de stock";
                    } elseif ($stock <= 5) {
                        $classe = "limite";
                        $texte = "Stock limité";
                    } else {
                        $classe = "en-stock";
                        $texte = "En stock";
                    }
                ?>
                <div class="stock <?= $classe ?>"><?= $texte ?></div>

                <!-- Lien détail -->
                <a href="detail.php?id=<?= $produit['id_produit'] ?>" class="btn-detail">Voir détails</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
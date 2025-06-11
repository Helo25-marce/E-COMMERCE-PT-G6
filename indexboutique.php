// === indexboutique.php ===
<?php
require_once("config.php");

// 1. Récupérer les boutiques
$boutiques = [];
$result = $conn->query("SELECT id, nom, logo FROM boutiques");
while ($row = $result->fetch_assoc()) {
    $boutiques[] = $row;
}

// 2. Identifier la boutique sélectionnée
$boutique_id = isset($_GET['boutique_id']) ? intval($_GET['boutique_id']) : 0;
$boutique_selected = null;
foreach ($boutiques as $b) {
    if ($b['id'] == $boutique_id) {
        $boutique_selected = $b;
        break;
    }
}

// 3. Gérer le tri
$tri = isset($_GET['tri']) ? $_GET['tri'] : 'recent';
$orderBy = "date_ajout DESC";
if ($tri === 'prix_asc') $orderBy = "prix ASC";
elseif ($tri === 'prix_desc') $orderBy = "prix DESC";

// 4. Récupérer les produits
$produits = [];
if ($boutique_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM produits WHERE boutique_id = ? ORDER BY $orderBy");
    $stmt->bind_param("i", $boutique_id);
    $stmt->execute();
    $res = $stmt->get_result();
} else {
    $res = $conn->query("SELECT * FROM produits ORDER BY $orderBy");
}

while ($row = $res->fetch_assoc()) {
    $produits[] = $row;
}
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
    <form method="get" class="filter-bar">
        <label><b>Choisir une boutique :</b></label>
        <select name="boutique_id" onchange="this.form.submit()">
            <option value="0">-- Toutes les boutiques --</option>
            <?php foreach ($boutiques as $b): ?>
                <option value="<?= $b['id'] ?>" <?= ($boutique_id == $b['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($b['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label><b>Trier par :</b></label>
        <select name="tri" onchange="this.form.submit()">
            <option value="recent" <?= ($tri === 'recent') ? 'selected' : '' ?>>Nouveaux</option>
            <option value="prix_asc" <?= ($tri === 'prix_asc') ? 'selected' : '' ?>>Prix croissant</option>
            <option value="prix_desc" <?= ($tri === 'prix_desc') ? 'selected' : '' ?>>Prix décroissant</option>
        </select>
    </form>

    <?php if ($boutique_selected): ?>
        <div class="header-boutique">
            <img src="image/<?= htmlspecialchars($boutique_selected['logo']) ?>" alt="logo" class="logo">
            <div class="nom-boutique"><?= htmlspecialchars($boutique_selected['nom']) ?></div>
        </div>
    <?php endif; ?>

    <div class="catalogue">
        <?php if (empty($produits)): ?>
            <p>Aucun produit trouvé.</p>
        <?php endif; ?>
        <?php foreach ($produits as $produit): ?>
            <div class="produit">
                <span class="badge <?= $produit['est_promo'] ? 'promo' : 'new' ?>">
                    <?= $produit['est_promo'] ? 'Promo' : 'Nouveau' ?>
                </span>
                <img src="images/<?= htmlspecialchars($produit['image_url']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>">
                <h4><?= htmlspecialchars($produit['nom']) ?></h4>
                <p><?= htmlspecialchars($produit['description']) ?></p>
                <b><?= htmlspecialchars($produit['prix']) ?> FCFA</b>

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

                <form method="POST" action="panierB.php">
                    <input type="hidden" name="produit" value="<?= $produit['nom'] ?>">
                    <input type="hidden" name="prix" value="<?= $produit['prix'] ?>">
                    <input type="number" name="quantite" value="1" min="1">
                    <button type="submit" class="ajouter">Ajouter au panier</button>
                </form>

                <a href="detailB.php?id=<?= $produit['id_produit'] ?>" class="btn-detail">Voir détails</a>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="text-align:center; margin-top: 30px;">
        <a href="welcome.php" class="btn">🏠 Retour à l'accueil</a>
    </div>
</div>
</body>
</html>


// === panierB.php ===
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
    <link rel="stylesheet" href="StyleB.css">
</head>
<body>
<div class="container">
    <h1>🛒 Votre Panier</h1>
    <?php if (empty($liste)): ?>
        <p>Votre panier est vide.</p>
    <?php else: ?>
        <table>
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
            <tr class="total-row">
                <td colspan="4">Total :</td>
                <td colspan="2" id="total_global"><?= number_format($total, 0, ',', ' ') ?> FCFA</td>
            </tr>
            </tbody>
        </table>
        <div style="display: flex; justify-content: flex-end; gap: 10px;">
            <a href="indexboutique.php" class="btn-main">🛍️ Continuer les achats</a>
            <a href="checkoutB.php" class="btn-commande">🧾 Passer à la commande</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>

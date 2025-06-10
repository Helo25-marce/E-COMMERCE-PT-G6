<?php
session_start();
include('connexion.php');

// Supprimer un produit du panier
if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
    $id = $_GET['id'];
    unset($_SESSION['panier'][$id]);
    header('Location: panierB.php');
    exit;
}

// Mettre à jour la quantité
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['maj_quantite'])) {
    $id = $_POST['id_produit'];
    $qte = max(1, intval($_POST['quantite']));
    if (isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id]['quantite'] = $qte;
    }
    header('Location: panierB.php');
    exit;
}

// Récupérer les infos produits depuis la BDD
$total = 0;
$liste = [];
if (!empty($_SESSION['panier'])) {
    $ids = array_keys($_SESSION['panier']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    // ⚠️ Ici on utilise id (et non id_produit)
    $stmt = $conn->prepare("SELECT * FROM produits WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['quantite'] = $_SESSION['panier'][$row['id']]['quantite'];
        $row['sous_total'] = $row['prix'] * $row['quantite'];
        $total += $row['sous_total'];
        $liste[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Votre Panier</title>
    <link rel="stylesheet" href="StyleB.css">
    <style>
    .container { max-width: 950px; margin: 40px auto; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
    th, td { padding: 13px 8px; text-align: center; }
    th { background: #f3f6fb; font-size: 17px; }
    tr:nth-child(even) { background: #f8fbff; }
    td img { width: 65px; height: 65px; border-radius: 10px; object-fit: cover; box-shadow: 0 2px 10px #dde; }
    .qte-input { width: 55px; padding: 6px 4px; text-align: center; border-radius: 7px; border: 1px solid #ddd; }
    .btn-update, .btn-delete, .btn-main, .btn-commande {
        padding: 8px 15px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; transition: 0.2s;
    }
    .btn-update { background: #0080ff; color: #fff; }
    .btn-update:hover { background: #0058b3; }
    .btn-delete { background: #ffe5e5; color: #d11b1b; border: 1px solid #ffbbbb;}
    .btn-delete:hover { background: #ffd6d6; }
    .btn-commande { background: linear-gradient(90deg,#44c5c7,#0080ff 80%); color: #fff; margin-left: 10px;}
    .btn-main { background: #eee; color: #355; border:1px solid #cbe;}
    .btn-main:hover { background: #d9f2fa;}
    .total-row td { font-size: 18px; font-weight: bold; color: #0080ff; }
    </style>
</head>
<body>
<div class="container">
    <h1>🛒 Votre Panier</h1>
    <?php if (empty($liste)): ?>
        <p>Votre panier est vide.</p>
    <?php else: ?>
        <form method="post" action="panierB.php">
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Photo</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Sous-total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($liste as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nom']) ?></td>
                    <td><img src="images/<?= htmlspecialchars($p['image_url']) ?>" alt="<?= htmlspecialchars($p['nom']) ?>"></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id_produit" value="<?= $p['id'] ?>">
                            <input class="qte-input" type="number" name="quantite" min="1" value="<?= $p['quantite'] ?>" onchange="this.form.submit()">
                            <input type="hidden" name="maj_quantite" value="1">
                        </form>
                    </td>
                    <td><?= number_format($p['prix'], 0, ',', ' ') ?> FCFA</td>
                    <td><?= number_format($p['sous_total'], 0, ',', ' ') ?> FCFA</td>
                    <td>
                        <a href="?action=supprimer&id=<?= $p['id'] ?>" class="btn-delete">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="4">TOTAL</td>
                <td colspan="2"><?= number_format($total, 0, ',', ' ') ?> FCFA</td>
            </tr>
            </tbody>
        </table>
        </form>
        <div style="display:flex;gap:20px;justify-content:flex-end;">
            <a href="indexboutique.php" class="btn-main">Retourner au menu principal</a>
            <a href="checkoutB.php" class="btn-commande">Passer à la commande</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>

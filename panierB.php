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

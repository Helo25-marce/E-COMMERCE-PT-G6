<?php
session_start();
include('connexion.php');

// Vérifier si le panier existe
if (empty($_SESSION['panier'])) {
    header('Location: panierB.php');
    exit;
}

$total = 0;
foreach ($_SESSION['panier'] as $item) {
    $total += $item['prix'] * $item['quantite'];
}

// Lorsqu'on clique sur GOOD!
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['valider'])) {
    // Tu pourrais enregistrer la commande en base ici
    header('Location: facture.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Valider ma commande</title>
    <link rel="stylesheet" href="Style.css">
    <style>
    .checkout-container {
        max-width: 600px;
        margin: 40px auto;
        padding: 30px 32px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 6px 24px rgba(44,60,90,0.08);
        text-align: center;
    }
    .checkout-btns {
        display: flex;
        gap: 20px;
        justify-content: center;
        margin-top: 30px;
    }
    .checkout-btns a, .checkout-btns button {
        padding: 12px 28px;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
        transition: 0.2s;
    }
    .revoir {
        background: #ffe5d2;
        color: #b75b00;
        border: 1px solid #ffb366;
    }
    .revoir:hover { background: #ffb366; color: white;}
    .good {
        background: linear-gradient(90deg,#44c5c7,#0080ff 80%);
        color: #fff;
        box-shadow: 0 2px 8px #44c5c73a;
        border: none;
    }
    .good:hover { background: linear-gradient(90deg,#0080ff,#44c5c7 80%);}
    </style>
</head>
<body>
<div class="checkout-container">
    <h2>Récapitulatif de votre commande</h2>
    <table style="margin:auto;width:90%;background:#f5f8ff;border-radius:10px;">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Sous-total</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($_SESSION['panier'] as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['nom']) ?></td>
                <td><?= $item['quantite'] ?></td>
                <td><?= number_format($item['prix'] * $item['quantite'], 0, ',', ' ') ?> FCFA</td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td><strong><?= number_format($total, 0, ',', ' ') ?> FCFA</strong></td>
        </tr>
        </tbody>
    </table>
    <div class="checkout-btns">
        <a href="panierB.php" class="revoir">Revoir mon panier</a>
        <form method="post" style="display:inline;">
            <button type="submit" name="valider" class="good">GOOD!</button>
        </form>
    </div>
</div>
</body>
</html>

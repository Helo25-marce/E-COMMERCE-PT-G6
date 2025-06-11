<?php
session_start();
include('connexion.php');

// Vérifier si le panier existe
if (empty($_SESSION['panier'])) {
    header('Location: panier.php');
    exit;
}

$total = 0;
foreach ($_SESSION['panier'] as $item) {
    $total += $item['prix'] * $item['quantite'];
}

// Lorsqu'on clique sur GOOD!
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['valider'])) {
    // Enregistrement de la commande (à personnaliser selon ta BDD)
    $date = date("Y-m-d H:i:s");
    $stmt = $conn->prepare("INSERT INTO commandes (date_commande, total) VALUES (?, ?)");
    $stmt->bind_param("sd", $date, $total);
    $stmt->execute();
    $id_commande = $stmt->insert_id;

    // Enregistrement des articles
    foreach ($_SESSION['panier'] as $item) {
        $stmt = $conn->prepare("INSERT INTO commande_produit (id_commande, nom_produit, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isid", $id_commande, $item['nom'], $item['quantite'], $item['prix']);
        $stmt->execute();
    }

    $_SESSION['id_commande'] = $id_commande;
    header('Location: facture.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Valider ma commande</title>
    <link rel="stylesheet" href="PT.css">
    <style>
    .checkout-container {
        max-width: 700px;
        margin: 40px auto;
        padding: 30px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 6px 24px rgba(44,60,90,0.08);
        text-align: center;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
    }
    .checkout-btns {
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
        margin: 0 10px;
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
    }
    .good:hover { background: linear-gradient(90deg,#0080ff,#44c5c7 80%); }
    </style>
</head>
<body>
<div class="checkout-container">
    <h2>Récapitulatif de votre commande</h2>
    <table>
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
        <a href="panier.php" class="revoir">← Revoir mon panier</a>
        <form method="post" style="display:inline;">
            <button type="submit" name="valider" class="good">GOOD! Valider</button>
        </form>
    </div>
</div>
</body>
</html>

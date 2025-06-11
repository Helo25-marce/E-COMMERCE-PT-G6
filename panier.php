<?php
session_start();

// Initialiser le panier si vide
if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    echo "<h2 style='text-align:center;'>Votre panier est vide.</h2>";
    echo "<p style='text-align:center;'><a href='indexboutique.php'>← Retour aux boutiques</a></p>";
    exit;
}

// Calcul total
$total = 0;
foreach ($_SESSION['panier'] as $item) {
    $total += $item['prix'] * $item['quantite'];
}

// Supprimer un produit
if (isset($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    unset($_SESSION['panier'][$id]);
    header("Location: panier.php");
    exit;
}

// Vider le panier
if (isset($_GET['vider'])) {
    unset($_SESSION['panier']);
    header("Location: panier.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Panier</title>
    <link rel="stylesheet" href="PT.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
        }
        .container {
            width: 90%;
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 16px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        img {
            width: 60px;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
        .actions {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .btn {
            padding: 10px 20px;
            background: #00b894;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background: #019875;
        }
        .remove-btn {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }
        .remove-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Votre Panier</h2>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Sous-total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['panier'] as $id => $item): ?>
                <tr>
                    <td><img src="images/<?= htmlspecialchars($item['image']) ?>" alt="Produit"></td>
                    <td><?= htmlspecialchars($item['nom']) ?></td>
                    <td><?= $item['quantite'] ?></td>
                    <td><?= number_format($item['prix'], 0, ',', ' ') ?> FCFA</td>
                    <td><?= number_format($item['prix'] * $item['quantite'], 0, ',', ' ') ?> FCFA</td>
                    <td><a href="?supprimer=<?= $id ?>" class="remove-btn">Supprimer</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total">Total : <?= number_format($total, 0, ',', ' ') ?> FCFA</div>

        <div class="actions">
            <a href="indexboutique.php" class="btn">← Continuer mes achats</a>
            <a href="checkoutB.php" class="btn">Valider la commande</a>
            <a href="?vider=1" class="btn" style="background:#ff7675;">Vider le panier</a>
        </div>
    </div>
</body>
</html>

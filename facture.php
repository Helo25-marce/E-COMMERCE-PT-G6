<?php
session_start();
require_once('config.php');

// Vérifie si une commande a été enregistrée
if (!isset($_SESSION['id_commande'])) {
    header('Location: index.php');
    exit;
}

$id_commande = $_SESSION['id_commande'];

// Récupération de la commande
$stmt = $conn->prepare("SELECT * FROM commandes WHERE id = ?");
$stmt->execute([$id_commande]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupération des produits
$stmt = $conn->prepare("SELECT * FROM commande_produit WHERE id_commande = ?");
$stmt->execute([$id_commande]);
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture #<?= $id_commande ?></title>
    <link rel="stylesheet" href="PT.css">
    <style>
        body { font-family: Arial; padding: 30px; background: #f9f9f9; }
        .facture { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); max-width: 700px; margin: auto; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border-bottom: 1px solid #ccc; text-align: left; }
        .total { text-align: right; font-size: 1.2em; margin-top: 20px; }
        .btn-retour {
            display: inline-block;
            margin-top: 20px;
            background: #0080ff;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="facture">
    <h1>Facture #<?= $id_commande ?></h1>
    <p><strong>Date :</strong> <?= date('d/m/Y H:i', strtotime($commande['date_commande'])) ?></p>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Sous-total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produits as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nom_produit']) ?></td>
                    <td><?= $p['quantite'] ?></td>
                    <td><?= number_format($p['prix_unitaire'], 0, ',', ' ') ?> FCFA</td>
                    <td><?= number_format($p['quantite'] * $p['prix_unitaire'], 0, ',', ' ') ?> FCFA</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p class="total"><strong>Total à payer :</strong> <?= number_format($commande['total'], 0, ',', ' ') ?> FCFA</p>

    <a href="index.php" class="btn-retour">Retour à l'accueil</a>
</div>
</body>
</html>

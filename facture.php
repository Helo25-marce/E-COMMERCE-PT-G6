<?php
session_start();
require 'config.php'; // Connexion PDO $pdo

if (!isset($_SESSION['user_id']) || !isset($_SESSION['last_commande_id'])) {
    die("Accès non autorisé.");
}

$id_commande = $_SESSION['last_commande_id'];
$id_utilisateur = $_SESSION['user_id'];

// Récupérer les infos de la commande
$stmt = $pdo->prepare("
    SELECT c.*, u.nom, u.prenom, u.email 
    FROM commandes c 
    JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur 
    WHERE c.id_commande = ?
");
$stmt->execute([$id_commande]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande) {
    die("Commande introuvable.");
}

// Récupérer les produits de la commande
$stmt = $pdo->prepare("
    SELECT p.nom AS nom_produit, cp.quantite, cp.prix_unitaire
    FROM commande_produits cp
    JOIN produits p ON cp.id_produit = p.id_produit
    WHERE cp.id_commande = ?
");
$stmt->execute([$id_commande]);
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture - Commande #<?= $id_commande ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; }
        h1, h2 { color: #0080ff; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { font-weight: bold; text-align: right; }
        .info { margin-top: 20px; }
    </style>
</head>
<body>

<h1>Facture n°<?= $id_commande ?></h1>
<p><strong>Date :</strong> <?= date('d/m/Y', strtotime($commande['date_commande'])) ?></p>

<div class="info">
    <h2>Client</h2>
    <p><?= htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']) ?></p>
    <p>Email : <?= htmlspecialchars($commande['email']) ?></p>
</div>

<div class="info">
    <h2>Livraison</h2>
    <p><strong>Adresse :</strong> <?= htmlspecialchars($commande['adresse_livraison']) ?></p>
    <p><strong>Créneau :</strong> <?= htmlspecialchars($commande['creneau_livraison']) ?></p>
    <p><strong>Moyen de paiement :</strong> <?= htmlspecialchars($commande['moyen_paiement']) ?></p>
</div>

<h2>Détails de la commande</h2>
<table>
    <thead>
        <tr>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Prix unitaire (FCFA)</th>
            <th>Total (FCFA)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produits as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nom_produit']) ?></td>
                <td><?= $p['quantite'] ?></td>
                <td><?= number_format($p['prix_unitaire'], 0, ',', ' ') ?></td>
                <td><?= number_format($p['prix_unitaire'] * $p['quantite'], 0, ',', ' ') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3" class="total">Total général :</td>
            <td class="total"><?= number_format($commande['total'], 0, ',', ' ') ?> FCFA</td>
        </tr>
    </tbody>
</table>

</body>
</html>

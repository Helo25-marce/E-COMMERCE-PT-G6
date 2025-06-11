<?php
session_start();
require_once 'config.php';

// Vérifier la session utilisateur
if (!isset($_SESSION['utilisateur_id']) || !isset($_SESSION['last_commande_id'])) {
    die("Accès refusé.");
}

$commande_id = $_SESSION['last_commande_id'];
$user_id = $_SESSION['utilisateur_id'];

// Vérifier la connexion à la base
if (!isset($pdo)) {
    die("Erreur de connexion à la base de données.");
}

// Récupérer les infos de la commande
$stmt = $pdo->prepare("SELECT * FROM commandes WHERE id_commande = ? AND id_utilisateur = ?");
$stmt->execute([$commande_id, $user_id]);
$commande = $stmt->fetch();

if (!$commande) {
    die("Commande non trouvée.");
}

// Récupérer les produits de la commande
$stmt = $pdo->prepare("
    SELECT p.nom, cp.quantite, cp.prix_unitaire
    FROM commande_produits cp
    JOIN produits p ON p.id = cp.id_produit
    WHERE cp.id_commande = ?
");
$stmt->execute([$commande_id]);
$produits = $stmt->fetchAll();

// Générer le HTML de la facture
ob_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture - Commande #<?= $commande_id ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f9f9f9; }
        h1 { text-align: center; color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #ecf0f1; }
        .total { text-align: right; font-size: 18px; margin-top: 20px; }
        .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #888; }
    </style>
</head>
<body>
    <h1>Facture</h1>
    <p><strong>Commande #:</strong> <?= $commande_id ?></p>
    <p><strong>Date:</strong> <?= $commande['date_commande'] ?></p>
    <p><strong>Moyen de paiement:</strong> <?= $commande['moyen_paiement'] ?></p>
    <p><strong>Adresse de livraison:</strong> <?= $commande['adresse_livraison'] ?></p>

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
            <?php $total = 0; ?>
            <?php foreach ($produits as $produit): ?>
                <tr>
                    <td><?= htmlspecialchars($produit['nom']) ?></td>
                    <td><?= $produit['quantite'] ?></td>
                    <td><?= number_format($produit['prix_unitaire'], 0, ',', ' ') ?> FCFA</td>
                    <td><?= number_format($produit['quantite'] * $produit['prix_unitaire'], 0, ',', ' ') ?> FCFA</td>
                </tr>
                <?php $total += $produit['quantite'] * $produit['prix_unitaire']; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p class="total"><strong>Total: <?= number_format($total, 0, ',', ' ') ?> FCFA</strong></p>
    <div class="footer">Merci pour votre confiance. BHELMAR - E-commerce collaboratif.</div>
</body>
</html>
<?php
$html = ob_get_clean();
file_put_contents("facture/facture_{$commande_id}.html", $html);
echo $html;
?>

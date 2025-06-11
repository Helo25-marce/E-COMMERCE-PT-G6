<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    header("Location: panierB.php");
    exit;
}

$total = 0;
foreach ($_SESSION['panier'] as $item) {
    $total += $item['prix'] * $item['quantite'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        $id_utilisateur = $_SESSION['user_id'] ?? null;
        if (!$id_utilisateur) {
            header('Location: inscription.php');
            exit;
        }

        $statut = 'En attente';
        $moyen_paiement = $_POST['moyen_paiement'] ?? 'espèces';
        $adresse_livraison = $_POST['adresse'] ?? 'Adresse inconnue';
        $creneau_livraison = $_POST['creneau'] ?? 'Non précisé';

        // Insérer la commande
        $stmt = $pdo->prepare("INSERT INTO commandes (id_utilisateur, date_commande, total, statut, moyen_paiement, adresse_livraison, creneau_livraison)
                               VALUES (?, NOW(), ?, ?, ?, ?, ?)");
        $stmt->execute([$id_utilisateur, $total, $statut, $moyen_paiement, $adresse_livraison, $creneau_livraison]);

        $id_commande = $pdo->lastInsertId();

        // Insertion des produits
        $stmt2 = $pdo->prepare("INSERT INTO commande_produits (id_commande, id_produit, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
        foreach ($_SESSION['panier'] as $item) {
            $stmt2->execute([$id_commande, $item['id'], $item['quantite'], $item['prix']]);
        }

        $pdo->commit();
        header("Location: facture.php?id_commande=$id_commande");
        exit;

    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Erreur lors de la validation de la commande : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Validation commande</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>
    <div class="checkout-container">
        <h2>Récapitulatif</h2>
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

        <form method="post" class="mt-4">
            <label for="adresse">Adresse de livraison :</label>
            <input type="text" name="adresse" id="adresse" required class="form-control mb-2">

            <label for="creneau">Créneau horaire :</label>
            <input type="text" name="creneau" id="creneau" class="form-control mb-2">

            <label for="moyen_paiement">Mode de paiement :</label>
            <select name="moyen_paiement" id="moyen_paiement" class="form-select mb-3" required>
                <option value="espèces">Espèces</option>
                <option value="carte">Carte Bancaire</option>
                <option value="mobile">Mobile Money</option>
            </select>

            <button type="submit" class="btn btn-success">Valider la commande</button>
        </form>
    </div>
</body>
</html>

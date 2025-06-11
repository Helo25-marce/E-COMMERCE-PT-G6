<?php
session_start();
require_once 'config.php'; // Connexion PDO via $conn

if (empty($_SESSION['panier'])) {
    header('Location: panierB.php');
    exit;
}

// Calcul du total
$total = 0;
foreach ($_SESSION['panier'] as $item) {
    $total += $item['prix'] * $item['quantite'];
}

// Si l'utilisateur valide la commande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['valider'])) {
    try {
        $conn->beginTransaction();

        // Insertion de la commande
        $date = date('Y-m-d H:i:s');
        $stmt = $conn->prepare("INSERT INTO commandes (date_commande, total) VALUES (?, ?)");
        $stmt->execute([$date, $total]);
        $id_commande = $conn->lastInsertId();

        // Insertion des produits de la commande
        $stmtProduit = $conn->prepare("INSERT INTO commande_produit (id_commande, nom_produit, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");

        foreach ($_SESSION['panier'] as $item) {
            $stmtProduit->execute([
                $id_commande,
                $item['nom'],
                $item['quantite'],
                $item['prix']
            ]);
        }

        $conn->commit();
        unset($_SESSION['panier']); // Vider le panier après validation
        header('Location: facture.php?id_commande=' . $id_commande);
        exit;

    } catch (Exception $e) {
        $conn->rollBack();
        die("Erreur lors de la validation de la commande : " . $e->getMessage());
    }
}

// Préparation affichage
$total = number_format($total, 0, ',', ' ');
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
            padding: 30px;
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
        .revoir:hover {
            background: #ffb366;
            color: white;
        }
        .good {
            background: linear-gradient(90deg,#44c5c7,#0080ff 80%);
            color: #fff;
            box-shadow: 0 2px 8px #44c5c73a;
        }
        .good:hover {
            background: linear-gradient(90deg,#0080ff,#44c5c7 80%);
        }
        table {
            width: 90%;
            margin: auto;
            background: #f5f8ff;
            border-radius: 10px;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
        }
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
            <td><strong><?= $total ?> FCFA</strong></td>
        </tr>
        </tbody>
    </table>
    <div class="checkout-btns">
        <a href="panierB.php" class="revoir">Revoir mon panier</a>
        <form method="post" style="display:inline;">
            <button type="submit" name="valider" class="good">Valider</button>
        </form>
    </div>
</div>
</body>
</html>

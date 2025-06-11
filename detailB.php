<?php
session_start();
require_once('config.php');

$id = $_GET['id'] ?? 0;

// Préparer la requête avec le bon nom de colonne
$stmt = $conn->prepare("SELECT * FROM produits WHERE id_produit = :id");
$stmt->execute(['id' => $id]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    echo "<h2>Produit introuvable</h2>";
    exit;
}

// Ajouter au panier (simulation)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantite = max(1, (int)$_POST['quantite']);
    $_SESSION['panier'][$produit['id_produit']] = [
        'nom' => $produit['nom'],
        'prix' => $produit['prix'],
        'quantite' => $quantite,
        'image' => $produit['image']
    ];
    header("Location: panier.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($produit['nom']) ?> - Détail</title>
    <link rel="stylesheet" href="PT.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
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
        .detail-grid {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }
        .detail-grid img {
            width: 100%;
            max-width: 350px;
            border-radius: 10px;
        }
        .infos {
            flex: 1;
        }
        .infos h2 {
            margin-bottom: 10px;
        }
        .infos p {
            margin: 10px 0;
        }
        .infos form {
            margin-top: 20px;
        }
        .infos input[type=number] {
            width: 80px;
            padding: 6px;
        }
        .btn {
            padding: 10px 20px;
            background: #00b894;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn:hover {
            background: #019875;
        }
        .back-link {
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            color: #555;
        }
        .back-link:hover {
            color: #000;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="detail-grid">
            <div>
                <img src="images/<?= htmlspecialchars($produit['image']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>">
            </div>
            <div class="infos">
                <h2><?= htmlspecialchars($produit['nom']) ?></h2>
                <p><strong>Prix :</strong> <?= number_format($produit['prix'], 0, ',', ' ') ?> FCFA</p>
                <p><strong>Description :</strong> <?= htmlspecialchars($produit['description']) ?></p>

                <form method="post">
                    <label>Quantité :</label>
                    <input type="number" name="quantite" value="1" min="1">
                    <button type="submit" class="btn">Ajouter au panier</button>
                </form>
            </div>
        </div>

        <a href="indexboutique.php" class="back-link">← Retour à la boutique</a>
    </div>
</body>
</html>

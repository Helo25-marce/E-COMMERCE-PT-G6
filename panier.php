<?php
// panier.php
session_start();

require_once 'config.php';

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = array();
}
c
function getProductDetails($productId, $mysqli) {
    $sql = "SELECT id, name, price, image_url FROM products WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

        switch ($action) {
            case 'add':
                if ($productId > 0) {
                    if (isset($_SESSION['panier'][$productId])) {
                        $_SESSION['panier'][$productId]['quantity'] += $quantity;
                    } else {
                        $product = getProductDetails($productId, $mysqli);
                        if ($product) {
                            $_SESSION['panier'][$productId] = array(
                                'id' => $product['id'],
                                'name' => $product['name'],
                                'price' => $product['price'],
                                'image_url' => $product['image_url'],
                                'quantity' => $quantity
                            );
                        }
                    }
                }
                break;

            case 'update_quantity':
                if ($productId > 0 && isset($_SESSION['panier'][$productId])) {
                    if ($quantity > 0) {
                        $_SESSION['panier'][$productId]['quantity'] = $quantity;
                    } else {
                        unset($_SESSION['panier'][$productId]);
                    }
                }
                break;

            case 'remove':
                if ($productId > 0 && isset($_SESSION['panier'][$productId])) {
                    unset($_SESSION['panier'][$productId]);
                }
                break;
        }
    }
    header('Location: panier.php');
    exit();
}

$totalPrice = 0;
foreach ($_SESSION['panier'] as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Panier - BHELMAR</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            color: #E67E22;
            text-align: center;
            margin-bottom: 30px;
        }
        .panier-item {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding: 15px 0;
            margin-bottom: 15px;
        }
        .panier-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .panier-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-right: 20px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .item-details {
            flex-grow: 1;
        }
        .item-details h3 {
            margin: 0 0 5px 0;
            color: #6C3428;
        }
        .item-details p {
            margin: 0;
            color: #555;
        }
        .item-actions {
            display: flex;
            align-items: center;
        }
        .item-actions input[type="number"] {
            width: 60px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
            margin-right: 10px;
        }
        .item-actions button {
            background-color: #C0392B;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .item-actions button:hover {
            background-color: #A93226;
        }
        .panier-summary {
            text-align: right;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }
        .panier-summary h2 {
            color: #E67E22;
            margin-bottom: 15px;
        }
        .panier-summary p {
            font-size: 1.2em;
            font-weight: bold;
            color: #6C3428;
        }
        .panier-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .panier-actions button {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .panier-actions .btn-back {
            background-color: #6C3428;
            color: white;
        }
        .panier-actions .btn-back:hover {
            background-color: #5B2C22;
        }
        .panier-actions .btn-checkout {
            background-color: #E67E22;
            color: white;
        }
        .panier-actions .btn-checkout:hover {
            background-color: #D35400;
        }
        .empty-panier {
            text-align: center;
            font-size: 1.2em;
            color: #777;
            padding: 50px;
            border: 1px dashed #ddd;
            border-radius: 8px;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Votre Panier</h1>

        <?php if (empty($_SESSION['panier'])): ?>
            <div class="empty-panier">
                Votre panier est vide.
            </div>
        <?php else: ?>
            <div class="panier-items">
                <?php foreach ($_SESSION['panier'] as $productId => $item): ?>
                    <div class="panier-item">
                        <img src="<?php echo htmlspecialchars($item['image_url'] ?? 'placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <div class="item-details">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p>Prix: <?php echo number_format($item['price'], 2); ?> XAF</p>
                            <p>Total: <?php echo number_format($item['price'] * $item['quantity'], 2); ?> XAF</p>
                        </div>
                        <div class="item-actions">
                            <form action="panier.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                <input type="hidden" name="action" value="update_quantity">
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="0" onchange="this.form.submit()">
                            </form>
                            <form action="panier.php" method="post" style="margin-left: 10px;">
                                <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                <input type="hidden" name="action" value="remove">
                                <button type="submit">Supprimer</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="panier-summary">
                <h2>Total du panier:</h2>
                <p><?php echo number_format($totalPrice, 2); ?> XAF</p>
            </div>

            <div class="panier-actions">
                <button class="btn-back" onclick="history.back()">Continuer vos achats</button>
                <button class="btn-checkout" onclick="location.href='paiement.php'">Passer à la caisse</button>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>

<?php
$mysqli->close();
?>
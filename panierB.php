<?php
session_start();
require_once 'config.php';

// Langue
$lang = $_GET['lang'] ?? ($_SESSION['lang'] ?? 'fr');
$_SESSION['lang'] = $lang;
@include_once "lang/{$lang}.php";

// Simuler un panier d'achat
$cart = [
    ['name' => 'Item 1', 'price' => 20, 'quantity' => 1],
    ['name' => 'Item 2', 'price' => 30, 'quantity' => 1],
    ['name' => 'Item 3', 'price' => 50, 'quantity' => 1],
];

// Calcul du total du panier
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <title>Shopping Cart</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6f9;
      color: #333;
    }
    .container {
      max-width: 1200px;
      margin: 30px auto;
    }
    .cart-header {
      text-align: center;
      margin-bottom: 20px;
    }
    .cart-items {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .cart-items table {
      width: 100%;
      margin-bottom: 20px;
    }
    .cart-items table th, .cart-items table td {
      text-align: left;
      padding: 10px;
    }
    .cart-items table th {
      background-color: #f1f1f1;
    }
    .btn-custom {
      background-color: #4CAF50;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      font-size: 16px;
      text-decoration: none;
    }
    .btn-custom:hover {
      background-color: #45a049;
    }
    .total {
      font-weight: bold;
      font-size: 18px;
      margin-top: 20px;
    }
    footer {
      background: #333;
      color: #fff;
      text-align: center;
      padding: 20px 0;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="cart-header">
      <h1>My Shopping Cart</h1>
    </div>

    <div class="cart-items">
      <form method="POST" action="CheckoutB.php">
        <table>
          <thead>
            <tr>
              <th>Item</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cart as $index => $item): ?>
              <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td>$<?= number_format($item['price'], 2) ?></td>
                <td>
                  <input type="number" name="cart[<?= $index ?>][quantity]" value="<?= $item['quantity'] ?>" min="1">
                </td>
                <td>
                  <button class="btn btn-danger btn-sm" name="remove_item" value="<?= $index ?>">Remove</button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <div class="total">
          <p>Total: $<?= number_format($total, 2) ?></p>
        </div>

        <!-- Passer les données du panier -->
        <?php foreach ($cart as $index => $item): ?>
          <input type="hidden" name="cart[<?= $index ?>][name]" value="<?= htmlspecialchars($item['name']) ?>">
          <input type="hidden" name="cart[<?= $index ?>][price]" value="<?= $item['price'] ?>">
        <?php endforeach; ?>

        <div class="actions text-center">
          <button class="btn-custom" type="button" onclick="goBack()">⬅ Go Back</button>
          <button class="btn-custom" type="submit" name="checkout" value="true">✅ Checkout</button>
        </div>
      </form>
    </div>
  </div>

  <footer>
    <p>&copy; 2025 BHELMAR - All Rights Reserved</p>
  </footer>

  <script>
    function goBack() {
      window.history.back();
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

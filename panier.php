<?php
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['panier'])) $_SESSION['panier'] = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produit = $_POST['produit'];
    $prix = $_POST['prix'];
    $quantite = $_POST['quantite'];
    $_SESSION['panier'][] = ['produit' => $produit, 'prix' => $prix, 'quantite' => $quantite];
}
?>
<h1>Panier</h1>
<div id="cart">
    <?php
    $total = 0;
    foreach ($_SESSION['panier'] as $item) {
        echo "<div>{$item['produit']} - {$item['quantite']} x {$item['prix']} FCFA</div>";
        $total += $item['prix'] * $item['quantite'];
    }
    echo "<h3>Total : {$total} FCFA</h3>";
    ?>
</div>
<a href="welcome.php">Continuer les achats</a>

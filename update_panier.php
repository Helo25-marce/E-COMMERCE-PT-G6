<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $quantite = max(1, intval($_POST['quantite']));

    if (isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id]['quantite'] = $quantite;
    }

    // Recalculer sous-total et total
    $sous_total = $_SESSION['panier'][$id]['prix'] * $quantite;
    $total = 0;
    foreach ($_SESSION['panier'] as $item) {
        $total += $item['prix'] * $item['quantite'];
    }

    echo json_encode([
        'sous_total' => number_format($sous_total, 0, ',', ' '),
        'total' => number_format($total, 0, ',', ' ')
    ]);
}

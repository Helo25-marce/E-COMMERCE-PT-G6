<?php
session_start();
header('Content-Type: application/json');

// Vérifie que la requête est bien POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['quantite'])) {
    
    $id = intval($_POST['id']);
    $quantite = max(1, intval($_POST['quantite']));

    // Vérifie si l’ID existe dans le panier
    if (isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id]['quantite'] = $quantite;

        // Recalcul sous-total et total
        $sous_total = $_SESSION['panier'][$id]['prix'] * $quantite;
        $total = 0;
        foreach ($_SESSION['panier'] as $item) {
            $total += $item['prix'] * $item['quantite'];
        }

        echo json_encode([
            'status' => 'success',
            'sous_total' => number_format($sous_total, 0, ',', ' '),
            'total' => number_format($total, 0, ',', ' ')
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Produit introuvable dans le panier']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Requête invalide']);
}
?>

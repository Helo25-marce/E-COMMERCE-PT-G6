<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['produit_id'] ?? null;
    $nom = $_POST['produit_nom'] ?? null;
    $prix = isset($_POST['produit_prix']) ? floatval($_POST['produit_prix']) : 0;
    $quantite = isset($_POST['quantite']) ? intval($_POST['quantite']) : 1;

    if (!$id || !$nom || !$prix || $quantite <= 0) {
        echo "Erreur : données manquantes.";
        exit;
    }

    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    $existe = false;
    foreach ($_SESSION['panier'] as &$produit) {
        if ($produit['id'] == $id) {
            $produit['quantite'] += $quantite;
            $existe = true;
            break;
        }
    }

    if (!$existe) {
        $_SESSION['panier'][] = [
            'id' => $id,
            'nom' => $nom,
            'prix' => $prix,
            'quantite' => $quantite
        ];
    }

    echo "OK";
}

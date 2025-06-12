<?php
// === supprimer_livreur.php ===
session_start();
require_once 'config.php';

// Vérification de session admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: alog.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ? AND type = 'livreur'");
    $stmt->execute([$id]);
}

header("Location: admin_dashboard.php");
exit();
?>

<?php
// === categories.php ===
session_start();
require_once 'config.php';

// Langue et connexion
$lang = $_GET['lang'] ?? ($_SESSION['lang'] ?? 'fr');
$_SESSION['lang'] = $lang;
include_once "lang/{$lang}.php";
$loggedIn = isset($_SESSION['utilisateur']);

// Liste des types de boutiques
$types = [
    'boulangerie' => 'Boulangerie',
    'boucherie' => 'Boucherie',
    'poissonnerie' => 'Poissonnerie',
    'pharmacie' => 'Parapharmacie',
    'restaurant' => 'Restaurant'
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Catégories - BHELMAR</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{background:#f9f9f9;font-family:'Segoe UI',sans-serif;margin:0;padding:0}
    .navbar{background:#d35400}
    .navbar-brand, .nav-link{color:#fff!important}
    .container{padding-top:2rem}
    .category-btn{width:100%;margin-bottom:1rem;padding:1rem;border-radius:10px;font-size:1.2rem;color:#fff;text-decoration:none;display:block;text-align:center;}
    .cat-boulangerie{background:#f39c12}
    .cat-boucherie{background:#c0392b}
    .cat-poissonnerie{background:#2980b9}
    .cat-pharmacie{background:#27ae60}
    .cat-restaurant{background:#8e44ad}
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">BHELMAR</a>
  </div>
</nav>
<div class="container">
  <h2 class="mb-4 text-center" style="color:#d35400;">Choisissez une catégorie</h2>
  <?php foreach ($types as $slug => $label): ?>
    <a href="<?= $slug ?>.php" class="category-btn cat-<?= $slug ?>"><?= $label ?></a>
  <?php endforeach; ?>
</div>
</body>
</html>


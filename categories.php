<?php
// === categories.php ===
session_start();
require_once 'config.php';

// Langue et connexion
$lang = $_GET['lang'] ?? ($_SESSION['lang'] ?? 'fr');
$_SESSION['lang'] = $lang;
@include_once "lang/{$lang}.php";
$loggedIn = isset($_SESSION['utilisateur']);

// Liste des types de boutiques
$types = [
    'boulangerie' => '🥖 Boulangerie',
    'boucherie' => '🍖 Boucherie',
    'poissonerie' => '🐟 Poissonnerie',
    'pharmacie' => '🧼 Parapharmacie',
    'restaurant' => '🍽️ Restaurant'
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Catégories - BHELMAR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #f9f9f9, #fffdfb);
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
    }

    .navbar {
      background: #d35400;
      padding: 1rem;
    }

    .navbar-brand {
      font-size: 1.5rem;
      font-weight: bold;
      color: #fff !important;
    }

    .container {
      padding: 2rem 1rem;
      max-width: 800px;
      margin: auto;
    }

    h2 {
      color: #d35400;
      text-align: center;
      margin-bottom: 2rem;
    }

    .category-card {
      text-align: center;
      padding: 1.5rem;
      border-radius: 15px;
      margin-bottom: 1.5rem;
      color: #fff;
      font-size: 1.3rem;
      font-weight: bold;
      text-decoration: none;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      display: block;
    }

    .category-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }

    .cat-boulangerie { background: #f39c12; }
    .cat-boucherie { background: #c0392b; }
    .cat-poissonerie { background: #2980b9; }
    .cat-pharmacie { background: #27ae60; }
    .cat-restaurant { background: #8e44ad; }

    @media (min-width: 768px) {
      .category-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
      }
    }
  </style>
</head>
<body>

<nav class="navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">BHELMAR</a>
  </div>
</nav>

<div class="container">
  <h2>Explorez nos Catégories</h2>
  <div class="text-end mb-3">
  <a href="ai_client.php" class="btn btn-sm btn-info">🤖 Parler à l’IA</a>
</div>

  <div class="category-grid">
    <?php foreach ($types as $slug => $label): ?>
      <a href="<?= $slug ?>.php" class="category-card cat-<?= $slug ?>"><?= $label ?></a>
    <?php endforeach; ?>
  </div>
</div>

</body>
</html>
<!-- Icône IA flottante -->
<style>
  #ia-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    background-color: #3498db;
    color: white;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    text-align: center;
    font-size: 30px;
    line-height: 60px;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
  }
  #ia-chatbox {
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 320px;
    max-height: 400px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.2);
    display: none;
    z-index: 10000;
    padding: 15px;
  }
</style>

<div id="ia-button" title="Parler à l'IA">🤖</div>

<div id="ia-chatbox">
  <h5>IA BHELMAR</h5>
  <form id="ia-form">
    <textarea name="prompt" rows="3" class="form-control" placeholder="Posez votre question..."></textarea>
    <button type="submit" class="btn btn-primary btn-sm mt-2">Envoyer</button>
  </form>
  <div id="ia-response" style="margin-top:10px;font-size:14px;"></div>
</div>

<script>
  const btn = document.getElementById('ia-button');
  const box = document.getElementById('ia-chatbox');
  btn.onclick = () => box.style.display = box.style.display === 'block' ? 'none' : 'block';

  document.getElementById('ia-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const responseDiv = document.getElementById('ia-response');
    responseDiv.innerHTML = '⏳ Réponse en cours...';

    fetch('ai_client_ajax.php', {
      method: 'POST',
      body: new FormData(form)
    })
    .then(res => res.text())
    .then(data => responseDiv.innerHTML = data)
    .catch(() => responseDiv.innerHTML = '❌ Erreur lors de la communication avec l’IA.');
  });
</script>

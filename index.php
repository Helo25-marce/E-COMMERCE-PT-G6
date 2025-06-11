<?php
session_start();
require_once 'config.php';

// Gestion de la langue
$lang = $_GET['lang'] ?? ($_SESSION['lang'] ?? 'fr');
$_SESSION['lang'] = $lang;
@include_once "lang/{$lang}.php";

// Obtenir l’état de connexion
$loggedIn = isset($_SESSION['utilisateur_id']);

// Catégories disponibles
$cats = ['boucherie', 'poissonnerie', 'pharmacie', 'restaurant', 'boulangerie'];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <title>Bienvenue sur BHELMAR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #ece9e6, #ffffff);
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
    }
    .custom-navbar {
      background-color: #1c1c1e;
      padding: .75rem 2rem;
    }
    .logo-img { height: 40px; }
    .site-title { font-size: 1.4rem; font-weight: bold; color: #fff; }
    .subtitle { color: #bbb; font-size: .8rem; margin-left: 5px; }
    .hero-section { background: url('hero.jpg') center/cover no-repeat; height: 80vh; position: relative; }
    .hero-overlay {
      background: rgba(0,0,0,0.6);
      position: absolute; inset: 0;
      display: flex; flex-direction: column; justify-content: center; align-items: center;
      color: #fff; text-align: center; padding: 2rem;
    }
    .search-transparent input {
      background: rgba(255,255,255,0.3);
      border: 1px solid #ccc;
      color: #333;
    }
    .search-small select, .search-small button {
      padding: .5rem;
    }
    .categories .btn {
      margin: .3rem;
      border-radius: 50px;
    }
    footer { background: #1c1c1e; color: #ccc; text-align: center; padding: 1rem; font-size: .9rem; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg custom-navbar">
  <a class="navbar-brand d-flex align-items-center" href="index.php">
    <img src="logo.jpg" alt="Logo" class="logo-img">
    <span class="site-title">BHELMAR</span>
    <small class="subtitle">Tous à domicile</small>
  </a>
  <div class="collapse navbar-collapse justify-content-end">
    <ul class="navbar-nav align-items-center">
      <?php if (!$loggedIn): ?>
        <li class="nav-item"><a class="nav-link text-white" href="login.php">Connexion</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="inscription.php">Inscription</a></li>
      <?php else: ?>
        <li class="nav-item"><a class="nav-link text-white" href="welcome.php">Mon compte</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="logout.php">Déconnexion</a></li>
      <?php endif; ?>
      <li class="nav-item ms-3">
        <form method="GET" id="lang-form">
          <select class="form-select form-select-sm" name="lang" onchange="this.form.submit()">
            <?php foreach ($langs as $code=>$label): ?>
              <option value="<?= $code ?>" <?= $lang=== $code?'selected':'' ?>><?= $label ?></option>
            <?php endforeach; ?>
          </select>
        </form>
      </li>
    </ul>
  </div>
</nav>

<section class="hero-section">
  <div class="hero-overlay">
    <h1><?= TXT_WELCOME ?></h1>
    <p class="mb-4"><?= TXT_SUBTITLE ?></p>

    <!-- Barre de recherche transparente -->
    <form method="GET" action="recherche.php" class="w-75 search-transparent mb-3">
      <input type="text" name="q" class="form-control" placeholder="<?= TXT_PLACEHOLDER ?>">
    </form>
    <!-- Filtre catégories plus petite -->
    <form method="GET" action="recherche.php" class="d-flex w-50 search-small">
      <select name="categorie" class="form-select me-2">
        <option value=""><?= TXT_ALL_CATEGORIES ?></option>
        <?php foreach($cats as $cat): ?>
          <option value="<?= $cat ?>"><?= ucfirst($cat) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-light"><i class="fas fa-search"></i></button>
    </form>

    <!-- Boutons catégories dynamiques -->
    <div class="categories mt-4">
      <?php foreach($cats as $cat): ?>
        <a href="indexboutique.php?categorie=<?= $cat ?>" class="btn btn-outline-light text-capitalize"><?= $cat ?></a>
      <?php endforeach; ?>
      <a href="indexboutique.php" class="btn btn-outline-info">Accès aux Boutiques</a>
    </div>
  </div>
</section>

<footer>
  <p>Contact: 657558491 | heloisemarcellinepelagiekackka@gmail.com</p>
  <p>&copy; 2025 BHELMAR</p>
</footer>

</body>
</html>

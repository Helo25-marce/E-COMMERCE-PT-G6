<?php
session_start();
require_once 'config.php';

// Langue
$lang = $_GET['lang'] ?? ($_SESSION['lang'] ?? 'fr');
$_SESSION['lang'] = $lang;
@include_once "lang/{$lang}.php";

// Connexion
$loggedIn = isset($_SESSION['utilisateur']);

// Catégories
$cats = ['boucherie','poissonnerie','pharmacie','restaurant','boulangerie'];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <title>BHELMAR – Tous à domicile</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: url('hero.jpg') center/cover no-repeat;
      position: relative;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      color: #fff;
      min-height: 100vh;
    }
    body::before {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(0,0,0,0.6);
      z-index: 0;
    }
    .navbar, .hero-overlay, footer { position: relative; z-index: 1; }
    .custom-navbar { background: rgba(0,0,0,0.8) !important; }
    .logo-img { height: 40px; }
    .site-title, .subtitle, .nav-link, footer a { color: #fff !important; }
    .auth-item, .cart-item { text-align: center; margin: 0 .5rem; }
    .auth-item i, .cart-item i { font-size: 1.2rem; }
    .auth-item span, .cart-item span { display: block; font-size: .75rem; }
    .search-form { max-width: 600px; width: 100%; }
    .btn-search { background: #e74c3c; border: none; }
    .btn-search:hover { background: #c0392b; }
    .boutique-btn {
      margin: .5rem;
      padding: .5rem 1rem;
      border-radius: 50px;
      color: #fff;
      animation: blink 1s infinite;
      text-decoration: none;
      display: inline-block;
    }
    @keyframes blink { 50% { opacity: .5; } }
    .btn-boucherie { background: #d35400; }
    .btn-poissonnerie { background: #2980b9; }
    .btn-pharmacie { background: #27ae60; }
    .btn-restaurant { background: #8e44ad; }
    .btn-boulangerie { background: #f39c12; }
    .btn-access {
      background: #c0392b; color: #fff; border: none; padding: .6rem 1.2rem; border-radius: 8px;
      text-decoration: none;
    }
    .btn-access:hover { background: #96281b; }
    footer {
      background: rgba(0,0,0,0.8);
      padding: 1rem;
      text-align: center;
    }
    footer a { color: #fff !important; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg custom-navbar">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="logo.jpg" class="logo-img me-2" alt="Logo">
      <div>
        <div class="site-title">BHELMAR</div>
        <small class="subtitle">Tous à domicile</small>
      </div>
    </a>
    <div class="ms-auto d-flex align-items-center">
      <?php if (!$loggedIn): ?>
        <div class="auth-item">
          <a href="login.php" class="text-white">
            <i class="fas fa-sign-in-alt"></i><span>Se connecter</span>
          </a>
        </div>
        <div class="auth-item">
          <a href="inscription.php" class="text-white">
            <i class="fas fa-user-plus"></i><span>S'inscrire</span>
          </a>
        </div>
      <?php else: ?>
        <div class="auth-item">
          <a href="profile.php" class="text-white">
            <i class="fas fa-user-circle"></i><span>Mon compte</span>
          </a>
        </div>
        <div class="cart-item">
          <a href="panierB.php" class="text-white">
            <i class="fas fa-shopping-cart"></i><span>Panier</span>
          </a>
        </div>
        <div class="auth-item">
          <a href="logout.php" class="text-white">
            <i class="fas fa-sign-out-alt"></i><span>Déconnexion</span>
          </a>
        </div>
      <?php endif; ?>
      <form method="GET" class="ms-3">
        <select name="lang" class="form-select form-select-sm" onchange="this.form.submit()">
          <?php foreach (['fr'=>'Français','en'=>'English','es'=>'Español','de'=>'Deutsch','ar'=>'العربية','zh'=>'中文'] as $code=>$lbl): ?>
            <option value="<?= $code ?>" <?= $lang === $code ? 'selected' : '' ?>>
              <?= $lbl ?>
            </option>
          <?php endforeach; ?>
        </select>
      </form>
    </div>
  </div>
</nav>

<section class="hero-overlay d-flex flex-column justify-content-center align-items-center text-center" style="height:80vh;">
  <h1 class="display-4 mb-3"><?= TXT_WELCOME ?></h1>
  <p class="lead mb-4"><?= TXT_SUBTITLE ?></p>

  <form method="GET" action="recherche.php" class="search-form d-flex mb-3 mx-auto">
    <input type="text" name="q" class="form-control me-2"
           placeholder="<?= TXT_PLACEHOLDER ?>"
           style="background:rgba(255,255,255,0.3); border:none;">
    <button type="submit" class="btn btn-search">Rechercher</button>
  </form>

  <div class="categories text-center">
    <?php foreach ($cats as $cat): ?>
      <a href="indexboutique.php?categorie=<?= $cat ?>"
         class="boutique-btn btn-<?= $cat ?> text-capitalize">
        <?= ucfirst($cat) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <a href="indexboutique.php" class="btn-access mt-4">Accès aux boutiques</a>
</section>

<footer>
  <p>Tel: 657558491 | Email:
    <a href="mailto:heloisemarcellinepelagiekackka@gmail.com">
      heloïsemarcellinepelagiekackka@gmail.com
    </a>
  </p>
  <p>&copy; 2025 BHELMAR</p>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

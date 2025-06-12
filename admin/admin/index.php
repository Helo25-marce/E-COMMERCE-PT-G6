<?php
session_start();
require_once 'config.php';

// Langue
$lang = $_GET['lang'] ?? ($_SESSION['lang'] ?? 'fr');
$_SESSION['lang'] = $lang;
@include_once "lang/{$lang}.php";


// État de connexion
$loggedIn = isset($_SESSION['utilisateur_id']);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <title>BHELMAR - Tous à domicile</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: url('hero.jpg') center/cover no-repeat;
      position: relative;
      min-height: 100vh;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      color: #fff;
    }
    /* voile noir */
    body::before {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(0,0,0,0.6);
      z-index: 0;
    }
    .navbar, footer, .hero-overlay { position: relative; z-index: 1; }
    .custom-navbar { background: rgba(0,0,0,0.8) !important; }
    .logo-img { height: 40px; }
    .site-title, .subtitle, .nav-link, footer a { color: white !important; }
    .auth-icons a { color: white; margin: 0 0.5rem; font-size: 1.2rem; }
    .search-form { max-width: 600px; width: 100%; }
    .btn-search { background-color: #e67e22; color: white; }
    .btn-search:hover { background-color: #cf711f; }
    .categories .boutique-btn { margin: 0.5rem; animation: blink 1s infinite; }
    @keyframes blink { 50% { opacity: 0.5; } }
    .btn-access { background-color: #d35400; color: white; border: none; }
    .btn-access:hover { background-color: #b03a02; }
    footer {
      background: rgba(0,0,0,0.8);
      padding: 1rem;
      text-align: center;
    }
    footer p { margin: 0.2rem; }
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
        <a href="login.php" class="auth-icons"><i class="fas fa-sign-in-alt"></i></a>
        <a href="inscription.php" class="auth-icons"><i class="fas fa-user-plus"></i></a>
      <?php else: ?>
        <a href="welcome.php" class="auth-icons"><i class="fas fa-user-circle"></i></a>
        <a href="logout.php" class="auth-icons"><i class="fas fa-sign-out-alt"></i></a>
      <?php endif; ?>
      <form method="GET" class="ms-3">
        <select name="lang" class="form-select form-select-sm" onchange="this.form.submit()">
          <?php foreach([ 'fr'=>'Français','en'=>'English','es'=>'Español','de'=>'Deutsch'] as $code=>$lbl): ?>
            <option value="<?= $code ?>" <?= $lang===$code?'selected':'' ?>><?= $lbl ?></option>
          <?php endforeach; ?>
        </select>
      </form>
    </div>
  </div>
</nav>

<section class="hero-overlay d-flex flex-column justify-content-center align-items-center text-center" style="height:80vh;">
  <h1 class="display-4 mb-3"><?= TXT_WELCOME ?></h1>
  <p class="lead mb-4"><?= TXT_SUBTITLE ?></p>

  <!-- Barre de recherche principale transparente -->
  <form method="GET" action="recherche.php" class="search-form d-flex mb-3">
    <input type="text" name="q" class="form-control me-2" placeholder="<?= TXT_PLACEHOLDER ?>" style="background: rgba(255,255,255,0.3); border: none;">
    <button type="submit" class="btn btn-search">Rechercher</button>
  </form>

  <!-- Catégories adaptatives -->
  <div class="categories text-center">
    <?php foreach($cats as $cat): ?>
      <a href="indexboutique.php?categorie=<?= $cat ?>" class="btn btn-outline-light boutique-btn text-capitalize"><?= $cat ?></a>
    <?php endforeach; ?>
  </div>

  <!-- Accès aux boutiques, bouton frappant -->
  <a href="indexboutique.php" class="btn btn-access mt-4">Accès aux boutiques</a>
</section>

<footer>
  <p>Tel: 657558491 | Email: <a href="mailto:heloisemarcellinepelagiekackka@gmail.com">heloïsemarcellinepelagiekackka@gmail.com</a></p>
  <p>&copy; 2025 BHELMAR</p>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

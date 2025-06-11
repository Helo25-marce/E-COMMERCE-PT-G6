<?php
session_start();
$lang = $_GET['lang'] ?? ($_SESSION['lang'] ?? 'fr');
$_SESSION['lang'] = $lang;

$langs = [
  'fr' => 'Français',
  'en' => 'English',
  'es' => 'Español',
  'de' => 'Deutsch',
  'ar' => 'العربية',
  'zh' => '中文'
];

@include_once "lang/{$lang}.php";

$cats = ['boucherie', 'poissonnerie', 'pharmacie', 'restaurant', 'boulangerie'];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <title>T~D | Tous à domicile: BHELMAR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="PT.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

  <!-- NAVIGATION -->
  <nav class="navbar navbar-expand-lg navbar-dark custom-navbar">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="Images.h/logobon.jpg" alt="Logo T~D" class="logo-img me-2">
      <span class="site-title">T~D <small class="subtitle">Tous à domicile</small></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="connexion.php?role=admin"><i class="fas fa-user-shield me-1"></i> Admin</a></li>
        <li class="nav-item"><a class="nav-link" href="connexion.php?role=client"><i class="fas fa-user me-1"></i> Client</a></li>
        <li class="nav-item"><a class="nav-link" href="inscription.php"><i class="fas fa-user-plus me-1"></i> <?= TXT_SIGNUP ?></a></li>
        <li class="nav-item ms-3">
          <form method="GET" action="" id="lang-form">
            <select class="form-select form-select-sm lang-select" name="lang" onchange="document.getElementById('lang-form').submit();">
              <?php foreach ($langs as $code => $label): ?>
                <option value="<?= $code ?>" <?= $lang === $code ? 'selected' : '' ?>><?= $label ?></option>
              <?php endforeach; ?>
            </select>
          </form>
        </li>
      </ul>
    </div>
  </nav>

  <!-- SECTION PRINCIPALE -->
  <section class="hero-section">
    <div class="hero-overlay text-center">
      <h1 class="mb-4"><?= TXT_WELCOME ?></h1>
      <p class="slogan-animated">Livraison rapide, produits frais, et service à votre porte !</p>
      <p class="mb-4 fs-5"><?= TXT_SUBTITLE ?></p>

      <form class="search-form" method="GET" action="recherche.php">
        <input type="text" name="q" class="form-control mb-3" placeholder="<?= TXT_PLACEHOLDER ?>">
        <select name="categorie" class="form-select mb-3">
          <option value=""><?= TXT_ALL_CATEGORIES ?></option>
          <?php foreach ($cats as $cat): ?>
            <option value="<?= $cat ?>"><?= ucfirst($cat) ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-accent"><i class="fas fa-search me-1"></i> <?= TXT_SEARCH ?></button>
      </form>

      <div class="categories mt-4">
        <?php foreach ($cats as $cat): ?>
          <a href="boutiques.php?categorie=<?= $cat ?>" class="btn btn-outline-light cat-btn text-capitalize">
            <i class="fas fa-store me-1"></i> <?= $cat ?>
          </a>
        <?php endforeach; ?>
        <a href="indexboutique.php" class="btn btn-outline-info mt-2"><i class="fas fa-store me-1"></i> Accès aux Boutiques</a>
      </div>
    </div>
  </section>

  <!-- PIED DE PAGE -->
  <footer class="text-center">
    <h2>Contact</h2>
    <p>Tel: 657558491</p>
    <p>Email: <a href="mailto:heloisemarcellinepelagiekackka@gmail.com">heloïsemarcellinepelagiekackka@gmail.com</a></p>
    <p>LinkedIn | GitHub</p>
    <p>&copy; 2025 <strong>T~D</strong> – Tous à domicile: BHELMAR. <?= TXT_RIGHTS ?></p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
require_once "config.php";

// Langue
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

// Catégories de boutiques
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
  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="index.php">
      <img src="Images.h/logo.jpg" alt="Logo T~D" class="me-2" style="height: 40px;"> T~D
      <small class="d-block text-white-50">Tous à domicile</small>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="connexion.php?role=admin"><i class="fas fa-user-shield"></i> Admin</a></li>
        <li class="nav-item"><a class="nav-link" href="connexion.php?role=client"><i class="fas fa-user"></i> Client</a></li>
        <li class="nav-item"><a class="nav-link" href="inscription.php"><i class="fas fa-user-plus"></i> <?= TXT_SIGNUP ?></a></li>
        <li class="nav-item">
          <form method="GET" action="" class="ms-3">
            <select class="form-select form-select-sm" name="lang" onchange="this.form.submit();">
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
  <section class="text-center py-5 bg-light">
    <div class="container">
      <h1 class="display-5 mb-3 text-primary fw-bold"><?= TXT_WELCOME ?></h1>
      <p class="lead">Livraison rapide, produits frais, et service à votre porte !</p>
      <p class="mb-4 fs-5 text-muted"><?= TXT_SUBTITLE ?></p>

      <form class="row g-2 justify-content-center mb-4" method="GET" action="recherche.php">
        <div class="col-md-4">
          <input type="text" name="q" class="form-control" placeholder="<?= TXT_PLACEHOLDER ?>">
        </div>
        <div class="col-md-3">
          <select name="categorie" class="form-select">
            <option value=""><?= TXT_ALL_CATEGORIES ?></option>
            <?php foreach ($cats as $cat): ?>
              <option value="<?= $cat ?>"><?= ucfirst($cat) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> <?= TXT_SEARCH ?></button>
        </div>
      </form>

      <div class="d-flex flex-wrap justify-content-center gap-2">
        <?php foreach ($cats as $cat): ?>
          <a href="boutiques.php?categorie=<?= $cat ?>" class="btn btn-outline-dark">
            <i class="fas fa-store me-1"></i> <?= ucfirst($cat) ?>
          </a>
        <?php endforeach; ?>
        <a href="indexboutique.php" class="btn btn-outline-info"><i class="fas fa-store"></i> Accès aux Boutiques</a>
      </div>
    </div>
  </section>

  <!-- PIED DE PAGE -->
  <footer class="bg-dark text-light text-center py-4">
    <h5>Contact</h5>
    <p>Tel: 657558491 | Email: <a href="mailto:heloisemarcellinepelagiekackka@gmail.com" class="text-light">heloïsemarcellinepelagiekackka@gmail.com</a></p>
    <p>&copy; 2025 T~D – Tous à domicile: BHELMAR. <?= TXT_RIGHTS ?></p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

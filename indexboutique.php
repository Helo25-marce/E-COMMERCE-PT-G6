<?php
// === indexcategories.php ===
session_start();
require_once 'config.php';

try {
    // On récupère toutes les catégories
    $stmt = $pdo->query("
        SELECT 
            id_categorie, 
            nom, 
            description, 
            image 
        FROM categories
    ");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors du chargement des catégories : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Nos Catégories - BHELMAR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f9f9f9; font-family: 'Segoe UI', sans-serif; }
    .navbar { background-color: #d35400; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .navbar-brand span { color: white; font-size: 1.5rem; margin-left: .5rem; }
    .container { margin-top: 50px; }
    .card-cat { border: none; border-radius: 15px; overflow: hidden; box-shadow: 0 6px 20px rgba(0,0,0,0.1);
                 transition: transform .3s, box-shadow .3s; }
    .card-cat:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
    .card-cat img { width: 100%; height: 200px; object-fit: cover; }
    .card-title { color: #d35400; font-weight: bold; }
    .btn-details { background: #e67e22; color: white; border: none; }
    .btn-details:hover { background: #cf711f; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="logo.jpg" alt="Logo BHELMAR" height="40">
      <span>BHELMAR</span>
    </a>
  </div>
</nav>

<div class="container">
  <h2 class="text-center my-4" style="color:#d35400;">Nos Catégories</h2>
  <div class="row g-4">
    <?php foreach ($categories as $c): ?>
      <div class="col-md-4">
        <div class="card card-cat">
          <img src="Images.h/logobon.jpg" 
               alt="<?= htmlspecialchars($c['nom']) ?>">
          <div class="card-body text-center">
            <h5 class="card-title"><?= htmlspecialchars($c['nom']) ?></h5>
            <!-- on génère dynamiquement le lien vers la page catégorie -->
            <a href="<?= strtolower($c['nom']) ?>.php" class="btn btn-details">
              Voir <?= htmlspecialchars($c['nom']) ?>
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Bootstrap 5 JS bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

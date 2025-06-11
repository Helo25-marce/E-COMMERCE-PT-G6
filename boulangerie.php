<?php
// === boulangerie.php ===
session_start(); require_once 'config.php';
$type = 'Boulangerie';
$loggedIn = isset($_SESSION['utilisateur']);

$stmt = $pdo->prepare("SELECT id, nom, logo FROM boutiques WHERE nom LIKE ?");
$stmt->execute(["%{$type}%"]);
$boutiques = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><title>Boulangeries - BHELMAR</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{background:#f5f5f5;font-family:'Segoe UI',sans-serif;margin:0;padding:0}
    .navbar{background:#d35400}
    .navbar-brand, .nav-link{color:#fff!important}
    .container{padding:2rem}
    .card{border:none;border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,0.1);margin-bottom:1.5rem}
    .card-title{color:#f39c12}
    .btn-detail{background:#f39c12;color:#fff;border:none}
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg"><div class="container-fluid"><a class="navbar-brand" href="categories.php">Catégories</a></div></nav>
<div class="container">
  <h2 class="mb-4 text-center">Boulangeries locales</h2>
  <div class="row">
    <?php if(empty($boutiques)): ?>
      <p>Aucune boulangerie trouvée.</p>
    <?php endif; foreach($boutiques as $b): ?>
      <div class="col-md-4">
        <div class="card">
          <img src="images/<?=htmlspecialchars($b['logo'])?>" class="card-img-top" alt="">
          <div class="card-body text-center">
            <h5 class="card-title"><?=htmlspecialchars($b['nom'])?></h5>
            <a href="detailB.php?id=<?=$b['id']?>" class="btn btn-detail">Voir détails</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>

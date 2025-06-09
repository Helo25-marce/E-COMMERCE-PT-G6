<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MultiBoutiques - Accueil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

  <!-- Section Héros (Image de fond + recherche) -->
  <div class="hero">
    <div class="hero-content">
      <h1 class="display-4 fw-bold">Commandez local, vivez mieux</h1>
      <p class="lead mb-4">Découvrez nos boutiques partenaires près de chez vous</p>

      <!-- Champ de recherche (ex : produit ou boutique) -->
      <form action="recherche.php" method="get" class="input-group mb-3">
        <input type="text" name="q" class="form-control form-control-lg" placeholder="Rechercher un produit, une boutique..." required>
        <button class="btn btn-primary btn-lg" type="submit">Rechercher</button>
      </form>

      <!-- Boutons catégories -->
      <div class="categories d-flex flex-wrap justify-content-center">
        <?php
          $cats = ['boucherie', 'poissonnerie', 'pharmacie', 'restaurant', 'boulangerie'];
          foreach ($cats as $cat) {
            echo '<a href="boutiques.php?categorie=' . $cat . '" class="btn btn-outline-light cat-btn text-capitalize">' . $cat . '</a>';
          }
        ?>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-dark text-white py-4 text-center">
    &copy; <?= date("Y") ?> MultiBoutiques. Livraison multi-boutiques à portée de clic.
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

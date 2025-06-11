<?php
// === connexion.php ===
session_start();
$role = $_GET['role'] ?? 'client';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion <?= ucfirst($role) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
  <div class="container" style="max-width: 450px;">
    <h2 class="mb-4">Connexion <?= ucfirst($role) ?></h2>
    <form method="post" action="login.php">
      <input type="hidden" name="role" value="<?= $role ?>">
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Mot de passe</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button class="btn btn-primary">Se connecter</button>
    </form>
  </div>
</body>
</html>

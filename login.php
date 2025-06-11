<?php
session_start();
require_once 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
        // Stocke l'utilisateur complet en session
        $_SESSION['utilisateur'] = $utilisateur;
        header("Location: index.php");
        exit;
    } else {
        $message = "Email ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(120deg, #3498db, #8e44ad);
      font-family: 'Segoe UI', sans-serif;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
    }
    .card {
      width: 100%;
      max-width: 400px;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    .btn-primary {
      background-color: #8e44ad;
      border: none;
    }
    .btn-primary:hover {
      background-color: #732d91;
    }
    .form-control:focus {
      box-shadow: 0 0 5px rgba(142,68,173,0.7);
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="card-body p-4">
      <h2 class="card-title text-center mb-4">Connexion</h2>
      <?php if ($message): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>
      <form method="POST" action="login.php">
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="mot_de_passe" class="form-label">Mot de passe</label>
          <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
      </form>
      <p class="mt-3 text-center">
        Pas encore inscrit ? <a href="inscription.php" class="text-decoration-none text-primary">Créer un compte</a>
      </p>
    </div>
  </div>
</body>
</html>

<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit;
}
if (isset($_SESSION['livreur_id'])) {
    header("Location: livreur_dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            if ($user['role'] === 'admin') {
                $_SESSION['admin_id'] = $user['id'];
                header("Location: admin_dashboard.php");
                exit;
            } elseif ($user['role'] === 'livreur') {
                $_SESSION['livreur_id'] = $user['id'];
                header("Location: livreur_dashboard.php");
                exit;
            } else {
                $error = "Rôle non autorisé à se connecter.";
            }
        } else {
            $error = "Mot de passe incorrect.";
        }
    } else {
        $error = "Email introuvable.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion - BHELMAR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f1f3f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: 'Segoe UI', sans-serif;
    }
    .login-card {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
    }
  </style>
</head>
<body>
<div class="login-card">
  <h4 class="text-center mb-4">Connexion</h4>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="POST" autocomplete="off">
    <div class="mb-3">
      <input type="email" name="email" class="form-control" placeholder="Email" autocomplete="off" required>
    </div>
    <div class="mb-3">
      <input type="password" name="password" class="form-control" placeholder="Mot de passe" autocomplete="new-password" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
  </form>
</div>
</body>
</html>

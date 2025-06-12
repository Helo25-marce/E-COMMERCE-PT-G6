<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            if ($user['role'] === 'admin') {
                $_SESSION['admin_id'] = $user['id'];
                header("Location: admin_dashboard.php");
                exit;
            } else {
                $error = "Rôle non autorisé.";
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
  <title>Connexion Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #ecf0f1;
      display: flex;
      height: 100vh;
      justify-content: center;
      align-items: center;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      width: 100%;
      max-width: 400px;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      background: white;
    }
  </style>
</head>
<body>
<div class="card">
  <h4 class="text-center mb-4">Connexion Administrateur</h4>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>
  <form method="POST">
    <div class="mb-3">
      <input type="email" name="email" class="form-control" placeholder="Email" required>
    </div>
    <div class="mb-3">
      <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
  </form>
</div>
</body>
</html>

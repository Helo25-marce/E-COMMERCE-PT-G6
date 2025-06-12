<?php
// === login_roles.php ===
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['utilisateur'] = $user['id'];

        if ($user['role'] === 'admin') {
            $_SESSION['admin_id'] = $user['id'];
            header('Location: admin_dashboard.php');
            exit();
        } elseif ($user['role'] === 'livreur') {
            $_SESSION['livreur_id'] = $user['id'];
            header('Location: livreur_dashboard.php');
            exit();
        }
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f5f6fa; font-family: 'Segoe UI', sans-serif; }
    .login-form {
      width: 100%; max-width: 400px; margin: 50px auto; background: #fff;
      padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .form-title {
      text-align: center; margin-bottom: 20px; color: #34495e;
    }
  </style>
</head>
<body>
<div class="login-form">
  <h3 class="form-title">Connexion</h3>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"> <?= $error ?> </div>
  <?php endif; ?>
  <form method="POST">
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Mot de passe</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
  </form>
</div>
</body>
</html>

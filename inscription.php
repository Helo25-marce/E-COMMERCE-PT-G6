<?php
session_start();
require_once 'config.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $hash = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];

    $stmt = $pdo->prepare("SELECT id_utilisateur FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount()) {
        $message = "Email déjà utilisé.";
    } else {
        $pdo->prepare("INSERT INTO utilisateurs 
            (nom, prenom, email, mot_de_passe, role, telephone, adresse, date_inscription)
            VALUES (?, ?, ?, ?, 'client', ?, ?, NOW())")
          ->execute([$nom,$prenom,$email,$hash,$telephone,$adresse]);
        $_SESSION['utilisateur_id'] = $pdo->lastInsertId();
        $_SESSION['utilisateur_nom'] = $nom;
        $_SESSION['utilisateur_email'] = $email;
        $_SESSION['utilisateur_role'] = 'client';
        header('Location: welcome.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Inscription</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #dff6fd, #b8e9f7);
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      margin-top: 80px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .btn-success {
      background-color: #3498db;
      border: none;
    }
    .btn-success:hover {
      background-color: #2980b9;
    }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
  <div class="card p-4" style="max-width:500px; width:100%;">
    <h2 class="text-center mb-4">Créer un compte</h2>
    <?php if ($message): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="row g-2">
        <div class="col">
          <input type="text" name="nom" class="form-control" placeholder="Nom" required>
        </div>
        <div class="col">
          <input type="text" name="prenom" class="form-control" placeholder="Prénom" required>
        </div>
      </div>
      <div class="mt-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>
      <div class="mt-3">
        <input type="text" name="telephone" class="form-control" placeholder="Téléphone" required>
      </div>
      <div class="mt-3">
        <input type="text" name="adresse" class="form-control" placeholder="Adresse" required>
      </div>
      <div class="mt-3">
        <input type="password" name="mot_de_passe" class="form-control" placeholder="Mot de passe" required>
      </div>
      <button type="submit" class="btn btn-success w-100 mt-4">S'inscrire</button>
    </form>
    <p class="text-center mt-3">
      Déjà inscrit ? <a href="login.php">Se connecter</a>
    </p>
  </div>
</body>
</html>

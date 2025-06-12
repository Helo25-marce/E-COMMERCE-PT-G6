<?php
require_once 'config.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom        = htmlspecialchars($_POST['nom']);
    $prenom     = htmlspecialchars($_POST['prenom']);
    $email      = htmlspecialchars($_POST['email']);
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Vérifie si l'email est déjà utilisé
    $check = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        $message = "❌ Cet email est déjà utilisé.";
    } else {
        $insert = $pdo->prepare("INSERT INTO users (email, password, role, nom, prenom) VALUES (?, ?, 'livreur', ?, ?)");
        if ($insert->execute([$email, $password, $nom, $prenom])) {
            $message = "✅ Inscription réussie ! Vous pouvez maintenant vous connecter.";
        } else {
            $message = "❌ Une erreur est survenue lors de l'inscription.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription Livreur</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: url('images.h/marche.jpg') center/cover no-repeat;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
            color: #fff;
        }

        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.7);
            z-index: 0;
        }

        .form-box {
            background-color: rgba(255,255,255,0.12);
            padding: 40px;
            border-radius: 15px;
            z-index: 1;
            position: relative;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            width: 100%;
            background: #27ae60;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
            color: #f1c40f;
        }

        a {
            color: #fff;
            text-decoration: underline;
            display: block;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Inscription Livreur</h2>

        <?php if (!empty($message)): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="email" name="email" placeholder="Adresse email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">📝 S'inscrire</button>
        </form>

        <a href="livreur_choix.php">⬅ Retour</a>
    </div>
</body>
</html>

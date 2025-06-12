<?php
session_start();
require_once 'config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'livreur'");
    $stmt->execute([$email]);
    $livreur = $stmt->fetch();

    if ($livreur && password_verify($password, $livreur['password'])) {
        $_SESSION['livreur_id'] = $livreur['id'];
        $_SESSION['livreur_nom'] = $livreur['nom'];
        header('Location: livreur_dashboard.php');
        exit;
    } else {
        $message = "❌ Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Livreur</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #3498db, #2ecc71);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #fff;
        }

        .login-box {
            background: #ffffff22;
            padding: 40px;
            border-radius: 12px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
        }

        h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #1abc9c;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #16a085;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
            color: #f8d7da;
        }

        a {
            display: block;
            margin-top: 15px;
            text-align: center;
            color: #fff;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Connexion Livreur</h2>
        <?php if (!empty($message)): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">🔐 Se connecter</button>
        </form>
        <a href="livreur_dashboard.php">⬅ Retour</a>
    </div>
</body>
</html>

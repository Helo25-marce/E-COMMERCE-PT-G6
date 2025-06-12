<?php
session_start();
require_once 'config.php'; // Connexion à la base de données

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['admin_id'])) {
    header('Location: admin_dashboard.php'); // Si connecté, rediriger vers le tableau de bord
    exit;
}

// Traitement de la connexion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérification dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]); // Exécute la requête avec l'email

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) { // Vérification du mot de passe
        if ($user['role'] == 'admin') { // Vérification du rôle admin
            $_SESSION['admin_id'] = $user['id']; // Créer une session pour l'admin
            header('Location: admin_dashboard.php'); // Redirection vers le tableau de bord admin
            exit;
        } else {
            $error_message = "Utilisateur non autorisé.";
        }
    } else {
        $error_message = "Identifiants incorrects.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <style>
        /* Style général de la page */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Container du formulaire */
        .container {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        /* Titre */
        h2 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* Style pour les messages d'erreur */
        p {
            color: #e74c3c;
            font-size: 14px;
        }

        /* Champ de formulaire */
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="email"]:focus, input[type="password"]:focus {
            border-color: #3498db;
            outline: none;
        }

        /* Bouton de soumission */
        button {
            background-color: #3498db;
            color: #fff;
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        /* Bouton de soumission au survol */
        button:active {
            background-color: #1abc9c;
        }

        /* Formulaire avec un léger padding */
        form {
            margin-top: 20px;
        }

        /* Style du lien de redirection */
        a {
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Pour les écrans plus petits */
        @media (max-width: 600px) {
            .container {
                padding: 20px;
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Connexion à l'admin</h2>
        <?php if (isset($error_message)): ?>
            <p><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="POST" action="alog.php">
            <label for="email">Email :</label><br>
            <input type="email" name="email" required><br><br>

            <label for="password">Mot de passe :</label><br>
            <input type="password" name="password" required><br><br>

            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>

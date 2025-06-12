<?php
// livreur_choix.php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accès Livreurs - BHELMAR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to bottom right, #f39c12, #e67e22);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #ffffff22;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            width: 90%;
            max-width: 400px;
        }

        h1 {
            margin-bottom: 30px;
        }

        button {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: none;
            font-size: 18px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .connect-btn {
            background-color: #2ecc71;
            color: white;
        }

        .connect-btn:hover {
            background-color: #27ae60;
        }

        .inscrire-btn {
            background-color: #3498db;
            color: white;
        }

        .inscrire-btn:hover {
            background-color: #2980b9;
        }

        a {
            color: #fff;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            font-size: 14px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Accès Livreurs</h1>
        <form action="livreur_login.php" method="get">
            <button type="submit" class="connect-btn">🔑 Se connecter</button>
        </form>
        <form action="inscription_livreur.php" method="get">
            <button type="submit" class="inscrire-btn">📝 S'inscrire</button>
        </form>
        <a href="index.php">⬅ Retour à l'accueil</a>
    </div>
</body>
</html>

<?php
session_start();
require_once 'config.php';
$loggedIn = isset($_SESSION['admin_id']) || isset($_SESSION['livreur_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue sur BHELMAR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: url('images.h/marche.jpg') center/cover no-repeat;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }

        .custom-navbar {
            background-color: rgba(0, 0, 0, 0.85);
            width: 100%;
            padding: 10px 20px;
            position: fixed;
            top: 0;
            z-index: 2;
        }

        .logo-img {
            height: 40px;
        }

        .site-title, .subtitle {
            color: #fff;
            margin-left: 10px;
        }

        .main-content {
            position: relative;
            z-index: 1;
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 100px;
        }

        .container-box {
            background-color: rgba(255, 255, 255, 0.12);
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.4);
            max-width: 400px;
            width: 90%;
            color: white;
        }

        h1 {
            margin-bottom: 20px;
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

        .admin-btn {
            background-color: #2ecc71;
            color: white;
        }

        .admin-btn:hover {
            background-color: #27ae60;
        }

        .livreur-btn {
            background-color: #f39c12;
            color: white;
        }

        .livreur-btn:hover {
            background-color: #e67e22;
        }

        footer {
            padding: 20px;
            font-size: 14px;
            color: #ddd;
            background: rgba(0, 0, 0, 0.7);
            text-align: center;
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg custom-navbar">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="images.h/logobon.jpg" class="logo-img me-2" alt="Logo">
            <div>
                <div class="site-title">BHELMAR</div>
                <small class="subtitle">Tous à domicile</small>
            </div>
        </a>
        <div class="ms-auto d-flex align-items-center">
            <?php if ($loggedIn): ?>
                <div class="text-white">
                    <i class="fas fa-user-check"></i> Connecté
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="main-content">
    <div class="container-box">
        <h1>Bienvenue sur BHELMAR</h1>
        <p>Choisissez votre rôle :</p>
        <form action="alog.php" method="get">
            <button type="submit" class="admin-btn">👨‍💼 Je suis Admin</button>
        </form>
        <form action="livreur_choix.php" method="get">
            <button type="submit" class="livreur-btn">🚚 Je suis Livreur</button>
        </form>
    </div>
</div>

<footer>
    &copy; <?= date('Y') ?> BHELMAR - Tous droits réservés
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
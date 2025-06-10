<?php
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['utilisateur'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profil</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Bienvenue, <?= htmlspecialchars($user['nom']) ?> (<?= $user['role'] ?>)</h2>
        <p><a href="logout.php">Déconnexion</a></p>
    </div>
</body>
</html>

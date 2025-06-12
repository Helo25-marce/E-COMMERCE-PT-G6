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
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->execute([$email, md5($password)]); // Utilisez md5 ou mieux, password_hash pour plus de sécurité
    $user = $stmt->fetch();

    if ($user && $user['role'] == 'admin') { // Vérification du rôle admin
        $_SESSION['admin_id'] = $user['id']; // Créer une session pour l'admin
        header('Location: admin_dashboard.php'); // Redirection vers le tableau de bord admin
        exit;
    } else {
        $error_message = "Identifiants incorrects ou utilisateur non autorisé.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
</head>
<body>
    <h2>Connexion à l'admin</h2>
    <?php if (isset($error_message)): ?>
        <p style="color:red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form method="POST" action="alog.php">
        <label for="email">Email :</label><br>
        <input type="email" name="email" required><br><br>

        <label for="password">Mot de passe :</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Se connecter</button>
    </form>
</body>
</html>

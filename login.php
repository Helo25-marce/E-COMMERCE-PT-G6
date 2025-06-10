<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        $_SESSION['utilisateur'] = [
            'id' => $user['id_utilisateur'],
            'nom' => $user['nom'],
            'role' => $user['role']
        ];
        header('Location: ../index.php');
        exit;
    } else {
        $erreur = "Identifiants incorrects.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <link rel="stylesheet" href="inscriptions.css">
</head>
<body>
    <div class="form-container">
        <h2>Connexion</h2>
        <?php if (isset($erreur)) echo "<p class='erreur'>$erreur</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
        <p><a href="forgot_password.php">Mot de passe oublié ?</a></p>
        <p>Pas encore inscrit ? <a href="register.php">S'inscrire</a></p>
    </div>
</body>
</html>

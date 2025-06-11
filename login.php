<?php
session_start();
require_once("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
            $_SESSION['utilisateur'] = $user;
            header("Location: welcome.php");
            exit();
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Email non trouvé.";
    }
}
?>

<!-- HTML login.php -->
<form method="POST" action="">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
    <button type="submit">Connexion</button>
</form>

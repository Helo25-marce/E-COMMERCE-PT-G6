<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $date = date("Y-m-d");
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];

    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, date_inscription, telephone, adresse)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $prenom, $email, $mot_de_passe, $role, $date, $telephone, $adresse]);

    header('Location: welcome.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Inscription</h2>
        <form method="POST">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
            <select name="role" required>
                <option value="client">Client</option>
                <option value="admin">Admin</option>
            </select>
            <input type="text" name="telephone" placeholder="Téléphone">
            <input type="text" name="adresse" placeholder="Adresse">
            <button type="submit">S'inscrire</button>
        </form>
        <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
    </div>
</body>
</html>

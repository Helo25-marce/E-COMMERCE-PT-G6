<?php
session_start();
require_once("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];

    $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, adresse) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nom, $prenom, $email, $password, $telephone, $adresse);
    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        echo "Erreur lors de l'inscription.";
    }
}
?>

<form method="POST" action="">
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="text" name="prenom" placeholder="Prénom" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
    <input type="text" name="telephone" placeholder="Téléphone">
    <input type="text" name="adresse" placeholder="Adresse">
    <button type="submit">S'inscrire</button>
</form>


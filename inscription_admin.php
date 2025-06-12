<?php
// Inscription Admin
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('config.php');
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hashage du mot de passe

    // Insertion dans la base de données
    $sql = "INSERT INTO utilisateurs (nom, email, password, role) VALUES ('$nom', '$email', '$password', 'admin')";
    if (mysqli_query($conn, $sql)) {
        echo "Inscription réussie. Vous pouvez vous connecter.";
    } else {
        echo "Erreur: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>

<form action="inscription_admin.php" method="POST">
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <input type="submit" value="S'inscrire">
</form>

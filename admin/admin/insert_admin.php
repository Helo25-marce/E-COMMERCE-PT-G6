<?php
require 'config.php'; // Connexion PDO via $pdo

try {
    // Données de l'utilisateur à insérer
    $nom = "Admin Principal";
    $email = "admin@admin.com";
    $motdepasse = "admin123";
    $motdepasse_hash = password_hash($motdepasse, PASSWORD_DEFAULT); // Hachage sécurisé
    $role = "admin";

    // Requête d'insertion
    $stmt = $pdo->prepare("INSERT INTO users (nom, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nom, $email, $motdepasse_hash, $role]);

    echo "✅ Utilisateur admin inséré avec succès.<br>";
    echo "👉 Email : <strong>$email</strong><br>";
    echo "🔐 Mot de passe : <strong>$motdepasse</strong><br>";

} catch (PDOException $e) {
    echo "❌ Erreur lors de l'insertion : " . $e->getMessage();
}
?>

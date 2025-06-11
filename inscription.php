<?php
// === inscription.php ===
require_once 'config.php';
session_start();

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];
    $role = 'client';
    $date = date('Y-m-d H:i:s');

    // Vérifier si l'email existe déjà
    $check = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        $msg = "<div class='alert alert-warning'>Cet email est déjà inscrit.</div>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, date_inscription, telephone, adresse)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $email, $mot_de_passe, $role, $date, $telephone, $adresse]);

        // Connexion automatique après inscription
        $_SESSION['utilisateur_id'] = $pdo->lastInsertId();
        $_SESSION['utilisateur_email'] = $email;
        $_SESSION['utilisateur_nom'] = $nom;
        $_SESSION['utilisateur_role'] = $role;

        header("Location: welcome.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
<div class="container" style="max-width:600px">
    <h2>Inscription Client</h2>
    <?= $msg ?>
    <form method="post">
        <div class="mb-3"><label>Nom</label><input type="text" name="nom" class="form-control" required></div>
        <div class="mb-3"><label>Prénom</label><input type="text" name="prenom" class="form-control" required></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label>Mot de passe</label><input type="password" name="mot_de_passe" class="form-control" required></div>
        <div class="mb-3"><label>Téléphone</label><input type="text" name="telephone" class="form-control" required></div>
        <div class="mb-3"><label>Adresse</label><input type="text" name="adresse" class="form-control" required></div>
        <button class="btn btn-success">Créer un compte</button>
    </form>
</div>
</body>
</html>

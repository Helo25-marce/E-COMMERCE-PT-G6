<?php
session_start();
require_once 'config.php';

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: connexion.php');
    exit;
}

// Traitement du formulaire
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_site = $_POST['nom_site'];
    $email_contact = $_POST['email_contact'];
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];

    $stmt = $pdo->prepare("UPDATE parametres SET nom_site = ?, email_contact = ?, telephone = ?, adresse = ? WHERE id = 1");
    if ($stmt->execute([$nom_site, $email_contact, $telephone, $adresse])) {
        $message = "Paramètres mis à jour avec succès.";
    } else {
        $message = "Erreur lors de la mise à jour.";
    }
}

// Récupération des paramètres actuels
$stmt = $pdo->query("SELECT * FROM parametres WHERE id = 1");
$param = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paramètres du site</title>
    <link rel="stylesheet" href="PT.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">⚙️ Paramètres du site</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="nom_site" class="form-label">Nom du site</label>
            <input type="text" name="nom_site" id="nom_site" class="form-control" value="<?= htmlspecialchars($param['nom_site']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="email_contact" class="form-label">Email de contact</label>
            <input type="email" name="email_contact" id="email_contact" class="form-control" value="<?= htmlspecialchars($param['email_contact']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="text" name="telephone" id="telephone" class="form-control" value="<?= htmlspecialchars($param['telephone']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <textarea name="adresse" id="adresse" class="form-control" required><?= htmlspecialchars($param['adresse']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
        <a href="admin_dashboard.php" class="btn btn-secondary">↩️ Retour</a>
    </form>
</div>
</body>
</html>

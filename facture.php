<?php
// facture.php
session_start();
require_once "config.php"; // Connexion PDO
require_once "vendor/autoload.php"; // PHPMailer si pas encore inclus

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 1. Vérifier si utilisateur connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: inscription.php");
    exit();
}

// 2. Récupérer l'email de l'utilisateur
$id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT email, nom FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || empty($user['email'])) {
    header("Location: inscription.php");
    exit();
}

$email = $user['email'];
$nom = $user['nom'];

// 3. Générer un résumé de la commande
$contenu = "<h2>Bonjour $nom, voici votre facture :</h2><ul>";
$total = 0;
foreach ($_SESSION['panier'] as $item) {
    $sous_total = $item['prix'] * $item['quantite'];
    $contenu .= "<li>{$item['nom']} - Quantité: {$item['quantite']} - Total: {$sous_total} FCFA</li>";
    $total += $sous_total;
}
$contenu .= "</ul><p><strong>Total: $total FCFA</strong></p>";

// 4. Envoyer l'email avec PHPMailer
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'tonemail@gmail.com';
    $mail->Password = 'ton_mot_de_passe_app'; // mot de passe d'application Gmail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('tonemail@gmail.com', 'T~D Facturation');
    $mail->addAddress($email, $nom);
    $mail->isHTML(true);
    $mail->Subject = 'Votre facture T~D';
    $mail->Body    = $contenu;

    $mail->send();
    echo "<p>Facture envoyée à $email</p>";
} catch (Exception $e) {
    echo "Erreur lors de l'envoi : {$mail->ErrorInfo}";
}
?>

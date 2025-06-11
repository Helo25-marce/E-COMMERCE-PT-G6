<?php
// … (le code d’insertion de la commande reste inchangé)
    
$pdo->commit();

// Récupérer l'email de l'utilisateur (table `utilisateurs`, pas `utilisateur`)
$stmt_user = $pdo->prepare("SELECT email FROM utilisateurs WHERE id_utilisateur = ?");
$stmt_user->execute([$id_utilisateur]);
$email_utilisateur = $stmt_user->fetchColumn();

// PHPMailer
require 'vendor/autoload.php';            // Chargement via Composer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'heloisemarcellinepelagiekackka@gmail.com';
    $mail->Password   = 'xjly biyo jvph neqe'; // mot de passe d'application
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('heloisemarcellinepelagiekackka@gmail.com', 'BHELMAR');
    $mail->addAddress($email_utilisateur);

    $mail->isHTML(true);
    $mail->Subject = 'Confirmation de votre commande';
    $mail->Body    = sprintf(
        'Merci pour votre commande !<br>Vous pouvez consulter votre <a href="http://localhost/E-COMMERCE-PT-G6/facture/facture_%d.html">facture ici</a>.',
        $id_commande
    );

    $mail->send();
} catch (Exception $e) {
    error_log('PHPMailer Error: ' . $mail->ErrorInfo);
    // Ne pas interrompre l’utilisateur si email échoue
}

// Suite du script…

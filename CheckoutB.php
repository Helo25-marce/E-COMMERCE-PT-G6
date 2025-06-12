<?php
session_start();
require_once 'config.php'; // Connexion base de données
require_once 'fpdf/fpdf.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Vérification du panier
$panier = $_SESSION['panier'] ?? [];
if (empty($panier)) {
    die("Votre panier est vide.");
}

// Récupérer infos utilisateur connecté
$id_utilisateur = $_SESSION['utilisateur_id'] ?? null;
if (!$id_utilisateur) {
    die("Utilisateur non connecté.");
}

$stmt = $pdo->prepare("SELECT nom, email FROM utilisateurs WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$utilisateur = $stmt->fetch();
if (!$utilisateur) {
    die("Utilisateur introuvable.");
}

$nom_client = $utilisateur['nom'];
$email_client = $utilisateur['email'];

// Génération de la facture PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Facture - BHELMAR', 0, 1, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Client : $nom_client", 0, 1);
$pdf->Cell(0, 10, "Date : " . date('d/m/Y'), 0, 1);
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Produit', 1);
$pdf->Cell(30, 10, 'Quantité', 1);
$pdf->Cell(40, 10, 'Prix', 1);
$pdf->Cell(40, 10, 'Total', 1);
$pdf->Ln();

$totalGeneral = 0;
foreach ($panier as $produit) {
    $nom = $produit['name'] ?? 'Inconnu';
    $quantite = (int)($produit['quantity'] ?? 0);
    $prix = (float)($produit['price'] ?? 0);
    $total = $quantite * $prix;
    $totalGeneral += $total;

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(60, 10, $nom, 1);
    $pdf->Cell(30, 10, $quantite, 1);
    $pdf->Cell(40, 10, number_format($prix, 0), 1);
    $pdf->Cell(40, 10, number_format($total, 0), 1);
    $pdf->Ln();
}
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(130, 10, 'Total général', 1);
$pdf->Cell(40, 10, number_format($totalGeneral, 0), 1);

// Enregistrement du fichier
if (!file_exists('facture')) {
    mkdir('facture', 0777, true);
}
$nomFichier = 'facture_' . time() . '.pdf';
$cheminFichier = 'facture/' . $nomFichier;
$pdf->Output('F', $cheminFichier);

// Envoi par email
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // ou votre serveur SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'heloisemarcellinepelagiekackka@gmail.com';
    $mail->Password = 'xjly biyo jvph neqe';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('heloisemarcellinepelagiekackka@gmail.com', 'BHELMAR');
    $mail->addAddress($email_client, $nom_client);
    $mail->Subject = 'Votre facture BHELMAR';
    $mail->Body = 'Bonjour ' . $nom_client . ",\n\nVoici votre facture en pièce jointe. Merci pour votre achat !";

    $mail->addAttachment($cheminFichier);
    $mail->send();
    echo "<script>alert('Commande validée et facture envoyée !');</script>";
} catch (Exception $e) {
    echo "Erreur lors de l'envoi de l'email : " . $mail->ErrorInfo;
}

// ✅ Téléchargement automatique
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $nomFichier . '"');
readfile($cheminFichier);
exit;
?>

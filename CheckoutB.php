<?php
session_start();

// 1. Vérifie que le panier existe
$panier = $_SESSION['panier'] ?? [];

if (empty($panier)) {
    die("Votre panier est vide.");
}

// 2. Inclure FPDF
require_once('fpdf/fpdf.php');

// 3. Créer le PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Facture d\'achat', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Produit', 1);
$pdf->Cell(30, 10, 'Quantite', 1);
$pdf->Cell(40, 10, 'Prix unitaire', 1);
$pdf->Cell(40, 10, 'Total', 1);
$pdf->Ln();

$totalGeneral = 0;

foreach ($panier as $produit) {
    // Vérifie si l'ID est bien défini
    if (!isset($produit['id'])) {
        echo "Erreur : Le produit n'a pas d'ID valide. Il sera ignoré.<br>";
        continue;
    }

    $nom = $produit['name'] ?? 'Inconnu';
    $quantite = (int)($produit['quantity'] ?? 0);
    $prix = (float)($produit['price'] ?? 0);
    $total = $quantite * $prix;
    $totalGeneral += $total;

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, $nom, 1);
    $pdf->Cell(30, 10, $quantite, 1);
    $pdf->Cell(40, 10, number_format($prix, 2), 1);
    $pdf->Cell(40, 10, number_format($total, 2), 1);
    $pdf->Ln();
}

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(110, 10, 'Total général', 1);
$pdf->Cell(40, 10, number_format($totalGeneral, 2), 1);
$pdf->Ln(20);

// 4. Sauvegarde ou affichage
$nomFichier = 'facture_' . time() . '.pdf';
$chemin = __DIR__ . '/facture/' . $nomFichier;

if (!file_exists(__DIR__ . '/facture')) {
    mkdir(__DIR__ . '/facture', 0777, true);
}

$pdf->Output('F', $chemin); // Sauvegarde dans le dossier /facture

echo "<p>Facture générée avec succès : <a href='facture/$nomFichier' target='_blank'>Télécharger</a></p>";
?>

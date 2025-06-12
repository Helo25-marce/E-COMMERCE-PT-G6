<?php
// Traitement de la commande (CheckoutB.php)
session_start();
require 'vendor/autoload.php';            // Charger PHPMailer via Composer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once 'config.php';  // Connexion à la base de données

// Vérifier si le formulaire de checkout a été soumis
if (isset($_POST['checkout'])) {
    $cart = $_POST['cart'];

    // Calcul du total
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Enregistrer la commande dans la base de données
    $stmt = $pdo->prepare("INSERT INTO commandes (total, date_commande) VALUES (?, NOW())");
    $stmt->execute([$total]);
    $id_commande = $pdo->lastInsertId();  // Récupérer l'ID de la commande

    // Insérer les articles du panier dans la base de données
    foreach ($cart as $item) {
        $stmt_item = $pdo->prepare("INSERT INTO commandes_details (id_commande, nom, prix, quantite, photo) VALUES (?, ?, ?, ?, ?)");
        $stmt_item->execute([$id_commande, $item['name'], $item['price'], $item['quantity'], $item['photo']]);
    }

    // Récupérer l'email de l'utilisateur (table `utilisateurs`)
    $stmt_user = $pdo->prepare("SELECT email, nom_livraison FROM utilisateurs WHERE id_utilisateur = ?");
    $stmt_user->execute([$_SESSION['utilisateur']]);
    $user_data = $stmt_user->fetch();
    $email_utilisateur = $user_data['email'];
    $nom_livraison = $user_data['nom_livraison'];

    // Générer la facture en PDF
    require_once('fpdf/fpdf.php');
    $pdf = new FPDF();
    $pdf->AddPage();
    
    // Ajouter le logo de l'entreprise
    $pdf->Image('images/logo.png', 10, 10, 30);  // Ajuste le chemin et la taille du logo
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(200, 10, 'Facture #'.$id_commande, 0, 1, 'C');
    
    // Ajouter le nom de l'entreprise : BHELMAR - Tous à domicile
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(200, 10, 'BHELMAR - Tous à domicile', 0, 1, 'C'); // Nom de l'entreprise centré
    $pdf->SetFont('Arial', '', 12);
    
    // Ajouter le nom de livraison
    $pdf->Cell(200, 10, 'Nom de livraison: '.$nom_livraison, 0, 1, 'C');

    $pdf->Ln(10); // Ajouter un espace

    // Tableau des articles avec image
    $pdf->Cell(50, 10, 'Article', 1);
    $pdf->Cell(30, 10, 'Prix', 1);
    $pdf->Cell(30, 10, 'Quantité', 1);
    $pdf->Cell(30, 10, 'Total', 1);
    $pdf->Cell(50, 10, 'Image', 1, 1);  // Ligne de titres avec colonne Image

    foreach ($cart as $item) {
        $itemTotal = $item['price'] * $item['quantity'];
        $pdf->Cell(50, 10, $item['name'], 1);
        $pdf->Cell(30, 10, '$' . number_format($item['price'], 2), 1);
        $pdf->Cell(30, 10, $item['quantity'], 1);
        $pdf->Cell(30, 10, '$' . number_format($itemTotal, 2), 1);
        
        // Affichage de l'image du produit
        $pdf->Image($item['photo'], 150, $pdf->GetY(), 20);  // Place l'image à droite (ajuster la position)
        $pdf->Ln(10);  // Nouvelle ligne pour la prochaine entrée
    }

    // Ajouter le total général
    $pdf->Cell(160, 10, 'Total', 1);
    $pdf->Cell(30, 10, '$' . number_format($total, 2), 1, 1);

    // Sauvegarder le PDF dans le dossier /facture/
    $pdf_filename = "facture_".$id_commande.".pdf";
    $pdf->Output('F', 'facture/'.$pdf_filename);

    // PHPMailer : Envoi d'email avec la facture
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'heloisemarcellinepelagiekackka@gmail.com';
        $mail->Password   = 'xjly biyo jvph neqe';  // Mot de passe d'application
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('heloisemarcellinepelagiekackka@gmail.com', 'BHELMAR');
        $mail->addAddress($email_utilisateur);

        $mail->isHTML(true);
        $mail->Subject = 'Confirmation de votre commande';
        $mail->Body    = sprintf(
            'Merci pour votre commande !<br>Vous pouvez consulter votre <a href="http://localhost/E-COMMERCE-PT-G6/facture/facture_%d.pdf">facture ici</a>.',
            $id_commande
        );

        // Ajouter la pièce jointe de la facture PDF
        $mail->addAttachment('facture/'.$pdf_filename);

        $mail->send();
        echo 'Votre commande a été envoyée par email avec la facture.';
    } catch (Exception $e) {
        error_log('PHPMailer Error: ' . $mail->ErrorInfo);
    }

    // Rediriger l'utilisateur vers une page de confirmation ou un message de succès
    header('Location: confirmation.php');
} else {
    echo "Aucune donnée de panier trouvée.";
}
?>

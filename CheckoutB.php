<?php
session_start();
require_once 'config.php';

if (empty($_SESSION['panier']) || empty($_SESSION['utilisateur_id'])) {
    header('Location: panierB.php');
    exit;
}

try {
    $pdo->beginTransaction();

    $id_utilisateur = $_SESSION['utilisateur_id'];
    $date_commande = date('Y-m-d H:i:s');
    $total = 0;

    foreach ($_SESSION['panier'] as $item) {
        $total += $item['prix'] * $item['quantite'];
    }

    $moyen_paiement = 'Espèces';
    $adresse_livraison = 'Adresse par défaut';
    $creneau_livraison = 'Matin';

    // Insertion commande
    $stmt = $pdo->prepare("INSERT INTO commandes (id_utilisateur, date_commande, total, statut, moyen_paiement, adresse_livraison, creneau_livraison) 
        VALUES (?, ?, ?, 'En attente', ?, ?, ?)");
    $stmt->execute([$id_utilisateur, $date_commande, $total, $moyen_paiement, $adresse_livraison, $creneau_livraison]);

    $id_commande = $pdo->lastInsertId();

    // Insertion produits
    $stmt_produit = $pdo->prepare("INSERT INTO commande_produits (id_commande, id_produit, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
    foreach ($_SESSION['panier'] as $item) {
        $stmt_produit->execute([
            $id_commande,
            $item['id'],
            $item['quantite'],
            $item['prix']
        ]);
    }

    $pdo->commit();

    // Email utilisateur
    $stmt_user = $pdo->prepare("SELECT email FROM utilisateur WHERE id_utilisateur = ?");
    $stmt_user->execute([$id_utilisateur]);
    $email_utilisateur = $stmt_user->fetchColumn();

    // Envoi mail avec PHPMailer
    require 'vendor/autoload.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'tonemail@gmail.com';
    $mail->Password = 'tonMotDePasseApplication'; // mot de passe application Gmail
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('tonemail@gmail.com', 'BHELMAR');
    $mail->addAddress($email_utilisateur);

    $mail->isHTML(true);
    $mail->Subject = 'Confirmation de votre commande';
    $mail->Body    = 'Merci pour votre commande ! Vous pouvez consulter votre <a href="http://localhost/E-COMMERCE-PT-G6/facture/facture_' . $id_commande . '.html">facture ici</a>.';

    $mail->send();

    $_SESSION['last_commande_id'] = $id_commande;
    unset($_SESSION['panier']);
    header('Location: facture.php');
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    die("Erreur : " . $e->getMessage());
}
?>

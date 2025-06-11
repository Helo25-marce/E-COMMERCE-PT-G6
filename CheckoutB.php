<?php
session_start();
require_once 'config.php';

if (empty($_SESSION['panier']) || empty($_SESSION['utilisateur_id'])) {
    header('Location: panierB.php');
    exit;
}

try {
    $pdo->beginTransaction();

    // Insertion dans la table commandes
    $id_utilisateur = $_SESSION['utilisateur_id'];
    $date_commande = date('Y-m-d H:i:s');
    $total = 0;

    foreach ($_SESSION['panier'] as $item) {
        $total += $item['prix'] * $item['quantite'];
    }

    $moyen_paiement = 'Espèces'; // Peut être dynamique selon ton formulaire
    $adresse_livraison = 'Adresse par défaut'; // À adapter selon ton système
    $creneau_livraison = 'Matin'; // Idem

    $stmt = $pdo->prepare("INSERT INTO commandes (id_utilisateur, date_commande, total, statut, moyen_paiement, adresse_livraison, creneau_livraison) 
        VALUES (?, ?, ?, 'En attente', ?, ?, ?)");
    $stmt->execute([$id_utilisateur, $date_commande, $total, $moyen_paiement, $adresse_livraison, $creneau_livraison]);

    $id_commande = $pdo->lastInsertId();

    // Insertion des produits de la commande
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

    // Redirection vers facture
    $_SESSION['last_commande_id'] = $id_commande;
    unset($_SESSION['panier']);
    header('Location: facture.php');
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    die("Erreur lors de la validation de la commande : " . $e->getMessage());
}
// Envoi du mail
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Configuration serveur SMTP (exemple Gmail)
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'tonemail@gmail.com';
    $mail->Password = 'tonMotDePasseApplication';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Destinataire
    $mail->setFrom('tonemail@gmail.com', 'BHELMAR');
    $mail->addAddress($email_utilisateur); // Récupéré depuis la BDD

    // Contenu du mail
    $mail->isHTML(true);
    $mail->Subject = 'Confirmation de votre commande';
    $mail->Body    = 'Merci pour votre commande ! Vous pouvez consulter votre <a href="http://localhost/E-COMMERCE-PT-G6/facture/facture_' . $commande_id . '.html">facture ici</a>.';

    $mail->send();
} catch (Exception $e) {
    echo "Erreur lors de l'envoi du mail : {$mail->ErrorInfo}";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Validation commande</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>
    <div class="checkout-container">
        <h2>Récapitulatif</h2>
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Sous-total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['panier'] as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nom']) ?></td>
                        <td><?= $item['quantite'] ?></td>
                        <td><?= number_format($item['prix'] * $item['quantite'], 0, ',', ' ') ?> FCFA</td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="2"><strong>Total</strong></td>
                    <td><strong><?= number_format($total, 0, ',', ' ') ?> FCFA</strong></td>
                </tr>
            </tbody>
        </table>

        <form method="post" class="mt-4">
            <label for="adresse">Adresse de livraison :</label>
            <input type="text" name="adresse" id="adresse" required class="form-control mb-2">

            <label for="creneau">Créneau horaire :</label>
            <input type="text" name="creneau" id="creneau" class="form-control mb-2">

            <label for="moyen_paiement">Mode de paiement :</label>
            <select name="moyen_paiement" id="moyen_paiement" class="form-select mb-3" required>
                <option value="espèces">Espèces</option>
                <option value="carte">Carte Bancaire</option>
                <option value="mobile">Mobile Money</option>
            </select>

            <button type="submit" class="btn btn-success">Valider la commande</button>
        </form>
    </div>
</body>
</html>

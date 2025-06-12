<?php
// ai_client.php
$apiKey = 'TON_API_KEY'; // ← Remplace par ta clé OpenAI

$responseMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question'])) {
    $question = trim($_POST['question']);

    $data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => 'Tu es un assistant spécialisé dans la vente de produits alimentaires et médicaux pour la plateforme BHELMAR.'],
            ['role' => 'user', 'content' => $question]
        ]
    ];

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer $apiKey"
        ],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data)
    ]);

    $result = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($result, true);
    $responseMessage = $response['choices'][0]['message']['content'] ?? 'Réponse non disponible.';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Conseiller IA - BHELMAR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f9f9f9; font-family:'Segoe UI', sans-serif }
    .container { max-width: 700px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
    h1 { color: #d35400; }
    .btn-orange { background-color: #d35400; color: white; border: none; }
    .btn-orange:hover { background-color: #b34300; }
    textarea { resize: none; }
  </style>
</head>
<body>

<div class="container">
  <h1>🤖 Conseiller IA - BHELMAR</h1>
  <p>Posez une question pour obtenir une suggestion personnalisée.</p>
  <form method="post">
    <textarea name="question" rows="5" class="form-control" placeholder="Exemple : Quel pain choisir si je suis diabétique ?" required></textarea><br>
    <button type="submit" class="btn btn-orange">Demander à l'IA</button>
  </form>

  <?php if (!empty($responseMessage)): ?>
    <hr>
    <h5>🧠 Réponse de BHELMAR IA :</h5>
    <div class="alert alert-info"><?= nl2br(htmlspecialchars($responseMessage)) ?></div>
  <?php endif; ?>
</div>

</body>
</html>

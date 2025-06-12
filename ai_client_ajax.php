<?php
// ai_client_ajax.php
$apiKey = 'TON_API_KEY';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prompt'])) {
    $prompt = trim($_POST['prompt']);
    $messages = [
        ['role' => 'system', 'content' => "Tu es une IA pour BHELMAR. Si le client parle de son panier, tu peux lui recommander des produits complémentaires dans les catégories suivantes : boulangerie, boucherie, poissonnerie, pharmacie, restaurant. Reste bref et clair."
],
        ['role' => 'user', 'content' => $prompt]
    ];

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer $apiKey"
        ],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode(['model' => 'gpt-3.5-turbo', 'messages' => $messages])
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    if (isset($data['choices'][0]['message']['content'])) {
        echo nl2br(htmlspecialchars($data['choices'][0]['message']['content']));
    } else {
        echo "Erreur lors de la réponse de l’IA.";
    }
} else {
    echo "Aucune question reçue.";
}
?>

<?php
// ai_integration.php
$apiKey = 'TON_API_KEY'; // 🔐 Remplace par ta vraie clé OpenAI (format : sk-...)

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prompt'])) {
    $prompt = trim($_POST['prompt']);

    $data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => 'Tu es un assistant intelligent.'],
            ['role' => 'user', 'content' => $prompt]
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
    if (isset($response['choices'][0]['message']['content'])) {
        echo "<div style='padding:20px;border:1px solid #ccc;margin-top:10px'>
                <strong>Réponse :</strong><br>" . nl2br(htmlspecialchars($response['choices'][0]['message']['content'])) . "
              </div>";
    } else {
        echo "<p style='color:red'>❌ Erreur : " . htmlspecialchars($result) . "</p>";
    }
} else {
    echo "<p style='color:orange'>Aucune requête reçue.</p>";
}
?>

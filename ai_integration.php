<?php
// Exemple pour intégrer GPT-3 via l'API OpenAI
$apiKey = "TON_API_KEY";
$data = array(
    'model' => 'gpt-3.5-turbo',
    'messages' => array(
        array("role" => "system", "content" => "Tu es un assistant intelligent."),
        array("role" => "user", "content" => "Comment puis-je t'aider ?")
    ),
);

$options = array(
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-type: application/json',
        'content' => json_encode($data)
    )
);
$context = stream_context_create($options);
$response = file_get_contents('https://api.openai.com/v1/chat/completions', false, $context);
echo $response; // Affichage de la réponse de l'IA
?>

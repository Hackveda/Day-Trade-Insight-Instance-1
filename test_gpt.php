<?php

// You'll need to replace 'YOUR_API_KEY' with your actual GPT-3 API key.
$apiKey = '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';

// Get the user's message from the POST request
$userMessage = "Write a tweet for Ambujacem Target Hit";

// Call the GPT-3 API (GPT-3.5-turbo engine) to generate a response
$response = gpt35TurboChat($userMessage, $apiKey);

// Return the bot's response as JSON
echo json_encode(['botMessage' => $response]);

function gpt35TurboChat($userMessage, $apiKey) {
    $url = 'https://api.openai.com/v1/engines/text-davinci-003/completions'; // GPT-3.5-turbo engine
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ];

    $data = [
        'prompt' => $userMessage,
        'max_tokens' => 50, // Adjust as needed
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    $responseJson = json_decode($response, true);

    // Extract and return the bot's response
    return $responseJson['choices'][0]['text'];
}
?>

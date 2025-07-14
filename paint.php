<?php
require_once "./include/config.php";
require_once "./include/helpers.php";
only_post();

$apiKey = 'your_openai_api_key_here';  // Replace with your actual API key

$ch = curl_init();

$data = [
    "model" => "gpt-4", // Or "gpt-3.5-turbo"
    "messages" => [
        ["role" => "system", "content" => "You are a helpful assistant."],
        ["role" => "user", "content" => "Tell me a joke."]
    ],
    "temperature" => 0.7
];

curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

if(curl_errno($ch)){
    echo 'Curl error: ' . curl_error($ch);
} else {
    $result = json_decode($response, true);
    var_dump($result);
    echo "Assistant: " . $result['choices'][0]['message']['content'];
}

curl_close($ch);
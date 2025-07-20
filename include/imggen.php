<?php
require_once "./include/config.php";

$apiKey = $OPENAI_API_KEY;  // Replace with your actual API key

function imggen($imagePath, $color)
{
    global $apiKey;

    $prompt = "Paint the color of the wall with red.";

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.openai.com/v1/images/edits',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $apiKey"
        ],
        CURLOPT_POSTFIELDS => [
            'image' => new CURLFile($imagePath, 'image/png', 'image'),
            'mask' => new CURLFile('./mask.png', 'image/png'), // Optional if you have mask
            'prompt' => $prompt,
            'n' => 1,
            'size' => '1024x1024',
            'response_format' => 'url'
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if($err) {
        ajax(['error' => "cURL Error: $err"], 444);
    } else {
        $result = json_decode($response, true);
        if (isset($result['error'])) {
            ajax(['error' => "Error: " . $result['error']['message']], 444);
        }
        $imageUrl = $result['data'][0]['url'];
        return $imageUrl;
    }

    return null;
}

<?php

/**
 * このファイルには、色名を取得する関数が含まれています。
 * This file containss functions to get color names from hex values.
 */

/**
 * 指定された16進数カラーコードから色名を取得します。
 * Retrieves the color name from the given hex color code.
 *
 * @param string $hex Hex color code (e.g. #FF5733)
 * @return string The color name
 */
function colorName($hex)
{
    global $OPENAI_API_KEY;

    $endpoint = 'https://api.openai.com/v1/chat/completions';

    $data = [
        'model' => 'gpt-4',  // or 'gpt-3.5-turbo'
        'messages' => [
            [
                'role' => 'system', 
                'content' => 'You are a [hex color code] to [literary description of the color] converter.'
            ], [
                'role' => 'user', 
                'content' => "Hex color code: $hex." 
                    . ' Provide a natuaral literary description.'
                    . ' The description format must be like "a neutral, medium-dark gray with a hint of green"'
                    . ' Output style is "Color Name: [literary description]" if succeed, "Failed" if fail.'
            ]
        ],
        'temperature' => 0.7
    ];

    $headers = [
        'Authorization: Bearer ' . $OPENAI_API_KEY,
        'Content-Type: application/json'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // タイムアウトを30秒に設定
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // 接続タイムアウトを10秒に設定

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception('Curl error: ' . curl_error($ch));
    } else {
        $result = json_decode($response, true);
        $content =  $result['choices'][0]['message']['content'];
        if($content === 'Failed') {
            throw new Exception('Failed to get color name from OpenAI API');
        }
        $colorName = trim(str_replace('Color Name: ', '', $content));
        return $colorName;
    }

    curl_close($ch);
}
/* function colorName($hex)
{
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 10, // タイムアウトを10秒に設定
            'ignore_errors' => true, // エラーを無視してレスポンスを取得
        ]
    ]);

    $url = "https://www.thecolorapi.com/id?hex=" . ltrim($hex, '#');
    $response = file_get_contents($url, false, $context);
    $data = json_decode($response, true);
    trace("Closest hex: {$data['name']['closest_named_hex']}");
    return $data['name']['value'];
} */

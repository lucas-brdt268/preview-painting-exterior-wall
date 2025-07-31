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
}

<?php
require_once 'config.php';

/*
 * 色のリストをCSVファイルから読み込み、配列として返します
 * Reads a list of colors from a CSV file and returns it as an array
 */

/**
 * 色のリストをCSVファイルから読み込み、配列として返します
 * Reads a list of colors from a CSV file and returns it as an array
 *
 * @return array 色のリストの配列, Array of color list
 */
function colorlist()
{
    global $CSV_PATH;

    $colorArray = [];

    if (($handle = fopen($CSV_PATH, 'r')) !== false) {
        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) >= 2) {
                $hex = trim($data[0]);
                $name = trim($data[1]);
                $colorArray[] = [
                    'hex' => $hex,
                    'name' => $name
                ];
            }
        }
        fclose($handle);
    }

    return $colorArray;
}

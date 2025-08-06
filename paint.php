<?php
require_once "./include/request_init.php";
require_once "./include/helpers.php";
require_once "./include/pngproc.php";
require_once "./include/imggen.php";
require_once "./include/colorname.php";

/*
 * paint.php
 * 壁の色のシミュレーションのための画像のアップロードと処理を処理します
 * Handles image uploading and processing for wall color simulation
 */

// リクエストがPOSTリクエストであることを確認する
// Check if the request is POST
onlyPost();

trace("Start handling request");
// 元の画像をアップロードする
// Upload the original image
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    trace('Error(500): Image upload failed');
    resJson(['error' => '画像のアップロードに失敗しました'], 500);
}
$tempName = $_FILES['image']['tmp_name'];
$fileName = basename($_FILES['image']['name']);
$fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
$fileSizeKB = $_FILES['image']['size'] / 1024; // サイズ（KB), Size in KB
$fileId = uniqid('img_');
$targetName = $UPLOAD_DIR . $fileId;
$targetPath = "$targetName.$fileType";

if ($fileSizeKB > 5120) { // 5MBまでに制限, Limit to 5MB
    trace('Error(500): Image size exceeds the limit of 5MB');
    resJson(['error' => '画像サイズが5MBの制限を超えています'], 500);
}

checkDir($UPLOAD_DIR);
if (!move_uploaded_file($tempName, $targetPath)) {
    trace('Error(500): Failed to save uploaded image');
    resJson(['error' => 'アップロードした画像を保存できませんでした'], 500);
}
trace("File id: $fileId");

// フォームから色を取得する
// Get the color from the form
$color = $_POST['color'];
trace("Color: $color");
/* 
$colorName = $_POST['color_name'] ?? 'custom';
$colorCustom = $_POST['color_custom'] ?? '';
trace("Color name: $colorName, Custom color: $colorCustom");
if ($colorName === 'custom') {
    try{
        $color = colorName($colorCustom) ?? "white";
    } catch (Exception $e) {
        trace('Error(500): ' . $e->getMessage());
        resJson(['error' => '色分析中にエラーが発生しました。'], 500);
    }
} else {
    $color = $colorName;
} 
*/
try {
    $colorName = colorName($color) ?? "white"; // 色名を取得, Get the color name
} catch (Exception $e) {
    trace('Error(500): ' . $e->getMessage());
    resJson(['error' => '色分析中にエラーが発生しました。'], 500);
}
trace("Color Name: $colorName");
// resJson(['error' => "Color Name: $colorName"], 500);

// 画像を生成する
// Generate an image
try {
    $imgUrl = imggen($targetPath, $colorName);
} catch (Exception $e) {
    trace('Error(500): ' . $e->getMessage());
    // resJson(['error' => '画像の生成中にエラーが発生しました。'], 500);
    resJson(['error' => 'システムに問題が発生しています。しばらく経ってから再度お試しください。'], 500);
}

// 画像の保存
// Save the image
$savePath = $OUTPUT_DIR . $fileId . '.jpg';
$imageData = file_get_contents($imgUrl);
checkDir($OUTPUT_DIR);
try {
    file_put_contents($savePath, $imageData);
} catch (Exception $e) {
    trace('Error: ' . $e->getMessage());
}

trace("End handling request\n");

// 画像のURLを返す
// Return the image URL
$base64Image = base64_encode($imageData);
resJson(['image_url' => $imgUrl, 'download_url' => "$BASE_URL/$savePath", 'base64_image' => $base64Image]);

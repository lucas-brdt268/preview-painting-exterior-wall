<?php
require_once "./include/helpers.php";
require_once "./include/pngproc.php";
require_once "./include/imggen.php";
require_once "./include/colorname.php";

/*
 * paint.php
 * 壁の色のシミュレーションのための画像のアップロードと処理を処理します
 * Handles image uploading and processing for wall color simulation
 */

// スクリプトの実行時間を60秒に設定
// Set script execution time to 60 seconds
set_time_limit(60);

// リクエストがPOSTリクエストであることを確認する
// Check if the request is POST
onlyPost();

trace("Start handling request");
// 元の画像をアップロードする
// Upload the original image
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    trace('Error(444): Image upload failed');
    resJson(['error' => '画像のアップロードに失敗しました'], 444);
}
$tempName = $_FILES['image']['tmp_name'];
$fileName = basename($_FILES['image']['name']);
$fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
$fileSizeKB = $_FILES['image']['size'] / 1024; // サイズ（KB), Size in KB
$fileId = uniqid('img_');
$targetName = $UPLOAD_DIR . $fileId;
$targetPath = "$targetName.$fileType";

if ($fileSizeKB > 5120) { // 5MBまでに制限, Limit to 5MB
    trace('Error(444): Image size exceeds the limit of 5MB');
    resJson(['error' => '画像サイズが5MBの制限を超えています'], 444);
}

checkDir($UPLOAD_DIR);
if (!move_uploaded_file($tempName, $targetPath)) {
    trace('Error(444): Failed to save uploaded image');
    resJson(['error' => 'アップロードした画像を保存できませんでした'], 444);
}
trace("File id: $fileId");

// フォームから色を取得する
// Get the color from the form
$color = $_POST['color'];
/* 
$colorName = $_POST['color_name'] ?? 'custom';
$colorCustom = $_POST['color_custom'] ?? '';
trace("Color name: $colorName, Custom color: $colorCustom");
if ($colorName === 'custom') {
    try{
        $color = colorName($colorCustom) ?? "white";
    } catch (Exception $e) {
        trace('Error(444): ' . $e->getMessage());
        resJson(['error' => '色分析中にエラーが発生しました。'], 444);
    }
} else {
    $color = $colorName;
} 
*/
trace("Gotten color name: $color");

// 画像を生成する
// Generate an image
try{
    $imgUrl = imggen($targetPath, $color);
} catch (Exception $e) {
    trace('Error(444): ' . $e->getMessage());
    resJson(['error' => '画像の生成中にエラーが発生しました。'], 444);
}

// 画像の保存
// Save the image
$savePath = $OUTPUT_DIR . $fileId . '.jpg';
$imageData = file_get_contents($imgUrl);
checkDir($OUTPUT_DIR);
try{
    file_put_contents($savePath, $imageData);
} catch (Exception $e) {
    trace('Error: ' . $e->getMessage());
}

trace("End handling request\n");

// 画像のURLを返す
// Return the image URL
$base64Image = base64_encode($imageData);
resJson(['image_url' => $imgUrl, 'download_url' => "$BASE_URL/$savePath", 'base64_image' => $base64Image]);

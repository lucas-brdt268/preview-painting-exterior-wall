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

// リクエストがPOSTリクエストであることを確認する
// Check if the request is POST
onlyPost();

// 元の画像をアップロードする
// Upload the original image
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    resJson(['error' => 'Image upload failed'], 444);
}
$tempName = $_FILES['image']['tmp_name'];
$fileName = basename($_FILES['image']['name']);
$fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
$fileSizeKB = $_FILES['image']['size'] / 1024; // サイズ（KB), Size in KB
$fileId = uniqid('img_', true);
$targetName = $UPLOAD_DIR . $fileId;
$targetPath = "$targetName.$fileType";

if ($fileSizeKB > 5120) { // 5MBまでに制限, Limit to 5MB
    resJson(['error' => 'Image size exceeds the limit of 2MB'], 444);
}

checkDir($UPLOAD_DIR);
if (!move_uploaded_file($tempName, $targetPath)) {
    resJson(['error' => 'Failed to save uploaded image'], 444);
}

// フォームから色を取得する
// Get the color from the form
$colorName = $_POST['color_name'] ?? '';
$colorCustom = $_POST['color_custom'] ?? '';
if ($colorName === 'custom'/*  && !empty($colorCustom) */) {
    $color = colorName($colorCustom) ?? "white";
} else {
    $color = $colorName;
}

// 画像を生成する
// Generate an image
$imgUrl = imggen($targetPath, $color);
if (!$imgUrl) {
    resJson(['error' => 'Image generation failed'], 444);
}

// 画像の保存
// Save the image
$savePath = $OUTPUT_DIR . $fileId . '.jpg';
$imageData = file_get_contents($imgUrl);
checkDir($OUTPUT_DIR);
file_put_contents($savePath, $imageData);

// 画像のURLを返す
// Return the image URL
resJson(['image_url' => $imgUrl, 'download_url' => "$BASE_URL/$savePath"]);

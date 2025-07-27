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

trace("Start handling request");
// 元の画像をアップロードする
// Upload the original image
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    trace('444Error: Image upload failed');
    resJson(['error' => 'Image upload failed'], 444);
}
$tempName = $_FILES['image']['tmp_name'];
$fileName = basename($_FILES['image']['name']);
$fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
$fileSizeKB = $_FILES['image']['size'] / 1024; // サイズ（KB), Size in KB
$fileId = uniqid('img_');
$targetName = $UPLOAD_DIR . $fileId;
$targetPath = "$targetName.$fileType";

if ($fileSizeKB > 5120) { // 5MBまでに制限, Limit to 5MB
    trace('444Error: Image size exceeds the limit of 2MB');
    resJson(['error' => 'Image size exceeds the limit of 2MB'], 444);
}

checkDir($UPLOAD_DIR);
if (!move_uploaded_file($tempName, $targetPath)) {
    trace('444Error: Failed to save uploaded image');
    resJson(['error' => 'Failed to save uploaded image'], 444);
}
trace("File id: $fileId");

// フォームから色を取得する
// Get the color from the form
$colorName = $_POST['color_name'] ?? '';
$colorCustom = $_POST['color_custom'] ?? '';
trace("Color name: $colorName, Custom color: $colorCustom");
if ($colorName === 'custom'/*  && !empty($colorCustom) */) {
    $color = colorName($colorCustom) ?? "white";
} else {
    $color = $colorName;
}
trace("Gotten color name: $color");

// 画像を生成する
// Generate an image
$imgUrl = imggen($targetPath, $color);
if (!$imgUrl) {
    trace('444Error: Image generation failed');
    resJson(['error' => 'Image generation failed'], 444);
}

// 画像の保存
// Save the image
$savePath = $OUTPUT_DIR . $fileId . '.jpg';
$imageData = file_get_contents($imgUrl);
checkDir($OUTPUT_DIR);
file_put_contents($savePath, $imageData);

trace("End handling request\n");

// 画像のURLを返す
// Return the image URL
$base64Image = base64_encode($imageData);
resJson(['image_url' => $imgUrl, 'download_url' => "$BASE_URL/$savePath", 'base64_image' => $base64Image]);

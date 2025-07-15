<?php
require_once "./include/helpers.php";
require_once "./include/pngproc.php";
require_once "./include/imggen.php";

// Ensure the request is a POST request
only_post();

// Upload the original image
if(!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    ajax(['error' => 'Image upload failed'], 444);
}
$tempName = $_FILES['image']['tmp_name'];
$fileName = basename($_FILES['image']['name']);
$fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
$fileSizeKB = $_FILES['image']['size'] / 1024; // Size in KB
$targetName = $UPLOAD_DIR . uniqid();
$targetPath = "$targetName.$fileType";

if($fileSizeKB > 2048) { // Limit to 2MB
    ajax(['error' => 'Image size exceeds the limit of 2MB'], 444);
}
if(!move_uploaded_file($tempName, $targetPath)) {
    ajax(['error' => 'Failed to save uploaded image'], 444);
}
if($fileType !== 'png' || !isPngRgba($targetPath)) {
    // Convert to PNG
    $oldPath = $targetPath;
    $targetPath = "$targetName.png";
    convertToPngRgba($oldPath, $targetPath);
    unlink($oldPath); // Remove the old file
}

// Get the color choice
$colorName = $_POST['color_name'] ?? '';
$colorCustom = $_POST['color_custom'] ?? '';
if($colorName === 'custom'/*  && !empty($colorCustom) */) {
    $color = $colorCustom;
} else {
    $color = $colorName;
}

// Generate the image
$imgUrl = imggen($targetPath, $color);
if($imgUrl) {
    ajax(['image_url' => $imgUrl]);
} else {
    ajax(['error' => 'Image generation failed'], 444);
}

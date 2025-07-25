<?php
require_once "./include/helpers.php"

/**
 * index.php
 * 壁の色シミュレーションプレビューのメインページ
 * Main page for wall color simulation preview
 */
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>外壁カラー プレビュー</title>

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

    <!-- Begin: Styles -->
    <link rel="stylesheet" href="<?= asset('style.css') ?>">
    <!-- End: Styles -->
</head>

<body>
    <h1>外壁カラー プレビュー</h1>

    <div class="block-cover" id="processingMessage">
        <span>処理中...</span>
    </div>

    <!-- Begin: Container -->
    <div class="container">

        <div id="alert" class="alert fade-in">
            <span id="alertText"></span>
            <button class="close-btn" onclick="closeAlert()">&times;</button>
        </div>

        <!-- Begin: Image upload form -->
        <form id="imageForm" method="post" action="paint.php">

            <!-- Begin: Image upload part -->
            <div class="form-group">
                <label for="imageUpload">画像をアップロード</label>
                <input type="file" id="imageUpload" accept="image/*" required name="image">
            </div>
            <!-- End: Image upload part -->

            <!-- Begin: Color select part -->
            <div class="form-group">
                <label for="colorPicker">カラーを選択</label>
                <!-- Color name select -->
                <select id="colorName" name="color_name">
                    <option value="red">赤 (Red)</option>
                    <option value="green">緑 (Green)</option>
                    <option value="light green">薄緑 (Light Green)</option>
                    <option value="blue">青 (Blue)</option>
                    <option value="yellow">黄 (Yellow)</option>
                    <option value="magenta">紫 (Magenta)</option>
                    <option value="cyan">水色 (Cyan)</option>
                    <!-- <option value="custom">カスタムカラー</option> -->
                </select>
                <!-- Custom color picker -->
                <input type="color" id="colorPicker" name="color_custom">
            </div>
            <!-- End: Color select part -->

            <!-- Submit button -->
            <button type="submit" id="submitButton" class="btn">プレビューを生成</button>
        </form>
        <!-- End: Image upload form -->

        <!-- Begin: Preview area with original -->
        <div class="preview" id="previewArea">
            <img id="originalImage" src="" alt="元画像" style="display: none;">
            <img id="generatedImage" src="" alt="プレビュー画像" style="display: none;">
        </div>
        <div id="processTime" style="display: none;">処理時間: 0s</div>
        <!-- End: Preview area with original -->

        <!-- Begin: Buttons -->
        <button class="btn fullscreen-btn" id="fullscreenBtn" style="display: none;">全画面表示</button>
        <a class="btn download-btn" id="downloadBtn" style="display: none;" download>ダウンロード</a>
        <!-- End: Buttons -->
    </div>
    <!-- End: Container -->

    <!-- Begin: Scripts -->
    <!-- <script type="text/javascript" src="https://chir.ag/projects/ntc/ntc.js"></script> -->
    <script src="<?= asset('script.js') ?>"></script>
    <!-- End: Scripts -->
</body>

</html>
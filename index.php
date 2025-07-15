<?php require_once "./include/helpers.php" ?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>外壁カラー プレビュー</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('style.css') ?>">
</head>

<body>
    <h1>外壁カラー プレビュー</h1>
    <div class="container">
        <span id="processingMessage">処理中...</span>
        <form id="imageForm" method="post" action="paint.php">
            <div class="form-group">
                <label for="imageUpload">画像をアップロード</label>
                <input type="file" id="imageUpload" accept="image/*" required name="image">
            </div>

            <div class="form-group">
                <label for="colorPicker">カラーを選択</label>
                <select id="colorNames" name="color_name">
                    <option value="#ff0000">赤 (Red)</option>
                    <option value="#00ff00">緑 (Green)</option>
                    <option value="#0000ff">青 (Blue)</option>
                    <option value="#ffff00">黄 (Yellow)</option>
                    <option value="#ff00ff">紫 (Magenta)</option>
                    <option value="#00ffff">水色 (Cyan)</option>
                    <option value="custom">カスタムカラー</option>
                </select>
                <input type="color" id="colorPicker" name="color_custom">
            </div>

            <button type="submit" id="submitButton">プレビューを生成</button>
        </form>

        <div class="preview" id="previewArea">
            <img id="originalImage" src="" alt="元画像" style="display: none;">
            <img id="generatedImage" src="" alt="プレビュー画像" style="display: none;">
        </div>

        <button class="fullscreen-btn" id="fullscreenBtn" style="display: none;">全画面表示</button>
        <button class="download-btn" id="downloadBtn" style="display: none;">ダウンロード</button>

    </div>

    <script src="<?= asset('script.js') ?>"></script>
</body>

</html>
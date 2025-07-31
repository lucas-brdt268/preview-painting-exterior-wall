<?php 
require_once '../include/config.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>アクセスが拒否されました</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: "Hiragino Kaku Gothic ProN", Meiryo, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f9f8f4;
      background-image: url('https://www.transparenttextures.com/patterns/washi.png');
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      text-align: center;
      color: #333;
    }

    .error-container {
      padding: 40px;
      border-radius: 10px;
      background-color: rgba(255, 255, 255, 0.95);
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      max-width: 500px;
    }

    h1 {
      font-size: 80px;
      margin: 0;
      color: #b30000;
    }

    h2 {
      margin: 20px 0 10px;
      font-size: 24px;
      color: #444;
    }

    p {
      font-size: 16px;
      color: #666;
    }

    a.button {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #c0a16b;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    a.button:hover {
      background-color: #a88b5a;
    }
  </style>
</head>
<body>
  <div class="error-container">
    <h1>403</h1>
    <h2>アクセスが拒否されました</h2>
    <p>このページにアクセスする権限がありません。</p>
    <a href="<?=$BASE_URL?>" class="button">ホームに戻る</a>
  </div>
</body>
</html>

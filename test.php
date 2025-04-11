<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>圓形轉場顯示內容</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <style>
        body {
            margin: 0;
            overflow: hidden;
            font-family: Arial, sans-serif;
        }

        /* 主要內容 */
        .main-content {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            padding: 20px;
            background-color: #f9f9f9;
            text-align: center;
        }

        .main-content h1 {
            color: #333;
        }

        .main-content p {
            max-width: 800px;
            margin: 20px auto;
            line-height: 1.6;
        }

        /* GIF 層 */
        .gif-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #fff;
            z-index: 2;
            /* 關鍵：使用 clip-path 來創建圓形遮罩 */
            clip-path: circle(150% at 50% 50%);
        }

        .gif {
            width: 400px;
            height: 300px;
        }
    </style>
</head>
<body>
    <!-- 主要網頁內容 -->
    <div class="main-content">
        <h1>歡迎來到我的網站</h1>
        <p>這是我的網站主要內容！圓形動畫進行時，這些內容會逐漸顯示。</p>
    </div>

    <!-- GIF 層 -->
    <div class="gif-container">
        <img src="img/logo_animation.webp" class="gif" alt="Loading Animation">
    </div>

    <script>
        // 等待一段時間後開始圓形動畫
        setTimeout(() => {
            // 開始圓形遮罩動畫
            anime({
                targets: '.gif-container',
                clipPath: ['circle(150% at 50% 50%)', 'circle(0% at 50% 50%)'],
                duration: 2000,
                easing: 'easeInOutQuad',
                complete: function() {
                    // 動畫完成後移除 GIF 層
                    document.querySelector('.gif-container').style.display = 'none';
                }
            });
        }, 3000);
    </script>
</body>
</html>

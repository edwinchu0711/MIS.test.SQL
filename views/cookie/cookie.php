<?php

if (!defined('BASE_PATH')) {
    $currentPath = $_SERVER['PHP_SELF'];
    $depth = substr_count($currentPath, '/') - 1;
    define('BASE_PATH', str_repeat('../', $depth));
}
// 檢查是否已經引入過 cookie 的資源
if (!defined('cookie_ASSETS_LOADED')) {
    define('cookie_ASSETS_LOADED', true);
    ?>
    <!-- cookie CSS -->
    <link id="cookie-css" rel="stylesheet" href="<?php echo BASE_PATH; ?>css/style-cookie.css">
    <!-- cookie JavaScript -->
    <script>
        // // 檢查是否已載入 cookie.js
        if (typeof cookieJsLoaded === 'undefined') {
            var cookieScript = document.createElement('script');
            const timestamp = new Date().getTime(); // 取得當前時間戳
            cookieScript.src = `<?php echo BASE_PATH; ?>js/script-cookie.js?t=${timestamp}`;
            cookieScript.onload = function() {
                window.cookieJsLoaded = true;
                // 確保 DOM 載入完成後初始化 cookie
                if (document.readyState === 'complete' || document.readyState === 'interactive') {
                    
                } else {
                    document.addEventListener('DOMContentLoaded', initcookie);
                }
            };
            document.head.appendChild(cookieScript);
        }
    </script>
<?php
}

?>



<div id="bottom-fixed">

  <div id="cookie" class="w3-card" <?php if (isset($_SESSION['cookie'])) echo 'style="display : none;"';?> >
    <p id="cookie_p">Do you accept <a href="<?php echo BASE_PATH ; ?>img/Cookie.webp" target="_blank" >cookie</a>?</p>
    <div id="cookie_button"> 
      <button id="cookie_yes" class="btn">YES</button>
      <button id="cookie_no" class="btn">NO</button>
    </div>
  </div>
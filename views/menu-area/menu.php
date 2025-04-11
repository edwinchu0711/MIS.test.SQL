<?php
// 定義基礎路徑常數
if (!defined('BASE_PATH')) {
    $currentPath = $_SERVER['PHP_SELF'];
    $depth = substr_count($currentPath, '/') - 1;
    define('BASE_PATH', str_repeat('../', $depth));
}
// 檢查是否已經引入過 menu 的資源
if (!defined('menu_ASSETS_LOADED')) {
    define('menu_ASSETS_LOADED', true);
    ?>
    <!-- menu CSS -->
    <link id="menu-css" rel="stylesheet" href="<?php echo BASE_PATH; ?>css/style-menu.css">
    <script src="<?php echo BASE_PATH ; ?>js/anime-master/lib/anime.min.js"></script>
    <!-- menu JavaScript -->
    <script>
        // // 檢查是否已載入 menu.js
        if (typeof menuJsLoaded === 'undefined') {
            var menuScript = document.createElement('script');
            const timestamp = new Date().getTime(); // 取得當前時間戳
            menuScript.src = `<?php echo BASE_PATH; ?>js/script-menu.js?t=${timestamp}`;
            menuScript.onload = function() {
                window.menuJsLoaded = true;
                // 確保 DOM 載入完成後初始化 menu
                if (document.readyState === 'complete' || document.readyState === 'interactive') {
                    
                } else {
                    
                }
            };
            document.head.appendChild(menuScript);
        }
    </script>
<?php } ?>

<?php
if (!$result) {
    die("Query failed: " . $conn->error);
}
if ($result->num_rows > 0) {
    $totalRows = $result->num_rows;
    $currentRow = 1;
    echo '<div class="w3-row w3-padding-16 post" id="menu">
                <div class="w3-col l12 w3-padding-large" id = "menu-table-area">
                <h2 id ="menu-title" class="w3-center menu-table-content hide_up">Menu</h2><br>
                <div id="menu-incontent-area">
                <div class="table-img" id="border"></div>
                <div id="menu-namelist">';
    while ($row = $result->fetch_assoc()) {  
        $dishName = $row['dish_name'];
        // $imageUrl = $row['photo_path']; // 假設資料庫有儲存圖片的 URL
        $imageUrl = implode('/', array_map('rawurlencode', explode('/', $row['photo_path'])));

       echo '<div><a href="#'.$row['dish_name'].$row['id'].'"><p class="button-text"><span>'.$row['dish_name'].'</span></p>';
       echo '<div class="table-img" style="background-image: url('.BASE_PATH . $imageUrl.'?t='.time().');"></div></a></div>';
    }
    echo '</div></div></div></div>';
    $result->data_seek(0);  
}

?>




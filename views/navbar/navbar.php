<?php
// 定義基礎路徑常數
$currentPath = $_SERVER['SCRIPT_NAME'];
// 取得母資料夾名稱
$parentFolderName = basename(dirname($currentPath));
// 將母資料夾名稱存為變數
$navbarFolder = $parentFolderName;

if (!defined('BASE_PATH')) {
    $currentPath = $_SERVER['PHP_SELF'];
    $depth = substr_count($currentPath, '/') - 1;
    define('BASE_PATH', str_repeat('../', $depth));
}
// 檢查是否已經引入過 navbar 的資源
if (!defined('NAVBAR_ASSETS_LOADED')) {
    define('NAVBAR_ASSETS_LOADED', true);
    ?>
    <!-- Navbar CSS -->
    <link id="navbar-css" rel="stylesheet" href="<?php echo BASE_PATH; ?>css/style-navbar.css">
    <!-- Navbar JavaScript -->
    <script>
        // // 檢查是否已載入 navbar.js
        if (typeof navbarJsLoaded === 'undefined') {
            var navbarScript = document.createElement('script');
            const timestamp = new Date().getTime(); // 取得當前時間戳
            navbarScript.src = `<?php echo BASE_PATH; ?>js/script-navbar.js?t=${timestamp}`;
            navbarScript.onload = function() {
                window.navbarJsLoaded = true;
                // 確保 DOM 載入完成後初始化 navbar
                if (document.readyState === 'complete' || document.readyState === 'interactive') {
                    initNavbar();
                } else {
                    document.addEventListener('DOMContentLoaded', initNavbar);
                }
            };
            document.head.appendChild(navbarScript);
        }
    </script>
    <?php
}
/***********************每個part分類 ****************************************/
$search = '<div id= "search-button" class=" w3-bar-item w3-button"><img id="search-img"src="'.BASE_PATH.'img/search.webp"></div>';

$small_search = '<div id= "small-search-button" class=" w3-right w3-bar-item w3-button"><img id="small-search-img"src="'.BASE_PATH.'img/search.webp"></div>';

$AllPost = '<a href="'.BASE_PATH.'views/classification/allpost.php" class="w3-bar-item w3-button navtitle">All Post</a>';
$title ='<a href="'.BASE_PATH.'views/index/index.php"><div id="top-title" class="w3-padding-small w3-bar-item" onclick="backtoindex()"><img src="'.BASE_PATH.'img/BaChi%20Foodblog.webp"></div></a>';

// PostgreSQL 兼容：檢查 $result 是否為 PDOStatement 物件，並且有資料
if (isset($result) && $result instanceof PDOStatement && $navbarFolder == 'classification') {
    // 取得結果數量
    $result_count = $result->rowCount();
    
    if ($result_count > 0) {
        $menu = '<div id="menu-button-area" class="w3-bar-item w3-button">
                    <a href="#menu" class="w3-bar-item w3-button navtitle" id="menu-button">Menu</a>
                    <div class="menu-list" id="menu-list">';
        
        // 取得所有結果
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $encoded_path = implode('/', array_map('rawurlencode', explode('/', $row['photo_path'])));
            $menu .= '<a href="#' . $row['dish_name'] . $row['id'] . '" class="w3-bar-item w3-button w3-card w3-padding-large">
                        ' . $row['dish_name'] . '
                        <button class="toggle-preview" data-img="' . BASE_PATH . $row['photo_path'] . '">&#8744;</button>
                      </a>
                      <div class="image-preview" style="display:none;">
                        <div class="menu-preview-img " style="background-image: url('.BASE_PATH.$encoded_path.')">
                        </div>
                      </div>';
        }
        $menu .= '</div></div>';
        
        // 重新執行查詢以獲取相同的結果集
        $result = $pdo->query("SELECT * FROM posts WHERE id IN (" . implode(',', array_column($rows, 'id')) . ")");
    }
    
    if ($result_count > 0) {
        $small_menu = '<div id="small-menu">
            <div class="w3-bar-item w3-button navtitle" id="small-menu-button">Menu</div>
            <div id="small-menu-list">';
        
        // 取得所有結果
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $small_menu .= '<a href="#'.$row['dish_name'].$row['id'].'" class="w3-bar-item w3-button w3-card w3-padding-large">
                    '.$row['dish_name'].'
                </a>';
        }
        $small_menu .= '</div>
        </div>';
        
        // 重新執行查詢以獲取相同的結果集
        $result = $pdo->query("SELECT * FROM posts WHERE id IN (" . implode(',', array_column($rows, 'id')) . ")");
    }
}

$type = '<div id="type-button-area" class="w3-bar-item w3-button">
            <div class="w3-bar-item w3-button navtitle" id="type-button">Food Type</div>
            <div class="type-list" id="type-list">
                <a href="'.BASE_PATH.'views/classification/dishtypepost.php?dishtype=Rice" class="w3-bar-item w3-button w3-card w3-padding-large">
                    Rice
                </a>
                <a href="'.BASE_PATH.'views/classification/dishtypepost.php?dishtype=Noodles" class="w3-bar-item w3-button w3-card w3-padding-large">
                    Noodles
                </a>
                <a href="'.BASE_PATH.'views/classification/dishtypepost.php?dishtype=Drinks" class="w3-bar-item w3-button w3-card w3-padding-large">
                    Drinks
                </a> 
                <a href="'.BASE_PATH.'views/classification/dishtypepost.php?dishtype=Meat" class="w3-bar-item w3-button w3-card w3-padding-large">
                    Meat
                </a>
                <a href="'.BASE_PATH.'views/classification/dishtypepost.php?dishtype=Fast Food and Fried Food" class="w3-bar-item w3-button w3-card w3-padding-large">
                    Fast Food <br>and Fried Food
                </a>
                <a href="'.BASE_PATH.'views/classification/dishtypepost.php?dishtype=Soup" class="w3-bar-item w3-button w3-card w3-padding-large noodle">
                    Soup
                </a>
                <a href="'.BASE_PATH.'views/classification/dishtypepost.php?dishtype=Dessert" class="w3-bar-item w3-button w3-card w3-padding-large steak">
                    Dessert                </a>
                <a href="'.BASE_PATH.'views/classification/dishtypepost.php?dishtype=Others" class="w3-bar-item w3-button w3-card w3-padding-large latte">
                    Others
                </a>
            </div>
        </div>';
$login = '<a href="' . BASE_PATH . 'views/login/login.php?index=1" class="w3-bar-item w3-button navtitle">Login</a>';
$small_login = '<div id="small-login"><a href="' . BASE_PATH . 'views/login/login.php?index=1" class="w3-bar-item w3-button navtitle" id="small-login-button">Login</a></div>';

$small_type = '<div id="small-type">
                    <div class="w3-bar-item w3-button navtitle" id="small-type-button">Food Type</div>
                <div class="" id="small-type-list">
                <a href="'.BASE_PATH.'views/classification/dishtypepost.php?dishtype=Rice"class="w3-bar-item w3-button w3-card w3-padding-large">
                    Rice
                </a>
                <a href="'.BASE_PATH.'views/classification/dishtypepost.php?dishtype=Noodles" class="w3-bar-item w3-button w3-card w3-padding-large">
                    Noodles
                </a>
                <a href="'.BASE_PATH.'views/classification/dishtypepost.php?dishtype=Drinks" class="w3-bar-item w3-button w3-card w3-padding-large">
                    Drinks
                </a> 
                <a href="'.BASE_PATH.'views/classification/dishtypepost.php?dishtype=Meat" class="w3-bar-item w3-button w3-card w3-padding-large">
                    Meat
                </a>
                <a href="'.BASE_PATH.'views/classification/dishtypepost.php?dishtype=Fast Food and Fried Food" class="w3-bar-item w3-button w3-card w3-padding-large">
                    Fast Food <br>and Fried Food
                </a>
                <a href="'.BASE_PATH.'views/classification/dishtypepost.php?dishtype=Soup" class="w3-bar-item w3-button w3-card w3-padding-large noodle">
                    Soup
                </a>
                <a href="'.BASE_PATH.'views/classification/dishtypepost.php?dishtype=Dessert" class="w3-bar-item w3-button w3-card w3-padding-large steak">
                    Dessert                </a>
                <a href="'.BASE_PATH.'views/classification/dishtypepost.php?dishtype=Others" class="w3-bar-item w3-button w3-card w3-padding-large latte">
                    Others
                </a>
            </div>
            </div>';

$Small_AllPost = '<a href="'.BASE_PATH.'views/classification/allpost.php" class="w3-bar-item w3-button navtitle">All Post</a>';
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
    $user = '<div id="user-button-area" class="w3-bar-item w3-button">
            <div class="w3-bar-item w3-button navtitle" id="user-button">Welcome ' . htmlspecialchars($_SESSION['username']) . '!!</div>
            <div class="user-list" id="user-list">
                <a href="' . BASE_PATH . 'views/account/setting/setting.php" class="w3-bar-item w3-button w3-card w3-padding-large">Setting</a>
                <a href="'.BASE_PATH.'views/classification/userpost.php?username='.htmlspecialchars($_SESSION['username']).'" class="w3-bar-item w3-button w3-card w3-padding-large">My Post</a>
                <a href="' . BASE_PATH . 'views/login/log out.php" class="w3-bar-item w3-button w3-card w3-padding-large">Sign out</a>
            </div>
        </div>';
    $small_user = '<div class="w3-bar-item w3-button navtitle" id="small-user-button">Welcome '.htmlspecialchars($_SESSION['username']).'!!</div>
                        <div id="small-user-button-area" class="">
                        <div id="small-user-list" class="small-user-list">
                            <a href="'.BASE_PATH.'views/account/setting/setting.php" class="w3-bar-item w3-button w3-card w3-padding-large">Setting</a>
                            <a href="'.BASE_PATH.'views/classification/userpost.php?username='.htmlspecialchars($_SESSION['username']).'" class="w3-bar-item w3-button w3-card w3-padding-large">My Post</a>
                            <a href="'.BASE_PATH.'views/login/log out.php" class="w3-bar-item w3-button w3-card w3-padding-large">Sign out</a>
                        </div>
                    </div>';
    }

// *************************************以上為每個按鈕分類***************************************************************/

/************************輸出的不分 (用$data) ********************************/
$data = '<div class="w3-top" id="navbar">'; //****************最大的div********************/

$data .= ' <div id = "all_navbar" class="w3-bar w3-Tea-color w3-padding w3-card" style="letter-spacing:4px;">'
            .$title.
            //*********以下是navbar右邊的部分****************/
            '<div class=" w3-hide-small w3-right" id = "navbar_right">';
            if ($parentFolderName != 'index'){
                $data .= $search;
            }
            $data .= $AllPost;
            if ($navbarFolder == 'classification' && isset($menu)){
                $data .= $menu;
            }
            $data .= $type;
            //*********************判斷是否有登入********************/
            if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
                $data .= $user;
            } else {
                $data .= $login;
            }
$data .= '</div>';
//***************************************以下是navbar縮小時會出現的code***********************************************//
$data .= '<button id ="small-state-button" class="w3-right w3-bar-item w3-button"><img id="more-list" w3-button" src="'. BASE_PATH .'img/list.png"></button>';
            if ($parentFolderName != 'index'){
                $data .= $small_search;
            }
            $data .= '<div id = "small-list">
                '.$Small_AllPost;
                if ($navbarFolder == 'classification' && isset($small_menu)){
                    $data .= $small_menu;
                }
                $data .= $small_type;
                //*********************判斷是否有登入********************/
                if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
                    $data .= $small_user;
                } 
                else {
                    $data .= $small_login;
                }
                $data .= '</div></div></div>'; //****************將所有div閉合********************/

// 如果使用 PostgreSQL，重新準備結果集
if (isset($result) && $result instanceof PDOStatement) {
    // 重新執行查詢
    $result = $pdo->query("SELECT * FROM posts");
}

$search_spare = '<div id="search-spare">
    <div class="search-container"> 
        <button id="search-close" class="search-close-btn">&times;</button>
        <div id="search-in">
            <div class="search-header">
                <input type="text" id="search-input" placeholder="Search">
            </div>
            <div class="filter-container">
                <div class="filter-tags-wrapper">
                    <div class="filter-tags">
                        <button class="filter-tag">Rice</button>
                        <button class="filter-tag">Noodles</button>
                        <button class="filter-tag">Drinks</button>
                        <button class="filter-tag">Meat</button>
                        <button class="filter-tag">Fast Food and Fried Food</button>
                        <button class="filter-tag">Soup</button>
                        <button class="filter-tag">Dessert</button>
                        <button class="filter-tag">Others</button>
                        <!-- 更多標籤... -->
                    </div>
                </div>
            </div>
        </div>
        <div id="search-gap">
            </div>
        <div id="search-results-click"class="search-results">
            <div class="search-loading" style="display: none;">
                <div class="spinner"></div>
            </div>
            <div class="search-content"></div>
        </div>
    </div>
</div>';

echo $data;
if ($parentFolderName != 'index'){
    echo $search_spare; // 確保輸出搜尋視窗
}
?>

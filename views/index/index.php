<?php
  if (!defined('BASE_PATH')) {
    $currentPath = $_SERVER['PHP_SELF'];
    $depth = substr_count($currentPath, '/') - 1;
    define('BASE_PATH', str_repeat('../', $depth));
  }
  session_start();
  $timestamp = time();
  include BASE_PATH.'views/post/database.php?n='.$timestap ;
?>
<!DOCTYPE html>
<html>
<head>
<title>BaChi FoodBlog</title>
<meta charset="UTF-8">
<!--------------------------------PWA------------------------------>
<link rel="manifest" id ="manifest" href="./manifest.json">
<meta name="theme-color" content="#000000">
<!-- iOS 支援 -->
<link rel="apple-touch-icon" href="<?php echo BASE_PATH ; ?>img/cookie.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">

<meta name="mobile-web-app-capable" content="yes">

<script>
  console.log('腳本開始執行');
  if ('serviceWorker' in navigator) {
    console.log('瀏覽器支援 ServiceWorker');
    window.addEventListener('load', () => {
      console.log('頁面已載入');
      navigator.serviceWorker.register('./service-worker.js')
        .then(registration => {
          console.log('ServiceWorker 註冊成功:', registration.scope);
        })
        .catch(error => {
          console.log('ServiceWorker 註冊失敗:', error);
        });
    });
  } else {
    console.log('此瀏覽器不支援 ServiceWorker');
  }
</script>





<!-- <script type="application/json" id="manifest">
{
  "name": "BachiWeb",
  "short_name": "MISSQL",
  "description": "應用描述",
  "start_url": "/views/index/",
  "scope": "/views/index/",
  "display": "standalone",
  "background_color": "#ffffff",
  "theme_color": "#000000",
  "icons": [
      {
          "src": "img/cookie.png",
          "sizes": "192x192",
          "type": "image/png"
      },
      {
          "src": "img/cookie.png",
          "sizes": "512x512",
          "type": "image/png"
      }
  ]
}

</script> -->
<script>
  // 創建 Blob 並生成 URL
  const manifestContent = document.getElementById('manifest').textContent;
  const manifestBlob = new Blob([manifestContent], {type: 'application/json'});
  const manifestURL = URL.createObjectURL(manifestBlob);
  
  // 創建並添加 manifest link
  const link = document.createElement('link');
  link.rel = 'manifest';
  link.href = manifestURL;
  document.head.appendChild(link);
</script>


<!--------------------------------PWA------------------------------>





<link rel="shortcut icon" href="<?php echo BASE_PATH ; ?>img/Cookie.webp">
<!--------------------- 字體 ----------------------------->
<link rel="preconnect" href="https://fonts.googleapis.com">
<script src="<?php echo BASE_PATH ; ?>js/anime-master/lib/anime.min.js"></script>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Satisfy&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo BASE_PATH ; ?>css/w3.css?v=initial"> <!-- 原本網頁用的CSS -->
<!-- 預設載入CSS -->
<link id="dynamic-css" rel="stylesheet" href="<?php echo BASE_PATH ; ?>css/style-index.css?v=initial">
<!-- 使用defer載入JavaScript，避免阻塞 -->
<script defer>
    document.addEventListener("DOMContentLoaded", function() {
        const timestamp = new Date().getTime();
        document.getElementById("dynamic-css").href = `<?php echo BASE_PATH ; ?>css/style-index.css?v=${timestamp}`;
        const script = document.createElement("script");
        script.src = `<?php echo BASE_PATH ; ?>js/script-index.js?v=${timestamp} defer`;
        document.head.appendChild(script);
    });
    
</script>
</head>
<body>
<!-- style="visibility:hidden;" -->
<div class="container">
  <div id="preloader" style="z-index : 30;" ></div>
  <div class="circle-mask"></div>
</div>

<div id="hidebody" style="visibility:hidden;">
<div id="top"></div>
<!-- 讓每次重新整理都會跑到最上面 -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
      setTimeout(function(){
          const topElement = document.querySelector('#top');
          if (topElement) {
            topElement.scrollIntoView({behavior:"auto"});
          }
      },200); //0.2秒後往上滑動



        document.documentElement.style.scrollBehavior = "smooth";
  });
</script>


<!-- Navbar (sit on top) -->
<div id="index-navbar">
<?php include BASE_PATH . 'views/navbar/navbar.php'; ?>
</div>



<!-- Header -->
<header class="" style="max-width:100%;" id="header">
  <div class="" id="header-img" style="background-image: url('<?php echo BASE_PATH ; ?>img/headerBackground.webp');"  alt="Hamburger Catering"></div>
  <div id="headerWhite">
    <img id="header-logo" src='<?php echo BASE_PATH ; ?>img/BaChi Foodblog.webp'>
    <img id="header-line"  src='<?php echo BASE_PATH; ?>img/subtitle.png'>
    <div id ="index-button-area">
      <div id="first-part">

        <a href="#NewPost" class = "index-button">
          <div class= "introduction">See latest posts</div>
          <div class ="button-icon">
            <div id ="newPost" class = "button-img" style="background-image: url('<?php echo BASE_PATH ; ?>img/NEW.webp');"></div>
          </div>
          <p class = "button-text">Latest Post</p>
        </a>
        <a href="#Search" class = "index-button" id="search-bigbutton">
          <div class= "introduction">Search for post</div>
          <div class ="button-icon">
            <div id ="search"  class = "button-img" style="background-image: url('<?php echo BASE_PATH ; ?>img/search.webp');"></div>
          </div>
          <p class = "button-text">Search</p>
        </a>
      </div>
      <div id = "second-part">
        <?php 
          if (isset($_SESSION['username']) && isset($_SESSION['id'])){
            echo '
              <a href="'.BASE_PATH.'views/classification/userpost.php?username='.$_SESSION['username'].'" class = "index-button">
                <div class= "introduction">See my post</div>
                <div class ="button-icon">
                  <div id ="mypost" class = "button-img" style="background-image: url(\''.BASE_PATH.'img/myPost.webp\');"></div>
                </div>
                <p class = "button-text">My Post</p>
              </a>
            ';

          }
          else{
            echo '
              <a href="'.BASE_PATH.'views/login/login.php?index=1" class="index-button">
                <div class= "introduction">Login to post</div>
                <div class="button-icon">
                  <div id="login" class="button-img" style="background-image: url(\''.BASE_PATH.'img/login.webp\');"></div>
                </div>
                <p class="button-text">Login</p>
              </a>
            ';
          }?>
            
        <a href="<?php echo BASE_PATH ; ?>/views/footer/aboutus.php" class = "index-button">
          <div class= "introduction">More about makers</div>
          <div class ="button-icon">
            <div id ="aboutUs" class = "button-img" style="background-image: url('<?php echo BASE_PATH ; ?>img/aboutUs.webp');"></div>
          </div>
          <p class = "button-text">About Us</p>
        </a>
      </div>
    </div>
  </div>
</header>
<div id="changeBackground"></div>
<!-- Page content -->
  <div id="NewPost-area">
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>
    <div class="row"><div class="el"></div></div>

    <?php
// 直接在這裡建立資料庫連接，不依賴外部 $conn

try {
    // SQLite資料庫連接
    $db_path = BASE_PATH.'MIS.db3'; // 請替換為您的db3檔案路徑
    $conn = new PDO("sqlite:$db_path");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 執行查詢 (SQLite語法與PostgreSQL基本相同)
    $stmt = $conn->query("SELECT * FROM posts ORDER BY created_at DESC limit 12");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 創建一個模擬的 $result 對象，以便與原始代碼兼容
    class MockResult {
        public $num_rows;
        private $data = [];
        private $position = 0;
        
        public function __construct($data) {
            $this->data = $data;
            $this->num_rows = count($data);
        }
        
        public function fetch_assoc() {
            if ($this->position < count($this->data)) {
                return $this->data[$this->position++];
            }
            return null;
        }
    }
    
    // 創建模擬的結果對象
    $result = new MockResult($posts);
    
    // 現在 $result 可以像原來的 mysqli 結果一樣使用
    // $result->num_rows 和 $result->fetch_assoc() 都可以正常工作
    
} catch (PDOException $e) {
    // 創建一個空的結果對象，避免後續代碼出錯
    class EmptyResult {
        public $num_rows = 0;
        public function fetch_assoc() { return null; }
    }
    $result = new EmptyResult();
    
    // 記錄錯誤
    error_log("資料庫錯誤: " . $e->getMessage());
    echo "<!-- 資料庫連接或查詢錯誤: " . $e->getMessage() . " -->";
}

// 以下是原始代碼，現在可以正常使用 $result
if ($result->num_rows > 0) {
    echo '<div id = "food_marquee">';
    echo '<div id="NewPost"></div>';
    echo '<div id="marquee_title">Latest Posts</div>';
    echo '<div id = "all_cards">';
    while ($row = $result->fetch_assoc()) {
        $encoded_path = implode('/', array_map('rawurlencode', explode('/', $row['photo_path'])));
        echo '<a href="'.BASE_PATH.'views/classification/newpost.php#'.$row['dish_name'].$row['id'].'" style="text-decoration: none;">'; // 使用動態連結
        echo '<div class="dish_card" id="'.$row['dish_name'].$row['id'].'">
                <div class="dish_img" style="background-image: url('.BASE_PATH . $encoded_path .');"></div>
                <div class="dish_name"><p class="dish_name_p">'.$row['dish_name'].'</p></div>
              </div>';
        echo '</a>';
    }
    echo '</div></div>';
    echo '<div id="left_right_button">
            <img src="'.BASE_PATH.'img/left_button.webp" id = "left_button" class="marquee_change_button">
            <img src="'.BASE_PATH.'img/right_button.webp" id = "right_button" class="marquee_change_button">
          </div>';
    echo '</div>';
}
?>


  </div>
<!-- search -->

  <div id="search-index-spare">
      <div class="search-index-container">
          <div class="search-index-header">
              <input type="text" id="search-index-input" placeholder="Search">
              <button id="search-index-close" class="search-close-btn">&times;</button>
          </div>
          <div class="search-index-filters">
            <!-- 這裡可以放置篩選器、標籤或其他額外的搜尋選項 -->
            <div class="filter-tags">
                <span class="filter-tag">Rice</span>
                <span class="filter-tag">Noodles</span>
                <span class="filter-tag">Drinks</span>
                <span class="filter-tag">Meat</span>
                <span class="filter-tag">Fast Food and Fried Food</span>
                <span class="filter-tag">Soup</span>
                <span class="filter-tag">Dessert</span>
                <span class="filter-tag">Others</span>
            </div>
        </div>
          <div class="search-index-results">
              <div class="search-index-loading" style="display: none;">
                  <div class="index-spinner"></div>
              </div>
              <div class="search-index-content"></div>
          </div>
      </div>
  </div>

  <!----------------- 食物跑馬燈 -------------------->
   

  

<!-- Footer -->
<footer id="footer-end" class="w3-center  w3-padding-32">
  <?php include BASE_PATH.'views/footer/footer.php'; ?>
</footer>


<!-- 搜尋文章 -->
<div id="search-spare">
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
</div>
  

</body>
</html>
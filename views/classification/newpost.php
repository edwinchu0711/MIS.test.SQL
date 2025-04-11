<?php
  if (!defined('BASE_PATH')) {
    $currentPath = $_SERVER['PHP_SELF'];
    $depth = substr_count($currentPath, '/') - 1;
    define('BASE_PATH', str_repeat('../', $depth));
  }
  session_start();
  include BASE_PATH.'views/post/database.php';
?>
<!DOCTYPE html>
<html>
<head>
<title>BaChi FoodBlog</title>
<meta charset="UTF-8">
<link rel="shortcut icon" href="<?php echo BASE_PATH ; ?>img/Cookie.webp">
<!--------------------- 字體 ----------------------------->
<link rel="preconnect" href="https://fonts.googleapis.com">
<script src="<?php echo BASE_PATH ; ?>js/anime-master/lib/anime.min.js"></script>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo BASE_PATH ; ?>css/w3.css?v=initial"> <!-- 原本網頁用的CSS -->
<!-- 預設載入CSS -->
<link id="dynamic-css" rel="stylesheet" href="<?php echo BASE_PATH ; ?>css/style-dishtypepost.css?v=initial">
<!-- 使用defer載入JavaScript，避免阻塞 -->
<script defer>
    document.addEventListener("DOMContentLoaded", function() {
        const timestamp = new Date().getTime();
        document.getElementById("dynamic-css").href = `<?php echo BASE_PATH ; ?>css/style-dishtypepost.css?v=${timestamp}`;
        const script = document.createElement("script");
        script.src = `<?php echo BASE_PATH ; ?>js/script-dishtypepost.js?v=${timestamp} defer`;
        document.head.appendChild(script);
    });
    
</script>
</head>
<body>
<div class="container">
  <div id="preloader" ></div>
  <div class="circle-mask"></div>
</div>

<div id="hidebody">
<div id="top"></div>


<!-- Navbar (sit on top) -->
<?php 
$dishType = $_GET['dishtype'];
$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC limit 12");
include BASE_PATH.'views/navbar/navbar.php'; 
?>



<!-- Header -->
<header class="w3-display-container w3-content w3-wide" style="max-width:100%;" id="home">
  <img class="w3-image" id="header-img" src="<?php echo BASE_PATH ; ?>img/headerBackground.webp" alt="Hamburger Catering">
  <img id="header-titleBG" src="<?php echo BASE_PATH; ?>img/headerTitle.png">
  <div id="header-title"><p>Latest Posts</p><div>
  </header>
<div class="image-border"></div>

  <?php include BASE_PATH.'views/menu-area/menu.php' ; ?>




 



  <?php
    include BASE_PATH.'views/post/readpost.php'; //讀取貼文
  if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
    include BASE_PATH.'views/post/addpost.php'; //新增貼文
  }
  else{
    include BASE_PATH .'views/post/loginSign.php';
  }

  ?>


<!-- Footer -->
<footer id="footer-end" class="w3-center  w3-padding-32">
  <?php include BASE_PATH.'views/footer/footer.php'; ?>
</footer>
<a id="arrow-box" href="#top" ><img id="arrow" class = "hidden" src="<?php echo BASE_PATH ; ?>img/arrow.webp" ></a>

<?php include BASE_PATH.'views/cookie/cookie.php'; ?>

</body>
</html>
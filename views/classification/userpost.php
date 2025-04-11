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
<link id="dynamic-css" rel="stylesheet" href="<?php echo BASE_PATH ; ?>css/style-userpost.css?v=initial">
<!-- 使用defer載入JavaScript，避免阻塞 -->
<script defer>
    document.addEventListener("DOMContentLoaded", function() {
        const timestamp = new Date().getTime();
        document.getElementById("dynamic-css").href = `<?php echo BASE_PATH ; ?>css/style-userpost.css?v=${timestamp}`;
        const script = document.createElement("script");
        script.src = `<?php echo BASE_PATH ; ?>js/script-userpost.js?v=${timestamp} defer`;
        document.head.appendChild(script);
    });
    
</script>
</head>
<body>
<div class="container">
  <div id="preloader" ></div>
</div>

<div id="hidebody">
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
<?php 
  $userpost = $_GET['username'];
  $result = $conn->query("SELECT * FROM posts WHERE username='$userpost'");
  include BASE_PATH.'views/navbar/navbar.php'; 
  ?>



<!-- Header -->
<?php
  if (isset($_GET['username'])) {
      $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
      $stmt->bind_param("s", $_GET['username']);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $username = $row['username'];
            $photoPath = $row['photoPath'];
            $backgroundPath = $row['BGpath'];
            $ig = $row['ig'];
            $phoneNumber = $row['phoneNumber'];
            $introduction = $row['Introduction'];
          }
      } 
      $stmt->close();
  } 
  
  


?>
<header class="w3-display-container w3-content w3-wide" style="max-width:100%;" id="header">
  <div class="w3-image" id="header-img" style="background-image: url('<?php echo BASE_PATH . $backgroundPath.'?t='.time(); ?>');" alt="User background"></div>
  <div  id = "profileImg" style="background-image: url('<?php echo BASE_PATH . $photoPath.'?t='.time(); ?>')"></div>
  <div id = "profile-content">
      <h1><?php echo $username ; ?></h1>
      <div class = "w3-text-grey" id = "introduction"><?php echo $introduction ; ?></div>
  </div>
</header>









<?php 
if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['username'] == $_GET['username']) {
  include BASE_PATH.'views/post/addpost.php'; //新增貼文
}
  $result = $conn->query("SELECT * FROM posts WHERE username='$userpost' ORDER BY created_at DESC");
  include BASE_PATH.'views/menu-area/menu.php' 
?>







  <?php
  
  include BASE_PATH.'views/post/readpost.php'; //讀取貼文
  

  
?>
<!-- Footer -->
<footer id="footer-end" class="w3-center  w3-padding-32">
  <?php include BASE_PATH.'views/footer/footer.php'; ?>
</footer>
<a id="arrow-box" href="#top" ><img id="arrow" class = "hidden" src="<?php echo BASE_PATH ; ?>img/arrow.webp" ></a>

<?php include BASE_PATH.'views/cookie/cookie.php'; ?>

</body>
</html>
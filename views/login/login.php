<?php
session_start();
if (!defined('BASE_PATH')) {
    $currentPath = $_SERVER['PHP_SELF'];
    $depth = substr_count($currentPath, '/') - 1;
    define('BASE_PATH', str_repeat('../', $depth));
}
if (isset($_SESSION['username']) && isset($_SESSION['id'])){
    header("Location:".BASE_PATH."index.html");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<!-- 預先加載 w3.css -->
<link rel="stylesheet" href="<?php echo BASE_PATH ; ?>/css/w3.css">
<link id="dynamic-css" rel="stylesheet" href="<?php echo BASE_PATH ; ?>/css/w3.css">

<!-- 預先加載 style.css -->
<link rel="preload" href="<?php echo BASE_PATH ; ?>/css/style-login.css" as="style" onload="this.onload=null;this.rel='stylesheet'">

<title>BaChi FoodBlog</title>
<meta charset="UTF-8">
<link rel="shortcut icon" href="<?php echo BASE_PATH ; ?>/img/Cookie.webp">
<!--------------------- 字體 ----------------------------->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- 使用defer載入JavaScript，避免阻塞 -->
<script defer>
    document.addEventListener("DOMContentLoaded", function() {
        // 取得當前時間戳
        const timestamp = new Date().getTime();
        // 更新CSS檔案的時間戳參數
        document.getElementById("dynamic-css").href = `<?php echo BASE_PATH ; ?>/css/style-login.css?v=${timestamp}`;
        // 動態載入JavaScript檔案並加上時間戳
        const script = document.createElement("script");
        script.src = `<?php echo BASE_PATH ; ?>/js/script-login.js?v=${timestamp} defer`;
        document.head.appendChild(script);
    });
    
</script>
<style>
        body {
            opacity: 0;
        }
</style>
<?php
        $content = "" ;
        if (isset($_GET['index'])){
            $content = '<div id="preloader"></div>';
            echo $content ;
        }
        
    ?>
<script defer>
    window.addEventListener('load', function() {
    document.body.style.opacity = '1';
    preloader(); // 如果有其他初始化代碼
});

</script>
</head>
<body>



<!-- Navbar (sit on top) -->
<?php include BASE_PATH.'views/navbar/navbar.php'; ?>

<div id="body-area" class="inBody">
    <h1>Login</h1>
    <div style="width :100%;  display: flex;justify-content: center;">
        <div class="w3-col l12 w3-padding-large" id="login-form">
        <?php
            $content ="";
            if (isset($_GET['error'])){
                $content = '<p class="error">'. $_GET['error'] . ' </p> ';
            }
            else{
                
            }
            echo $content ;
        ?>
            <form action="login-check.php" method="post">
                <p class="input" ><span>Username : </span>  <input type="text" placeholder="Name" name="username"></input></p>
                <p class="input"><span>Password : </span><input type="password" placeholder="Password" name = "password"></input></p>
                <p class="input" id="ask">Don't have an account?<a href="signup.php">Sign up</a></p>
                <p><button class="ani_button w3-light-grey w3-section" data-hover="Login!" type="submit">
                    <span class="text">Submit</span>
                    <span class = "edge"></span>
                    <span class = "edge"></span>
                    <span class = "edge"></span>
                    <span class = "edge"></span>
                </button>
                </p>
            </form>
        </div>
    </div>




</div>
<footer id="footer-end" class="w3-center  w3-padding-32 inBody">
    <?php include '../footer/footer.php'; ?>
</footer>



</body>
</html>
<?php
      session_start();
      if (!defined('BASE_PATH')) {
        $currentPath = $_SERVER['PHP_SELF'];
        $depth = substr_count($currentPath, '/') - 1;
        define('BASE_PATH', str_repeat('../', $depth));
    }

?> 




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BaChi FoodBlog</title>
  <link rel="shortcut icon" href="<?php echo BASE_PATH ; ?>img/Cookie.webp">
  <link rel="stylesheet" href="<?php echo BASE_PATH ;?>css/style-aboutus.css">
  <link rel="stylesheet" href="<?php echo BASE_PATH ;?>css/w3.css">
    
</head>
<body>


<!-- Navbar (sit on top) -->

  <?php include BASE_PATH . 'views/navbar/navbar.php'; ?>



<div id="preloader" ></div>
<script>
var loader = document.getElementById("preloader")
window.addEventListener("load", function(){
    setTimeout(function(){
        loader.style.transition =  "opacity 1s ease";
        loader.style.opacity = "0";
        document.getElementById("hidebody").style.visibility = 'visible';
    }, 1500); // 1500毫秒 = 1.5秒
    setTimeout(function(){
        loader.style.display = "none";
    }, 2500);

});
</script>
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
      },200); 
        document.documentElement.style.scrollBehavior = "smooth";
  });
</script>




  <header>
    <h1 class="title">ABOUT US</h1>
  </header>

  <section class="photo-gallery">
    <div class="card">
      <img src="<?php echo BASE_PATH ; ?>/img/ABOUTEDWIN.jpg" alt="Wayne">
      <h3>Wayne</h3>
      <p>What did I do:</p>
      <ul class="content">
        <li>Login system</li>
        <li>Account settings</li>
        <li>User posts (My posts)</li>
        <li>Homepage creation</li>
        <li>Program integration</li>
        <li>Logo</li>
        <li>Video demo</li>
       </ul>
    </div>
    <div class="card">
      <img src="<?php echo BASE_PATH ; ?>/img/ABOUTSUNNY.jpg" alt="Sunny">
      <h3>Sunny</h3>
      <p>What did I do:</p>
       <ul class="content">
        <li>Add posts</li>
        <LI>Edit delete posts</li>
        <LI>Preview posts</LI>
        <LI>Read posts</LI>
        <LI>PPT maker</LI>
        <LI>Presentation speaker</LI>
       </ul>
       <br>
    </div>
    <div class="card">
      <img src="<?php echo BASE_PATH ; ?>/img/ABOUTTAO.jpg" alt="Osas">
      <h3>Osas</h3>
      <p>What did I do:</p>
      <ul class="content">
        <li>Footer</li>
        <li>About us</li>
        <li>Menu</li>
        <li>Menu list preview</li>
        <li>Design</li>
       </ul>
       <br>
       <br>
       <br>
    </div>
    <div class="card">
      <img src="<?php echo BASE_PATH ; ?>/img/ABOUTCWT.jpg" alt="CWT">
      <h3>Waiting</h3>
      <p>What did I do:</p>
       <ul class="content">
        <li>Navbar optimization</li>
        <LI>Sorting functionality</LI>
        <LI>Search bar</LI>
        <li>Cookie optimization</li>
       </ul>
       <br>
       <br>
       <br>
       <br>
    </div>
  </section>

  <footer id="footer-end" class="w3-center  w3-padding-32">
  <?php include BASE_PATH.'views/footer/footer.php'; ?>
</footer>

<?php include BASE_PATH.'views/cookie/cookie.php'; ?>
</body>
</html>


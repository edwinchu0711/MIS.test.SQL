

<?php
// 在 footer.php 中
$currentPath = $_SERVER['PHP_SELF'];
$depth = substr_count($currentPath, '/') - 1;
$prefix = str_repeat('../', $depth);?>
<link id="footer-css" rel="stylesheet" href="<?php echo $prefix;?>'../../css/style-footer.css?">



<?php


echo '
<footer id="footer">
    <div id="footer-links">
        <a href="https://www.instagram.com/no_rainy_day._.0619/profilecard/?igsh=ejZ5MjJiajZvaHk4" class="footer-link">CONTACT US</a>
        <span class="divider">|</span>
        <a href="' . BASE_PATH . 'views/footer/aboutus.php" class="footer-link">ABOUT US</a>
    </div>
    <p id="footer-text">Powered by <a href="https://www.w3schools.com/w3css/default.asp" title="W3.CSS" target="_blank" class="w3-hover-text-green">W3.css</a></p>
</footer>';
?>

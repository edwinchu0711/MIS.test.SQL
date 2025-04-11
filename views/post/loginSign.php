<?php
    if (!defined('BASE_PATH')) {
        $currentPath = $_SERVER['PHP_SELF'];
        $depth = substr_count($currentPath, '/') - 1;
        define('BASE_PATH', str_repeat('../', $depth));
    }
    echo '
    <div id="login-sign" class="w3-container w3-content w3-center">
            <p id="login-content">
            Please <a href="'.BASE_PATH.'views/login/signup.php">sign up</a>
            or <a href="'.BASE_PATH.'views/login/login.php">log in</a> to post your content.
            </p>
    </div>';
?>
<style>
    #login-sign{
        border: 3px dashed rgba(207, 128, 30, 0.938);
        width: 80%;
        height: 160px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 30px;
        margin-top: 10px;
        font-size: 20px;
        a{
            color : rgba(0, 51, 102, 1);
        }
    }
</style>

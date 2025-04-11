<?php
if (!defined('BASE_PATH')) {
    $currentPath = $_SERVER['PHP_SELF'];
    $depth = substr_count($currentPath, '/') - 1;
    define('BASE_PATH', str_repeat('../', $depth));
}

session_start();
unset($_SESSION['username']);
unset($_SESSION['name']);
unset($_SESSION['id']);
header("Location:".BASE_PATH."index.html");
?>
<?php
session_start();
include 'database.php';

if (!defined('BASE_PATH')) {
    $currentPath = $_SERVER['PHP_SELF'];
    $depth = substr_count($currentPath, '/') - 1;
    define('BASE_PATH', str_repeat('../', $depth));
}

$error_message = "";
$success_message = "";

// 確認用戶是否登入
if (!isset($_SESSION['username'])) {
    $error_message = "You must be logged in to delete a post.";
    header('Location: ' . BASE_PATH . 'views/login/login.php');
    exit;
}

$username = $_SESSION['username'];
$post_id = intval($_GET['id'] ?? 0);

// 驗證貼文是否存在且屬於目前用戶，並讀取圖片路徑
$stmt = $conn->prepare("SELECT id, photo_path FROM posts WHERE id = ? AND username = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("is", $post_id, $username);
$stmt->execute();
$stmt->bind_result($found_post_id, $photoPath);
$stmt->fetch();
$stmt->close();

if (!$found_post_id) {
    $error_message = "Post not found or unauthorized access.";
    header('Location: ' . BASE_PATH . 'index.html');
    exit;
}

// 刪除圖片文件
$fullPath = BASE_PATH . $photoPath;
if ($photoPath && file_exists($fullPath)) {
    unlink($fullPath);
    if (!unlink($fullPath)) {
        $error_message = "Failed to delete image file.";
        echo $error_message ;
        exit;
    }
}

// 刪除貼文
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $post_id);

if ($stmt->execute()) {
    $success_message = "Post and associated image deleted successfully!";
    header('Location: ' . BASE_PATH . 'views/classification/userpost.php?username='.$_SESSION['username'].'&message=' . urlencode($success_message));
    exit;
} else {
    $error_message = "Failed to delete post. Please try again.";
}
$stmt->close();
?>

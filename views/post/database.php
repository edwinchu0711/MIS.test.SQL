<?php
// $host = 'localhost'; // MySQL 主機
// $username = 'root'; // MySQL 使用者名稱
// $password = ''; // MySQL 密碼
// $database = 'if0_37642403_message_board'; // 資料庫名稱


$host = 'sql109.infinityfree.com'; // MySQL 主機
$username = 'if0_38718843'; // MySQL 使用者名稱
$password = 'hb4F1GuhFfBTBJ'; // MySQL 密碼
$database = 'if0_38718843_MIS'; // 資料庫名稱

$conn = new mysqli($host, $username, $password, $database);
$conn->set_charset("utf8mb4");

// 驗證連接是否成功
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
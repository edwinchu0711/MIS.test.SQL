<?php
// $host = 'localhost'; // MySQL 主機
// $username = 'root'; // MySQL 使用者名稱
// $password = ''; // MySQL 密碼
// $database = 'if0_37642403_message_board'; // 資料庫名稱


$host = 'sql3.freesqldatabase.com'; // MySQL 主機
$username = 'sql3772510'; // MySQL 使用者名稱
$password = 'lvbf8yFYyX'; // MySQL 密碼
$database = 'sql3772510'; // 資料庫名稱

$conn = new mysqli($host, $username, $password, $database);
$conn->set_charset("utf8mb4");

// 驗證連接是否成功
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php
// $host = 'localhost'; // MySQL 主機
// $username = 'root'; // MySQL 使用者名稱
// $password = ''; // MySQL 密碼
// $database = 'if0_37642403_message_board'; // 資料庫名稱


$host = 'https://sql-76mh.onrender.com'; // MySQL 主機
$username = 'mis116';
$password = 'mis_test_mis';
$database = 'mis_test_mis';

$conn = new mysqli($host, $username, $password, $database);
$conn->set_charset("utf8mb4");

// 驗證連接是否成功
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

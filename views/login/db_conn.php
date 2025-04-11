<?php
//線上用
// $servername = "sql102.infinityfree.com";
// $username = "if0_37642403";
// $password = "web116web116";
// $dbname = "if0_37642403_message_board";
//本機用
$servername = "localhost";
$username = "root";     // 預設使用者名稱
$password = "";         // 預設密碼為空
$dbname = "if0_37642403_message_board";

// 建立連接
$conn = mysqli_connect($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

// 檢查連接是否成功
if ($conn->connect_error) {
    echo("Connection failed: " . $conn->connect_error);
}

// 執行SQL查詢

?>
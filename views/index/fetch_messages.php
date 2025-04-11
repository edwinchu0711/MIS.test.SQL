<?php
// header("Access-Control-Allow-Origin: https://116mispachiweb.wuaze.com");
// $servername = "sql102.infinityfree.com";
// $username = "if0_37642403";
// $password = "web116web116";
// $dbname = "if0_37642403_message_board";

$servername = "localhost";
$username = "root";     // 預設使用者名稱
$password = "";         // 預設密碼為空
$dbname = "if0_37642403_message_board";

// 建立連接
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

// 檢查連接是否成功
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 執行SQL查詢
$sql = "SELECT name, message FROM `Bulletin board` ORDER BY `Bulletin board`.`id` DESC";
$result = $conn->query($sql);

// 檢查是否有結果
$data = "";
if ($result->num_rows > 0) {
    // 輸出數據
    while ($row = $result->fetch_assoc()) {
        $data .= '<div class="post">
        <p class="user-name"><strong>' . $row['name'] . ' :</strong><br></p>
        <p class="w3-text-grey message"> ' . $row['message'] . '<br></p>
        </div>
        <hr class="Comment-board-hr">';
        //To see the version with the comment board, please visit: webproject116.kesug.com/for_Phase1
    }
} else {
    $data = "No messages currently";
}

// 關閉連接
$conn->close();

// 返回數據
echo $data;
?>

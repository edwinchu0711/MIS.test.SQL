<?php
header('Content-Type: application/json'); // 設置響應類型為 JSON

// 顯示所有錯誤訊息（僅在開發階段使用，生產環境應關閉）
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// $servername = "sql102.infinityfree.com"; // 主機名稱
// $username = "if0_37642403"; // MySQL 帳號
// $password = "web116web116"; // 使用 vPanel 密碼
// $dbname = "if0_37642403_message_board"; // 資料庫名稱
$servername = "localhost";
$username = "root";     // 預設使用者名稱
$password = "";         // 預設密碼為空
$dbname = "if0_37642403_message_board";

// 建立連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// 設置字符集
if (!$conn->set_charset("utf8")) {
    echo json_encode(["status" => "error", "message" => "Setting character set failed: " . $conn->error]);
    exit();
}

// 確認POST資料存在並進行驗證
if (isset($_POST['name']) && isset($_POST['message'])) {
    $name = trim($_POST['name']);
    $message = trim($_POST['message']);

    // 基本檢查
    if (empty($name) || empty($message)) {
        echo json_encode(["status" => "error", "message" => "Name and message are required."]);
        exit();
    }

    // 使用預處理語句以防止SQL注入
    // 假設 `id` 是自動遞增的，可以省略 `id` 欄位
    $stmt = $conn->prepare("INSERT INTO `Bulletin board` (`name`, `message`) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param("ss", $name, $message);

        if ($stmt->execute()) {
            // 執行成功
            echo json_encode(["status" => "success"]);
            exit();
        } else {
            // 執行失敗，返回錯誤訊息
            echo json_encode(["status" => "error", "message" => "Execution error: " . $stmt->error]);
            exit();
        }

        $stmt->close();
    } else {
        // 預處理語句錯誤
        echo json_encode(["status" => "error", "message" => "Preparation failed: " . $conn->error]);
        exit();
    }
} else {
    echo json_encode(["status" => "error", "message" => "Please provide name and message."]);
}

$conn->close();
?>

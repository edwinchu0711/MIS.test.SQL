<?php
session_start();

// 接收 JSON 資料
$data = json_decode(file_get_contents('php://input'), true);

// 驗證資料並更新 $_SESSION
if (isset($data['key']) && isset($data['value'])) {
    $_SESSION[$data['key']] = $data['value']; // 更新 session
    echo json_encode(['status' => 'success', 'session' => $_SESSION]); // 回傳成功訊息
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']); // 回傳錯誤訊息
}
?>

<?php
if (!defined('BASE_PATH')) {
    $currentPath = $_SERVER['PHP_SELF'];
    $depth = substr_count($currentPath, '/') - 1;
    define('BASE_PATH', str_repeat('../', $depth));
}


// 開啟錯誤報告
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 設置響應頭
header('Content-Type: application/json; charset=utf-8');

try {
    // 包含數據庫連接文件
    require_once BASE_PATH.'views/post/database.php';

    // 檢查是否有搜索參數
    if (!isset($_GET['q'])) {
        throw new Exception('未提供搜索關鍵字');
    }

    // 獲取並清理搜索關鍵字
    $search = trim($_GET['q']);
    if (empty($search)) {
        throw new Exception('搜索關鍵字不能為空');
    }

    // 準備 SQL 語句
    $sql = "SELECT id, dish_name, description, photo_path, username, dish_type
            FROM posts 
            WHERE dish_name LIKE ? OR description LIKE ? OR username LIKE ? OR dish_type LIKE ?";
    
    // 準備語句
    $stmt = $conn->prepare($sql);
    if (!$stmt) {       
        throw new Exception('準備語句失敗: ' . $conn->error);
    }

    // 綁定參數
    $searchParam = "%{$search}%";
    $stmt->bind_param('ssss', $searchParam, $searchParam, $searchParam, $searchParam);

    // 執行查詢
    if (!$stmt->execute()) {
        throw new Exception('執行查詢失敗: ' . $stmt->error);
    }

    // 獲取結果
    $result = $stmt->get_result();
    $data = [];

    // 提取數據
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $row['id'],
            'dish_name' => $row['dish_name'],
            'description' => $row['description'],
            'image_path' => $row['photo_path'],
            'username' => $row['username']
        ];
    }

    // 關閉語句
    $stmt->close();

    // 返回結果
    echo json_encode($data, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    // 記錄錯誤
    error_log('Search error: ' . $e->getMessage());
    
    // 返回錯誤信息
    http_response_code(200); // 改為 200 以確保錯誤信息能被前端正確接收
    echo json_encode([
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

// 關閉數據庫連接
if (isset($conn)) {
    $conn->close();
}

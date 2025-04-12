<?php
/**
 * SQLite 資料庫連線管理
 * 專門針對 F5 重新整理問題設計
 */

// 1. 首先檢查 SQLite 驅動程式是否已載入
if (!extension_loaded('pdo_sqlite')) {
    // 如果是 AJAX 請求
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'SQLite 驅動程式未載入']);
        exit;
    }
    
    // 如果是普通頁面請求，嘗試強制重新整理
    if (!isset($_GET['_force_reload'])) {
        // 添加強制重新整理參數並重定向
        $redirect_url = $_SERVER['REQUEST_URI'] . 
            (strpos($_SERVER['REQUEST_URI'], '?') === false ? '?' : '&') . 
            '_force_reload=1';
        
        // 輸出 JavaScript 強制重新整理
        echo '<script>window.location.href="' . $redirect_url . '";</script>';
        exit;
    } else {
        // 如果已經嘗試過強制重新整理，顯示錯誤
        die('系統錯誤：SQLite 驅動程式未載入。請聯絡系統管理員或使用 Ctrl+F5 重新整理頁面。');
    }
}

// 2. 設定連線函數
function getDbConnection() {
    static $conn = null;
    
    if ($conn === null) {
        $db_path = BASE_PATH.'MIS.db3';
        
        // 檢查檔案是否存在
        if (!file_exists($db_path)) {
            error_log("SQLite 資料庫檔案不存在: $db_path");
            die("資料庫檔案不存在");
        }
        
        try {
            // 使用完整路徑建立連線
            $absolute_path = realpath($db_path);
            if (!$absolute_path) {
                $absolute_path = $db_path;
            }
            
            // 使用 PDO 參數來確保連線正確建立
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 5, // 設定連線超時
                PDO::ATTR_PERSISTENT => false // 不使用持久連線
            ];
            
            $conn = new PDO("sqlite:$absolute_path", null, null, $options);
            
            // 驗證連線
            $stmt = $conn->query('SELECT 1');
            if (!$stmt) {
                throw new PDOException("連線無效");
            }
            
            // SQLite 優化
            $conn->exec('PRAGMA journal_mode = WAL;');
            $conn->exec('PRAGMA synchronous = NORMAL;');
        } catch (PDOException $e) {
            error_log("SQLite 連線錯誤: " . $e->getMessage());
            
            // 清除 SESSION 中可能存在的舊連線資訊
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_regenerate_id(true);
            }
            
            // 如果是普通頁面請求，嘗試強制重新整理
            if (!isset($_GET['_db_retry'])) {
                // 添加重試參數並重定向
                $redirect_url = $_SERVER['REQUEST_URI'] . 
                    (strpos($_SERVER['REQUEST_URI'], '?') === false ? '?' : '&') . 
                    '_db_retry=1';
                
                // 輸出 JavaScript 強制重新整理
                echo '<script>location.reload(true);</script>';
                exit;
            } else {
                die("資料庫連線失敗：" . $e->getMessage() . "<br>請使用 Ctrl+F5 重新整理頁面。");
            }
        }
    }
    
    return $conn;
}

// 3. 獲取連線
try {
    $conn = getDbConnection();
} catch (Exception $e) {
    error_log("未預期的資料庫錯誤: " . $e->getMessage());
    die("系統發生未預期錯誤，請使用 Ctrl+F5 重新整理頁面");
}

// 4. 添加前端檢測和自動修復
echo <<<HTML
<script>
// 檢測頁面是否包含連線錯誤訊息
document.addEventListener('DOMContentLoaded', function() {
    if (document.body.textContent.includes('could not find driver') || 
        document.body.textContent.includes('連接失敗')) {
        console.log('檢測到資料庫連線錯誤，正在嘗試強制重新整理...');
        // 強制重新整理頁面
        setTimeout(function() {
            location.reload(true);
        }, 500);
    }
});
</script>
HTML;
?>

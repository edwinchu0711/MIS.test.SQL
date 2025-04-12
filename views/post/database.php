<?php
/**
 * 資料庫連線管理函數
 * 包含錯誤處理、連線驗證和重試機制
 */
function getDbConnection() {
    static $conn = null;
    
    if ($conn === null) {
        $db_path = BASE_PATH.'MIS.db3';
        
        // 檢查檔案是否存在且可讀
        if (!file_exists($db_path)) {
            error_log("SQLite 資料庫檔案不存在: $db_path");
            die("資料庫檔案不存在，請聯絡系統管理員");
        }
        
        if (!is_readable($db_path)) {
            error_log("SQLite 資料庫檔案無法讀取: $db_path");
            die("資料庫檔案無法讀取，請檢查權限設定");
        }
        
        // 確保 SQLite 驅動程式已載入
        if (!in_array('sqlite', PDO::getAvailableDrivers())) {
            error_log("SQLite PDO 驅動程式未載入");
            die("系統錯誤: SQLite 驅動程式未載入，請聯絡系統管理員");
        }
        
        try {
            // 建立連線
            $conn = new PDO("sqlite:$db_path");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // 驗證連線是否正常
            $stmt = $conn->query('SELECT 1');
            if (!$stmt) {
                throw new PDOException("無法執行簡單查詢，連線可能無效");
            }
        } catch (PDOException $e) {
            error_log("SQLite 連線錯誤: " . $e->getMessage());
            
            // 清除無效連線
            $conn = null;
            
            // 重試一次
            try {
                $conn = new PDO("sqlite:$db_path");
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // 再次驗證連線
                $stmt = $conn->query('SELECT 1');
                if (!$stmt) {
                    throw new PDOException("重試後仍無法執行簡單查詢");
                }
            } catch (PDOException $e2) {
                error_log("SQLite 重試連線失敗: " . $e2->getMessage());
                
                // 如果是在 AJAX 請求中
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    header('HTTP/1.1 500 Internal Server Error');
                    echo json_encode(['error' => '資料庫連線失敗']);
                    exit;
                }
                
                // 一般頁面請求
                die("資料庫連線失敗，請重新整理頁面或聯絡系統管理員");
            }
        }
        
        // 設定 SQLite 連線的其他優化選項
        $conn->exec('PRAGMA journal_mode = WAL;'); // 寫入優化
        $conn->exec('PRAGMA synchronous = NORMAL;'); // 性能優化
        $conn->exec('PRAGMA cache_size = 10000;'); // 增加快取大小
        $conn->exec('PRAGMA temp_store = MEMORY;'); // 使用記憶體存儲臨時表
    }
    
    // 檢查連線是否仍然有效
    try {
        $test = $conn->query('SELECT 1');
        if ($test) {
            return $conn;
        } else {
            // 連線無效，重設並重新連線
            $conn = null;
            return getDbConnection(); // 遞迴調用自身重新連線
        }
    } catch (PDOException $e) {
        error_log("SQLite 連線已失效: " . $e->getMessage());
        $conn = null;
        return getDbConnection(); // 遞迴調用自身重新連線
    }
}

// 使用函數獲取連線
try {
    $conn = getDbConnection();
} catch (Exception $e) {
    // 捕獲所有其他可能的例外
    error_log("未預期的資料庫錯誤: " . $e->getMessage());
    die("系統發生未預期錯誤，請稍後再試");
}
?>

<?php
// 顯示所有錯誤，方便調試
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>PostgreSQL 資料庫連接測試</h1>";

// 嘗試連接 PostgreSQL
try {
    // PostgreSQL 連接參數
    $host = "dpg-cvsa2fc9c44c739t4uk0-a.oregon-postgres.render.com";
    $dbname = "mydb_d092";
    $user = "myuser";
    $password = "2BeqE2rDtkKP5pTUWRSKWOT6eiZDcj5a";
    $port = "5432";
    
    // 建立連接
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color:green; font-weight:bold;'>✓ 成功連接到 PostgreSQL 資料庫!</p>";
    
    // 執行簡單查詢 - 列出所有表格
    $tables_query = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'";
    $stmt = $pdo->query($tables_query);
    $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>資料庫中的表格：</h2>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>" . htmlspecialchars($table['table_name']) . "</li>";
    }
    echo "</ul>";
    
    // 嘗試查詢 posts 表格 (如果存在)
    $posts_table_exists = false;
    foreach ($tables as $table) {
        if ($table['table_name'] === 'posts') {
            $posts_table_exists = true;
            break;
        }
    }
    
    if ($posts_table_exists) {
        echo "<h2>從 posts 表格讀取資料</h2>";
        
        $posts_query = "SELECT * FROM posts ORDER BY created_at DESC LIMIT 5";
        $stmt = $pdo->query($posts_query);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($posts) > 0) {
            echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
            
            // 表頭
            echo "<tr style='background-color: #f2f2f2;'>";
            foreach (array_keys($posts[0]) as $column) {
                echo "<th>" . htmlspecialchars($column) . "</th>";
            }
            echo "</tr>";
            
            // 資料行
            foreach ($posts as $post) {
                echo "<tr>";
                foreach ($post as $key => $value) {
                    // 限制長文字欄位顯示長度
                    if (is_string($value) && strlen($value) > 100) {
                        $value = substr($value, 0, 100) . "...";
                    }
                    echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
                }
                echo "</tr>";
            }
            
            echo "</table>";
        } else {
            echo "<p>posts 表格中沒有資料</p>";
        }
    } else {
        // 如果沒有 posts 表格，嘗試讀取第一個表格的資料
        if (count($tables) > 0) {
            $first_table = $tables[0]['table_name'];
            echo "<h2>從 $first_table 表格讀取資料</h2>";
            
            $query = "SELECT * FROM \"$first_table\" LIMIT 5";
            $stmt = $pdo->query($query);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($rows) > 0) {
                echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
                
                // 表頭
                echo "<tr style='background-color: #f2f2f2;'>";
                foreach (array_keys($rows[0]) as $column) {
                    echo "<th>" . htmlspecialchars($column) . "</th>";
                }
                echo "</tr>";
                
                // 資料行
                foreach ($rows as $row) {
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                        // 限制長文字欄位顯示長度
                        if (is_string($value) && strlen($value) > 100) {
                            $value = substr($value, 0, 100) . "...";
                        }
                        echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
                    }
                    echo "</tr>";
                }
                
                echo "</table>";
            } else {
                echo "<p>$first_table 表格中沒有資料</p>";
            }
        }
    }
    
} catch (PDOException $e) {
    echo "<p style='color:red; font-weight:bold;'>PostgreSQL 連接失敗: " . htmlspecialchars($e->getMessage()) . "</p>";
    
    // 顯示更詳細的錯誤信息
    echo "<h2>錯誤詳情</h2>";
    echo "<pre>";
    echo "錯誤代碼: " . $e->getCode() . "\n";
    echo "錯誤訊息: " . $e->getMessage() . "\n";
    echo "錯誤發生在: " . $e->getFile() . " 第 " . $e->getLine() . " 行\n";
    echo "</pre>";
}

// 顯示 PHP 環境信息
echo "<h2>PHP 環境信息</h2>";
echo "<p>PHP 版本: " . phpversion() . "</p>";

echo "<p>已啟用的 PDO 驅動:</p>";
echo "<ul>";
foreach (PDO::getAvailableDrivers() as $driver) {
    echo "<li>" . htmlspecialchars($driver) . "</li>";
}
echo "</ul>";

// 檢查 PostgreSQL 擴展
if (extension_loaded('pgsql')) {
    echo "<p style='color:green'>✓ PostgreSQL 擴展已啟用</p>";
} else {
    echo "<p style='color:red'>✗ PostgreSQL 擴展未啟用</p>";
}

// 檢查 PDO PostgreSQL 驅動
if (in_array('pgsql', PDO::getAvailableDrivers())) {
    echo "<p style='color:green'>✓ PDO PostgreSQL 驅動已啟用</p>";
} else {
    echo "<p style='color:red'>✗ PDO PostgreSQL 驅動未啟用</p>";
}
?>

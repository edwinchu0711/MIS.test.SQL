<?php
    try {
        // 使用您的 Render 資料庫資訊
        $host = "postgresql://myuser:2BeqE2rDtkKP5pTUWRSKWOT6eiZDcj5a@dpg-cvsa2fc9c44c739t4uk0-a.oregon-postgres.render.com/mydb_d092"; // 使用外部 URL 的主機部分
        $dbname = "mydb_d092";
        $user = "myuser";
        $password = "2BeqE2rDtkKP5pTUWRSKWOT6eiZDcj5a";
        $port = "5432";
    
        // 建立 PDO 連接
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
        $pdo = new PDO($dsn, $user, $password);
        
        // 設定錯誤模式
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "成功連接到 PostgreSQL 資料庫";
        
        // 執行查詢範例
        $stmt = $pdo->query("SELECT version()");
        $version = $stmt->fetch();
        echo "<br>PostgreSQL 版本: " . $version[0];
        
    } catch (PDOException $e) {
        echo "連接失敗: " . $e->getMessage();
    }


















// // $host = 'localhost'; // MySQL 主機
// // $username = 'root'; // MySQL 使用者名稱
// // $password = ''; // MySQL 密碼
// // $database = 'if0_37642403_message_board'; // 資料庫名稱


// $host = 'https://sql-76mh.onrender.com'; // MySQL 主機
// $username = 'mis116';
// $password = 'mis_test_mis';
// $database = 'mis_test_mis';

// $conn = new mysqli($host, $username, $password, $database);
// $conn->set_charset("utf8mb4");

// // 驗證連接是否成功
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
// ?>

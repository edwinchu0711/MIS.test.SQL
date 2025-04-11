<?php
// db_connect.php - 集中管理資料庫連接

class DatabaseConnection {
    private static $pdo = null;
    public static $conn = null; // 模擬舊的 $conn 物件
    
public static function initialize() {
    try {
        // PostgreSQL 連接資訊
        $host = "dpg-cvsa2fc9c44c739t4uk0-a.oregon-postgres.render.com";
        $dbname = "mydb_d092";
        $user = "myuser";
        $password = "2BeqE2rDtkKP5pTUWRSKWOT6eiZDcj5a";
        $port = "5432";
        
        // 建立 PDO 連接
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
        self::$pdo = new PDO($dsn, $user, $password);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // 建立相容層，模擬 mysqli 物件
        self::$conn = new MySQLiCompatibilityLayer(self::$pdo);
        
        error_log("資料庫連接成功初始化");
        return true;
    } catch (PDOException $e) {
        error_log("資料庫連接錯誤: " . $e->getMessage());
        self::$pdo = null;
        self::$conn = null;
        return false;
    }
}

    
    public static function getPDO() {
        if (self::$pdo === null) {
            self::initialize();
        }
        return self::$pdo;
    }
    
    public static function getConn() {
        if (self::$conn === null) {
            self::initialize();
        }
        return self::$conn;
    }
}

// 相容層類別 - 將 PDO 操作轉換為 mysqli 風格
class MySQLiCompatibilityLayer {
    private $pdo;
    public $insert_id; // 模擬 mysqli 的 insert_id 屬性
    public $affected_rows; // 模擬 mysqli 的 affected_rows 屬性
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // 模擬 mysqli 的 query 方法
    public function query($sql) {
        try {
            $stmt = $this->pdo->query($sql);
            
            // 對於 INSERT 語句，設置 insert_id
            if (stripos($sql, 'INSERT') === 0) {
                $this->insert_id = $this->pdo->lastInsertId();
            }
            
            // 設置 affected_rows
            $this->affected_rows = $stmt->rowCount();
            
            // 返回結果集包裝器
            return new ResultWrapper($stmt);
        } catch (PDOException $e) {
            error_log("查詢錯誤: " . $e->getMessage());
            return false;
        }
    }
    
    // 模擬 mysqli 的 prepare 方法
    public function prepare($sql) {
        try {
            $stmt = $this->pdo->prepare($sql);
            return new StatementWrapper($stmt, $this);
        } catch (PDOException $e) {
            error_log("準備語句錯誤: " . $e->getMessage());
            return false;
        }
    }
    
    // 模擬 mysqli 的 real_escape_string 方法
    public function real_escape_string($string) {
        // PDO 使用預處理語句，不需要 escape，但為了相容性提供此方法
        return str_replace(["'", "\""], ["\'", "\\\""], $string);
    }
    
    // 模擬 mysqli 的 set_charset 方法
    public function set_charset($charset) {
        // PostgreSQL 處理字符集的方式與 MySQL 不同，但我們可以記錄請求
        error_log("注意: 嘗試設置字符集 '$charset'，但在 PostgreSQL PDO 中此操作可能無效");
        return true;
    }
}

// 結果集包裝器
class ResultWrapper {
    private $stmt;
    private $results = [];
    private $position = 0;
    
    public function __construct($stmt) {
        $this->stmt = $stmt;
        $this->results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // 模擬 mysqli_result 的 fetch_assoc 方法
    public function fetch_assoc() {
        if ($this->position < count($this->results)) {
            return $this->results[$this->position++];
        }
        return null;
    }
    
    // 模擬 mysqli_result 的 fetch_array 方法
    public function fetch_array($resultType = MYSQLI_BOTH) {
        if ($this->position < count($this->results)) {
            $row = $this->results[$this->position++];
            
            // 如果需要 MYSQLI_NUM 或 MYSQLI_BOTH，添加數字索引
            if ($resultType == MYSQLI_NUM || $resultType == MYSQLI_BOTH) {
                $i = 0;
                foreach ($row as $key => $value) {
                    $row[$i] = $value;
                    $i++;
                }
            }
            
            return $row;
        }
        return null;
    }
    
    // 模擬 mysqli_result 的 num_rows 屬性/方法
    public function num_rows() {
        return count($this->results);
    }
}

// 預處理語句包裝器
class StatementWrapper {
    private $stmt;
    private $conn;
    
    public function __construct($stmt, $conn) {
        $this->stmt = $stmt;
        $this->conn = $conn;
    }
    
    // 模擬 mysqli_stmt 的 bind_param 方法
    public function bind_param($types, ...$params) {
        // PDO 使用不同的綁定方式，我們需要轉換
        $paramIndex = 1;
        foreach ($params as $param) {
            $this->stmt->bindValue($paramIndex++, $param);
        }
        return true;
    }
    
    // 模擬 mysqli_stmt 的 execute 方法
    public function execute() {
        try {
            $result = $this->stmt->execute();
            $this->conn->affected_rows = $this->stmt->rowCount();
            $this->conn->insert_id = $this->conn->pdo->lastInsertId();
            return $result;
        } catch (PDOException $e) {
            error_log("執行語句錯誤: " . $e->getMessage());
            return false;
        }
    }
    
    // 模擬 mysqli_stmt 的 get_result 方法
    public function get_result() {
        $this->stmt->execute();
        return new ResultWrapper($this->stmt);
    }
}

// 定義 mysqli 常數 (如果尚未定義)
if (!defined('MYSQLI_ASSOC')) define('MYSQLI_ASSOC', 1);
if (!defined('MYSQLI_NUM')) define('MYSQLI_NUM', 2);
if (!defined('MYSQLI_BOTH')) define('MYSQLI_BOTH', 3);

// 初始化連接
DatabaseConnection::initialize();

// 設置全域變數 $conn，以便現有程式碼可以使用
$conn = DatabaseConnection::getConn();
?>

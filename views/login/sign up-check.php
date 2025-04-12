<?php
session_start();

if (!defined('BASE_PATH')) {
    $currentPath = $_SERVER['PHP_SELF'];
    $depth = substr_count($currentPath, '/') - 1;
    define('BASE_PATH', str_repeat('../', $depth));
}

include BASE_PATH.'views/post/database.php'; // 假設此檔案已包含 SQLite 連接 $conn

if (isset($_POST['username']) && isset($_POST['password'])) {
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $uname = ucfirst(validate($_POST['username']));
    $password = validate($_POST['password']);
    $confirmPass = validate($_POST['confirmpass']);

    if (empty($uname)) {
        header("Location: signup.php?error=User name is required");
        exit();
    } 
    else if (empty($password)) {
        header("Location: signup.php?error=Password is required");
        exit();
    } 
    else if (empty($confirmPass)) {
        header("Location: signup.php?error=Confirm Password is required");
        exit();
    }
    else if ($password != $confirmPass) {
        header("Location: signup.php?error=Password and Confirm Password aren't identical");
        exit();
    }
    else {
        try {
            // 檢查用戶名是否已存在
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$uname]);
            
            $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existingUser) {
                // 如果用戶名已存在，重定向並顯示錯誤
                header("Location: signup.php?error=Username already taken. Please choose another one.");
                exit();
            } else {
                // 用戶名不存在，插入新用戶
                $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                
                // 執行插入操作
                if ($stmt->execute([$uname, $password])) {
                    // 使用 JavaScript 進行 alert 並重定向
                    echo "<script type='text/javascript'>
                            alert('Registration successful, please enter your username and password to log in.');
                            window.location.href = 'login.php';
                          </script>";
                    exit();
                } else {
                    echo "Error: " . implode(" ", $conn->errorInfo());
                    exit();
                }
            }
        } catch (PDOException $e) {
            echo "Database Error: " . $e->getMessage();
            exit();
        }
    }
} else {
    header("Location: login.php?error");
    exit();
}
?>

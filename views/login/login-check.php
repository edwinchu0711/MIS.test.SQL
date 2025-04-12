<?php
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

        if (empty($uname)) {
            header("Location: login.php?error=User name is required");
            exit();
        } else if (empty($password)) {
            header("Location: login.php?error=Password is required");
            exit();
        } else {
            try {
                // 使用 PDO 預處理語句
                $sql = "SELECT * FROM users WHERE username = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$uname]);
                
                // 獲取結果
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($row) {
                    // 如果密碼沒有加密，直接比對
                    if ($row['password'] === $password) {
                        session_start();
                        $_SESSION['username'] = $row['username'];
                        $_SESSION['name'] = $row['username'];
                        $_SESSION['id'] = $row['id'];
                        header('Location: ' . BASE_PATH . 'views/classification/userpost.php?username='.$_SESSION['username']);
                        exit();
                    } else {
                        header("Location: login.php?error=Incorrect User or Password");
                        exit();
                    }
                } else {
                    header("Location: login.php?error=Incorrect User or Password");
                    exit();
                }
            } catch (PDOException $e) {
                header("Location: login.php?error=Database Error: " . $e->getMessage());
                exit();
            }
        }
    } else {
        header("Location: login.php?error123");
        exit();
    }
?>

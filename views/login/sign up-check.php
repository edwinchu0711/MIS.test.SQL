

<?php
session_start();

if (!defined('BASE_PATH')) {

    $currentPath = $_SERVER['PHP_SELF'];

    $depth = substr_count($currentPath, '/') - 1;

    define('BASE_PATH', str_repeat('../', $depth));

}

include BASE_PATH.'views/post/database.php';



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

        // 檢查用戶名是否已存在

        $sql = "SELECT * FROM users WHERE username=?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $uname);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);



        if (mysqli_fetch_assoc($result)) {

            // 如果用戶名已存在，重定向並顯示錯誤

            header("Location: signup.php?error=Username already taken. Please choose another one.");

            exit();

        } else { // 這裡的 else 是一個關鍵變動

            // 用戶名不存在，插入新用戶

            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

            $stmt = mysqli_prepare($conn, $sql);



            // 使用 password_hash 加密密碼



            mysqli_stmt_bind_param($stmt, "ss", $uname, $password);



            if (mysqli_stmt_execute($stmt)) {

                // 使用 JavaScript 進行 alert 並重定向

                echo "<script type='text/javascript'>

                        alert('Registration successful, please enter your username and password to log in.');

                        window.location.href = 'login.php';

                      </script>";

                exit();

            } else {

                echo "Error: " . mysqli_error($conn);

                exit();

            }

        }

    }

} else {

    header("Location: login.php?error");

    exit();

}


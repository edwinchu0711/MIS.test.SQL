<?php

    if (!defined('BASE_PATH')) {

        $currentPath = $_SERVER['PHP_SELF'];

        $depth = substr_count($currentPath, '/') - 1;

        define('BASE_PATH', str_repeat('../', $depth));

    }

    include BASE_PATH.'views/post/database.php' ;

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

        $sql = "SELECT * FROM users WHERE username=?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $uname);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);



        if ($row = mysqli_fetch_assoc($result)) {

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

    }

} else {

    header("Location: login.php?error123");

    exit();

}

?>






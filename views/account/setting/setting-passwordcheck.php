<?php
session_start();
if (!defined('BASE_PATH')) {
    $currentPath = $_SERVER['PHP_SELF'];
    $depth = substr_count($currentPath, '/') - 1;
    define('BASE_PATH', str_repeat('../', $depth));
}
include BASE_PATH.'views/post/database.php';

if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $uname = $_SESSION['username'];
    $oldpassword = validate($_POST['oldpassword']);
    $newpassword = validate($_POST['newpassword']);
    $confirmPass = validate($_POST['confirmpass']);
    
    
    if (empty($uname)) {
        $_SESSION['setting'] = 'password';
        header("Location: setting.php?error=Username is required");
  
        exit();
    }


    else if (empty($oldpassword)) {
        $_SESSION['setting'] = 'password';
        header("Location: setting.php?error=Old password is required");
  
        exit();
    }
    else if (empty($newpassword)) {
        $_SESSION['setting'] = 'password';
        header("Location: setting.php?error=New password is required");
        exit();
    }     
    else if (empty($confirmPass)) {
        $_SESSION['setting'] = 'password';
        header("Location: setting.php?error=Confirm New password is required");
        exit();
    }
    else {
        // 檢查用戶名是否已存在
        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $uname);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            if($row['password'] === $oldpassword){
                if ($newpassword != $confirmPass) {
                    $_SESSION['setting'] = 'password';
                    header("Location: setting.php?error=New password and Confirm Password aren't identical");
                    exit();
                }
                else{
                    $sql = "UPDATE users SET password=? WHERE username=?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "ss", $newpassword, $uname);
    
                    if (mysqli_stmt_execute($stmt)) {
                        unset($_SESSION['setting']);
                        echo "<script type='text/javascript'>
                                alert('Password updated successfully.');
                                window.location.href = '".BASE_PATH."index.html';
                            </script>";
                        exit();
                    }
                    else{
                        $_SESSION['setting'] = 'password';
                        header("Location: setting.php?error=Something went wrong, please try again.");

                        exit();
                    } 
                
    
                }
            }
            else {
                $_SESSION['setting'] = 'password';
                echo "<script type='text/javascript'>
                    window.location.href = 'setting.php?error=Old Password is incorrect';
                    </script>";
                exit();
            }
        }
    }
} 
else {
    header("Location: ".BASE_PATH."index.html");
    exit();
}
?>
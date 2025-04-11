<?php
session_start();
if (!defined('BASE_PATH')) {
    $currentPath = $_SERVER['PHP_SELF'];
    $depth = substr_count($currentPath, '/') - 1;
    define('BASE_PATH', str_repeat('../', $depth));
}
include BASE_PATH.'views/post/database.php';

$sql = "SELECT * FROM users WHERE username=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if ($row = mysqli_fetch_assoc($result)) {
    $oldphotoPath = $row['photoPath'];
    $oldBGPath = $row['BGpath'];
}


if (isset($_POST['username']) ) {

    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $uname = ucfirst(validate($_POST['username']));
    $ig = validate($_POST['ig']);
    $phone = validate($_POST['phone']);
    $aboutMe = validate($_POST['form-aboutMe']);

    // 預設圖片路徑
    $photoPath = null;
    $BGphotoPath = null;
    
    // 上傳目錄
    $upload_dir = realpath(BASE_PATH . 'views/account/img') . '/'; // 確保路徑正確
    
    // 檢查目錄是否存在並具有寫入權限
    if (!is_dir($upload_dir) || !is_writable($upload_dir)) {
        die('Upload directory is not writable or does not exist！');
    }
    // 檢查新的使用者名稱是否已存在
    $checkUsernameSql = "SELECT 1 FROM users WHERE username = ? AND id != ?";
    $checkUsernameStmt = $conn->prepare($checkUsernameSql);
    $checkUsernameStmt->bind_param("si", $uname, $_SESSION['id']);
    $checkUsernameStmt->execute();
    $checkUsernameResult = $checkUsernameStmt->get_result();

    if ($checkUsernameResult->num_rows > 0) {
        // 使用者名稱已存在
        echo '<script>
                alert("Username already taken. Please choose another one.");
                window.location.href = "'.BASE_PATH.'views/classification/userpost.php?username='.$_SESSION['username'].'";
            </script>';
        $checkUsernameStmt->close();
        $conn->close();
        exit();
    }
    $checkUsernameStmt->close();
    
    /**
     * 處理個人照片上傳
     */
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['photo']['tmp_name'];
        $file_ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION); // 取得副檔名
        $file_name = basename($uname . '_profileIMG.' . $file_ext); // 新檔案名稱
        $target_file = $upload_dir . $file_name;
    
        // 驗證檔案大小（例如限制為 4MB）
        if ($_FILES['photo']['size'] > 6 * 1024 * 1024) {
            die('Photo oversize！');
        }
    
        // 驗證檔案類型
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = mime_content_type($file_tmp);
        if (!in_array($file_type, $allowed_types)) {
            die('Photo type not supported！');
        }
    
        // 移動檔案
        if (!move_uploaded_file($file_tmp, $target_file)) {
            die('Photo upload failed！');
        } else {
            $photoPath = 'views/account/img/' . $file_name; // 更新圖片路徑
        }
    }
    
    /**
     * 處理背景圖片上傳
     */
    if (isset($_FILES['BGphoto']) && $_FILES['BGphoto']['error'] === UPLOAD_ERR_OK) {
        $BG_file_tmp = $_FILES['BGphoto']['tmp_name'];
        $BG_file_ext = pathinfo($_FILES['BGphoto']['name'], PATHINFO_EXTENSION); // 取得副檔名
        $BG_file_name = basename($uname . '_BGimg.' . $BG_file_ext); // 新檔案名稱
        $BG_target_file = $upload_dir . $BG_file_name;
    
        // 驗證檔案大小（例如限制為 10MB）
        if ($_FILES['BGphoto']['size'] > 10 * 1024 * 1024) {
            die('Background photo oversize！');
        }
    
        // 驗證檔案類型
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $BG_file_type = mime_content_type($BG_file_tmp);
        if (!in_array($BG_file_type, $allowed_types)) {
            die('Background photo type not supported！');
        }
    
        // 移動檔案
        if (!move_uploaded_file($BG_file_tmp, $BG_target_file)) {
            die('Background photo upload failed！');
        } else {
            $BGphotoPath = 'views/account/img/' . $BG_file_name; // 更新圖片路徑
        }
    }
    
    

    // 動態構建 SQL 語句
    $sql = "UPDATE users 
            SET username = ?, phoneNumber = ?, Introduction = ?, ig = ?";
    if ($photoPath) {
        $sql .= ", photoPath = ?";
    }
    if($BGphotoPath){
        $sql .= ", BGpath = ?";
    }
    $sql .= " WHERE id = ?";

    // 準備 SQL 語句
    $stmt = $conn->prepare($sql);

    // 綁定參數
    $userId = $_SESSION['id']; // 從 session 獲取用戶的 id
    if ($photoPath && !$BGphotoPath) {
        $stmt->bind_param('sssssi', $uname, $phone, $aboutMe, $ig, $photoPath, $userId);
    } 
    else if (!$photoPath && $BGphotoPath){
        $stmt->bind_param('sssssi', $uname, $phone, $aboutMe, $ig, $BGphotoPath, $userId);
    }
    else if($photoPath && $BGphotoPath){
        $stmt->bind_param('ssssssi', $uname, $phone, $aboutMe, $ig, $photoPath, $BGphotoPath, $userId);
    }
    else{
        $stmt->bind_param('ssssi', $uname, $phone, $aboutMe, $ig, $userId);
    }

    // 執行語句
    if ($stmt->execute()) {
        unset($_SESSION['setting']);
        if (isset($BGphotoPath) && $oldBGPath != $BGphotoPath && $oldBGPath != 'views/account/img/background.webp'){
            unlink(BASE_PATH.$oldBGPath);
        }
        if (isset($photoPath) && $oldphotoPath != $photoPath && $oldphotoPath != 'views/account/img/profile.webp'){
            unlink(BASE_PATH.$oldphotoPath);
        }
        // 從 SESSION 中取得當前的使用者名稱
        $currentUsername = $_SESSION['username'];


        // 使用 Prepared Statement 避免 SQL Injection
        $sql = "UPDATE posts SET username = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $uname, $_SESSION['username']);
        if ($stmt->execute()) {
            $_SESSION['username'] = $uname;
          } 

        echo '<script>
                alert("Update profile success!!");
                const MypostUrl = "'.BASE_PATH.'views/classification/userpost.php?username='.$_SESSION['username'].'"; // 獲取當前網站的根域名
                window.location.href = MypostUrl; // 導向首頁
                </script>';
                $stmt->close();
                $conn->close();
        exit();
    } else {
        $_SESSION['setting'] = 'profile';
        echo '資料更新失敗：' . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    exit();
    // 關閉連線

}
?>

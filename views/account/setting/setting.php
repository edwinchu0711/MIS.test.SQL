<?php

if (!defined('BASE_PATH')) {
    $currentPath = $_SERVER['PHP_SELF'];
    $depth = substr_count($currentPath, '/') - 1;
    define('BASE_PATH', str_repeat('../', $depth));
}
include BASE_PATH.'views/post/database.php';


session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['id'])) {
    // 當 username 或 id 未設置時執行的操作
    header("Location: ".BASE_PATH."index.html");
    exit();
}

$sql = "SELECT * FROM users WHERE username=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if ($row = mysqli_fetch_assoc($result)) {
    $photoPath = $row['photoPath'];
    $backgroundPath = $row['BGpath'];
    $ig = $row['ig'];
    $phoneNumber = $row['phoneNumber'];
    $introduction = $row['Introduction'];
}

if (!defined('BASE_PATH')) {
    $currentPath = $_SERVER['PHP_SELF'];
    $depth = substr_count($currentPath, '/') - 1;
    define('BASE_PATH', str_repeat('../', $depth));
}
if (!isset($_SESSION['setting'])){
    $_SESSION['setting'] = 'profile';
}

?>
<!DOCTYPE html>
<html>
<head>
<!-- 預先加載 w3.css -->
<link rel="preload" href="../../../css/w3.css" as="style" onload="this.onload=null;this.rel='stylesheet'">

<!-- 預先加載 style.css -->
<link id="dynamic-css" rel="preload" href="../../../css/style-setting.css?v=initial" as="style" onload="this.onload=null;this.rel='stylesheet'">

<title>BaChi FoodBlog</title>
<meta charset="UTF-8">
<link rel="shortcut icon" href="../../../img/Cookie.webp">
<!--------------------- 字體 ----------------------------->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- 使用defer載入JavaScript，避免阻塞 -->
<script defer>
    document.addEventListener("DOMContentLoaded", function() {
        // 取得當前時間戳
        const timestamp = new Date().getTime();
        // 更新CSS檔案的時間戳參數
        document.getElementById("dynamic-css").href = `../../../css/style-setting.css?v=${timestamp}`;
        // 動態載入JavaScript檔案並加上時間戳
        const script = document.createElement("script");
        script.src = `../../../js/script-setting.js?v=${timestamp} defer`;
        document.head.appendChild(script);
    });
    
</script>
<style>
        body {
            opacity: 0;
        }
</style>
<script defer>
    window.addEventListener('load', function() {
    document.body.style.opacity = '1';
    preloader(); // 如果有其他初始化代碼
});

    
</script>
</head>
<body>
<div id="preloader" style="z-index : 30;"></div>


<!-- Navbar (sit on top) -->
<?php 
include BASE_PATH.'views/navbar/navbar.php'; 
?>

<div id="body-area" class="inBody">
   
    <div id = setting-area>
        <div id = setting-list> <!-- 左邊的選單 -->
            <p id = "setting-title">Setting</p>
            <div id = "setting-button-area">
                <div id = "profile" class = "setting-button"><p>Profile</p></div>
                <div id = "password" class = "setting-button"><p>Password</p></div>
            </div>
        </div>
        <div id = dividingLine></div><!-- 分割線 -->
        <div id = dividingLine_wide></div><!-- 分割線 -->
        <div id = setting-content><!-- 右邊的內容  -->
            <div id = "profile-setting" class = "setting-content">
                <!-- <div  id = "backgroundContainer" style=" background-image: url('');">
                    
                </div>   -->
                <div id = "Background-Container">
                <div  id = "backgroundContainer">
                    <style>
                        #backgroundContainer{
                        background-image: url('<?php echo BASE_PATH.$backgroundPath.'?t='.time() ; ?>');
                        }
                    </style>
                </div>
                <div class="backgroundOverlay">Change</div> 
            </div> 




            
                <div class="profile-container" id = "ImgContianer">
                    <div  id = "profileImg" style="background-image: url('<?php echo BASE_PATH.$photoPath.'?t='.time() ; ?>')"></div>
                    <div class="overlay">Change</div>
                </div>
                <div class="w3-col l12 " id="profile-form">
                            <form action="profile-submit.php" method="post" enctype="multipart/form-data">
                                <div class="form-input"><span>Username : </span>  
                                <?php
                                echo '<input id="form-username"  class="input-space" type="text"  name = "username" value="'. $_SESSION['username'].'" ></input>';
                                ?>
                                </div>
                                <div class="form-input"><span>IG : </span>  
                                <input id="form-ig" class="input-space" type="text"  name = "ig" placeholder="IG account" value = "<?php echo $ig ;?>" ></input>
                                </div>
                                <div class="form-input"><span>Phone Number : </span>  
                                <input id="form-phone" class="input-space" placeholder="Phone Number" type="text"  name = "phone" pattern="\d{10,15}" title="Number Only" minlength = 9 value = "<?php echo $phoneNumber ;?>"></input>
                                </div>
                                <div class="form-input"><span for="About_Me">Personal Introduction:</span>
                                <textarea id="About_Me"  class="input-space" name="form-aboutMe" rows="12" cols="20" placeholder="Tell us about yourself." ><?= ($introduction) ?></textarea></div>
                                
                                <div id = "BGchangeIMG">
                                    <img src = "<?php echo BASE_PATH ;?>img/exit.webp" id = "BGexit" >
                                    <div id = "BGchoseIMG">
                                        <div id = "BGphoto-compare">
                                            <div class = "BGcompare">
                                                <div class = "BGphoto-compare" id = "OldBGImg" style="background-image: url('<?php echo BASE_PATH . $backgroundPath.'?t='.time(); ?>')"></div>
                                                <p>Old IMG</p>
                                            </div>
                                            <img class = "" id="BGarrow" src = "<?php echo BASE_PATH ;?>img/change_arrow.webp" id = "BG-arrow">
                                            <div class = "BGcompare">
                                                <div class = "BGphoto-compare" id = "NewBGImg" style="background-image: url('<?php echo BASE_PATH ; ?>img/unknow_BG.webp')"></div>
                                                <p>New IMG</p>
                                            </div>
                                        </div>
                                        <div id ="BGchangeLine">
                                            <label for="BGIMGbutton">Upload Image</label>
                                            <input type="file" id="BGIMGbutton" name="BGphoto" accept="image/*">
                                            <div id = "changeBGbutton"><p>Submit</p></div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                <div id = "changeIMG">
                                    <img src = "<?php echo BASE_PATH ;?>img/exit.webp" id = "exit" >
                                    <div id = "choseIMG">
                                        <div id = "photo-compare">
                                            <div class = "compare">
                                                <div class = "photo-compare" id = "OldprofileImg" style="background-image: url('<?php echo BASE_PATH . $photoPath.'?t='.time(); ?>')"></div>
                                                <p>Old IMG</p>
                                            </div>
                                            <img class = "photo-compare" src = "<?php echo BASE_PATH ;?>img/change_arrow.webp" id = "photo-arrow">
                                            <div class = "compare">
                                                <div class = "photo-compare" id = "NewprofileImg" style="background-image: url('<?php echo BASE_PATH ; ?>img/unknow.webp')"></div>
                                                <p>New IMG</p>
                                            </div>
                                        </div>
                                        <div id ="changeLine">
                                            <label for="profileIMGbutton">Upload Image</label>
                                            <input type="file" id="profileIMGbutton" name="photo" accept="image/*">
                                            <div id = "changeIMGbutton"><p>Submit</p></div>
                                        </div>
                                    </div>                   





                                </div>





                                <button class="button" type="submit">Update Profile</button>
                            </form>
                </div>

                
            
            <div id = "password-setting" class = "setting-content">
                <div style="width :100%;  display: flex;justify-content: center;">
                    <div class="w3-col l12 " id="password-form">
                    <?php
                        $content ="";
                        if (isset($_GET['error'])){
                            $content = '<p class="error">'. $_GET['error'] . ' </p> ';
                        }
                        else{
                            
                        }
                        echo $content ;
                    ?>
                        <form action="setting-passwordcheck.php" method="post">
                            <div class="form-input"><span>Old Password : </span><input type="password" placeholder="Enter your old password" name="oldpassword"></input></div>
                            <div class="form-input"><span>New Password : </span><input type="password" placeholder="Enter your new password" name="newpassword"></input></div>
                            <div class="form-input"><span>Confirm New Password : </span><input type="password" placeholder="Enter your new password again" name="confirmpass"></input></div>
                            <button class="button" type="submit">Update Password</button>

                        </form>
                    </div>
                </div> 
            </div>
            


        </div>

    </div>




</div>

<footer id="footer" class="w3-center  w3-padding-32 inBody">
  <?php include '../../footer/footer.php'; ?>
</footer>


<script>
    let buttons = document.querySelectorAll(".setting-button"); // 選取所有帶有 class "setting-button" 的按鈕
    let nowShow = document.getElementById('<?php echo $_SESSION['setting'] ; ?>-setting'); // 預設顯示的設定區域
    let nowShowButton = document.getElementById("<?php echo $_SESSION['setting'] ; ?>"); // 預設顯示的按鈕
    if (nowShow) {
        // 顯示新的設定區域，並隱藏其他設定區域
        document.querySelectorAll('.setting-content').forEach(area => {
            area.style.display = 'none'; // 隱藏所有設定區域
        });
        nowShow.style.display = 'flex'; // 顯示當前設定區域
    }

    // 檢查 nowShowButton 是否存在
    if (nowShowButton) {
        nowShowButton.style.background = '#E6B566'; // 設定初始按鈕的背景顏色
    }

</script>

<?php include BASE_PATH.'views/cookie/cookie.php'; ?>


</body>
</html>
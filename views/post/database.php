
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<script>
// 檢測連線錯誤並自動修復
window.onerror = function(msg) {
    if (msg.includes('driver') || msg.includes('連接失敗')) {
        console.log('檢測到資料庫錯誤，正在重新整理...');
        location.reload(true);
        return true;
    }
};
</script>

<?php
$db_path = BASE_PATH.'MIS.db3'; // 請替換為您的db3檔案路徑
$conn = new PDO("sqlite:$db_path");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>







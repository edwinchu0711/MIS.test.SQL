<?php
$db_path = BASE_PATH.'MIS.db3'; // 請替換為您的db3檔案路徑
$conn = new PDO("sqlite:$db_path");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
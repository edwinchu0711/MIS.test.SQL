
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<?php
    if (!defined('BASE_PATH')) {
        $currentPath = $_SERVER['PHP_SELF'];
        $depth = substr_count($currentPath, '/') - 1;
        define('BASE_PATH', str_repeat('../', $depth));
    }
    include BASE_PATH.'views/post/database.php' ;

  

  if (!$result) {
      die("Query failed: " . $conn->error);
  }
if (!defined('readpost_ASSETS_LOADED')) {
    define('readpost_ASSETS_LOADED', true);
}
    ?>
    <!-- readpost CSS -->
    <link id="readpost-css" rel="stylesheet" href="<?php echo BASE_PATH; ?>css/style-readpost.css">
    <!-- readpost JavaScript -->
    <script>
        // 檢查是否已載入 readpost.js
        if (typeof readpostJsLoaded === 'undefined') {
            var readpostScript = document.createElement('script');
            readpostScript.src = '<?php echo BASE_PATH; ?>js/script-readpost.js';
            readpostScript.onload = function() {
                window.readpostJsLoaded = true;

            };
            document.head.appendChild(readpostScript);
        }
    </script>

<?php
    echo '<div class="w3-content" style="max-width:1100px">';
        echo '<div class="new_posts">';
        if ($result->num_rows > 0) {
            $count = 1;
            while ($row = $result->fetch_assoc()) {
                $animation_class = $count % 2 === 0 ? "hide_left" : "hide_right";
                echo '<div class="allCard p-6" id="' . $row['dish_name'] . $row['id']. '">';
                // 注意這裡添加了 elegant-border 類
                echo '<div class="w3-container w3-padding-64 elegant-border ">';
                echo '<div class="post ' . $animation_class . '" >';
                // 左側內容
                echo '<div class="w3-col l6 w3-padding-large">';
                if ($animation_class === "hide_right") {
                    echo '<img src="' . BASE_PATH . htmlspecialchars($row['photo_path']) . '" 
                          id="post-' . $count . '-img" 
                          class="w3-round w3-image zoom" 
                          alt="' . htmlspecialchars($row['dish_name']) . '" 
                          style="width:100%">';
                } else {
                    echo '<h2 class="w3-center dishtopic" >' . htmlspecialchars($row['dish_name']) . '</h2><br>';
                    echo '<div style="display: flex; align-items: center; justify-content: space-between; width: 100%; position: relative;">
                            <hr class="star-divider" style="width: 100%; border-top: 2px solid #8B7355; margin: 0; position: relative;">
                            <span style="color: #8B7355; background-color: #fcf9f4; padding: 2px; border-radius: 50%; position: absolute; left: 43%; transform: translateX(-50%);">★</span>
                            <span style="color: #8B7355; background-color: #fcf9f4; padding: 2px; border-radius: 50%; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">★</span>
                            <span style="color: #8B7355; background-color: #fcf9f4; padding: 2px; border-radius: 50%; position: absolute; right: 43%; transform: translateX(50%);">★</span>
                            </div>';
                    echo '<p class="w3-large">' . nl2br(htmlspecialchars($row['description'])) . '</p>';
                    echo '<h5 class="w3-padding-16 tiptopic">Eating Tips</h5>';
                    echo '<ol class="w3-text-grey w3-large" id="post-' . $count . '-list">';
                    
                    $tips = explode("\n", htmlspecialchars($row['eating_tips']));
                    foreach ($tips as $tip) {
                        if (trim($tip)) {
                            echo '<li>' . nl2br($tip) . '</li>';
                        }
                    }
                    echo '</ol>';
                }
                echo '</div>';
            
                // 右側內容
                echo '<div class="w3-col l6 w3-padding-large">';
                if ($animation_class === "hide_left") {
                    echo '<img src="' . BASE_PATH . htmlspecialchars($row['photo_path']) . '" 
                          id="post-' . $count . '-img" 
                          class="w3-round w3-image zoom" 
                          alt="' . htmlspecialchars($row['dish_name']) . '" 
                          style="width:100%">';
                } else {
                    echo '<h2 class="w3-center dishtopic">' . htmlspecialchars($row['dish_name']) . '</h2><br>';
                    echo '<div style="display: flex; align-items: center; justify-content: space-between; width: 100%; position: relative;">
                            <hr class="star-divider" style="width: 100%; border-top: 2px solid #8B7355; margin: 0; position: relative;">
                            <span style="color: #8B7355; background-color: #fcf9f4; padding: 2px; border-radius: 50%; position: absolute; left: 43%; transform: translateX(-50%);">★</span>
                            <span style="color: #8B7355; background-color: #fcf9f4; padding: 2px; border-radius: 50%; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">★</span>
                            <span style="color: #8B7355; background-color: #fcf9f4; padding: 2px; border-radius: 50%; position: absolute; right: 43%; transform: translateX(50%);">★</span>
                            </div>';
                    echo '<p class="w3-large">' . nl2br(htmlspecialchars($row['description'])) . '</p>';
                    echo '<h5 class="w3-padding-16 tiptopic">Eating Tips</h5>';
                    echo '<ol class="w3-text-grey w3-large" id="post-' . $count . '-list">';
                    
                    $tips = explode("\n", htmlspecialchars($row['eating_tips']));
                    foreach ($tips as $tip) {
                        if (trim($tip)) {
                            echo '<li>' . nl2br($tip) . '</li>';
                        }
                    }
                    echo '</ol>';
                }
                echo '</div>';
            
                // 底部用戶信息和按鈕
                echo '<div class="w3-col l12 w3-padding-large post-more">';
                echo '<p class="w3-small w3-text-grey w3-center postby">';
                echo '<strong>Post By:</strong> <a href="' . BASE_PATH . 'views/classification/userpost.php?username=' . urlencode($row['username']) . '" class="username-link">' . htmlspecialchars($row['username']) . '</a>';
                echo '</p>';
                
                if (isset($_SESSION['username']) && $row['username'] === $_SESSION['username']) {
                    echo '<div class="w3-center">';
                    echo '<div class="action-buttons">';
                    echo '<a href="' . BASE_PATH . 'views/post/editpost.php?id=' . $row['id'] . '" 
                        class="custom-btn edit-btn"><i class="fas fa-edit"></i> Edit</a> ';
                    echo '<a href="' . BASE_PATH . 'views/post/deletepost.php?id=' . $row['id'] . '" 
                        class="custom-btn delete-btn" 
                        onclick="return confirm(\'Are you sure you want to delete this post?\');"><i class="fas fa-trash"></i> Delete</a>';
                    echo '</div>';
                    echo '</div>';
                }
                
                echo '</div>';
                echo '</div></div></div>';
                echo '<hr class="post-hr">';
                
                $count++;
            }
        } else {
            echo '<p class = "nopostbro">No posts found.</p>';
        }
        echo '</div>';
    echo '</div>';
    $result->data_seek(0);  
?>

<?php
session_start();
include 'database.php';

if (!defined('BASE_PATH')) {
    $currentPath = $_SERVER['PHP_SELF'];
    $depth = substr_count($currentPath, '/') - 1;
    define('BASE_PATH', str_repeat('../', $depth));
}

$error_message = "";
$success_message = "";

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    $error_message = "You must be logged in to edit a post.";
    header('Location: ' . BASE_PATH . 'login.php');
    exit;
}

$username = $_SESSION['username'];
$post_id = intval($_GET['id'] ?? 0);

// Validate if the post exists and belongs to the current user
$stmt = $conn->prepare("SELECT dish_name, dish_type, description, eating_tips, photo_path FROM posts WHERE id = ? AND username = ?");
$stmt->bind_param("is", $post_id, $username);
$stmt->execute();
$stmt->bind_result($dish_name, $dish_type, $description, $eating_tips, $photo_path);
if (!$stmt->fetch()) {
    $error_message = "Post not found or unauthorized access.";
    header('Location: ' . BASE_PATH . 'index.html');
    exit;
}
$stmt->close();

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_dish_name = $_POST['dish_name'] ?? '';
    $new_dish_type = $_POST['dish_type'] ?? '';
    $new_description = $_POST['description'] ?? '';
    
    // Collect all eating tips
    $new_eating_tips_array = [];
    for ($i = 1; $i <= 5; $i++) {
        $tip = $_POST["eating_tip_$i"] ?? '';
        if (!empty(trim($tip))) {
            $new_eating_tips_array[] = trim($tip);
        }
    }
    $new_eating_tips = implode("\n", $new_eating_tips_array);

    $new_photo_path = $photo_path; // Default to the existing photo
    $new_photo_preview = "";

    // Handle file upload
    if (isset($_FILES['dish_photo']) && $_FILES['dish_photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = BASE_PATH . 'views/post/newImg/';
        $file_tmp = $_FILES['dish_photo']['tmp_name'];
        $file_name = basename($_FILES['dish_photo']['name']);
        $unique_file_name = uniqid() . '_' . $file_name;
        $target_file = $upload_dir . $unique_file_name;
        $new_photo_path = 'views/post/newImg/' . $unique_file_name;

        // Validate file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = mime_content_type($file_tmp);

        if (in_array($file_type, $allowed_types)) {
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            if (move_uploaded_file($file_tmp, $target_file)) {
                $new_photo_preview = BASE_PATH . $new_photo_path;
            } else {
                $error_message = "Failed to upload the photo.";
                $new_photo_path = $photo_path;
                $new_photo_preview = "";
            }
        } else {
            $error_message = "Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.";
            $new_photo_path = $photo_path;
            $new_photo_preview = "";
        }
    }

    // Validate fields
    if (empty($new_dish_name) || empty($new_description) || empty($new_eating_tips)) {
        $error_message = "All fields are required!";
    } else {
        // Update post
        $stmt = $conn->prepare("UPDATE posts SET dish_name = ?, dish_type = ?, description = ?, eating_tips = ?, photo_path = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $new_dish_name, $new_dish_type, $new_description, $new_eating_tips, $new_photo_path, $post_id);

        if ($stmt->execute()) {
            $success_message = "Post updated successfully!";
            header('Location: ' . BASE_PATH . 'views/classification/userpost.php?username='.$_SESSION['username']);
            exit;
        } else {
            $error_message = "Failed to update post. Please try again.";
        }
        $stmt->close();
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BaChi FoodBlog</title>
    <link rel="shortcut icon" href="<?php echo BASE_PATH ; ?>img/Cookie.webp">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <style>
        #accordion {
            width: 100%;
            margin-bottom: 20px;
        }
    </style>
</head>


<link rel="stylesheet" href="<?php echo BASE_PATH ; ?>css/style-edit.css">
<link rel="stylesheet" href="<?php echo BASE_PATH ; ?>css/w3.css">

<?php
    include BASE_PATH.'views/navbar/navbar.php';
?>
<div id="preloader" style="z-index : 50;"></div>
<script>


var loader = document.getElementById("preloader")
window.addEventListener("load", function(){

    // 延遲1.5秒後隱藏 preloader

    setTimeout(function(){

        loader.style.transition =  "opacity 1s ease";

        loader.style.opacity = "0";



    }, 1500); // 1500毫秒 = 1.5秒

    setTimeout(function(){

        loader.style.display = "none";

    }, 2500);

});


</script>
<div class="edit-preview-container">
    <!-- Edit Form -->
    <div class="edit-dish">
        <?php if ($error_message): ?>
            <p class="error"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <p class="success"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <label for="dish_name" class="edittitle">Dish Name:</label>
            <input type="text" id="dish_name" name="dish_name" value="<?= htmlspecialchars($dish_name) ?>"><br><br>

            <label for="dish_type"  class="edittitle">Dish Type:</label>
            <select id="dish_type" name="dish_type">
                <option <?= $dish_type === "Rice" ? "selected" : "" ?>>Rice</option>
                <option <?= $dish_type === "Noodles" ? "selected" : "" ?>>Noodles</option>
                <option <?= $dish_type === "Drinks" ? "selected" : "" ?>>Drinks</option>
                <option <?= $dish_type === "Meat" ? "selected" : "" ?>>Meat</option>
                <option <?= $dish_type === "Fast Food and Fried Food" ? "selected" : "" ?>>Fast Food and Fried Food</option>
                <option <?= $dish_type === "Soup" ? "selected" : "" ?>>Soup</option>
                <option <?= $dish_type === "Dessert" ? "selected" : "" ?>>Dessert</option>
                <option <?= $dish_type === "Others" ? "selected" : "" ?>>Others</option>
            </select><br><br>

            
            <label for="description"  class="edittitle">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50"><?= htmlspecialchars($description) ?></textarea><br><br>
           

            <div id="accordion">
                <h3  class="edittitle">Eating Tips:</h3>
                <div id="eating-tips-container">
                    <?php
                    $tips = explode("\n", htmlspecialchars($eating_tips));
                    foreach ($tips as $index => $tip) {
                        if (trim($tip)) {
                            echo '<textarea name="eating_tip_' . ($index + 1) . '" rows="2" cols="50">' . nl2br($tip) . '</textarea><br>';
                        }
                    }
                    ?>
                </div>
                <button type="button" id="add-tip-button">Add Tip</button>
                <p id="tip-limit-message" style="display:none;">You can add up to 5 tips only.</p>
            </div>


           
           

            <label for="dish_photo">Update Photo</label>
            <input type="file" id="dish_photo" name="dish_photo" accept="image/*" onchange="previewNewPhoto(event)"><br><br>

           

            <button type="submit">Update Dish</button>
        </form>
    </div>

    <!-- Preview -->
    <div class="preview-dish p-6">
        <div class="preview-content elegant-border w3-padding-16">
            <div class="preview-text">
                <h2 class="w3-center"><?= htmlspecialchars($dish_name) ?></h2>
                <div style="display: flex; align-items: center; justify-content: space-between; width: 100%; position: relative;">
                            <hr class="star-divider" style="width: 100%; border-top: 2px solid #8B7355; margin: 0; position: relative;">
                            <span style="color: #8B7355; background-color: #fcf9f4; padding: 2px; border-radius: 50%; position: absolute; left: 43%; transform: translateX(-50%);">★</span>
                            <span style="color: #8B7355; background-color: #fcf9f4; padding: 2px; border-radius: 50%; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">★</span>
                            <span style="color: #8B7355; background-color: #fcf9f4; padding: 2px; border-radius: 50%; position: absolute; right: 43%; transform: translateX(50%);">★</span>
                </div>
                <p class="w3-large"><?= nl2br(htmlspecialchars($description)) ?></p>
                <h4 class="w3-padding-16">Eating Tips</h4>
                <ol class="w3-text-grey w3-large">
                    <?php
                    $tips = explode("\n", htmlspecialchars($eating_tips));
                    foreach ($tips as $tip) {
                        if (trim($tip)) {
                            echo '<li>' . nl2br($tip) . '</li>';
                        }
                    }
                    ?>
                </ol>
            </div>
            <div class="preview-image">
                <img id="new_photo_preview" 
                    src="<?= isset($new_photo_preview) && $new_photo_preview ? htmlspecialchars($new_photo_preview) : htmlspecialchars(BASE_PATH . $photo_path) ?>" 
                    alt="Dish Photo" 
                    style="display: block;">
            </div>
        </div>
    </div>
</div>

<footer id="footer-end" class="w3-center  w3-padding-32">
  <?php include BASE_PATH.'views/footer/footer.php'; ?>
</footer>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dishNameInput = document.getElementById('dish_name');
        const descriptionInput = document.getElementById('description');
        const eatingTipsContainer = document.getElementById('eating-tips-container');
        const addTipButton = document.getElementById('add-tip-button');
        const tipLimitMessage = document.getElementById('tip-limit-message');

        // 修正選擇器
        const previewDishName = document.querySelector('.preview-dish h2');
        const previewDescription = document.querySelector('.preview-dish p.w3-large');
        const previewEatingTips = document.querySelector('.preview-dish ol.w3-text-grey');

        // 防止元素不存在時出錯
        if (!dishNameInput || !descriptionInput || !previewDishName || !previewDescription || !previewEatingTips) {
            console.error("One or more elements are missing in the DOM.");
            return;
        }

        let tipCount = eatingTipsContainer.querySelectorAll('textarea').length;

        const updatePreview = () => {
            // 更新預覽區域內容
            previewDishName.textContent = dishNameInput.value || "Dish Name Preview";
            previewDescription.innerHTML = nl2br(descriptionInput.value || "Description Preview");
            updateEatingTipsPreview();
        };

        const nl2br = (str) => {
            return str.replace(/\n/g, '<br>');
        };

        const updateEatingTipsPreview = () => {
            const tipsArray = Array.from(eatingTipsContainer.querySelectorAll('textarea'))
                .map(textarea => textarea.value.trim())
                .filter(tip => tip !== '');
            previewEatingTips.innerHTML = tipsArray.map(tip => `<li>${tip}</li>`).join('');
        };

        // 綁定事件
        dishNameInput.addEventListener('input', updatePreview);
        descriptionInput.addEventListener('input', updatePreview);
        eatingTipsContainer.addEventListener('input', updatePreview);

        addTipButton.addEventListener('click', () => {
            if (tipCount < 5) {
                tipCount++;
                const newTip = document.createElement('textarea');
                newTip.name = `eating_tip_${tipCount}`;
                newTip.rows = 2;
                newTip.cols = 50;
                eatingTipsContainer.appendChild(newTip);
                eatingTipsContainer.appendChild(document.createElement('br'));
                updatePreview();
            }
            if (tipCount >= 5) {
                addTipButton.style.display = 'none';
                tipLimitMessage.style.display = 'block';
            }
        });

        // 初始化預覽
        updatePreview();
    });

    function previewNewPhoto(event) {
        const preview = document.getElementById('new_photo_preview');
        const file = event.target.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        } else {
            preview.src = '<?= htmlspecialchars(BASE_PATH . $photo_path) ?>';
            preview.style.display = 'block';
        }
    }
</script>
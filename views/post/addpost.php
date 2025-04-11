<?php

if (!defined('BASE_PATH')) {
    $currentPath = $_SERVER['PHP_SELF'];
    $depth = substr_count($currentPath, '/') - 1;
    define('BASE_PATH', str_repeat('../', $depth));
}

$error_message = "";
$success_message = "";
?>

<link rel="stylesheet" href="<?php echo BASE_PATH ; ?>css/style-addpost.css">
<link rel="stylesheet" href="<?php echo BASE_PATH ; ?>css/w3.css">

<div class="submit-dish w3-content">
    <?php if ($error_message): ?>
        <p class="error"><?= htmlspecialchars($error_message) ?></p>
    <?php endif; ?>

    <?php if ($success_message): ?>
        <p class="success"><?= htmlspecialchars($success_message) ?></p>
    <?php endif; ?>

    <form id="addpost-form" method="POST" autocomplete="off" action="<?php echo BASE_PATH ;?>views/post/formsubmit.php" enctype="multipart/form-data">

        <p  class="addphoto" for="dish_name">Dish Name:</p>
        <input type="text" id="dish_name" name="dish_name" value=""><br><br>

            <p class="addphoto" for="dish_type">Dish Type:</p>
            <select id="dish_type" name="dish_type">
                <option selected="selected">Rice</option>
                <option>Noodles</option>
                <option>Drinks</option>
                <option>Meat</option>
                <option>Fast Food and Fried Food</option>
                <option>Soup</option>
                <option>Dessert</option>
                <option>Others</option>
            </select>
            <br><br>


        <p class="addphoto" for="description">Description:</p><br>
        <textarea id="description" name="description" rows="4" cols="50"></textarea><br><br>

        <p class="addphoto" for="eating_tips">Eating Tips:</p><br>
        <div id="eating-tips-container">
            <textarea name="eating_tip_1" rows="2" cols="50"></textarea><br>
        </div>
        <button type="button" id="add-tip-button">Add Tip</button>
        <p id="tip-limit-message" style="display:none;">You can add up to 5 tips only.</p><br>

        <div class="photocontainer">
        <p class="addphoto">Photo:</p>

        <label for="dish_photo">Select Photo</label>
        <input type="file" id="dish_photo" name="dish_photo" accept="image/*" onchange="previewNewPhoto(event)"><br>

        </div>

        <p class="pretitle">New Photo Preview:</p>
        <img id="new_photo_preview" src="" alt="New Dish Photo" style="max-width: 300px; display: none;"><br>

        

        <button type="submit">Add Post</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const eatingTipsContainer = document.getElementById('eating-tips-container');
    const addTipButton = document.getElementById('add-tip-button');
    const tipLimitMessage = document.getElementById('tip-limit-message');

    let tipCount = eatingTipsContainer.querySelectorAll('textarea').length;

    addTipButton.addEventListener('click', () => {
        if (tipCount < 5) {
            tipCount++;
            const newTip = document.createElement('textarea');
            newTip.name = `eating_tip_${tipCount}`;
            newTip.rows = 2;
            newTip.cols = 50;
            eatingTipsContainer.appendChild(newTip);
            eatingTipsContainer.appendChild(document.createElement('br'));
        }
        if (tipCount >= 5) {
            addTipButton.style.display = 'none';
            tipLimitMessage.style.display = 'block';
        }
    });
});

function previewNewPhoto(event) {
    const preview = document.getElementById('new_photo_preview');
    const file = event.target.files[0];
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    } else {
        preview.src = '';
        preview.style.display = 'none';
    }
}
</script>

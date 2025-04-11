<?php
session_start();

if (!defined('BASE_PATH')) {
    $currentPath = $_SERVER['PHP_SELF'];
    $depth = substr_count($currentPath, '/') - 1;
    define('BASE_PATH', str_repeat('../', $depth));
}
include BASE_PATH.'views/post/database.php'; // Include database connection

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_SESSION['username'];
    $dish_name = $_POST['dish_name'] ?? '';
    $dish_type = $_POST['dish_type'] ?? '';
    $description = $_POST['description'] ?? '';
    
    // Collect all eating tips
    $eating_tips_array = [];
    for ($i = 1; $i <= 5; $i++) {
        $tip = $_POST["eating_tip_$i"] ?? '';
        if (!empty(trim($tip))) {
            $eating_tips_array[] = trim($tip);
        }
    }
    $eating_tips = implode("\n", $eating_tips_array);

    // Check if file is uploaded
    if (isset($_FILES['dish_photo']) && $_FILES['dish_photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = BASE_PATH .'views/post/newImg/'; // Upload directory
        $file_tmp = $_FILES['dish_photo']['tmp_name'];
        $file_name = basename($_FILES['dish_photo']['name']);
        $target_file = $upload_dir . $file_name;

        // Validate file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = mime_content_type($file_tmp);

        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($file_tmp, $target_file)) {
                // Insert data into MySQL
                $stmt = $conn->prepare("INSERT INTO posts (username, dish_name, dish_type, description, eating_tips, photo_path) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $username, $dish_name, $dish_type, $description, $eating_tips, $target_file);

                if ($stmt->execute()) {
                    // Success message and redirect
                    $success_message = "Dish added successfully!";

                    // Clear form data
                    $_POST = [];

                    echo '<script>
                            alert("'.$success_message.'");
                            window.history.back();
                        </script>';
                    exit();
                    
                } else {
                    $error_message = "Failed to save post to database.";
                }

                $stmt->close();
            } else {
                $error_message = "Failed to upload the photo.";
            }
        } else {
            $error_message = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
        }
    } else {
        $error_message = "Please upload a photo of the dish.";
    }

    // Validate fields
    if (empty($dish_name) || empty($description) || empty($eating_tips)) {
        $error_message = "All fields are required!";
    }
    echo $error_message;
}
?>

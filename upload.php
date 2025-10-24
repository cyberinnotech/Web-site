<?php
session_start();
include 'db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.html");
    exit;
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_pic"])) {
    $target_dir = "uploads/";
    $message = '';
    $uploadOk = 1;

    // --- FIX: Secure File Upload Validation ---

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
    if($check === false) {
        $message = "Error: File is not an image.";
        $uploadOk = 0;
    }

    // Check file extension
    $imageFileType = strtolower(pathinfo(basename($_FILES["profile_pic"]["name"]), PATHINFO_EXTENSION));
    $allowed_extensions = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowed_extensions)) {
        $message = "Error: Only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check file size (e.g., 5MB limit)
    if ($_FILES["profile_pic"]["size"] > 5000000) {
        $message = "Error: Your file is too large.";
        $uploadOk = 0;
    }

    // If all checks pass, proceed with upload
    if ($uploadOk == 1) {
        // Create a new, unique filename to prevent conflicts and directory traversal
        $new_filename = uniqid('', true) . '.' . $imageFileType;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            // File uploaded successfully, now update the user's profile_image_url in the DB
            $sql = "UPDATE users SET profile_image_url = ? WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("si", $new_filename, $_SESSION['id']);
                $stmt->execute();
                $stmt->close();
                $message = "The file has been uploaded successfully.";
            } else {
                $message = "Sorry, there was an error updating your profile.";
                // Optionally, delete the uploaded file if DB update fails
                unlink($target_file);
            }
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    }
    // --- END FIX ---

    header("location: profile.php?id=".$_SESSION['id']."&message=".urlencode($message));
} else {
    header("location: profile.php?id=".$_SESSION['id']);
}

$conn->close();
?>

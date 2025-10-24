<?php
include 'db.php';

// New password for the admin user
$new_password = 'Password123!';
$admin_username = 'admin';

// Hash the new password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Prepare the UPDATE statement
$sql = "UPDATE users SET password = ? WHERE username = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ss", $hashed_password, $admin_username);
    
    if ($stmt->execute()) {
        echo "<h1>Admin Password Updated Successfully!</h1>";
        echo "<p>The admin password has been changed to: <strong>" . htmlspecialchars($new_password) . "</strong></p>";
        echo "<p style='color:red;'><b>IMPORTANT:</b> Please delete this file (fix_admin.php) from the 'www' directory immediately!</p>";
    } else {
        echo "<h1>Error!</h1>";
        echo "<p>Could not execute the password update statement.</p>";
    }
    
    $stmt->close();
} else {
    echo "<h1>Error!</h1>";
    echo "<p>Could not prepare the SQL statement.</p>";
}

$conn->close();
?>
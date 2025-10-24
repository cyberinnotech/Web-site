<?php
echo "<h1>Application State Reset</h1>";

// Part 1: Reset Admin User
echo "<h2>1. Resetting Admin Account...</h2>";
include 'db.php';

$admin_username = 'admin';
$new_password = 'Password123!';
$admin_email = 'admin@example.com';

// Hash the new password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Delete existing admin user to prevent conflicts
$delete_sql = "DELETE FROM users WHERE username = ?";
if ($stmt = $conn->prepare($delete_sql)) {
    $stmt->bind_param("s", $admin_username);
    $stmt->execute();
    echo "<p>Previous admin account removed (if it existed).</p>";
    $stmt->close();
}

// Insert the new admin user
$insert_sql = "INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, 1)";
if ($stmt = $conn->prepare($insert_sql)) {
    $stmt->bind_param("sss", $admin_username, $admin_email, $hashed_password);
    if ($stmt->execute()) {
        echo "<p style='color:green; font-weight:bold;'>New admin account created successfully!</p>";
        echo "<p>Username: " . htmlspecialchars($admin_username) . "</p>";
        echo "<p>Password: " . htmlspecialchars($new_password) . "</p>";
    } else {
        echo "<p style='color:red;'>Error: Could not create new admin account.</p>";
    }
    $stmt->close();
} else {
    echo "<p style='color:red;'>Error: Could not prepare the admin creation statement.</p>";
}
$conn->close();
echo "<hr>";

// Part 2: Check Session Writable
echo "<h2>2. Checking Session Configuration...</h2>";
$session_path = session_save_path();
if (empty($session_path)) {
    $session_path = sys_get_temp_dir(); // Default path if not set
}

echo "<p>Session save path is: " . htmlspecialchars($session_path) . "</p>";
if (is_writable($session_path)) {
    echo "<p style='color:green; font-weight:bold;'>Session path is writable by the server.</p>";
} else {
    echo "<p style='color:red;'>Error: Session path is NOT writable by the server. This is likely the cause of the login issue.</p>";
}
echo "<hr>";

echo "<p style='color:red;'><b>IMPORTANT:</b> Please delete this file (reset_app.php) from the 'www' directory now!</p>";
?>
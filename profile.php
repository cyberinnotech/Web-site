<?php
session_start();
include 'db.php';

// Authorization: Must be logged in to view profiles
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.html");
    exit;
}

// VULNERABILITY: IDOR - No check to see if the logged-in user is the owner of the profile
$profile_id = isset($_GET['id']) ? (int)$_GET['id'] : $_SESSION['id'];

// Handle profile updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_bio'])) {
    if ($profile_id == $_SESSION['id']) { // Basic check to ensure you only update your own bio
        // VULNERABILITY: Stored XSS - bio is not sanitized before being stored
        $new_bio = $_POST['bio'];
        $update_sql = "UPDATE users SET bio = ? WHERE id = ?";
        if ($stmt = $conn->prepare($update_sql)) {
            $stmt->bind_param("si", $new_bio, $profile_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Fetch profile data
$sql = "SELECT id, username, email, bio, profile_image_url, phone_number, address FROM users WHERE id = ?";
$user = null;
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $profile_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    }
    $stmt->close();
}

if ($user === null) {
    echo "User not found.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <style>
        body { background-color: #0D1B2A; color: #E0E1DD; }
        .card { background-color: #1B263B; }
        .btn { background-color: #00FFFF; color: #0D1B2A; transition: all 0.3s ease; }
        .btn:hover { background-color: #0D1B2A; color: #00FFFF; border: 1px solid #00FFFF; }
    </style>
</head>
<body>
    <div class="container mx-auto p-8">
        <div class="card rounded-lg p-8 max-w-2xl mx-auto">
            <a href="<?php echo ($_SESSION['is_admin'] == 1) ? 'view_users.php' : 'workspace.php'; ?>" class="text-cyan-400 hover:underline">&larr; Back</a>
            <div class="text-center mt-4">
                <img src="uploads/<?php echo htmlspecialchars($user['profile_image_url']); ?>" alt="Profile Picture" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover border-4 border-cyan-400">
                <h1 class="text-3xl font-bold"><?php echo htmlspecialchars($user['username']); ?></h1>
                <p class="text-gray-400"><?php echo htmlspecialchars($user['email']); ?></p>
                <div class="mt-4 text-gray-400">
                    <p class="flex items-center justify-center"><i data-feather="phone" class="mr-2 h-4 w-4"></i> <?php echo htmlspecialchars($user['phone_number']); ?></p>
                    <p class="flex items-center justify-center mt-1"><i data-feather="map-pin" class="mr-2 h-4 w-4"></i> <?php echo htmlspecialchars($user['address'] ?? ''); ?></p>
                </div>
            </div>

            <div class="mt-8">
                <h2 class="text-xl font-bold mb-2">Bio</h2>
                
                <div class="prose prose-invert p-4 bg-gray-800 rounded-md min-h-[100px]"><?php echo $user['bio']; ?></div>
            </div>

            <?php if ($user['id'] == $_SESSION['id']): // Show edit forms only to the profile owner ?>
                <div class="mt-8">
                    <h2 class="text-xl font-bold mb-4">Update Your Profile</h2>
                    
                    <!-- Bio Update Form -->
                    <form action="profile.php?id=<?php echo $profile_id; ?>" method="POST">
                        <label for="bio" class="block mb-2">Update Bio </label>
                        <textarea name="bio" class="w-full p-2 rounded bg-gray-700 text-white" rows="4"><?php echo htmlspecialchars($user['bio']); ?></textarea>
                        <button type="submit" name="update_bio" class="btn font-bold py-2 px-4 rounded mt-2">Save Bio</button>
                    </form>

                    <hr class="my-8 border-gray-600">

                    <!-- Profile Picture Upload Form -->
                    <form action="upload.php" method="POST" enctype="multipart/form-data">
                        <label for="profile_pic" class="block mb-2">Upload New Profile Picture </label>
                        <input type="file" name="profile_pic" class="w-full p-2 rounded bg-gray-700 text-white">
                        <button type="submit" class="btn font-bold py-2 px-4 rounded mt-2">Upload Image</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script>feather.replace();</script>
</body>
</html>

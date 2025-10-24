<?php
session_start();
include 'db.php';

// Authorization check
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    session_destroy();
    header("location: login.html?error=unauthorized");
    exit;
}

$users = [];
$search_term = '';

// FIX: Use prepared statement to prevent SQL Injection in search
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = $_GET['search'];
    $sql = "SELECT id, username, email, phone_number FROM users WHERE username LIKE ?";
    $stmt = $conn->prepare($sql);
    $like_search_term = "%" . $search_term . "%";
    $stmt->bind_param("s", $like_search_term);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT id, username, email, phone_number FROM users";
    $result = $conn->query($sql); // No user input, so direct query is safe here
}

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #0D1B2A; color: #E0E1DD; }
        h1, h2 { font-family: 'Poppins', sans-serif; }
        .card { background-color: #1B263B; }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header -->
    <header class="bg-[#1B263B] shadow-md p-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold flex items-center"><i data-feather="shield" class="mr-2"></i>Admin Panel</h1>
        <div>
            <span class="mr-4">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</span>
            <a href="logout.php" class="text-cyan-400 hover:underline">Logout</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-8">
        <div class="flex gap-8">
            <!-- Left Column: Navigation -->
            <div class="w-1/4">
                <div class="card p-6 rounded-lg">
                    <h2 class="text-xl font-bold mb-4">Navigation</h2>
                    <ul class="space-y-2">
                        <li><a href="admin.php" class="flex items-center p-2 hover:bg-gray-700 rounded-md"><i data-feather="trello" class="mr-3"></i>Dashboard</a></li>
                        <li><a href="#" class="flex items-center p-2 text-cyan-400 font-bold rounded-md bg-gray-700"><i data-feather="users" class="mr-3"></i>User Management</a></li>
                        <li><a href="admin_editor.php" class="flex items-center p-2 hover:bg-gray-700 rounded-md"><i data-feather="edit" class="mr-3"></i>Theme Editor</a></li>
                        <li><a href="profile.php?id=<?php echo $_SESSION['id']; ?>" class="flex items-center p-2 hover:bg-gray-700 rounded-md"><i data-feather="user" class="mr-3"></i>My Profile</a></li>
                        <li><a href="index.html" class="flex items-center p-2 hover:bg-gray-700 rounded-md"><i data-feather="home" class="mr-3"></i>Main Site</a></li>
                    </ul>
                </div>
            </div>

            <!-- Right Column: User Table -->
            <div class="w-3/4">
                <div class="card p-6 rounded-lg">
                    <h2 class="text-2xl font-bold mb-4">User List</h2>

                    <!-- Search Form -->
                    <form method="GET" action="view_users.php" class="mb-4">
                        <div class="flex">
                            <input type="text" name="search" class="w-full p-2 rounded-l-md bg-gray-700 text-white" placeholder="Search by username..." value="<?php echo htmlspecialchars($search_term); ?>">
                            <button type="submit" class="bg-cyan-500 text-white p-2 rounded-r-md">Search</button>
                        </div>
                    </form>

                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-600">
                                <th class="p-2">ID</th>
                                <th class="p-2">Username</th>
                                <th class="p-2">Email</th>
                                <th class="p-2">Téléphone</th>
                                <th class="p-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-gray-700">
                                    <td class="p-2"><?php echo $user['id']; ?></td>
                                    <td class="p-2"><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td class="p-2"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td class="p-2"><?php echo htmlspecialchars($user['phone_number'] ?? ''); ?></td>
                                    <td class="p-2"><a href="profile.php?id=<?php echo $user['id']; ?>" class="text-cyan-400 hover:underline">View Profile</a></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="4" class="text-center p-4">No users found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>feather.replace();</script>
</body>
</html>

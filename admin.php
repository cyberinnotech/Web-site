<?php
session_start();

// Authorization check: user must be logged in AND be an admin.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    session_destroy();
    header("location: login.html?error=unauthorized");
    exit;
}

// Fake system info for realism
$php_version = phpversion();
$server_ip = '10.10.0.20'; // From Vagrantfile
$db_status = 'Connected'; // Assume connected if we are here

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #0D1B2A; color: #E0E1DD; }
        h1, h2 { font-family: 'Poppins', sans-serif; }
        .card { background-color: #1B263B; }
        .btn { background-color: #00FFFF; color: #0D1B2A; transition: all 0.3s ease; }
        .btn:hover { background-color: #0D1B2A; color: #00FFFF; border: 1px solid #00FFFF; }
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left Column: Navigation -->
            <div class="md:col-span-1">
                <div class="card p-6 rounded-lg">
                    <h2 class="text-xl font-bold mb-4">Navigation</h2>
                    <ul class="space-y-2">
                        <li><a href="#" class="flex items-center p-2 text-cyan-400 font-bold rounded-md bg-gray-700"><i data-feather="trello" class="mr-3"></i>Dashboard</a></li>
                        <li><a href="view_users.php" class="flex items-center p-2 hover:bg-gray-700 rounded-md"><i data-feather="users" class="mr-3"></i>User Management</a></li>
                        <li><a href="admin_editor.php" class="flex items-center p-2 hover:bg-gray-700 rounded-md"><i data-feather="edit" class="mr-3"></i>Theme Editor</a></li>
                        <li><a href="profile.php?id=<?php echo $_SESSION['id']; ?>" class="flex items-center p-2 hover:bg-gray-700 rounded-md"><i data-feather="user" class="mr-3"></i>My Profile</a></li>
                        <li><a href="index.html" class="flex items-center p-2 hover:bg-gray-700 rounded-md"><i data-feather="home" class="mr-3"></i>Main Site</a></li>
                    </ul>
                </div>
            </div>

            <!-- Right Column: Content -->
            <div class="md:col-span-2 space-y-8">
                <!-- System Info Card -->
                <div class="card p-6 rounded-lg">
                    <h2 class="text-xl font-bold mb-4">System Information</h2>
                    <ul class="divide-y divide-gray-600">
                        <li class="py-2 flex justify-between"><span>PHP Version:</span> <span class="font-mono"><?php echo $php_version; ?></span></li>
                        <li class="py-2 flex justify-between"><span>Server IP:</span> <span class="font-mono"><?php echo $server_ip; ?></span></li>
                        <li class="py-2 flex justify-between"><span>Database Status:</span> <span class="text-green-400"><?php echo $db_status; ?></span></li>
                    </ul>
                </div>

                <!-- Quick Actions Card -->
                <div class="card p-6 rounded-lg">
                    <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
                    <div class="flex gap-4">
                        <a href="view_users.php" class="btn font-bold py-2 px-4 rounded-lg flex-1 text-center">Manage Users</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>feather.replace();</script>
</body>
</html>

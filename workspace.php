<?php
session_start();

// Authorization: Must be logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.html");
    exit;
}

// If an admin somehow lands here, redirect them to their dashboard
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
    header("location: admin.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Employee Workspace</title>
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
        <h1 class="text-2xl font-bold flex items-center"><i data-feather="briefcase" class="mr-2"></i>My Workspace</h1>
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
                        <li><a href="#" class="flex items-center p-2 text-cyan-400 font-bold rounded-md bg-gray-700"><i data-feather="layout" class="mr-3"></i>Dashboard</a></li>
                        <li><a href="profile.php?id=<?php echo $_SESSION['id']; ?>" class="flex items-center p-2 hover:bg-gray-700 rounded-md"><i data-feather="user" class="mr-3"></i>My Profile</a></li>
                        <li><a href="index.html" class="flex items-center p-2 hover:bg-gray-700 rounded-md"><i data-feather="home" class="mr-3"></i>Main Site</a></li>
                    </ul>
                </div>
            </div>

            <!-- Right Column: Content -->
            <div class="w-3/4 space-y-8">
                <!-- Announcements Card -->
                <div class="card p-6 rounded-lg">
                    <h2 class="text-xl font-bold mb-4">Company Announcements</h2>
                    <ul class="divide-y divide-gray-600">
                        <li class="py-3">
                            <p class="font-bold">Q4 Performance Review</p>
                            <p class="text-sm text-gray-400">All employees must complete their self-assessment by October 25th.</p>
                        </li>
                        <li class="py-3">
                            <p class="font-bold">New Coffee Machine</p>
                            <p class="text-sm text-gray-400">A new coffee machine has been installed in the break room. Enjoy!</p>
                        </li>
                    </ul>
                </div>

                <!-- My Tasks Card -->
                <div class="card p-6 rounded-lg">
                    <h2 class="text-xl font-bold mb-4">My Tasks</h2>
                    <ul class="space-y-3">
                        <li class="flex items-center"><input type="checkbox" class="form-checkbox h-5 w-5 bg-gray-700 border-gray-600 text-cyan-500" checked> <span class="ml-3">Finalize project report</span></li>
                        <li class="flex items-center"><input type="checkbox" class="form-checkbox h-5 w-5 bg-gray-700 border-gray-600 text-cyan-500"> <span class="ml-3">Submit expense claims</span></li>
                        <li class="flex items-center"><input type="checkbox" class="form-checkbox h-5 w-5 bg-gray-700 border-gray-600 text-cyan-500"> <span class="ml-3">Book annual leave</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <script>feather.replace();</script>
</body>
</html>

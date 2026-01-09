<?php
// ADD THIS AT THE VERY TOP OF home.php
require_once '../backend/config/database.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user's name from session
$user_name = $_SESSION['full_name'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpotBro - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-50">

    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <img src="images/logo.png" alt="SpotBro Logo" class="h-12">
            <div class="flex items-center space-x-6">
                <a href="home.php" class="nav-item active flex items-center space-x-2 px-4 py-2 rounded-lg">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <span class="font-medium">Home</span>
                </a>
                <a href="exercises.php" class="nav-item flex items-center space-x-2 px-4 py-2 rounded-lg">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="m6.5 6.5 11 11"></path>
                        <path d="m21 21-1-1"></path>
                        <path d="m3 21 9-9"></path>
                        <circle cx="10.5" cy="10.5" r="7.5"></circle>
                    </svg>
                    <span class="font-medium">Exercises</span>
                </a>
                <a href="progress.php" class="nav-item flex items-center space-x-2 px-4 py-2 rounded-lg">
                    <svg class="icon" viewBox="0 0 24 24">
                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                        <polyline points="16 7 22 7 22 13"></polyline>
                    </svg>
                    <span class="font-medium">Progress</span>
                </a>
                <a href="logout.php" class="nav-item flex items-center space-x-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-50">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" x2="9" y1="12" y2="12"></line>
                    </svg>
                    <span class="font-medium">Logout</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Dashboard Page -->
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto p-6">
            <div class="mb-8">
                <!-- FIXED LINE: Changed "Andrea" to dynamic PHP variable -->
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome back, <?php echo htmlspecialchars($user_name); ?>!</h1>
                <p class="text-gray-600">Ready to improve your form today?</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="stat-card rounded-2xl p-6 text-white" style="--tw-gradient-from: #3b82f6; --tw-gradient-to: #2563eb;">
                    <div class="flex items-center justify-between mb-4">
                        <svg class="icon-lg opacity-80" viewBox="0 0 24 24">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                            <line x1="16" x2="16" y1="2" y2="6"></line>
                            <line x1="8" x2="8" y1="2" y2="6"></line>
                            <line x1="3" x2="21" y1="10" y2="10"></line>
                        </svg>
                        <span class="text-3xl font-bold">12</span>
                    </div>
                    <p class="text-blue-100">Workouts This Week</p>
                </div>
                <div class="stat-card rounded-2xl p-6 text-white" style="--tw-gradient-from: #a855f7; --tw-gradient-to: #9333ea;">
                    <div class="flex items-center justify-between mb-4">
                        <svg class="icon-lg opacity-80" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="12" r="6"></circle>
                            <circle cx="12" cy="12" r="2"></circle>
                        </svg>
                        <span class="text-3xl font-bold">86%</span>
                    </div>
                    <p class="text-purple-100">Average Form Score</p>
                </div>
                <div class="stat-card rounded-2xl p-6 text-white" style="--tw-gradient-from: #22c55e; --tw-gradient-to: #16a34a;">
                    <div class="flex items-center justify-between mb-4">
                        <svg class="icon-lg opacity-80" viewBox="0 0 24 24">
                            <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                            <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                            <path d="M4 22h16"></path>
                            <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                            <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                            <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                        </svg>
                        <span class="text-3xl font-bold">7</span>
                    </div>
                    <p class="text-green-100">Day Streak</p>
                </div>
            </div>

            <!-- Quick Start -->
            <div class="bg-white rounded-2xl p-8 shadow-sm mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Quick Start</h2>
                <a href="exercises.php" class="btn w-full gradient-button text-white py-4 rounded-xl font-semibold text-lg flex items-center justify-center space-x-2">
                    <svg class="icon" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polygon points="10 8 16 12 10 16 10 8"></polygon>
                    </svg>
                    <span>Start New Workout</span>
                </a>
            </div>

            <!-- Recent Workouts -->
            <div class="bg-white rounded-2xl p-8 shadow-sm">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Recent Workouts</h2>
                <div class="space-y-4" id="recentWorkouts"></div>
            </div>
        </div>
    </div>

</body>
</html>
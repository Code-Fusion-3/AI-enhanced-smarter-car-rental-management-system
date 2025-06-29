<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Car Rental System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="assets/css/chat.css" rel="stylesheet"> <!-- Add this line -->

    <!-- Add before closing body tag -->
    <?php require 'views/components/chat-widget.php'; ?>
    <script src="assets/js/chat.js?v=<?php echo time(); ?>"></script>

</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="index.php" class="text-xl font-bold text-blue-600">Smart Car Rental</a>
                </div>
                <div class="flex space-x-4">
                    <a href="index.php" class="text-gray-700 hover:text-blue-600">Home</a>
                    <a href="index.php?page=cars" class="text-gray-700 hover:text-blue-600">Cars</a>
                    <?php if (isLoggedIn()): ?>
                    <a href="index.php?page=rentals" class="text-gray-700 hover:text-blue-600">My Rentals</a>
                    <?php if (isAdmin()): ?>
                    <a href="index.php?page=admin&action=dashboard"
                        class="text-gray-700 hover:text-blue-600">Dashboard</a>
                    <?php endif; ?>
                    <a href="index.php?page=profile" class="text-gray-700 hover:text-blue-600">My Profile</a>
                    <a href="index.php?page=auth&action=logout" class="text-gray-700 hover:text-blue-600">Logout</a>
                    <?php else: ?>
                    <a href="index.php?page=auth&action=login" class="text-gray-700 hover:text-blue-600">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <?php require_once 'views/components/notifications.php'; ?>
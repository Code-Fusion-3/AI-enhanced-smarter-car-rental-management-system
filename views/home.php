<?php require 'views/layouts/header.php'; ?>

<div class="bg-white">
    <!-- Hero Section -->
    <div class="relative bg-blue-600 h-[500px]">
        <div class="max-w-6xl mx-auto px-4 py-32">
            <h1 class="text-4xl font-bold text-white mb-4">
                Welcome to Smart Car Rental
            </h1>
            <p class="text-xl text-white mb-8">
                AI-powered car rental system for the best experience
            </p>
            <a href="index.php?page=cars" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50">
                Browse Cars
            </a>
        </div>
    </div>

    <!-- Features Grid -->
    <div class="max-w-6xl mx-auto px-4 py-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 border rounded-lg">
                <h3 class="text-xl font-semibold mb-4">Smart Recommendations</h3>
                <p class="text-gray-600">Get personalized car suggestions based on your preferences</p>
            </div>
            <div class="p-6 border rounded-lg">
                <h3 class="text-xl font-semibold mb-4">Dynamic Pricing</h3>
                <p class="text-gray-600">Fair prices adjusted in real-time based on demand</p>
            </div>
            <div class="p-6 border rounded-lg">
                <h3 class="text-xl font-semibold mb-4">24/7 AI Assistant</h3>
                <p class="text-gray-600">Get instant answers to your questions</p>
            </div>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>

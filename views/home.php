<?php require 'views/layouts/header.php'; ?>

<div class="bg-white">
    <!-- Hero Section with Dynamic Background -->
    <div class="relative bg-gradient-to-r from-blue-700 to-blue-500 h-[600px] overflow-hidden">
        <!-- Animated Car Silhouettes -->
        <div class="absolute inset-0 opacity-10">
            <div class="animate-float-slow absolute top-[10%] left-[5%]">
                <svg class="w-32 h-32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z" fill="currentColor"/>
                </svg>
            </div>
            <div class="animate-float absolute top-[30%] right-[15%]">
                <svg class="w-48 h-48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z" fill="currentColor"/>
                </svg>
            </div>
        </div>
        
        <div class="max-w-6xl mx-auto px-4 py-24 relative z-10">
            <div class="md:w-2/3">
                <h1 class="text-5xl font-bold text-white mb-4 leading-tight">
                    AI-Powered <span class="text-yellow-300">Smart Car Rental</span> For The Modern Driver
                </h1>
                <p class="text-xl text-white mb-8 opacity-90">
                    Experience the future of car rentals with our AI-enhanced platform. 
                    Personalized recommendations, dynamic pricing, and 24/7 intelligent assistance.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="index.php?page=cars" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-blue-50 transition shadow-lg inline-flex items-center justify-center">
                        <span>Browse Cars</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#how-it-works" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition inline-flex items-center justify-center">
                        <span>How It Works</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Stats Counter -->
        <div class="absolute bottom-0 left-0 right-0 bg-blue-800 bg-opacity-50 backdrop-blur-sm">
            <div class="max-w-6xl mx-auto px-4 py-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div class="p-2">
                        <p class="text-3xl font-bold text-white">5+</p>
                        <p class="text-sm text-blue-100">Car Categories</p>
                    </div>
                    <div class="p-2">
                        <p class="text-3xl font-bold text-white">24/7</p>
                        <p class="text-sm text-blue-100">AI Assistance</p>
                    </div>
                    <div class="p-2">
                        <p class="text-3xl font-bold text-white">100%</p>
                        <p class="text-sm text-blue-100">Secure Booking</p>
                    </div>
                    <div class="p-2">
                        <p class="text-3xl font-bold text-white">15%</p>
                        <p class="text-sm text-blue-100">Summer Discount</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Cars Section -->
    <div class="max-w-6xl mx-auto px-4 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Featured Vehicles</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Discover our selection of premium vehicles available for rent. From economic options to luxury experiences.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
            require_once 'config/database.php';
            require_once 'models/Car.php';
            
            $db = new Database();
            $conn = $db->connect();
            
            $sql = "SELECT c.*, cc.name as category_name 
                    FROM cars c 
                    LEFT JOIN car_categories cc ON c.category_id = cc.category_id 
                    WHERE c.status = 'available' 
                    ORDER BY c.created_at DESC LIMIT 3";
            $result = $conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                while($car = $result->fetch_assoc()) {
            ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-transform hover:scale-105 hover:shadow-xl">
                <div class="h-48 bg-gray-200 relative">
                    <?php if($car['image_url']): ?>
                        <img src="<?php echo $car['image_url']; ?>" alt="<?php echo $car['make'] . ' ' . $car['model']; ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="flex items-center justify-center h-full bg-blue-50">
                            <svg class="w-24 h-24 text-blue-300" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z" fill="currentColor"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                    <?php if(isset($car['category_name'])): ?>
                        <span class="absolute top-2 right-2 bg-blue-600 text-white text-xs px-2 py-1 rounded-full"><?php echo $car['category_name']; ?></span>
                    <?php endif; ?>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800"><?php echo $car['make'] . ' ' . $car['model']; ?></h3>
                    <div class="flex items-center text-gray-600 text-sm mt-2 space-x-4">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <?php echo $car['year']; ?>
                        </span>
                        <?php if(isset($car['transmission'])): ?>
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            <?php echo ucfirst($car['transmission']); ?>
                        </span>
                        <?php endif; ?>
                        <?php if(isset($car['fuel_type'])): ?>
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <?php echo ucfirst($car['fuel_type']); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="mt-4 flex justify-between items-center">
                        <div>
                            <span class="text-2xl font-bold text-blue-600">$<?php echo $car['daily_rate']; ?></span>
                            <span class="text-gray-500 text-sm">/day</span>
                        </div>
                        <a href="index.php?page=cars&action=view&id=<?php echo $car['car_id']; ?>" class="bg-blue-100 text-blue-600 px-4 py-2 rounded-lg font-medium hover:bg-blue-200 transition">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
            ?>
            <div class="col-span-3 text-center py-8">
                <p class="text-gray-500">No cars available at the moment. Please check back later.</p>
            </div>
            <?php
            }
            ?>
        </div>
        
        <div class="text-center mt-10">
            <a href="index.php?page=cars" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                View All Vehicles
            </a>
        </div>
    </div>

       <!-- How It Works Section -->
    <div id="how-it-works" class="bg-gray-50 py-16">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">How It Works</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Renting a car has never been easier. Our AI-powered platform simplifies the entire process.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-md text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-blue-600 text-2xl font-bold">1</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Browse & Select</h3>
                    <p class="text-gray-600">Explore our diverse fleet and find the perfect vehicle for your needs.</p>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-md text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-blue-600 text-2xl font-bold">2</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Book Instantly</h3>
                    <p class="text-gray-600">Reserve your car with our simple booking system in just a few clicks.</p>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-md text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-blue-600 text-2xl font-bold">3</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Confirm & Pay</h3>
                    <p class="text-gray-600">Secure payment options with special discounts and promotions.</p>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-md text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-blue-600 text-2xl font-bold">4</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Enjoy Your Ride</h3>
                    <p class="text-gray-600">Pick up your car and hit the road with 24/7 support if needed.</p>
                </div>
            </div>
        </div>
    </div>


    <!-- AI Features Section -->
    <div class="max-w-6xl mx-auto px-4 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-6">AI-Powered Rental Experience</h2>
                <p class="text-gray-600 mb-8">Our smart car rental system leverages artificial intelligence to provide you with a seamless and personalized experience.</p>
                
                <div class="space-y-6">
                    <div class="flex">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">Smart Recommendations</h3>
                            <p class="text-gray-600 mt-1">Our AI analyzes your preferences and past rentals to suggest the perfect vehicle for your needs.</p>
                        </div>
                    </div>
                    
                    <div class="flex">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">Dynamic Pricing</h3>
                            <p class="text-gray-600 mt-1">Get the best rates with our AI-powered pricing system that adjusts in real-time based on demand and availability.</p>
                        </div>
                    </div>
                    
                    <div class="flex">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                                    <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">24/7 AI Assistant</h3>
                            <p class="text-gray-600 mt-1">Our intelligent chatbot is always available to answer your questions and guide you through the rental process.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="relative">
                <div class="bg-blue-50 rounded-xl p-8 relative z-10">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">AI Rental Assistant</h3>
                                <p class="text-gray-600 mt-1 text-sm">How can I help you find the perfect car today?</p>
                            </div>
                        </div>
                        
                        <div class="mt-4 space-y-3">
                            <div class="bg-gray-100 rounded-lg p-3 text-sm text-gray-700">
                                I'm looking for an SUV for a weekend trip with my family.
                            </div>
                            <div class="bg-blue-100 rounded-lg p-3 text-sm text-blue-700">
                                Great choice! I recommend our Honda CR-V or Toyota RAV4. Both have ample space for 5 passengers and luggage. Would you like to see availability for this weekend?
                            </div>
                            <div class="bg-gray-100 rounded-lg p-3 text-sm text-gray-700">
                                Yes, and what special offers do you have?
                            </div>
                            <div class="bg-blue-100 rounded-lg p-3 text-sm text-blue-700">
                                You're in luck! We currently have a WEEKEND25 promotion with 25% off weekend rentals. I can apply this to your booking automatically.
                            </div>
                        </div>
                        
                        <div class="mt-4 flex">
                            <input type="text" placeholder="Ask our AI assistant..." class="flex-1 border border-gray-300 rounded-l-lg px-4 py-2 text-sm focus:outline-none focus:border-blue-500">
                            <button class="bg-blue-600 text-white rounded-r-lg px-4 py-2 text-sm hover:bg-blue-700 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Decorative elements -->
                <div class="absolute top-0 right-0 -mr-6 -mt-6 w-32 h-32 bg-yellow-400 rounded-full opacity-50 z-0"></div>
                <div class="absolute bottom-0 left-0 -ml-6 -mb-6 w-24 h-24 bg-blue-400 rounded-full opacity-50 z-0"></div>
            </div>
        </div>
    </div>

    <!-- Promotions Section -->
    <div class="bg-blue-600 py-16">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-4">Current Promotions</h2>
                    <p class="text-blue-100 mb-6">Take advantage of our limited-time offers and save on your next rental.</p>
                    
                    <?php
                    $sql = "SELECT * FROM promotions WHERE is_active = 1 AND end_date >= CURDATE() ORDER BY start_date ASC LIMIT 3";
                    $result = $conn->query($sql);
                    
                    if ($result && $result->num_rows > 0) {
                        while($promo = $result->fetch_assoc()) {
                    ?>
                    <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4 mb-4 border border-blue-300 border-opacity-30">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-yellow-300 font-bold"><?php echo $promo['code']; ?></span>
                                <h3 class="text-white font-semibold"><?php echo $promo['description']; ?></h3>
                            </div>
                            <div class="text-right">
                                <?php if($promo['discount_percentage']): ?>
                                <span class="text-2xl font-bold text-white"><?php echo $promo['discount_percentage']; ?>%</span>
                                <span class="text-blue-200 block text-sm">OFF</span>
                                <?php elseif($promo['discount_amount']): ?>
                                <span class="text-2xl font-bold text-white">$<?php echo $promo['discount_amount']; ?></span>
                                <span class="text-blue-200 block text-sm">OFF</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-blue-200">
                            Valid until: <?php echo date('M d, Y', strtotime($promo['end_date'])); ?>
                        </div>
                    </div>
                    <?php
                        }
                    } else {
                    ?>
                    <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4 mb-4 border border-blue-300 border-opacity-30">
                        <h3 class="text-white font-semibold">No active promotions at the moment</h3>
                        <p class="text-blue-200 text-sm mt-1">Check back soon for new offers!</p>
                    </div>
                    <?php
                    }
                    ?>
                    
                    <a href="index.php?page=promotions" class="inline-block mt-4 bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">
                        View All Promotions
                    </a>
                </div>
                
                <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                    <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Sign Up & Save</h3>
                        <p class="text-gray-600 mb-6">Join our community and get exclusive access to special offers, early bird discounts, and personalized recommendations.</p>
                        
                        <form action="index.php?page=auth&action=register" method="GET" class="space-y-4">
                            <input type="hidden" name="page" value="auth">
                            <input type="hidden" name="action" value="register">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" id="email" name="email" placeholder="your@email.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                                Create Account
                            </button>
                        </form>
                        
                        <div class="mt-4 text-center text-sm text-gray-500">
                            Already have an account? 
                            <a href="index.php?page=auth&action=login" class="text-blue-600 font-medium hover:text-blue-500">
                                Sign in
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="max-w-6xl mx-auto px-4 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">What Our Customers Say</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Hear from our satisfied customers about their experience with our AI-enhanced car rental service.</p>
        </div>
       
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-xl shadow-md">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-blue-600 font-bold">JD</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">John Doe</h3>
                        <div class="flex text-yellow-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600">"The AI assistant made finding the right car so easy! It recommended the perfect SUV for our family trip and even applied a discount automatically. Seamless experience from start to finish."</p>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-md">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-blue-600 font-bold">IN</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">IMBABAZI NIRVAN</h3>
                        <div class="flex text-yellow-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600">"I love the dynamic pricing feature! I was able to get a luxury car at an economy price by booking during a low-demand period. The AI chatbot helped me understand when the best time to book would be."</p>            </div>

            <div class="bg-white p-6 rounded-xl shadow-md">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-blue-600 font-bold">AF</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">AMANI FAGHILI</h3>
                        <div class="flex text-yellow-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600">"The WEEKEND25 promotion saved me a lot on my weekend getaway. The Tesla Model 3 was in perfect condition and the entire rental process was smooth. Will definitely use this service again!"</p>            </div>
    </div>

    <!-- Car Categories Section -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Browse by Category</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">We offer a wide range of vehicles to meet every need and budget.</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                <?php
                $sql = "SELECT * FROM car_categories ORDER BY name ASC";
                $result = $conn->query($sql);
                
                if ($result && $result->num_rows > 0) {
                    while($category = $result->fetch_assoc()) {
                ?>
                <a href="index.php?page=cars&category=<?php echo $category['category_id']; ?>" class="bg-white rounded-xl shadow-md p-6 text-center hover:shadow-lg transition transform hover:-translate-y-1">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <?php if($category['name'] == 'Economy'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <?php elseif($category['name'] == 'Compact'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                        <?php elseif($category['name'] == 'Midsize'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                        <?php elseif($category['name'] == 'SUV'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                        <?php elseif($category['name'] == 'Luxury'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                        <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                        <?php endif; ?>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800"><?php echo $category['name']; ?></h3>
                    <p class="text-sm text-gray-600 mt-1"><?php echo substr($category['description'], 0, 60); ?><?php echo (strlen($category['description']) > 60) ? '...' : ''; ?></p>
                </a>
                <?php
                    }
                } else {
                ?>
                <div class="col-span-5 text-center py-8">
                    <p class="text-gray-500">No categories available at the moment.</p>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-blue-600 py-16">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-white mb-6">Ready to Experience Smart Car Rental?</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">Join thousands of satisfied customers who have transformed their travel experience with our AI-powered car rental system.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="index.php?page=cars" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-blue-50 transition">
                    Browse Available Cars
                </a>
                <a href="index.php?page=auth&action=register" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition">
                    Create an Account
                </a>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="max-w-6xl mx-auto px-4 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Frequently Asked Questions</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Find answers to common questions about our smart car rental service.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="text-xl font-bold text-gray-800 mb-3">How does the AI recommendation system work?</h3>
                <p class="text-gray-600">Our AI analyzes your preferences, past rentals, and current needs to suggest vehicles that best match your requirements. It learns from your feedback to improve future recommendations.</p>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="text-xl font-bold text-gray-800 mb-3">What is dynamic pricing?</h3>
                <p class="text-gray-600">Dynamic pricing adjusts rental rates based on demand, seasonality, and availability. This ensures you get competitive rates during off-peak times and helps us manage inventory during high-demand periods.</p>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="text-xl font-bold text-gray-800 mb-3">How do I apply a promotion code?</h3>
                <p class="text-gray-600">During the checkout process, you'll find a field to enter your promotion code. Our system will automatically apply the discount to your total. Some promotions may be applied automatically if you qualify.</p>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="text-xl font-bold text-gray-800 mb-3">What happens if I need to cancel my reservation?</h3>
                <p class="text-gray-600">You can cancel your reservation through your account dashboard. Cancellation policies vary depending on how far in advance you cancel. Full details are provided during the booking process.</p>
            </div>
        </div>
        
        <!-- <div class="text-center mt-8">
            <a href="index.php?page=faq" class="text-blue-600 font-semibold hover:text-blue-800 transition">
                View all FAQs <span class="ml-1">→</span>
            </a>
        </div> -->
    </div>
</div>

<!-- Add custom styles for animations -->
<style>
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    
    @keyframes float-slow {
        0% { transform: translateY(0px) rotate(10deg); }
        50% { transform: translateY(-20px) rotate(-5deg); }
        100% { transform: translateY(0px) rotate(10deg); }
    }
    
    .animate-float {
        animation: float 3s ease-in-out infinite;
    }
    
    .animate-float-slow {
        animation: float-slow 6s ease-in-out infinite;
    }
</style>

<?php require 'views/layouts/footer.php'; ?>

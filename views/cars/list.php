<?php require 'views/layouts/header.php'; 
// Helper function to remove a query parameter from the current URL
function removeQueryParam($param) {
    $params = $_GET;
    unset($params[$param]);
    $params['page'] = 'cars'; // Ensure we keep the page parameter
    return 'index.php?' . http_build_query($params);
}
?>

<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section with Advanced Search -->
    <div class="relative bg-gradient-to-r from-blue-600 to-blue-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative z-10">
                <h1 class="text-4xl font-extrabold text-white sm:text-5xl">
                    Explore Our Fleet
                </h1>
                <p class="mt-4 text-xl text-blue-100 max-w-3xl">
                    Find the perfect vehicle for your journey with our AI-powered recommendations and dynamic pricing.
                </p>
                
                <!-- Advanced Search and Filter Bar -->
                <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
                    <form action="index.php" method="GET" class="space-y-5">
                        <input type="hidden" name="page" value="cars">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="text" name="search" id="search" 
                                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                                           class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-3 py-2 sm:text-sm border-gray-300 rounded-md" 
                                           placeholder="Make, model, features...">
                                </div>
                            </div>
                            
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <select name="category" id="category" 
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['category_id']; ?>" 
                                                <?php echo (isset($_GET['category']) && $_GET['category'] == $cat['category_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Max Price</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="price" id="price" 
                                           value="<?php echo isset($_GET['price']) ? htmlspecialchars($_GET['price']) : ''; ?>"
                                           class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-3 py-2 sm:text-sm border-gray-300 rounded-md" 
                                           placeholder="Max daily rate">
                                </div>
                            </div>
                            
                            <div>
                                <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                                <select name="sort" id="sort" 
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="price_asc" <?php echo (!isset($_GET['sort']) || $_GET['sort'] == 'price_asc') ? 'selected' : ''; ?>>Price: Low to High</option>
                                    <option value="price_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : ''; ?>>Price: High to Low</option>
                                    <option value="newest" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'newest') ? 'selected' : ''; ?>>Newest Models</option>
                                    <option value="oldest" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'oldest') ? 'selected' : ''; ?>>Oldest Models</option>
                                    <option value="name_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'name_asc') ? 'selected' : ''; ?>>Name: A to Z</option>
                                    <option value="name_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'name_desc') ? 'selected' : ''; ?>>Name: Z to A</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Advanced Filters (collapsible) -->
                        <div x-data="{ open: false }">
                            <button type="button" @click="open = !open" class="flex items-center text-sm text-blue-600 hover:text-blue-800 focus:outline-none">
                                <svg :class="{'rotate-90': open}" class="h-5 w-5 mr-1 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                Advanced Filters
                            </button>
                            
                            <div x-show="open" x-transition class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div>
                                    <label for="fuel_type" class="block text-sm font-medium text-gray-700 mb-1">Fuel Type</label>
                                    <select name="fuel_type" id="fuel_type" 
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option value="">Any Fuel Type</option>
                                        <?php foreach ($fuelTypes as $type): ?>
                                            <option value="<?php echo $type; ?>" 
                                                    <?php echo (isset($_GET['fuel_type']) && $_GET['fuel_type'] == $type) ? 'selected' : ''; ?>>
                                                <?php echo ucfirst(htmlspecialchars($type)); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="transmission" class="block text-sm font-medium text-gray-700 mb-1">Transmission</label>
                                    <select name="transmission" id="transmission" 
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option value="">Any Transmission</option>
                                        <?php foreach ($transmissions as $trans): ?>
                                            <option value="<?php echo $trans; ?>" 
                                                    <?php echo (isset($_GET['transmission']) && $_GET['transmission'] == $trans) ? 'selected' : ''; ?>>
                                                <?php echo ucfirst(htmlspecialchars($trans)); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="seats" class="block text-sm font-medium text-gray-700 mb-1">Minimum Seats</label>
                                    <select name="seats" id="seats" 
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option value="">Any Number of Seats</option>
                                        <?php for ($i = 2; $i <= 8; $i++): ?>
                                            <option value="<?php echo $i; ?>" 
                                                    <?php echo (isset($_GET['seats']) && $_GET['seats'] == $i) ? 'selected' : ''; ?>>
                                                <?php echo $i; ?>+ seats
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                
                                <?php if (isAdmin()): ?>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="show_all" value="1" 
                                               <?php echo (isset($_GET['show_all']) && $_GET['show_all'] == '1') ? 'checked' : ''; ?>
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Show all cars (including unavailable)</span>
                                    </label>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <a href="index.php?page=cars" class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Reset Filters
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                                Search Cars
                            </button>
                        </div>
                    </form>
                              </div>
            </div>
        </div>
        
        <!-- Decorative pattern -->
        <div class="absolute inset-y-0 right-0 w-1/2 opacity-20">
            <svg class="h-full w-full" viewBox="0 0 150 150" xmlns="http://www.w3.org/2000/svg">
                <pattern id="grid-pattern" width="30" height="30" patternUnits="userSpaceOnUse">
                    <circle cx="10" cy="10" r="1.5" fill="#fff" />
                </pattern>
                <rect x="0" y="0" width="100%" height="100%" fill="url(#grid-pattern)" />
            </svg>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="mb-8 bg-green-50 border-l-4 border-green-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700"><?php echo $_SESSION['success']; ?></p>
                    </div>
                </div>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="mb-8 bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700"><?php echo $_SESSION['error']; ?></p>
                    </div>
                </div>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Available Cars</h2>
                <p class="text-gray-600 mt-1">
                    <?php echo $totalCars; ?> vehicles found
                    <?php if (isset($_GET['search']) || isset($_GET['category']) || isset($_GET['price']) || isset($_GET['fuel_type']) || isset($_GET['transmission']) || isset($_GET['seats'])): ?>
                        based on your filters
                    <?php endif; ?>
                </p>
            </div>
            
            <?php if (isAdmin()): ?>
                <a href="index.php?page=cars&action=add" class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add New Car
                </a>
            <?php endif; ?>
        </div>

        <!-- Active Filters -->
        <?php if (isset($_GET['search']) || isset($_GET['category']) || isset($_GET['price']) || isset($_GET['fuel_type']) || isset($_GET['transmission']) || isset($_GET['seats'])): ?>
        <div class="bg-blue-50 rounded-lg p-4 mb-8">
            <div class="flex items-center flex-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                </svg>
                <h3 class="text-sm font-medium text-blue-800">Active Filters:</h3>
                <div class="ml-4 flex flex-wrap gap-2">
                    <?php if (!empty($_GET['search'])): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Search: <?php echo htmlspecialchars($_GET['search']); ?>
                            <a href="<?php echo removeQueryParam('search'); ?>" class="ml-1 text-blue-500 hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </span>
                    <?php endif; ?>
                    
                    <?php if (!empty($_GET['category'])): 
                        $catId = $_GET['category'];
                        $catName = "Category";
                        foreach ($categories as $cat) {
                            if ($cat['category_id'] == $catId) {
                                $catName = $cat['name'];
                                break;
                            }
                        }
                    ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Category: <?php echo htmlspecialchars($catName); ?>
                            <a href="<?php echo removeQueryParam('category'); ?>" class="ml-1 text-blue-500 hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </span>
                    <?php endif; ?>
                    
                    <?php if (!empty($_GET['price'])): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Max Price: $<?php echo htmlspecialchars($_GET['price']); ?>/day
                            <a href="<?php echo removeQueryParam('price'); ?>" class="ml-1 text-blue-500 hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </span>
                    <?php endif; ?>
                    
                    <?php if (!empty($_GET['fuel_type'])): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Fuel: <?php echo ucfirst(htmlspecialchars($_GET['fuel_type'])); ?>
                            <a href="<?php echo removeQueryParam('fuel_type'); ?>" class="ml-1 text-blue-500 hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </span>
                    <?php endif; ?>
                    
                    <?php if (!empty($_GET['transmission'])): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Transmission: <?php echo ucfirst(htmlspecialchars($_GET['transmission'])); ?>
                            <a href="<?php echo removeQueryParam('transmission'); ?>" class="ml-1 text-blue-500 hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </span>
                    <?php endif; ?>
                    
                    <?php if (!empty($_GET['seats'])): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Min Seats: <?php echo htmlspecialchars($_GET['seats']); ?>
                            <a href="<?php echo removeQueryParam('seats'); ?>" class="ml-1 text-blue-500 hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </span>
                    <?php endif; ?>
                    
                    <a href="index.php?page=cars" class="text-sm text-blue-600 hover:text-blue-800 ml-auto">
                        Clear All Filters
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (empty($cars)): ?>
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No cars found</h3>
                <p class="text-gray-600 mb-4">We couldn't find any cars matching your criteria.</p>
                <a href="index.php?page=cars" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Clear filters and try again
                </a>
            </div>
        <?php else: ?>
            <!-- Car Grid with Enhanced Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($cars as $car): ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
                        <div class="relative">
                            <img src="<?= $car['image_url'] ?? 'assets/images/default-car.jpg' ?>" 
                                alt="<?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?>" 
                                class="w-full h-56 object-cover">
                            
                            <?php if ($car['status'] !== 'available'): ?>
                                <div class="absolute top-0 right-0 bg-red-500 text-white px-3 py-1 m-2 rounded-full text-xs font-semibold uppercase">
                                    <?= htmlspecialchars($car['status']) ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Category Badge -->
                            <?php
                            $categoryName = "Uncategorized";
                            foreach ($categories as $cat) {
                                if ($cat['category_id'] == $car['category_id']) {
                                    $categoryName = $cat['name'];
                                    break;
                                }
                            }
                            ?>
                            <div class="absolute top-0 left-0 bg-blue-600 text-white px-3 py-1 m-2 rounded-full text-xs font-semibold">
                                <?= htmlspecialchars($categoryName) ?>
                            </div>
                            
                            <!-- Favorite Button (if logged in) -->
                            <?php if (isLoggedIn()): ?>
                                <button type="button" 
                                        onclick="toggleFavorite(<?= $car['car_id'] ?>, this)" 
                                        class="absolute top-12 left-0 m-2 p-2 bg-white rounded-full shadow-md hover:bg-gray-100 focus:outline-none"
                                        aria-label="Add to favorites">
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                         class="h-5 w-5 <?= in_array($car['car_id'], $favorites) ? 'text-red-500' : 'text-gray-400' ?> favorite-icon" 
                                         viewBox="0 0 20 20" 
                                         fill="<?= in_array($car['car_id'], $favorites) ? 'currentColor' : 'none' ?>" 
                                         stroke="currentColor">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            <?php endif; ?>
                            
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                                <h3 class="text-xl font-bold text-white">
                                    <?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?>
                                </h3>
                                <p class="text-sm text-gray-200"><?= htmlspecialchars($car['year']) ?></p>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <span class="text-2xl font-bold text-blue-600">
                                        $<?= number_format($car['daily_rate'], 2) ?>
                                    </span>
                                    <span class="text-gray-600">/day</span>
                                </div>
                                
                                <!-- Dynamic Pricing Indicator -->
                                <?php if (isset($car['base_rate']) && $car['daily_rate'] < $car['base_rate']): ?>
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                        <?= round((($car['base_rate'] - $car['daily_rate']) / $car['base_rate']) * 100) ?>% OFF
                                    </span>
                                <?php elseif (isset($car['base_rate']) && $car['daily_rate'] > $car['base_rate']): ?>
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                        High Demand
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="space-y-3 mb-6">
                                <!-- Car Features -->
                                <div class="grid grid-cols-2 gap-2">
                                    <?php if (!empty($car['transmission'])): ?>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <?= ucfirst(htmlspecialchars($car['transmission'])) ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($car['fuel_type'])): ?>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        <?= ucfirst(htmlspecialchars($car['fuel_type'])) ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($car['seats'])): ?>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <?= htmlspecialchars($car['seats']) ?> Seats
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <?= htmlspecialchars($car['year']) ?>
                                    </div>
                                </div>
                                
                                <!-- Features Preview -->
                                <?php if (!empty($car['features'])): ?>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600 line-clamp-1">
                                        <span class="font-medium">Features:</span> <?= htmlspecialchars($car['features']) ?>
                                    </p>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <a href="index.php?page=cars&action=view&id=<?= $car['car_id'] ?>" 
                                   class="flex items-center justify-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                    Details
                                </a>
                                
                            </div>
                            
                            <!-- Admin Actions -->
                            <?php if (isAdmin()): ?>
                            <div class="mt-3 pt-3 border-t border-gray-200 flex justify-between">
                                <a href="index.php?page=admin&action=cars&subaction=edit&id=<?= $car['car_id'] ?>" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>
                                <a href="index.php?page=cars&action=delete&subaction=delete&id=<?= $car['car_id'] ?>" 
                                   onclick="return confirm('Are you sure you want to delete this car?')"
                                   class="text-red-600 hover:text-red-800 hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="mt-12 flex justify-center">
                <nav class="inline-flex rounded-md shadow">
                    <?php 
                    $currentPage = $page;
                    $queryParams = $_GET;
                    
                    // Previous page link
                    if ($currentPage > 1) {
                        $queryParams['page_num'] = $currentPage - 1;
                        $prevPageUrl = 'index.php?' . http_build_query($queryParams);
                    ?>
                    <a href="<?= $prevPageUrl ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <?php } else { ?>
                    <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <?php } ?>
                    
                    <!-- Page numbers -->
                    <?php 
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($totalPages, $startPage + 4);
                    
                    if ($startPage > 1) {
                        $queryParams['page_num'] = 1;
                        echo '<a href="index.php?' . http_build_query($queryParams) . '" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">1</a>';
                        
                        if ($startPage > 2) {
                            echo '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>';
                        }
                    }
                    
                    for ($i = $startPage; $i <= $endPage; $i++) {
                        $queryParams['page_num'] = $i;
                        $isActive = $i === $currentPage;
                        $classes = $isActive 
                            ? 'relative inline-flex items-center px-4 py-2 border border-blue-500 bg-blue-50 text-sm font-medium text-blue-600'
                            : 'relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50';
                            
                        echo '<a href="index.php?' . http_build_query($queryParams) . '" class="' . $classes . '">' . $i . '</a>';
                    }
                    
                    if ($endPage < $totalPages) {
                        if ($endPage < $totalPages - 1) {
                            echo '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>';
                        }
                        
                        $queryParams['page_num'] = $totalPages;
                        echo '<a href="index.php?' . http_build_query($queryParams) . '" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">' . $totalPages . '</a>';
                    }
                    
                    // Next page link
                    if ($currentPage < $totalPages) {
                        $queryParams['page_num'] = $currentPage + 1;
                        $nextPageUrl = 'index.php?' . http_build_query($queryParams);
                    ?>
                    <a href="<?= $nextPageUrl ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <?php } else { ?>
                    <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <?php } ?>
                </nav>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <!-- Promotion Banner -->
    <?php if ($promotion): ?>
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:flex lg:items-center lg:justify-between">
                <div class="lg:w-0 lg:flex-1">
                    <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                        Special Offer: <?= htmlspecialchars($promotion['code']) ?>
                    </h2>
                    <p class="mt-3 max-w-3xl text-lg text-blue-100">
                        <?= htmlspecialchars($promotion['description']) ?>
                    </p>
                    <p class="mt-4 text-2xl font-bold text-white">
                        <?php if (!empty($promotion['discount_percentage'])): ?>
                            Save <?= $promotion['discount_percentage'] ?>% on your next rental!
                        <?php elseif (!empty($promotion['discount_amount'])): ?>
                            Save $<?= $promotion['discount_amount'] ?> on your next rental!
                        <?php endif; ?>
                    </p>
                    <p class="mt-2 text-sm text-blue-100">
                        Valid until <?= date('F j, Y', strtotime($promotion['end_date'])) ?>
                    </p>
                </div>
                <div class="mt-8 lg:mt-0 lg:ml-8">
                    <div class="sm:flex">
                        <div class="bg-white bg-opacity-10 backdrop-filter backdrop-blur-sm rounded-md px-6 py-4">
                            <p class="text-xl font-bold text-white mb-2">Promo Code:</p>
                            <div class="flex items-center">
                                <span class="text-2xl font-mono text-white tracking-wider"><?= htmlspecialchars($promotion['code']) ?></span>
                                <button onclick="copyPromoCode('<?= htmlspecialchars($promotion['code']) ?>')" class="ml-3 bg-white bg-opacity-20 p-2 rounded-md hover:bg-opacity-30 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M8 2a1 1 0 000 2h2a1 1 0 100-2H8z" />
                                        <path d="M3 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v6h-4.586l1.293-1.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L10.414 13H15v3a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM15 11h2a1 1 0 110 2h-2v-2z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <?php if (isLoggedIn()): ?>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <a href="index.php?page=cars" class="block w-full rounded-md bg-white px-5 py-4 text-base font-medium text-blue-600 shadow hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600 sm:px-10">
                                Browse Cars
                            </a>
                        </div>
                        <?php else: ?>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <a href="index.php?page=auth&action=register" class="block w-full rounded-md bg-white px-5 py-4 text-base font-medium text-blue-600 shadow hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600 sm:px-10">
                                Sign Up Now
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- AI Recommendation Section (for logged in users) -->
    <?php if (isLoggedIn() && isset($_SESSION['user_id'])): ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Recommended for You</h2>
            <p class="mt-2 text-lg text-gray-600">Based on your preferences and rental history</p>
        </div>
        
        <?php if (!empty($recommendedCars)): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($recommendedCars as $car): ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-blue-100">
                    <div class="relative">
                        <img src="<?= $car['image_url'] ?? 'assets/images/default-car.jpg' ?>" 
                            alt="<?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?>" 
                            class="w-full h-48 object-cover">
                        <div class="absolute top-0 left-0 bg-blue-600 text-white px-3 py-1 m-2 rounded-full text-xs font-semibold">
                            Recommended
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            <?= htmlspecialchars($car['make'] . ' ' . $car['model'] . ' ' . $car['year']) ?>
                        </h3>
                        
                        <p class="text-2xl font-bold text-blue-600 mb-4">
                            $<?= number_format($car['daily_rate'], 2) ?> <span class="text-sm text-gray-600">/day</span>
                        </p>
                        
                        <div class="flex justify-between">
                            <a href="index.php?page=cars&action=view&id=<?= $car['car_id'] ?>" 
                               class="flex-1 mr-2 flex items-center justify-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-300">
                                View Details
                            </a>
                           
                             <a href="index.php?page=cars&action=view&id=<?= $car['car_id'] ?>" 
                                   class="flex items-center justify-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                    Details
                                </a>
                                
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
        <div class="bg-blue-50 rounded-lg p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
            <h3 class="text-xl font-medium text-gray-900 mb-2">Personalized recommendations coming soon!</h3>
            <p class="text-gray-600 mb-4">Rent a few cars to help us understand your preferences better.</p>
            <a href="index.php?page=cars" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                Browse all cars <span class="ml-2">→</span>
            </a>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<script>
function copyPromoCode(code) {
    navigator.clipboard.writeText(code).then(function() {
        alert('Promo code copied to clipboard: ' + code);
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}

// helper function to remove a query parameter from the current URL
function removeQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.delete(param);
    urlParams.set('page', 'cars'); // Ensure we keep the page parameter
    return 'index.php?' + urlParams.toString();
}
// Toggle favorite status
function toggleFavorite(carId, button) {
    fetch('index.php?page=cars&action=toggle_favorite&car_id=' + carId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const icon = button.querySelector('.favorite-icon');
        if (data.status === 'added') {
            icon.setAttribute('fill', 'currentColor');
            icon.classList.add('text-red-500');
            icon.classList.remove('text-gray-400');
        } else {
            icon.setAttribute('fill', 'none');
            icon.classList.add('text-gray-400');
            icon.classList.remove('text-red-500');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>

<?php require 'views/layouts/footer.php'; ?>

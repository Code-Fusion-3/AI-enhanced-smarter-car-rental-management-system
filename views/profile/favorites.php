<?php require 'views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row">
            <!-- Sidebar -->
            <div class="w-full md:w-1/4 mb-6 md:mb-0 md:pr-6">
                <?php require 'views/profile/sidebar.php'; ?>
            </div>
            
            <!-- Main Content -->
            <div class="w-full md:w-3/4">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline"><?= $_SESSION['success'] ?></span>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline"><?= $_SESSION['error'] ?></span>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">My Favorite Cars</h2>
                    
                    <?php if (empty($favorites)): ?>
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <p class="mt-2 text-gray-500">You don't have any favorite cars yet.</p>
                            <a href="index.php?page=cars" class="mt-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Browse Cars
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php foreach ($favorites as $car): ?>
                                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                                    <div class="relative">
                                        <img src="<?= $car['image_url'] ?? 'assets/images/default-car.jpg' ?>" 
                                            alt="<?= $car['make'] ?> <?= $car['model'] ?>" 
                                            class="w-full h-48 object-cover">
                                        
                                        <?php if ($car['status'] !== 'available'): ?>
                                            <div class="absolute top-0 right-0 bg-red-500 text-white px-3 py-1 m-2 rounded-full text-xs font-semibold uppercase">
                                                <?= $car['status'] ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <form action="index.php?page=cars&action=removeFavorite" method="POST" class="absolute top-0 left-0 m-2">
                                            <input type="hidden" name="favorite_id" value="<?= $car['favorite_id'] ?>">
                                            <button type="submit" class="bg-white rounded-full p-2 shadow-md text-red-500 hover:text-red-700 focus:outline-none" title="Remove from favorites">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <div class="p-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                <?= $car['make'] ?> <?= $car['model'] ?>
                                            </h3>
                                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                                <?= $car['category_name'] ?? 'Uncategorized' ?>
                                            </span>
                                        </div>
                                        
                                        <p class="text-gray-600 text-sm mb-2"><?= $car['year'] ?></p>
                                        
                                        <div class="flex justify-between items-center mb-4">
                                            <div>
                                                <span class="text-xl font-bold text-blue-600">
                                                    $<?= number_format($car['daily_rate'], 2) ?>
                                                </span>
                                                <span class="text-gray-600">/day</span>
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-2">
                                            <a href="index.php?page=cars&action=view&id=<?= $car['car_id'] ?>" 
                                               class="flex items-center justify-center bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700 transition-colors duration-300 text-sm">
                                                View Details
                                            </a>
                                            <?php if ($car['status'] === 'available'): ?>
                                                <a href="index.php?page=rentals&action=create&car_id=<?= $car['car_id'] ?>" 
                                                   class="flex items-center justify-center bg-green-600 text-white px-3 py-2 rounded-md hover:bg-green-700 transition-colors duration-300 text-sm">
                                                    Rent Now
                                                </a>
                                            <?php else: ?>
                                                <button disabled
                                                       class="flex items-center justify-center bg-gray-300 text-gray-600 px-3 py-2 rounded-md cursor-not-allowed text-sm">
                                                    Not Available
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
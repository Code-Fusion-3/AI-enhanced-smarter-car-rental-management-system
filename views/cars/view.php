<?php require 'views/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen pb-12">
    <!-- Car Details Header -->
    <div class="relative bg-blue-600 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative z-10">
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-blue-200">
                        <li>
                            <a href="index.php" class="hover:text-white">Home</a>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 flex-shrink-0 text-blue-300" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <a href="index.php?page=cars" class="ml-2 hover:text-white">Cars</a>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 flex-shrink-0 text-blue-300" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-2 text-white font-medium"><?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?></span>
                        </li>
                    </ol>
                </nav>
                
                <h1 class="text-4xl font-extrabold text-white sm:text-5xl">
                    <?= htmlspecialchars($car['make'] . ' ' . $car['model'] . ' ' . $car['year']) ?>
                </h1>
                
                <div class="mt-3 flex items-center">
                    <?php if ($avgRating > 0): ?>
                        <div class="flex items-center">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= round($avgRating)): ?>
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                <?php else: ?>
                                    <svg class="h-5 w-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <span class="ml-2 text-white"><?= number_format($avgRating, 1) ?> (<?= $reviewCount ?> reviews)</span>
                        </div>
                    <?php else: ?>
                        <span class="bg-blue-700 text-white px-3 py-1 rounded-full text-sm font-medium">No reviews yet</span>
                    <?php endif; ?>
                    
                    <?php if ($category): ?>
                        <span class="mx-3 text-blue-300">•</span>
                        <span class="bg-blue-700 text-white px-3 py-1 rounded-full text-sm font-medium">
                            <?= htmlspecialchars($category['name']) ?>
                        </span>
                    <?php endif; ?>
                    
                    <span class="mx-3 text-blue-300">•</span>
                    <span class="bg-blue-700 text-white px-3 py-1 rounded-full text-sm font-medium">
                        Reg: <?= htmlspecialchars($car['registration_number']) ?>
                    </span>
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12">
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

        <!-- Car Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Car Image and Primary Details -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                     <!-- Car Image -->
                    <div class="relative">
                        <img src="<?= $car['image_url'] ?? 'assets/images/car1.png' ?>" 
                            alt="<?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?>" 
                            class="w-full h-96 object-cover">
                            
                        <?php if ($car['status'] !== 'available'): ?>
                            <div class="absolute top-0 right-0 bg-red-500 text-white px-4 py-2 m-4 rounded-full text-sm font-semibold uppercase">
                                <?= htmlspecialchars($car['status']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isLoggedIn()): ?>
                            <button type="button" 
                                    onclick="toggleFavorite(<?= $car['car_id'] ?>, this)" 
                                    class="absolute top-4 left-4 p-2 bg-white rounded-full shadow-md hover:bg-gray-100 focus:outline-none"
                                    aria-label="<?= $isFavorite ? 'Remove from favorites' : 'Add to favorites' ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" 
                                     class="h-6 w-6 <?= $isFavorite ? 'text-red-500' : 'text-gray-400' ?> favorite-icon" 
                                     viewBox="0 0 20 20" 
                                     fill="<?= $isFavorite ? 'currentColor' : 'none' ?>" 
                                     stroke="currentColor">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Car Details -->
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <span class="text-3xl font-bold text-blue-600">
                                    $<?= number_format((float)$dynamicPrice, 2) ?>
                                </span>
                                <span class="text-gray-600">/day</span>
                                
                                <?php if ($dynamicPrice < $car['base_rate']): ?>
                                    <span class="ml-2 bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                        <?= round((($car['base_rate'] - $dynamicPrice) / $car['base_rate']) * 100) ?>% OFF
                                    </span>
                                <?php elseif ($dynamicPrice > $car['base_rate']): ?>
                                    <span class="ml-2 bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                        High Demand
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            
                            <div class="hidden">
                            <?php if ($car['status'] === 'available' && isLoggedIn()): ?>
                                <a href="index.php?page=rentals&action=create&car_id=<?= $car['car_id'] ?>" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                                    </svg>
                                    Rent Now
                                </a>
                            <?php elseif (!isLoggedIn()): ?>
                                <a href="index.php?page=auth&action=login" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Login to Rent
                                </a>
                            <?php endif; ?>
                                </div>
                        </div>
                        
                        <!-- Car Specifications -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <?php if (!empty($car['transmission'])): ?>
                            <div class="bg-gray-50 p-3 rounded-lg text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto text-gray-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <div class="text-sm font-medium text-gray-500">Transmission</div>
                                <div class="text-base font-semibold text-gray-900"><?= ucfirst(htmlspecialchars($car['transmission'])) ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($car['fuel_type'])): ?>
                            <div class="bg-gray-50 p-3 rounded-lg text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto text-gray-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <div class="text-sm font-medium text-gray-500">Fuel Type</div>
                                <div class="text-base font-semibold text-gray-900"><?= ucfirst(htmlspecialchars($car['fuel_type'])) ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($car['seats'])): ?>
                            <div class="bg-gray-50 p-3 rounded-lg text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto text-gray-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <div class="text-sm font-medium text-gray-500">Seats</div>
                                <div class="text-base font-semibold text-gray-900"><?= htmlspecialchars($car['seats']) ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="bg-gray-50 p-3 rounded-lg text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto text-gray-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-sm font-medium text-gray-500">Year</div>
                                <div class="text-base font-semibold text-gray-900"><?= htmlspecialchars($car['year']) ?></div>
                            </div>
                        </div>
                        
                        <!-- Features -->
                        <?php if (!empty($car['features'])): ?>
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Features</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <ul class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    <?php 
                                    $features = explode(',', $car['features']);
                                    foreach ($features as $feature): 
                                        $feature = trim($feature);
                                        if (empty($feature)) continue;
                                    ?>
                                    <li class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-gray-700"><?= htmlspecialchars($feature) ?></span>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Pricing Options -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Pricing Options</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Rental Period
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Rate
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Savings
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                Daily
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                $<?= number_format((float)$dynamicPrice, 2) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                -
                                            </td>
                                        </tr>
                                        <?php if (!empty($car['weekend_rate'])): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                Weekend (Fri-Sun)
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                $<?= number_format($car['weekend_rate'], 2) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                            <?php 
$savings = ((float)$dynamicPrice * 3) - ($car['weekend_rate'] * 3);
echo ($savings > 0) ? 'Save $' . number_format($savings, 2) : '-';
?>

                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($car['weekly_rate'])): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                Weekly
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                $<?= number_format($car['weekly_rate'], 2) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                            <?php 
$savings = ((float)$dynamicPrice * 7) - $car['weekly_rate'];
echo ($savings > 0) ? 'Save $' . number_format($savings, 2) : '-';
?>

                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($car['monthly_rate'])): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                Monthly
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                $<?= number_format($car['monthly_rate'], 2) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                            <?php 
$savings = ((float)$dynamicPrice * 30) - $car['monthly_rate'];
echo ($savings > 0) ? 'Save $' . number_format($savings, 2) : '-';
?>

                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Admin Actions -->
                        
                </div>
                </div>
                
                <!-- Reviews Section -->
                <div class="mt-8 bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Customer Reviews</h3>
                        
                        <?php if (empty($reviews)): ?>
                            <div class="text-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                                <p class="text-gray-500 mb-4">No reviews yet for this car.</p>
                                <?php if (isLoggedIn() && $hasRented): ?>
                                    <button type="button" 
            onclick="openReviewModal()"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
        </svg>
        Write Your Review
    </button>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="space-y-6">
                                <?php foreach ($reviews as $review): ?>
                                <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                                    <div class="flex items-center mb-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <?php if (!empty($review['profile_image'])): ?>
                                                <img src="<?= htmlspecialchars($review['profile_image']) ?>" alt="<?= htmlspecialchars($review['username']) ?>" class="w-10 h-10 rounded-full">
                                            <?php else: ?>
                                                <span class="text-blue-600 font-bold"><?= strtoupper(substr($review['username'], 0, 2)) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($review['full_name']) ?></h4>
                                            <div class="flex items-center">
                                            <div class="flex text-yellow-400">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <?php if ($i <= $review['rating']): ?>
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        <?php else: ?>
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                </div>

                                                <span class="text-xs text-gray-500 ml-2">
                                                    <?= date('M d, Y', strtotime($review['created_at'])) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="text-gray-700 text-sm">
                                        <?= nl2br(htmlspecialchars($review['feedback'])) ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <?php if (isLoggedIn() && $hasRented): ?>
                                <div class="mt-6 text-center">
                <button type="button" 
            onclick="openReviewModal()"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
        </svg>
        Write Your Review
    </button>
</div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Booking Widget -->
                <?php if ($car['status'] === 'available'): ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                    <div class="bg-blue-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Quick Booking</h3>
                    </div>
                    <div class="p-6">
                        <?php if (isLoggedIn()): ?>
                            <form action="index.php?page=rentals&action=create" method="POST">
                                <input type="hidden" name="car_id" value="<?= $car['car_id'] ?>">
                                
                                <div class="mb-4">
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Pickup Date</label>
                                    <input type="date" id="start_date" name="start_date" 
                                           min="<?= date('Y-m-d') ?>" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                           required>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Return Date</label>
                                    <input type="date" id="end_date" name="end_date" 
                                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                           required>
                                </div>
                                
                                <?php if ($promotion): ?>
                                <div class="mb-4">
                                    <label for="promo_code" class="block text-sm font-medium text-gray-700 mb-1">Promo Code</label>
                                    <div class="flex">
                                        <input type="text" id="promo_code" name="promo_code" 
                                               value="<?= htmlspecialchars($promotion['code']) ?>"
                                               class="w-full rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <button type="button" onclick="applyPromoCode()" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-50 text-gray-500 rounded-r-md hover:bg-gray-100">
                                            Apply
                                        </button>
                                    </div>
                                    <p class="mt-1 text-xs text-green-600 hidden" id="promo-success">Promo code applied successfully!</p>
                                </div>
                                <?php endif; ?>
                                
                                <div class="mb-6">
                                    <div class="flex justify-between py-2 text-sm">
                                        <span class="text-gray-600">Daily Rate:</span>
                                        <span class="text-gray-900 font-medium">$<?= number_format((float)$dynamicPrice, 2) ?></span>
                                    </div>
                                    <div class="flex justify-between py-2 text-sm border-t border-gray-200">
                                        <span class="text-gray-600">Rental Days:</span>
                                        <span class="text-gray-900 font-medium" id="rental-days">0</span>
                                    </div>
                                    <div class="flex justify-between py-2 text-sm border-t border-gray-200">
                                        <span class="text-gray-600">Subtotal:</span>
                                        <span class="text-gray-900 font-medium" id="subtotal">$0.00</span>
                                    </div>
                                    <?php if ($promotion): ?>
                                    <div class="flex justify-between py-2 text-sm border-t border-gray-200 text-green-600" id="discount-row" style="display: none;">
                                        <span>Discount (<?= $promotion['discount_percentage'] ?>%):</span>
                                        <span id="discount-amount">-$0.00</span>
                                    </div>
                                    <?php endif; ?>
                                    <div class="flex justify-between py-3 text-base font-semibold border-t border-gray-200">
                                        <span class="text-gray-900">Total:</span>
                                        <span class="text-blue-600" id="total">$0.00</span>
                                    </div>
                                </div>
                                
                                <button type="submit" 
                                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Book Now
                                </button>
                            </form>
                            
                            <script>
                                // Calculate rental days and total
                                const dailyRate = <?= $dynamicPrice ?>;
                                const discountPercentage = <?= $promotion ? $promotion['discount_percentage'] : 0 ?>;
                                let promoApplied = false;
                                
                                function calculateTotal() {
                                    const startDate = new Date(document.getElementById('start_date').value);
                                    const endDate = new Date(document.getElementById('end_date').value);
                                    
                                    if (startDate && endDate && startDate < endDate) {
                                        const diffTime = Math.abs(endDate - startDate);
                                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                                        
                                        document.getElementById('rental-days').textContent = diffDays;
                                        
                                        const subtotal = dailyRate * diffDays;
                                        document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
                                        
                                        let total = subtotal;
                                        
                                        if (promoApplied && discountPercentage > 0) {
                                            const discountAmount = subtotal * (discountPercentage / 100);
                                            document.getElementById('discount-amount').textContent = '-$' + discountAmount.toFixed(2);
                                            document.getElementById('discount-row').style.display = 'flex';
                                            total = subtotal - discountAmount;
                                        }
                                        
                                        document.getElementById('total').textContent = '$' + total.toFixed(2);
                                    }
                                }
                                
                                function applyPromoCode() {
                                    promoApplied = true;
                                    document.getElementById('promo-success').classList.remove('hidden');
                                    calculateTotal();
                                }
                                
                                document.getElementById('start_date').addEventListener('change', calculateTotal);
                                document.getElementById('end_date').addEventListener('change', calculateTotal);
                            </script>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <p class="text-gray-600 mb-4">Please log in to book this car.</p>
                                <a href="index.php?page=auth&action=login" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Login to Continue
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Similar Cars -->
                <?php if (!empty($similarCars)): ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-blue-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Similar Cars</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php foreach ($similarCars as $similarCar): ?>
                            <a href="index.php?page=cars&action=view&id=<?= $similarCar['car_id'] ?>" class="block group">
                                <div class="flex items-center">
                                    <div class="w-20 h-16 bg-gray-200 rounded-md overflow-hidden flex-shrink-0">
                                        <img src="<?= $similarCar['image_url'] ?? 'assets/images/default-car.jpg' ?>" 
                                             alt="<?= htmlspecialchars($similarCar['make'] . ' ' . $similarCar['model']) ?>" 
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-900 group-hover:text-blue-600">
                                            <?= htmlspecialchars($similarCar['make'] . ' ' . $similarCar['model'] . ' ' . $similarCar['year']) ?>
                                        </h4>
                                        <p class="text-sm text-gray-500">
                                            $<?= number_format($similarCar['daily_rate'], 2) ?> / day
                                        </p>
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                Available
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Maintenance History (Admin Only) -->
                <?php if (isAdmin() && !empty($maintenanceHistory)): ?>
                <div class="mt-8 bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-yellow-600 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Maintenance History</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php foreach ($maintenanceHistory as $record): ?>
                            <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">
                                            <?= ucfirst(htmlspecialchars($record['maintenance_type'])) ?>
                                        </h4>
                                        <p class="text-sm text-gray-500">
                                            <?= date('M d, Y', strtotime($record['start_date'])) ?>
                                            <?= $record['end_date'] ? ' to ' . date('M d, Y', strtotime($record['end_date'])) : '' ?>
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        <?php 
                                        echo match($record['status']) {
                                            'completed' => 'bg-green-100 text-green-800',
                                            'in_progress' => 'bg-blue-100 text-blue-800',
                                            default => 'bg-yellow-100 text-yellow-800'
                                        };
                                        ?>">
                                        <?= ucfirst(str_replace('_', ' ', $record['status'])) ?>
                                    </span>
                                </div>
                                <p class="mt-2 text-sm text-gray-600">
                                    <?= htmlspecialchars($record['description']) ?>
                                </p>
                                <?php if ($record['cost']): ?>
                                <p class="mt-1 text-sm text-gray-500">
                                    Cost: $<?= number_format($record['cost'], 2) ?>
                                </p>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Review Modal (Hidden by default) -->
<div id="reviewModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 overflow-hidden">
        <div class="bg-blue-600 px-4 py-3 flex justify-between items-center">
            <h3 class="text-lg font-medium text-white">Write Your Review</h3>
            <button type="button" onclick="closeReviewModal()" class="text-white hover:text-gray-200">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form action="index.php?page=cars&action=submit_review" method="POST" class="p-6">
        <input type="hidden" name="car_id" value="<?= $car['car_id'] ?>">
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Rating</label>
                <div class="flex space-x-2">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <label class="cursor-pointer">
                        <input type="radio" name="rating" value="<?= $i ?>" class="hidden peer" <?= $i === 5 ? 'checked' : '' ?>>
                        <svg class="h-8 w-8 text-gray-300 peer-checked:text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </label>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="mb-6">
                <label for="feedback" class="block text-gray-700 text-sm font-bold mb-2">Your Review</label>
                <textarea id="feedback" name="feedback" rows="4" 
                          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                          placeholder="Share your experience with this car..."></textarea>
            </div>
            
            <div class="flex items-center justify-end">
                <button type="button" onclick="closeReviewModal()" class="mr-4 text-gray-600 hover:text-gray-800">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Submit Review
                </button>
            </div>
        </form>
    </div>
</div>

</div>

<script>
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
function openReviewModal() {
        document.getElementById('reviewModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
    }
    
    function closeReviewModal() {
        document.getElementById('reviewModal').classList.add('hidden');
        document.body.style.overflow = ''; // Restore scrolling
    }
    
    // Close modal when clicking outside of it
    document.getElementById('reviewModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeReviewModal();
        }
    });
</script>

<?php require 'views/layouts/footer.php'; ?>

<?php require 'views/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-4 py-5 sm:px-6 bg-blue-600">
                <h1 class="text-xl font-semibold text-white">Edit Rental</h1>
                <p class="mt-1 text-sm text-blue-100">
                    Modify your rental details for <?= htmlspecialchars($carDetails['make'] . ' ' . $carDetails['model']) ?>
                </p>
            </div>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700"><?= $_SESSION['error'] ?></p>
                        </div>
                    </div>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <form action="index.php?page=rentals&action=edit" method="POST" class="p-6">
                <input type="hidden" name="rental_id" value="<?= $rental['rental_id'] ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Car Details Section -->
                    <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <img src="<?= $carDetails['image_url'] ?? 'assets/images/default-car.jpeg' ?>" 
                                     alt="<?= htmlspecialchars($carDetails['make'] . ' ' . $carDetails['model']) ?>" 
                                     class="h-24 w-24 object-cover rounded-md">
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    <?= htmlspecialchars($carDetails['make'] . ' ' . $carDetails['model'] . ' ' . $carDetails['year']) ?>
                                </h3>
                                <p class="text-sm text-gray-500">
                                    Daily Rate: $<?= number_format($carDetails['daily_rate'], 2) ?>
                                </p>
                                <p class="text-sm text-gray-500">
                                    Registration: <?= htmlspecialchars($carDetails['registration_number']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Date Selection -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" id="start_date" name="start_date" 
                               value="<?= $rental['start_date'] ?>"
                               min="<?= date('Y-m-d') ?>"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                               required>
                    </div>
                    
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" id="end_date" name="end_date" 
                               value="<?= $rental['end_date'] ?>"
                               min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                               required>
                    </div>
                    
                    <!-- Pickup and Return Locations -->
                    <div>
                        <label for="pickup_location" class="block text-sm font-medium text-gray-700">Pickup Location</label>
                        <select id="pickup_location" name="pickup_location" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="Downtown" <?= $rental['pickup_location'] == 'Downtown' ? 'selected' : '' ?>>Downtown</option>
                            <option value="Airport" <?= $rental['pickup_location'] == 'Airport' ? 'selected' : '' ?>>Airport</option>
                            <option value="North Branch" <?= $rental['pickup_location'] == 'North Branch' ? 'selected' : '' ?>>North Branch</option>
                            <option value="South Branch" <?= $rental['pickup_location'] == 'South Branch' ? 'selected' : '' ?>>South Branch</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="return_location" class="block text-sm font-medium text-gray-700">Return Location</label>
                        <select id="return_location" name="return_location" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="Downtown" <?= $rental['return_location'] == 'Downtown' ? 'selected' : '' ?>>Downtown</option>
                            <option value="Airport" <?= $rental['return_location'] == 'Airport' ? 'selected' : '' ?>>Airport</option>
                            <option value="North Branch" <?= $rental['return_location'] == 'North Branch' ? 'selected' : '' ?>>North Branch</option>
                            <option value="South Branch" <?= $rental['return_location'] == 'South Branch' ? 'selected' : '' ?>>South Branch</option>
                        </select>
                    </div>
                    
                    <!-- Promotion Code -->
                    <div class="md:col-span-2">
                        <label for="promo_code" class="block text-sm font-medium text-gray-700">Promotion Code (Optional)</label>
                        <input type="text" id="promo_code" name="promo_code" 
                               value="<?= $rental['promo_code'] ?? '' ?>"
                               placeholder="Enter promotion code if you have one"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        
                        <?php if (!empty($promotions)): ?>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-1">Available promotions:</p>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($promotions as $promo): ?>
                                        <button type="button" 
                                                onclick="document.getElementById('promo_code').value='<?= $promo['code'] ?>'"
                                                class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <?= $promo['code'] ?>: 
                                            <?php if ($promo['discount_percentage']): ?>
                                                <?= $promo['discount_percentage'] ?>% off
                                            <?php elseif ($promo['discount_amount']): ?>
                                                $<?= $promo['discount_amount'] ?> off
                                            <?php endif; ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="index.php?page=rentals&action=view&id=<?= $rental['rental_id'] ?>" 
                       class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Update Rental
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Rental Policy Information -->
        <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                <h2 class="text-lg font-medium text-gray-900">Rental Policies</h2>
            </div>
            <div class="px-4 py-5 sm:p-6 text-sm text-gray-600 space-y-3">
                <p>• Changes to your rental are only allowed while the rental is in 'pending' status.</p>
                <p>• You can modify your rental dates, pickup/return locations, and apply promotion codes.</p>
                <p>• If you need to make changes after your rental is approved, please contact customer support.</p>
                <p>• Cancellation is available for pending and approved rentals with no fee.</p>
            </div>
        </div>
    </div>
</div>

<script>
// Simple date validation
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    startDateInput.addEventListener('change', function() {
        const startDate = new Date(this.value);
        const minEndDate = new Date(startDate);
        minEndDate.setDate(minEndDate.getDate() + 1);
        
        // Format the date as YYYY-MM-DD for the min attribute
        const minEndDateStr = minEndDate.toISOString().split('T')[0];
        endDateInput.min = minEndDateStr;
        
        // If current end date is before new min end date, update it
        if (new Date(endDateInput.value) < minEndDate) {
            endDateInput.value = minEndDateStr;
        }
    });
});
</script>

<?php require 'views/layouts/footer.php'; ?>
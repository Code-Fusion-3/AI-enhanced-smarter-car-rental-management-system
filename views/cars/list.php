<?php require 'views/layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Available Cars</h2>
        <?php if (isAdmin()): ?>
            <a href="index.php?page=cars&action=add" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Add New Car</a>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($cars as $car): ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="<?= $car['image_url'] ?? 'assets/images/default-car.jpg' ?>" 
                     alt="<?= $car['make'] ?> <?= $car['model'] ?>" 
                     class="w-full h-48 object-cover">
                
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900">
                        <?= $car['make'] ?> <?= $car['model'] ?> <?= $car['year'] ?>
                    </h3>
                    
                    <div class="mt-4 space-y-2">
                        <p class="text-gray-600">Registration: <?= $car['registration_number'] ?></p>
                        <p class="text-2xl font-bold text-blue-600">
                            $<?= number_format($car['daily_rate'], 2) ?> /day
                        </p>
                    </div>

                    <div class="mt-6 flex space-x-3">
                        <a href="index.php?page=cars&action=view&id=<?= $car['car_id'] ?>" 
                           class="flex-1 text-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            View Details
                        </a>
                        <?php if (isLoggedIn()): ?>
                            <a href="index.php?page=rentals&action=create&car_id=<?= $car['car_id'] ?>" 
                               class="flex-1 text-center bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                Rent Now
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>

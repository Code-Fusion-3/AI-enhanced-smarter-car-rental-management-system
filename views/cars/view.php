<?php require 'views/layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            <div class="md:flex-shrink-0">
                <img class="h-96 w-full object-cover md:w-96" 
                     src="<?= $car['image_url'] ?? 'assets/images/default-car.jpg' ?>" 
                     alt="<?= $car['make'] ?> <?= $car['model'] ?>">
            </div>
            
            <div class="p-8">
                <h2 class="text-3xl font-bold text-gray-900">
                    <?= $car['make'] ?> <?= $car['model'] ?> <?= $car['year'] ?>
                </h2>
                
                <div class="mt-6 space-y-4">
                    <p class="text-gray-600">Registration: <?= $car['registration_number'] ?></p>
                    <p class="text-gray-600">Features: <?= $car['features'] ?></p>
                    <p class="text-3xl font-bold text-blue-600">
                        $<?= number_format($dynamicPrice, 2) ?> /day
                    </p>
                    <p class="text-sm text-gray-500">*Price adjusted based on current demand</p>
                </div>

                <?php if (isLoggedIn()): ?>
                    <div class="mt-8">
                        <a href="index.php?page=rentals&action=create&car_id=<?= $car['car_id'] ?>" 
                           class="inline-block bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700">
                            Rent This Car
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>

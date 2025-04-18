<?php require 'views/layouts/header.php'; ?>

<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-2xl font-bold text-gray-900">Rental Details</h2>
        </div>
        
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Vehicle</h3>
                    <p class="text-lg text-gray-900"><?= $rental['make'] ?> <?= $rental['model'] ?></p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Registration</h3>
                    <p class="text-lg text-gray-900"><?= $rental['registration_number'] ?></p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Start Date</h3>
                    <p class="text-lg text-gray-900"><?= date('M d, Y', strtotime($rental['start_date'])) ?></p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">End Date</h3>
                    <p class="text-lg text-gray-900"><?= date('M d, Y', strtotime($rental['end_date'])) ?></p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Status</h3>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        <?= $rental['status'] === 'active' ? 'bg-green-100 text-green-800' : 
                            ($rental['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                            'bg-gray-100 text-gray-800') ?>">
                        <?= ucfirst($rental['status']) ?>
                    </span>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Total Cost</h3>
                    <p class="text-lg font-semibold text-blue-600">$<?= number_format($rental['total_cost'], 2) ?></p>
                </div>
            </div>
            
            <div class="mt-8 flex justify-end">
                <a href="index.php?page=rentals" class="text-blue-600 hover:text-blue-900">Back to Rentals</a>
            </div>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>

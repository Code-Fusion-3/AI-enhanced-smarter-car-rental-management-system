<?php require 'views/layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold text-gray-900 mb-6">My Rentals</h2>
    <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">Your rental booking has been confirmed.</span>
        </div>
    <?php endif; ?>
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Car</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Cost</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($rentals as $rental): ?>
                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                <?= $rental['make'] ?> <?= $rental['model'] ?>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                <?= date('M d, Y', strtotime($rental['start_date'])) ?> - 
                                <?= date('M d, Y', strtotime($rental['end_date'])) ?>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?= $rental['status'] === 'active' ? 'bg-green-100 text-green-800' : 
                                    ($rental['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    'bg-gray-100 text-gray-800') ?>">
                                <?= ucfirst($rental['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                $<?= number_format($rental['total_cost'], 2) ?>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <a href="index.php?page=rentals&action=view&id=<?= $rental['rental_id'] ?>" 
                               class="text-blue-600 hover:text-blue-900">View Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>

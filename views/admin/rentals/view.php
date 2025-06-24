<div class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Admin Sidebar -->
        <?php require 'views/admin/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-8">

            <div class="container mx-auto px-4 py-8">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Rental Details</h1>
                    <div class="flex space-x-2">
                        <a href="index.php?page=admin&action=rentals"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm">
                            Back to Rentals
                        </a>

                    </div>
                </div>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <span class="block sm:inline"><?= $_SESSION['success'] ?></span>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?= $_SESSION['error'] ?></span>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <!-- Rental Status and Actions -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Rental #<?= $rental['rental_id'] ?></h2>
                            <div class="mt-2">
                                <?php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-blue-100 text-blue-800',
                                    'active' => 'bg-green-100 text-green-800',
                                    'completed' => 'bg-gray-100 text-green-500',
                                    'cancelled' => 'bg-red-100 text-red-800'
                                ];
                                $statusClass = $statusClasses[$rental['rental_status']] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span
                                    class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full <?= $statusClass ?>">
                                    <?= ucfirst($rental['rental_status']) ?>
                                </span>

                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
                            <?php if ($rental['rental_status'] === 'pending'): ?>
                                <a href="index.php?page=admin&action=rentals&subaction=approve&id=<?= $rental['rental_id'] ?>"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm"
                                    onclick="return confirm('Are you sure you want to approve this rental?')">
                                    Approve Rental
                                </a>
                            <?php endif; ?>

                            <?php if ($rental['rental_status'] === 'approved' || $rental['rental_status'] === 'active'): ?>
                                <a href="index.php?page=admin&action=rentals&subaction=complete&id=<?= $rental['rental_id'] ?>"
                                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm"
                                    onclick="return confirm('Are you sure you want to mark this rental as completed?')">
                                    Mark as Completed
                                </a>
                            <?php endif; ?>

                            <?php if ($rental['rental_status'] !== 'completed' && $rental['rental_status'] !== 'cancelled'): ?>
                                <a href="index.php?page=admin&action=rentals&subaction=cancel&id=<?= $rental['rental_id'] ?>"
                                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm"
                                    onclick="return confirm('Are you sure you want to cancel this rental?')">
                                    Cancel Rental
                                </a>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Rental Details -->
                    <div class="md:col-span-2">
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800">Rental Information</h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">
                                            Rental Period</h4>
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="flex items-center mb-2">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">Start Date</div>
                                                    <div class="text-sm text-gray-500">
                                                        <?= date('F j, Y', strtotime($rental['start_date'])) ?></div>
                                                </div>
                                            </div>
                                            <div class="flex items-center mb-2">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">End Date</div>
                                                    <div class="text-sm text-gray-500">
                                                        <?= date('F j, Y', strtotime($rental['end_date'])) ?></div>
                                                </div>
                                            </div>
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">Duration</div>
                                                    <div class="text-sm text-gray-500"><?= $duration ?> days</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">
                                            Pricing Details</h4>
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="flex justify-between mb-2">
                                                <span class="text-sm text-gray-500">Daily Rate:</span>
                                                <span class="text-sm font-medium text-gray-900">
                                                    <?= formatCurrency($rental['daily_rate']) ?>
                                                </span>
                                            </div>
                                            <div class="flex justify-between mb-2">
                                                <span class="text-sm text-gray-500">Duration:</span>
                                                <span class="text-sm font-medium text-gray-900"><?= $duration ?>
                                                    days</span>
                                            </div>
                                            <div class="flex justify-between mb-2">
                                                <span class="text-sm text-gray-500">Base Cost:</span>
                                                <span class="text-sm font-medium text-gray-900">
                                                    <?= formatCurrency($rental['daily_rate'] * $duration) ?>
                                                </span>
                                            </div>

                                            <?php if (!empty($rental['discount_amount']) && $rental['discount_amount'] > 0): ?>
                                                <div class="flex justify-between mb-2">
                                                    <span class="text-sm text-gray-500">Discount:</span>
                                                    <span class="text-sm font-medium text-green-600">
                                                        -<?= formatCurrency($rental['discount_amount']) ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (!empty($rental['additional_charges']) && $rental['additional_charges'] > 0): ?>
                                                <div class="flex justify-between mb-2">
                                                    <span class="text-sm text-gray-500">Additional Charges:</span>
                                                    <span class="text-sm font-medium text-red-600">
                                                        +<?= formatCurrency($rental['additional_charges']) ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>

                                            <div class="border-t border-gray-200 mt-2 pt-2 flex justify-between">
                                                <span class="text-sm font-medium text-gray-900">Total Cost:</span>
                                                <span class="text-sm font-bold text-gray-900">
                                                    <?= formatCurrency($rental['total_cost']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Location
                                        Information</h4>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 text-gray-400 mr-2" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">Pickup Location
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            <?= !empty($rental['pickup_location']) ? htmlspecialchars($rental['pickup_location']) : 'Not specified' ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 text-gray-400 mr-2" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">Return Location
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            <?= !empty($rental['return_location']) ? htmlspecialchars($rental['return_location']) : 'Not specified' ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php if (!empty($rental['notes'])): ?>
                                    <div class="mt-6">
                                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Notes
                                        </h4>
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <p class="text-sm text-gray-700 whitespace-pre-line">
                                                <?= htmlspecialchars($rental['notes']) ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($rental['promotion_id'])): ?>
                                    <div class="mt-6">
                                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Applied
                                            Promotion</h4>
                                        <div class="bg-gray-50 rounded-lg p-4 flex items-center">
                                            <div
                                                class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold mr-3">
                                                <?= htmlspecialchars($promotion['code']) ?>
                                            </div>
                                            <p class="text-sm text-gray-700">
                                                <?= htmlspecialchars($promotion['description']) ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Rental History -->
                        <?php if (!empty($rentalHistory)): ?>
                            <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
                                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800">Rental History</h3>
                                </div>
                                <div class="p-6">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Return Date
                                                    </th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Condition
                                                    </th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Additional Charges
                                                    </th>
                                                    <th scope="col"
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Rating
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <?php foreach ($rentalHistory as $history): ?>
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            <?= !empty($history['return_date']) ? date('F j, Y, g:i a', strtotime($history['return_date'])) : 'Not returned yet' ?>
                                                        </td>
                                                        <td class="px-6 py-4 text-sm text-gray-500">
                                                            <?= !empty($history['return_condition']) ? htmlspecialchars($history['return_condition']) : 'No condition reported' ?>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            <?= !empty($history['additional_charges']) ? '$' . number_format($history['additional_charges'], 2) : '$0.00' ?>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            <?php if (!empty($history['rating'])): ?>
                                                                <div class="flex items-center">
                                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                        <?php if ($i <= $history['rating']): ?>
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20"
                                                                                fill="currentColor">
                                                                                <path
                                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                            </svg>
                                                                        <?php else: ?>
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                class="h-5 w-5 text-gray-300" viewBox="0 0 20 20"
                                                                                fill="currentColor">
                                                                                <path
                                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                            </svg>
                                                                        <?php endif; ?>
                                                                    <?php endfor; ?>
                                                                </div>
                                                            <?php else: ?>
                                                                <span>No rating</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <?php if (!empty($rentalHistory[0]['feedback'])): ?>
                                        <div class="mt-4 bg-gray-50 rounded-lg p-4">
                                            <h4 class="text-sm font-medium text-gray-700 mb-2">Customer Feedback:</h4>
                                            <p class="text-sm text-gray-600 italic">
                                                "<?= htmlspecialchars($rentalHistory[0]['feedback']) ?>"</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Sidebar -->
                    <div>
                        <!-- User Information -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800">Customer Information</h3>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center mb-4">
                                    <div
                                        class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                                        <?= strtoupper(substr($user['username'], 0, 2)) ?>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-medium text-gray-900">
                                            <?= htmlspecialchars($user['full_name']) ?></h4>
                                        <p class="text-sm text-gray-500"><?= htmlspecialchars($user['username']) ?></p>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span
                                            class="text-sm text-gray-600"><?= htmlspecialchars($user['email']) ?></span>
                                    </div>

                                    <?php if (!empty($user['phone'])): ?>
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            <span
                                                class="text-sm text-gray-600"><?= htmlspecialchars($user['phone']) ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($user['driver_license'])): ?>
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                            </svg>
                                            <span class="text-sm text-gray-600">License:
                                                <?= htmlspecialchars($user['driver_license']) ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($user['address'])): ?>
                                        <div class="flex items-start">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-gray-400 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span
                                                class="text-sm text-gray-600"><?= nl2br(htmlspecialchars($user['address'])) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>

                        <!-- Car Information -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800">Vehicle Information</h3>
                            </div>
                            <div class="p-6">
                                <div class="mb-4">
                                    <img src="<?= !empty($car['image_url']) ? $car['image_url'] : 'assets/images/default-car.jpg' ?>"
                                        alt="<?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?>"
                                        class="w-full h-40 object-cover rounded-lg">
                                </div>

                                <h4 class="text-lg font-medium text-gray-900 mb-2">
                                    <?= htmlspecialchars($car['make'] . ' ' . $car['model'] . ' (' . $car['year'] . ')') ?>
                                </h4>

                                <div class="flex items-center mb-4">
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?= htmlspecialchars($categoryName ?? 'Uncategorized') ?>
                                    </span>
                                    <span
                                        class="ml-2 px-2 py-1 text-xs font-semibold rounded-full <?= $car['status'] === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= ucfirst($car['status']) ?>
                                    </span>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm text-gray-600">Daily Rate:
                                            <?= formatCurrency($car['daily_rate']) ?></span>
                                    </div>

                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                        </svg>
                                        <span class="text-sm text-gray-600">Registration:
                                            <?= htmlspecialchars($car['registration_number']) ?></span>
                                    </div>

                                    <?php if (!empty($car['fuel_type'])): ?>
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                            <span class="text-sm text-gray-600">Fuel Type:
                                                <?= ucfirst($car['fuel_type']) ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($car['transmission'])): ?>
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="text-sm text-gray-600">Transmission:
                                                <?= ucfirst($car['transmission']) ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($car['seats'])): ?>
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span class="text-sm text-gray-600">Seats: <?= $car['seats'] ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($car['features'])): ?>
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <h5 class="text-sm font-medium text-gray-700 mb-2">Features:</h5>
                                        <div class="flex flex-wrap gap-2">
                                            <?php foreach (explode(',', $car['features']) as $feature): ?>
                                                <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">
                                                    <?= trim(htmlspecialchars($feature)) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <a href="index.php?page=admin&action=cars&subaction=view&id=<?= $car['car_id'] ?>"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View Full Car Details
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information (if available) -->
                        <?php if (!empty($payments)): ?>
                            <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
                                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-800">Payment Information</h3>
                                </div>
                                <div class="p-6">
                                    <?php
                                    $paymentCount = count($payments);
                                    foreach ($payments as $index => $payment):
                                        $isLast = ($index === $paymentCount - 1);
                                        ?>
                                        <div class="mb-4 pb-4 <?= !$isLast ? 'border-b border-gray-200' : '' ?>">
                                            <div class="flex justify-between items-center mb-2">
                                                <div>
                                                    <span class="text-sm font-medium text-gray-900">
                                                        <?= formatCurrency($payment['amount']) ?>
                                                    </span>
                                                    <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full 
                        <?php
                        switch ($payment['status']) {
                            case 'completed':
                                echo 'bg-green-100 text-green-800';
                                break;
                            case 'pending':
                                echo 'bg-yellow-100 text-yellow-800';
                                break;
                            case 'failed':
                                echo 'bg-red-100 text-red-800';
                                break;
                            case 'refunded':
                                echo 'bg-purple-100 text-purple-800';
                                break;
                            default:
                                echo 'bg-gray-100 text-gray-800';
                        }
                        ?>">
                                                        <?= ucfirst($payment['status']) ?>
                                                    </span>
                                                </div>
                                                <span class="text-xs text-gray-500">
                                                    <?= date('M d, Y', strtotime($payment['payment_date'])) ?>
                                                </span>
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                Method: <?= ucfirst(str_replace('_', ' ', $payment['payment_method'])) ?>
                                            </div>
                                            <?php if (!empty($payment['transaction_id'])): ?>
                                                <div class="text-sm text-gray-600">
                                                    Transaction ID: <?= htmlspecialchars($payment['transaction_id']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
<?php require 'views/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 bg-blue-600 text-white p-4 rounded-lg shadow-lg">
            <h1 class="text-3xl font-bold text-gray-900">My Rentals</h1>
            <p class="mt-2 text-sm text-gray-100">Manage your car rentals and view your rental history</p>
        </div>
        <script>
            // Tab functionality
            function showTab(tabId) {
                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(tab => {
                    tab.classList.add('hidden');
                });

                // Show the selected tab content
                document.getElementById(tabId + '-tab').classList.remove('hidden');

                // Update tab button styles
                document.querySelectorAll('.tab-button').forEach(button => {
                    button.classList.remove('text-blue-600', 'border-blue-600');
                    button.classList.add('text-gray-500', 'border-transparent');
                });

                // Highlight the active tab button
                event.currentTarget.classList.remove('text-gray-500', 'border-transparent');
                event.currentTarget.classList.add('text-blue-600', 'border-blue-600');
            }
        </script>
        <!-- Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button onclick="showTab('upcoming')"
                        class="tab-button text-blue-600 border-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Upcoming
                        <?php if (!empty($upcomingRentals)): ?>
                            <span class="ml-2 bg-blue-100 text-blue-600 py-0.5 px-2.5 rounded-full text-xs font-medium">
                                <?= count($upcomingRentals) ?>
                            </span>
                        <?php endif; ?>
                    </button>
                    <button onclick="showTab('active')"
                        class="tab-button text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 border-transparent font-medium text-sm">
                        Active
                        <?php if (!empty($activeRentals)): ?>
                            <span class="ml-2 bg-green-100 text-green-600 py-0.5 px-2.5 rounded-full text-xs font-medium">
                                <?= count($activeRentals) ?>
                            </span>
                        <?php endif; ?>
                    </button>
                    <button onclick="showTab('past')"
                        class="tab-button text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 border-transparent font-medium text-sm">
                        Past Rentals
                    </button>
                    <button onclick="showTab('all')"
                        class="tab-button text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 border-transparent font-medium text-sm">
                        All Rentals
                    </button>
                </nav>
            </div>
        </div>

        <!-- Empty state -->
        <?php if (empty($rentals)): ?>
            <div class="text-center py-12 bg-white rounded-lg shadow">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No rentals found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by renting a car.</p>
                <div class="mt-6">
                    <a href="index.php?page=cars"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Browse Cars
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- Upcoming Rentals Tab -->
            <div id="upcoming-tab" class="tab-content">
                <?php if (empty($upcomingRentals)): ?>
                    <div class="text-center py-8 bg-white rounded-lg shadow">
                        <p class="text-gray-500">You don't have any upcoming rentals.</p>
                        <div class="mt-4">
                            <a href="index.php?page=cars" class="text-blue-600 hover:text-blue-800">Browse cars to rent</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <?php foreach ($upcomingRentals as $rental): ?>
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="relative">
                                    <img class="h-48 w-full object-cover"
                                        src="<?= $rental['image_url'] ?? 'assets/images/car1.png' ?>"
                                        alt="<?= $rental['make'] ?> <?= $rental['model'] ?>">
                                    <div class="absolute top-0 right-0 mt-2 mr-2">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <?= ucfirst($rental['status']) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <h3 class="text-lg font-medium text-gray-900"><?= $rental['make'] ?>             <?= $rental['model'] ?>
                                        <?= $rental['year'] ?></h3>

                                    <div class="mt-2 flex items-center text-sm text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <?= date('M d, Y', strtotime($rental['start_date'])) ?> -
                                        <?= date('M d, Y', strtotime($rental['end_date'])) ?>
                                    </div>

                                    <?php if (!empty($rental['pickup_location'])): ?>
                                        <div class="mt-2 flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Pickup: <?= htmlspecialchars($rental['pickup_location']) ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="mt-4 flex justify-between">
                                        <div>
                                            <p class="text-sm text-gray-500">Total Cost</p>
                                            <p class="text-lg font-semibold text-gray-900">
                                                <?= formatCurrency($rental['total_cost']) ?></p>
                                            <?php if (!empty($rental['promo_code'])): ?>
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    Promo: <?= $rental['promo_code'] ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <?php if ($rental['status'] === 'pending'): ?>
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Awaiting Approval
                                                </span>
                                            <?php elseif ($rental['status'] === 'approved' && empty($rental['payment_complete'])): ?>
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Payment Required
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="mt-5 flex space-x-2">
                                        <a href="index.php?page=rentals&action=view&id=<?= $rental['rental_id'] ?>"
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd"
                                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Details
                                        </a>

                                        <?php if ($rental['status'] === 'pending'): ?>
                                            <a href="index.php?page=rentals&action=cancel&id=<?= $rental['rental_id'] ?>"
                                                onclick="return confirm('Are you sure you want to cancel this rental?');"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Cancel
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($rental['status'] === 'approved' && empty($rental['payment_complete'])): ?>
                                            <a href="index.php?page=payments&action=pay&rental_id=<?= $rental['rental_id'] ?>"
                                                class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Pay Now
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Active Rentals Tab -->
            <div id="active-tab" class="tab-content hidden">
                <?php if (empty($activeRentals)): ?>
                    <div class="text-center py-8 bg-white rounded-lg shadow">
                        <p class="text-gray-500">You don't have any active rentals.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <?php foreach ($activeRentals as $rental): ?>
                            <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-green-500">
                                <div class="relative">
                                    <img class="h-48 w-full object-cover"
                                        src="<?= $rental['image_url'] ?? 'assets/images/car.png' ?>"
                                        alt="<?= $rental['make'] ?> <?= $rental['model'] ?>">
                                    <div class="absolute top-0 right-0 mt-2 mr-2">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <h3 class="text-lg font-medium text-gray-900"><?= $rental['make'] ?>             <?= $rental['model'] ?>
                                        <?= $rental['year'] ?></h3>

                                    <div class="mt-2 flex items-center text-sm text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <?= date('M d, Y', strtotime($rental['start_date'])) ?> -
                                        <?= date('M d, Y', strtotime($rental['end_date'])) ?>
                                    </div>

                                    <?php
                                    // Calculate days remaining
                                    $endDate = new DateTime($rental['end_date']);
                                    $today = new DateTime();
                                    $daysRemaining = $today->diff($endDate)->days;
                                    $isOverdue = $today > $endDate;
                                    ?>

                                    <div class="mt-2 flex items-center">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 <?= $isOverdue ? 'text-red-500' : 'text-yellow-500' ?>"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-sm <?= $isOverdue ? 'text-red-600 font-medium' : 'text-gray-500' ?>">
                                            <?php if ($isOverdue): ?>
                                                Overdue by <?= $daysRemaining ?> day<?= $daysRemaining > 1 ? 's' : '' ?>
                                            <?php else: ?>
                                                <?= $daysRemaining ?> day<?= $daysRemaining > 1 ? 's' : '' ?> remaining
                                            <?php endif; ?>
                                        </span>
                                    </div>

                                    <div class="mt-4 flex justify-between">
                                        <div>
                                            <p class="text-sm text-gray-500">Total Cost</p>
                                            <p class="text-lg font-semibold text-gray-900">
                                                <?= formatCurrency($rental['total_cost']) ?></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Reg. Number</p>
                                            <p class="text-sm font-medium text-gray-900"><?= $rental['registration_number'] ?></p>
                                        </div>
                                    </div>

                                    <div class="mt-5 flex space-x-2">
                                        <a href="index.php?page=rentals&action=view&id=<?= $rental['rental_id'] ?>"
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd"
                                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Details
                                        </a>

                                        <a href="index.php?page=rentals&action=return&id=<?= $rental['rental_id'] ?>"
                                            class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Return Car
                                        </a>

                                        <?php if ($isOverdue): ?>
                                            <a href="index.php?page=rentals&action=extend&id=<?= $rental['rental_id'] ?>"
                                                class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Extend Rental
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Past Rentals Tab -->
            <div id="past-tab" class="tab-content hidden">
                <?php if (empty($pastRentals)): ?>
                    <div class="text-center py-8 bg-white rounded-lg shadow">
                        <p class="text-gray-500">You don't have any past rentals.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <?php foreach ($pastRentals as $rental): ?>
                            <div
                                class="bg-white overflow-hidden shadow rounded-lg <?= $rental['status'] === 'completed' ? 'border-l-4 border-gray-300' : 'border-l-4 border-red-300' ?>">
                                <div class="relative">
                                    <img class="h-48 w-full object-cover"
                                        src="<?= $rental['image_url'] ?? 'assets/images/car.png' ?>"
                                        alt="<?= $rental['make'] ?> <?= $rental['model'] ?>">
                                    <div class="absolute top-0 right-0 mt-2 mr-2">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $rental['status'] === 'completed' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800' ?>">
                                            <?= ucfirst($rental['status']) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <h3 class="text-lg font-medium text-gray-900"><?= $rental['make'] ?>             <?= $rental['model'] ?>
                                        <?= $rental['year'] ?></h3>

                                    <div class="mt-2 flex items-center text-sm text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <?= date('M d, Y', strtotime($rental['start_date'])) ?> -
                                        <?= date('M d, Y', strtotime($rental['end_date'])) ?>
                                    </div>

                                    <?php if (!empty($rental['category_name'])): ?>
                                        <div class="mt-2 flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                            </svg>
                                            <?= htmlspecialchars($rental['category_name']) ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($rental['pickup_location'])): ?>
                                        <div class="mt-2 flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Pickup: <?= htmlspecialchars($rental['pickup_location']) ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="mt-4 flex justify-between">
                                        <div>
                                            <p class="text-sm text-gray-500">Total Cost</p>
                                            <p class="text-lg font-semibold text-gray-900">
                                                <?= formatCurrency($rental['total_cost']) ?></p>
                                            <?php if (!empty($rental['promo_code'])): ?>
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    Promo: <?= $rental['promo_code'] ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <?php
                                        $hasReview = $rental['hasReview'];
                                        $rating = $rental['rating'];


                                        ?>

                                        <?php if ($hasReview): ?>
                                            <div>
                                                <p class="text-sm text-gray-500">Your Rating</p>
                                                <div class="flex text-yellow-400">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <?php if ($i <= $rating): ?>
                                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path
                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        <?php else: ?>
                                                            <svg class="h-5 w-5 text-gray-300" xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 20 20" fill="currentColor">
                                                                <path
                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mt-5 flex space-x-2">
                                        <a href="index.php?page=rentals&action=view&id=<?= $rental['rental_id'] ?>"
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd"
                                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Details
                                        </a>

                                        <?php if ($rental['status'] === 'completed' && !$hasReview): ?>
                                            <a href="index.php?page=rentals&action=review&id=<?= $rental['rental_id'] ?>"
                                                class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                Write Review
                                            </a>
                                        <?php elseif ($hasReview): ?>
                                            <a href="index.php?page=rentals&action=review&id=<?= $rental['rental_id'] ?>"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path
                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                                Edit Review
                                            </a>
                                        <?php endif; ?>

                                        <a href="index.php?page=rentals&action=rent_again&car_id=<?= $rental['car_id'] ?>"
                                            class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Rent Again
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- All Rentals Tab -->
            <div id="all-tab" class="tab-content hidden">
                <?php if (empty($rentals)): ?>
                    <div class="text-center py-8 bg-white rounded-lg shadow">
                        <p class="text-gray-500">You don't have any rentals yet.</p>
                        <div class="mt-4">
                            <a href="index.php?page=cars" class="text-blue-600 hover:text-blue-800">Browse cars to rent</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-white shadow overflow-hidden sm:rounded-md">
                        <ul class="divide-y divide-gray-200">
                            <?php foreach ($rentals as $rental): ?>
                                <li>
                                    <a href="index.php?page=rentals&action=view&id=<?= $rental['rental_id'] ?>"
                                        class="block hover:bg-gray-50">
                                        <div class="flex items-center px-4 py-4 sm:px-6">
                                            <div class="min-w-0 flex-1 flex items-center">
                                                <div class="flex-shrink-0 h-16 w-16 bg-gray-100 rounded-md overflow-hidden">
                                                    <img src="<?= $rental['image_url'] ?? 'assets/images/car.png' ?>"
                                                        alt="<?= $rental['make'] ?> <?= $rental['model'] ?>"
                                                        class="h-full w-full object-cover">
                                                </div>
                                                <div class="min-w-0 flex-1 px-4">
                                                    <div>
                                                        <p class="text-sm font-medium text-blue-600 truncate">
                                                            <?= $rental['make'] ?>             <?= $rental['model'] ?>             <?= $rental['year'] ?>
                                                        </p>
                                                        <div class="mt-1 flex">
                                                            <p class="flex items-center text-sm text-gray-500">
                                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                    fill="currentColor">
                                                                    <path fill-rule="evenodd"
                                                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                                <?= date('M d, Y', strtotime($rental['start_date'])) ?> -
                                                                <?= date('M d, Y', strtotime($rental['end_date'])) ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ml-5 flex-shrink-0 flex flex-col items-end">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    <?php
                                                    switch ($rental['status']) {
                                                        case 'pending':
                                                            echo 'bg-yellow-100 text-yellow-800';
                                                            break;
                                                        case 'approved':
                                                            echo 'bg-blue-100 text-blue-800';
                                                            break;
                                                        case 'active':
                                                            echo 'bg-green-100 text-green-800';
                                                            break;
                                                        case 'completed':
                                                            echo 'bg-gray-100 text-gray-800';
                                                            break;
                                                        case 'cancelled':
                                                            echo 'bg-red-100 text-red-800';
                                                            break;
                                                        default:
                                                            echo 'bg-gray-100 text-gray-800';
                                                    }
                                                    ?>">
                                                    <?= ucfirst($rental['status']) ?>
                                                </span>
                                                <p class="mt-2 text-sm font-medium text-gray-900">
                                                    <?= formatCurrency($rental['total_cost']) ?></p>
                                            </div>
                                            <div class="ml-5">
                                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd"
                                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Review Modal -->
<div id="review-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Write a Review
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Share your experience with this rental. Your feedback helps us improve our service.
                            </p>
                        </div>
                    </div>
                </div>
                <form id="review-form" action="index.php?page=rentals&action=submit_review" method="POST" class="mt-5">
                    <input type="hidden" id="rental-id" name="rental_id" value="">
                    <input type="hidden" id="car-id" name="car_id" value="">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                        <div class="flex space-x-2">
                            <div class="rating-stars flex">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <button type="button" data-rating="<?= $i ?>"
                                        class="rating-star p-1 focus:outline-none">
                                        <svg class="h-8 w-8 text-gray-300 hover:text-yellow-400"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </button>
                                <?php endfor; ?>
                            </div>
                            <input type="hidden" id="rating" name="rating" value="0" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="feedback" class="block text-sm font-medium text-gray-700 mb-2">Your Review</label>
                        <textarea id="feedback" name="feedback" rows="4"
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                            placeholder="Share your experience with this rental..."></textarea>
                    </div>
                </form>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="submitReview()"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Submit Review
                </button>
                <button type="button" onclick="closeReviewModal()"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Tab functionality
    function showTab(tabId) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });

        // Show the selected tab content
        document.getElementById(tabId + '-tab').classList.remove('hidden');

        // Update tab button styles
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('text-blue-600', 'border-blue-600');
            button.classList.add('text-gray-500', 'border-transparent');
        });

        // Highlight the active tab button
        event.currentTarget.classList.remove('text-gray-500', 'border-transparent');
        event.currentTarget.classList.add('text-blue-600', 'border-blue-600');
    }

    // Review modal functionality
    function openReviewModal(rentalId, carId) {
        document.getElementById('rental-id').value = rentalId;
        document.getElementById('car-id').value = carId;
        document.getElementById('review-modal').classList.remove('hidden');
    }

    function closeReviewModal() {
        document.getElementById('review-modal').classList.add('hidden');
        // Reset form
        document.getElementById('review-form').reset();
        document.getElementById('rating').value = 0;
        document.querySelectorAll('.rating-star svg').forEach(star => {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        });
    }

    // Star rating functionality
    document.querySelectorAll('.rating-star').forEach(star => {
        star.addEventListener('click', function () {
            const rating = this.getAttribute('data-rating');
            document.getElementById('rating').value = rating;

            // Update star colors
            document.querySelectorAll('.rating-star svg').forEach((s, index) => {
                if (index < rating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });
        });
    });

    function submitReview() {
        const rating = document.getElementById('rating').value;
        if (rating === '0') {
            alert('Please select a rating before submitting.');
            return;
        }
        document.getElementById('review-form').submit();
    }

    // Initialize the first tab as active
    document.addEventListener('DOMContentLoaded', function () {
        // Check if there's a hash in the URL
        const hash = window.location.hash.substring(1);
        if (hash && ['upcoming', 'active', 'past', 'all'].includes(hash)) {
            // Find the button for this tab and click it
            const tabButton = document.querySelector(`button[onclick="showTab('${hash}')"]`);
            if (tabButton) {
                tabButton.click();
            } else {
                // Default to upcoming tab
                document.querySelector('.tab-button').click();
            }
        } else {
            // Default to upcoming tab if no hash or invalid hash
            document.querySelector('.tab-button').click();
        }

        // Update URL hash when changing tabs
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function () {
                const tabId = this.getAttribute('onclick').match(/'([^']+)'/)[1];
                window.location.hash = tabId;
            });
        });
    });
</script>

<?php require 'views/layouts/footer.php'; ?>
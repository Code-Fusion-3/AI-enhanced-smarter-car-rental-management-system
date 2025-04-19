
<div class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Admin Sidebar -->
        <?php require 'views/admin/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 p-8">
            <h1 class="text-2xl font-semibold text-gray-900 mb-6">Admin Dashboard</h1>
            
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Users -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-gray-600 text-sm font-medium">Total Users</h2>
                            <p class="text-2xl font-semibold text-gray-800"><?= $stats['totalUsers'] ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Total Cars -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-gray-600 text-sm font-medium">Total Cars</h2>
                            <p class="text-2xl font-semibold text-gray-800"><?= $stats['totalCars'] ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Active Rentals -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-gray-600 text-sm font-medium">Active Rentals</h2>
                            <p class="text-2xl font-semibold text-gray-800"><?= $stats['activeRentals'] ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Total Revenue -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-gray-600 text-sm font-medium">Total Revenue</h2>
                            <p class="text-2xl font-semibold text-gray-800">$<?= number_format($stats['totalRevenue'], 2) ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Fleet Status and Revenue Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Fleet Status -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Fleet Status</h2>
                    <div class="flex items-center justify-center h-64">
                        <canvas id="fleetStatusChart"></canvas>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mt-4">
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Available</p>
                            <p class="text-lg font-semibold text-green-600"><?= $stats['availableCars'] ?></p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Rented</p>
                            <p class="text-lg font-semibold text-blue-600"><?= $stats['rentedCars'] ?></p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Maintenance</p>
                            <p class="text-lg font-semibold text-yellow-600"><?= $stats['maintenanceCars'] ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Monthly Revenue -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Monthly Revenue</h2>
                    <div class="h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mt-4">
                        <div class="text-center">
                            <p class="text-sm text-gray-600">This Week</p>
                            <p class="text-lg font-semibold text-indigo-600">$<?= number_format($stats['weeklyRevenue'], 2) ?></p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600">This Month</p>
                            <p class="text-lg font-semibold text-indigo-600">$<?= number_format($stats['monthlyRevenue'], 2) ?></p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Total</p>
                            <p class="text-lg font-semibold text-indigo-600">$<?= number_format($stats['totalRevenue'], 2) ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity and Pending Approvals -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Recent Activity</h2>
                    </div>
                    <div class="p-6">
                        <ul class="divide-y divide-gray-200">
                            <?php if (empty($recentActivities)): ?>
                                <li class="py-4 text-center text-gray-500">No recent activities</li>
                            <?php else: ?>
                                <?php foreach ($recentActivities as $activity): ?>
                                    <li class="py-4">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-600">
                                                    <?= substr($activity['username'] ?? 'U', 0, 1) ?>
                                                </span>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900">
                                                    <?= htmlspecialchars($activity['username'] ?? 'Unknown User') ?>
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    <?= htmlspecialchars($activity['action']) ?>
                                                    <?php if (!empty($activity['details'])): ?>
                                                        - <?= htmlspecialchars($activity['details']) ?>
                                                    <?php endif; ?>
                                                </p>
                                                <p class="mt-1 text-xs text-gray-400">
                                                    <?= date('M j, Y g:i A', strtotime($activity['created_at'])) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                        <div class="mt-4 text-center">
                            <a href="index.php?page=admin&action=activity" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                View all activity
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Pending Approvals -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Pending Approvals</h2>
                    </div>
                    <div class="p-6">
                        <ul class="divide-y divide-gray-200">
                            <?php if (empty($pendingRentals)): ?>
                                <li class="py-4 text-center text-gray-500">No pending approvals</li>
                            <?php else: ?>
                                <?php foreach ($pendingRentals as $rental): ?>
                                    <li class="py-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    <?= htmlspecialchars($rental['full_name']) ?> (<?= htmlspecialchars($rental['username']) ?>)
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    <?= htmlspecialchars($rental['make'] . ' ' . $rental['model'] . ' ' . $rental['year']) ?>
                                                </p>
                                                <p class="text-xs text-gray-400">
                                                    <?= date('M j, Y', strtotime($rental['start_date'])) ?> - 
                                                    <?= date('M j, Y', strtotime($rental['end_date'])) ?>
                                                </p>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="index.php?page=admin&action=rentals&subaction=approve&id=<?= $rental['rental_id'] ?>" 
                                                   class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    Approve
                                                </a>
                                                <a href="index.php?page=admin&action=rentals&subaction=reject&id=<?= $rental['rental_id'] ?>" 
                                                   class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Reject
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                        <div class="mt-4 text-center">
                            <a href="index.php?page=admin&action=rentals&filter=pending" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                View all pending rentals
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Top Performing Cars and Recent Rentals -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top Performing Cars -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Top Performing Cars</h2>
                    </div>
                    <div class="p-6">
                        <ul class="divide-y divide-gray-200">
                            <?php if (empty($topCars)): ?>
                                <li class="py-4 text-center text-gray-500">No data available</li>
                            <?php else: ?>
                                <?php foreach ($topCars as $car): ?>
                                    <li class="py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-16 w-16">
                                                <img class="h-16 w-16 rounded-md object-cover" 
                                                     src="<?= $car['image_url'] ?? 'assets/images/default-car.jpg' ?>" 
                                                     alt="<?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?>">
                                            </div>
                                            <div class="ml-4 flex-1">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        <?= htmlspecialchars($car['make'] . ' ' . $car['model'] . ' ' . $car['year']) ?>
                                                    </p>
                                                    <p class="text-sm font-semibold text-green-600">
                                                        $<?= number_format($car['revenue'] ?? 0, 2) ?>
                                                    </p>
                                                </div>
                                                <div class="flex items-center mt-1">
                                                    <div class="flex items-center">
                                                        <?php 
                                                        $rating = round($car['avg_rating'] ?? 0);
                                                        for ($i = 1; $i <= 5; $i++): 
                                                            if ($i <= $rating): 
                                                        ?>
                                                            <svg class="h-4 w-4 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        <?php else: ?>
                                                            <svg class="h-4 w-4 text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        <?php endif; endfor; ?>
                                                    </div>
                                                    <span class="text-xs text-gray-500 ml-2">
                                                        <?= $car['rental_count'] ?> rentals
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                        <div class="mt-4 text-center">
                            <a href="index.php?page=admin&action=cars&sort=performance" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                View all cars
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Rentals -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Recent Rentals</h2>
                    </div>
                    <div class="p-6">
                        <ul class="divide-y divide-gray-200">
                            <?php if (empty($recentRentals)): ?>
                                <li class="py-4 text-center text-gray-500">No recent rentals</li>
                            <?php else: ?>
                                <?php foreach ($recentRentals as $rental): ?>
                                    <li class="py-4">
                                        <div class="flex items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        <?= htmlspecialchars($rental['full_name']) ?>
                                                    </p>
                                                    <p class="text-xs font-medium 
                                                        <?php 
                                                        switch($rental['status']) {
                                                            case 'pending': echo 'text-yellow-600'; break;
                                                            case 'approved': echo 'text-blue-600'; break;
                                                            case 'active': echo 'text-green-600'; break;
                                                            case 'completed': echo 'text-gray-600'; break;
                                                            case 'cancelled': echo 'text-red-600'; break;
                                                            default: echo 'text-gray-600';
                                                        }
                                                        ?>">
                                                        <?= ucfirst($rental['status']) ?>
                                                    </p>
                                                </div>
                                                <p class="text-sm text-gray-500">
                                                    <?= htmlspecialchars($rental['make'] . ' ' . $rental['model'] . ' ' . $rental['year']) ?>
                                                </p>
                                                <div class="mt-1 flex items-center justify-between">
                                                    <p class="text-xs text-gray-400">
                                                        <?= date('M j, Y', strtotime($rental['start_date'])) ?> - 
                                                        <?= date('M j, Y', strtotime($rental['end_date'])) ?>
                                                    </p>
                                                    <p class="text-xs font-semibold text-gray-900">
                                                        $<?= number_format($rental['total_cost'], 2) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                        <div class="mt-4 text-center">
                            <a href="index.php?page=admin&action=rentals" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                View all rentals
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Fleet Status Chart
    const fleetStatusCtx = document.getElementById('fleetStatusChart').getContext('2d');
    const fleetStatusChart = new Chart(fleetStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Available', 'Rented', 'Maintenance'],
            datasets: [{
                data: [
                    <?= $stats['availableCars'] ?>, 
                    <?= $stats['rentedCars'] ?>, 
                    <?= $stats['maintenanceCars'] ?>
                ],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(234, 179, 8, 0.8)'
                ],
                borderColor: [
                    'rgba(34, 197, 94, 1)',
                    'rgba(59, 130, 246, 1)',
                    'rgba(234, 179, 8, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($revenueMetrics['months'] ?? []) ?>,
            datasets: [{
                label: 'Monthly Revenue',
                data: <?= json_encode($revenueMetrics['revenues'] ?? []) ?>,
                backgroundColor: 'rgba(79, 70, 229, 0.8)',
                borderColor: 'rgba(79, 70, 229, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>


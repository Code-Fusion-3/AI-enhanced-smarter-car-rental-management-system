
<div class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Admin Sidebar -->
        <?php require 'views/admin/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Car Details</h1>
                    <p class="text-gray-600 mt-1">Detailed information about this vehicle</p>
                </div>
                
                <div class="flex space-x-3">
                    <a href="index.php?page=admin&action=cars" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back to Cars
                    </a>
                    <a href="index.php?page=admin&action=cars&subaction=edit&id=<?= $car['car_id'] ?>" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Edit Car
                    </a>
                </div>
            </div>
            
            <!-- Car Details -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3">
                    <!-- Car Image -->
                    <div class="md:col-span-1 p-6 flex items-center justify-center bg-gray-50">
                        <?php if (!empty($car['image_url'])): ?>
                            <img src="<?= $car['image_url'] ?>" alt="<?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?>" class="max-w-full max-h-80 object-contain">
                        <?php else: ?>
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Car Info -->
                    <div class="md:col-span-2 p-6">
                        <div class="flex justify-between items-start">
                            <h2 class="text-2xl font-bold text-gray-900">
                                <?= htmlspecialchars($car['make'] . ' ' . $car['model'] . ' (' . $car['year'] . ')') ?>
                            </h2>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                <?php 
                                    if ($car['status'] === 'available') echo 'bg-green-100 text-green-800';
                                    elseif ($car['status'] === 'rented') echo 'bg-blue-100 text-blue-800';
                                    else echo 'bg-red-100 text-red-800';
                                ?>">
                                <?= ucfirst($car['status']) ?>
                            </span>
                        </div>
                        
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-3">Basic Information</h3>
                                <dl class="space-y-3">
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Registration Number</dt>
                                        <dd class="text-sm text-gray-900"><?= htmlspecialchars($car['registration_number']) ?></dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                                        <dd class="text-sm text-gray-900"><?= $category ? htmlspecialchars($category['name']) : 'Uncategorized' ?></dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Fuel Type</dt>
                                        <dd class="text-sm text-gray-900"><?= !empty($car['fuel_type']) ? ucfirst($car['fuel_type']) : 'N/A' ?></dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Transmission</dt>
                                        <dd class="text-sm text-gray-900"><?= !empty($car['transmission']) ? ucfirst($car['transmission']) : 'N/A' ?></dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Seats</dt>
                                        <dd class="text-sm text-gray-900"><?= !empty($car['seats']) ? $car['seats'] : 'N/A' ?></dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Mileage</dt>
                                        <dd class="text-sm text-gray-900"><?= !empty($car['mileage']) ? number_format($car['mileage']) . ' km' : 'N/A' ?></dd>
                                    </div>
                                </dl>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-3">Pricing Information</h3>
                                <dl class="space-y-3">
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Daily Rate</dt>
                                        <dd class="text-sm text-gray-900 font-semibold">$<?= number_format($car['daily_rate'], 2) ?></dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Base Rate</dt>
                                        <dd class="text-sm text-gray-900">$<?= number_format($car['base_rate'], 2) ?></dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Weekend Rate</dt>
                                        <dd class="text-sm text-gray-900"><?= !empty($car['weekend_rate']) ? '$' . number_format($car['weekend_rate'], 2) : 'N/A' ?></dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Weekly Rate</dt>
                                        <dd class="text-sm text-gray-900"><?= !empty($car['weekly_rate']) ? '$' . number_format($car['weekly_rate'], 2) : 'N/A' ?></dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Monthly Rate</dt>
                                        <dd class="text-sm text-gray-900"><?= !empty($car['monthly_rate']) ? '$' . number_format($car['monthly_rate'], 2) : 'N/A' ?></dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                        
                        <?php if (!empty($car['features'])): ?>
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-3">Features</h3>
                            <div class="flex flex-wrap gap-2">
                                <?php 
                                $features = explode(',', $car['features']);
                                foreach ($features as $feature): 
                                    $feature = trim($feature);
                                    if (!empty($feature)):
                                ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <?= htmlspecialchars($feature) ?>
                                </span>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Tabs for Rental History, Maintenance Records, etc. -->
            <div class="mb-6">
                <div class="sm:hidden">
                    <label for="tabs" class="sr-only">Select a tab</label>
                    <select id="tabs" name="tabs" onchange="showTab(this.value)" class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="rental-history">Rental History</option>
                        <option value="maintenance">Maintenance Records</option>
                        <option value="analytics">Analytics</option>
                    </select>
                </div>
                <div class="hidden sm:block">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <a href="#" onclick="showTab('rental-history'); return false;" class="tab-link active border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="rental-history">
                                Rental History
                            </a>
                            <a href="#" onclick="showTab('maintenance'); return false;" class="tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="maintenance">
                                Maintenance Records
                            </a>
                            <a href="#" onclick="showTab('analytics'); return false;" class="tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="analytics">
                                Analytics
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
            
            <!-- Tab Content -->
            <div>
                <!-- Rental History Tab -->
                <div id="rental-history" class="tab-content">
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Rental History</h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">Recent rentals of this vehicle</p>
                            </div>
                            <a href="index.php?page=admin&action=rentals&car_id=<?= $car['car_id'] ?>" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200">
                                View All Rentals
                            </a>
                        </div>
                        
                        <?php if (empty($rentalHistory)): ?>
                        <div class="px-4 py-5 sm:p-6 text-center">
                            <p class="text-gray-500">No rental history available for this vehicle.</p>
                        </div>
                        <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Rental ID
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Customer
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Dates
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Rental ID
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Customer
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Dates
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Cost
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($rentalHistory as $rental): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #<?= $rental['rental_id'] ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">
                                                    <?= strtoupper(substr($rental['username'], 0, 1)) ?>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($rental['full_name']) ?></div>
                                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($rental['username']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="text-sm text-gray-900"><?= date('M d, Y', strtotime($rental['start_date'])) ?></div>
                                            <div class="text-sm text-gray-500">to <?= date('M d, Y', strtotime($rental['end_date'])) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
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
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            $<?= number_format($rental['total_cost'], 2) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="index.php?page=admin&action=rentals&subaction=view&id=<?= $rental['rental_id'] ?>" class="text-blue-600 hover:text-blue-900">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Maintenance Records Tab -->
                <div id="maintenance" class="tab-content hidden">
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Maintenance Records</h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">Service history and upcoming maintenance</p>
                            </div>
                            <a href="index.php?page=admin&action=maintenance&car_id=<?= $car['car_id'] ?>" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200">
                                Add Maintenance Record
                            </a>
                        </div>
                        
                        <?php if (empty($maintenanceRecords)): ?>
                        <div class="px-4 py-5 sm:p-6 text-center">
                            <p class="text-gray-500">No maintenance records available for this vehicle.</p>
                        </div>
                        <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Type
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Description
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Cost
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($maintenanceRecords as $record): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php 
                                                    switch ($record['maintenance_type']) {
                                                        case 'routine':
                                                            echo 'bg-green-100 text-green-800';
                                                            break;
                                                        case 'repair':
                                                            echo 'bg-red-100 text-red-800';
                                                            break;
                                                        case 'inspection':
                                                            echo 'bg-blue-100 text-blue-800';
                                                            break;
                                                        default:
                                                            echo 'bg-gray-100 text-gray-800';
                                                    }
                                                ?>">
                                                <?= ucfirst($record['maintenance_type']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <?= htmlspecialchars($record['description']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="text-sm text-gray-900"><?= date('M d, Y', strtotime($record['start_date'])) ?></div>
                                            <?php if (!empty($record['end_date'])): ?>
                                            <div class="text-sm text-gray-500">to <?= date('M d, Y', strtotime($record['end_date'])) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php 
                                                    switch ($record['status']) {
                                                        case 'scheduled':
                                                            echo 'bg-yellow-100 text-yellow-800';
                                                            break;
                                                        case 'in_progress':
                                                            echo 'bg-blue-100 text-blue-800';
                                                            break;
                                                        case 'completed':
                                                            echo 'bg-green-100 text-green-800';
                                                            break;
                                                        default:
                                                            echo 'bg-gray-100 text-gray-800';
                                                    }
                                                ?>">
                                                <?= str_replace('_', ' ', ucfirst($record['status'])) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= !empty($record['cost']) ? '$' . number_format($record['cost'], 2) : 'N/A' ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Analytics Tab -->
                <div id="analytics" class="tab-content hidden">
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Performance Analytics</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Rental statistics and performance metrics</p>
                        </div>
                        
                        <div class="px-4 py-5 sm:p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Utilization Rate -->
                                <div class="bg-white p-6 rounded-lg border border-gray-200">
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">Utilization Rate</h4>
                                    <div class="flex items-baseline">
                                        <?php
                                        // Calculate utilization rate (example calculation)
                                        $totalDays = 90; // Last 3 months
                                        $rentedDays = 0;
                                        
                                        // Count days rented in the last 3 months
                                        $threeMonthsAgo = date('Y-m-d', strtotime('-3 months'));
                                        foreach ($rentalHistory as $rental) {
                                            if ($rental['status'] != 'cancelled' && $rental['end_date'] >= $threeMonthsAgo) {
                                                $start = max(strtotime($rental['start_date']), strtotime($threeMonthsAgo));
                                                $end = min(strtotime($rental['end_date']), time());
                                                $days = max(0, floor(($end - $start) / (60 * 60 * 24)) + 1);
                                                $rentedDays += $days;
                                            }
                                        }
                                        
                                        $utilizationRate = min(100, round(($rentedDays / $totalDays) * 100));
                                        ?>
                                        <span class="text-3xl font-semibold text-blue-600"><?= $utilizationRate ?>%</span>
                                        <span class="ml-2 text-sm text-gray-500">last 90 days</span>
                                    </div>
                                    <div class="mt-3 w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?= $utilizationRate ?>%"></div>
                                    </div>
                                </div>
                                
                                <!-- Revenue Generated -->
                                <div class="bg-white p-6 rounded-lg border border-gray-200">
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">Revenue Generated</h4>
                                    <div class="flex items-baseline">
                                        <?php
                                        // Calculate total revenue (example calculation)
                                        $totalRevenue = 0;
                                        foreach ($rentalHistory as $rental) {
                                            if ($rental['status'] != 'cancelled') {
                                                $totalRevenue += $rental['total_cost'];
                                            }
                                        }
                                        ?>
                                        <span class="text-3xl font-semibold text-green-600">$<?= number_format($totalRevenue, 2) ?></span>
                                        <span class="ml-2 text-sm text-gray-500">total</span>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">From <?= count($rentalHistory) ?> completed rentals</p>
                                </div>
                                                                <!-- Average Rental Duration -->
                                                                <div class="bg-white p-6 rounded-lg border border-gray-200">
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">Average Rental Duration</h4>
                                    <div class="flex items-baseline">
                                        <?php
                                        // Calculate average rental duration (example calculation)
                                        $totalDuration = 0;
                                        $completedRentals = 0;
                                        
                                        foreach ($rentalHistory as $rental) {
                                            if ($rental['status'] != 'cancelled') {
                                                $start = strtotime($rental['start_date']);
                                                $end = strtotime($rental['end_date']);
                                                $days = floor(($end - $start) / (60 * 60 * 24)) + 1;
                                                $totalDuration += $days;
                                                $completedRentals++;
                                            }
                                        }
                                        
                                        $avgDuration = $completedRentals > 0 ? round($totalDuration / $completedRentals, 1) : 0;
                                        ?>
                                        <span class="text-3xl font-semibold text-purple-600"><?= $avgDuration ?></span>
                                        <span class="ml-2 text-sm text-gray-500">days per rental</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Monthly Performance Chart -->
                            <div class="mt-8">
    <h4 class="text-lg font-medium text-gray-900 mb-4">Monthly Performance</h4>
    <div class="h-64 bg-gray-50 rounded-lg border border-gray-200 p-4">
        <canvas id="monthlyPerformanceChart"></canvas>
    </div>
    <p class="mt-2 text-sm text-gray-500 text-center">Monthly rental revenue and utilization for the past 12 months</p>
</div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Fetch monthly performance data for this car
fetch(`index.php?page=admin&action=cars&subaction=performance&id=<?= $car['car_id'] ?>&format=json`)
    .then(response => {
        // Clone the response so we can inspect it
        const responseClone = response.clone();
        
        // Check if the response is ok
        if (!response.ok) {
            responseClone.text().then(text => {
                console.error('Response not OK. Raw response:', text);
            });
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        
        // Try to parse as JSON, but also log the raw text if it fails
        return response.text().then(text => {
            // console.log('Raw response:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                throw new Error('Invalid JSON response');
            }
        });
    })
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        renderMonthlyPerformanceChart(data);
    })
    .catch(error => {
        // console.error('Error fetching performance data:', error);
        document.getElementById('monthlyPerformanceChart').parentNode.innerHTML = 
            `<div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-gray-500">Failed to load performance data</p>
                </div>
            </div>`;
    });

});

function renderMonthlyPerformanceChart(data) {
    const ctx = document.getElementById('monthlyPerformanceChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Revenue ($)',
                    data: data.revenue,
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1,
                    yAxisID: 'y'
                },
                {
                    label: 'Utilization (%)',
                    data: data.utilization,
                    type: 'line',
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.4,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Revenue ($)'
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    max: 100,
                    title: {
                        display: true,
                        text: 'Utilization (%)'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
}
function showTab(tabId) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Show the selected tab content
    document.getElementById(tabId).classList.remove('hidden');
    
    // Update active tab styling
    document.querySelectorAll('.tab-link').forEach(link => {
        link.classList.remove('active', 'border-blue-500', 'text-blue-600');
        link.classList.add('border-transparent', 'text-gray-500');
    });
    
    document.querySelector(`.tab-link[data-tab="${tabId}"]`).classList.add('active', 'border-blue-500', 'text-blue-600');
    document.querySelector(`.tab-link[data-tab="${tabId}"]`).classList.remove('border-transparent', 'text-gray-500');
}
</script>


                               
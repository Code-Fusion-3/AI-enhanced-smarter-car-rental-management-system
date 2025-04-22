<?php require 'views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row">
            <!-- Sidebar -->
            <div class="w-full md:w-1/4 mb-6 md:mb-0 md:pr-6">
                <?php require 'views/profile/sidebar.php'; ?>
            </div>
            
            <!-- Main Content -->
            <div class="w-full md:w-3/4">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline"><?= $_SESSION['success'] ?></span>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline"><?= $_SESSION['error'] ?></span>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Profile Overview -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="md:mr-6 mb-4 md:mb-0">
                            <?php if (!empty($userData['profile_image'])): ?>
                                <img src="<?= $userData['profile_image'] ?>" alt="Profile" class="w-32 h-32 rounded-full object-cover border-4 border-blue-500">
                            <?php else: ?>
                                <div class="w-32 h-32 rounded-full bg-blue-500 flex items-center justify-center text-white text-4xl font-bold">
                                    <?= strtoupper(substr($userData['full_name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 text-center md:text-left">
                            <h1 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($userData['full_name']) ?></h1>
                            <p class="text-gray-600">@<?= htmlspecialchars($userData['username']) ?></p>
                            <p class="text-gray-600 mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                                <?= htmlspecialchars($userData['email']) ?>
                            </p>
                            <?php if (!empty($userData['phone'])): ?>
                                <p class="text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                    </svg>
                                    <?= htmlspecialchars($userData['phone']) ?>
                                </p>
                            <?php endif; ?>
                            <div class="mt-4">
                                <a href="index.php?page=profile&action=edit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    Edit Profile
                                </a>
                                <a href="index.php?page=profile&action=password" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 ml-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                    Change Password
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Information -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Personal Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Full Name</h3>
                            <p class="mt-1 text-gray-900"><?= htmlspecialchars($userData['full_name']) ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Email</h3>
                            <p class="mt-1 text-gray-900"><?= htmlspecialchars($userData['email']) ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Phone</h3>
                            <p class="mt-1 text-gray-900"><?= !empty($userData['phone']) ? htmlspecialchars($userData['phone']) : 'Not provided' ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Driver's License</h3>
                            <p class="mt-1 text-gray-900"><?= !empty($userData['driver_license']) ? htmlspecialchars($userData['driver_license']) : 'Not provided' ?></p>
                        </div>
                        <div class="md:col-span-2">
                            <h3 class="text-sm font-medium text-gray-500">Address</h3>
                            <p class="mt-1 text-gray-900"><?= !empty($userData['address']) ? htmlspecialchars($userData['address']) : 'Not provided' ?></p>
                        </div>
                    </div>
                </div>
                <?php if(!isAdmin()): ?>
                <!-- Upcoming Rentals -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Upcoming Rentals</h2>
                    
                    <?php if (empty($upcomingRentals)): ?>
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-2 text-gray-500">You don't have any upcoming rentals.</p>
                            <a href="index.php?page=cars" class="mt-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Browse Cars
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Car</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($upcomingRentals as $rental): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full object-cover" src="<?= !empty($rental['image_url']) ? $rental['image_url'] : 'assets/images/default-car.jpg' ?>" alt="<?= $rental['make'] . ' ' . $rental['model'] ?>">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <?= htmlspecialchars($rental['make'] . ' ' . $rental['model']) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    <?= date('M d, Y', strtotime($rental['start_date'])) ?> - <?= date('M d, Y', strtotime($rental['end_date'])) ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php
                                                $statusClass = '';
                                                switch ($rental['status']) {
                                                    case 'pending':
                                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                                        break;
                                                    case 'approved':
                                                        $statusClass = 'bg-green-100 text-green-800';
                                                        break;
                                                    case 'active':
                                                        $statusClass = 'bg-blue-100 text-blue-800';
                                                        break;
                                                    case 'completed':
                                                        $statusClass = 'bg-gray-100 text-gray-800';
                                                        break;
                                                    case 'cancelled':
                                                        $statusClass = 'bg-red-100 text-red-800';
                                                        break;
                                                }
                                                ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusClass ?>">
                                                    <?= ucfirst($rental['status']) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                $<?= number_format($rental['total_cost'], 2) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="index.php?page=rentals&action=view&id=<?= $rental['rental_id'] ?>" class="text-blue-600 hover:text-blue-900">View</a>
                                                <?php if ($rental['status'] === 'pending'): ?>
                                                    <a href="index.php?page=rentals&action=cancel&id=<?= $rental['rental_id'] ?>" class="ml-3 text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to cancel this rental?')">Cancel</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-right">
                            <a href="index.php?page=rentals" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                View All Rentals →
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Recent Rentals -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Rental History</h2>
                    
                    <?php if (empty($recentRentals)): ?>
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-2 text-gray-500">You haven't rented any cars yet.</p>
                            <a href="index.php?page=cars" class="mt-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Browse Cars
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Car</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($recentRentals as $rental): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full object-cover" src="<?= !empty($rental['image_url']) ? $rental['image_url'] : 'assets/images/default-car.jpg' ?>" alt="<?= $rental['make'] . ' ' . $rental['model'] ?>">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <?= htmlspecialchars($rental['make'] . ' ' . $rental['model']) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    <?= date('M d, Y', strtotime($rental['start_date'])) ?> - <?= date('M d, Y', strtotime($rental['end_date'])) ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php
                                                $statusClass = '';
                                                switch ($rental['status']) {
                                                    case 'pending':
                                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                                        break;
                                                    case 'approved':
                                                        $statusClass = 'bg-green-100 text-green-800';
                                                        break;
                                                    case 'active':
                                                        $statusClass = 'bg-blue-100 text-blue-800';
                                                        break;
                                                    case 'completed':
                                                        $statusClass = 'bg-gray-100 text-gray-800';
                                                        break;
                                                        case 'cancelled':
                                                            $statusClass = 'bg-red-100 text-red-800';
                                                            break;
                                                    }
                                                    ?>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusClass ?>">
                                                        <?= ucfirst($rental['status']) ?>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    $<?= number_format($rental['total_cost'], 2) ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="index.php?page=rentals&action=view&id=<?= $rental['rental_id'] ?>" class="text-blue-600 hover:text-blue-900">View</a>
                                                    <?php if ($rental['status'] === 'completed'): ?>
                                                        <a href="index.php?page=rentals&action=review&id=<?= $rental['rental_id'] ?>" class="ml-3 text-green-600 hover:text-green-900">Review</a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 text-right">
                                <a href="index.php?page=rentals" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                    View All Rentals →
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
              <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    
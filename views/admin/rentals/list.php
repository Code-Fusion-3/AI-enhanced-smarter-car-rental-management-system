<div class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Admin Sidebar -->
        <?php require 'views/admin/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 p-8">
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manage Rentals</h1>
        <div>
            <a href="index.php?page=admin&action=dashboard" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm">
                Back to Dashboard
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
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

    <!-- Filter and Search -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="index.php" method="GET" class="space-y-4">
            <input type="hidden" name="page" value="admin">
            <input type="hidden" name="action" value="rentals">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" id="search" name="search" value="<?= htmlspecialchars($search ?? '') ?>" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           placeholder="User, car make/model...">
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="pending" <?= isset($status) && $status === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= isset($status) && $status === 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="active" <?= isset($status) && $status === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="completed" <?= isset($status) && $status === 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= isset($status) && $status === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input type="date" id="date_from" name="date_from" value="<?= htmlspecialchars($dateFrom ?? '') ?>" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input type="date" id="date_to" name="date_to" value="<?= htmlspecialchars($dateTo ?? '') ?>" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <a href="index.php?page=admin&action=rentals" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm">
                    Reset Filters
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Status Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <a href="index.php?page=admin&action=rentals" 
                   class="<?= empty($status) ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    All Rentals
                    <span class="ml-2 py-0.5 px-2.5 text-xs font-medium rounded-full <?= empty($status) ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-900' ?>">
                        <?= $totalCount ?? 0 ?>
                    </span>
                </a>
                
                <a href="index.php?page=admin&action=rentals&status=pending" 
                   class="<?= isset($status) && $status === 'pending' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Pending
                    <span class="ml-2 py-0.5 px-2.5 text-xs font-medium rounded-full <?= isset($status) && $status === 'pending' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-900' ?>">
                        <?= $statusCounts['pending'] ?? 0 ?>
                    </span>
                </a>
                
                <a href="index.php?page=admin&action=rentals&status=approved" 
                   class="<?= isset($status) && $status === 'approved' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Approved
                    <span class="ml-2 py-0.5 px-2.5 text-xs font-medium rounded-full <?= isset($status) && $status === 'approved' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-900' ?>">
                        <?= $statusCounts['approved'] ?? 0 ?>
                    </span>
                </a>
                
                <a href="index.php?page=admin&action=rentals&status=active" 
                   class="<?= isset($status) && $status === 'active' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Active
                    <span class="ml-2 py-0.5 px-2.5 text-xs font-medium rounded-full <?= isset($status) && $status === 'active' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-900' ?>">
                        <?= $statusCounts['active'] ?? 0 ?>
                    </span>
                </a>
                
                <a href="index.php?page=admin&action=rentals&status=completed" 
                   class="<?= isset($status) && $status === 'completed' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Completed
                    <span class="ml-2 py-0.5 px-2.5 text-xs font-medium rounded-full <?= isset($status) && $status === 'completed' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-900' ?>">
                        <?= $statusCounts['completed'] ?? 0 ?>
                    </span>
                </a>
                
                <a href="index.php?page=admin&action=rentals&status=cancelled" 
                   class="<?= isset($status) && $status === 'cancelled' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Cancelled
                    <span class="ml-2 py-0.5 px-2.5 text-xs font-medium rounded-full <?= isset($status) && $status === 'cancelled' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-900' ?>">
                        <?= $statusCounts['cancelled'] ?? 0 ?>
                    </span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Rentals Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <?php if (empty($rentals)): ?>
            <div class="p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <p class="text-gray-500 text-lg">No rentals found matching your criteria.</p>
                <a href="index.php?page=admin&action=rentals" class="mt-4 inline-block text-blue-600 hover:text-blue-800">
                    View all rentals
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Car
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dates
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Cost
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($rentals as $rental): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= $rental['rental_id'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-medium"><?= strtoupper(substr($rental['username'], 0, 2)) ?></span>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($rental['username']) ?></div>
                                            <div class="text-xs text-gray-500"><?= htmlspecialchars($rental['full_name']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                <div class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($rental['make'] . ' ' . $rental['model']) ?>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <?= htmlspecialchars($rental['registration_number']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="text-sm">
                                        <span class="font-medium">From:</span> <?= date('M d, Y', strtotime($rental['start_date'])) ?>
                                    </div>
                                    <div class="text-sm">
                                        <span class="font-medium">To:</span> <?= date('M d, Y', strtotime($rental['end_date'])) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="text-sm font-medium text-gray-900">
                                        $<?= number_format($rental['total_cost'], 2) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-blue-100 text-blue-800',
                                        'active' => 'bg-green-100 text-green-800',
                                        'completed' => 'bg-gray-100 text-gray-800',
                                        'cancelled' => 'bg-red-100 text-red-800'
                                    ];
                                    $statusClass = $statusClasses[$rental['status']] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusClass ?>">
                                        <?= ucfirst($rental['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('M d, Y', strtotime($rental['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="index.php?page=admin&action=rentals&subaction=view&id=<?= $rental['rental_id'] ?>" 
                                           class="text-blue-600 hover:text-blue-900" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                       
                                        <?php if ($rental['status'] === 'pending'): ?>
                                            <a href="index.php?page=admin&action=rentals&subaction=approve&id=<?= $rental['rental_id'] ?>" 
                                               class="text-green-600 hover:text-green-900" 
                                               onclick="return confirm('Are you sure you want to approve this rental?')"
                                               title="Approve">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($rental['status'] === 'approved' || $rental['status'] === 'active'): ?>
                                            <a href="index.php?page=admin&action=rentals&subaction=complete&id=<?= $rental['rental_id'] ?>" 
                                               class="text-purple-600 hover:text-purple-900" 
                                               onclick="return confirm('Are you sure you want to mark this rental as completed?')"
                                               title="Complete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($rental['status'] !== 'completed' && $rental['status'] !== 'cancelled'): ?>
                                            <a href="index.php?page=admin&action=rentals&subaction=cancel&id=<?= $rental['rental_id'] ?>" 
                                               class="text-yellow-600 hover:text-yellow-900" 
                                               onclick="return confirm('Are you sure you want to cancel this rental?')"
                                               title="Cancel">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                        
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if (isset($totalPages) && $totalPages > 1): ?>
        <div class="mt-6 flex justify-center">
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <?php 
                $currentPage = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
                $queryParams = $_GET;
                
                // Previous page link
                if ($currentPage > 1) {
                    $queryParams['page_num'] = $currentPage - 1;
                    $prevPageUrl = 'index.php?' . http_build_query($queryParams);
                ?>
                <a href="<?= $prevPageUrl ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <span class="sr-only">Previous</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
                <?php } else { ?>
                <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                    <span class="sr-only">Previous</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
                <?php } ?>
                
                <!-- Page numbers -->
                <?php 
                $startPage = max(1, $currentPage - 2);
                $endPage = min($totalPages, $startPage + 4);
                
                for ($i = $startPage; $i <= $endPage; $i++) {
                    $queryParams['page_num'] = $i;
                    $isActive = $i === $currentPage;
                    $classes = $isActive 
                        ? 'relative inline-flex items-center px-4 py-2 border border-blue-500 bg-blue-50 text-sm font-medium text-blue-600'
                        : 'relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50';
                        
                    echo '<a href="index.php?' . http_build_query($queryParams) . '" class="' . $classes . '">' . $i . '</a>';
                }
                ?>
                
                <!-- Next page link -->
                <?php 
                if ($currentPage < $totalPages) {
                    $queryParams['page_num'] = $currentPage + 1;
                    $nextPageUrl = 'index.php?' . http_build_query($queryParams);
                ?>
                <a href="<?= $nextPageUrl ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <span class="sr-only">Next</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
                <?php } else { ?>
                <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                    <span class="sr-only">Next</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
                <?php } ?>
            </nav>
        </div>
    <?php endif; ?>

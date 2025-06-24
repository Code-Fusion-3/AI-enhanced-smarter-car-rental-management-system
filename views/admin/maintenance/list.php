<div class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Admin Sidebar -->
        <?php require 'views/admin/sidebar.php'; ?>

        <!-- Main Content -->
        <di class="flex-1 p-8">
            <div class="container mx-auto px-4 py-8">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Vehicle Maintenance Records</h1>
                    <a href="index.php?page=admin&action=maintenance&subaction=add"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                        Add New Maintenance
                    </a>
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

                <!-- Search and Filter -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <form action="index.php" method="GET"
                        class="flex flex-col md:flex-row md:items-end space-y-4 md:space-y-0 md:space-x-4">
                        <input type="hidden" name="page" value="admin">
                        <input type="hidden" name="action" value="maintenance">

                        <div class="flex-1">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input type="text" id="search" name="search"
                                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                placeholder="Search by car details or description">
                        </div>

                        <div class="w-full md:w-48">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" name="status"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">All Status</option>
                                <option value="scheduled"
                                    <?= (isset($_GET['status']) && $_GET['status'] === 'scheduled') ? 'selected' : '' ?>>
                                    Scheduled</option>
                                <option value="in_progress"
                                    <?= (isset($_GET['status']) && $_GET['status'] === 'in_progress') ? 'selected' : '' ?>>
                                    In Progress</option>
                                <option value="completed"
                                    <?= (isset($_GET['status']) && $_GET['status'] === 'completed') ? 'selected' : '' ?>>
                                    Completed</option>
                            </select>
                        </div>

                        <div class="w-full md:w-64">
                            <label for="car_id" class="block text-sm font-medium text-gray-700 mb-1">Vehicle</label>
                            <select id="car_id" name="car_id"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">All Vehicles</option>
                                <?php foreach ($cars as $car): ?>
                                <option value="<?= $car['car_id'] ?>"
                                    <?= (isset($_GET['car_id']) && $_GET['car_id'] == $car['car_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($car['make'] . ' ' . $car['model'] . ' (' . $car['registration_number'] . ')') ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="flex space-x-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                                Search
                            </button>
                            <a href="index.php?page=admin&action=maintenance"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Maintenance Records Table -->
                <div class="bg-white rounded-lg shadow-md overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Vehicle
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dates
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cost
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($maintenanceRecords)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No maintenance records found
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($maintenanceRecords as $record): ?>
                            <?php 
                            // Set status class
                            switch ($record['status']) {
                                case 'scheduled':
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                    break;
                                case 'in_progress':
                                    $statusClass = 'bg-blue-100 text-blue-800';
                                    break;
                                case 'completed':
                                    $statusClass = 'bg-green-100 text-green-800';
                                    break;
                                default:
                                    $statusClass = 'bg-gray-100 text-gray-800';
                            }
                            
                            // Format maintenance type
                            $typeLabel = ucfirst(str_replace('_', ' ', $record['maintenance_type']));
                        ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($record['make'] . ' ' . $record['model']) ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?= htmlspecialchars($record['registration_number']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                      <?= $record['maintenance_type'] === 'routine' ? 'bg-green-100 text-green-800' : 
                                         ($record['maintenance_type'] === 'repair' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') ?>">
                                        <?= $typeLabel ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">
                                        <?= htmlspecialchars($record['description']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>Start: <?= date('M d, Y', strtotime($record['start_date'])) ?></div>
                                    <?php if (!empty($record['end_date'])): ?>
                                    <div>End: <?= date('M d, Y', strtotime($record['end_date'])) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= !empty($record['cost']) ? '$' . number_format($record['cost'], 2) : '-' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusClass ?>">
                                        <?= ucfirst(str_replace('_', ' ', $record['status'])) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <?php if ($record['status'] !== 'completed'): ?>
                                        <a href="index.php?page=admin&action=maintenance&subaction=complete&id=<?= $record['maintenance_id'] ?>"
                                            class="text-green-600 hover:text-green-900"
                                            onclick="return confirm('Are you sure you want to mark this maintenance as completed?')">
                                            Complete
                                        </a>
                                        <?php endif; ?>
                                        <a href="index.php?page=admin&action=maintenance&subaction=edit&id=<?= $record['maintenance_id'] ?>"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            Edit
                                        </a>
                                        <a href="index.php?page=admin&action=maintenance&subaction=delete&id=<?= $record['maintenance_id'] ?>"
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Are you sure you want to delete this maintenance record? This action cannot be undone.')">
                                            Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <div class="flex justify-center mt-6">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <?php if ($page > 1): ?>
                        <a href="index.php?page=admin&action=maintenance&page_num=<?= $page - 1 ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '' ?><?= isset($_GET['car_id']) ? '&car_id=' . urlencode($_GET['car_id']) : '' ?>"
                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Previous</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="index.php?page=admin&action=maintenance&page_num=<?= $i ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '' ?><?= isset($_GET['car_id']) ? '&car_id=' . urlencode($_GET['car_id']) : '' ?>"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?= $i === $page ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-50' ?>">
                            <?= $i ?>
                        </a>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                        <a href="index.php?page=admin&action=maintenance&page_num=<?= $page + 1 ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '' ?><?= isset($_GET['car_id']) ? '&car_id=' . urlencode($_GET['car_id']) : '' ?>"
                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Next</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                        <?php endif; ?>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
    </div>
</div>
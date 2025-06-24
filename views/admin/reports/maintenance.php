<div class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Admin Sidebar -->
        <?php require 'views/admin/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="container mx-auto px-4 py-8">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Maintenance Report</h1>
                    <div class="flex space-x-2">
                        <a href="index.php?page=admin&action=reports&type=revenue"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md <?= $reportType === 'revenue' ? 'bg-blue-700' : '' ?>">Revenue</a>
                        <a href="index.php?page=admin&action=reports&type=utilization"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md <?= $reportType === 'utilization' ? 'bg-blue-700' : '' ?>">Utilization</a>
                        <a href="index.php?page=admin&action=reports&type=maintenance"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md <?= $reportType === 'maintenance' ? 'bg-blue-700' : '' ?>">Maintenance</a>
                        <a href="index.php?page=admin&action=reports&type=customer"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md <?= $reportType === 'customer' ? 'bg-blue-700' : '' ?>">Customer</a>
                    </div>
                </div>

                <!-- Date Range Filter -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <form action="index.php" method="GET"
                        class="flex flex-col md:flex-row md:items-end space-y-4 md:space-y-0 md:space-x-4">
                        <input type="hidden" name="page" value="admin">
                        <input type="hidden" name="action" value="reports">
                        <input type="hidden" name="type" value="maintenance">

                        <div class="flex-1">
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start
                                Date</label>
                            <input type="date" id="start_date" name="start_date"
                                value="<?= htmlspecialchars($startDate) ?>"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="flex-1">
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($endDate) ?>"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                                Apply Filter
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Maintenance Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Maintenance</h2>
                        <p class="text-3xl font-bold text-blue-600"><?= $totalMaintenance ?></p>
                        <p class="text-sm text-gray-500 mt-1">Records for the period</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Cost</h2>
                        <p class="text-3xl font-bold text-blue-600"><?= formatCurrency($totalCost ?? 0) ?></p>
                        <p class="text-sm text-gray-500 mt-1">Maintenance expenses</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Completion Rate</h2>
                        <p class="text-3xl font-bold text-blue-600"><?= number_format($completionRate ?? 0, 1) ?>%</p>
                        <p class="text-sm text-gray-500 mt-1">Completed maintenance</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Avg. Cost per Record</h2>
                        <p class="text-3xl font-bold text-blue-600">
                            <?= formatCurrency($totalMaintenance > 0 ? ($totalCost / $totalMaintenance) : 0) ?></p>
                        <p class="text-sm text-gray-500 mt-1">Average maintenance cost</p>
                    </div>
                </div>

                <!-- Maintenance by Type Chart -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Maintenance by Type</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="h-80">
                            <canvas id="maintenanceTypeChart"></canvas>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Type
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Count
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Cost
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Average Cost
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($typeData as $type): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <?= ucfirst(htmlspecialchars($type['maintenance_type'])) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= $type['count'] ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= formatCurrency($type['total_cost'] ?? 0) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= formatCurrency($type['average_cost'] ?? 0) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Top Maintenance Costs by Car -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Top Maintenance Costs by Car</h2>
                    <div class="h-80 mb-6">
                        <canvas id="carMaintenanceChart"></canvas>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Vehicle
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Maintenance Count
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Cost
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Average Cost
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($carData as $car): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?= htmlspecialchars($car['registration_number']) ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= $car['maintenance_count'] ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= formatCurrency($car['total_cost'] ?? 0) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= formatCurrency($car['maintenance_count'] > 0 ? ($car['total_cost'] / $car['maintenance_count']) : 0) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Maintenance Records -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Maintenance Records</h2>
                    <div class="overflow-x-auto">
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
                                        Cost
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($maintenanceData as $record): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($record['make'] . ' ' . $record['model']) ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?= htmlspecialchars($record['registration_number']) ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= ucfirst(htmlspecialchars($record['maintenance_type'])) ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                            <?= htmlspecialchars($record['description']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= formatCurrency($record['cost'] ?? 0) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('M d, Y', strtotime($record['start_date'])) ?>
                                            <?php if (!empty($record['end_date']) && $record['end_date'] != $record['start_date']): ?>
                                                - <?= date('M d, Y', strtotime($record['end_date'])) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php
                                            $statusClass = '';
                                            switch ($record['status']) {
                                                case 'completed':
                                                    $statusClass = 'bg-green-100 text-green-800';
                                                    break;
                                                case 'in_progress':
                                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                                    break;
                                                default:
                                                    $statusClass = 'bg-blue-100 text-blue-800';
                                                    break;
                                            }
                                            ?>
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusClass ?>">
                                                <?= ucfirst(str_replace('_', ' ', $record['status'])) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Include Chart.js -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                // Maintenance Type Chart
                const typeCtx = document.getElementById('maintenanceTypeChart').getContext('2d');

                // Prepare data for maintenance type chart
                const maintenanceTypes = <?= json_encode(array_map(function ($type) {
                    return ucfirst($type['maintenance_type']); }, $typeData)) ?>;
                const typeCounts = <?= json_encode(array_column($typeData, 'count')) ?>;
                const typeCosts = <?= json_encode(array_column($typeData, 'total_cost')) ?>;

                const maintenanceTypeChart = new Chart(typeCtx, {
                    type: 'pie',
                    data: {
                        labels: maintenanceTypes,
                        datasets: [{
                            data: typeCosts,
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 99, 132, 0.7)',
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(75, 192, 192, 0.7)',
                                'rgba(153, 102, 255, 0.7)'
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = Math.round((value / total) * 100);
                                        return `${label}: $${value.toFixed(2)} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });

                // Car Maintenance Chart
                const carCtx = document.getElementById('carMaintenanceChart').getContext('2d');

                // Prepare data for car maintenance chart
                const carLabels = <?= json_encode(array_map(function ($car) {
                    return $car['make'] . ' ' . $car['model']; }, $carData)) ?>;
                const carCosts = <?= json_encode(array_column($carData, 'total_cost')) ?>;
                const carCounts = <?= json_encode(array_column($carData, 'maintenance_count')) ?>;

                const carMaintenanceChart = new Chart(carCtx, {
                    type: 'bar',
                    data: {
                        labels: carLabels,
                        datasets: [
                            {
                                label: 'Total Cost ($)',
                                data: carCosts,
                                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                                borderColor: 'rgba(59, 130, 246, 1)',
                                borderWidth: 1,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Maintenance Count',
                                data: carCounts,
                                type: 'line',
                                backgroundColor: 'rgba(220, 38, 38, 0.2)',
                                borderColor: 'rgba(220, 38, 38, 1)',
                                borderWidth: 2,
                                pointBackgroundColor: 'rgba(220, 38, 38, 1)',
                                pointRadius: 3,
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
                                type: 'linear',
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Total Cost ($)'
                                }
                            },
                            y1: {
                                beginAtZero: true,
                                type: 'linear',
                                position: 'right',
                                grid: {
                                    drawOnChartArea: false
                                },
                                title: {
                                    display: true,
                                    text: 'Maintenance Count'
                                }
                            }
                        },
                        interaction: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                });
            </script>
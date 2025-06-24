<div class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Admin Sidebar -->
        <?php require 'views/admin/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="container mx-auto px-4 py-8">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Customer Report</h1>
                    <div class="flex space-x-2">
                        <a href="index.php?page=admin&action=reports&type=revenue"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md <?= $reportType === 'revenue' ? 'bg-blue-700' : '' ?>">Revenue</a>

                        <a href="index.php?page=admin&action=reports&type=maintenance"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md <?= $reportType === 'maintenance' ? 'bg-blue-700' : '' ?>">Maintenance</a>
                        <a href="index.php?page=admin&action=reports&type=customer"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md <?= $reportType === 'customer' ? 'bg-blue-700' : '' ?>">Customer</a>
                        <button id="download-pdf"
                            class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Download
                            PDF</button>
                    </div>
                </div>

                <!-- Date Range Filter -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <form action="index.php" method="GET"
                        class="flex flex-col md:flex-row md:items-end space-y-4 md:space-y-0 md:space-x-4">
                        <input type="hidden" name="page" value="admin">
                        <input type="hidden" name="action" value="reports">
                        <input type="hidden" name="type" value="customer">

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

                <!-- Customer Growth Chart -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">New Customer Growth</h2>
                    <div class="h-80">
                        <canvas id="customerGrowthChart"></canvas>
                    </div>
                </div>

                <!-- Customer Preferences Chart -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Customer Preferences</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="h-80">
                            <canvas id="preferencesChart"></canvas>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Car Type
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Number of Customers
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Percentage
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    $totalPreferences = array_sum(array_column($preferencesData, 'count'));
                                    foreach ($preferencesData as $preference):
                                        ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <?= ucfirst(htmlspecialchars($preference['preferred_car_type'])) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= $preference['count'] ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= number_format(($preference['count'] / $totalPreferences) * 100, 1) ?>%
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Top Customers -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Top Customers by Revenue</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Customer
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rentals
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Spent
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Last Rental
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($customerData as $customer): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($customer['full_name']) ?>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                @<?= htmlspecialchars($customer['username']) ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($customer['email']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= $customer['rental_count'] ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            RWF <?= number_format($customer['total_spent'] ?? 0, 2) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('M d, Y', strtotime($customer['last_rental'])) ?>
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
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
            <script>
                // Customer Growth Chart
                const growthCtx = document.getElementById('customerGrowthChart').getContext('2d');

                const customerGrowthChart = new Chart(growthCtx, {
                    type: 'line',
                    data: {
                        labels: <?= $chartData['months'] ?>,
                        datasets: [{
                            label: 'New Customers',
                            data: <?= $chartData['newUsers'] ?>,
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 2,
                            tension: 0.1,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Number of New Customers'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Month'
                                }
                            }
                        }
                    }
                });

                // Customer Preferences Chart
                const preferencesCtx = document.getElementById('preferencesChart').getContext('2d');

                // Prepare data for preferences chart
                const preferenceLabels =
                    <?= json_encode(array_map(function ($pref) {
                        return ucfirst($pref['preferred_car_type']);
                    }, $preferencesData)) ?>;
                const preferenceCounts = <?= json_encode(array_column($preferencesData, 'count')) ?>;

                const preferencesChart = new Chart(preferencesCtx, {
                    type: 'doughnut',
                    data: {
                        labels: preferenceLabels,
                        datasets: [{
                            data: preferenceCounts,
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 99, 132, 0.7)',
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(75, 192, 192, 0.7)',
                                'rgba(153, 102, 255, 0.7)',
                                'rgba(255, 159, 64, 0.7)'
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
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
                                        return `${label}: ${value} customers (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });

                document.addEventListener('DOMContentLoaded', function () {
                    var btn = document.getElementById('download-pdf');
                    if (!btn) return;
                    btn.addEventListener('click', function () {
                        const pdf = new window.jspdf.jsPDF('p', 'mm', 'a4');
                        // Header design
                        pdf.setFillColor(54, 162, 235);
                        pdf.rect(0, 0, 210, 20, 'F');
                        pdf.setTextColor(255, 255, 255);
                        pdf.setFontSize(20);
                        pdf.text('Customer Report', 105, 13, { align: 'center' });
                        pdf.setTextColor(0, 0, 0);
                        let y = 25;
                        pdf.setFontSize(12);
                        pdf.text('Date: ' + new Date().toLocaleDateString(), 15, y);
                        y += 8;
                        // Section divider
                        pdf.setDrawColor(54, 162, 235);
                        pdf.setLineWidth(1);
                        pdf.line(10, y, 200, y);
                        y += 5;
                        // Table: Customer Preferences
                        pdf.setFontSize(14);
                        pdf.setTextColor(54, 162, 235);
                        pdf.text('Customer Preferences', 15, y);
                        pdf.setTextColor(0, 0, 0);
                        y += 8;
                        pdf.setFontSize(10);
                        pdf.setFillColor(230, 240, 255);
                        pdf.rect(13, y - 5, 180, 7, 'F');
                        pdf.text('Car Type', 15, y);
                        pdf.text('Customers', 70, y);
                        pdf.text('Percent', 110, y);
                        y += 6;
                        <?php $totalPreferences = array_sum(array_column($preferencesData, 'count'));
                        foreach ($preferencesData as $preference): ?>
                            pdf.text('<?= ucfirst(htmlspecialchars($preference['preferred_car_type'])) ?>', 15, y);
                            pdf.text('<?= $preference['count'] ?>', 70, y);
                            pdf.text('<?= number_format(($preference['count'] / $totalPreferences) * 100, 1) ?>%', 110, y);
                            y += 6;
                        <?php endforeach; ?>
                        y += 4;
                        // Section divider
                        pdf.setDrawColor(54, 162, 235);
                        pdf.setLineWidth(0.5);
                        pdf.line(10, y, 200, y);
                        y += 5;
                        // Table: Top Customers by Revenue (first 10 for brevity)
                        pdf.setFontSize(14);
                        pdf.setTextColor(54, 162, 235);
                        pdf.text('Top Customers by Revenue', 15, y);
                        pdf.setTextColor(0, 0, 0);
                        y += 8;
                        pdf.setFontSize(10);
                        pdf.setFillColor(230, 240, 255);
                        pdf.rect(13, y - 5, 180, 7, 'F');
                        pdf.text('Name', 15, y);
                        pdf.text('Email', 60, y);
                        pdf.text('Rentals', 110, y);
                        pdf.text('Spent', 135, y);
                        pdf.text('Last Rental', 160, y);
                        y += 6;
                        <?php $count = 0;
                        foreach ($customerData as $customer):
                            if ($count++ >= 10)
                                break; ?>
                            pdf.text('<?= htmlspecialchars($customer['full_name']) ?>', 15, y);
                            pdf.text('<?= htmlspecialchars($customer['email']) ?>', 60, y);
                            pdf.text('<?= $customer['rental_count'] ?>', 110, y);
                            pdf.text('RWF <?= number_format($customer['total_spent'] ?? 0, 2) ?>', 135, y);
                            pdf.text('<?= date('M d, Y', strtotime($customer['last_rental'])) ?>', 160, y);
                            y += 6;
                        <?php endforeach; ?>
                        pdf.save('customer-report.pdf');
                    });
                });
            </script>
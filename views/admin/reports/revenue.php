<div class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Admin Sidebar -->
        <?php require 'views/admin/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-8">

            <div class="container mx-auto px-4 py-8">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Revenue Report</h1>
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
                        <input type="hidden" name="type" value="revenue">

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

                <!-- Revenue Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Revenue</h2>
                        <p class="text-3xl font-bold text-blue-600"><?= formatCurrency($totals['total_revenue']) ?></p>
                        <p class="text-sm text-gray-500 mt-1">For the selected period</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Rentals</h2>
                        <p class="text-3xl font-bold text-blue-600"><?= $totals['total_rentals'] ?></p>
                        <p class="text-sm text-gray-500 mt-1">Bookings completed</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Average Rental Value</h2>
                        <p class="text-3xl font-bold text-blue-600"><?= formatCurrency($totals['average_rental']) ?></p>
                        <p class="text-sm text-gray-500 mt-1">Per booking</p>
                    </div>
                </div>

                <!-- Revenue Chart -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Revenue Trend</h2>
                    <div class="h-80">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Revenue by Category -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Revenue by Category</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Category
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Revenue
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rentals
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Average Value
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        % of Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($categoryData as $category): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($category['category']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= formatCurrency($category['revenue']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= $category['rentals'] ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= formatCurrency($category['revenue'] / $category['rentals']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= number_format(($category['revenue'] / $totals['total_revenue']) * 100, 1) ?>%
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Daily Revenue Data -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Daily Revenue Data</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Revenue
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rentals
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Average Value
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($revenueData as $data): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= date('M d, Y', strtotime($data['date'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= formatCurrency($data['daily_revenue']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= $data['rental_count'] ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= formatCurrency($data['daily_revenue'] / $data['rental_count']) ?>
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
                // Revenue Chart
                const ctx = document.getElementById('revenueChart').getContext('2d');
                const revenueChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?= $chartData['labels'] ?>,
                        datasets: [
                            {
                                label: 'Revenue ($)',
                                data: <?= $chartData['revenues'] ?>,
                                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                                borderColor: 'rgba(59, 130, 246, 1)',
                                borderWidth: 1,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Rentals',
                                data: <?= $chartData['counts'] ?>,
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
                                    text: 'Revenue ($)'
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
                                    text: 'Number of Rentals'
                                }
                            }
                        },
                        interaction: {
                            mode: 'index',
                            intersect: false
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
                        pdf.text('Revenue Report', 105, 13, { align: 'center' });
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
                        // Summary
                        pdf.setFontSize(14);
                        pdf.setTextColor(54, 162, 235);
                        pdf.text('Summary', 15, y);
                        pdf.setTextColor(0, 0, 0);
                        y += 8;
                        pdf.setFontSize(12);
                        pdf.text('Total Revenue: <?= formatCurrency($totals['total_revenue']) ?>', 15, y);
                        y += 7;
                        pdf.text('Total Rentals: <?= $totals['total_rentals'] ?>', 15, y);
                        y += 7;
                        pdf.text('Average Rental Value: <?= formatCurrency($totals['average_rental']) ?>', 15, y);
                        y += 10;
                        // Section divider
                        pdf.setDrawColor(54, 162, 235);
                        pdf.setLineWidth(0.5);
                        pdf.line(10, y, 200, y);
                        y += 5;
                        // Table: Revenue by Category
                        pdf.setFontSize(14);
                        pdf.setTextColor(54, 162, 235);
                        pdf.text('Revenue by Category', 15, y);
                        pdf.setTextColor(0, 0, 0);
                        y += 8;
                        pdf.setFontSize(10);
                        pdf.setFillColor(230, 240, 255);
                        pdf.rect(13, y - 5, 180, 7, 'F');
                        pdf.text('Category', 15, y);
                        pdf.text('Revenue', 60, y);
                        pdf.text('Rentals', 100, y);
                        pdf.text('Avg. Value', 130, y);
                        pdf.text('% of Total', 160, y);
                        y += 6;
                        <?php foreach ($categoryData as $category): ?>
                            pdf.text('<?= htmlspecialchars($category['category']) ?>', 15, y);
                            pdf.text('<?= formatCurrency($category['revenue']) ?>', 60, y);
                            pdf.text('<?= $category['rentals'] ?>', 100, y);
                            pdf.text('<?= formatCurrency($category['revenue'] / $category['rentals']) ?>', 130, y);
                            pdf.text('<?= number_format(($category['revenue'] / $totals['total_revenue']) * 100, 1) ?>%', 160, y);
                            y += 6;
                        <?php endforeach; ?>
                        y += 4;
                        // Section divider
                        pdf.setDrawColor(54, 162, 235);
                        pdf.setLineWidth(0.5);
                        pdf.line(10, y, 200, y);
                        y += 5;
                        // Table: Daily Revenue Data (first 10 for brevity)
                        pdf.setFontSize(14);
                        pdf.setTextColor(54, 162, 235);
                        pdf.text('Sample Daily Revenue Data', 15, y);
                        pdf.setTextColor(0, 0, 0);
                        y += 8;
                        pdf.setFontSize(10);
                        pdf.setFillColor(230, 240, 255);
                        pdf.rect(13, y - 5, 180, 7, 'F');
                        pdf.text('Date', 15, y);
                        pdf.text('Revenue', 60, y);
                        pdf.text('Rentals', 100, y);
                        pdf.text('Avg. Value', 130, y);
                        y += 6;
                        <?php $count = 0;
                        foreach ($revenueData as $data):
                            if ($count++ >= 10)
                                break; ?>
                            pdf.text('<?= date('M d, Y', strtotime($data['date'])) ?>', 15, y);
                            pdf.text('<?= formatCurrency($data['daily_revenue']) ?>', 60, y);
                            pdf.text('<?= $data['rental_count'] ?>', 100, y);
                            pdf.text('<?= formatCurrency($data['daily_revenue'] / $data['rental_count']) ?>', 130, y);
                            y += 6;
                        <?php endforeach; ?>
                        pdf.save('revenue-report.pdf');
                    });
                });
            </script>
        </div>
    </div>
</div>
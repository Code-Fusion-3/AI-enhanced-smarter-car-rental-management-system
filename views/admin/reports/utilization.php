<div class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Admin Sidebar -->
        <?php require 'views/admin/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="container mx-auto px-4 py-8">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Fleet Utilization Report</h1>
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
                        <input type="hidden" name="type" value="utilization">

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

                <!-- Utilization Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Overall Utilization Rate</h2>
                        <p class="text-3xl font-bold text-blue-600"><?= number_format($overallUtilization, 1) ?>%</p>
                        <p class="text-sm text-gray-500 mt-1">For the selected period</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Fleet Size</h2>
                        <p class="text-3xl font-bold text-blue-600"><?= $totalCars ?></p>
                        <p class="text-sm text-gray-500 mt-1">Vehicles in fleet</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Rental Days</h2>
                        <p class="text-3xl font-bold text-blue-600"><?= $totalRentalDays ?></p>
                        <p class="text-sm text-gray-500 mt-1">Days vehicles were rented</p>
                    </div>
                </div>

                <!-- Utilization by Category Chart -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Utilization by Category</h2>
                    <div class="h-80">
                        <canvas id="categoryUtilizationChart"></canvas>
                    </div>
                </div>

                <!-- Utilization by Category Table -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Utilization by Category</h2>
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
                                        Car Count
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rental Count
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Rental Days
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Utilization Rate
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
                                            <?= $category['car_count'] ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= $category['rental_count'] ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= $category['total_rental_days'] ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2 max-w-[100px]">
                                                    <div class="bg-blue-600 h-2.5 rounded-full"
                                                        style="width: <?= min(100, $category['utilization_rate']) ?>%">
                                                    </div>
                                                </div>
                                                <?= number_format($category['utilization_rate'] ?? 0, 1) ?>%

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Individual Car Utilization -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Individual Car Utilization</h2>
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
                                        Category
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rental Count
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Rental Days
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Utilization Rate
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($utilizationData as $car): ?>
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
                                            <?= htmlspecialchars($car['category']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= $car['rental_count'] ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= $car['total_rental_days'] ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2 max-w-[100px]">
                                                    <div class="bg-blue-600 h-2.5 rounded-full"
                                                        style="width: <?= min(100, $car['utilization_rate']) ?>%"></div>
                                                </div>
                                                <?= number_format($car['utilization_rate'] ?? 0, 1) ?>%

                                            </div>
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
                // Category Utilization Chart
                const ctx = document.getElementById('categoryUtilizationChart').getContext('2d');

                // Prepare data for chart
                const categories = <?= json_encode(array_column($categoryData, 'category')) ?>;
                const utilizationRates = <?= json_encode(array_column($categoryData, 'utilization_rate')) ?>;
                const carCounts = <?= json_encode(array_column($categoryData, 'car_count')) ?>;

                const categoryUtilizationChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: categories,
                        datasets: [
                            {
                                label: 'Utilization Rate (%)',
                                data: utilizationRates,
                                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                                borderColor: 'rgba(59, 130, 246, 1)',
                                borderWidth: 1,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Car Count',
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
                                    text: 'Utilization Rate (%)'
                                },
                                max: 100
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
                                    text: 'Number of Cars'
                                }
                            }
                        },
                        interaction: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                });

                // Custom jsPDF Export for Utilization Report
                // Wait for DOMContentLoaded to ensure all elements are ready
                window.addEventListener('DOMContentLoaded', function () {
                    document.getElementById('download-pdf').addEventListener('click', function () {
                        const { jsPDF } = window.jspdf;
                        const pdf = new jsPDF('p', 'mm', 'a4');
                        const pageWidth = pdf.internal.pageSize.getWidth();
                        let y = 15;

                        // Header
                        pdf.setFillColor(59, 130, 246); // Tailwind blue-600
                        pdf.rect(0, 0, pageWidth, 20, 'F');
                        pdf.setTextColor(255, 255, 255);
                        pdf.setFontSize(18);
                        pdf.text('Fleet Utilization Report', pageWidth / 2, 13, { align: 'center' });
                        y = 28;

                        // Date Range
                        pdf.setFontSize(11);
                        pdf.setTextColor(80, 80, 80);
                        pdf.text(`Period: <?= htmlspecialchars($startDate->format('Y-m-d')) ?> to <?= htmlspecialchars($endDate->format('Y-m-d')) ?>`, 12, y);
                        y += 8;

                        // Section Divider
                        pdf.setDrawColor(59, 130, 246);
                        pdf.setLineWidth(1);
                        pdf.line(12, y, pageWidth - 12, y);
                        y += 6;

                        // Utilization Summary Cards (as table)
                        pdf.setFontSize(13);
                        pdf.setTextColor(59, 130, 246);
                        pdf.text('Summary', 12, y);
                        y += 6;
                        pdf.setFontSize(11);
                        pdf.setTextColor(0, 0, 0);
                        pdf.setFillColor(219, 234, 254); // Tailwind blue-100
                        pdf.rect(12, y, pageWidth - 24, 8, 'F');
                        pdf.setTextColor(59, 130, 246);
                        pdf.text('Overall Utilization Rate', 14, y + 6);
                        pdf.text('Total Fleet Size', 80, y + 6);
                        pdf.text('Total Rental Days', 140, y + 6);
                        y += 8;
                        pdf.setTextColor(0, 0, 0);
                        pdf.text('<?= number_format($overallUtilization, 1) ?>%', 14, y + 6);
                        pdf.text('<?= $totalCars ?>', 80, y + 6);
                        pdf.text('<?= $totalRentalDays ?>', 140, y + 6);
                        y += 12;

                        // Section Divider
                        pdf.setDrawColor(59, 130, 246);
                        pdf.setLineWidth(0.5);
                        pdf.line(12, y, pageWidth - 12, y);
                        y += 6;

                        // Utilization by Category Table
                        pdf.setFontSize(13);
                        pdf.setTextColor(59, 130, 246);
                        pdf.text('Utilization by Category', 12, y);
                        y += 6;
                        pdf.setFontSize(10);
                        pdf.setFillColor(191, 219, 254); // Tailwind blue-200
                        pdf.setTextColor(59, 130, 246);
                        pdf.rect(12, y, pageWidth - 24, 8, 'F');
                        pdf.text('Category', 14, y + 6);
                        pdf.text('Car Count', 50, y + 6);
                        pdf.text('Rental Count', 80, y + 6);
                        pdf.text('Total Rental Days', 115, y + 6);
                        pdf.text('Utilization Rate', 160, y + 6);
                        y += 8;
                        pdf.setTextColor(0, 0, 0);
                        <?php foreach ($categoryData as $category): ?>
                            if (y > 270) { pdf.addPage(); y = 20; }
                            pdf.text('<?= htmlspecialchars($category['category']) ?>', 14, y + 6);
                            pdf.text('<?= $category['car_count'] ?>', 50, y + 6);
                            pdf.text('<?= $category['rental_count'] ?>', 80, y + 6);
                            pdf.text('<?= $category['total_rental_days'] ?>', 115, y + 6);
                            pdf.text('<?= number_format($category['utilization_rate'] ?? 0, 1) ?>%', 160, y + 6);
                            y += 8;
                        <?php endforeach; ?>
                        y += 4;

                        // Section Divider
                        pdf.setDrawColor(59, 130, 246);
                        pdf.line(12, y, pageWidth - 12, y);
                        y += 6;

                        // Individual Car Utilization Table
                        pdf.setFontSize(13);
                        pdf.setTextColor(59, 130, 246);
                        pdf.text('Individual Car Utilization', 12, y);
                        y += 6;
                        pdf.setFontSize(10);
                        pdf.setFillColor(191, 219, 254);
                        pdf.setTextColor(59, 130, 246);
                        pdf.rect(12, y, pageWidth - 24, 8, 'F');
                        pdf.text('Vehicle', 14, y + 6);
                        pdf.text('Category', 60, y + 6);
                        pdf.text('Rental Count', 95, y + 6);
                        pdf.text('Total Rental Days', 130, y + 6);
                        pdf.text('Utilization Rate', 170, y + 6);
                        y += 8;
                        pdf.setTextColor(0, 0, 0);
                        <?php foreach ($utilizationData as $car): ?>
                            if (y > 270) { pdf.addPage(); y = 20; }
                            pdf.text('<?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?> (<?= htmlspecialchars($car['registration_number']) ?>)', 14, y + 6);
                            pdf.text('<?= htmlspecialchars($car['category']) ?>', 60, y + 6);
                            pdf.text('<?= $car['rental_count'] ?>', 95, y + 6);
                            pdf.text('<?= $car['total_rental_days'] ?>', 130, y + 6);
                            pdf.text('<?= number_format($car['utilization_rate'] ?? 0, 1) ?>%', 170, y + 6);
                            y += 8;
                        <?php endforeach; ?>

                        // Save PDF
                        pdf.save('utilization-report.pdf');
                    });
                });
            </script>
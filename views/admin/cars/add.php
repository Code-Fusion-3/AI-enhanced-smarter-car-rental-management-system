<div class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Admin Sidebar -->
        <?php require 'views/admin/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Add New Car</h1>
                    <p class="text-gray-600 mt-1">Add a new vehicle to the rental fleet</p>
                </div>

                <a href="index.php?page=admin&action=cars"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Back to Cars
                </a>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
            <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700"><?= $_SESSION['error'] ?></p>
                    </div>
                </div>
            </div>
            <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <form action="index.php?page=admin&action=cars&subaction=create" method="POST"
                        enctype="multipart/form-data">
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <!-- Basic Information Section -->
                            <div class="sm:col-span-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Basic Information</h3>
                                <div class="border-t border-gray-200 pt-4"></div>
                            </div>

                            <!-- Make -->
                            <div class="sm:col-span-3">
                                <label for="make" class="block text-sm font-medium text-gray-700">Make</label>
                                <div class="mt-1">
                                    <input type="text" name="make" id="make" required
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <!-- Model -->
                            <div class="sm:col-span-3">
                                <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                                <div class="mt-1">
                                    <input type="text" name="model" id="model" required
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <!-- Year -->
                            <div class="sm:col-span-2">
                                <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                                <div class="mt-1">
                                    <input type="number" name="year" id="year" min="1900" max="<?= date('Y') + 1 ?>"
                                        required
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <!-- Registration Number -->
                            <div class="sm:col-span-2">
                                <label for="registration_number"
                                    class="block text-sm font-medium text-gray-700">Registration Number</label>
                                <div class="mt-1">
                                    <input type="text" name="registration_number" id="registration_number" required
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="sm:col-span-2">
                                <label for="category_id"
                                    class="block text-sm font-medium text-gray-700">Category</label>
                                <div class="mt-1">
                                    <select name="category_id" id="category_id"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['category_id'] ?>">
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="sm:col-span-2">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <div class="mt-1">
                                    <select name="status" id="status"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="available" selected>Available</option>
                                        <option value="rented">Rented</option>
                                        <option value="maintenance">Maintenance</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Mileage -->
                            <div class="sm:col-span-2">
                                <label for="mileage" class="block text-sm font-medium text-gray-700">Mileage</label>
                                <div class="mt-1">
                                    <input type="number" name="mileage" id="mileage" min="0"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <!-- Fuel Type -->
                            <div class="sm:col-span-2">
                                <label for="fuel_type" class="block text-sm font-medium text-gray-700">Fuel Type</label>
                                <div class="mt-1">
                                    <select name="fuel_type" id="fuel_type"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="">Select Fuel Type</option>
                                        <option value="petrol">Petrol</option>
                                        <option value="diesel">Diesel</option>
                                        <option value="electric">Electric</option>
                                        <option value="hybrid">Hybrid</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Transmission -->
                            <div class="sm:col-span-2">
                                <label for="transmission"
                                    class="block text-sm font-medium text-gray-700">Transmission</label>
                                <div class="mt-1">
                                    <select name="transmission" id="transmission"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="">Select Transmission</option>
                                        <option value="manual">Manual</option>
                                        <option value="automatic">Automatic</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Seats -->
                            <div class="sm:col-span-2">
                                <label for="seats" class="block text-sm font-medium text-gray-700">Seats</label>
                                <div class="mt-1">
                                    <input type="number" name="seats" id="seats" min="1" max="20"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <!-- Pricing Section -->
                            <div class="sm:col-span-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-2 mt-6">Pricing Information</h3>
                                <div class="border-t border-gray-200 pt-4"></div>
                            </div>

                            <!-- Daily Rate -->
                            <div class="sm:col-span-2">
                                <label for="daily_rate" class="block text-sm font-medium text-gray-700">Daily Rate
                                    (RWF)</label>
                                <div class="mt-1">
                                    <input type="number" name="daily_rate" id="daily_rate" min="0" step="0.01" required
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <!-- Base Rate -->
                            <div class="sm:col-span-2">
                                <label for="base_rate" class="block text-sm font-medium text-gray-700">Base Rate
                                    (RWF)</label>
                                <div class="mt-1">
                                    <input type="number" name="base_rate" id="base_rate" min="0" step="0.01" required
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <!-- Weekend Rate -->
                            <div class="sm:col-span-2">
                                <label for="weekend_rate" class="block text-sm font-medium text-gray-700">Weekend Rate
                                    (RWF)</label>
                                <div class="mt-1">
                                    <input type="number" name="weekend_rate" id="weekend_rate" min="0" step="0.01"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <!-- Weekly Rate -->
                            <div class="sm:col-span-2">
                                <label for="weekly_rate" class="block text-sm font-medium text-gray-700">Weekly Rate
                                    (RWF)</label>
                                <div class="mt-1">
                                    <input type="number" name="weekly_rate" id="weekly_rate" min="0" step="0.01"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <!-- Monthly Rate -->
                            <div class="sm:col-span-2">
                                <label for="monthly_rate" class="block text-sm font-medium text-gray-700">Monthly Rate
                                    (RWF)</label>
                                <div class="mt-1">
                                    <input type="number" name="monthly_rate" id="monthly_rate" min="0" step="0.01"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <!-- Features Section -->
                            <div class="sm:col-span-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-2 mt-6">Features & Details</h3>
                                <div class="border-t border-gray-200 pt-4"></div>
                            </div>

                            <!-- Features -->
                            <div class="sm:col-span-6">
                                <label for="features" class="block text-sm font-medium text-gray-700">Features</label>
                                <div class="mt-1">
                                    <textarea name="features" id="features" rows="3"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                                    <p class="mt-1 text-sm text-gray-500">Separate features with commas (e.g., GPS,
                                        Bluetooth, Backup Camera)</p>
                                </div>
                            </div>

                            <!-- Image Upload -->
                            <div class="sm:col-span-6">
                                <label for="image" class="block text-sm font-medium text-gray-700">Car Image</label>
                                <div class="mt-1">
                                    <input type="file" name="image" id="image" accept="image/*"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300">
                                    <p class="mt-1 text-sm text-gray-500">Upload an image (JPG, PNG, GIF up to 2MB)</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 border-t border-gray-200 pt-5">
                            <div class="flex justify-end">
                                <a href="index.php?page=admin&action=cars"
                                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Cancel
                                </a>
                                <button type="submit"
                                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Add Car
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- JavaScript for form validation and dynamic behavior -->
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Form validation
                const form = document.querySelector('form');
                form.addEventListener('submit', function(event) {
                    let valid = true;

                    // Basic validation
                    const requiredFields = ['make', 'model', 'year', 'registration_number',
                        'daily_rate', 'base_rate'
                    ];
                    requiredFields.forEach(field => {
                        const input = document.getElementById(field);
                        if (!input.value.trim()) {
                            input.classList.add('border-red-500');
                            valid = false;
                        } else {
                            input.classList.remove('border-red-500');
                        }
                    });

                    // Validate numeric fields
                    const numericFields = ['year', 'daily_rate', 'base_rate', 'weekend_rate',
                        'weekly_rate', 'monthly_rate', 'mileage', 'seats'
                    ];
                    numericFields.forEach(field => {
                        const input = document.getElementById(field);
                        if (input.value && isNaN(parseFloat(input.value))) {
                            input.classList.add('border-red-500');
                            valid = false;
                        }
                    });

                    if (!valid) {
                        event.preventDefault();
                        alert('Please fill in all required fields correctly.');
                    }
                });

                // Dynamic calculation of rates based on daily rate
                const dailyRateInput = document.getElementById('daily_rate');
                const baseRateInput = document.getElementById('base_rate');
                const weekendRateInput = document.getElementById('weekend_rate');
                const weeklyRateInput = document.getElementById('weekly_rate');
                const monthlyRateInput = document.getElementById('monthly_rate');

                dailyRateInput.addEventListener('change', function() {
                    const dailyRate = parseFloat(this.value) || 0;

                    // Only update other rates if they're empty
                    if (!baseRateInput.value) {
                        baseRateInput.value = dailyRate.toFixed(2);
                    }

                    if (!weekendRateInput.value) {
                        weekendRateInput.value = (dailyRate * 1.2).toFixed(2); // 20% more on weekends
                    }

                    if (!weeklyRateInput.value) {
                        weeklyRateInput.value = (dailyRate * 6).toFixed(2); // 7 days for the price of 6
                    }

                    if (!monthlyRateInput.value) {
                        monthlyRateInput.value = (dailyRate * 24).toFixed(
                        2); // 30 days for the price of 24
                    }
                });

                // Set current year as default for year field
                document.getElementById('year').value = new Date().getFullYear();
            });
            </script>
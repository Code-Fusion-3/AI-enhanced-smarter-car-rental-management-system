<div class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Admin Sidebar -->
        <?php require 'views/admin/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 p-8">
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="index.php?page=admin&action=maintenance" class="text-blue-500 hover:text-blue-700 mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Edit Maintenance Record</h1>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= $_SESSION['error'] ?></span>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="index.php?page=admin&action=maintenance&subaction=edit&id=<?= $maintenance['maintenance_id'] ?>" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="car_id" class="block text-sm font-medium text-gray-700 mb-1">Vehicle*</label>
                    <select id="car_id" name="car_id" required
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <option value="">Select a vehicle</option>
                        <?php foreach ($cars as $car): ?>
                            <option value="<?= $car['car_id'] ?>" <?= $maintenance['car_id'] == $car['car_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($car['make'] . ' ' . $car['model'] . ' (' . $car['registration_number'] . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="maintenance_type" class="block text-sm font-medium text-gray-700 mb-1">Maintenance Type*</label>
                    <select id="maintenance_type" name="maintenance_type" required
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="routine" <?= $maintenance['maintenance_type'] === 'routine' ? 'selected' : '' ?>>Routine</option>
                        <option value="repair" <?= $maintenance['maintenance_type'] === 'repair' ? 'selected' : '' ?>>Repair</option>
                        <option value="inspection" <?= $maintenance['maintenance_type'] === 'inspection' ? 'selected' : '' ?>>Inspection</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description*</label>
                    <textarea id="description" name="description" rows="3" required
                              class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                              placeholder="Describe the maintenance work"><?= htmlspecialchars($maintenance['description']) ?></textarea>
                </div>

                <div>
                    <label for="cost" class="block text-sm font-medium text-gray-700 mb-1">Cost ($)</label>
                    <input type="number" id="cost" name="cost" min="0" step="0.01" value="<?= htmlspecialchars($maintenance['cost']) ?>"
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                           placeholder="e.g., 150.00">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status*</label>
                    <select id="status" name="status" required
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <option value="scheduled" <?= $maintenance['status'] === 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
                        <option value="in_progress" <?= $maintenance['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="completed" <?= $maintenance['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                    </select>
                </div>

                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date*</label>
                    <input type="date" id="start_date" name="start_date" required value="<?= htmlspecialchars($maintenance['start_date']) ?>"
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="<?= !empty($maintenance['end_date']) ? htmlspecialchars($maintenance['end_date']) : '' ?>"
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    <p class="mt-1 text-xs text-gray-500">Leave blank if not completed or for future maintenance</p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-3">
                <a href="index.php?page=admin&action=maintenance" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    Update Maintenance Record
                </button>
            </div>
        </form>
    </div>
</div>


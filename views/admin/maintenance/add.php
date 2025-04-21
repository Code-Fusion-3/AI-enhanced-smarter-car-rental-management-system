
<div class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Admin Sidebar -->
        <?php require 'views/admin/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 p-8">
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Add Maintenance Record</h1>
        <p class="text-gray-600 mt-1">For <?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?> (<?= htmlspecialchars($car['registration_number']) ?>)</p>
    </div>
    
    <a href="index.php?page=admin&action=cars&subaction=view&id=<?= $car['car_id'] ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Back to Car Details
    </a>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
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
        <form action="index.php?page=admin&action=maintenance&subaction=create" method="POST">
            <input type="hidden" name="car_id" value="<?= $car['car_id'] ?>">
            
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Maintenance Type -->
                <div class="sm:col-span-3">
                    <label for="maintenance_type" class="block text-sm font-medium text-gray-700">Maintenance Type</label>
                    <div class="mt-1">
                        <select id="maintenance_type" name="maintenance_type" required class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Select Type</option>
                            <option value="routine">Routine Maintenance</option>
                            <option value="repair">Repair</option>
                            <option value="inspection">Inspection</option>
                        </select>
                    </div>
                </div>
                
                <!-- Status -->
                <div class="sm:col-span-3">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <div class="mt-1">
                        <select id="status" name="status" required class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="scheduled">Scheduled</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                
                <!-- Description -->
                <div class="sm:col-span-6">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <div class="mt-1">
                        <textarea id="description" name="description" rows="3" required class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Detailed description of the maintenance work.</p>
                </div>
                
                <!-- Cost -->
                <div class="sm:col-span-2">
                    <label for="cost" class="block text-sm font-medium text-gray-700">Cost ($)</label>
                    <div class="mt-1">
                        <input type="number" name="cost" id="cost" min="0" step="0.01" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>
                
                <!-- Start Date -->
                <div class="sm:col-span-2">
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <div class="mt-1">
                        <input type="date" name="start_date" id="start_date" required class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>
                
                <!-- End Date -->
                <div class="sm:col-span-2">
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <div class="mt-1">
                        <input type="date" name="end_date" id="end_date" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Leave blank if not completed yet.</p>
                </div>
            </div>
            
            <div class="mt-8 border-t border-gray-200 pt-5">
                <div class="flex justify-end">
                    <a href="index.php?page=admin&action=cars&subaction=view&id=<?= $car['car_id'] ?>" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Add Maintenance Record
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default start date to today
    document.getElementById('start_date').valueAsDate = new Date();
    
    // Status change handler
    document.getElementById('status').addEventListener('change', function() {
        const endDateField = document.getElementById('end_date');
        if (this.value === 'completed') {
            endDateField.valueAsDate = new Date();
            endDateField.required = true;
        } else {
            endDateField.required = false;
        }
    });
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        const startDate = new Date(document.getElementById('start_date').value);
        const endDateEl = document.getElementById('end_date');
        
        if (endDateEl.value) {
            const endDate = new Date(endDateEl.value);
            if (endDate < startDate) {
                event.preventDefault();
                alert('End date cannot be earlier than start date.');
            }
        }
    });
});
</script>

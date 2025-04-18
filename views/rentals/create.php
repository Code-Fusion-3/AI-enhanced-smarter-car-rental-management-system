<?php require 'views/layouts/header.php'; ?>

<div class="max-w-3xl mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold text-gray-900 mb-6">Book Your Rental</h2>
    
    <form action="index.php?page=rentals&action=create" method="POST" class="bg-white shadow-lg rounded-lg p-6">
        <input type="hidden" name="car_id" value="<?= $carId ?>">
        
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" required min="<?= date('Y-m-d') ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" name="end_date" required min="<?= date('Y-m-d') ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <button type="submit" 
                    class="w-full bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700">
                Confirm Booking
            </button>
        </div>
    </form>
</div>

<?php require 'views/layouts/footer.php'; ?>

<div class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Admin Sidebar -->
        <?php require 'views/admin/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 p-8">

<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="index.php?page=admin&action=promotions" class="text-blue-500 hover:text-blue-700 mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Edit Promotion</h1>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= $_SESSION['error'] ?></span>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="index.php?page=admin&action=promotions&subaction=edit&id=<?= $promotion['promotion_id'] ?>" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Promotion Code*</label>
                    <input type="text" id="code" name="code" required value="<?= htmlspecialchars($promotion['code']) ?>"
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                           placeholder="e.g., SUMMER2025">
                    <p class="mt-1 text-xs text-gray-500">Unique code that customers will use to apply the promotion</p>
                </div>

                <div>
                    <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <div class="flex items-center mt-2">
                        <input type="checkbox" id="is_active" name="is_active" <?= $promotion['is_active'] ? 'checked' : '' ?>
                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Active
                        </label>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description*</label>
                    <textarea id="description" name="description" rows="3" required
                              class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                              placeholder="Describe the promotion"><?= htmlspecialchars($promotion['description']) ?></textarea>
                    <p class="mt-1 text-xs text-gray-500">Provide details about the promotion that will be visible to customers</p>
                </div>

                <div>
                    <label for="discount_percentage" class="block text-sm font-medium text-gray-700 mb-1">Discount Percentage (%)</label>
                    <input type="number" id="discount_percentage" name="discount_percentage" min="0" max="100" step="0.01"
                           value="<?= $promotion['discount_percentage'] ? htmlspecialchars($promotion['discount_percentage']) : '' ?>"
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                           placeholder="e.g., 15.00">
                    <p class="mt-1 text-xs text-gray-500">Enter percentage discount (leave empty if using fixed amount)</p>
                </div>

                <div>
                    <label for="discount_amount" class="block text-sm font-medium text-gray-700 mb-1">Discount Amount ($)</label>
                    <input type="number" id="discount_amount" name="discount_amount" min="0" step="0.01"
                           value="<?= $promotion['discount_amount'] ? htmlspecialchars($promotion['discount_amount']) : '' ?>"
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                           placeholder="e.g., 25.00">
                    <p class="mt-1 text-xs text-gray-500">Enter fixed amount discount (leave empty if using percentage)</p>
                </div>

                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date*</label>
                    <input type="date" id="start_date" name="start_date" required value="<?= htmlspecialchars($promotion['start_date']) ?>"
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date*</label>
                    <input type="date" id="end_date" name="end_date" required value="<?= htmlspecialchars($promotion['end_date']) ?>"
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-3">
                <a href="index.php?page=admin&action=promotions" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    Update Promotion
                </button>
            </div>
        </form>
    </div>
</div>

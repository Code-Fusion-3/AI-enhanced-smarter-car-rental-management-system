<div class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Admin Sidebar -->
        <?php require 'views/admin/sidebar.php'; ?>
        <!-- Main Content -->
        <div class="flex-1 p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6"><?php echo isset($faq) ? 'Edit FAQ' : 'Add FAQ'; ?></h1>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <form method="post" action="" class="p-6">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Question</label>
                        <input type="text" name="question" class="w-full border rounded px-3 py-2" required
                            value="<?php echo isset($faq) ? htmlspecialchars($faq['question']) : ''; ?>">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Answer</label>
                        <textarea name="answer" class="w-full border rounded px-3 py-2" rows="5"
                            required><?php echo isset($faq) ? htmlspecialchars($faq['answer']) : ''; ?></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Keywords <span
                                class="text-gray-400 text-xs">(optional,
                                comma-separated)</span></label>
                        <input type="text" name="keywords" class="w-full border rounded px-3 py-2"
                            value="<?php echo isset($faq) ? htmlspecialchars($faq['keywords']) : ''; ?>">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Status</label>
                        <select name="active" class="w-full border rounded px-3 py-2">
                            <option value="1" <?php echo (!isset($faq) || $faq['active']) ? 'selected' : ''; ?>>Active
                            </option>
                            <option value="0" <?php echo (isset($faq) && !$faq['active']) ? 'selected' : ''; ?>>Inactive
                            </option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <a href="index.php?page=admin&action=admin_faqs"
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save
                            FAQ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
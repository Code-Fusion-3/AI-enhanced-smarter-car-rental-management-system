<div class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Admin Sidebar -->
        <?php require 'views/admin/sidebar.php'; ?>
        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="container mx-auto px-4 py-8">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Frequently Asked Questions</h1>
                    <a href="index.php?page=admin&action=admin_faqs&subaction=add"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                        Add FAQ
                    </a>
                </div>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <span class="block sm:inline"><?= $_SESSION['success'] ?></span>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?= $_SESSION['error'] ?></span>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <!-- FAQ Table -->
                <div class="bg-white rounded-lg shadow-md overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Question</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Keywords</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($faqs)): ?>
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No FAQs found.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($faqs as $faq): ?>
                                    <tr>
                                        <td class="px-6 py-4 text-gray-900 text-sm max-w-xl break-words">
                                            <?php echo htmlspecialchars($faq['question']); ?>
                                        </td>
                                        <td class="px-6 py-4 text-gray-700 text-xs max-w-xs break-words">
                                            <?php echo htmlspecialchars($faq['keywords']); ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?php echo $faq['active'] ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-600'; ?>">
                                                <?php echo $faq['active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <a href="index.php?page=admin&action=admin_faqs&subaction=edit&id=<?php echo $faq['id']; ?>"
                                                class="text-blue-600 hover:underline">Edit</a>
                                            <a href="index.php?page=admin&action=admin_faqs&subaction=delete&id=<?php echo $faq['id']; ?>"
                                                class="text-red-600 hover:underline"
                                                onclick="return confirm('Delete this FAQ?');">Delete</a>
                                            <a href="index.php?page=admin&action=admin_faqs&subaction=toggle&id=<?php echo $faq['id']; ?>"
                                                class="text-gray-600 hover:underline">
                                                <?php echo $faq['active'] ? 'Deactivate' : 'Activate'; ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
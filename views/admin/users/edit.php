
<div class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Admin Sidebar -->
        <?php require 'views/admin/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Edit User</h1>
                <p class="text-gray-600 mt-1">Update user information and permissions</p>
            </div>
            
            <!-- Back Button -->
            <div class="mb-6">
                <a href="index.php?page=admin&action=users" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back to Users List
                </a>
            </div>
            
            <!-- Edit User Form -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <form action="index.php?page=admin&action=users&subaction=update" method="POST" enctype="multipart/form-data" class="p-6">
                    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-6">
                            <h2 class="text-lg font-medium text-gray-900 border-b pb-2">Basic Information</h2>
                            
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                                <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                            
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                        
                        <!-- Additional Information -->
                        <div class="space-y-6">
                            <h2 class="text-lg font-medium text-gray-900 border-b pb-2">Additional Information</h2>
                            
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <textarea id="address" name="address" rows="3" 
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                            </div>
                            
                            <div>
                                <label for="driver_license" class="block text-sm font-medium text-gray-700">Driver's License</label>
                                <input type="text" id="driver_license" name="driver_license" value="<?= htmlspecialchars($user['driver_license'] ?? '') ?>" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                                <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="customer" <?= $user['role'] === 'customer' ? 'selected' : '' ?>>Customer</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrator</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Account Status</label>
                                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="active" <?= ($user['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="inactive" <?= ($user['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    <option value="suspended" <?= ($user['status'] ?? '') === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Profile Image -->
                    <div class="mt-6">
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Profile Image</h2>
                        
                        <div class="flex items-start space-x-6">
                            <div class="flex-shrink-0">
                                <?php if (!empty($user['profile_image'])): ?>
                                    <img src="<?= $user['profile_image'] ?>" alt="<?= htmlspecialchars($user['username']) ?>" class="h-24 w-24 object-cover rounded-md">
                                <?php else: ?>
                                    <div class="h-24 w-24 rounded-md bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold text-2xl">
                                            <?= strtoupper(substr($user['username'], 0, 1)) ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex-1">
                                <label for="profile_image" class="block text-sm font-medium text-gray-700">Upload New Image</label>
                                <input type="file" id="profile_image" name="profile_image" accept="image/*" 
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="mt-1 text-sm text-gray-500">JPG, PNG or GIF up to 2MB</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Password Reset Section -->
                    <div class="mt-6">
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Password Management</h2>
                        
                        <div class="bg-gray-50 p-4 rounded-md">
                            <p class="text-sm text-gray-600 mb-4">To reset the user's password, enter a new password below. Leave blank to keep the current password.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                                    <input type="password" id="new_password" name="new_password" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                
                                <div>
                                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                    <input type="password" id="confirm_password" name="confirm_password" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="index.php?page=admin&action=users" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Form validation for password fields
    document.querySelector('form').addEventListener('submit', function(e) {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        if (newPassword !== '' && newPassword !== confirmPassword) {
            e.preventDefault();
            alert('The passwords do not match. Please try again.');
        }
    });
</script>

<?php require 'views/layouts/footer.php'; ?>
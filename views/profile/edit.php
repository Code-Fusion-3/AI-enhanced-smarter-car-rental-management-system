<?php require 'views/layouts/header.php'; ?>

<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row">
            <!-- Sidebar -->
            <div class="w-full md:w-1/4 mb-6 md:mb-0 md:pr-6">
                <?php require 'views/profile/sidebar.php'; ?>
            </div>
            
            <!-- Main Content -->
            <div class="w-full md:w-3/4">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline"><?= $_SESSION['error'] ?></span>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Edit Profile</h2>
                    
                    <form action="index.php?page=profile&action=update" method="POST" enctype="multipart/form-data">
                        <!-- Profile Image -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
                            <div class="flex items-center">
                                <?php if (!empty($userData['profile_image'])): ?>
                                    <div class="mr-4">
                                        <img src="<?= $userData['profile_image'] ?>" alt="Profile" class="w-24 h-24 rounded-full object-cover">
                                    </div>
                                <?php else: ?>
                                    <div class="mr-4">
                                        <div class="w-24 h-24 rounded-full bg-blue-500 flex items-center justify-center text-white text-3xl font-bold">
                                            <?= strtoupper(substr($userData['full_name'], 0, 1)) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="flex-1">
                                    <input type="file" name="profile_image" id="profile_image" class="block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100">
                                    <p class="mt-1 text-sm text-gray-500">JPG, PNG or GIF up to 2MB</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" name="full_name" id="full_name" value="<?= htmlspecialchars($userData['full_name']) ?>" required
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" name="email" id="email" value="<?= htmlspecialchars($userData['email']) ?>" required
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            
                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" name="phone" id="phone" value="<?= htmlspecialchars($userData['phone'] ?? '') ?>"
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            
                            <!-- Driver's License -->
                            <div>
                                <label for="driver_license" class="block text-sm font-medium text-gray-700 mb-1">Driver's License</label>
                                <input type="text" name="driver_license" id="driver_license" value="<?= htmlspecialchars($userData['driver_license'] ?? '') ?>"
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            
                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <textarea name="address" id="address" rows="3"
                                          class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"><?= htmlspecialchars($userData['address'] ?? '') ?></textarea>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                        <a href="index.php?page=profile" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3">
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
</div>

<?php require 'views/layouts/footer.php'; ?>

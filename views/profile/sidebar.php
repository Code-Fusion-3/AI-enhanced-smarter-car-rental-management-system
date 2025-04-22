<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 bg-blue-600 text-white">
        <h2 class="text-xl font-semibold">Account Dashboard</h2>
    </div>
    <nav class="p-4">
        <ul class="space-y-2">
            <li>
                <a href="index.php?page=profile" class="flex items-center px-4 py-2 text-gray-700 rounded-lg <?= !isset($_GET['action']) || $_GET['action'] === 'view' ? 'bg-blue-50 text-blue-700' : 'hover:bg-gray-50' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    Profile Overview
                </a>
            </li>
            <li>
                <a href="index.php?page=profile&action=edit" class="flex items-center px-4 py-2 text-gray-700 rounded-lg <?= isset($_GET['action']) && $_GET['action'] === 'edit' ? 'bg-blue-50 text-blue-700' : 'hover:bg-gray-50' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Edit Profile
                </a>
            </li>
            <li>
                <a href="index.php?page=profile&action=password" class="flex items-center px-4 py-2 text-gray-700 rounded-lg <?= isset($_GET['action']) && $_GET['action'] === 'password' ? 'bg-blue-50 text-blue-700' : 'hover:bg-gray-50' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                    Change Password
                </a>
            </li>
            <?php if(!isAdmin()): ?>
            <li>
                <a href="index.php?page=profile&action=favorites" class="flex items-center px-4 py-2 text-gray-700 rounded-lg <?= isset($_GET['action']) && $_GET['action'] === 'favorites' ? 'bg-blue-50 text-blue-700' : 'hover:bg-gray-50' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                    </svg>
                    My Favorites
                </a>
            </li>
            <?php endif; ?>
            <li class="border-t pt-2 mt-4">
                <a href="index.php?page=auth&action=logout" class="flex items-center px-4 py-2 text-red-600 rounded-lg hover:bg-red-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V7.414l-5-5H3zm7 5a1 1 0 10-2 0v4.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L12 12.586V8z" clip-rule="evenodd" />
                    </svg>
                    Logout
                </a>
            </li>
        </ul>
    </nav>
</div>
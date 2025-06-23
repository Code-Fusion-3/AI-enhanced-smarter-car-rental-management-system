<?php require 'views/layouts/header.php'; ?>

<div class="min-h-screen flex bg-gray-50">
    <!-- Left side - Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Welcome Back!</h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Sign in to access your AI-Enhanced car rental experience
                </p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">Invalid username or password. Please try again.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['registered'])): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">Registration successful! Please log in with your new account.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['logout'])): ?>
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-md shadow" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 7a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">You have been successfully logged out. Come back soon!</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form class="mt-8 space-y-6 bg-white p-8 rounded-xl shadow-md" action="index.php?page=auth&action=login"
                method="POST">
                <div class="rounded-md -space-y-px">
                    <div class="mb-5">
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input id="username" name="username" type="text" required
                                class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                                placeholder="Enter your username">
                        </div>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" required
                                class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                                placeholder="Enter your password">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                            Forgot your password?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:scale-105">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                        Sign in
                    </button>
                </div>
            </form>
            <div class="text-center mt-4">
                <p class="text-sm text-gray-600">Don't have an account?</p>
                <a href="index.php?page=auth&action=register"
                    class="font-medium text-blue-600 hover:text-blue-500 inline-flex items-center mt-1">
                    Create an account
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Right side - Benefits -->
    <div class="hidden lg:block lg:w-1/2 bg-blue-600 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-blue-700 opacity-90"></div>
        <div class="relative h-full flex flex-col justify-center px-12 py-12 z-10">
            <h2 class="text-3xl font-bold text-white mb-6">Experience Smart Car Rental</h2>

            <div class="space-y-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-blue-400 bg-opacity-30 rounded-full p-2">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-semibold text-white">AI-Enhanced Recommendations</h3>
                        <p class="mt-1 text-blue-100">Get personalized car suggestions based on your preferences and
                            past rentals.</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-blue-400 bg-opacity-30 rounded-full p-2">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-semibold text-white">Dynamic Pricing</h3>
                        <p class="mt-1 text-blue-100">Enjoy fair prices adjusted in real-time based on demand and
                            availability.</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-blue-400 bg-opacity-30 rounded-full p-2">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-semibold text-white">24/7 AI Assistant</h3>
                        <p class="mt-1 text-blue-100">Get instant answers and support through our intelligent chat
                            assistant.</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-blue-400 bg-opacity-30 rounded-full p-2">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-semibold text-white">Secure Booking</h3>
                        <p class="mt-1 text-blue-100">Your data and payments are protected with enterprise-grade
                            security.</p>
                    </div>
                </div>
            </div>

            <div class="mt-10">
                <div class="bg-white bg-opacity-10 rounded-lg p-6 backdrop-filter backdrop-blur-sm">
                    <div class="flex items-start">
                        <svg class="h-8 w-8 text-yellow-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M8.128 19.825a1.586 1.586 0 0 1-1.643-.117 1.543 1.543 0 0 1-.53-1.467l.777-4.54-3.29-3.207a1.56 1.56 0 0 1-.376-1.59 1.56 1.56 0 0 1 1.26-1.06l4.55-.663 2.035-4.12a1.56 1.56 0 0 1 2.792 0l2.035 4.12 4.55.663a1.56 1.56 0 0 1 1.26 1.06 1.56 1.56 0 0 1-.375 1.59l-3.29 3.207.777 4.54a1.56 1.56 0 0 1-.53 1.467 1.586 1.586 0 0 1-1.643.117l-4.073-2.14-4.073 2.14z" />
                        </svg>
                        <div class="ml-4">
                            <p class="text-white italic">"The AI assistant made finding the right car so easy! It
                                recommended the perfect SUV for our family trip and even applied a discount
                                automatically."</p>
                            <p class="mt-2 text-blue-200 font-medium">— John D., Verified Customer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Decorative elements -->
        <div class="absolute bottom-0 right-0 transform translate-x-1/4 translate-y-1/4">
            <svg class="text-blue-400 opacity-20" width="400" height="400" viewBox="0 0 56 56"
                xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                <path
                    d="M28 0C12.536 0 0 12.536 0 28C0 43.464 12.536 56 28 56C43.464 56 56 43.464 56 28C56 12.536 43.464 0 28 0ZM28 50.4C15.624 50.4 5.6 40.376 5.6 28C5.6 15.624 15.624 5.6 28 5.6C40.376 5.6 50.4 15.624 50.4 28C50.4 40.376 40.376 50.4 28 50.4Z" />
                <path
                    d="M28 11.2C28 9.856 29.12 8.4 30.8 8.4C38.08 8.4 44.8 15.12 44.8 22.4C44.8 24.08 43.344 25.2 42 25.2C40.656 25.2 39.2 24.08 39.2 22.4C39.2 18.256 35.84 14.896 31.696 14.896C30.016 14 28.896 12.544 28.896 11.2H28Z" />
            </svg>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
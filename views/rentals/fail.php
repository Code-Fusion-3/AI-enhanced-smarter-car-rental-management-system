
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Failed</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-red-50 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-lg p-8 max-w-md w-full text-center">
        <svg class="mx-auto mb-4 h-16 w-16 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        <h2 class="text-2xl font-bold text-red-700 mb-2">Payment Failed</h2>
        <p class="text-lg text-gray-700 mb-6"><?= htmlspecialchars($error ?? 'An error occurred.') ?></p>
        <a href="index.php?page=payments&action=pay&rental_id=<?= htmlspecialchars($rental_id ?? '') ?>"
           class="inline-block px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition font-semibold">
            Try Again
        </a>
    </div>
</body>
</html>
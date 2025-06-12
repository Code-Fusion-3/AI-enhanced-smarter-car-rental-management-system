
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-50 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-lg p-8 max-w-md w-full text-center">
        <svg class="mx-auto mb-4 h-16 w-16 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        <h2 class="text-2xl font-bold text-green-700 mb-2">Payment Successful!</h2>
        <p class="text-lg text-gray-700 mb-6">Your payment was processed and your rental is now active.</p>
        <a href="index.php?page=rentals&action=view&id=<?= htmlspecialchars($rental_id ?? '') ?>"
           class="inline-block px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition font-semibold">
            View Rental
        </a>
    </div>
</body>
</html>
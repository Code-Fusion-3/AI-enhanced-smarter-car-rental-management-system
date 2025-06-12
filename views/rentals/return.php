<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Return Car</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-lg p-8 max-w-md w-full">
        <h2 class="text-2xl font-bold text-gray-800 mb-2 text-center">Return Car</h2>
        <form action="index.php?page=rentals&action=return" method="POST" class="space-y-4">
            <input type="hidden" name="rental_id" value="<?= htmlspecialchars($rental['rental_id']) ?>">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Return Condition</label>
                <textarea name="return_condition" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" placeholder="Describe the car's condition..." required></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Additional Charges (if any)</label>
                <input type="number" step="0.01" name="additional_charges" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" placeholder="0.00">
            </div>
            <div class="flex justify-between mt-6">
                <a href="index.php?page=rentals&action=view&id=<?= htmlspecialchars($rental['rental_id']) ?>"
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition font-semibold">
                    Submit Return
                </button>
            </div>
        </form>
    </div>
</body>
</html>
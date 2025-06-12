<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Write a Review</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-lg p-8 max-w-md w-full">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">Write a Review for Rental #<?= htmlspecialchars($rental['rental_id']) ?></h2>
        <form action="index.php?page=rentals&action=review" method="POST" class="space-y-4">
            <input type="hidden" name="rental_id" value="<?= htmlspecialchars($rental['rental_id']) ?>">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                <div class="flex items-center space-x-1" id="star-rating">
                    <?php for($i=1; $i<=5; $i++): ?>
                        <button type="button" class="star focus:outline-none" data-value="<?= $i ?>">
                            <svg class="h-8 w-8 text-gray-300 hover:text-yellow-400 transition" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </button>
                    <?php endfor; ?>
                </div>
                <input type="hidden" name="rating" id="rating-value" required>
                <div id="rating-error" class="text-red-500 text-xs mt-1 hidden">Please select a rating.</div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Feedback</label>
                <textarea name="feedback" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" placeholder="Share your experience..." required></textarea>
            </div>
            <div class="flex justify-between mt-6">
                <a href="index.php?page=rentals&action=view&id=<?= htmlspecialchars($rental['rental_id']) ?>"
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition font-semibold">
                    Submit Review
                </button>
            </div>
        </form>
    </div>
</body>
</html>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('#star-rating .star');
    const ratingInput = document.getElementById('rating-value');
    const errorDiv = document.getElementById('rating-error');
    let selected = 0;

    stars.forEach((star, idx) => {
        star.addEventListener('click', function() {
            selected = idx + 1;
            ratingInput.value = selected;
            errorDiv.classList.add('hidden');
            stars.forEach((s, i) => {
                s.querySelector('svg').classList.toggle('text-yellow-400', i < selected);
                s.querySelector('svg').classList.toggle('text-gray-300', i >= selected);
            });
        });
    });

    // Prevent form submit if no rating
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!ratingInput.value) {
            e.preventDefault();
            errorDiv.classList.remove('hidden');
        }
    });
});
</script>
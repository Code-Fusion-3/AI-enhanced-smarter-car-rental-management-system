<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pay for Rental Using Stripe Pay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-lg p-8 max-w-md w-full">
        <h2 class="text-2xl font-bold text-gray-800 mb-2 text-center">Pay for Rental Stripe
            Pay</h2>
        <p class="text-lg text-gray-600 mb-6 text-center">Total: <span class="font-semibold text-green-600">RWF
                <?= number_format($rental['total_cost'], 2) ?></span></p>
        <form action="index.php?page=payments&action=process" method="POST" id="payment-form" class="space-y-4">
            <input type="hidden" name="rental_id" value="<?= $rental['rental_id'] ?>">
            <!-- Payment Method Selector -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Payment Method</label>
                <div class="flex items-center space-x-6">
                    <label class="inline-flex items-center">
                        <input type="radio" name="payment_method" value="stripe" checked>
                        <span class="ml-2">Pay with Card (Stripe)</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="payment_method" value="paypack">
                        <span class="ml-2">Pay with Mobile Money (MTN/Airtel)</span>
                    </label>
                </div>
            </div>
            <!-- Mobile Money Phone Input -->
            <div id="paypack-phone-section" style="display:none;">
                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                    Phone Number for Mobile Money
                </label>
                <input type="tel" id="phone_number" name="phone_number" class="form-input w-full"
                    placeholder="0781234567" pattern="[0-9]{10}" maxlength="10">
                <div class="text-xs text-gray-500 mt-2">
                    Enter your 10-digit Rwandan number (e.g., 0781234567 for MTN, 0721234567 for Airtel)
                </div>
            </div>
            <!-- Stripe Card Details (only show if Stripe is selected) -->
            <div id="stripe-section">
                <label class="block text-sm font-medium text-gray-700 mb-1">Card Details</label>
                <div id="card-element" class="p-3 border border-gray-300 rounded-md bg-gray-100"></div>
                <div id="card-errors" class="text-red-500 text-sm mt-2" role="alert"></div>
            </div>
            <div class="flex justify-between mt-6">
                <a href="index.php?page=rentals&action=view&id=<?= $rental['rental_id'] ?>"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">Cancel</a>
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition font-semibold">
                    Pay Now
                </button>
            </div>
        </form>
    </div>
    <script>
    var stripe = Stripe(
        'pk_test_51RZ8geB791T8Zvw8a8NNrgAAUEoyD2UvfVkt7uBHz4rVfg3j2kcXD893Jd7GcIk0y3QBmO6phQFnhMMfzQ96L9pp00LX5JJznF'
    ); // Replace with your Stripe publishable key
    var elements = stripe.elements();
    var card = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
                '::placeholder': {
                    color: '#a0aec0'
                }
            }
        }
    });
    card.mount('#card-element');
    var form = document.getElementById('payment-form');
    // Show/hide payment sections based on method
    document.addEventListener('DOMContentLoaded', function() {
        const methodRadios = document.querySelectorAll('input[name="payment_method"]');
        const paypackSection = document.getElementById('paypack-phone-section');
        const stripeSection = document.getElementById('stripe-section');

        function updateSections() {
            const selected = document.querySelector('input[name="payment_method"]:checked').value;
            if (selected === 'paypack') {
                paypackSection.style.display = '';
                stripeSection.style.display = 'none';
            } else {
                paypackSection.style.display = 'none';
                stripeSection.style.display = '';
            }
        }
        methodRadios.forEach(radio => {
            radio.addEventListener('change', updateSections);
        });
        updateSections();
    });
    // Only submit Stripe token if Stripe is selected
    form.addEventListener('submit', function(event) {
        const selected = document.querySelector('input[name="payment_method"]:checked').value;
        if (selected === 'stripe') {
            event.preventDefault();
            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    document.getElementById('card-errors').textContent = result.error.message;
                } else {
                    var hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'stripeToken');
                    hiddenInput.setAttribute('value', result.token.id);
                    form.appendChild(hiddenInput);
                    form.submit();
                }
            });
        }
        // else, allow normal form submit for mobile money
    });
    </script>
</body>

</html>
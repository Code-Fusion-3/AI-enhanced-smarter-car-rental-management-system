<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment Pending</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-yellow-50 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-lg p-8 max-w-full w-1/2 text-center">
        <!-- Payment Summary -->
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="text-2xl font-bold text-blue-800 mb-1">
                RWF <?= htmlspecialchars($transaction['amount'] ?? '0') ?>
            </div>
            <div class="text-sm text-blue-600">
                Mobile Money Payment
            </div>
            <div class="text-xs text-blue-500 mt-1">
                Transaction #<?= htmlspecialchars($transaction['transaction_id'] ?? 'N/A') ?>
            </div>
        </div>

        <div id="loading-spinner"
            class="animate-spin mx-auto mb-4 h-16 w-16 border-4 border-yellow-500 border-t-transparent rounded-full">
        </div>
        <div id="success-icon" class="hidden mx-auto mb-4 h-16 w-16 text-green-500">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <div id="failed-icon" class="hidden mx-auto mb-4 h-16 w-16 text-red-500">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>

        <h2 id="main-title" class="text-2xl font-bold text-yellow-700 mb-2">Payment Pending</h2>
        <p id="main-message" class="text-lg text-gray-700 mb-4">Please check your phone and approve the payment request.
        </p>
        <p id="sub-message" class="text-sm text-gray-600 mb-6">We're checking your payment status...</p>

        <!-- Status Indicator -->
        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse mr-3"></div>
                    <span class="font-medium text-yellow-800">Payment Status</span>
                </div>
                <span id="status-badge" class="px-3 py-1 bg-yellow-500 text-white text-xs rounded-full font-medium">
                    Pending
                </span>
            </div>
            <div id="status-details" class="mt-2 text-sm text-yellow-700">
                Waiting for mobile money confirmation...
            </div>
        </div>

        <div class="space-y-3">
            <button id="check-btn" onclick="checkStatus()"
                class="w-full px-6 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition font-semibold">
                Check Status
            </button>
            <button onclick="testManualComplete()"
                class="w-full px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition font-semibold">
                Test Manual Complete
            </button>
            <a href="index.php?page=payments&action=pay&rental_id=<?= htmlspecialchars($transaction['rental_id'] ?? '') ?>"
                class="inline-block w-full px-6 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition font-semibold">
                Try Different Payment
            </a>
            <a id="view-rental-btn"
                href="index.php?page=rentals&action=view&id=<?= htmlspecialchars($transaction['rental_id'] ?? '') ?>"
                class="hidden inline-block w-full px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition font-semibold">
                View Rental
            </a>
        </div>

        <div id="status-message" class="mt-4 text-sm"></div>

        <!-- Progress indicator -->
        <div id="progress-container" class="mt-6">
            <div class="text-xs text-gray-500 mb-2">Auto-checking: <span id="check-counter">0</span> times</div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div id="progress-bar" class="bg-yellow-600 h-2 rounded-full transition-all duration-1000"
                    style="width: 0%"></div>
            </div>
            <div class="text-xs text-gray-500 mt-1">Will stop checking after 5 minutes</div>
        </div>

        <!-- Debug info -->
        <div class="mt-4 text-xs border-t pt-4">
            <div class="bg-gray-50 p-3 rounded-lg">
                <h4 class="font-semibold text-gray-700 mb-2">Payment Details</h4>
                <div class="grid grid-cols-2 gap-2 text-gray-600">
                    <div><span class="font-medium">Transaction ID:</span>
                        <?= htmlspecialchars($transaction['transaction_id'] ?? 'N/A') ?></div>
                    <div><span class="font-medium">Amount:</span> RWF
                        <?= htmlspecialchars($transaction['amount'] ?? 'N/A') ?>
                    </div>
                    <div><span class="font-medium">Gateway Ref:</span>
                        <?= htmlspecialchars($transaction['gateway_reference'] ?? 'N/A') ?></div>
                    <div><span class="font-medium">Created:</span>
                        <?= htmlspecialchars($transaction['created_at'] ?? 'N/A') ?></div>
                </div>

                <div class="mt-3 pt-3 border-t border-gray-200 hidden">
                    <h4 class="font-semibold text-gray-700 mb-2">Status Check Log</h4>
                    <div id="last-check" class="text-yellow-600 font-medium mb-1"></div>
                    <div id="debug-info"
                        class="text-blue-600 font-mono text-xs bg-blue-50 p-2 rounded border-l-2 border-blue-300 max-h-32 overflow-y-auto">
                    </div>
                </div>

                <div class="mt-3 pt-3 border-t border-gray-200 hidden">
                    <h4 class="font-semibold text-gray-700 mb-2">Quick Actions</h4>
                    <div class="flex gap-2">
                        <button onclick="checkStatus()"
                            class="px-3 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                            Check Now
                        </button>
                        <button onclick="testManualComplete()"
                            class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
                            Test Complete
                        </button>
                        <button onclick="toggleDebug()"
                            class="px-3 py-1 bg-gray-500 text-white text-xs rounded hover:bg-gray-600">
                            Toggle Debug
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    const transactionId = <?= json_encode($transaction['transaction_id'] ?? '') ?>;
    const rentalId = <?= json_encode($transaction['rental_id'] ?? '') ?>;
    let checkInterval;
    let checkCount = 0;
    const maxChecks = 30; // 5 minutes with 10-second intervals
    let startTime = Date.now();
    let debugLog = [];
    let debugVisible = true;

    function addDebugLog(message, type = 'info') {
        const timestamp = new Date().toLocaleTimeString();
        const logEntry = `[${timestamp}] ${message}`;
        debugLog.push({
            message: logEntry,
            type
        });

        // Keep only last 20 entries
        if (debugLog.length > 20) {
            debugLog.shift();
        }

        updateDebugDisplay();
    }

    function updateDebugDisplay() {
        if (!debugVisible) {
            document.getElementById('debug-info').innerHTML =
                '<em>Debug info hidden. Click "Toggle Debug" to show.</em>';
            return;
        }

        const debugDiv = document.getElementById('debug-info');
        const html = debugLog.map(entry => {
            const color = entry.type === 'error' ? 'text-red-600' :
                entry.type === 'success' ? 'text-green-600' :
                entry.type === 'warning' ? 'text-yellow-600' : 'text-blue-600';
            return `<div class="${color}">${entry.message}</div>`;
        }).join('');
        debugDiv.innerHTML = html;
        debugDiv.scrollTop = debugDiv.scrollHeight;
    }

    function toggleDebug() {
        debugVisible = !debugVisible;
        updateDebugDisplay();
    }

    function updateProgress() {
        const progress = (checkCount / maxChecks) * 100;
        document.getElementById('progress-bar').style.width = progress + '%';
        document.getElementById('check-counter').textContent = checkCount;
    }

    function updateUI(status, title, message, showSuccess = false, showFailed = false) {
        document.getElementById('main-title').textContent = title;
        document.getElementById('main-message').textContent = message;

        // Update icons
        document.getElementById('loading-spinner').classList.toggle('hidden', showSuccess || showFailed);
        document.getElementById('success-icon').classList.toggle('hidden', !showSuccess);
        document.getElementById('failed-icon').classList.toggle('hidden', !showFailed);

        // Update title color
        const titleEl = document.getElementById('main-title');
        titleEl.className = `text-2xl font-bold mb-2 ${showSuccess ? 'text-green-700' :
                showFailed ? 'text-red-700' :
                    'text-yellow-700'
                }`;

        // Update status indicator
        const statusContainer = document.querySelector('.bg-yellow-50');
        const statusBadge = document.getElementById('status-badge');
        const statusDetails = document.getElementById('status-details');
        const statusDot = document.querySelector('.w-3.h-3');

        if (showSuccess) {
            statusContainer.className = 'mb-6 p-4 bg-green-50 border border-green-200 rounded-lg';
            statusBadge.className = 'px-3 py-1 bg-green-500 text-white text-xs rounded-full font-medium';
            statusBadge.textContent = 'Completed';
            statusDetails.className = 'mt-2 text-sm text-green-700';
            statusDetails.textContent = 'Payment processed successfully!';
            statusDot.className = 'w-3 h-3 bg-green-500 rounded-full mr-3';
        } else if (showFailed) {
            statusContainer.className = 'mb-6 p-4 bg-red-50 border border-red-200 rounded-lg';
            statusBadge.className = 'px-3 py-1 bg-red-500 text-white text-xs rounded-full font-medium';
            statusBadge.textContent = 'Failed';
            statusDetails.className = 'mt-2 text-sm text-red-700';
            statusDetails.textContent = 'Payment could not be processed.';
            statusDot.className = 'w-3 h-3 bg-red-500 rounded-full mr-3';
        } else {
            statusContainer.className = 'mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg';
            statusBadge.className = 'px-3 py-1 bg-yellow-500 text-white text-xs rounded-full font-medium';
            statusBadge.textContent = 'Pending';
            statusDetails.className = 'mt-2 text-sm text-yellow-700';
            statusDetails.textContent = 'Waiting for mobile money confirmation...';
            statusDot.className = 'w-3 h-3 bg-yellow-500 rounded-full animate-pulse mr-3';
        }

        // Show/hide view rental button
        document.getElementById('view-rental-btn').classList.toggle('hidden', !showSuccess);
    }

    function checkStatus() {
        checkCount++;
        updateProgress();

        const now = new Date();
        document.getElementById('last-check').textContent = `Last check: ${now.toLocaleTimeString()}`;

        addDebugLog(`Starting status check #${checkCount} for transaction ${transactionId}`, 'info');

        fetch(`index.php?page=payments&action=checkStatus&transaction_id=${transactionId}`)
            .then(response => {
                addDebugLog(`HTTP Response: ${response.status} ${response.statusText}`, 'info');
                return response.text();
            })
            .then(text => {
                addDebugLog(`Raw response length: ${text.length} characters`, 'info');
                try {
                    return JSON.parse(text);
                } catch (e) {
                    addDebugLog(`JSON Parse Error: ${e.message}`, 'error');
                    addDebugLog(`Response preview: ${text.substring(0, 200)}...`, 'warning');
                    throw new Error('Invalid JSON response: ' + text);
                }
            })
            .then(data => {
                addDebugLog(`Status check result: ${JSON.stringify(data)}`, 'info');

                if (data.success && data.status_updated) {
                    clearInterval(checkInterval);
                    addDebugLog('Status updated successfully!', 'success');

                    if (data.redirect === 'success') {
                        updateUI('completed', 'Payment Successful!',
                            'Your payment has been processed successfully.', true, false);
                        document.getElementById('status-message').innerHTML =
                            `<p class="text-green-600 font-semibold">✅ Payment completed! Redirecting...</p>`;

                        addDebugLog('Payment completed - redirecting to rental view', 'success');

                        setTimeout(() => {
                            window.location.href = `index.php?page=rentals&action=view&id=${rentalId}`;
                        }, 2000);

                    } else if (data.redirect === 'failed') {
                        updateUI('failed', 'Payment Failed', 'Your payment could not be processed.', false, true);
                        document.getElementById('status-message').innerHTML =
                            `<p class="text-red-600 font-semibold">❌ Payment failed. Please try again.</p>`;
                        addDebugLog('Payment failed', 'error');
                    }
                } else if (data.success) {
                    document.getElementById('status-message').innerHTML =
                        `<p class="text-yellow-600">${data.message}</p>`;
                    document.getElementById('sub-message').textContent = data.message;
                    addDebugLog(`Status: ${data.message}`, 'info');
                } else {
                    document.getElementById('status-message').innerHTML =
                        `<p class="text-red-600">Error: ${data.message}</p>`;
                    addDebugLog(`Error: ${data.message}`, 'error');
                }
            })
            .catch(error => {
                addDebugLog(`Network/Processing Error: ${error.message}`, 'error');
                document.getElementById('status-message').innerHTML =
                    `<p class="text-red-600">Network error: ${error.message}</p>`;
            });
    }

    function testManualComplete() {
        addDebugLog('Testing manual completion...', 'info');
        fetch(`index.php?page=payments&action=manualComplete&transaction_id=${transactionId}`)
            .then(response => response.text())
            .then(text => {
                addDebugLog(`Manual complete result: ${text.substring(0, 200)}...`, 'success');
                console.log('Manual complete result:', text);
            })
            .catch(error => {
                addDebugLog(`Manual complete error: ${error.message}`, 'error');
                console.error('Manual complete error:', error);
            });
    }

    // Auto-check status every 10 seconds
    checkInterval = setInterval(checkStatus, 10000);

    // Stop checking after 5 minutes
    setTimeout(() => {
        clearInterval(checkInterval);
        updateUI('timeout', 'Payment Check Timeout',
            'We stopped checking automatically. Please check manually or try a different payment method.');
        document.getElementById('status-message').innerHTML =
            '<p class="text-gray-600">⏰ Auto-checking stopped after 5 minutes. The payment may still be processing.</p>';
        document.getElementById('progress-bar').style.width = '100%';
        document.getElementById('progress-bar').classList.add('bg-gray-400');
        addDebugLog('Auto-checking stopped after 5 minutes', 'warning');
    }, 300000); // 5 minutes

    // Initial status check after 3 seconds
    setTimeout(() => {
        addDebugLog('Starting initial status check...', 'info');
        checkStatus();
    }, 3000);
    </script>
</body>

</html>
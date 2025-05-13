<?php require 'views/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back button -->
        <div class="mb-6">
            <a href="index.php?page=rentals" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Rentals
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Rental Status Banner -->
            <div class="<?php 
                switch($rental['status']) {
                    case 'pending': echo 'bg-yellow-500'; break;
                    case 'approved': echo 'bg-blue-600'; break;
                    case 'active': echo 'bg-green-600'; break;
                    case 'completed': echo 'bg-gray-600'; break;
                    case 'cancelled': echo 'bg-red-600'; break;
                    default: echo 'bg-gray-600';
                }
            ?> text-white px-6 py-3">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <?php if($rental['status'] === 'active'): ?>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <?php elseif($rental['status'] === 'pending'): ?>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <?php elseif($rental['status'] === 'cancelled'): ?>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <?php elseif($rental['status'] === 'completed'): ?>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            <?php else: ?>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <?php endif; ?>
                        </svg>
                        <h2 class="text-xl font-bold">Rental #<?= $rental['rental_id'] ?> - <?= ucfirst($rental['status']) ?></h2>
                    </div>
                    <div>
                        <span class="text-sm">Created: <?= date('M d, Y', strtotime($rental['created_at'])) ?></span>
                    </div>
                </div>
            </div>

            <!-- Vehicle Information -->
            <div class="p-6 border-b">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/3 mb-6 md:mb-0">
                        <img src="<?= $rental['image_url'] ?? 'assets/images/default-car.jpg' ?>" 
                             alt="<?= $rental['make'] ?> <?= $rental['model'] ?>" 
                             class="w-full h-48 object-cover rounded-lg">
                    </div>
                    <div class="md:w-2/3 md:pl-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2"><?= $rental['make'] ?> <?= $rental['model'] ?> <?= $rental['created_at'] ?></h3>
                        
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Registration</h4>
                                <p class="text-lg text-gray-900"><?= $rental['registration_number'] ?></p>
                            </div>
                            
                            <?php if(!empty($rental['category_name'])): ?>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Category</h4>
                                <p class="text-lg text-gray-900"><?= $rental['category_name'] ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if(!empty($rental['fuel_type'])): ?>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Fuel Type</h4>
                                <p class="text-lg text-gray-900"><?= ucfirst($rental['fuel_type']) ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if(!empty($rental['transmission'])): ?>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Transmission</h4>
                                <p class="text-lg text-gray-900"><?= ucfirst($rental['transmission']) ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if(!empty($rental['features'])): ?>
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-500">Features</h4>
                            <p class="text-gray-700"><?= $rental['features'] ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Rental Details -->
            <div class="p-6 border-b">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Rental Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500">Rental Period</h4>
                        <div class="flex items-center mt-1">
                            <svg class="h-5 w-5 text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="text-gray-900 font-medium"><?= date('M d, Y', strtotime($rental['start_date'])) ?> - <?= date('M d, Y', strtotime($rental['end_date'])) ?></p>
                                <?php
                                $start = new DateTime($rental['start_date']);
                                $end = new DateTime($rental['end_date']);
                                $days = $end->diff($start)->days + 1;
                                ?>
                                <p class="text-sm text-gray-500"><?= $days ?> day<?= $days > 1 ? 's' : '' ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <?php if(!empty($rental['pickup_location'])): ?>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500">Pickup Location</h4>
                        <div class="flex items-center mt-1">
                            <svg class="h-5 w-5 text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-gray-900"><?= htmlspecialchars($rental['pickup_location']) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($rental['return_location'])): ?>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500">Return Location</h4>
                        <div class="flex items-center mt-1">
                            <svg class="h-5 w-5 text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-gray-900"><?= htmlspecialchars($rental['return_location']) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="p-6 border-b">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Payment Information</h3>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex flex-col md:flex-row justify-between">
                        <div>
                            <div class="flex items-center mb-2">
                                <h4 class="text-sm font-medium text-gray-500 mr-2">Daily Rate:</h4>
                                <p class="text-gray-900">$<?= number_format($rental['daily_rate'], 2) ?>/day</p>
                            </div>
                            
                            <div class="flex items-center mb-2">
                                <h4 class="text-sm font-medium text-gray-500 mr-2">Rental Duration:</h4>
                                <p class="text-gray-900"><?= $days ?> day<?= $days > 1 ? 's' : '' ?></p>
                            </div>
                            
                            <?php if(!empty($rental['promo_code'])): ?>
                            <div class="flex items-center mb-2">
                                <h4 class="text-sm font-medium text-gray-500 mr-2">Promotion Applied:</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <?= $rental['promo_code'] ?>
                                    <?php if(!empty($rental['discount_percentage'])): ?>
                                        (<?= $rental['discount_percentage'] ?>% off)
                                    <?php elseif(!empty($rental['discount_amount'])): ?>
                                        ($<?= number_format($rental['discount_amount'], 2) ?> off)
                                    <?php endif; ?>
                                </span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if(!empty($rental['discount_amount']) && $rental['discount_amount'] > 0): ?>
                            <div class="flex items-center mb-2">
                                <h4 class="text-sm font-medium text-gray-500 mr-2">Discount:</h4>
                                <p class="text-green-600">-$<?= number_format($rental['discount_amount'], 2) ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if(!empty($rental['additional_charges']) && $rental['additional_charges'] > 0): ?>
                            <div class="flex items-center mb-2">
                                <h4 class="text-sm font-medium text-gray-500 mr-2">Additional Charges:</h4>
                                <p class="text-red-600">+$<?= number_format($rental['additional_charges'], 2) ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mt-4 md:mt-0 md:text-right">
                            <h4 class="text-sm font-medium text-gray-500">Total Cost</h4>
                            <p class="text-2xl font-bold text-blue-600">$<?= number_format($rental['total_cost'], 2) ?></p>
                            
                           
                            
                            <?php if($payment): ?>
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        <?php 
                                        switch($payment['status']) {
                                            case 'completed': echo 'bg-green-100 text-green-800'; break;
                                            case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'failed': echo 'bg-red-100 text-red-800'; break;
                                            case 'refunded': echo 'bg-purple-100 text-purple-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        Payment: <?= ucfirst($payment['status']) ?>
                                        <?php if($payment['status'] === 'completed'): ?>
                                            <svg class="ml-1 h-3 w-3 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        <?php endif; ?>
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <?= date('M d, Y g:i A', strtotime($payment['payment_date'])) ?> 
                                        <?php if(!empty($payment['transaction_id'])): ?>
                                            • Transaction ID: <?= $payment['transaction_id'] ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            <?php else: ?>
                                <?php if($rental['status'] === 'approved'): ?>
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Payment Required
                                        </span>
                                    </div>
                                <?php elseif($rental['status'] === 'pending'): ?>
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Awaiting Approval
                                        </span>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Notes Section (if any) -->
            <?php if(!empty($rental['notes'])): ?>
            <div class="p-6 border-b">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Notes</h3>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <p class="text-gray-700"><?= nl2br(htmlspecialchars($rental['notes'])) ?></p>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Rental History (if completed) -->
            <?php if($rental['status'] === 'completed'): 
                $historyQuery = "SELECT * FROM rental_history WHERE rental_id = ?";
                $stmt = $conn->prepare($historyQuery);
                $stmt->bind_param("i", $rental['rental_id']);
                $stmt->execute();
                $historyResult = $stmt->get_result();
                $history = $historyResult->fetch_assoc();
            ?>
                <?php if($history): ?>
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Rental History</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php if(!empty($history['return_date'])): ?>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500">Return Date</h4>
                            <p class="text-gray-900 mt-1"><?= date('M d, Y g:i A', strtotime($history['return_date'])) ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($history['return_condition'])): ?>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500">Return Condition</h4>
                            <p class="text-gray-900 mt-1"><?= htmlspecialchars($history['return_condition']) ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($history['additional_charges']) && $history['additional_charges'] > 0): ?>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500">Additional Charges</h4>
                            <p class="text-red-600 font-medium mt-1">$<?= number_format($history['additional_charges'], 2) ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($history['rating'])): ?>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500">Your Rating</h4>
                            <div class="flex text-yellow-400 mt-1">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <?php if($i <= $history['rating']): ?>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    <?php else: ?>
                                        <svg class="h-5 w-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if(!empty($history['feedback'])): ?>
                    <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500">Your Feedback</h4>
                        <p class="text-gray-700 mt-1"><?= nl2br(htmlspecialchars($history['feedback'])) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>
            
            <!-- Action Buttons -->
            

            <!-- Action Buttons -->
            <div class="p-6 flex flex-wrap gap-3 justify-end">
                <?php 
                // Calculate if rental is overdue
                $today = new DateTime();
                $endDate = new DateTime($rental['end_date']);
                $isOverdue = $today > $endDate;
                
                // Different actions based on rental status
                switch($rental['status']) {
                    case 'pending':
                        // For pending rentals, allow cancellation
                        ?>
                        <a href="index.php?page=rentals&action=cancel&id=<?= $rental['rental_id'] ?>" 
                           onclick="return confirm('Are you sure you want to cancel this rental?');"
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Cancel Rental
                        </a>
                        <a href="index.php?page=rentals&action=edit&id=<?= $rental['rental_id'] ?>" 
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Edit Rental
                        </a>
                        <?php
                        break;
                        
                    case 'approved':
                        // For approved rentals, allow payment if not already paid
                        if(empty($payment) || $payment['status'] !== 'completed'):
                        ?>
                        <a href="index.php?page=payments&action=pay&rental_id=<?= $rental['rental_id'] ?>" 
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            Pay Now
                        </a>
                        <?php
                        endif;
                        
                        // Also allow cancellation for approved rentals
                        ?>
                        <a href="index.php?page=rentals&action=cancel&id=<?= $rental['rental_id'] ?>" 
                           onclick="return confirm('Are you sure you want to cancel this rental?');"
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Cancel Rental
                        </a>
                        <?php
                        break;
                        
                    case 'active':
                        // For active rentals, allow return and extend (if overdue)
                        ?>
                        <a href="index.php?page=rentals&action=return&id=<?= $rental['rental_id'] ?>" 
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Return Car
                        </a>
                        
                        <?php if($isOverdue): ?>
                        <a href="index.php?page=rentals&action=extend&id=<?= $rental['rental_id'] ?>" 
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 001.415-1.414L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            Extend Rental
                        </a>
                        <?php endif; ?>
                        
                        <?php if(!$isOverdue): ?>
                        <a href="index.php?page=rentals&action=extend&id=<?= $rental['rental_id'] ?>" 
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            Extend Rental
                        </a>
                        <?php endif; ?>
                        <?php
                        break;
                        
                    case 'completed':
                        // For completed rentals, allow review if not already reviewed
                        if(empty($history) || empty($history['rating'])):
                        ?>
                        <a href="index.php?page=rentals&action=review&id=<?= $rental['rental_id'] ?>" 
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            Write Review
                        </a>
                        <?php 
                        elseif(!empty($history) && !empty($history['rating'])): 
                        ?>
                        <a href="index.php?page=rentals&action=review&id=<?= $rental['rental_id'] ?>" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Edit Review
                        </a>
                        <?php 
                        endif;
                        break;
                        
                    case 'cancelled':
                        // No special actions for cancelled rentals
                        break;
                }
                ?>
                
                <!-- Always show Rent Again button for any status -->
                <a href="index.php?page=cars&action=view&id=<?= $rental['car_id'] ?>" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                    </svg>
                    Rent Again
                </a>
                
                 <!-- Always show Back to Rentals button -->
                 <a href="index.php?page=rentals" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back to Rentals
                </a>
            </div>
        </div>
        
        <!-- Related Rentals (if any) -->
        <?php if(!empty($relatedRentals)): ?>
        <div class="mt-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Your Other Rentals for This Car</h3>
            
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    <?php foreach($relatedRentals as $related): ?>
                    <li>
                        <a href="index.php?page=rentals&action=view&id=<?= $related['rental_id'] ?>" class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <p class="text-sm font-medium text-blue-600 truncate">
                                            Rental #<?= $related['rental_id'] ?>
                                        </p>
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            <?php
                                            switch ($related['status']) {
                                                case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                                case 'approved': echo 'bg-blue-100 text-blue-800'; break;
                                                case 'active': echo 'bg-green-100 text-green-800'; break;
                                                case 'completed': echo 'bg-gray-100 text-gray-800'; break;
                                                case 'cancelled': echo 'bg-red-100 text-red-800'; break;
                                                default: echo 'bg-gray-100 text-gray-800';
                                            }
                                            ?>">
                                            <?= ucfirst($related['status']) ?>
                                        </span>
                                    </div>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <p class="text-sm text-gray-500">
                                            $<?= number_format($related['total_cost'], 2) ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                            <?= date('M d, Y', strtotime($related['start_date'])) ?> - <?= date('M d, Y', strtotime($related['end_date'])) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal for Extending Rental (hidden by default) -->
<div id="extendRentalModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Extend Rental Period</h3>
            <button type="button" onclick="closeExtendModal()" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form action="index.php?page=rentals&action=extend_submit" method="POST">
            <input type="hidden" name="rental_id" value="<?= $rental['rental_id'] ?>">
            
            <div class="mb-4">
                <label for="current_end_date" class="block text-sm font-medium text-gray-700 mb-1">Current End Date</label>
                <input type="text" id="current_end_date" value="<?= date('M d, Y', strtotime($rental['end_date'])) ?>" class="bg-gray-100 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm text-gray-700" readonly>
            </div>
            
            <div class="mb-4">
                <label for="new_end_date" class="block text-sm font-medium text-gray-700 mb-1">New End Date</label>
                <input type="date" id="new_end_date" name="new_end_date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                       min="<?= date('Y-m-d', strtotime('+1 day', strtotime($rental['end_date']))) ?>" required>
            </div>
            
            <div class="mb-4">
                <label for="extension_reason" class="block text-sm font-medium text-gray-700 mb-1">Reason for Extension</label>
                <textarea id="extension_reason" name="extension_reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Please provide a reason for extending your rental..."></textarea>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeExtendModal()" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Request Extension
                </button>
            </div>
        </form>
    </div>
</div>

        </div>
        
       
    </div>
</div>
<script>
// Function to show the extend rental modal
function showExtendModal() {
    document.getElementById('extendRentalModal').classList.remove('hidden');
}

// Function to close the extend rental modal
function closeExtendModal() {
    document.getElementById('extendRentalModal').classList.add('hidden');
}

// Add event listener to the extend rental button
document.addEventListener('DOMContentLoaded', function() {
    const extendButtons = document.querySelectorAll('a[href*="action=extend"]');
    extendButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            showExtendModal();
        });
    });
});
</script>
<?php require 'views/layouts/footer.php'; ?>

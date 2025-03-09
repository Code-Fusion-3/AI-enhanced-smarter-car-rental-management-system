<?php
class AIChat {
    private $responses = [
        'rental_process' => 'To rent a car: 1. Browse available cars 2. Select dates 3. Submit request 4. Wait for approval',
        'payment' => 'We accept credit cards and PayPal. Payment is required upon rental approval.',
        'return' => 'Please return the car to the same location you picked it up from.',
        'default' => 'Please contact our support team for more information.'
    ];
    
    public function getResponse($query) {
        $query = strtolower($query);
        
        if (strpos($query, 'rent') !== false) {
            return $this->responses['rental_process'];
        } elseif (strpos($query, 'pay') !== false) {
            return $this->responses['payment'];
        } elseif (strpos($query, 'return') !== false) {
            return $this->responses['return'];
        }
        
        return $this->responses['default'];
    }
}

<?php

class Rental {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getUserRentals($userId) {
        $sql = "SELECT r.*, c.make, c.model 
                FROM rentals r 
                JOIN cars c ON r.car_id = c.car_id 
                WHERE r.user_id = ? 
                ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);  
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getRental($rentalId) {
        $sql = "SELECT r.*, c.make, c.model, c.year, c.registration_number, c.daily_rate, c.features, c.image_url, 
                       c.fuel_type, c.transmission, c.category_id,
                       u.full_name, 
                       cc.name as category_name
                FROM rentals r 
                JOIN cars c ON r.car_id = c.car_id 
                JOIN users u ON r.user_id = u.user_id 
                LEFT JOIN car_categories cc ON c.category_id = cc.category_id
                WHERE r.rental_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $rentalId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    

    public function getRentalPayment($rentalId) {
        $sql = "SELECT * FROM payments WHERE rental_id = ? ORDER BY payment_date DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $rentalId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getRentalHistory($rentalId) {
        $sql = "SELECT * FROM rental_history WHERE rental_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $rentalId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getRelatedRentals($carId, $rentalId, $userId, $limit = 3) {
        $sql = "SELECT r.rental_id, r.start_date, r.end_date, r.status, r.total_cost 
                FROM rentals r 
                WHERE r.car_id = ? AND r.rental_id != ? AND r.user_id = ? 
                ORDER BY r.created_at DESC 
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiii", $carId, $rentalId, $userId, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
 
    public function isCarAvailable($carId, $startDate, $endDate) {
        // Check if car exists and is available
        $sql = "SELECT status FROM cars WHERE car_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $carId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false; // Car doesn't exist
        }
        
        $car = $result->fetch_assoc();
        if ($car['status'] !== 'available') {
            return false; // Car is not available
        }
        
        // Check if car is already booked for the selected dates
        $sql = "SELECT COUNT(*) as count FROM rentals 
                WHERE car_id = ? 
                AND status IN ('approved', 'active') 
                AND ((start_date <= ? AND end_date >= ?) 
                OR (start_date <= ? AND end_date >= ?) 
                OR (start_date >= ? AND end_date <= ?))";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("issssss", $carId, $endDate, $startDate, $startDate, $startDate, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'] === 0;
    }
    
    public function isCarAvailableForEdit($carId, $startDate, $endDate, $rentalId) {
        // Check if car is already booked for the selected dates (excluding current rental)
        $sql = "SELECT COUNT(*) as count FROM rentals 
                WHERE car_id = ? 
                AND rental_id != ?
                AND status IN ('approved', 'active') 
                AND ((start_date <= ? AND end_date >= ?) 
                OR (start_date <= ? AND end_date >= ?) 
                OR (start_date >= ? AND end_date <= ?))";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iissssss", $carId, $rentalId, $endDate, $startDate, $startDate, $startDate, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'] == 0;
    }
    
    public function isCarAvailableForExtension($carId, $currentEndDate, $newEndDate, $rentalId) {
        // Check if car is already booked for the extended period (excluding current rental)
        $sql = "SELECT COUNT(*) as count FROM rentals 
                WHERE car_id = ? 
                AND rental_id != ?
                AND status IN ('approved', 'active') 
                AND start_date <= ? 
                AND end_date >= ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiss", $carId, $rentalId, $newEndDate, $currentEndDate);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'] == 0;
    }
    
    public function validatePromoCode($code) {
        $sql = "SELECT promotion_id FROM promotions 
                WHERE code = ? 
                AND is_active = 1 
                AND start_date <= CURDATE() 
                AND end_date >= CURDATE()";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['promotion_id'];
        }
        
        return false;
    }
    
    public function calculateTotalCost($carId, $startDate, $endDate, $promotionId = null) {
        // Get car daily rate
        $sql = "SELECT daily_rate FROM cars WHERE car_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $carId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false;
        }
        
        $car = $result->fetch_assoc();
        $dailyRate = $car['daily_rate'];
        
        // Calculate number of days
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $interval = $start->diff($end);
        $days = $interval->days + 1; // Include both start and end days
        
        // Calculate base cost
        $totalCost = $dailyRate * $days;
        
        // Apply promotion if available
        if ($promotionId) {
            $sql = "SELECT discount_percentage, discount_amount FROM promotions WHERE promotion_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $promotionId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $promotion = $result->fetch_assoc();
                
                if ($promotion['discount_percentage']) {
                    $discount = $totalCost * ($promotion['discount_percentage'] / 100);
                    $totalCost -= $discount;
                } elseif ($promotion['discount_amount']) {
                    $totalCost -= $promotion['discount_amount'];
                }
            }
        }
        
        return max(0, $totalCost); // Ensure total cost is not negative
    }
    
    public function createRental($userId, $carId, $startDate, $endDate, $pickupLocation = null, $returnLocation = null, $promotionId = null) {
        // Calculate total cost
        $totalCost = $this->calculateTotalCost($carId, $startDate, $endDate, $promotionId);
        
        if ($totalCost === false) {
            return false;
        }
        
        // Begin transaction
        $this->db->begin_transaction();
        
        try {
            // Insert rental record
            $sql = "INSERT INTO rentals (user_id, car_id, start_date, end_date, pickup_location, return_location, promotion_id, total_cost, status, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
            
            $stmt = $this->db->prepare($sql);
            $status = 'pending';
            $stmt->bind_param("iisssids", $userId, $carId, $startDate, $endDate, $pickupLocation, $returnLocation, $promotionId, $totalCost);
            $stmt->execute();
            
            $rentalId = $this->db->insert_id;
            
            // Commit transaction
            $this->db->commit();
            
            return $rentalId;
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->db->rollback();
            return false;
        }
    }
    
    public function updateRental($rentalId, $startDate, $endDate, $pickupLocation = null, $returnLocation = null, $promotionId = null) {
        // Get rental details
        $sql = "SELECT car_id FROM rentals WHERE rental_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $rentalId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false;
        }
        
        $rental = $result->fetch_assoc();
        $carId = $rental['car_id'];
        
        // Calculate new total cost
        $totalCost = $this->calculateTotalCost($carId, $startDate, $endDate, $promotionId);
        
        if ($totalCost === false) {
            return false;
        }
        
        // Begin transaction
        $this->db->begin_transaction();
        
        try {
            // Update rental record
            $sql = "UPDATE rentals 
                    SET start_date = ?, end_date = ?, pickup_location = ?, return_location = ?, 
                        promotion_id = ?, total_cost = ? 
                    WHERE rental_id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ssssdii", $startDate, $endDate, $pickupLocation, $returnLocation, $promotionId, $totalCost, $rentalId);
            $stmt->execute();
            
            // Commit transaction
            $this->db->commit();
            
            return true;
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->db->rollback();
            return false;
        }
    }
    
    public function updateRentalStatus($rentalId, $status) {
        $sql = "UPDATE rentals SET status = ? WHERE rental_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $status, $rentalId);
        
        if ($stmt->execute()) {
            // If status is 'active', update car status to 'rented'
            if ($status === 'active') {
                $sql = "UPDATE cars c 
                        JOIN rentals r ON c.car_id = r.car_id 
                        SET c.status = 'rented' 
                        WHERE r.rental_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("i", $rentalId);
                $stmt->execute();
            }
            
            // If status is 'completed' or 'cancelled', update car status to 'available'
            if ($status === 'completed' || $status === 'cancelled') {
                $sql = "UPDATE cars c 
                        JOIN rentals r ON c.car_id = r.car_id 
                        SET c.status = 'available' 
                        WHERE r.rental_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("i", $rentalId);
                $stmt->execute();
            }
            
            return true;
        }
        
        return false;
    }
    
    public function processReturn($rentalId, $returnCondition, $additionalCharges = 0) {
        // Begin transaction
        $this->db->begin_transaction();
        
        try {
            // Update rental status
            $this->updateRentalStatus($rentalId, 'completed');
            
            // Get rental details
            $rental = $this->getRental($rentalId);
            
            // Create rental history record
            $sql = "INSERT INTO rental_history (rental_id, user_id, car_id, return_date, return_condition, additional_charges) 
                    VALUES (?, ?, ?, NOW(), ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("iiisf", $rentalId, $rental['user_id'], $rental['car_id'], $returnCondition, $additionalCharges);
            $stmt->execute();
            
            // If there are additional charges, update the total cost
            if ($additionalCharges > 0) {
                $newTotalCost = $rental['total_cost'] + $additionalCharges;
                
                $sql = "UPDATE rentals SET total_cost = ? WHERE rental_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("di", $newTotalCost, $rentalId);
                $stmt->execute();
            }
            
            // Commit transaction
            $this->db->commit();
            
            return true;
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->db->rollback();
            return false;
        }
    }
    
    public function extendRental($rentalId, $newEndDate, $extensionReason = '') {
        // Begin transaction
        $this->db->begin_transaction();
        
        try {
            // Get rental details
            $rental = $this->getRental($rentalId);
            
            // Calculate additional cost
            $additionalCost = $this->calculateTotalCost(
                $rental['car_id'], 
                $rental['end_date'], 
                $newEndDate
            );
            
            if ($additionalCost === false) {
                throw new Exception("Error calculating additional cost");
            }
            
            // Update rental end date and total cost
            $newTotalCost = $rental['total_cost'] + $additionalCost;
            
            $sql = "UPDATE rentals 
                    SET end_date = ?, total_cost = ?, notes = CONCAT(IFNULL(notes, ''), '\nExtended from ', end_date, ' to ', ?, '. Reason: ', ?) 
                    WHERE rental_id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("sdssi", $newEndDate, $newTotalCost, $newEndDate, $extensionReason, $rentalId);
            $stmt->execute();
            
            // Commit transaction
            $this->db->commit();
            
            return true;
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->db->rollback();
            return false;
        }
    }
    
    public function submitReview($rentalId, $rating, $feedback) {
        // Get rental details
        $rental = $this->getRental($rentalId);
        
        // Check if review already exists
        $sql = "SELECT history_id FROM rental_history WHERE rental_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $rentalId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Update existing review
            $sql = "UPDATE rental_history SET rating = ?, feedback = ? WHERE rental_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("isi", $rating, $feedback, $rentalId);
        } else {
            // Create new review
            $sql = "INSERT INTO rental_history (rental_id, user_id, car_id, rating, feedback, created_at) 
                    VALUES (?, ?, ?, ?, ?, NOW())";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("iiiss", $rentalId, $rental['user_id'], $rental['car_id'], $rating, $feedback);
        }
        
        return $stmt->execute();
    }
    /**
 * Check if a rental has a review
 * 
 * @param int $rentalId The rental ID to check
 * @return array|false Returns an array with 'hasReview' and 'rating' if found, false otherwise
 */
public function checkRentalReview($rentalId) {
    $result = [
        'hasReview' => false,
        'rating' => null
    ];
    
    $reviewQuery = "SELECT rating FROM rental_history WHERE rental_id = ? AND rating IS NOT NULL";
    $stmt = $this->db->prepare($reviewQuery);
    $stmt->bind_param("i", $rentalId);
    $stmt->execute();
    $reviewResult = $stmt->get_result();
    
    if ($reviewResult && $reviewResult->num_rows > 0) {
        $result['hasReview'] = true;
        $result['rating'] = $reviewResult->fetch_assoc()['rating'];
    }
    
    return $result;
}

}


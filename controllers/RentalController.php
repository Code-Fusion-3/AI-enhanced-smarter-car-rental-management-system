<?php
require_once 'models/Rental.php';
require_once 'models/Car.php';
class RentalController {
    private $db;
    private $rental;
    
    public function __construct() {
        global $conn;
        $this->db = $conn;
        $this->rental = new Rental($conn);
    }
    
    public function handle() {
        if (!isLoggedIn()) {
            header('Location: index.php?page=auth&action=login');
            exit();
        }

        $action = isset($_GET['action']) ? $_GET['action'] : 'list';
        
        switch($action) {
            case 'list':
                $this->listRentals();
                break;
            case 'create':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->createRental();
                } else {
                    $this->showRentalForm();
                }
                break;
            case 'view':
                $this->viewRental();
                break;
            case 'cancel':
                $this->cancelRental();
                break;
            case 'edit':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->updateRental();
                } else {
                    $this->showEditForm();
                }
                break;
            case 'return':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->processReturn();
                } else {
                    $this->showReturnForm();
                }
                break;
            case 'extend_submit':
                $this->extendRental();
                break;
            case 'review':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->submitReview();
                } else {
                    $this->showReviewForm();
                }
                break;
            case 'rent_again':
                $this->rentAgain();
                break;
            default:
                header('Location: index.php?page=rentals');
                exit();
        }
    }
    
    private function listRentals() {
        $userId = $_SESSION['user_id'];
        
        $sql = "SELECT r.*, c.make, c.model, c.year, c.image_url, c.registration_number, 
        c.category_id, cc.name as category_name, 
        p.code as promo_code, p.discount_percentage, p.discount_amount,
        (SELECT COUNT(*) FROM payments WHERE rental_id = r.rental_id AND status = 'completed') as payment_complete
 FROM rentals r
 JOIN cars c ON r.car_id = c.car_id
 LEFT JOIN car_categories cc ON c.category_id = cc.category_id
 LEFT JOIN promotions p ON r.promotion_id = p.promotion_id
 WHERE r.user_id = ?
 ORDER BY r.created_at DESC";

$stmt = $this->db->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$rentals = [];
while ($row = $result->fetch_assoc()) {
// Check if this rental has a review
$reviewInfo = $this->rental->checkRentalReview($row['rental_id']);
$row['hasReview'] = $reviewInfo['hasReview'];
$row['rating'] = $reviewInfo['rating'];

$rentals[] = $row;
}

        // Get upcoming rentals (next 7 days)
        $upcomingRentals = array_filter($rentals, function($rental) {
            $startDate = new DateTime($rental['start_date']);
            $now = new DateTime();
            $diff = $startDate->diff($now)->days;
            return $startDate > $now && $diff <= 7 && $rental['status'] != 'cancelled';
        });
        
        // Get active rentals
        $activeRentals = array_filter($rentals, function($rental) {
            return $rental['status'] === 'active';
        });
        
        // Get past rentals
        $pastRentals = array_filter($rentals, function($rental) {
            return $rental['status'] === 'completed' || $rental['status'] === 'cancelled';
        });
        
        require 'views/rentals/list.php';
    }
    
    private function createRental() {
        $userId = $_SESSION['user_id'];
        $carId = $_POST['car_id'];
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
        $pickupLocation = isset($_POST['pickup_location']) ? $_POST['pickup_location'] : null;
        $returnLocation = isset($_POST['return_location']) ? $_POST['return_location'] : null;
        $promoCode = isset($_POST['promo_code']) ? $_POST['promo_code'] : null;
        
        // Validate dates
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $today = new DateTime();
        
        if ($start < $today) {
            $_SESSION['error'] = "Start date cannot be in the past.";
            header('Location: index.php?page=rentals&action=create&car_id=' . $carId);
            exit();
        }
        
        if ($end <= $start) {
            $_SESSION['error'] = "End date must be after start date.";
            header('Location: index.php?page=rentals&action=create&car_id=' . $carId);
            exit();
        }
        
        // Check if car is available for the selected dates
        if (!$this->rental->isCarAvailable($carId, $startDate, $endDate)) {
            $_SESSION['error'] = "This car is not available for the selected dates.";
            header('Location: index.php?page=rentals&action=create&car_id=' . $carId);
            exit();
        }
        
        // Process promotion code if provided
        $promotionId = null;
        if (!empty($promoCode)) {
            $promotionId = $this->rental->validatePromoCode($promoCode);
            if (!$promotionId) {
                $_SESSION['error'] = "Invalid or expired promotion code.";
                header('Location: index.php?page=rentals&action=create&car_id=' . $carId);
                exit();
            }
        }
        
        // Create the rental
        $rentalId = $this->rental->createRental(
            $userId, 
            $carId, 
            $startDate, 
            $endDate, 
            $pickupLocation, 
            $returnLocation, 
            $promotionId
        );
        
        if ($rentalId) {
            // Create a notification for the user
            $this->createNotification(
                $userId,
                'Rental Confirmation',
                'Your rental booking has been confirmed. Rental ID: ' . $rentalId,
                'rental',
                $rentalId
            );
            
            $_SESSION['success'] = "Your rental has been successfully created.";
            header('Location: index.php?page=rentals&status=success');
        } else {
            $_SESSION['error'] = "There was an error creating your rental.";
            header('Location: index.php?page=rentals&status=error');
        }
        exit();
    }
    
    private function showRentalForm() {
        $carId = $_GET['car_id'];
        
        // Get car details
        $car = new Car($this->db);
        $carDetails = $car->getCarById($carId);
        
        if (!$carDetails) {
            $_SESSION['error'] = "Car not found.";
            header('Location: index.php?page=cars');
            exit();
        }
        
        // Get dynamic price
        $dynamicPrice = $car->getDynamicPrice($carId);
        
        // Get active promotions
        $sql = "SELECT * FROM promotions WHERE is_active = 1 AND start_date <= CURDATE() AND end_date >= CURDATE()";
        $result = $this->db->query($sql);
        $promotions = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $promotions[] = $row;
            }
        }
        
        require 'views/rentals/create.php';
    }
    
    private function viewRental() {
        $rentalId = $_GET['id'];
        $userId = $_SESSION['user_id'];
        
        // Get rental details
        $rental = $this->rental->getRental($rentalId);
        
        // Check if rental exists and belongs to the current user
        if (!$rental || $rental['user_id'] != $userId) {
            $_SESSION['error'] = "Rental not found or you don't have permission to view it.";
            header('Location: index.php?page=rentals');
            exit();
        }
        
        // Get additional data
        $payment = $this->rental->getRentalPayment($rentalId);
        $history = $this->rental->getRentalHistory($rentalId);
        $relatedRentals = $this->rental->getRelatedRentals(
            $rental['car_id'], 
            $rentalId, 
            $_SESSION['user_id']
        );
        
        // Check if rental is overdue
        $today = new DateTime();
        $endDate = new DateTime($rental['end_date']);
        $isOverdue = $today > $endDate;
        
        require 'views/rentals/view.php';
    }
    
    private function cancelRental() {
        $rentalId = $_GET['id'];
        $userId = $_SESSION['user_id'];
        
        // Get rental details
        $rental = $this->rental->getRental($rentalId);
        
        // Check if rental exists and belongs to the current user
        if (!$rental || $rental['user_id'] != $userId) {
            $_SESSION['error'] = "Rental not found or you don't have permission to cancel it.";
            header('Location: index.php?page=rentals');
            exit();
        }
        
        // Check if rental can be cancelled
        if ($rental['status'] !== 'pending' && $rental['status'] !== 'approved') {
            $_SESSION['error'] = "This rental cannot be cancelled.";
            header('Location: index.php?page=rentals&action=view&id=' . $rentalId);
            exit();
        }
        
        // Cancel the rental
        if ($this->rental->updateRentalStatus($rentalId, 'cancelled')) {
            // Create a notification for the user
            $this->createNotification(
                $userId,
                'Rental Cancelled',
                'Your rental booking has been cancelled. Rental ID: ' . $rentalId,
                'rental',
                $rentalId
            );
            
            $_SESSION['success'] = "Your rental has been successfully cancelled.";
        } else {
            $_SESSION['error'] = "There was an error cancelling your rental.";
        }
        
        header('Location: index.php?page=rentals');
        exit();
    }
    
    private function showEditForm() {
        $rentalId = $_GET['id'];
        $userId = $_SESSION['user_id'];
        
        // Get rental details
        $rental = $this->rental->getRental($rentalId);
        
        // Check if rental exists and belongs to the current user
        if (!$rental || $rental['user_id'] != $userId) {
            $_SESSION['error'] = "Rental not found or you don't have permission to edit it.";
            header('Location: index.php?page=rentals');
            exit();
        }
        
        // Check if rental can be edited
        if ($rental['status'] !== 'pending') {
            $_SESSION['error'] = "This rental cannot be edited.";
            header('Location: index.php?page=rentals&action=view&id=' . $rentalId);
            exit();
        }
        
        // Get car details
        $car = new Car($this->db);
        $carDetails = $car->getCarById($rental['car_id']);
        
        // Get active promotions
        $sql = "SELECT * FROM promotions WHERE is_active = 1 AND start_date <= CURDATE() AND end_date >= CURDATE()";
        $result = $this->db->query($sql);
        $promotions = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $promotions[] = $row;
            }
        }
        
        require 'views/rentals/edit.php';
    }
    
    private function updateRental() {
        $rentalId = $_POST['rental_id'];
        $userId = $_SESSION['user_id'];
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
        $pickupLocation = isset($_POST['pickup_location']) ? $_POST['pickup_location'] : null;
        $returnLocation = isset($_POST['return_location']) ? $_POST['return_location'] : null;
        $promoCode = isset($_POST['promo_code']) ? $_POST['promo_code'] : null;
        
        // Get rental details
        $rental = $this->rental->getRental($rentalId);
        
        // Check if rental exists and belongs to the current user
        if (!$rental || $rental['user_id'] != $userId) {
            $_SESSION['error'] = "Rental not found or you don't have permission to edit it.";
            header('Location: index.php?page=rentals');
            exit();
        }
        
        // Check if rental can be edited
        if ($rental['status'] !== 'pending') {
            $_SESSION['error'] = "This rental cannot be edited.";
            header('Location: index.php?page=rentals&action=view&id=' . $rentalId);
            exit();
        }
        
        // Validate dates
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $today = new DateTime();
        
        if ($start < $today) {
            $_SESSION['error'] = "Start date cannot be in the past.";
            header('Location: index.php?page=rentals&action=edit&id=' . $rentalId);
            exit();
        }
        
        if ($end <= $start) {
            $_SESSION['error'] = "End date must be after start date.";
            header('Location: index.php?page=rentals&action=edit&id=' . $rentalId);
            exit();
        }
        
        // Check if car is available for the selected dates (excluding current rental)
        if (!$this->rental->isCarAvailableForEdit($rental['car_id'], $startDate, $endDate, $rentalId)) {
            $_SESSION['error'] = "This car is not available for the selected dates.";
            header('Location: index.php?page=rentals&action=edit&id=' . $rentalId);
            exit();
        }
        
        // Process promotion code if provided
        $promotionId = null;
        if (!empty($promoCode)) {
            $promotionId = $this->rental->validatePromoCode($promoCode);
            if (!$promotionId) {
                $_SESSION['error'] = "Invalid or expired promotion code.";
                header('Location: index.php?page=rentals&action=edit&id=' . $rentalId);
                exit();
            }
        }
        // Update the rental
        if ($this->rental->updateRental($rentalId, $startDate, $endDate, $pickupLocation, $returnLocation, $promotionId)) {
            $_SESSION['success'] = "Your rental has been successfully updated.";
            header('Location: index.php?page=rentals&action=view&id=' . $rentalId);
        } else {
            $_SESSION['error'] = "There was an error updating your rental.";
            header('Location: index.php?page=rentals&action=edit&id=' . $rentalId);
        }
        exit();
    }
    
    private function showReturnForm() {
        $rentalId = $_GET['id'];
        $userId = $_SESSION['user_id'];
        
        // Get rental details
        $rental = $this->rental->getRental($rentalId);
        
        // Check if rental exists and belongs to the current user
        if (!$rental || $rental['user_id'] != $userId) {
            $_SESSION['error'] = "Rental not found or you don't have permission to return it.";
            header('Location: index.php?page=rentals');
            exit();
        }
        
        // Check if rental can be returned
        if ($rental['status'] !== 'active') {
            $_SESSION['error'] = "This rental cannot be returned.";
            header('Location: index.php?page=rentals&action=view&id=' . $rentalId);
            exit();
        }
        
        require 'views/rentals/return.php';
    }
    
    private function processReturn() {
        $rentalId = $_POST['rental_id'];
        $userId = $_SESSION['user_id'];
        $returnCondition = $_POST['return_condition'];
        $additionalCharges = isset($_POST['additional_charges']) ? $_POST['additional_charges'] : 0;
        
        // Get rental details
        $rental = $this->rental->getRental($rentalId);
        
        // Check if rental exists and belongs to the current user
        if (!$rental || $rental['user_id'] != $userId) {
            $_SESSION['error'] = "Rental not found or you don't have permission to return it.";
            header('Location: index.php?page=rentals');
            exit();
        }
        
        // Check if rental can be returned
        if ($rental['status'] !== 'active') {
            $_SESSION['error'] = "This rental cannot be returned.";
            header('Location: index.php?page=rentals&action=view&id=' . $rentalId);
            exit();
        }
        
        // Process the return
        if ($this->rental->processReturn($rentalId, $returnCondition, $additionalCharges)) {
            // Create a notification for the user
            $this->createNotification(
                $userId,
                'Rental Returned',
                'Your rental has been successfully returned. Rental ID: ' . $rentalId,
                'rental',
                $rentalId
            );
            
            $_SESSION['success'] = "Your rental has been successfully returned.";
            header('Location: index.php?page=rentals&action=view&id=' . $rentalId);
        } else {
            $_SESSION['error'] = "There was an error processing your return.";
            header('Location: index.php?page=rentals&action=return&id=' . $rentalId);
        }
        exit();
    }
    
    private function extendRental() {
        $rentalId = $_POST['rental_id'];
        $userId = $_SESSION['user_id'];
        $newEndDate = $_POST['new_end_date'];
        $extensionReason = isset($_POST['extension_reason']) ? $_POST['extension_reason'] : '';
        
        // Get rental details
        $rental = $this->rental->getRental($rentalId);
        
        // Check if rental exists and belongs to the current user
        if (!$rental || $rental['user_id'] != $userId) {
            $_SESSION['error'] = "Rental not found or you don't have permission to extend it.";
            header('Location: index.php?page=rentals');
            exit();
        }
        
        // Check if rental can be extended
        if ($rental['status'] !== 'active') {
            $_SESSION['error'] = "This rental cannot be extended.";
            header('Location: index.php?page=rentals&action=view&id=' . $rentalId);
            exit();
        }
        
        // Validate new end date
        $currentEnd = new DateTime($rental['end_date']);
        $newEnd = new DateTime($newEndDate);
        
        if ($newEnd <= $currentEnd) {
            $_SESSION['error'] = "New end date must be after current end date.";
            header('Location: index.php?page=rentals&action=view&id=' . $rentalId);
            exit();
        }
        
        // Check if car is available for the extended period
        if (!$this->rental->isCarAvailableForExtension($rental['car_id'], $rental['end_date'], $newEndDate, $rentalId)) {
            $_SESSION['error'] = "This car is not available for the extended period.";
            header('Location: index.php?page=rentals&action=view&id=' . $rentalId);
            exit();
        }
        
        // Process the extension
        if ($this->rental->extendRental($rentalId, $newEndDate, $extensionReason)) {
            // Create a notification for the user
            $this->createNotification(
                $userId,
                'Rental Extended',
                'Your rental has been successfully extended. New end date: ' . date('M d, Y', strtotime($newEndDate)),
                'rental',
                $rentalId
            );
            
            $_SESSION['success'] = "Your rental has been successfully extended.";
            header('Location: index.php?page=rentals&action=view&id=' . $rentalId);
        } else {
            $_SESSION['error'] = "There was an error extending your rental.";
            header('Location: index.php?page=rentals&action=view&id=' . $rentalId);
        }
        exit();
    }
    
    private function showReviewForm() {
        $rentalId = $_GET['id'];
        $userId = $_SESSION['user_id'];
        
        // Get rental details
        $rental = $this->rental->getRental($rentalId);
        
        // Check if rental exists and belongs to the current user
        if (!$rental || $rental['user_id'] != $userId) {
            $_SESSION['error'] = "Rental not found or you don't have permission to review it.";
            header('Location: index.php?page=rentals');
            exit();
        }
        
        // Check if rental can be reviewed
        if ($rental['status'] !== 'completed') {
            $_SESSION['error'] = "You can only review completed rentals.";
            header('Location: index.php?page=rentals&action=view&id=' . $rentalId);
            exit();
        }
        
        // Get existing review if any
        $history = $this->rental->getRentalHistory($rentalId);
        
        require 'views/rentals/review.php';
    }
    
    private function submitReview() {
        $rentalId = $_POST['rental_id'];
        $userId = $_SESSION['user_id'];
        $rating = $_POST['rating'];
        $feedback = $_POST['feedback'];
        
        // Get rental details
        $rental = $this->rental->getRental($rentalId);
        
        // Check if rental exists and belongs to the current user
        if (!$rental || $rental['user_id'] != $userId) {
            $_SESSION['error'] = "Rental not found or you don't have permission to review it.";
            header('Location: index.php?page=rentals');
            exit();
        }
        
        // Check if rental can be reviewed
        if ($rental['status'] !== 'completed') {
            $_SESSION['error'] = "You can only review completed rentals.";
            header('Location: index.php?page=rentals&action=view&id=' . $rentalId);
            exit();
        }
        
        // Validate rating
        if ($rating < 1 || $rating > 5) {
            $_SESSION['error'] = "Rating must be between 1 and 5.";
            header('Location: index.php?page=rentals&action=review&id=' . $rentalId);
            exit();
        }
        
        // Submit the review
        if ($this->rental->submitReview($rentalId, $rating, $feedback)) {
            $_SESSION['success'] = "Your review has been successfully submitted.";
            header('Location: index.php?page=rentals&action=view&id=' . $rentalId);
        } else {
            $_SESSION['error'] = "There was an error submitting your review.";
            header('Location: index.php?page=rentals&action=review&id=' . $rentalId);
        }
        exit();
    }
    
    private function rentAgain() {
        $carId = $_GET['car_id'];
        
        // Redirect to create rental form with the car ID
        header('Location: index.php?page=cars&action=view&id=3=' . $carId);
        exit();
    }
    
    private function createNotification($userId, $title, $message, $type, $relatedId = null) {
        $sql = "INSERT INTO notifications (user_id, title, message, type, related_id, is_read, created_at) 
                VALUES (?, ?, ?, ?, ?, 0, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isssi", $userId, $title, $message, $type, $relatedId);
        return $stmt->execute();
    }
}

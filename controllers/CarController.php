<?php
require_once 'models/Car.php';
require_once 'includes/Recommender.php';

class CarController {
    private $db;
    private $car;
    private $recommender;
    
    public function __construct() {
        global $conn;
        $this->db = $conn;
        $this->car = new Car($conn);
        $this->recommender = new Recommender($conn);
    }
    
    public function handle() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'list';
        
        switch($action) {
            case 'list':
                $this->listCars();
                break;
            case 'view':
                $this->viewCar();
                break;
            case 'recommend':
                $this->getRecommendations();
                break;
            case 'add':
                $this->addCar();
                break;
            case 'edit':
                $this->editCar();
                break;
            case 'delete':
                $this->deleteCar();
                break;
            case 'favorite':
                $this->toggleFavorite();
                break;
                case 'submit_review':  // Add this new case
                    $this->submitReview();
                    break;
                default:
                    $this->listCars();
                    break;
        }
    }
    
    private function listCars() {
        // Get filter parameters
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
        $price = isset($_GET['price']) ? (float)$_GET['price'] : 0;
        $fuel = isset($_GET['fuel_type']) ? $_GET['fuel_type'] : '';
        $transmission = isset($_GET['transmission']) ? $_GET['transmission'] : '';
        $seats = isset($_GET['seats']) ? (int)$_GET['seats'] : 0;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'price_asc';
        
        // Pagination
        $page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
        $perPage = 9; // Number of cars per page
        $offset = ($page - 1) * $perPage;
        
        // Build query conditions
        $conditions = [];
        $params = [];
        $types = '';
        
        if (!empty($search)) {
            $conditions[] = "(make LIKE ? OR model LIKE ? OR features LIKE ?)";
            $searchParam = "%$search%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
            $types .= 'sss';
        }
        
        if ($category > 0) {
            $conditions[] = "category_id = ?";
            $params[] = $category;
            $types .= 'i';
        }
        
        if ($price > 0) {
            $conditions[] = "daily_rate <= ?";
            $params[] = $price;
            $types .= 'd';
        }
        
        if (!empty($fuel)) {
            $conditions[] = "fuel_type = ?";
            $params[] = $fuel;
            $types .= 's';
        }
        
        if (!empty($transmission)) {
            $conditions[] = "transmission = ?";
            $params[] = $transmission;
            $types .= 's';
        }
        
        if ($seats > 0) {
            $conditions[] = "seats >= ?";
            $params[] = $seats;
            $types .= 'i';
        }
        
        // Default condition to show only available cars
        if (!isset($_GET['show_all']) || !isAdmin()) {
            $conditions[] = "status = 'available'";
        }
        
        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        // Sorting
        $orderBy = "ORDER BY ";
        switch ($sort) {
            case 'price_desc':
                $orderBy .= "daily_rate DESC";
                break;
            case 'newest':
                $orderBy .= "year DESC";
                break;
            case 'oldest':
                $orderBy .= "year ASC";
                break;
            case 'name_asc':
                $orderBy .= "make ASC, model ASC";
                break;
            case 'name_desc':
                $orderBy .= "make DESC, model DESC";
                break;
            case 'price_asc':
            default:
                $orderBy .= "daily_rate ASC";
                break;
        }
        
        // Count total cars for pagination
        $countQuery = "SELECT COUNT(*) as total FROM cars $whereClause";
        
        if (!empty($params)) {
            $stmt = $this->db->prepare($countQuery);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
            $totalCars = $result->fetch_assoc()['total'];
            $stmt->close();
        } else {
            $result = $this->db->query($countQuery);
            $totalCars = $result->fetch_assoc()['total'];
        }
        
        $totalPages = ceil($totalCars / $perPage);
        
        // Get cars with pagination
        $query = "SELECT * FROM cars $whereClause $orderBy LIMIT $offset, $perPage";
        
        if (!empty($params)) {
            $stmt = $this->db->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
            $cars = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        } else {
            $result = $this->db->query($query);
            $cars = $result->fetch_all(MYSQLI_ASSOC);
        }
        
        // Get user favorites if logged in
        $favorites = [];
        if (isLoggedIn()) {
            $userId = $_SESSION['user_id'];
            $favQuery = "SELECT car_id FROM user_favorites WHERE user_id = ?";
            $stmt = $this->db->prepare($favQuery);
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $favorites[] = $row['car_id'];
            }
            $stmt->close();
        }
        
        // Get all categories for filter
        $categoriesQuery = "SELECT * FROM car_categories ORDER BY name ASC";
        $categoriesResult = $this->db->query($categoriesQuery);
        $categories = $categoriesResult->fetch_all(MYSQLI_ASSOC);
        
        // Get all fuel types and transmissions for filters
        $fuelTypesQuery = "SELECT DISTINCT fuel_type FROM cars WHERE fuel_type IS NOT NULL";
        $fuelTypesResult = $this->db->query($fuelTypesQuery);
        $fuelTypes = [];
        while ($row = $fuelTypesResult->fetch_assoc()) {
            if (!empty($row['fuel_type'])) {
                $fuelTypes[] = $row['fuel_type'];
            }
        }
        
        $transmissionsQuery = "SELECT DISTINCT transmission FROM cars WHERE transmission IS NOT NULL";
        $transmissionsResult = $this->db->query($transmissionsQuery);
        $transmissions = [];
        while ($row = $transmissionsResult->fetch_assoc()) {
            if (!empty($row['transmission'])) {
                $transmissions[] = $row['transmission'];
            }
        }
        
        // Get active promotions
        $promotionsQuery = "SELECT * FROM promotions WHERE is_active = 1 AND start_date <= CURDATE() AND end_date >= CURDATE() ORDER BY RAND() LIMIT 1";
        $promotionsResult = $this->db->query($promotionsQuery);
        $promotion = $promotionsResult->num_rows > 0 ? $promotionsResult->fetch_assoc() : null;
        
        // Pass data to view
        require 'views/cars/list.php';
    }

    private function viewCar() {
        $car_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $car = $this->car->getCarById($car_id);
        
        if (!$car) {
            $_SESSION['error'] = "Car not found.";
            header('Location: index.php?page=cars');
            exit();
        }
        
        // Get dynamic price
        $dynamicPrice = $this->car->getDynamicPrice($car_id);
        
        // Get category details
        $categoryQuery = "SELECT * FROM car_categories WHERE category_id = ?";
        $stmt = $this->db->prepare($categoryQuery);
        $stmt->bind_param('i', $car['category_id']);
        $stmt->execute();
        $categoryResult = $stmt->get_result();
        $category = $categoryResult->num_rows > 0 ? $categoryResult->fetch_assoc() : null;
        $stmt->close();
        
        // Get similar cars
        $similarQuery = "SELECT c.*, cc.name as category_name FROM cars c 
                        LEFT JOIN car_categories cc ON c.category_id = cc.category_id 
                        WHERE c.category_id = ? AND c.car_id != ? AND c.status = 'available' 
                        ORDER BY RAND() LIMIT 3";
        $stmt = $this->db->prepare($similarQuery);
        $stmt->bind_param('ii', $car['category_id'], $car_id);
        $stmt->execute();
        $similarResult = $stmt->get_result();
        $similarCars = $similarResult->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        // Check if car is in user's favorites
        $isFavorite = false;
        if (isLoggedIn()) {
            $userId = $_SESSION['user_id'];
            $favQuery = "SELECT * FROM user_favorites WHERE user_id = ? AND car_id = ?";
            $stmt = $this->db->prepare($favQuery);
            $stmt->bind_param('ii', $userId, $car_id);
            $stmt->execute();
            $isFavorite = $stmt->get_result()->num_rows > 0;
            $stmt->close();
            
            // Check if user has rented this car before
            $hasRentedQuery = "SELECT COUNT(*) as count FROM rentals WHERE user_id = ? AND car_id = ?";
            $stmt = $this->db->prepare($hasRentedQuery);
            $stmt->bind_param('ii', $userId, $car_id);
            $stmt->execute();
            $hasRentedResult = $stmt->get_result();
            $hasRented = $hasRentedResult->fetch_assoc()['count'] > 0;
            $stmt->close();
        }
        
        // Get car ratings and reviews
        $reviewsQuery = "SELECT rh.*, u.username, u.full_name, u.profile_image FROM rental_history rh 
                        JOIN users u ON rh.user_id = u.user_id 
                        WHERE rh.car_id = ? AND rh.rating IS NOT NULL 
                        ORDER BY rh.created_at DESC";
        $stmt = $this->db->prepare($reviewsQuery);
        $stmt->bind_param('i', $car_id);
        $stmt->execute();
        $reviewsResult = $stmt->get_result();
        $reviews = $reviewsResult->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        // Calculate average rating
        $avgRatingQuery = "SELECT AVG(rating) as avg_rating, COUNT(*) as review_count FROM rental_history WHERE car_id = ? AND rating IS NOT NULL";
        $stmt = $this->db->prepare($avgRatingQuery);
        $stmt->bind_param('i', $car_id);
        $stmt->execute();
        $avgRatingResult = $stmt->get_result();
        $ratingData = $avgRatingResult->fetch_assoc();
        $avgRating = $ratingData['avg_rating'] ?? 0;
        $reviewCount = $ratingData['review_count'] ?? 0;
        $stmt->close();
        
        // Get active promotions
        $promoQuery = "SELECT * FROM promotions WHERE is_active = 1 AND start_date <= CURDATE() AND end_date >= CURDATE() ORDER BY RAND() LIMIT 1";
        $promoResult = $this->db->query($promoQuery);
        $promotion = $promoResult->num_rows > 0 ? $promoResult->fetch_assoc() : null;
        
        // Get maintenance history (for admin view)
        $maintenanceHistory = [];
        if (isAdmin()) {
            $maintenanceQuery = "SELECT * FROM maintenance_records WHERE car_id = ? ORDER BY start_date DESC";
            $stmt = $this->db->prepare($maintenanceQuery);
            $stmt->bind_param('i', $car_id);
            $stmt->execute();
            $maintenanceResult = $stmt->get_result();
            $maintenanceHistory = $maintenanceResult->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }
        
        require 'views/cars/view.php';
    }
    

    private function getRecommendations() {
        if (!isLoggedIn()) {
            header('Location: index.php?page=auth&action=login');
            exit();
        }
        
        $recommendations = $this->recommender->getRecommendations($_SESSION['user_id']);
        require 'views/cars/recommendations.php';
    }
    
    private function toggleFavorite() {
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'You must be logged in to add favorites']);
            exit();
        }
        
        $userId = $_SESSION['user_id'];
        $carId = isset($_POST['car_id']) ? (int)$_POST['car_id'] : 0;
        
        // Check if already a favorite
        $checkQuery = "SELECT * FROM user_favorites WHERE user_id = ? AND car_id = ?";
        $stmt = $this->db->prepare($checkQuery);
        $stmt->bind_param('ii', $userId, $carId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Remove from favorites
            $deleteQuery = "DELETE FROM user_favorites WHERE user_id = ? AND car_id = ?";
            $stmt = $this->db->prepare($deleteQuery);
            $stmt->bind_param('ii', $userId, $carId);
            $success = $stmt->execute();
            
            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Removed from favorites' : 'Failed to remove from favorites',
                'isFavorite' => false
            ]);
        } else {
            // Add to favorites
            $insertQuery = "INSERT INTO user_favorites (user_id, car_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($insertQuery);
            $stmt->bind_param('ii', $userId, $carId);
            $success = $stmt->execute();
            
            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Added to favorites' : 'Failed to add to favorites',
                'isFavorite' => true
            ]);
        }
        
        exit();
    }
    
    // Admin methods for car management
    private function addCar() {
        if (!isAdmin()) {
            $_SESSION['error'] = "Unauthorized access.";
            header('Location: index.php?page=cars');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process form submission
            // Implementation details omitted for brevity
            $_SESSION['success'] = "Car added successfully.";
            header('Location: index.php?page=cars');
            exit();
        }
        
        // Get categories for form
        $categoriesQuery = "SELECT * FROM car_categories ORDER BY name ASC";
        $categoriesResult = $this->db->query($categoriesQuery);
        $categories = $categoriesResult->fetch_all(MYSQLI_ASSOC);
        
        require 'views/cars/add.php';
    }
    
    private function editCar() {
        if (!isAdmin()) {
            $_SESSION['error'] = "Unauthorized access.";
            header('Location: index.php?page=cars');
            exit();
        }
        
        $car_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $car = $this->car->getCarById($car_id);
        
        if (!$car) {
            $_SESSION['error'] = "Car not found.";
            header('Location: index.php?page=cars');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process form submission
            // Implementation details omitted for brevity
            $_SESSION['success'] = "Car updated successfully.";
            header('Location: index.php?page=cars');
            exit();
        }
        
           // Get categories for form
           $categoriesQuery = "SELECT * FROM car_categories ORDER BY name ASC";
           $categoriesResult = $this->db->query($categoriesQuery);
           $categories = $categoriesResult->fetch_all(MYSQLI_ASSOC);
           
           require 'views/cars/edit.php';
       }
       
       private function deleteCar() {
           if (!isAdmin()) {
               $_SESSION['error'] = "Unauthorized access.";
               header('Location: index.php?page=cars');
               exit();
           }
           
           $car_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
           
           // Check if car exists
           $car = $this->car->getCarById($car_id);
           if (!$car) {
               $_SESSION['error'] = "Car not found.";
               header('Location: index.php?page=cars');
               exit();
           }
           
           // Check if car has active rentals
           $rentalQuery = "SELECT COUNT(*) as count FROM rentals WHERE car_id = ? AND status IN ('pending', 'approved', 'active')";
           $stmt = $this->db->prepare($rentalQuery);
           $stmt->bind_param('i', $car_id);
           $stmt->execute();
           $result = $stmt->get_result();
           $activeRentals = $result->fetch_assoc()['count'];
           $stmt->close();
           
           if ($activeRentals > 0) {
               $_SESSION['error'] = "Cannot delete car with active rentals.";
               header('Location: index.php?page=cars');
               exit();
           }
           
           // Delete car
           $deleteQuery = "DELETE FROM cars WHERE car_id = ?";
           $stmt = $this->db->prepare($deleteQuery);
           $stmt->bind_param('i', $car_id);
           $success = $stmt->execute();
           $stmt->close();
           
           if ($success) {
               $_SESSION['success'] = "Car deleted successfully.";
           } else {
               $_SESSION['error'] = "Failed to delete car.";
           }
           
           header('Location: index.php?page=cars');
           exit();
       }


private function submitReview() {
    // Check if user is logged in
    if (!isLoggedIn()) {
        $_SESSION['error'] = "You must be logged in to submit a review.";
        header('Location: index.php?page=auth&action=login');
        exit();
    }
    
    // Get form data
    $carId = isset($_POST['car_id']) ? (int)$_POST['car_id'] : 0;
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $feedback = isset($_POST['feedback']) ? sanitize($_POST['feedback']) : '';
    $userId = $_SESSION['user_id'];
    
    // Validate data
    if ($carId <= 0 || $rating <= 0 || $rating > 5) {
        $_SESSION['error'] = "Invalid review data.";
        header('Location: index.php?page=cars&action=view&id=' . $carId);
        exit();
    }
    
    // Check if the user has rented this car before
    $rentalCheckQuery = "SELECT r.rental_id FROM rentals r 
                        WHERE r.user_id = ? AND r.car_id = ? AND r.status = 'completed'";
    $stmt = $this->db->prepare($rentalCheckQuery);
    $stmt->bind_param('ii', $userId, $carId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $_SESSION['error'] = "You can only review cars you have rented.";
        header('Location: index.php?page=cars&action=view&id=' . $carId);
        exit();
    }
    
    $rentalId = $result->fetch_assoc()['rental_id'];
    
    // Check if user has already reviewed this car
    $reviewCheckQuery = "SELECT history_id FROM rental_history 
                        WHERE user_id = ? AND car_id = ? AND rating IS NOT NULL";
    $stmt = $this->db->prepare($reviewCheckQuery);
    $stmt->bind_param('ii', $userId, $carId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Update existing review
        $historyId = $result->fetch_assoc()['history_id'];
        $updateQuery = "UPDATE rental_history 
                        SET rating = ?, feedback = ?, created_at = CURRENT_TIMESTAMP 
                        WHERE history_id = ?";
        $stmt = $this->db->prepare($updateQuery);
        $stmt->bind_param('isi', $rating, $feedback, $historyId);
        $success = $stmt->execute();
        
        if ($success) {
            $_SESSION['success'] = "Your review has been updated.";
        } else {
            $_SESSION['error'] = "Failed to update your review. Please try again.";
        }
    } else {
        // Insert new review
        $insertQuery = "INSERT INTO rental_history (rental_id, user_id, car_id, rating, feedback) 
                        VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($insertQuery);
        $stmt->bind_param('iiiis', $rentalId, $userId, $carId, $rating, $feedback);
        $success = $stmt->execute();
        
        if ($success) {
            $_SESSION['success'] = "Thank you for your review!";
        } else {
            $_SESSION['error'] = "Failed to submit your review. Please try again.";
        }
    }
    
    // Redirect back to car view page
    header('Location: index.php?page=cars&action=view&id=' . $carId);
    exit();
}

   }
   
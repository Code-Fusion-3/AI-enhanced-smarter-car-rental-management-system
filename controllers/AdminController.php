<?php
require_once 'models/User.php';
require_once 'models/Car.php';
require_once 'models/Rental.php';
require_once 'models/Admin.php';

class AdminController {
    private $db;
    private $user;
    private $car;
    private $rental;
    private $admin;
    
    public function __construct() {
        global $conn;
        $this->db = $conn;
        $this->user = new User($conn);
        $this->car = new Car($conn);
        $this->rental = new Rental($conn);
        $this->admin = new Admin($conn);
    }
    
    public function handle() {
        // Check if user is admin
        if (!isAdmin()) {
            $_SESSION['error'] = "You don't have permission to access this page.";
            header('Location: index.php');
            exit();
        }
        
        $action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';
        
        switch($action) {
            case 'dashboard':
                $this->showDashboard();
                break;
            case 'users':
                $this->manageUsers();
                break;
            case 'cars':
                $this->manageCars();
                break;
            case 'rentals':
                $this->manageRentals();
            //     break;
            // case 'promotions':
            //     $this->managePromotions();
            //     break;
           
            // case 'reports':
            //     $this->showReports();
            //     break;
            // case 'settings':
            //     $this->manageSettings();
            //     break;
            default:
                $this->showDashboard();
        }
    }
    
    private function showDashboard() {
        // Get system statistics
        $stats = $this->admin->getSystemStatistics();
        
        // Get recent activities
        $recentActivities = $this->admin->getRecentActivities(10);
        
        // Get revenue metrics
        $revenueMetrics = $this->admin->getRevenueMetrics();
        
        // Get fleet utilization
        $fleetUtilization = $this->admin->getFleetUtilization();
        
        // Get top performing cars
        $topCars = $this->admin->getTopPerformingCars(5);
        
        // Get recent rentals
        $recentRentals = $this->admin->getRecentRentals(5);
        
        // Get pending rentals that need approval
        $pendingRentals = $this->admin->getPendingRentals(5);
        
        require 'views/admin/dashboard.php';
    }
    private function manageUsers() {
        $subaction = isset($_GET['subaction']) ? $_GET['subaction'] : 'list';
        
        switch($subaction) {
            case 'list':
                $this->listUsers();
                break;
            case 'add':
                $this->addUser();
                break;
            case 'edit':
                $this->editUser();
                break;
            case 'update':
                $this->updateUser();
                break;
            case 'delete':
                $this->deleteUser();
                break;
            case 'view':
                $this->viewUser();
                break;
            default:
                $this->listUsers();
        }
    }
    
    private function listUsers() {
        // Get filter parameters
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $role = isset($_GET['role']) ? $_GET['role'] : '';
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        
        // Determine if we should exclude admins
        // This could be based on a URL parameter, user preference, or fixed behavior
        $excludeAdmins = isset($_GET['customers_only']) && $_GET['customers_only'] == '1';
        
        // Pagination
        $perPage = 10;
        $currentPage = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
        $offset = ($currentPage - 1) * $perPage;
        
        // Get total count for pagination
        $totalUsers = $this->admin->countUsers($search, $role, $status, $excludeAdmins);
        $totalPages = ceil($totalUsers / $perPage);
        
        // Get users for current page
        $users = $this->admin->getAllUsers($search, $role, $status, $perPage, $offset, $excludeAdmins);
        
        // Build query string for pagination links
        $queryParams = '';
        if (!empty($search)) $queryParams .= '&search=' . urlencode($search);
        if (!empty($role)) $queryParams .= '&role=' . urlencode($role);
        if (!empty($status)) $queryParams .= '&status=' . urlencode($status);
        if ($excludeAdmins) $queryParams .= '&customers_only=1';
        
        require 'views/admin/users/list.php';
    }
    
    
    private function addUser() {
        // Show add user form
        require 'views/admin/users/add.php';
    }
    
    private function editUser() {
        $userId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $user = $this->admin->getUserById($userId);
        
        if (!$user) {
            $_SESSION['error'] = "User not found.";
            header('Location: index.php?page=admin&action=users');
            exit();
        }
        
        require 'views/admin/users/edit.php';
    }
    
    
    private function updateUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin&action=users');
            exit();
        }
        
        $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
        $userData = [
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'full_name' => $_POST['full_name'],
            'role' => $_POST['role'],
            'status' => $_POST['status']
        ];
        
        // Add optional fields if they exist
        if (isset($_POST['phone'])) $userData['phone'] = $_POST['phone'];
        if (isset($_POST['address'])) $userData['address'] = $_POST['address'];
        if (isset($_POST['driver_license'])) $userData['driver_license'] = $_POST['driver_license'];
        
        // Handle profile image upload if provided
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['size'] > 0) {
            $uploadDir = 'assets/images/profiles/';
            $fileName = time() . '_' . basename($_FILES['profile_image']['name']);
            $uploadFile = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
                $userData['profile_image'] = $uploadFile;
            }
        }
        
        $result = $this->admin->updateUser($userId, $userData);
        
        if ($result) {
            $_SESSION['success'] = "User updated successfully.";
            logActivity($this->db, $_SESSION['user_id'], 'update_user', "Updated user ID: $userId");
        } else {
            $_SESSION['error'] = "Failed to update user.";
        }
        
        header('Location: index.php?page=admin&action=users');
        exit();
    }
    
    private function deleteUser() {
        $userId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($userId === $_SESSION['user_id']) {
            $_SESSION['error'] = "You cannot delete your own account.";
            header('Location: index.php?page=admin&action=users');
            exit();
        }
        
        $result = $this->admin->deleteUser($userId);
        
        if ($result) {
            $_SESSION['success'] = "User deleted successfully.";
            logActivity($this->db, $_SESSION['user_id'], 'delete_user', "Deleted user ID: $userId");
        } else {
            $_SESSION['error'] = "Failed to delete user. The user may have active rentals.";
        }
        
        header('Location: index.php?page=admin&action=users');
        exit();
    }
    
    private function viewUser() {
        $userId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $user = $this->admin->getUserById($userId);
        
        if (!$user) {
            $_SESSION['error'] = "User not found.";
            header('Location: index.php?page=admin&action=users');
            exit();
        }
        
        // Get user's rental history
        // This would require additional methods in the Admin model
        
        require 'views/admin/users/view.php';
    }
    private function manageCars() {
        $subaction = isset($_GET['subaction']) ? $_GET['subaction'] : 'list';
        
        switch($subaction) {
            case 'list':
                $this->listCars();
                break;
            case 'add':
                $this->addCar();
                break;
            case 'edit':
                $this->editCar();
                break;
            case 'update':
                $this->updateCar();
                break;
            case 'delete':
                $this->deleteCar();
                break;
            case 'view':
                $this->viewCar();
                break;
                case 'create':
                    $this->createCar();
                    break;
                case 'maintenance':
                    $this->manageMaintenance();
                    break;
                    case 'performance':
                        $this->getCarPerformance();
                        break;
            default:
                $this->listCars();
        }
    }
    
    private function listCars() {
        // Get filter parameters
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        
        // Pagination
        $perPage = 10;
        $currentPage = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
        $offset = ($currentPage - 1) * $perPage;
        
        // Get total count for pagination
        $totalCars = $this->car->countCars($search, $category, $status);
        $totalPages = ceil($totalCars / $perPage);
        
        // Get cars for current page
        $cars = $this->car->getAllCars($search, $category, $status, $perPage, $offset);
        
        // Get categories for filter dropdown
        $categories = $this->car->getAllCategories();
        
        // Build query string for pagination links
        $queryParams = '';
        if (!empty($search)) $queryParams .= '&search=' . urlencode($search);
        if (!empty($category)) $queryParams .= '&category=' . urlencode($category);
        if (!empty($status)) $queryParams .= '&status=' . urlencode($status);
        
        require 'views/admin/cars/list.php';
        exit();
    }
    
    
    private function addCar() {
        // Get categories for form
        $categoriesQuery = "SELECT * FROM car_categories ORDER BY name ASC";
        $categoriesResult = $this->db->query($categoriesQuery);
        $categories = [];
        
        if ($categoriesResult) {
            while ($row = $categoriesResult->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        
        require 'views/admin/cars/add.php';
        exit();

    }
    
    private function editCar() {
        $carId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $car = $this->car->getCarById($carId);
        
        if (!$car) {
            $_SESSION['error'] = "Car not found.";
            header('Location: index.php?page=admin&action=cars');
            exit();
        }
        
        // Get categories for form
        $categoriesQuery = "SELECT * FROM car_categories ORDER BY name ASC";
        $categoriesResult = $this->db->query($categoriesQuery);
        $categories = [];
        
        if ($categoriesResult) {
            while ($row = $categoriesResult->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        
        require 'views/admin/cars/edit.php';
        exit();
    }
    
    private function updateCar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin&action=cars');
            exit();
        }
        
        $carId = isset($_POST['car_id']) ? (int)$_POST['car_id'] : 0;
        $carData = [
            'make' => $_POST['make'],
            'model' => $_POST['model'],
            'year' => (int)$_POST['year'],
            'registration_number' => $_POST['registration_number'],
            'daily_rate' => (float)$_POST['daily_rate'],
            'status' => $_POST['status'],
            'features' => $_POST['features'],
            'category_id' => (int)$_POST['category_id'],
            'mileage' => isset($_POST['mileage']) ? (int)$_POST['mileage'] : null,
            'fuel_type' => $_POST['fuel_type'],
            'transmission' => $_POST['transmission'],
            'seats' => isset($_POST['seats']) ? (int)$_POST['seats'] : null,
            'base_rate' => (float)$_POST['base_rate'],
            'weekend_rate' => isset($_POST['weekend_rate']) ? (float)$_POST['weekend_rate'] : null,
            'weekly_rate' => isset($_POST['weekly_rate']) ? (float)$_POST['weekly_rate'] : null,
            'monthly_rate' => isset($_POST['monthly_rate']) ? (float)$_POST['monthly_rate'] : null
        ];
        
        // Handle image upload if provided
        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            $uploadDir = 'assets/images/cars/';
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $uploadFile = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $carData['image_url'] = $uploadFile;
            }
        }
        
        $result = $this->car->updateCar($carId, $carData);
        
        if ($result) {
            $_SESSION['success'] = "Car updated successfully.";
            logActivity($this->db, $_SESSION['user_id'], 'update_car', "Updated car ID: $carId");
        } else {
            $_SESSION['error'] = "Failed to update car.";
        }
        
        header('Location: index.php?page=admin&action=cars');
        exit();
    }
    
    private function deleteCar() {
        $carId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        // Check if car has active rentals
        $activeRentalsQuery = "SELECT COUNT(*) as count FROM rentals WHERE car_id = ? AND status IN ('pending', 'approved', 'active')";
        $stmt = $this->db->prepare($activeRentalsQuery);
        $stmt->bind_param("i", $carId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            $_SESSION['error'] = "Cannot delete car with active rentals.";
            header('Location: index.php?page=admin&action=cars');
            exit();
        }
        
        $result = $this->car->deleteCar($carId);
        
        if ($result) {
            $_SESSION['success'] = "Car deleted successfully.";
            logActivity($this->db, $_SESSION['user_id'], 'delete_car', "Deleted car ID: $carId");
        } else {
            $_SESSION['error'] = "Failed to delete car.";
        }
        
        header('Location: index.php?page=admin&action=cars');
        exit();
    }
    
    private function viewCar() {
        $carId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $car = $this->car->getCarById($carId);
        
        if (!$car) {
            $_SESSION['error'] = "Car not found.";
            header('Location: index.php?page=admin&action=cars');
            exit();
        }
        
        // Get category details
        $categoryQuery = "SELECT * FROM car_categories WHERE category_id = ?";
        $stmt = $this->db->prepare($categoryQuery);
        $stmt->bind_param('i', $car['category_id']);
        $stmt->execute();
        $categoryResult = $stmt->get_result();
        $category = $categoryResult->num_rows > 0 ? $categoryResult->fetch_assoc() : null;
        
        // Get rental history for this car
        $rentalHistoryQuery = "SELECT r.*, u.username, u.full_name 
                              FROM rentals r 
                              JOIN users u ON r.user_id = u.user_id 
                              WHERE r.car_id = ? 
                              ORDER BY r.created_at DESC 
                              LIMIT 10";
        $stmt = $this->db->prepare($rentalHistoryQuery);
        $stmt->bind_param('i', $carId);
        $stmt->execute();
        $rentalHistoryResult = $stmt->get_result();
        $rentalHistory = $rentalHistoryResult->fetch_all(MYSQLI_ASSOC);
        
        // Get maintenance records
        $maintenanceQuery = "SELECT * FROM maintenance_records 
                            WHERE car_id = ? 
                            ORDER BY start_date DESC";
        $stmt = $this->db->prepare($maintenanceQuery);
        $stmt->bind_param('i', $carId);
        $stmt->execute();
        $maintenanceResult = $stmt->get_result();
        $maintenanceRecords = $maintenanceResult->fetch_all(MYSQLI_ASSOC);
        
        require 'views/admin/cars/view.php';
        exit();
    }
    private function createCar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin&action=cars');
            exit();
        }
        error_log("File upload attempt: " . print_r($_FILES, true));
        
        $carData = [
            'make' => $_POST['make'],
            'model' => $_POST['model'],
            'year' => (int)$_POST['year'],
            'registration_number' => $_POST['registration_number'],
            'daily_rate' => (float)$_POST['daily_rate'],
            'status' => $_POST['status'],
            'features' => $_POST['features'],
            'category_id' => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
            'mileage' => !empty($_POST['mileage']) ? (int)$_POST['mileage'] : null,
            'fuel_type' => !empty($_POST['fuel_type']) ? $_POST['fuel_type'] : null,
            'transmission' => !empty($_POST['transmission']) ? $_POST['transmission'] : null,
            'seats' => !empty($_POST['seats']) ? (int)$_POST['seats'] : null,
            'base_rate' => (float)$_POST['base_rate'],
            'weekend_rate' => !empty($_POST['weekend_rate']) ? (float)$_POST['weekend_rate'] : null,
            'weekly_rate' => !empty($_POST['weekly_rate']) ? (float)$_POST['weekly_rate'] : null,
            'monthly_rate' => !empty($_POST['monthly_rate']) ? (float)$_POST['monthly_rate'] : null
        ];
        
        // Handle image upload if provided
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0 && $_FILES['image']['size'] > 0) {
            $uploadDir = 'assets/images/cars/';
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    $_SESSION['error'] = "Failed to create upload directory.";
                    header('Location: index.php?page=admin&action=cars&subaction=add');
                    exit();
                }
            }
            
            // Check if directory is writable
            if (!is_writable($uploadDir)) {
                chmod($uploadDir, 0777);
            }
            
            // Generate a unique filename
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $uploadFile = $uploadDir . $fileName;
            
            // Check file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = $_FILES['image']['type'];
            
            if (!in_array($fileType, $allowedTypes)) {
                $_SESSION['error'] = "Only JPG, PNG, and GIF images are allowed.";
                header('Location: index.php?page=admin&action=cars&subaction=add');
                exit();
            }
            
            // Check file size (max 2MB)
            if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                $_SESSION['error'] = "Image size should be less than 2MB.";
                header('Location: index.php?page=admin&action=cars&subaction=add');
                exit();
            }
            
            // Try to upload the file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $carData['image_url'] = $uploadFile;
            } else {
                // Log the error for debugging
                error_log("Failed to upload image: " . print_r($_FILES['image'], true));
                
                $_SESSION['error'] = "Failed to upload image. Please try again.";
                header('Location: index.php?page=admin&action=cars&subaction=add');
                exit();
            }
        } else if (isset($_FILES['image']) && $_FILES['image']['error'] != 0) {
            // Log the file upload error
            $uploadErrors = [
                1 => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
                2 => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
                3 => "The uploaded file was only partially uploaded",
                4 => "No file was uploaded",
                6 => "Missing a temporary folder",
                7 => "Failed to write file to disk",
                8 => "A PHP extension stopped the file upload"
            ];
            
            $errorCode = $_FILES['image']['error'];
            $errorMessage = isset($uploadErrors[$errorCode]) ? $uploadErrors[$errorCode] : "Unknown upload error";
            
            error_log("File upload error: " . $errorMessage);
        }
        
        $result = $this->car->addCar($carData);
        
        if ($result) {
            $_SESSION['success'] = "Car added successfully.";
            logActivity($this->db, $_SESSION['user_id'], 'add_car', "Added new car: {$carData['make']} {$carData['model']}");
        } else {
            $_SESSION['error'] = "Failed to add car.";
        }
        
        header('Location: index.php?page=admin&action=cars');
        exit();
    }
    private function manageMaintenance() {
        $subaction = isset($_GET['subaction']) ? $_GET['subaction'] : 'add';
        
        switch($subaction) {
            case 'add':
                $this->addMaintenanceRecord();
                break;
            case 'create':
                $this->createMaintenanceRecord();
                break;
            case 'edit':
                $this->editMaintenanceRecord();
                break;
            case 'update':
                $this->updateMaintenanceRecord();
                break;
            case 'delete':
                $this->deleteMaintenanceRecord();
                break;
            default:
                $this->addMaintenanceRecord();
        }
    }
    
    private function addMaintenanceRecord() {
        $carId = isset($_GET['car_id']) ? (int)$_GET['car_id'] : 0;
        
        if ($carId <= 0) {
            $_SESSION['error'] = "Invalid car ID.";
            header('Location: index.php?page=admin&action=cars');
            exit();
        }
        
        // Get car details
        $car = $this->car->getCarById($carId);
        
        if (!$car) {
            $_SESSION['error'] = "Car not found.";
            header('Location: index.php?page=admin&action=cars');
            exit();
        }
        
        require 'views/admin/maintenance/add.php';
        exit();
    }
    
    private function createMaintenanceRecord() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin&action=cars');
            exit();
        }
        
        $carId = isset($_POST['car_id']) ? (int)$_POST['car_id'] : 0;
        $maintenanceType = isset($_POST['maintenance_type']) ? $_POST['maintenance_type'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $cost = isset($_POST['cost']) ? (float)$_POST['cost'] : 0;
        $startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
        $endDate = isset($_POST['end_date']) ? $_POST['end_date'] : null;
        $status = isset($_POST['status']) ? $_POST['status'] : 'scheduled';
        
        // Validate input
        if ($carId <= 0 || empty($maintenanceType) || empty($description) || empty($startDate)) {
            $_SESSION['error'] = "Please fill in all required fields.";
            header('Location: index.php?page=admin&action=maintenance&car_id=' . $carId);
            exit();
        }
        
        // Insert maintenance record
        $sql = "INSERT INTO maintenance_records (car_id, maintenance_type, description, cost, start_date, end_date, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("issdsss", $carId, $maintenanceType, $description, $cost, $startDate, $endDate, $status);
        $result = $stmt->execute();
        
        if ($result) {
            // If maintenance is in progress, update car status
            if ($status == 'in_progress') {
                $updateSql = "UPDATE cars SET status = 'maintenance' WHERE car_id = ?";
                $updateStmt = $this->db->prepare($updateSql);
                $updateStmt->bind_param("i", $carId);
                $updateStmt->execute();
            }
            
            $_SESSION['success'] = "Maintenance record added successfully.";
            logActivity($this->db, $_SESSION['user_id'], 'add_maintenance', "Added maintenance record for car ID: $carId");
        } else {
            $_SESSION['error'] = "Failed to add maintenance record.";
        }
        
        header('Location: index.php?page=admin&action=cars&subaction=view&id=' . $carId);
        exit();
    }
    
    private function editMaintenanceRecord() {
        $maintenanceId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($maintenanceId <= 0) {
            $_SESSION['error'] = "Invalid maintenance record ID.";
            header('Location: index.php?page=admin&action=cars');
            exit();
        }
        
        // Get maintenance record
        $sql = "SELECT * FROM maintenance_records WHERE maintenance_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $maintenanceId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $_SESSION['error'] = "Maintenance record not found.";
            header('Location: index.php?page=admin&action=cars');
            exit();
        }
        
        $maintenance = $result->fetch_assoc();
        
        // Get car details
        $car = $this->car->getCarById($maintenance['car_id']);
        
        require 'views/admin/maintenance/edit.php';
        exit();
    }
    
    private function updateMaintenanceRecord() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin&action=cars');
            exit();
        }
        
        $maintenanceId = isset($_POST['maintenance_id']) ? (int)$_POST['maintenance_id'] : 0;
        $carId = isset($_POST['car_id']) ? (int)$_POST['car_id'] : 0;
        $maintenanceType = isset($_POST['maintenance_type']) ? $_POST['maintenance_type'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $cost = isset($_POST['cost']) ? (float)$_POST['cost'] : 0;
        $startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
        $endDate = isset($_POST['end_date']) ? $_POST['end_date'] : null;
        $status = isset($_POST['status']) ? $_POST['status'] : 'scheduled';
        
        // Validate input
        if ($maintenanceId <= 0 || $carId <= 0 || empty($maintenanceType) || empty($description) || empty($startDate)) {
            $_SESSION['error'] = "Please fill in all required fields.";
            header('Location: index.php?page=admin&action=maintenance&subaction=edit&id=' . $maintenanceId);
            exit();
        }
        
        // Update maintenance record
        $sql = "UPDATE maintenance_records 
                SET maintenance_type = ?, description = ?, cost = ?, start_date = ?, end_date = ?, status = ? 
                WHERE maintenance_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssdssi", $maintenanceType, $description, $cost, $startDate, $endDate, $status, $maintenanceId);
        $result = $stmt->execute();
        
        if ($result) {
            // Update car status based on maintenance status
            if ($status == 'in_progress') {
                $updateSql = "UPDATE cars SET status = 'maintenance' WHERE car_id = ?";
                $updateStmt = $this->db->prepare($updateSql);
                $updateStmt->bind_param("i", $carId);
                $updateStmt->execute();
            } else if ($status == 'completed') {
                // Check if there are other in-progress maintenance records for this car
                $checkSql = "SELECT COUNT(*) as count FROM maintenance_records 
                            WHERE car_id = ? AND status = 'in_progress' AND maintenance_id != ?";
                $checkStmt = $this->db->prepare($checkSql);
                $checkStmt->bind_param("ii", $carId, $maintenanceId);
                $checkStmt->execute();
                $checkResult = $checkStmt->get_result();
                $row = $checkResult->fetch_assoc();
                
                // If no other in-progress maintenance, set car status back to available
                if ($row['count'] == 0) {
                    $updateSql = "UPDATE cars SET status = 'available' WHERE car_id = ?";
                    $updateStmt = $this->db->prepare($updateSql);
                    $updateStmt->bind_param("i", $carId);
                    $updateStmt->execute();
                }
            }
            
            $_SESSION['success'] = "Maintenance record updated successfully.";
            logActivity($this->db, $_SESSION['user_id'], 'update_maintenance', "Updated maintenance record ID: $maintenanceId");
        } else {
            $_SESSION['error'] = "Failed to update maintenance record.";
        }
        
        header('Location: index.php?page=admin&action=cars&subaction=view&id=' . $carId);
        exit();
    }
    
    private function deleteMaintenanceRecord() {
        $maintenanceId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($maintenanceId <= 0) {
            $_SESSION['error'] = "Invalid maintenance record ID.";
            header('Location: index.php?page=admin&action=cars');
            exit();
        }
        
        // Get car ID before deleting
        $sql = "SELECT car_id, status FROM maintenance_records WHERE maintenance_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $maintenanceId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $_SESSION['error'] = "Maintenance record not found.";
            header('Location: index.php?page=admin&action=cars');
            exit();
        }
        
        $maintenance = $result->fetch_assoc();
        $carId = $maintenance['car_id'];
        $maintenanceStatus = $maintenance['status'];
        
        // Delete maintenance record
        $deleteSql = "DELETE FROM maintenance_records WHERE maintenance_id = ?";
        $deleteStmt = $this->db->prepare($deleteSql);
        $deleteStmt->bind_param("i", $maintenanceId);
        $result = $deleteStmt->execute();
        
        if ($result) {
            // If deleted record was in-progress, check if there are other in-progress records
            if ($maintenanceStatus == 'in_progress') {
                $checkSql = "SELECT COUNT(*) as count FROM maintenance_records 
                            WHERE car_id = ? AND status = 'in_progress'";
                $checkStmt = $this->db->prepare($checkSql);
                $checkStmt->bind_param("i", $carId);
                $checkStmt->execute();
                $checkResult = $checkStmt->get_result();
                $row = $checkResult->fetch_assoc();
                
                // If no other in-progress maintenance, set car status back to available
                if ($row['count'] == 0) {
                    $updateSql = "UPDATE cars SET status = 'available' WHERE car_id = ?";
                    $updateStmt = $this->db->prepare($updateSql);
                    $updateStmt->bind_param("i", $carId);
                    $updateStmt->execute();
                }
            }
            
            $_SESSION['success'] = "Maintenance record deleted successfully.";
            logActivity($this->db, $_SESSION['user_id'], 'delete_maintenance', "Deleted maintenance record ID: $maintenanceId");
        } else {
            $_SESSION['error'] = "Failed to delete maintenance record.";
        }
        
        header('Location: index.php?page=admin&action=cars&subaction=view&id=' . $carId);
        exit();
    }
    private function getCarPerformance() {
        // Temporary debugging
// error_log("Performance data request: " . print_r($_GET, true));
try {
    // Existing code...
} catch (Exception $e) {
    error_log("Performance data error: " . $e->getMessage());
    // Rest of error handling...
}

        // Set headers early to prevent any HTML output before JSON
        if (isset($_GET['format']) && $_GET['format'] === 'json') {
            header('Content-Type: application/json');
        }
        
        $carId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($carId <= 0) {
            if (isset($_GET['format']) && $_GET['format'] === 'json') {
                echo json_encode(['error' => 'Invalid car ID']);
                exit();
            }
            
            $_SESSION['error'] = "Invalid car ID.";
            header('Location: index.php?page=admin&action=cars');
            exit();
        }
        
        try {
            // Get the last 12 months of data
            $months = 12;
            $labels = [];
            $revenue = [];
            $utilization = [];
            
            // Current month and year
            $currentMonth = date('n');
            $currentYear = date('Y');
            
            for ($i = 0; $i < $months; $i++) {
                // Calculate month and year for this data point
                $month = $currentMonth - $i;
                $year = $currentYear;
                
                while ($month <= 0) {
                    $month += 12;
                    $year--;
                }
                
                // Format month name
                $monthName = date('M', mktime(0, 0, 0, $month, 1, $year));
                $monthYear = $monthName . ' ' . $year;
                
                // Add to labels array (in reverse order so oldest is first)
                array_unshift($labels, $monthYear);
                
                // Get start and end date for this month
                $startDate = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
                $endDate = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));
                
                // Calculate revenue for this month
                $revenueSql = "SELECT COALESCE(SUM(total_cost), 0) as monthly_revenue 
                              FROM rentals 
                              WHERE car_id = ? 
                              AND status IN ('completed', 'active')
                              AND (
                                  (start_date BETWEEN ? AND ?) 
                                  OR (end_date BETWEEN ? AND ?)
                                  OR (start_date <= ? AND end_date >= ?)
                              )";
                
                $revenueStmt = $this->db->prepare($revenueSql);
                $revenueStmt->bind_param("issssss", $carId, $startDate, $endDate, $startDate, $endDate, $startDate, $endDate);
                $revenueStmt->execute();
                $revenueResult = $revenueStmt->get_result();
                $revenueRow = $revenueResult->fetch_assoc();
                
                $monthlyRevenue = floatval($revenueRow['monthly_revenue']);
                array_unshift($revenue, $monthlyRevenue);
                
                // Calculate utilization for this month (percentage of days the car was rented)
                $daysInMonth = date('t', mktime(0, 0, 0, $month, 1, $year));
                
                $utilizationSql = "SELECT 
                                  COALESCE(SUM(
                                      LEAST(DATEDIFF(LEAST(end_date, ?), GREATEST(start_date, ?)) + 1, ?)
                                  ), 0) as days_rented
                                  FROM rentals 
                                  WHERE car_id = ? 
                                  AND status IN ('completed', 'active')
                                  AND (
                                      (start_date BETWEEN ? AND ?) 
                                      OR (end_date BETWEEN ? AND ?)
                                      OR (start_date <= ? AND end_date >= ?)
                                  )";
                
                $utilizationStmt = $this->db->prepare($utilizationSql);
                $utilizationStmt->bind_param("sssissssss", 
                    $endDate, $startDate, $daysInMonth, 
                    $carId, 
                    $startDate, $endDate, $startDate, $endDate, $startDate, $endDate
                );
                $utilizationStmt->execute();
                $utilizationResult = $utilizationStmt->get_result();
                $utilizationRow = $utilizationResult->fetch_assoc();
                
                $daysRented = intval($utilizationRow['days_rented']);
                $monthlyUtilization = min(100, round(($daysRented / $daysInMonth) * 100, 1));
                array_unshift($utilization, $monthlyUtilization);
            }
            
            $performanceData = [
                'labels' => $labels,
                'revenue' => $revenue,
                'utilization' => $utilization
            ];
            
            if (isset($_GET['format']) && $_GET['format'] === 'json') {
                echo json_encode($performanceData);
                exit();
            }
            
            // If not JSON format, redirect back to car view
            header('Location: index.php?page=admin&action=cars&subaction=view&id=' . $carId);
            exit();
        } catch (Exception $e) {
            if (isset($_GET['format']) && $_GET['format'] === 'json') {
                echo json_encode(['error' => 'Failed to fetch performance data: ' . $e->getMessage()]);
                exit();
            }
            
            $_SESSION['error'] = "Failed to fetch performance data: " . $e->getMessage();
            header('Location: index.php?page=admin&action=cars&subaction=view&id=' . $carId);
            exit();
        }
    }

    private function manageRentals() {
        $subaction = isset($_GET['subaction']) ? $_GET['subaction'] : 'list';
        
        switch($subaction) {
            case 'list':
                $this->listRentals();
                break;
            case 'view':
                $this->viewRental();
                break;
            case 'edit':
                $this->editRental();
                break;
            case 'update':
                $this->updateRental();
                break;
            case 'delete':
                $this->deleteRental();
                break;
            case 'approve':
                $this->approveRental();
                break;
            case 'complete':
                $this->completeRental();
                break;
            case 'cancel':
                $this->cancelRental();
                break;
            default:
                $this->listRentals();
        }
    }
    
    private function listRentals() {
        // Get filter parameters
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
        $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';
        
        // Pagination
        $page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
        $itemsPerPage = 10;
        $offset = ($page - 1) * $itemsPerPage;
        
        // Build query
        $params = [];
        $types = '';
        
        $sql = "SELECT r.*, 
                      u.username, u.full_name, 
                      c.make, c.model, c.registration_number 
               FROM rentals r
               JOIN users u ON r.user_id = u.user_id
               JOIN cars c ON r.car_id = c.car_id
               WHERE 1=1";
        
        if (!empty($status)) {
            $sql .= " AND r.status = ?";
            $params[] = $status;
            $types .= 's';
        }
        
        if (!empty($search)) {
            $search = "%$search%";
            $sql .= " AND (u.username LIKE ? OR u.full_name LIKE ? OR c.make LIKE ? OR c.model LIKE ? OR c.registration_number LIKE ?)";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
            $types .= 'sssss';
        }
        
        if (!empty($dateFrom)) {
            $sql .= " AND r.start_date >= ?";
            $params[] = $dateFrom;
            $types .= 's';
        }
        
        if (!empty($dateTo)) {
            $sql .= " AND r.end_date <= ?";
            $params[] = $dateTo;
            $types .= 's';
        }
        
    // Count total for pagination
$countSql = str_replace("SELECT r.*, u.username, u.full_name, c.make, c.model, c.registration_number", "SELECT COUNT(*) as total", $sql);
$countStmt = $this->db->prepare($countSql);

if (!empty($params)) {
    $countStmt->bind_param($types, ...$params);
}

$countStmt->execute();
$countResult = $countStmt->get_result();
$resultRow = $countResult->fetch_assoc();
$totalItems = isset($resultRow['total']) ? $resultRow['total'] : 0;
$totalPages = ceil($totalItems / $itemsPerPage);


        // Get paginated results
        $sql .= " ORDER BY r.created_at DESC LIMIT ?, ?";
        $params[] = $offset;
        $params[] = $itemsPerPage;
        $types .= 'ii';
        
        $stmt = $this->db->prepare($sql);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $rentals = $result->fetch_all(MYSQLI_ASSOC);
        
        // Get status counts for filter tabs
        $statusCountsSql = "SELECT status, COUNT(*) as count FROM rentals GROUP BY status";
        $statusCountsResult = $this->db->query($statusCountsSql);
        $statusCounts = [];
        
        while ($row = $statusCountsResult->fetch_assoc()) {
            $statusCounts[$row['status']] = $row['count'];
        }
        
        // Get total count
        $totalCount = array_sum($statusCounts);
        
        require 'views/admin/rentals/list.php';
        exit();
    }
    
    private function viewRental() {
        $rentalId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($rentalId <= 0) {
            $_SESSION['error'] = "Invalid rental ID.";
            header('Location: index.php?page=admin&action=rentals');
            exit();
        }
        
        $sql = "SELECT r.*, 
        r.status AS rental_status,
        u.user_id, u.username, u.full_name, u.email, u.phone, u.address, u.driver_license, u.profile_image,
        c.car_id, c.make, c.model, c.year, c.registration_number, c.daily_rate, 
        c.status AS car_status, c.image_url, c.features, c.category_id
        FROM rentals r
        JOIN users u ON r.user_id = u.user_id
        JOIN cars c ON r.car_id = c.car_id
        WHERE r.rental_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $rentalId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $_SESSION['error'] = "Rental not found.";
            header('Location: index.php?page=admin&action=rentals');
            exit();
        }
        
        $rental = $result->fetch_assoc();
        
        // Extract user data into a separate variable
        $user = [
            'user_id' => $rental['user_id'],
            'username' => $rental['username'],
            'full_name' => $rental['full_name'],
            'email' => $rental['email'],
            'phone' => $rental['phone'] ?? null,
            'address' => $rental['address'] ?? null,
            'driver_license' => $rental['driver_license'] ?? null,
            'profile_image' => $rental['profile_image'] ?? null
        ];
        
     // Extract car data into a separate variable
$car = [
    'car_id' => $rental['car_id'],
    'make' => $rental['make'],
    'model' => $rental['model'],
    'year' => $rental['year'],
    'registration_number' => $rental['registration_number'],
    'daily_rate' => $rental['daily_rate'],
    'status' => $rental['car_status'],  // Use the aliased column
    'image_url' => $rental['image_url'],
    'features' => $rental['features'],
    'category_id' => $rental['category_id']
];

        
        // Get category name if category_id exists
        $categoryName = "Uncategorized";
        if (!empty($car['category_id'])) {
            $catQuery = "SELECT name FROM car_categories WHERE category_id = ?";
            $catStmt = $this->db->prepare($catQuery);
            $catStmt->bind_param("i", $car['category_id']);
            $catStmt->execute();
            $catResult = $catStmt->get_result();
            if ($catResult->num_rows > 0) {
                $categoryName = $catResult->fetch_assoc()['name'];
            }
        }
        
        // Get payment information
        $paymentSql = "SELECT * FROM payments WHERE rental_id = ?";
        $paymentStmt = $this->db->prepare($paymentSql);
        $paymentStmt->bind_param("i", $rentalId);
        $paymentStmt->execute();
        $paymentResult = $paymentStmt->get_result();
        $payments = $paymentResult->fetch_all(MYSQLI_ASSOC);
        
        // Get promotion information if promotion_id exists
        $promotion = null;
        if (!empty($rental['promotion_id'])) {
            $promoSql = "SELECT * FROM promotions WHERE promotion_id = ?";
            $promoStmt = $this->db->prepare($promoSql);
            $promoStmt->bind_param("i", $rental['promotion_id']);
            $promoStmt->execute();
            $promoResult = $promoStmt->get_result();
            if ($promoResult->num_rows > 0) {
                $promotion = $promoResult->fetch_assoc();
            }
        }
        
        // Get rental history
        $historySql = "SELECT * FROM rental_history WHERE rental_id = ?";
        $historyStmt = $this->db->prepare($historySql);
        $historyStmt->bind_param("i", $rentalId);
        $historyStmt->execute();
        $historyResult = $historyStmt->get_result();
        $rentalHistory = $historyResult->fetch_all(MYSQLI_ASSOC);
        
        // Calculate rental duration
        $startDate = new DateTime($rental['start_date']);
        $endDate = new DateTime($rental['end_date']);
        $duration = $startDate->diff($endDate)->days + 1;
        
        require 'views/admin/rentals/view.php';
        exit();
    }
    
    
    private function editRental() {
        $rentalId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($rentalId <= 0) {
            $_SESSION['error'] = "Invalid rental ID.";
            header('Location: index.php?page=admin&action=rentals');
            exit();
        }
        
        $sql = "SELECT r.*, 
                      u.username, u.full_name, 
                      c.make, c.model, c.registration_number 
               FROM rentals r
               JOIN users u ON r.user_id = u.user_id
               JOIN cars c ON r.car_id = c.car_id
               WHERE r.rental_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $rentalId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $_SESSION['error'] = "Rental not found.";
            header('Location: index.php?page=admin&action=rentals');
            exit();
        }
        
        $rental = $result->fetch_assoc();
        
        // Get all users for dropdown
        $usersSql = "SELECT user_id, username, full_name FROM users ORDER BY username";
        $usersResult = $this->db->query($usersSql);
        $users = $usersResult->fetch_all(MYSQLI_ASSOC);
        
        // Get all available cars for dropdown
        $carsSql = "SELECT car_id, make, model, registration_number, daily_rate 
                    FROM cars 
                    WHERE status = 'available' OR car_id = ?
                    ORDER BY make, model";
        $carsStmt = $this->db->prepare($carsSql);
        $carsStmt->bind_param("i", $rental['car_id']);
        $carsStmt->execute();
        $carsResult = $carsStmt->get_result();
        $cars = $carsResult->fetch_all(MYSQLI_ASSOC);
        
        // Get promotions for dropdown
        $promotionsSql = "SELECT promotion_id, code, description, discount_percentage, discount_amount 
                         FROM promotions 
                         WHERE is_active = 1 
                         AND start_date <= CURDATE() 
                         AND end_date >= CURDATE()
                         ORDER BY code";
        $promotionsResult = $this->db->query($promotionsSql);
        $promotions = $promotionsResult->fetch_all(MYSQLI_ASSOC);
        
        require 'views/admin/rentals/edit.php';
        exit();
    }
    
    private function updateRental() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=admin&action=rentals');
            exit();
        }
        
        $rentalId = isset($_POST['rental_id']) ? (int)$_POST['rental_id'] : 0;
        $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
        $carId = isset($_POST['car_id']) ? (int)$_POST['car_id'] : 0;
        $startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
        $endDate = isset($_POST['end_date']) ? $_POST['end_date'] : '';
        $status = isset($_POST['status']) ? $_POST['status'] : '';
        $promotionId = isset($_POST['promotion_id']) && !empty($_POST['promotion_id']) ? (int)$_POST['promotion_id'] : null;
        $discountAmount = isset($_POST['discount_amount']) ? (float)$_POST['discount_amount'] : 0;
        $additionalCharges = isset($_POST['additional_charges']) ? (float)$_POST['additional_charges'] : 0;
        $pickupLocation = isset($_POST['pickup_location']) ? $_POST['pickup_location'] : '';
        $returnLocation = isset($_POST['return_location']) ? $_POST['return_location'] : '';
        $notes = isset($_POST['notes']) ? $_POST['notes'] : '';
        
        // Validate input
        if ($rentalId <= 0 || $userId <= 0 || $carId <= 0 || empty($startDate) || empty($endDate) || empty($status)) {
            $_SESSION['error'] = "Please fill in all required fields.";
            header('Location: index.php?page=admin&action=rentals&subaction=edit&id=' . $rentalId);
            exit();
        }
        
        // Calculate total cost
        $carSql = "SELECT daily_rate FROM cars WHERE car_id = ?";
        $carStmt = $this->db->prepare($carSql);
        $carStmt->bind_param("i", $carId);
        $carStmt->execute();
        $carResult = $carStmt->get_result();
        $car = $carResult->fetch_assoc();
        
        $dailyRate = $car['daily_rate'];
        $startDateTime = new DateTime($startDate);
        $endDateTime = new DateTime($endDate);
        $days = $startDateTime->diff($endDateTime)->days + 1;
        
        $baseCost = $dailyRate * $days;
        $totalCost = $baseCost - $discountAmount + $additionalCharges;
        
        // Apply promotion discount if selected
        if ($promotionId) {
            $promoSql = "SELECT discount_percentage, discount_amount FROM promotions WHERE promotion_id = ?";
            $promoStmt = $this->db->prepare($promoSql);
            $promoStmt->bind_param("i", $promotionId);
            $promoStmt->execute();
            $promoResult = $promoStmt->get_result();
            
            if ($promoResult->num_rows > 0) {
                $promo = $promoResult->fetch_assoc();
                
                if ($promo['discount_percentage']) {
                    $promoDiscount = $baseCost * ($promo['discount_percentage'] / 100);
                    $totalCost -= $promoDiscount;
                } elseif ($promo['discount_amount']) {
                    $totalCost -= $promo['discount_amount'];
                }
            }
        }
        
        // Ensure total cost is not negative
        $totalCost = max(0, $totalCost);
        
        // Update rental
        $sql = "UPDATE rentals 
                SET user_id = ?, car_id = ?, start_date = ?, end_date = ?, status = ?, 
                    promotion_id = ?, discount_amount = ?, additional_charges = ?, 
                    pickup_location = ?, return_location = ?, notes = ?, total_cost = ? 
                WHERE rental_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "iisssiddssdi", 
            $userId, $carId, $startDate, $endDate, $status, 
            $promotionId, $discountAmount, $additionalCharges, 
            $pickupLocation, $returnLocation, $notes, $totalCost, 
            $rentalId
        );
        
        $result = $stmt->execute();
        
        if ($result) {
            // Update car status based on rental status
            if ($status == 'active') {
                $updateCarSql = "UPDATE cars SET status = 'rented' WHERE car_id = ?";
                $updateCarStmt = $this->db->prepare($updateCarSql);
                $updateCarStmt->bind_param("i", $carId);
                $updateCarStmt->execute();
            } elseif ($status == 'completed' || $status == 'cancelled') {
                // Check if there are other active rentals for this car
                $checkSql = "SELECT COUNT(*) as count FROM rentals 
                            WHERE car_id = ? AND status = 'active' AND rental_id != ?";
                           $checkStmt = $this->db->prepare($checkSql);
                           $checkStmt->bind_param("ii", $carId, $rentalId);
                           $checkStmt->execute();
                           $checkResult = $checkStmt->get_result();
                           $activeRentals = $checkResult->fetch_assoc()['count'];
                           
                           if ($activeRentals == 0) {
                               $updateCarSql = "UPDATE cars SET status = 'available' WHERE car_id = ?";
                               $updateCarStmt = $this->db->prepare($updateCarSql);
                               $updateCarStmt->bind_param("i", $carId);
                               $updateCarStmt->execute();
                           }
                       }
                       
                       // Add to rental history if completed
                       if ($status == 'completed') {
                           $historySql = "INSERT INTO rental_history (rental_id, user_id, car_id, return_date) 
                                         VALUES (?, ?, ?, NOW())";
                           $historyStmt = $this->db->prepare($historySql);
                           $historyStmt->bind_param("iii", $rentalId, $userId, $carId);
                           $historyStmt->execute();
                       }
                       
                       $_SESSION['success'] = "Rental updated successfully.";
                   } else {
                       $_SESSION['error'] = "Failed to update rental: " . $this->db->error;
                   }
                   
                   header('Location: index.php?page=admin&action=rentals&subaction=view&id=' . $rentalId);
                   exit();
               }
               
               private function deleteRental() {
                   $rentalId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                   
                   if ($rentalId <= 0) {
                       $_SESSION['error'] = "Invalid rental ID.";
                       header('Location: index.php?page=admin&action=rentals');
                       exit();
                   }
                   
                   // Get car ID before deleting rental
                   $carSql = "SELECT car_id, status FROM rentals WHERE rental_id = ?";
                   $carStmt = $this->db->prepare($carSql);
                   $carStmt->bind_param("i", $rentalId);
                   $carStmt->execute();
                   $carResult = $carStmt->get_result();
                   
                   if ($carResult->num_rows === 0) {
                       $_SESSION['error'] = "Rental not found.";
                       header('Location: index.php?page=admin&action=rentals');
                       exit();
                   }
                   
                   $rental = $carResult->fetch_assoc();
                   $carId = $rental['car_id'];
                   $rentalStatus = $rental['status'];
                   
                   // Delete rental
                   $sql = "DELETE FROM rentals WHERE rental_id = ?";
                   $stmt = $this->db->prepare($sql);
                   $stmt->bind_param("i", $rentalId);
                   $result = $stmt->execute();
                   
                   if ($result) {
                       // If the rental was active, check if there are other active rentals for this car
                       if ($rentalStatus == 'active') {
                           $checkSql = "SELECT COUNT(*) as count FROM rentals WHERE car_id = ? AND status = 'active'";
                           $checkStmt = $this->db->prepare($checkSql);
                           $checkStmt->bind_param("i", $carId);
                           $checkStmt->execute();
                           $checkResult = $checkStmt->get_result();
                           $activeRentals = $checkResult->fetch_assoc()['count'];
                           
                           if ($activeRentals == 0) {
                               $updateCarSql = "UPDATE cars SET status = 'available' WHERE car_id = ?";
                               $updateCarStmt = $this->db->prepare($updateCarSql);
                               $updateCarStmt->bind_param("i", $carId);
                               $updateCarStmt->execute();
                           }
                       }
                       
                       $_SESSION['success'] = "Rental deleted successfully.";
                   } else {
                       $_SESSION['error'] = "Failed to delete rental: " . $this->db->error;
                   }
                   
                   header('Location: index.php?page=admin&action=rentals');
                   exit();
               }
               
               private function approveRental() {
                   $rentalId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                   
                   if ($rentalId <= 0) {
                       $_SESSION['error'] = "Invalid rental ID.";
                       header('Location: index.php?page=admin&action=rentals');
                       exit();
                   }
                   
                   // Get rental details
                   $rentalSql = "SELECT car_id FROM rentals WHERE rental_id = ? AND status = 'pending'";
                   $rentalStmt = $this->db->prepare($rentalSql);
                   $rentalStmt->bind_param("i", $rentalId);
                   $rentalStmt->execute();
                   $rentalResult = $rentalStmt->get_result();
                   
                   if ($rentalResult->num_rows === 0) {
                       $_SESSION['error'] = "Rental not found or already processed.";
                       header('Location: index.php?page=admin&action=rentals');
                       exit();
                   }
                   
                   $rental = $rentalResult->fetch_assoc();
                   $carId = $rental['car_id'];
                   
                   // Update rental status
                   $updateSql = "UPDATE rentals SET status = 'approved' WHERE rental_id = ?";
                   $updateStmt = $this->db->prepare($updateSql);
                   $updateStmt->bind_param("i", $rentalId);
                   $result = $updateStmt->execute();
                   
                   if ($result) {
                       $_SESSION['success'] = "Rental approved successfully.";
                   } else {
                       $_SESSION['error'] = "Failed to approve rental: " . $this->db->error;
                   }
                   
                   header('Location: index.php?page=admin&action=rentals&subaction=view&id=' . $rentalId);
                   exit();
               }
               
               private function completeRental() {
                   $rentalId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                   
                   if ($rentalId <= 0) {
                       $_SESSION['error'] = "Invalid rental ID.";
                       header('Location: index.php?page=admin&action=rentals');
                       exit();
                   }
                   
                   // Get rental details
                   $rentalSql = "SELECT r.car_id, r.user_id, r.status 
                                FROM rentals r
                                WHERE r.rental_id = ? AND r.status IN ('approved', 'active')";
                   $rentalStmt = $this->db->prepare($rentalSql);
                   $rentalStmt->bind_param("i", $rentalId);
                   $rentalStmt->execute();
                   $rentalResult = $rentalStmt->get_result();
                   
                   if ($rentalResult->num_rows === 0) {
                       $_SESSION['error'] = "Rental not found or cannot be completed.";
                       header('Location: index.php?page=admin&action=rentals');
                       exit();
                   }
                   
                   $rental = $rentalResult->fetch_assoc();
                   $carId = $rental['car_id'];
                   $userId = $rental['user_id'];
                   
                   // Start transaction
                   $this->db->begin_transaction();
                   
                   try {
                       // Update rental status
                       $updateSql = "UPDATE rentals SET status = 'completed' WHERE rental_id = ?";
                       $updateStmt = $this->db->prepare($updateSql);
                       $updateStmt->bind_param("i", $rentalId);
                       $updateStmt->execute();
                       
                       // Check if there are other active rentals for this car
                       $checkSql = "SELECT COUNT(*) as count FROM rentals 
                                   WHERE car_id = ? AND status = 'active' AND rental_id != ?";
                       $checkStmt = $this->db->prepare($checkSql);
                       $checkStmt->bind_param("ii", $carId, $rentalId);
                       $checkStmt->execute();
                       $checkResult = $checkStmt->get_result();
                       $activeRentals = $checkResult->fetch_assoc()['count'];
                       
                       if ($activeRentals == 0) {
                           $updateCarSql = "UPDATE cars SET status = 'available' WHERE car_id = ?";
                           $updateCarStmt = $this->db->prepare($updateCarSql);
                           $updateCarStmt->bind_param("i", $carId);
                           $updateCarStmt->execute();
                       }
                       
                       // Add to rental history
                       $historySql = "INSERT INTO rental_history (rental_id, user_id, car_id, return_date) 
                                     VALUES (?, ?, ?, NOW())";
                       $historyStmt = $this->db->prepare($historySql);
                       $historyStmt->bind_param("iii", $rentalId, $userId, $carId);
                       $historyStmt->execute();
                       
                       // Commit transaction
                       $this->db->commit();
                       
                       $_SESSION['success'] = "Rental marked as completed successfully.";
                   } catch (Exception $e) {
                       // Rollback transaction on error
                       $this->db->rollback();
                       $_SESSION['error'] = "Failed to complete rental: " . $e->getMessage();
                   }
                   
                   header('Location: index.php?page=admin&action=rentals&subaction=view&id=' . $rentalId);
                   exit();
               }
               
               private function cancelRental() {
                   $rentalId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                   
                   if ($rentalId <= 0) {
                       $_SESSION['error'] = "Invalid rental ID.";
                       header('Location: index.php?page=admin&action=rentals');
                       exit();
                   }
                   
                   // Get rental details
                   $rentalSql = "SELECT car_id, status FROM rentals WHERE rental_id = ?";
                   $rentalStmt = $this->db->prepare($rentalSql);
                   $rentalStmt->bind_param("i", $rentalId);
                   $rentalStmt->execute();
                   $rentalResult = $rentalStmt->get_result();
                   
                   if ($rentalResult->num_rows === 0) {
                       $_SESSION['error'] = "Rental not found.";
                       header('Location: index.php?page=admin&action=rentals');
                       exit();
                   }
                   
                   $rental = $rentalResult->fetch_assoc();
                   $carId = $rental['car_id'];
                   $rentalStatus = $rental['status'];
                   
                   // Update rental status
                   $updateSql = "UPDATE rentals SET status = 'cancelled' WHERE rental_id = ?";
                   $updateStmt = $this->db->prepare($updateSql);
                   $updateStmt->bind_param("i", $rentalId);
                   $result = $updateStmt->execute();
                   
                   if ($result) {
                       // If the rental was active, check if there are other active rentals for this car
                       if ($rentalStatus == 'active') {
                           $checkSql = "SELECT COUNT(*) as count FROM rentals WHERE car_id = ? AND status = 'active' AND rental_id != ?";
                           $checkStmt = $this->db->prepare($checkSql);
                           $checkStmt->bind_param("ii", $carId, $rentalId);
                           $checkStmt->execute();
                           $checkResult = $checkStmt->get_result();
                           $activeRentals = $checkResult->fetch_assoc()['count'];
                           
                           if ($activeRentals == 0) {
                               $updateCarSql = "UPDATE cars SET status = 'available' WHERE car_id = ?";
                               $updateCarStmt = $this->db->prepare($updateCarSql);
                               $updateCarStmt->bind_param("i", $carId);
                               $updateCarStmt->execute();
                           }
                       }
                       
                       $_SESSION['success'] = "Rental cancelled successfully.";
                   } else {
                       $_SESSION['error'] = "Failed to cancel rental: " . $this->db->error;
                   }
                   
                   header('Location: index.php?page=admin&action=rentals&subaction=view&id=' . $rentalId);
                   exit();
               }
}
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
            // case 'users':
            //     $this->manageUsers();
            //     break;
            // case 'cars':
            //     $this->manageCars();
            //     break;
            // case 'rentals':
            //     $this->manageRentals();
            //     break;
            // case 'promotions':
            //     $this->managePromotions();
            //     break;
            // case 'maintenance':
            //     $this->manageMaintenance();
            //     break;
            // case 'settings':
            //     $this->manageSettings();
            //     break;
            // case 'reports':
            //     $this->showReports();
                // break;
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
    
    // Other methods for user, car, rental, promotion management will be added later
}
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
        }
    }
    
    private function listRentals() {
        $userId = $_SESSION['user_id'];
        $rentals = $this->rental->getUserRentals($userId);
        require 'views/rentals/list.php';
    }
    
    private function createRental() {
        $userId = $_SESSION['user_id'];
        $carId = $_POST['car_id'];
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
        
        if ($this->rental->createRental($userId, $carId, $startDate, $endDate)) {
            header('Location: index.php?page=rentals&status=success');
        } else {
            header('Location: index.php?page=rentals&status=error');
        }
    }
    
    private function showRentalForm() {
        $carId = $_GET['car_id'];
        require 'views/rentals/create.php';
    }
    
    private function viewRental() {
        $rentalId = $_GET['id'];
        $rental = $this->rental->getRental($rentalId);
        require 'views/rentals/view.php';
    }
}

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
        }
    }
    
    private function listCars() {
        $cars = $this->car->getAllCars();
        require 'views/cars/list.php';
    }

    private function viewCar() {
        $car_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $car = $this->car->getCarById($car_id);
        $dynamicPrice = $this->car->getDynamicPrice($car_id);
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
}

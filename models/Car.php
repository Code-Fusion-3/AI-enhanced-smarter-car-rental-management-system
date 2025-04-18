<?php
class Car {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAllCars() {
        $sql = "SELECT * FROM cars WHERE status = 'available'";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getBasePrice($car_id) {
        $sql = "SELECT daily_rate FROM cars WHERE car_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $car_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['daily_rate'];
    }
    
    public function calculateSeasonalMultiplier() {
        $month = date('n');
        // Peak season (summer months)
        if ($month >= 6 && $month <= 8) {
            return 1.3;
        }
        // Holiday season
        if ($month == 12 || $month == 1) {
            return 1.2;
        }
        // Off-peak season
        return 1.0;
    }
    
    public function getDynamicPrice($car_id) {
        $base_price = $this->getBasePrice($car_id);
        $demand_multiplier = $this->calculateDemandMultiplier();
        $seasonal_multiplier = $this->calculateSeasonalMultiplier();
        
        return $base_price * $demand_multiplier * $seasonal_multiplier;
    }
    
    private function calculateDemandMultiplier() {
        $sql = "SELECT COUNT(*) as active_rentals FROM rentals WHERE status = 'active'";
        $result = $this->db->query($sql)->fetch_assoc();
        
        $active_rentals = $result['active_rentals'];
        return 1 + ($active_rentals / 100);
    }
    public function getCarById($car_id) {
        $sql = "SELECT * FROM cars WHERE car_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $car_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
}

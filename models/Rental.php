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
        $sql = "SELECT r.*, c.make, c.model, c.registration_number, u.full_name 
                FROM rentals r 
                JOIN cars c ON r.car_id = c.car_id 
                JOIN users u ON r.user_id = u.user_id 
                WHERE r.rental_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $rentalId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function createRental($userId, $carId, $startDate, $endDate) {
        $car = new Car($this->db);
        $price = $car->getDynamicPrice($carId);
        $days = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24);
        $totalCost = $price * $days;
        
        $sql = "INSERT INTO rentals (user_id, car_id, start_date, end_date, total_cost) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iissd", $userId, $carId, $startDate, $endDate, $totalCost);
        return $stmt->execute();
    }
}

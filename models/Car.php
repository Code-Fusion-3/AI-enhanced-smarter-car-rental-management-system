<?php
class Car {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
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
    
   
    
    private function calculateDemandMultiplier() {
        $sql = "SELECT COUNT(*) as active_rentals FROM rentals WHERE status = 'active'";
        $result = $this->db->query($sql)->fetch_assoc();
        
        $active_rentals = $result['active_rentals'];
        return 1 + ($active_rentals / 100);
    }
    /**
     * Get all cars with optional filtering and pagination
     */
    public function getAllCars($search = '', $category = '', $status = '', $limit = null, $offset = null) {
        $sql = "SELECT c.*, cc.name as category_name 
                FROM cars c 
                LEFT JOIN car_categories cc ON c.category_id = cc.category_id 
                WHERE 1=1";
        $params = [];
        $types = "";
        
        if (!empty($search)) {
            $sql .= " AND (c.make LIKE ? OR c.model LIKE ? OR c.registration_number LIKE ?)";
            $searchParam = "%$search%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
            $types .= "sss";
        }
        
        if (!empty($category)) {
            $sql .= " AND c.category_id = ?";
            $params[] = $category;
            $types .= "i";
        }
        
        if (!empty($status)) {
            $sql .= " AND c.status = ?";
            $params[] = $status;
            $types .= "s";
        }
        
        $sql .= " ORDER BY c.car_id DESC";
        
        if ($limit !== null && $offset !== null) {
            $sql .= " LIMIT ?, ?";
            $params[] = $offset;
            $params[] = $limit;
            $types .= "ii";
        }
        
        $stmt = $this->db->prepare($sql);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $cars = [];
        while ($row = $result->fetch_assoc()) {
            $cars[] = $row;
        }
        
        return $cars;
    }
    
    /**
     * Count total cars with optional filtering
     */
    public function countCars($search = '', $category = '', $status = '') {
        $sql = "SELECT COUNT(*) as total FROM cars c WHERE 1=1";
        $params = [];
        $types = "";
        
        if (!empty($search)) {
            $sql .= " AND (c.make LIKE ? OR c.model LIKE ? OR c.registration_number LIKE ?)";
            $searchParam = "%$search%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
            $types .= "sss";
        }
        
        if (!empty($category)) {
            $sql .= " AND c.category_id = ?";
            $params[] = $category;
            $types .= "i";
        }
        
        if (!empty($status)) {
            $sql .= " AND c.status = ?";
            $params[] = $status;
            $types .= "s";
        }
        
        $stmt = $this->db->prepare($sql);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['total'];
    }
    
    /**
     * Get a car by ID
     */
    public function getCarById($carId) {
        $sql = "SELECT * FROM cars WHERE car_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $carId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        return $result->fetch_assoc();
    }
    
    /**
     * Add a new car
     */
    public function addCar($carData) {
        $sql = "INSERT INTO cars (
                    make, 
                    model, 
                    year, 
                    registration_number, 
                    daily_rate, 
                    status, 
                    image_url, 
                    features, 
                    category_id, 
                    mileage, 
                    fuel_type, 
                    transmission, 
                    seats, 
                    base_rate, 
                    weekend_rate, 
                    weekly_rate, 
                    monthly_rate
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "ssisssssiissidddd",
            $carData['make'],
            $carData['model'],
            $carData['year'],
            $carData['registration_number'],
            $carData['daily_rate'],
            $carData['status'],
            $carData['image_url'],
            $carData['features'],
            $carData['category_id'],
            $carData['mileage'],
            $carData['fuel_type'],
            $carData['transmission'],
            $carData['seats'],
            $carData['base_rate'],
            $carData['weekend_rate'],
            $carData['weekly_rate'],
            $carData['monthly_rate']
        );
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        
        return false;
    }
    
    /**
     * Update an existing car
     */
    public function updateCar($carId, $carData) {
        $sql = "UPDATE cars SET 
                make = ?, 
                model = ?, 
                year = ?, 
                registration_number = ?, 
                daily_rate = ?, 
                status = ?, 
                features = ?, 
                category_id = ?, 
                mileage = ?, 
                fuel_type = ?, 
                transmission = ?, 
                seats = ?, 
                base_rate = ?, 
                weekend_rate = ?, 
                weekly_rate = ?, 
                monthly_rate = ?";
        
        $params = [
            $carData['make'],
            $carData['model'],
            $carData['year'],
            $carData['registration_number'],
            $carData['daily_rate'],
            $carData['status'],
            $carData['features'],
            $carData['category_id'],
            $carData['mileage'],
            $carData['fuel_type'],
            $carData['transmission'],
            $carData['seats'],
            $carData['base_rate'],
            $carData['weekend_rate'],
            $carData['weekly_rate'],
            $carData['monthly_rate']
        ];
        
        $types = "ssisssssissidddd";
        
        // Add image_url if it exists
        if (isset($carData['image_url'])) {
            $sql .= ", image_url = ?";
            $params[] = $carData['image_url'];
            $types .= "s";
        }
        
        // Add WHERE clause
        $sql .= " WHERE car_id = ?";
        $params[] = $carId;
        $types .= "i";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        
        return $stmt->execute();
    }
    
    /**
     * Delete a car
     */
    public function deleteCar($carId) {
        // First check if car has any rentals
        $sql = "SELECT COUNT(*) as count FROM rentals WHERE car_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $carId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            // Car has rentals, cannot delete
            return false;
        }
        
        // Delete car
        $sql = "DELETE FROM cars WHERE car_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $carId);
        
        return $stmt->execute();
    }
    
    /**
     * Get all car categories
     */
    public function getAllCategories() {
        $sql = "SELECT * FROM car_categories ORDER BY name ASC";
        $result = $this->db->query($sql);
        
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        
        return $categories;
    }
    
    /**
     * Get available cars (for rental)
     */
    public function getAvailableCars($startDate, $endDate, $categoryId = null) {
        $sql = "SELECT c.* FROM cars c 
                WHERE c.status = 'available' 
                AND c.car_id NOT IN (
                    SELECT r.car_id FROM rentals r 
                    WHERE r.status IN ('approved', 'active') 
                    AND (
                        (r.start_date <= ? AND r.end_date >= ?) OR 
                        (r.start_date <= ? AND r.end_date >= ?) OR 
                        (r.start_date >= ? AND r.end_date <= ?)
                    )
                )";
        
        $params = [$endDate, $startDate, $startDate, $startDate, $startDate, $endDate];
        $types = "ssssss";
        
        if ($categoryId !== null) {
            $sql .= " AND c.category_id = ?";
            $params[] = $categoryId;
            $types .= "i";
        }
        
        $sql .= " ORDER BY c.daily_rate ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $cars = [];
        while ($row = $result->fetch_assoc()) {
            $cars[] = $row;
        }
        
        return $cars;
    }
    
    /**
     * Calculate dynamic price based on demand and other factors
     */
    public function getDynamicPrice($carId, $startDate = null, $endDate = null) {
        // Get base car information
        $car = $this->getCarById($carId);
        if (!$car) {
            return null;
        }
        
        $baseRate = $car['daily_rate'];
        
        // If no dates provided, return current price
        if ($startDate === null || $endDate === null) {
            return [
                'base_rate' => $baseRate,
                'dynamic_rate' => $baseRate,
                'discount' => 0,
                'final_rate' => $baseRate
            ];
        }
        
        // Convert dates to DateTime objects
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $interval = $start->diff($end);
        $days = $interval->days + 1; // Include both start and end days
        
        // Apply weekly discount if rental is for 7+ days
        if ($days >= 7 && $days < 30 && $car['weekly_rate']) {
            $dailyRate = $car['weekly_rate'] / 7;
        } 
        // Apply monthly discount if rental is for 30+ days
        elseif ($days >= 30 && $car['monthly_rate']) {
            $dailyRate = $car['monthly_rate'] / 30;
        }
        // Apply weekend rate for weekends
        elseif ($this->isWeekend($startDate, $endDate) && $car['weekend_rate']) {
            $dailyRate = $car['weekend_rate'];
        }
        // Otherwise use base rate
        else {
            $dailyRate = $baseRate;
        }
        
        // Check demand for this period
        $demandFactor = $this->calculateDemandFactor($startDate, $endDate);
        
        // Apply demand-based adjustment (up to 20% increase or decrease)
        $dynamicRate = $dailyRate * (1 + ($demandFactor * 0.2));
        
        // Calculate discount percentage
        $discount = ($baseRate > 0) ? (($baseRate - $dynamicRate) / $baseRate) * 100 : 0;
        
        return [
            'base_rate' => $baseRate,
            'dynamic_rate' => $dynamicRate,
            'discount' => $discount,
            'final_rate' => $dynamicRate,
            'total' => $dynamicRate * $days
        ];
    }
    
    /**
     * Check if rental period includes weekend
     */
    private function isWeekend($startDate, $endDate) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($start, $interval, $end);
        
        foreach ($dateRange as $date) {
            $dayOfWeek = $date->format('N');
            // 6 = Saturday, 7 = Sunday
            if ($dayOfWeek >= 6) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Calculate demand factor based on existing rentals for the period
     * Returns a value between -1 (low demand) and 1 (high demand)
     */
    private function calculateDemandFactor($startDate, $endDate) {
        // Count total available cars
        $totalCarsQuery = "SELECT COUNT(*) as total FROM cars WHERE status = 'available'";
        $result = $this->db->query($totalCarsQuery);
        $row = $result->fetch_assoc();
        $totalCars = $row['total'];
        
        if ($totalCars === 0) {
            return 0;
        }
        
        // Count cars already rented during this period
        $rentedCarsQuery = "SELECT COUNT(DISTINCT car_id) as rented FROM rentals 
                           WHERE status IN ('approved', 'active') 
                           AND (
                               (start_date <= ? AND end_date >= ?) OR 
                               (start_date <= ? AND end_date >= ?) OR 
                               (start_date >= ? AND end_date <= ?)
                           )";
        
        $stmt = $this->db->prepare($rentedCarsQuery);
        $stmt->bind_param("ssssss", $endDate, $startDate, $startDate, $startDate, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $rentedCars = $row['rented'];
        
        // Calculate occupancy rate (0 to 1)
        $occupancyRate = $rentedCars / $totalCars;
        
        // Convert to demand factor (-1 to 1)
        // Below 0.3 occupancy: negative factor (discount)
        // Above 0.7 occupancy: positive factor (premium)
        // Between 0.3 and 0.7: proportional factor
        if ($occupancyRate < 0.3) {
            return -1 * (0.3 - $occupancyRate) / 0.3; // Scale from 0 to -1
        } elseif ($occupancyRate > 0.7) {
            return (($occupancyRate - 0.7) / 0.3); // Scale from 0 to 1
        } else {
            // Linear scaling between -0.3 and 0.7 to a factor between -0.1 and 0.1
            return (($occupancyRate - 0.3) / 0.4) * 2 - 1;
        }
    }
}

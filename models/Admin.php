<?php
class Admin {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getSystemStatistics() {
        $stats = [];
        
        // Total users
        $sql = "SELECT COUNT(*) as total FROM users";
        $result = $this->db->query($sql);
        $stats['totalUsers'] = $result->fetch_assoc()['total'];
        
        // Total cars
        $sql = "SELECT COUNT(*) as total FROM cars";
        $result = $this->db->query($sql);
        $stats['totalCars'] = $result->fetch_assoc()['total'];
        
        // Available cars
        $sql = "SELECT COUNT(*) as total FROM cars WHERE status = 'available'";
        $result = $this->db->query($sql);
        $stats['availableCars'] = $result->fetch_assoc()['total'];
        
        // Rented cars
        $sql = "SELECT COUNT(*) as total FROM cars WHERE status = 'rented'";
        $result = $this->db->query($sql);
        $stats['rentedCars'] = $result->fetch_assoc()['total'];
        
        // Cars in maintenance
        $sql = "SELECT COUNT(*) as total FROM cars WHERE status = 'maintenance'";
        $result = $this->db->query($sql);
        $stats['maintenanceCars'] = $result->fetch_assoc()['total'];
        
        // Total rentals
        $sql = "SELECT COUNT(*) as total FROM rentals";
        $result = $this->db->query($sql);
        $stats['totalRentals'] = $result->fetch_assoc()['total'];
        
        // Active rentals
        $sql = "SELECT COUNT(*) as total FROM rentals WHERE status = 'active'";
        $result = $this->db->query($sql);
        $stats['activeRentals'] = $result->fetch_assoc()['total'];
        
        // Pending rentals
        $sql = "SELECT COUNT(*) as total FROM rentals WHERE status = 'pending'";
        $result = $this->db->query($sql);
        $stats['pendingRentals'] = $result->fetch_assoc()['total'];
        
        // Completed rentals
        $sql = "SELECT COUNT(*) as total FROM rentals WHERE status = 'completed'";
        $result = $this->db->query($sql);
        $stats['completedRentals'] = $result->fetch_assoc()['total'];
        
        // Total revenue
        $sql = "SELECT SUM(total_cost) as total FROM rentals WHERE status IN ('completed', 'active')";
        $result = $this->db->query($sql);
        $stats['totalRevenue'] = $result->fetch_assoc()['total'] ?? 0;
        
        // Revenue this month
        $sql = "SELECT SUM(total_cost) as total FROM rentals 
                WHERE status IN ('completed', 'active') 
                AND MONTH(created_at) = MONTH(CURRENT_DATE()) 
                AND YEAR(created_at) = YEAR(CURRENT_DATE())";
        $result = $this->db->query($sql);
        $stats['monthlyRevenue'] = $result->fetch_assoc()['total'] ?? 0;
        
        // Revenue this week
        $sql = "SELECT SUM(total_cost) as total FROM rentals 
                WHERE status IN ('completed', 'active') 
                AND YEARWEEK(created_at) = YEARWEEK(CURRENT_DATE())";
        $result = $this->db->query($sql);
        $stats['weeklyRevenue'] = $result->fetch_assoc()['total'] ?? 0;
        
        return $stats;
    }
    
    public function getRecentActivities($limit = 10) {
        $sql = "SELECT sl.*, u.username, u.full_name 
                FROM system_logs sl
                LEFT JOIN users u ON sl.user_id = u.user_id
                ORDER BY sl.created_at DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $activities = [];
        while ($row = $result->fetch_assoc()) {
            $activities[] = $row;
        }
        
        return $activities;
    }
    
    public function getRevenueMetrics() {
        $metrics = [];
        
        // Monthly revenue for the past 6 months
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    SUM(total_cost) as revenue
                FROM rentals
                WHERE status IN ('completed', 'active')
                AND created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month ASC";
        
        $result = $this->db->query($sql);
        $monthlyRevenue = [];
        $months = [];
        $revenues = [];
        
        while ($row = $result->fetch_assoc()) {
            $monthlyRevenue[] = $row;
            $months[] = date('M Y', strtotime($row['month'] . '-01'));
            $revenues[] = $row['revenue'];
        }
        
        $metrics['monthlyRevenue'] = $monthlyRevenue;
        $metrics['months'] = $months;
        $metrics['revenues'] = $revenues;
        
        // Revenue by car category
        $sql = "SELECT 
                    cc.name as category,
                    SUM(r.total_cost) as revenue
                FROM rentals r
                JOIN cars c ON r.car_id = c.car_id
                JOIN car_categories cc ON c.category_id = cc.category_id
                WHERE r.status IN ('completed', 'active')
                GROUP BY cc.name
                ORDER BY revenue DESC";
        
        $result = $this->db->query($sql);
        $categoryRevenue = [];
        
        while ($row = $result->fetch_assoc()) {
            $categoryRevenue[] = $row;
        }
        
        $metrics['categoryRevenue'] = $categoryRevenue;
        
        return $metrics;
    }
    
    public function getFleetUtilization() {
        $utilization = [];
        
        // Overall fleet status
        $sql = "SELECT 
                    status,
                    COUNT(*) as count
                FROM cars
                GROUP BY status";
        
        $result = $this->db->query($sql);
        $statusCounts = [];
        
        while ($row = $result->fetch_assoc()) {
            $statusCounts[$row['status']] = $row['count'];
        }
        
        $utilization['statusCounts'] = $statusCounts;
        
        // Utilization by category
        $sql = "SELECT 
                    cc.name as category,
                    c.status,
                    COUNT(*) as count
                FROM cars c
                JOIN car_categories cc ON c.category_id = cc.category_id
                GROUP BY cc.name, c.status
                ORDER BY cc.name, c.status";
        
        $result = $this->db->query($sql);
        $categoryUtilization = [];
        
        while ($row = $result->fetch_assoc()) {
            if (!isset($categoryUtilization[$row['category']])) {
                $categoryUtilization[$row['category']] = [
                    'available' => 0,
                    'rented' => 0,
                    'maintenance' => 0
                ];
            }
            $categoryUtilization[$row['category']][$row['status']] = $row['count'];
        }
        
        $utilization['categoryUtilization'] = $categoryUtilization;
        
        // Most rented cars
        $sql = "SELECT 
                    c.car_id,
                    c.make,
                    c.model,
                    c.year,
                    COUNT(r.rental_id) as rental_count
                FROM cars c
                JOIN rentals r ON c.car_id = r.car_id
                GROUP BY c.car_id
                ORDER BY rental_count DESC
                LIMIT 5";
        
        $result = $this->db->query($sql);
        $mostRented = [];
        
        while ($row = $result->fetch_assoc()) {
            $mostRented[] = $row;
        }
        
        $utilization['mostRented'] = $mostRented;
        
        return $utilization;
    }
    
    public function getTopPerformingCars($limit = 5) {
        $sql = "SELECT 
                    c.car_id,
                    c.make,
                    c.model,
                    c.year,
                    c.image_url,
                    COUNT(r.rental_id) as rental_count,
                    SUM(r.total_cost) as revenue,
                    AVG(rh.rating) as avg_rating
                FROM cars c
                LEFT JOIN rentals r ON c.car_id = r.car_id
                LEFT JOIN rental_history rh ON r.rental_id = rh.rental_id
                GROUP BY c.car_id
                ORDER BY revenue DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $topCars = [];
        while ($row = $result->fetch_assoc()) {
            $topCars[] = $row;
        }
        
        return $topCars;
    }
    
    public function getRecentRentals($limit = 5) {
        $sql = "SELECT 
                    r.rental_id,
                    r.start_date,
                    r.end_date,
                    r.total_cost,
                    r.status,
                    r.created_at,
                    u.username,
                    u.full_name,
                    c.make,
                    c.model,
                    c.year
                FROM rentals r
                JOIN users u ON r.user_id = u.user_id
                JOIN cars c ON r.car_id = c.car_id
                ORDER BY r.created_at DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $recentRentals = [];
        while ($row = $result->fetch_assoc()) {
            $recentRentals[] = $row;
        }
        
        return $recentRentals;
    }
    
    public function getPendingRentals($limit = 5) {
        $sql = "SELECT 
                    r.rental_id,
                    r.start_date,
                    r.end_date,
                    r.total_cost,
                    r.created_at,
                    u.username,
                    u.full_name,
                    c.make,
                    c.model,
                    c.year
                FROM rentals r
                JOIN users u ON r.user_id = u.user_id
                JOIN cars c ON r.car_id = c.car_id
                WHERE r.status = 'pending'
                ORDER BY r.created_at ASC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $pendingRentals = [];
        while ($row = $result->fetch_assoc()) {
            $pendingRentals[] = $row;
        }
        
        return $pendingRentals;
    }
}
<?php
class Recommender {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getRecommendations($user_id) {
        $sql = "SELECT c.* FROM cars c
                INNER JOIN rental_history rh ON c.car_id = rh.car_id
                WHERE rh.rating >= 4
                AND c.status = 'available'
                GROUP BY c.car_id
                ORDER BY AVG(rh.rating) DESC
                LIMIT 5";
                
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

<?php
class User {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    public function register($username, $password, $email, $fullName) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, password, email, full_name, role) 
                    VALUES (?, ?, ?, ?, 'customer')";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ssss", $username, $hashedPassword, $email, $fullName);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            // Check for duplicate entry errors
            if ($e->getCode() == 1062) { // MySQL error code for duplicate entry
                // Determine which field caused the duplicate
                if (strpos($e->getMessage(), 'username')) {
                    $_SESSION['register_error'] = "Username already exists. Please choose a different username.";
                } else if (strpos($e->getMessage(), 'email')) {
                    $_SESSION['register_error'] = "Email address already registered. Please use a different email.";
                } else {
                    $_SESSION['register_error'] = "Registration failed due to duplicate information.";
                }
            } else {
                $_SESSION['register_error'] = "Registration failed. Please try again later.";
            }
            return false;
        }
    }
    
    
    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if ($result && password_verify($password, $result['password'])) {
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['username'] = $result['username'];
            $_SESSION['role'] = $result['role'];
            return true;
        }
        return false;
    }
    
    public function getUserById($userId) {
        $sql = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}

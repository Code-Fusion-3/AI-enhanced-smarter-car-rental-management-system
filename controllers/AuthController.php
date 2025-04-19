<?php

require_once 'models/User.php';
class AuthController {
    private $db;
    private $user;
    
    public function __construct() {
        global $conn;
        $this->db = $conn;
        $this->user = new User($conn);
    }
    
    public function handle() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'login';
        
        switch($action) {
            case 'login':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->processLogin();
                } else {
                    require 'views/auth/login.php';
                }
                break;
            case 'register':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->processRegistration();
                } else {
                    require 'views/auth/register.php';
                }
                break;
            case 'logout':
                $this->logout();
                break;
        }
    }

    private function processLogin() {
        $username = sanitize($_POST['username']);
        $password = $_POST['password'];
        
        $loginResult = $this->user->login($username, $password);
        
        if ($loginResult) {
            // Update last_login timestamp
            $this->user->updateLastLogin($_SESSION['user_id']);
            
            // Log the successful login
            logActivity($this->user->getDb(), $_SESSION['user_id'], 'login', 'User logged in successfully');
            
            // Redirect based on role
            if ($loginResult === 'admin') {
                header('Location: index.php?page=admin&action=dashboard');
            } else {
                header('Location: index.php'); // Regular users go to homepage
            }
        } else {
            header('Location: index.php?page=auth&action=login&error=1');
        }
        exit();
    }
    
    
    private function processRegistration() {
        $username = sanitize($_POST['username']);
        $password = $_POST['password'];
        $email = sanitize($_POST['email']);
        $fullName = sanitize($_POST['full_name']);
        
        // Basic validation
        if (empty($username) || empty($password) || empty($email) || empty($fullName)) {
            $_SESSION['register_error'] = "All fields are required.";
            header('Location: index.php?page=auth&action=register&error=1');
            exit();
        }
        
        // Password length validation
        if (strlen($password) < 8) {
            $_SESSION['register_error'] = "Password must be at least 8 characters long.";
            header('Location: index.php?page=auth&action=register&error=1');
            exit();
        }
        
        // Email validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['register_error'] = "Please enter a valid email address.";
            header('Location: index.php?page=auth&action=register&error=1');
            exit();
        }
        
        if ($this->user->register($username, $password, $email, $fullName)) {
            header('Location: index.php?page=auth&action=login&registered=1');
        } else {
            // The specific error message is already set in the User model
            header('Location: index.php?page=auth&action=register&error=1');
        }
        exit();
    }
    
    
    private function logout() {
        session_destroy();
        header('Location: index.php');
        exit();
    }
}
